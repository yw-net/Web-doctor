<?php


namespace app\controller;


use app\model\Referral;
use diversen\sendfile;
use think\Db;
use think\facade\Env;

class ReferralSave extends Base
{
    //保存文件路径函数
    private function saveFileUrl($newUrl,$fileId,$patients_id,$dbName,$idName,$addressName)
    {
        $get = Db::name($dbName)->where([$idName=>$fileId,'patients_id'=>$patients_id])->find();

        //未保存则创建新数据
        if (!isset($get)){
            Db::name($dbName)
                ->insert([
                    $idName=>$fileId,
                    'patients_id'=>$patients_id,
                    'created_time'=>date('Y-m-d'),
                ]);
        }
        //判断数据库文件地址是否为空
        if ($get[$addressName] == null){
            //转换为json格式写入数据库
            $newUrl = json_encode($newUrl);
            Db::name($dbName)
                ->data($addressName,$newUrl)
                ->where([$idName=>$fileId,'patients_id'=>$patients_id])
                ->update();
        }
        else
        {
            $array_img_address= json_decode($get[$addressName]);//取出数据库原有json数据

            //判断原有数据是否为数组(单个数据默认为字符串，需转换为数据)
            if (is_array($array_img_address)){
                array_push($array_img_address,$newUrl);//组合新数组（多条数据）
            }
            else{
                $array_img_address = array($array_img_address);//把数据库原有数据转换为数组
                array_push($array_img_address,$newUrl);//组合新数组（单条数据）
            }

            Db::name($dbName)
                ->data($addressName,$array_img_address)
                ->where([$idName=>$fileId,'patients_id'=>$patients_id])
                ->update();
        }

    }

    //删除文件路径函数
    private function delFileUrl($form_id,$patients_id,$idName,$dbName,$folderName,$file_name,$addressName){
        $get = Db::name($dbName)
            ->where([$idName=>$form_id,'patients_id'=>$patients_id])
            ->find();

        $get_address = json_decode($get[$addressName]); //取出json数据并转换为数组
        //判断是否为字符串(多条数据)
        if (gettype($get_address)!='string'){

            foreach ($get_address as $key=>$value)
            {
                if ($value == $file_name)
                    array_splice($get_address,$key,1);   //删除数组对应的值
            }

            //把删除后的数组重新写入数据库
            $res = Db::name($dbName)
                ->data($addressName,$get_address)
                ->where([$idName=>$form_id,'patients_id'=>$patients_id])
                ->update();

            if($res){
                //删除对应文件
                $savePath = Env::get('root_path').'storge/referral/'.$folderName.'/';//图片保存根目录
                $filePath = $savePath.$file_name;
                unlink($filePath);
            }
        }
        //单条数据直接删除
        if (gettype($get_address) == 'string'){
            $res = Db::name($dbName)
                ->where([$idName=>$form_id,'patients_id'=>$patients_id])
                ->update([$addressName=>[]]);
            if($res){
                //删除对应文件
                $savePath = Env::get('root_path').'storge/referral/'.$folderName.'/';//图片保存根目录
                $filePath = $savePath.$file_name;
                unlink($filePath);

            }
        }
    }

    //获取文件信息函数
    /**
     * @param $file_path
     * @param $res
     * @param $type
     */
    private function getFileInfoArr($file_path,$res,$type){//数组
        $res_info = [];
        foreach ($res['img_address'] as $key=>$value)
        {
            $imgInfo = filesize($file_path.$value); //获取文件大小

            $file_name = explode('/',$value);   //截取字符串
            array_push($res_info,['caption'=>$file_name[1],'downloadUrl'=>'../reffile/'.$type.'/'.$value,'key'=>$value,'size'=>$imgInfo]);   //组合对象数组
        }
        $res['img_info'] = $res_info;
    }
    private function getFileInfoStr($file_path,$res,$type){//字符串
        $res_info = [];
        $file_name = explode('/',$res['img_address']);   //截取字符串

        $imgInfo = filesize($file_path.$res['img_address']); //获取文件大小

        array_push($res_info,['caption'=>$file_name[1],'downloadUrl'=>'../reffile/'.$type.'/'.$res['img_address'],'key'=>$res['img_address'],'size'=>$imgInfo]);   //组合对象数组
        $res['img_info'] = $res_info;
    }

