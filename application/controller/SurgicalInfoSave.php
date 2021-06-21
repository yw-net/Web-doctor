<?php


namespace app\controller;
use app\model\Surgical_info_zhongwu;
use app\model\Surgical_info_shoushu;
use diversen\sendfile;
use think\Db;
use think\facade\Env;


class SurgicalInfoSave extends Base
{
    //保存文件路径函数
    private function saveFileUrl($newUrl,$fileId,$patients_id,$dbName,$idName)
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
        if ($get['img_address'] == null){
            //转换为json格式写入数据库
            $newUrl = json_encode($newUrl);
            Db::name($dbName)
                ->data('img_address',$newUrl)
                ->where([$idName=>$fileId,'patients_id'=>$patients_id])
                ->update();
        }
        else
        {
            $array_img_address= json_decode($get['img_address']);//取出数据库原有json数据

            //判断原有数据是否为数组(单个数据默认为字符串，需转换为数据)
            if (is_array($array_img_address)){
                array_push($array_img_address,$newUrl);//组合新数组（多条数据）
            }
            else{
                $array_img_address = array($array_img_address);//把数据库原有数据转换为数组
                array_push($array_img_address,$newUrl);//组合新数组（单条数据）
            }

            Db::name($dbName)
                ->data('img_address',$array_img_address)
                ->where([$idName=>$fileId,'patients_id'=>$patients_id])
                ->update();
        }

    }

    //删除文件路径函数
    private function delFileUrl($form_id,$patients_id,$idName,$dbName,$folderName,$file_name){
        $get = Db::name($dbName)
            ->where([$idName=>$form_id,'patients_id'=>$patients_id])
            ->find();

        $get_address = json_decode($get['img_address']); //取出json数据并转换为数组
        //判断是否为字符串(多条数据)
        if (gettype($get_address)!='string'){

            foreach ($get_address as $key=>$value)
            {
                if ($value == $file_name)
                    array_splice($get_address,$key,1);   //删除数组对应的值
            }

            //把删除后的数组重新写入数据库
            $res = Db::name($dbName)
                ->data('img_address',$get_address)
                ->where([$idName=>$form_id,'patients_id'=>$patients_id])
                ->update();

            if($res){
                //删除对应文件
                $savePath = Env::get('root_path').'storge/surgical/'.$folderName.'/';//图片保存根目录
                $filePath = $savePath.$file_name;
                unlink($filePath);
            }
        }
        //单条数据直接删除
        if (gettype($get_address) == 'string'){
            $res = Db::name($dbName)
                ->where([$idName=>$form_id,'patients_id'=>$patients_id])
                ->update(['img_address'=>[]]);
            if($res){
                //删除对应文件
                $savePath = Env::get('root_path').'storge/surgical/'.$folderName.'/';//图片保存根目录
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
            array_push($res_info,['caption'=>$file_name[1],'downloadUrl'=>'../surfile/'.$type.'/'.$value,'key'=>$value,'size'=>$imgInfo]);   //组合对象数组
        }
        $res['img_info'] = $res_info;
    }
    private function getFileInfoStr($file_path,$res,$type){//字符串
        $res_info = [];
        $file_name = explode('/',$res['img_address']);   //截取字符串

        $imgInfo = filesize($file_path.$res['img_address']); //获取文件大小

        array_push($res_info,['caption'=>$file_name[1],'downloadUrl'=>'../surfile/'.$type.'/'.$res['img_address'],'key'=>$res['img_address'],'size'=>$imgInfo]);   //组合对象数组
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
        $savePath = Env::get('root_path').'storge/surgical/'.$fileType.'/';//图片保存根目录

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



    //更新、新增肿物信息
    public function zhongWuSaveNew(){
        //数据处理-获取表单数据
        $patients_id = $this->request->param('patients_id');
        $form_data = $this->request->param('form_data');
        $zhongwu_id = $this->request->param('form_id');

        $pre_zhongwu = new Surgical_info_zhongwu();

        //id
        $pre_zhongwu->patients_id=$patients_id;
        $pre_zhongwu->zhongwu_id=$zhongwu_id;

        //判断数据库当前患者下form_id是否存在
        $pre_zhongwu_find = Surgical_info_zhongwu::where(['patients_id'=>$patients_id,'zhongwu_id'=>$zhongwu_id])->find();
        //更新数据
        if ($pre_zhongwu_find){
            $pre_zhongwu_find->edit_time = date('Y-m-d H:i:s');

            if (isset($form_data['zhongwu_name'])) {
                $pre_zhongwu_find->zhongwu_name = $form_data['zhongwu_name'];
            }
            else{
                $pre_zhongwu_find->zhongwu_name = null;
            }
            if (isset($form_data['zhongwu_daxiao_1'])){
                $pre_zhongwu_find->zhongwu_daxiao_1 = $form_data['zhongwu_daxiao_1'];
            }
             else{
                $pre_zhongwu_find->zhongwu_daxiao_1 = null;
            }
            if (isset($form_data['zhongwu_daxiao_2'])){
                $pre_zhongwu_find->zhongwu_daxiao_2 = $form_data['zhongwu_daxiao_2'];
            }
             else{
                $pre_zhongwu_find->zhongwu_daxiao_2 = null;
            }
            if (isset($form_data['zhongwu_daxiao_3'])){
                $pre_zhongwu_find->zhongwu_daxiao_3 = $form_data['zhongwu_daxiao_3'];
            }
            else{
                $pre_zhongwu_find->zhongwu_daxiao_3 = null;
            }
            $pre_zhongwu_find->zhongwu_buwei = $form_data['zhongwu_buwei'];
            $pre_zhongwu_find->zhongwu_kuaye = $form_data['zhongwu_kuaye'];
            $pre_zhongwu_find->zhongwu_zhousuo = $form_data['zhongwu_zhousuo'];
            $pre_zhongwu_find->zhongwu_bichengxiongmo = $form_data['zhongwu_bichengxiongmo'];
            $pre_zhongwu_find->zhongwu_xiongbi = $form_data['zhongwu_xiongbi'];
            $pre_zhongwu_find->zhongwu_qiangjingmai = $form_data['zhongwu_qiangjingmai'];
            $pre_zhongwu_find->zhongwu_feimenxueguan = $form_data['zhongwu_feimenxueguan'];
            $pre_zhongwu_find->zhongwu_xinbao = $form_data['zhongwu_xinbao'];
            $pre_zhongwu_find->zhongwu_geshenjing = $form_data['zhongwu_geshenjing'];
            $pre_zhongwu_find->zhongwu_geji = $form_data['zhongwu_geji'];
            $pre_zhongwu_find->save();
            $res['status'] = 1;
            $res['msg'] ='数据更新成功';
            return $res;
        }
        //新增数据
        $pre_zhongwu->created_time = date('Y-m-d');

        if (isset($form_data['zhongwu_name'])) {
            $pre_zhongwu->zhongwu_name = $form_data['zhongwu_name'];
        }
        if (isset($form_data['zhongwu_daxiao_1'])){
            $pre_zhongwu->zhongwu_daxiao_1 = $form_data['zhongwu_daxiao_1'];
        }
        if (isset($form_data['zhongwu_daxiao_2'])){
            $pre_zhongwu->zhongwu_daxiao_2 = $form_data['zhongwu_daxiao_2'];
        }
        if (isset($form_data['zhongwu_daxiao_3'])){
            $pre_zhongwu->zhongwu_daxiao_3 = $form_data['zhongwu_daxiao_3'];
        }
        $pre_zhongwu->zhongwu_buwei = $form_data['zhongwu_buwei'];
        $pre_zhongwu->zhongwu_kuaye = $form_data['zhongwu_kuaye'];
        $pre_zhongwu->zhongwu_zhousuo = $form_data['zhongwu_zhousuo'];
        $pre_zhongwu->zhongwu_bichengxiongmo = $form_data['zhongwu_bichengxiongmo'];
        $pre_zhongwu->zhongwu_xiongbi = $form_data['zhongwu_xiongbi'];
        $pre_zhongwu->zhongwu_qiangjingmai = $form_data['zhongwu_qiangjingmai'];
        $pre_zhongwu->zhongwu_feimenxueguan = $form_data['zhongwu_feimenxueguan'];
        $pre_zhongwu->zhongwu_xinbao = $form_data['zhongwu_xinbao'];
        $pre_zhongwu->zhongwu_geshenjing = $form_data['zhongwu_geshenjing'];
        $pre_zhongwu->zhongwu_geji = $form_data['zhongwu_geji'];
        $pre_zhongwu->save();
        $res['status'] = 1;
        $res['msg'] ='数据保存成功';
        return $res;



    }

    //删除肿物信息
    public function delZhongWuInfo()
    {
        $patients_id = $this->request->param('patients_id');
        $id = $this->request->param('form_id');
        $del = Surgical_info_zhongwu::where(['zhongwu_id'=>$id,'patients_id'=>$patients_id])->delete();
        if ($del){
            $res['msg'] = '删除成功';
            $res['status'] = 1 ;
            return $res;
        }
        $res['msg'] = '数据库连接失败';
        $res['status'] = 0 ;
        return $res;
    }


    //更新、新增手术信息
    public function shouShuSaveNew()
    {
        //数据处理-获取表单数据
        $patients_id = $this->request->param('patients_id');
        $form_data = $this->request->param('form_data');
        $shoushu_id = $this->request->param('form_id');

        $shoushu = new Surgical_info_shoushu();

        //id
        $shoushu->patients_id=$patients_id;
        $shoushu->shoushu_id=$shoushu_id;

        //判断数据库当前患者下shoushu_id是否存在
        $shoushu_id_res = Surgical_info_shoushu::where(['shoushu_id'=>$shoushu_id,'patients_id'=>$patients_id])->find();
        //更新数据
        if ($shoushu_id_res){
            $shoushu_id_res->edit_time = date('Y-m-d H:i:s');
            if (isset($form_data['huojianzuzhi'])) {
                //判断多选框值是否为数组（当选择两个以下强制转换为数组）
                if (!is_array($form_data['huojianzuzhi'])) {
                    $form_data['huojianzuzhi'] = json_encode($form_data['huojianzuzhi']);
                }
                $shoushu_id_res->huojianzuzhi = $form_data['huojianzuzhi'];
            }
            else {$shoushu_id_res->huojianzuzhi = null;}
            if (isset($form_data['tanchaguxi'])) {
                //判断多选框值是否为数组（当选择两个以下强制转换为数组）
                if (!is_array($form_data['tanchaguxi'])) {
                    $form_data['tanchaguxi'] = json_encode($form_data['tanchaguxi']);
                }
                $shoushu_id_res->tanchaguxi = $form_data['tanchaguxi'];
            }else {$shoushu_id_res->tanchaguxi = null;}
            if (isset($form_data['qiekoufangshi'])) {
                //判断多选框值是否为数组（当选择两个以下强制转换为数组）
                if (!is_array($form_data['qiekoufangshi'])) {
                    $form_data['qiekoufangshi'] = json_encode($form_data['qiekoufangshi']);
                }
                $shoushu_id_res->qiekoufangshi = $form_data['qiekoufangshi'];
            }else {$shoushu_id_res->qiekoufangshi = null;}
            if (isset($form_data['shoushufangshi'])) {
                //判断多选框值是否为数组（当选择两个以下强制转换为数组）
                if (!is_array($form_data['shoushufangshi'])) {
                    $form_data['shoushufangshi'] = json_encode($form_data['shoushufangshi']);
                }
                $shoushu_id_res->shoushufangshi = $form_data['shoushufangshi'];
            }else {$shoushu_id_res->shoushufangshi = null;}
            if (isset($form_data['shoushu_date'])){
                $shoushu_id_res->shoushu_date = $form_data['shoushu_date'];
            }else {$shoushu_id_res->shoushu_date = null;}
            if (isset($form_data['huojianzuzhi_other'])){
                $shoushu_id_res->huojianzuzhi_other = $form_data['huojianzuzhi_other'];
            }else {$shoushu_id_res->huojianzuzhi_other = null;}
            if (isset($form_data['tanchaguxi_other'])){
                $shoushu_id_res->tanchaguxi_other = $form_data['tanchaguxi_other'];
            }else {$shoushu_id_res->tanchaguxi_other = null;}
            if (isset($form_data['shoushushichang'])){
                $shoushu_id_res->shoushushichang = $form_data['shoushushichang'];
            }else {$shoushu_id_res->shoushushichang = null;}
            if (isset($form_data['shuzhongchuxue'])){
                $shoushu_id_res->shuzhongchuxue = $form_data['shuzhongchuxue'];
            }else {$shoushu_id_res->shuzhongchuxue = null;}
            if (isset($form_data['shuzhong_shuye'])){
                $shoushu_id_res->shuzhong_shuye = $form_data['shuzhong_shuye'];
            }else {$shoushu_id_res->shuzhong_shuye = null;}
            if (isset($form_data['shuzhong_jingti'])){
                $shoushu_id_res->shuzhong_jingti = $form_data['shuzhong_jingti'];
            }else {$shoushu_id_res->shuzhong_jingti = null;}
            if (isset($form_data['shuzhong_jiaoti'])){
                $shoushu_id_res->shuzhong_jiaoti = $form_data['shuzhong_jiaoti'];
            }else {$shoushu_id_res->shuzhong_jiaoti = null;}
            if (isset($form_data['shuzhong_xuejiang'])){
                $shoushu_id_res->shuzhong_xuejiang = $form_data['shuzhong_xuejiang'];
            }else {$shoushu_id_res->shuzhong_xuejiang = null;}
            if (isset($form_data['shuzhong_hongxibao'])){
                $shoushu_id_res->shuzhong_hongxibao = $form_data['shuzhong_hongxibao'];
            }else {$shoushu_id_res->shuzhong_hongxibao = null;}
            if (isset($form_data['shuzhong_liaoliang'])){
                $shoushu_id_res->shuzhong_liaoliang = $form_data['shuzhong_liaoliang'];
            }else {$shoushu_id_res->shuzhong_liaoliang = null;}
            if (isset($form_data['shoushufangshi_other'])){
                $shoushu_id_res->shoushufangshi_other = $form_data['shoushufangshi_other'];
            }else {$shoushu_id_res->shoushufangshi_other = null;}
            $shoushu_id_res->zhudao = $form_data['zhudao'];
            $shoushu_id_res->mazui = $form_data['mazui'];
            $shoushu_id_res->zhushou1 = $form_data['zhushou1'];
            $shoushu_id_res->zhushou2 = $form_data['zhushou2'];
            $shoushu_id_res->shuqianbingli = $form_data['shuqianbingli'];
            $shoushu_id_res->shoushumudi = $form_data['shoushumudi'];
            $shoushu_id_res->huojianfangshi = $form_data['huojianfangshi'];
            $shoushu_id_res->shuzhongqiguanjing = $form_data['shuzhongqiguanjing'];
            $shoushu_id_res->qiguanchaguan = $form_data['qiguanchaguan'];
            $shoushu_id_res->tiwei = $form_data['tiwei'];
            $shoushu_id_res->qiekoubuwei = $form_data['qiekoubuwei'];
            $shoushu_id_res->zanlian = $form_data['zanlian'];
            $shoushu_id_res->xiongshui = $form_data['xiongshui'];
            $shoushu_id_res->yeliefayu = $form_data['yeliefayu'];
            $shoushu_id_res->shuzhongfeiweisuo = $form_data['shuzhongfeiweisuo'];
            $shoushu_id_res->linbajiezhuanyi = $form_data['linbajiezhuanyi'];
            $shoushu_id_res->shuzhongqiechubingdong = $form_data['shuzhongqiechubingdong'];
            $shoushu_id_res->who = $form_data['who'];
            $shoushu_id_res->qingshao1 = $form_data['qingshao1'];
            $shoushu_id_res->qingshao2 = $form_data['qingshao2'];
            $shoushu_id_res->qingshao3 = $form_data['qingshao3'];
            $shoushu_id_res->qingshao4 = $form_data['qingshao4'];
            $shoushu_id_res->qingshao5 = $form_data['qingshao5'];
            $shoushu_id_res->qingshao6 = $form_data['qingshao6'];
            $shoushu_id_res->qingshao7 = $form_data['qingshao7'];
            $shoushu_id_res->qingshao8 = $form_data['qingshao8'];
            $shoushu_id_res->qingshao9 = $form_data['qingshao9'];
            $shoushu_id_res->qingshao10 = $form_data['qingshao10'];
            $shoushu_id_res->qingshao11 = $form_data['qingshao11'];
            $shoushu_id_res->shoushugengzhi = $form_data['shoushugengzhi'];
            $shoushu_id_res->qiguancanduanbihe = $form_data['qiguancanduanbihe'];
            $shoushu_id_res->qiguancanduanchengxing = $form_data['qiguancanduanchengxing'];
            $shoushu_id_res->xueguanchengxing = $form_data['xueguanchengxing'];
            $shoushu_id_res->xiongqiangchongxi = $form_data['xiongqiangchongxi'];
            $shoushu_id_res->xiongguanshuliang = $form_data['xiongguanshuliang'];
            $shoushu_id_res->shuzhongteshuchuli = $form_data['shuzhongteshuchuli'];
            $shoushu_id_res->save();
            $res['status'] = 1;
            $res['msg'] ='数据更新成功';
            return $res;
        }
        //新增数据
        $shoushu->created_time = date('Y-m-d');
        if (isset($form_data['huojianzuzhi'])) {
            //判断多选框值是否为数组（当选择两个以下强制转换为数组）
            if (!is_array($form_data['huojianzuzhi'])) {
                $form_data['huojianzuzhi'] = json_encode($form_data['huojianzuzhi']);
            }
            $shoushu->huojianzuzhi = $form_data['huojianzuzhi'];
        }
        if (isset($form_data['tanchaguxi'])) {
            //判断多选框值是否为数组（当选择两个以下强制转换为数组）
            if (!is_array($form_data['tanchaguxi'])) {
                $form_data['tanchaguxi'] = json_encode($form_data['tanchaguxi']);
            }
            $shoushu->tanchaguxi = $form_data['tanchaguxi'];
        }
        if (isset($form_data['qiekoufangshi'])) {
            //判断多选框值是否为数组（当选择两个以下强制转换为数组）
            if (!is_array($form_data['qiekoufangshi'])) {
                $form_data['qiekoufangshi'] = json_encode($form_data['qiekoufangshi']);
            }
            $shoushu->qiekoufangshi = $form_data['qiekoufangshi'];
        }
        if (isset($form_data['shoushufangshi'])) {
            //判断多选框值是否为数组（当选择两个以下强制转换为数组）
            if (!is_array($form_data['shoushufangshi'])) {
                $form_data['shoushufangshi'] = json_encode($form_data['shoushufangshi']);
            }
            $shoushu->shoushufangshi = $form_data['shoushufangshi'];
        }
        if (isset($form_data['shoushu_date'])){
            $shoushu->shoushu_date = $form_data['shoushu_date'];
        }
        if (isset($form_data['huojianzuzhi_other'])){
            $shoushu->huojianzuzhi_other = $form_data['huojianzuzhi_other'];
        }
        if (isset($form_data['tanchaguxi_other'])){
            $shoushu->tanchaguxi_other = $form_data['tanchaguxi_other'];
        }
        if (isset($form_data['shoushushichang'])){
            $shoushu->shoushushichang = $form_data['shoushushichang'];
        }
        if (isset($form_data['shuzhongchuxue'])){
            $shoushu->shuzhongchuxue = $form_data['shuzhongchuxue'];
        }
        if (isset($form_data['shuzhong_shuye'])){
            $shoushu->shuzhong_shuye = $form_data['shuzhong_shuye'];
        }
        if (isset($form_data['shuzhong_jingti'])){
            $shoushu->shuzhong_jingti = $form_data['shuzhong_jingti'];
        }
        if (isset($form_data['shuzhong_jiaoti'])){
            $shoushu->shuzhong_jiaoti = $form_data['shuzhong_jiaoti'];
        }
        if (isset($form_data['shuzhong_xuejiang'])){
            $shoushu->shuzhong_xuejiang = $form_data['shuzhong_xuejiang'];
        }
        if (isset($form_data['shuzhong_hongxibao'])){
            $shoushu->shuzhong_hongxibao = $form_data['shuzhong_hongxibao'];
        }
        if (isset($form_data['shuzhong_liaoliang'])){
            $shoushu->shuzhong_liaoliang = $form_data['shuzhong_liaoliang'];
        }
        if (isset($form_data['shoushufangshi_other'])){
            $shoushu->shoushufangshi_other = $form_data['shoushufangshi_other'];
        }
        $shoushu->zhudao = $form_data['zhudao'];
        $shoushu->mazui = $form_data['mazui'];
        $shoushu->zhushou1 = $form_data['zhushou1'];
        $shoushu->zhushou2 = $form_data['zhushou2'];
        $shoushu->shuqianbingli = $form_data['shuqianbingli'];
        $shoushu->shoushumudi = $form_data['shoushumudi'];
        $shoushu->huojianfangshi = $form_data['huojianfangshi'];
        $shoushu->shuzhongqiguanjing = $form_data['shuzhongqiguanjing'];
        $shoushu->qiguanchaguan = $form_data['qiguanchaguan'];
        $shoushu->tiwei = $form_data['tiwei'];
        $shoushu->qiekoubuwei = $form_data['qiekoubuwei'];
        $shoushu->zanlian = $form_data['zanlian'];
        $shoushu->xiongshui = $form_data['xiongshui'];
        $shoushu->yeliefayu = $form_data['yeliefayu'];
        $shoushu->shuzhongfeiweisuo = $form_data['shuzhongfeiweisuo'];
        $shoushu->linbajiezhuanyi = $form_data['linbajiezhuanyi'];
        $shoushu->shuzhongqiechubingdong = $form_data['shuzhongqiechubingdong'];
        $shoushu->who = $form_data['who'];
        $shoushu->qingshao1 = $form_data['qingshao1'];
        $shoushu->qingshao2 = $form_data['qingshao2'];
        $shoushu->qingshao3 = $form_data['qingshao3'];
        $shoushu->qingshao4 = $form_data['qingshao4'];
        $shoushu->qingshao5 = $form_data['qingshao5'];
        $shoushu->qingshao6 = $form_data['qingshao6'];
        $shoushu->qingshao7 = $form_data['qingshao7'];
        $shoushu->qingshao8 = $form_data['qingshao8'];
        $shoushu->qingshao9 = $form_data['qingshao9'];
        $shoushu->qingshao10 = $form_data['qingshao10'];
        $shoushu->qingshao11 = $form_data['qingshao11'];
        $shoushu->shoushugengzhi = $form_data['shoushugengzhi'];
        $shoushu->qiguancanduanbihe = $form_data['qiguancanduanbihe'];
        $shoushu->qiguancanduanchengxing = $form_data['qiguancanduanchengxing'];
        $shoushu->xueguanchengxing = $form_data['xueguanchengxing'];
        $shoushu->xiongqiangchongxi = $form_data['xiongqiangchongxi'];
        $shoushu->xiongguanshuliang = $form_data['xiongguanshuliang'];
        $shoushu->shuzhongteshuchuli = $form_data['shuzhongteshuchuli'];
        $shoushu->save();
        $res['status'] = 1;
        $res['msg'] ='数据保存成功';
        return $res;
    }

    //删除手术信息
    public function delshoushuInfo()
    {
        $patients_id = $this->request->param('patients_id');
        $shoushu_id = $this->request->param('form_id');
        $del = Surgical_info_shoushu::where(['shoushu_id'=>$shoushu_id,'patients_id'=>$patients_id])->delete();
        if ($del){
            $res['msg'] = '删除成功';
            $res['status'] = 1 ;
            return $res;
        }
        $res['msg'] = '数据库连接失败';
        $res['status'] = 0 ;
        return $res;
    }

    //保存手术图像
    public function imgShouShuSave()
    {
        $savePath = Env::get('root_path').'storge/surgical/shoushu/'.date('Ymd');//图片保存根目录

        $info = $this->request->param();//获取附加信息

        $patients_id =$info['patients_id'];
        $form_id =$info['form_id'];


        $file = $this->request->file();//获取上传文件本体


        //文件保存
        if ($file){
            $res = $file['imgShowShouShu'.$form_id]->move($savePath,'');
        }
        else{
            return (['status'=>0,'error'=>'文件保存失败']);
        }

        //写入数据库
        if($res)
        {
            $url = date('Ymd')."/".$res->getSaveName();

            //数据库查重
            $get = Surgical_info_shoushu::where(['shoushu_id'=>$form_id,'patients_id'=>$patients_id])->find();

            if ($get['img_address']!=null){
                $get_address = json_decode($get['img_address']); //取出json数据并转换为数组
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
            $this->saveFileUrl($url,$form_id,$patients_id,'Surgical_info_shoushu','shoushu_id');

            return  (['status'=>1,'message'=>'文件上传成功','fileid'=>$url]);
        }
        else
        {
            return  (['status'=>0,'error'=>'写入数据库失败']);
        }
    }

    //删除手术图像
    public function imgShoushuDel()
    {
        $info = $this->request->param();//获取附加信息

        $patients_id =$info['patients_id'];
        $form_id =$info['form_id'];
        $file_name = $info['key'];
        $idName = 'shoushu_id';
        $dbName = 'Surgical_info_shoushu';
        $folderName = 'shoushu';

        $this->delFileUrl($form_id,$patients_id,$idName,$dbName,$folderName,$file_name);

        return ['status'=>1,'message'=>'删除成功'];

    }

    //获取图像预览信息
    public function getShouShuAddress()
    {
        $file_path = Env::get('root_path').'storge/surgical/shoushu/';//图片保存根目录

        $patients_id = $this->request->param('patients_id');
        $fileId = $this->request->param('shoushu_id');

        $res = Surgical_info_shoushu::where(['patients_id'=>$patients_id,'shoushu_id'=>$fileId])->field('img_address')->find();
        $res['img_address'] = json_decode($res['img_address']);

        //判断数据库字段是否为数组
        if (is_array($res['img_address'])){
            $this->getFileInfoArr($file_path,$res,'shoushu');
            return $res;
        }
        //判断数据库字段是否有数据(字符串)
        if ($res['img_address']!=null){
            $this->getFileInfoStr($file_path,$res,'shoushu');
            return $res;
        }
        //数据库字段无数据则输出空数组
        $res['img_address'] = [];
        return $res;
    }

}