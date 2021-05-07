<?php


namespace app\controller;
use app\controller\Base;
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

    //新增CT信息
    public function ctSaveNew()
    {
        //数据处理-获取表单数据
        $patients_id = $this->request->param('patients_id');
        $ct_data = $this->request->param('ct_form_data');
        $ct_id = $this->request->param('ct_id');



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

    //更新CT信息
    public function ctSave()
    {
        //数据处理-获取表单数据
        $patients_id = $this->request->param('patients_id');
        $ct_data = $this->request->param('ct_form_data');
        $ct_id = $this->request->param('ct_id');

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

        //判断数据库当前患者下ct_id是否存在
        $ct_id_res = Preoperative_ct::where(['ct_id'=>$ct_id,'patients_id'=>$patients_id])->find();
        //更新数据
        if ($ct_id_res){
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
        return $res['msg'] = '未知错误，请刷新后重试';

    }

    //保存CT图像
    public function imgCtSave()
    {
        $pre_ct = new Preoperative_ct();

        $savePath = Env::get('root_path').'storge/preoperative/ct/';//CT图片保存根目录

        $info = $this->request->param();//获取附加信息
        $patients_id =$info['patients_id'];
        $form_id =$info['form_id'];

        $file = $this->request->file();//获取上传文件本体
        $res = $file['imgShowCt'.$form_id]->move($savePath);//文件保存


        if($res)
        {
            $url = $res->getSaveName();

            $pre_ct->img_address = json_encode($savePath.$url);
            $pre_ct->where(['ct_id'=>$form_id,'patients_id'=>$patients_id])->data('img_address',$pre_ct->img_address)->update();

            return  (['status'=>1,'message'=>'图片上传成功，请核对其他信息后点击提交','url'=>$url,'arrUrl'=>json_decode($pre_ct->img_address)]);
        }
        else
        {
            return  (['status'=>0,'message'=>$file->getError(),'url'=>'']);
        }
    }

    //获取CT图像预览地址
    public function getCTAddress()
    {
        $patients_id = $this->request->param('patients_id');
        $ct_id = $this->request->param('ct_id');
        $res = Preoperative_ct::where(['patients_id'=>$patients_id,'ct_id'=>$ct_id])->field('img_address')->select();
        foreach ($res as $key=>$val){
            return $val;
        }


    }

    //显示CT图像路由
    public function imgCtShow()
    {

        $file_path = Env::get('root_path').'storge/preoperative/ct';
//        $filename = $_GET['filename'];


        $file = $file_path . '/20210506/5e7899280ef5cd5f10199168424cb2d6.png';
        $im = imagecreatefrompng($file);
        ob_start();
        imagepng($im);
        $content = ob_get_clean();
        imagedestroy($im);
        return response($content,200,['content-length'=>strlen($content)])->contentType('image/png');


    }
}