    //图片显示路由
    public function imgRoute()
    {
        $this->isLogin();

        $fileType = $this->request->param('type');
        $fileDate = $this->request->param('date');
        $fileName = $this->request->param('name');

        $path = $fileDate."/".$fileName;
        $savePath = Env::get('root_path').'storge/referral/'.$fileType.'/';//图片保存根目录

        $file_path = $savePath.$path; //图片地址

        $imgInfo = getimagesize($file_path); //解析图片文件信息
        $imgType = $imgInfo[2];//判断图片文件类型

        switch ($imgType) {
            case 1: // gif
                // 采用gif方式载入
                $im = imagecreatefromgif($file_path);
                ob_start();
                imagegif($im);
                $content = ob_get_clean();
                imagedestroy($im);
                return response($content,200,['content-length'=>strlen($content)])->contentType('image/gif');
                break;
            case 2: // jpg
                // 采用jpg方式载入
                $im = imagecreatefromjpeg($file_path);
                ob_start();
                imagejpeg($im);
                $content = ob_get_clean();
                imagedestroy($im);
                return response($content,200,['content-length'=>strlen($content)])->contentType('image/jpeg');
                break;
            case 3: // png
                // 采用png方式载入
                $im = imagecreatefrompng($file_path);
                ob_start();
                imagepng($im);
                $content = ob_get_clean();
                imagedestroy($im);
                return response($content,200,['content-length'=>strlen($content)])->contentType('image/png');
                break;
            default://下载
                $s = new sendfile();
                try {
                    $s->send($file_path);
                } catch (\Exception $e) {
                    echo $e->getMessage();
                }
        }

    }




