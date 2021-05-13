<?php


namespace app\controller;
use app\controller\Base;
use app\controller\Tools;
use app\model\Patients;
use app\model\Preoperative_ct;
use think\Db;
use app\model\User;
use think\facade\Request;
use think\db\Where;
use think\facade\Session;
use think\facade\Env;
use diversen\sendfile;

class PreSave extends Base
{
    //保存上传文件路径到数据库函数
    private function saveFileUrl($newUrl,$fileId,$patients_id,$dbName)
    {
        $get = Preoperative_ct::where(['ct_id'=>$fileId,'patients_id'=>$patients_id])->find();

        //未保存则创建新数据
        if (!isset($get)){
            $pre_ct = new Preoperative_ct();
            $pre_ct->ct_id =$fileId;
            $pre_ct->patients_id =$patients_id;
            $pre_ct->create_date = date('Y-m-d');
            $pre_ct->save();
        }
        //判断数据库文件地址是否为空
        if ($get['img_address'] == null){
            //转换为json格式写入数据库
            $newUrl = json_encode($newUrl);
            Db::name($dbName)
                ->data('img_address',$newUrl)
                ->where(['ct_id'=>$fileId,'patients_id'=>$patients_id])
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
                ->where(['ct_id'=>$fileId,'patients_id'=>$patients_id])
                ->update();
        }

    }

    //获取患者CT数量
    public function getCtNum()
    {
        $patients_id = $this->request->param('patients_id');
        $ct_num_res = Preoperative_ct::where('patients_id',$patients_id)->select();
        return count($ct_num_res);
    }

    //删除CT信息
    public function delCtInfo()
    {
        $patients_id = $this->request->param('patients_id');
        $ct_id = $this->request->param('ct_id');
        $del = Preoperative_ct::where(['ct_id'=>$ct_id,'patients_id'=>$patients_id])->delete();
        if ($del){
            $res['msg'] = '删除成功';
            $res['status'] = 1 ;
            return $res;
        }
        $res['msg'] = '数据库连接失败';
        $res['status'] = 0 ;
        return $res;
    }

    //更新、新增CT信息
    public function ctSaveNew()
    {
        //数据处理-获取表单数据
        $patients_id = $this->request->param('patients_id');
        $ct_data = $this->request->param('ct_form_data');
        $ct_id = $this->request->param('ct_id');

        //判断数据库当前患者下ct_id是否存在
        $ct_id_res = Preoperative_ct::where(['ct_id'=>$ct_id,'patients_id'=>$patients_id])->find();
        //更新数据
        if ($ct_id_res){

            //解决多选框及输入框为空
            if (isset($ct_data['buwei'])){
                //判断多选框值是否为数组（当选择两个以下强制转换为数组）
                if (!is_array($ct_data['buwei'])){
                    $ct_data['buwei'] = json_encode($ct_data['buwei']);
                }
            }else{
                $ct_data['buwei'] =null;
            }
            if (!isset($ct_data['daxiao1'])){
                $ct_data['daxiao1'] =null;
            }
            if (!isset($ct_data['daxiao2'])){
                $ct_data['daxiao2'] =null;
            }
            if (!isset($ct_data['daxiao3'])){
                $ct_data['daxiao3'] =null;
            }

            $res_ct = Db::name('Preoperative_ct')
                ->where(['ct_id'=>$ct_id,'patients_id'=>$patients_id])
                ->data([
                    'edit_date'=>date('Y-m-d H:i:s'),
                    'ggo'=>$ct_data['ggo'],
                    'buwei'=>$ct_data['buwei'],
                    'daxiao1'=>$ct_data['daxiao1'],
                    'daxiao2'=>$ct_data['daxiao2'],
                    'daxiao3'=>$ct_data['daxiao3'],
                    'gaihua'=>$ct_data['gaihua'],
                ])
                ->update();
            if ($res_ct){
                $res['status'] = 1;
                $res['msg'] ='保存成功';
                return $res;
            }
            $res['status'] =0;
            $res['msg'] ='数据未更改';
            return $res;
        }


        //新增数据
        $pre_ct = new Preoperative_ct();
        $pre_ct->ct_id =$ct_id;
        $pre_ct->patients_id =$patients_id;
        $pre_ct->create_date = date('Y-m-d');

        //处理空值问题
        if (isset($ct_data['buwei'])){
            //判断多选框值是否为数组（当选择两个以下强制转换为数组）
            if (!is_array($ct_data['buwei'])){
                $ct_data['buwei'] = json_encode($ct_data['buwei']);
            }
            $pre_ct->buwei = $ct_data['buwei'];
        }
        if (isset($ct_data['daxiao1'])){
            $pre_ct->daxiao1 = $ct_data['daxiao1'];
        }
        if (isset($ct_data['daxiao2'])){
            $pre_ct->daxiao2 = $ct_data['daxiao2'];
        }
        if (isset($ct_data['daxiao3'])){
            $pre_ct->daxiao3 = $ct_data['daxiao3'];
        }

        //单选框数据写入
        $pre_ct->ggo = $ct_data['ggo'];
        $pre_ct->gaihua = $ct_data['gaihua'];
        $pre_ct->save();

        if ($pre_ct){
            $res['status'] = 1;
            $res['msg'] ='保存成功';
            return $res;
        }

        $res['status'] =$pre_ct;
        $res['msg'] ='新增数据失败';
        return $res;
    }