    //更新、新增复诊信息
    public function fuZhenSaveNew()
    {
        //数据处理-获取表单数据
        $patients_id = $this->request->param('patients_id');
        $form_data = $this->request->param('form_data');
        $fuzhen_id = $this->request->param('form_id');

        $fuzhen = new Referral();

        //id
        $fuzhen->patients_id=$patients_id;
        $fuzhen->fuzhen_id=$fuzhen_id;

        //判断数据库当前患者下fuzhen_id是否存在
        $fuzhen_id_res = Referral::where(['fuzhen_id'=>$fuzhen_id,'patients_id'=>$patients_id])->find();
        //更新数据
        if ($fuzhen_id_res){
            $fuzhen_id_res->edit_time = date('Y-m-d H:i:s');
            if (isset($form_data['chaosheng_zhuanyi'])) {
                //判断多选框值是否为数组（当选择两个以下强制转换为数组）
                if (!is_array($form_data['chaosheng_zhuanyi'])) {
                    $form_data['chaosheng_zhuanyi'] = json_encode($form_data['chaosheng_zhuanyi']);
                }
                $fuzhen_id_res->chaosheng_zhuanyi = $form_data['chaosheng_zhuanyi'];
            }
            if (isset($form_data['ct_zhuanyi'])) {
                //判断多选框值是否为数组（当选择两个以下强制转换为数组）
                if (!is_array($form_data['ct_zhuanyi'])) {
                    $form_data['ct_zhuanyi'] = json_encode($form_data['ct_zhuanyi']);
                }
                $fuzhen_id_res->ct_zhuanyi = $form_data['ct_zhuanyi'];
            }
            if (isset($form_data['ect_zhuanyi'])) {
                //判断多选框值是否为数组（当选择两个以下强制转换为数组）
                if (!is_array($form_data['ect_zhuanyi'])) {
                    $form_data['ect_zhuanyi'] = json_encode($form_data['ect_zhuanyi']);
                }
                $fuzhen_id_res->ect_zhuanyi = $form_data['ect_zhuanyi'];
            }
            if (isset($form_data['toulu_zhuanyi'])) {
                //判断多选框值是否为数组（当选择两个以下强制转换为数组）
                if (!is_array($form_data['toulu_zhuanyi'])) {
                    $form_data['toulu_zhuanyi'] = json_encode($form_data['toulu_zhuanyi']);
                }
                $fuzhen_id_res->toulu_zhuanyi = $form_data['toulu_zhuanyi'];
            }
            if (isset($form_data['pet_zhuanyi'])) {
                //判断多选框值是否为数组（当选择两个以下强制转换为数组）
                if (!is_array($form_data['pet_zhuanyi'])) {
                    $form_data['pet_zhuanyi'] = json_encode($form_data['pet_zhuanyi']);
                }
                $fuzhen_id_res->pet_zhuanyi = $form_data['pet_zhuanyi'];
            }
            if (isset($form_data['referral_time'])){
                $fuzhen_id_res->referral_time = $form_data['referral_time'];
            }
            if (isset($form_data['referral_zhengzhuang'])){
                $fuzhen_id_res->referral_zhengzhuang = $form_data['referral_zhengzhuang'];
            }
            if (isset($form_data['referral_tizheng'])){
                $fuzhen_id_res->referral_tizheng = $form_data['referral_tizheng'];
            }
            if (isset($form_data['xueliubiao_cea'])){
                $fuzhen_id_res->xueliubiao_cea = $form_data['xueliubiao_cea'];
            }
            if (isset($form_data['xueliubiao_cyfra211'])){
                $fuzhen_id_res->xueliubiao_cyfra211 = $form_data['xueliubiao_cyfra211'];
            }
            if (isset($form_data['xueliubiao_ca199'])){
                $fuzhen_id_res->xueliubiao_ca199 = $form_data['xueliubiao_ca199'];
            }
            if (isset($form_data['xueliubiao_nse'])){
                $fuzhen_id_res->xueliubiao_nse = $form_data['xueliubiao_nse'];
            }
            if (isset($form_data['xueliubiao_scc'])){
                $fuzhen_id_res->xueliubiao_scc = $form_data['xueliubiao_scc'];
            }
            if (isset($form_data['xueliubiao_ca125'])){
                $fuzhen_id_res->xueliubiao_ca125 = $form_data['xueliubiao_ca125'];
            }
            if (isset($form_data['chaosheng_zhuanyi_other'])){
                $fuzhen_id_res->chaosheng_zhuanyi_other = $form_data['chaosheng_zhuanyi_other'];
            }
            if (isset($form_data['ct_zhuanyi_other'])){
                $fuzhen_id_res->ct_zhuanyi_other = $form_data['ct_zhuanyi_other'];
            }
            if (isset($form_data['ect_zhuanyi_other'])){
                $fuzhen_id_res->ect_zhuanyi_other = $form_data['ect_zhuanyi_other'];
            }
            if (isset($form_data['toulu_zhuanyi_other'])){
                $fuzhen_id_res->toulu_zhuanyi_other = $form_data['toulu_zhuanyi_other'];
            }
            if (isset($form_data['pet_zhuanyi_other'])){
                $fuzhen_id_res->pet_zhuanyi_other = $form_data['pet_zhuanyi_other'];
            }
            if (isset($form_data['referral_other'])){
                $fuzhen_id_res->referral_other = $form_data['referral_other'];
            }
            $fuzhen_id_res->referral_doctor = $form_data['referral_doctor'];
            $fuzhen_id_res->save();
            $res['status'] = 1;
            $res['msg'] ='数据更新成功';
            return $res;
        }
        //新增数据
        $fuzhen->created_time = date('Y-m-d');
        if (isset($form_data['chaosheng_zhuanyi'])) {
            //判断多选框值是否为数组（当选择两个以下强制转换为数组）
            if (!is_array($form_data['chaosheng_zhuanyi'])) {
                $form_data['chaosheng_zhuanyi'] = json_encode($form_data['chaosheng_zhuanyi']);
            }
            $fuzhen->chaosheng_zhuanyi = $form_data['chaosheng_zhuanyi'];
        }
        if (isset($form_data['ct_zhuanyi'])) {
            //判断多选框值是否为数组（当选择两个以下强制转换为数组）
            if (!is_array($form_data['ct_zhuanyi'])) {
                $form_data['ct_zhuanyi'] = json_encode($form_data['ct_zhuanyi']);
            }
            $fuzhen->ct_zhuanyi = $form_data['ct_zhuanyi'];
        }
        if (isset($form_data['ect_zhuanyi'])) {
            //判断多选框值是否为数组（当选择两个以下强制转换为数组）
            if (!is_array($form_data['ect_zhuanyi'])) {
                $form_data['ect_zhuanyi'] = json_encode($form_data['ect_zhuanyi']);
            }
            $fuzhen->ect_zhuanyi = $form_data['ect_zhuanyi'];
        }
        if (isset($form_data['toulu_zhuanyi'])) {
            //判断多选框值是否为数组（当选择两个以下强制转换为数组）
            if (!is_array($form_data['toulu_zhuanyi'])) {
                $form_data['toulu_zhuanyi'] = json_encode($form_data['toulu_zhuanyi']);
            }
            $fuzhen->toulu_zhuanyi = $form_data['toulu_zhuanyi'];
        }
        if (isset($form_data['pet_zhuanyi'])) {
            //判断多选框值是否为数组（当选择两个以下强制转换为数组）
            if (!is_array($form_data['pet_zhuanyi'])) {
                $form_data['pet_zhuanyi'] = json_encode($form_data['pet_zhuanyi']);
            }
            $fuzhen->pet_zhuanyi = $form_data['pet_zhuanyi'];
        }
        if (isset($form_data['referral_time'])){
            $fuzhen->referral_time = $form_data['referral_time'];
        }
        if (isset($form_data['referral_zhengzhuang'])){
            $fuzhen->referral_zhengzhuang = $form_data['referral_zhengzhuang'];
        }
        if (isset($form_data['referral_tizheng'])){
            $fuzhen->referral_tizheng = $form_data['referral_tizheng'];
        }
        if (isset($form_data['xueliubiao_cea'])){
            $fuzhen->xueliubiao_cea = $form_data['xueliubiao_cea'];
        }
        if (isset($form_data['xueliubiao_cyfra211'])){
            $fuzhen->xueliubiao_cyfra211 = $form_data['xueliubiao_cyfra211'];
        }
        if (isset($form_data['xueliubiao_ca199'])){
            $fuzhen->xueliubiao_ca199 = $form_data['xueliubiao_ca199'];
        }
        if (isset($form_data['xueliubiao_nse'])){
            $fuzhen->xueliubiao_nse = $form_data['xueliubiao_nse'];
        }
        if (isset($form_data['xueliubiao_scc'])){
            $fuzhen->xueliubiao_scc = $form_data['xueliubiao_scc'];
        }
        if (isset($form_data['xueliubiao_ca125'])){
            $fuzhen->xueliubiao_ca125 = $form_data['xueliubiao_ca125'];
        }
        if (isset($form_data['chaosheng_zhuanyi_other'])){
            $fuzhen->chaosheng_zhuanyi_other = $form_data['chaosheng_zhuanyi_other'];
        }
        if (isset($form_data['ct_zhuanyi_other'])){
            $fuzhen->ct_zhuanyi_other = $form_data['ct_zhuanyi_other'];
        }
        if (isset($form_data['ect_zhuanyi_other'])){
            $fuzhen->ect_zhuanyi_other = $form_data['ect_zhuanyi_other'];
        }
        if (isset($form_data['toulu_zhuanyi_other'])){
            $fuzhen->toulu_zhuanyi_other = $form_data['toulu_zhuanyi_other'];
        }
        if (isset($form_data['pet_zhuanyi_other'])){
            $fuzhen->pet_zhuanyi_other = $form_data['pet_zhuanyi_other'];
        }
        if (isset($form_data['referral_other'])){
            $fuzhen->referral_other = $form_data['referral_other'];
        }
        $fuzhen->referral_doctor = $form_data['referral_doctor'];
        $fuzhen->save();
        $res['status'] = 1;
        $res['msg'] ='数据保存成功';
        return $res;
    }

    //删除复诊信息
    public function delFuZhenInfo()
    {
        $patients_id = $this->request->param('patients_id');
        $fuzhen_id = $this->request->param('form_id');
        $del = Referral::where(['fuzhen_id'=>$fuzhen_id,'patients_id'=>$patients_id])->delete();
        if ($del){
            $res['msg'] = '删除成功';
            $res['status'] = 1 ;
            return $res;
        }
        $res['msg'] = '数据库连接失败';
        $res['status'] = 0 ;
        return $res;
    }




    //保存超声图像
    public function imgChaoShenSave()
    {
        $savePath = Env::get('root_path').'storge/referral/chaoshen/'.date('Ymd');//图片保存根目录

        $info = $this->request->param();//获取附加信息

        $patients_id =$info['patients_id'];
        $form_id =$info['form_id'];


        $file = $this->request->file();//获取上传文件本体


        //文件保存
        if ($file){
            $res = $file['imgShowChaoShen'.$form_id]->move($savePath,'');
        }
        else{
            return (['status'=>0,'error'=>'文件保存失败']);
        }

        //写入数据库
        if($res)
        {
            $url = date('Ymd')."/".$res->getSaveName();

            //数据库查重
            $get = Referral::where(['fuzhen_id'=>$form_id,'patients_id'=>$patients_id])->find();

            if ($get['img_address_chaoshen']!=null){
                $get_address = json_decode($get['img_address_chaoshen']); //取出json数据并转换为数组
                if (is_array($get_address)){
                    foreach ($get_address as $key=>$value)
                    {
                        if ($value == $url)
                            return  (['status'=>0,'error'=>'文件名重复！写入数据库失败！请更改文件名称后重试！']);
                    }
                }
                if ($get_address == $url){
                    return  (['status'=>0,'error'=>'文件名重复！写入数据库失败！请更改文件名称后重试！']);
                }


            }
            //正式写入数据库
            $this->saveFileUrl($url,$form_id,$patients_id,'Referral','fuzhen_id','img_address_chaoshen');

            return  (['status'=>1,'message'=>'文件上传成功','fileid'=>$url]);
        }
        else
        {
            return  (['status'=>0,'error'=>'写入数据库失败']);
        }
    }