    //保存CT图像
    public function imgCtSave()
    {
        $savePath = Env::get('root_path').'storge/preoperative/ct/'.date('Ymd');//CT图片保存根目录

        $info = $this->request->param();//获取附加信息

        $patients_id =$info['patients_id'];
        $form_id =$info['form_id'];


        $file = $this->request->file();//获取上传文件本体


        //文件保存
        if ($file){
            $res = $file['imgShowCt'.$form_id]->move($savePath,'');
        }
        else{
            return (['status'=>0,'error'=>'文件保存失败']);
        }

        //写入数据库
        if($res)
        {
            $url = date('Ymd')."/".$res->getSaveName();

            //数据库查重
            $get = Preoperative_ct::where(['ct_id'=>$form_id,'patients_id'=>$patients_id])->find();

            if ($get['img_address']!=null){
                $get_address = json_decode($get['img_address']); //取出json数据并转换为数组
//                return  (['status'=>1,'message'=>is_array($get_address)]);
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
            $this->saveFileUrl($url,$form_id,$patients_id,'Preoperative_ct');

            return  (['status'=>1,'message'=>'文件上传成功','fileid'=>$url]);
        }
        else
        {
            return  (['status'=>0,'error'=>'写入数据库失败']);
        }
    }

    //删除CT图像
    public function imgCtDel()
    {
        $info = $this->request->param();//获取附加信息

        $patients_id =$info['patients_id'];
        $form_id =$info['form_id'];
        $file_name = $info['key'];

        $get = Preoperative_ct::where(['ct_id'=>$form_id,'patients_id'=>$patients_id])->find();
        if ($get){
            $get_address = json_decode($get['img_address']); //取出json数据并转换为数组

            foreach ($get_address as $key=>$value)
            {
                if ($value == $file_name)
                    array_splice($get_address,$key,1);   //删除数组对应的值
            }

            //把删除后的数组重新写入数据库
            $res = Db::name('Preoperative_ct')
                ->data('img_address',$get_address)
                ->where(['ct_id'=>$form_id,'patients_id'=>$patients_id])
                ->update();

            if($res){
                //删除对应文件
                $savePath = Env::get('root_path').'storge/preoperative/ct/';//CT图片保存根目录
                $filePath = $savePath.$file_name;
                unlink($filePath);

                return ['status'=>1,'message'=>'删除成功'];
            }
            return ['status'=>0,'error'=>'删除失败'];
        }
        return ['status'=>0,'error'=>'未知错误'];
    }

    //获取CT图像预览信息
    public function getCTAddress()
    {
        $file_path = Env::get('root_path').'storge/preoperative/ct/';//CT图片保存根目录

        $patients_id = $this->request->param('patients_id');
        $ct_id = $this->request->param('ct_id');

        $res = Preoperative_ct::where(['patients_id'=>$patients_id,'ct_id'=>$ct_id])->field('img_address')->find();
        $res['img_address'] = json_decode($res['img_address']);

        $res_info = [];
        //判断数据库字段是否为数组
        if (is_array($res['img_address'])){
            foreach ($res['img_address'] as $key=>$value)
            {
                $imgInfo = filesize($file_path.$value); //获取文件大小

                $ct_name = explode('/',$value);   //截取字符串
                array_push($res_info,['caption'=>$ct_name[1],'downloadUrl'=>'../download/'.$value,'key'=>$value,'size'=>$imgInfo]);   //组合对象数组
            }
            $res['img_info'] = $res_info;
            return $res;
        }
        //判断数据库字段是否有数据
        if ($res['img_address']!=null){
            $ct_name = explode('/',$res['img_address']);   //截取字符串

            $imgInfo = filesize($file_path.$res['img_address']); //获取文件大小

            array_push($res_info,['caption'=>$ct_name[1],'downloadUrl'=>'../download/'.$res['img_address'],'key'=>$res['img_address'],'size'=>$imgInfo]);   //组合对象数组
            $res['img_info'] = $res_info;
            return $res;
        }
        //数据库字段无数据则输出空数组
        $res['img_address'] = [];
        return $res;

    }

    //图片显示路由
    public function imgRoute()
    {
        $this->isLogin();
        $fileDate = $this->request->param('date');
        $fileName = $this->request->param('name');

        $path = $fileDate."/".$fileName;
        $savePath = Env::get('root_path').'storge/preoperative/ct/';//CT图片保存根目录

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
            default:
                exit('图片格式不支持！');
        }

    }

    //文件下载路由
    public function download()
    {
        $this->isLogin();
        $fileDate = $this->request->param('date');
        $fileName = $this->request->param('name');

        $path = $fileDate."/".$fileName;
        $savePath = Env::get('root_path').'storge/preoperative/ct/';//CT图片保存根目录

        $file_path = $savePath.$path; //图片地址
        $s = new sendfile();

        $file = $file_path;

        try {
            $s->send($file);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

    }

    //测试
    public function ceshi()
    {
        $file_path = Env::get('root_path').'storge/preoperative/ct/20210512/IMG_4903.JPG';//CT图片保存根目录

        dump($file_path);

        $imgInfo = filesize($file_path); //解析图片文件信息
        dump($imgInfo);
        $size = Tools::flowAutoShow($imgInfo);
        dump($size);

    }

}