    //删除超声图像
    public function imgChaoShenDel()
    {
        $info = $this->request->param();//获取附加信息

        $patients_id =$info['patients_id'];
        $form_id =$info['form_id'];
        $file_name = $info['key'];
        $idName = 'fuzhen_id';
        $dbName = 'Referral';
        $folderName = 'chaoshen';

        $this->delFileUrl($form_id,$patients_id,$idName,$dbName,$folderName,$file_name,'img_address_chaoshen');

        return ['status'=>1,'message'=>'删除成功'];

    }


    //保存CT图像
    public function imgCTSave()
    {
        $savePath = Env::get('root_path').'storge/referral/ct/'.date('Ymd');//图片保存根目录

        $info = $this->request->param();//获取附加信息

        $patients_id =$info['patients_id'];
        $form_id =$info['form_id'];


        $file = $this->request->file();//获取上传文件本体


        //文件保存
        if ($file){
            $res = $file['imgShowCT'.$form_id]->move($savePath,'');
        }
        else{
            return (['status'=>0,'error'=>'文件保存失败']);
        }

        //写入数据库
        if($res)
        {
            $url = date('Ymd')."/".$res->getSaveName();

            //数据库查重
            $get = Referral::where(['fuzhen_id'=>$form_id,'patients_id'=>$patients_id])->find();

            if ($get['img_address_ct']!=null){
                $get_address = json_decode($get['img_address_ct']); //取出json数据并转换为数组
                if (is_array($get_address)){
                    foreach ($get_address as $key=>$value)
                    {
                        if ($value == $url)
                            return  (['status'=>0,'error'=>'文件名重复！写入数据库失败！请更改文件名称后重试！']);
                    }
                }
                if ($get_address == $url){
                    return  (['status'=>0,'error'=>'文件名重复！写入数据库失败！请更改文件名称后重试！']);
                }


            }
            //正式写入数据库
            $this->saveFileUrl($url,$form_id,$patients_id,'Referral','fuzhen_id','img_address_ct');

            return  (['status'=>1,'message'=>'文件上传成功','fileid'=>$url]);
        }
        else
        {
            return  (['status'=>0,'error'=>'写入数据库失败']);
        }
    }

    //删除CT图像
    public function imgCTDel()
    {
        $info = $this->request->param();//获取附加信息

        $patients_id =$info['patients_id'];
        $form_id =$info['form_id'];
        $file_name = $info['key'];
        $idName = 'fuzhen_id';
        $dbName = 'Referral';
        $folderName = 'ct';

        $this->delFileUrl($form_id,$patients_id,$idName,$dbName,$folderName,$file_name,'img_address_ct');

        return ['status'=>1,'message'=>'删除成功'];

    }


    //保存ECT图像
    public function imgECTSave()
    {
        $savePath = Env::get('root_path').'storge/referral/ect/'.date('Ymd');//图片保存根目录

        $info = $this->request->param();//获取附加信息

        $patients_id =$info['patients_id'];
        $form_id =$info['form_id'];


        $file = $this->request->file();//获取上传文件本体


        //文件保存
        if ($file){
            $res = $file['imgShowECT'.$form_id]->move($savePath,'');
        }
        else{
            return (['status'=>0,'error'=>'文件保存失败']);
        }

        //写入数据库
        if($res)
        {
            $url = date('Ymd')."/".$res->getSaveName();

            //数据库查重
            $get = Referral::where(['fuzhen_id'=>$form_id,'patients_id'=>$patients_id])->find();

            if ($get['img_address_ect']!=null){
                $get_address = json_decode($get['img_address_ect']); //取出json数据并转换为数组
                if (is_array($get_address)){
                    foreach ($get_address as $key=>$value)
                    {
                        if ($value == $url)
                            return  (['status'=>0,'error'=>'文件名重复！写入数据库失败！请更改文件名称后重试！']);
                    }
                }
                if ($get_address == $url){
                    return  (['status'=>0,'error'=>'文件名重复！写入数据库失败！请更改文件名称后重试！']);
                }


            }
            //正式写入数据库
            $this->saveFileUrl($url,$form_id,$patients_id,'Referral','fuzhen_id','img_address_ect');

            return  (['status'=>1,'message'=>'文件上传成功','fileid'=>$url]);
        }
        else
        {
            return  (['status'=>0,'error'=>'写入数据库失败']);
        }
    }

    //删除ECT图像
    public function imgECTDel()
    {
        $info = $this->request->param();//获取附加信息

        $patients_id =$info['patients_id'];
        $form_id =$info['form_id'];
        $file_name = $info['key'];
        $idName = 'fuzhen_id';
        $dbName = 'Referral';
        $folderName = 'ect';

        $this->delFileUrl($form_id,$patients_id,$idName,$dbName,$folderName,$file_name,'img_address_ect');

        return ['status'=>1,'message'=>'删除成功'];

    }


    //保存头颅图像
    public function imgTouLuSave()
    {
        $savePath = Env::get('root_path').'storge/referral/toulu/'.date('Ymd');//图片保存根目录

        $info = $this->request->param();//获取附加信息

        $patients_id =$info['patients_id'];
        $form_id =$info['form_id'];


        $file = $this->request->file();//获取上传文件本体


        //文件保存
        if ($file){
            $res = $file['imgShowTouLu'.$form_id]->move($savePath,'');
        }
        else{
            return (['status'=>0,'error'=>'文件保存失败']);
        }

        //写入数据库
        if($res)
        {
            $url = date('Ymd')."/".$res->getSaveName();

            //数据库查重
            $get = Referral::where(['fuzhen_id'=>$form_id,'patients_id'=>$patients_id])->find();

            if ($get['img_address_toulu']!=null){
                $get_address = json_decode($get['img_address_toulu']); //取出json数据并转换为数组
                if (is_array($get_address)){
                    foreach ($get_address as $key=>$value)
                    {
                        if ($value == $url)
                            return  (['status'=>0,'error'=>'文件名重复！写入数据库失败！请更改文件名称后重试！']);
                    }
                }
                if ($get_address == $url){
                    return  (['status'=>0,'error'=>'文件名重复！写入数据库失败！请更改文件名称后重试！']);
                }


            }
            //正式写入数据库
            $this->saveFileUrl($url,$form_id,$patients_id,'Referral','fuzhen_id','img_address_toulu');

            return  (['status'=>1,'message'=>'文件上传成功','fileid'=>$url]);
        }
        else
        {
            return  (['status'=>0,'error'=>'写入数据库失败']);
        }
    }

    //删除头颅图像
    public function imgTouLuDel()
    {
        $info = $this->request->param();//获取附加信息

        $patients_id =$info['patients_id'];
        $form_id =$info['form_id'];
        $file_name = $info['key'];
        $idName = 'fuzhen_id';
        $dbName = 'Referral';
        $folderName = 'toulu';

        $this->delFileUrl($form_id,$patients_id,$idName,$dbName,$folderName,$file_name,'img_address_toulu');

        return ['status'=>1,'message'=>'删除成功'];

    }


    //保存PET图像
    public function imgPETSave()
    {
        $savePath = Env::get('root_path').'storge/referral/pet/'.date('Ymd');//图片保存根目录

        $info = $this->request->param();//获取附加信息

        $patients_id =$info['patients_id'];
        $form_id =$info['form_id'];


        $file = $this->request->file();//获取上传文件本体


        //文件保存
        if ($file){
            $res = $file['imgShowPET'.$form_id]->move($savePath,'');
        }
        else{
            return (['status'=>0,'error'=>'文件保存失败']);
        }

        //写入数据库
        if($res)
        {
            $url = date('Ymd')."/".$res->getSaveName();

            //数据库查重
            $get = Referral::where(['fuzhen_id'=>$form_id,'patients_id'=>$patients_id])->find();

            if ($get['img_address_pet']!=null){
                $get_address = json_decode($get['img_address_pet']); //取出json数据并转换为数组
                if (is_array($get_address)){
                    foreach ($get_address as $key=>$value)
                    {
                        if ($value == $url)
                            return  (['status'=>0,'error'=>'文件名重复！写入数据库失败！请更改文件名称后重试！']);
                    }
                }
                if ($get_address == $url){
                    return  (['status'=>0,'error'=>'文件名重复！写入数据库失败！请更改文件名称后重试！']);
                }


            }
            //正式写入数据库
            $this->saveFileUrl($url,$form_id,$patients_id,'Referral','fuzhen_id','img_address_pet');

            return  (['status'=>1,'message'=>'文件上传成功','fileid'=>$url]);
        }
        else
        {
            return  (['status'=>0,'error'=>'写入数据库失败']);
        }
    }

    //删除PET图像
    public function imgPETDel()
    {
        $info = $this->request->param();//获取附加信息

        $patients_id =$info['patients_id'];
        $form_id =$info['form_id'];
        $file_name = $info['key'];
        $idName = 'fuzhen_id';
        $dbName = 'Referral';
        $folderName = 'pet';

        $this->delFileUrl($form_id,$patients_id,$idName,$dbName,$folderName,$file_name,'img_address_pet');

        return ['status'=>1,'message'=>'删除成功'];

    }




    //获取图像-超声预览信息
    public function getChaoShenAddress()
    {
        $file_path = Env::get('root_path').'storge/referral/chaoshen/';//图片保存根目录

        $patients_id = $this->request->param('patients_id');
        $fileId = $this->request->param('fuzhen_id');

        $res = Referral::where(['patients_id'=>$patients_id,'fuzhen_id'=>$fileId])->field('img_address_chaoshen')->find();
        $res['img_address'] = json_decode($res['img_address_chaoshen']);

        //判断数据库字段是否为数组
        if (is_array($res['img_address'])){
            $this->getFileInfoArr($file_path,$res,'chaoshen');
            return $res;
        }
        //判断数据库字段是否有数据(字符串)
        if ($res['img_address']!=null){
            $this->getFileInfoStr($file_path,$res,'chaoshen');
            return $res;
        }
        //数据库字段无数据则输出空数组
        $res['img_address'] = [];
        return $res;
    }

    //获取图像-CT预览信息
    public function getCTAddress()
    {
        $file_path = Env::get('root_path').'storge/referral/ct/';//图片保存根目录

        $patients_id = $this->request->param('patients_id');
        $fileId = $this->request->param('fuzhen_id');

        $res = Referral::where(['patients_id'=>$patients_id,'fuzhen_id'=>$fileId])->field('img_address_ct')->find();
        $res['img_address'] = json_decode($res['img_address_ct']);

        //判断数据库字段是否为数组
        if (is_array($res['img_address'])){
            $this->getFileInfoArr($file_path,$res,'ct');
            return $res;
        }
        //判断数据库字段是否有数据(字符串)
        if ($res['img_address']!=null){
            $this->getFileInfoStr($file_path,$res,'ct');
            return $res;
        }
        //数据库字段无数据则输出空数组
        $res['img_address'] = [];
        return $res;
    }

    //获取图像-ECT预览信息
    public function getECTAddress()
    {
        $file_path = Env::get('root_path').'storge/referral/ect/';//图片保存根目录

        $patients_id = $this->request->param('patients_id');
        $fileId = $this->request->param('fuzhen_id');

        $res = Referral::where(['patients_id'=>$patients_id,'fuzhen_id'=>$fileId])->field('img_address_ect')->find();
        $res['img_address'] = json_decode($res['img_address_ect']);

        //判断数据库字段是否为数组
        if (is_array($res['img_address'])){
            $this->getFileInfoArr($file_path,$res,'ect');
            return $res;
        }
        //判断数据库字段是否有数据(字符串)
        if ($res['img_address']!=null){
            $this->getFileInfoStr($file_path,$res,'ect');
            return $res;
        }
        //数据库字段无数据则输出空数组
        $res['img_address'] = [];
        return $res;
    }

    //获取图像-头颅预览信息
    public function getTouLuAddress()
    {
        $file_path = Env::get('root_path').'storge/referral/toulu/';//图片保存根目录

        $patients_id = $this->request->param('patients_id');
        $fileId = $this->request->param('fuzhen_id');

        $res = Referral::where(['patients_id'=>$patients_id,'fuzhen_id'=>$fileId])->field('img_address_toulu')->find();
        $res['img_address'] = json_decode($res['img_address_toulu']);

        //判断数据库字段是否为数组
        if (is_array($res['img_address'])){
            $this->getFileInfoArr($file_path,$res,'toulu');
            return $res;
        }
        //判断数据库字段是否有数据(字符串)
        if ($res['img_address']!=null){
            $this->getFileInfoStr($file_path,$res,'toulu');
            return $res;
        }
        //数据库字段无数据则输出空数组
        $res['img_address'] = [];
        return $res;
    }

    //获取图像-PET预览信息
    public function getPETAddress()
    {
        $file_path = Env::get('root_path').'storge/referral/pet/';//图片保存根目录

        $patients_id = $this->request->param('patients_id');
        $fileId = $this->request->param('fuzhen_id');

        $res = Referral::where(['patients_id'=>$patients_id,'fuzhen_id'=>$fileId])->field('img_address_pet')->find();
        $res['img_address'] = json_decode($res['img_address_pet']);

        //判断数据库字段是否为数组
        if (is_array($res['img_address'])){
            $this->getFileInfoArr($file_path,$res,'pet');
            return $res;
        }
        //判断数据库字段是否有数据(字符串)
        if ($res['img_address']!=null){
            $this->getFileInfoStr($file_path,$res,'pet');
            return $res;
        }
        //数据库字段无数据则输出空数组
        $res['img_address'] = [];
        return $res;
    }

}