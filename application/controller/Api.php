<?php

namespace app\controller;
use app\controller\Base;
use app\model\Patients;
use think\Db;
use app\model\User;
use think\Request;
use think\db\Where;
use think\facade\Session;

class Api extends Base
{
    //患者列表（展示患者信息）
    public function patientsManager()
    {
        //获取登录用户级别
        $level = Session::get('user_level');
        //登录用户为管理将获取所有病人资料
        if($level ==1){
            $list = Patients::all();

             //总条数
            $count=count($list);
            //获取每页显示的条数
            $limit= $this->request->param('limit');
            //获取当前页数
            $page= $this->request->param('page');
            //计算出从那条开始查询
            $tol=($page-1)*$limit;
            // 查询出当前页数显示的数据
            $list = Db::table('patients')
                ->field(['patients_id', 'patients_name', 'phone', 'hospital_id', 'sex', 'age', 'doctor', 'hospitalized_time', 'leaving_hospital'])
                ->limit("$tol","$limit")
                ->select();
            //返回数据
            return ["code"=>"0","msg"=>"","count"=>$count,"data"=>$list];
        }
        //只能获取当前医生名下病人资料
        else{
            $username = Session::get('user_neckname');
            $list = Db::table('patients')
                ->where('doctor',$username)
                ->select();
            //总条数
            $count=count($list);
            //获取每页显示的条数
            $limit= $this->request->param('limit');
            //获取当前页数
            $page= $this->request->param('page');
            //计算出从那条开始查询
            $tol=($page-1)*$limit;
            // 查询出当前页数显示的数据----分页
            $list = Db::table('patients')->field(['patients_id', 'patients_name', 'phone', 'hospital_id', 'sex', 'age', 'doctor', 'hospitalized_time', 'leaving_hospital'])->where('doctor',$username)->limit("$tol","$limit")->select();
            //返回数据
            return ["code"=>"0","msg"=>"","count"=>$count,"data"=>$list];
        }
    }

    //患者列表（添加新患者）
    public function addpatients(){
//        $doctor = Session::get('user_neckname');
//        $data = [
//            'doctor'=>$doctor
//        ];
//        $patientsid = Db::name('patients')->insertGetId($data);
        //生成临时患者ID
        $patientsid = Patients::order('patients_id', 'desc')->value("patients_id");
        $patientsid += 1;
        //设置患者ID缓存
        session('patientsid',null);
        Session::set('patientsid',$patientsid);
        $this->redirect('menu/patients',array('patientsid'=>$patientsid));
    }

    //患者列表（查询按钮）
    public function searchPatient(){
        $patients = $this->request->param('inputinfo');
        $result = Db::table('patients')
            ->where('patients_name',$patients)->whereOr('hospital_id',$patients)->whereOr('patients_id',$patients)
            ->select();
        return ["code"=>"0","msg"=>"","data"=>$result];
    }

    //患者列表（删除按钮）
    public function delPatient(){
        $patientsid = $this->request->get('patientsid');
        Db::table('patients')->delete($patientsid);
        return['status'=>1,'msg'=>'删除成功','user'=>$patientsid];
    }

    //后台管理-用户列表（查询按钮）
    public function searchUser()
    {
        $input = $this->request->param('inputinfo');
        $result = Db::table('user')
            ->where('name',$input)->whereOr('neckname',$input)
            ->select();
        return ["code"=>"0","msg"=>"","data"=>$result];
    }

    //后台管理-用户列表(展示用户信息)
    public function userManager()
    {
        $list = User::all();
        //总条数
        $count=count($list);
        //获取每页显示的条数
        $limit= $this->request->param('limit');
        //获取当前页数
        $page= $this->request->param('page');
        //计算出从那条开始查询
        $tol=($page-1)*$limit;
        // 查询出当前页数显示的数据
        $list = Db::table('user')->field(['userid', 'name', 'user_age', 'user_sex', 'level', 'password', 'neckname', 'user_class', 'user_level', 'face_img', 'active', 'user_reg_date'])->limit("$tol","$limit")->select();
        //返回数据
        return ["code"=>"0","msg"=>"","count"=>$count,"data"=>$list];
    }

    //后台管理-用户列表（删除用户）
    public function delUser(){
        $userid = $this->request->get('userid');
        Db::table('user')->delete($userid);
        return['status'=>1,'msg'=>'删除成功','user'=>$userid];
    }

    //后台管理-用户列表（编辑用户信息）
    public function editUser(){
        $result = $this->request->param();
        $data=[
            'name'=>$result['username'],
            'user_age'=>$result['age'],
            'user_sex'=>$result['sex'],
            'level'=>$result['level'],
            'password'=>$result['password'],
            'neckname'=>$result['neckname'],
            'user_class'=>$result['userClass'],
            'user_level'=>$result['userLevel'],
            'active'=>$result['active'],
            'face_img'=>$result['img'],
        ];
        $where=[
            'userid'=>$result['userid']
        ];
        $field=[
            'name','user_age','user_sex','level','password','neckname','user_class','user_level','active','face_img'
        ];
        $result = User::update($data,$where,$field);
        if ($result){
            session('user_neckname',null);
            Session::set('user_neckname',$result['neckname']);
            return['msg'=>'修改成功','status'=>100];
        }else{
            return['msg'=>'修改失败！请重试！','status'=>200];
        }

    }

    //后台管理-上传用户头像
    public function uploadImg(){
        $icon = $this->request->file("file");
        $info = $icon->validate([
            'size'=>10240000,
            'ext'=>'bmp,jpg,png,tif,gif,pcx,tga,exif,fpx,svg,psd,cdr,pcd,dxf,ufo,eps,ai,raw,WMF,webp,jpeg'
        ])->move('static/upload/userhead/');

        

        if($info)
        {
            $url = "../static/upload/userhead/".$info->getSaveName();
            return  json_encode(['status'=>100,'message'=>'上传成功,点击提交后保存','url'=>$url]);
        }
        else
        {
            $res = ['status'=>100,'message'=>'上传失败','url'=>''];
            return  json_encode(['status'=>-200,'message'=>$icon->getError(),'url'=>'']);
        }
    }

    //用户中心-个人信息（修改个人信息按钮）
    public function editMyInfo(){
        $result = $this->request->param();
        $face_img = $result['img'];
        $data=[
            'name'=>$result['username'],
            'user_age'=>$result['age'],
            'user_sex'=>$result['sex'],
            'neckname'=>$result['neckname'],
            'user_class'=>$result['userClass'],
            'user_level'=>$result['userLevel'],
            'face_img'=>$result['img'],
        ];
        $where=[
            'userid'=>$result['userid']
        ];
        $field=[
            'name','user_age','user_sex','neckname','user_class','user_level','face_img'
        ];
        $result = User::update($data,$where,$field);
        if ($result){
            session('user_neckname',null);
            session::set('user_neckname',$result['neckname']);
            session('user_img',null);
            session::set('user_img',$face_img);
            return['msg'=>'修改成功','status'=>100];
        }else{
            return['msg'=>'修改失败！请重试！','status'=>200];
        }
    }

    //用户中心-修改密码（修改密码按钮）
    public function editPassword(){
        $id = Session::get('user_id');
        $result = $this->request->param();
        $data=[
            'password'=>$result['password'],
        ];
        $where=[
            'userid'=>$id,
        ];
        $field=[
            'password'
        ];
        $result = User::update($data,$where,$field);
        if ($result){
            return['msg'=>'修改成功','status'=>100];
        }else{
            return['msg'=>'修改失败！请重试！','status'=>200];
        }
    }

    //清理用户冗余头像图片
    public function clearPhoto(){

        //指定目录绝对地址
        $path = realpath(dirname(__FILE__).'/../../public/static/upload');

        //查找指定目录下所有照片文件
        $arr=glob($path.'/userhead/*/*.{jpg,png,gif,jpeg,JPG,PNG,GIF,JPEG}',GLOB_BRACE);

        //服务器数据库目录绝对地址
        $dataPath = realpath(dirname(__FILE__).'/../../public/');
        //计算地址长度供查询数据库是截取使用
        $dataPath = strlen($dataPath);

        if(count($arr)>0){

            $j=count($arr);
            $ndel=0;

            for($i=0;$i<$j;$i++){
                //截取图片在服务器中的绝对地址，转换为数据库中的地址
                $dataArr = '..'.substr($arr[$i],$dataPath);
                //查询数据库中是否存在此图片
                $sql = Db::table('user')->where('face_img','=',$dataArr)->find();
                //不存在则删除
                if(!$sql){
                    unlink ($arr[$i]);
                    $ndel++;
                }
            }

            $n=count($arr)-$ndel;

            if($ndel>0){
                return "已清理".$ndel."张冗余照片，";
            }
            return ("没有找到冗余图片,共有 ".$n."张用户头像照片！");

        }

        if(count($arr)==0){
            return ("服务器没有冗余头像照片");
        }
    }

    //后台管理-上传图片
    public function uploadImgClass($class){
        $icon = $this->request->file("file");
        $info = $icon->validate([
            'size'=>102400000,
            'ext'=>'bmp,jpg,png,tif,gif,pcx,tga,exif,fpx,svg,psd,cdr,pcd,dxf,ufo,eps,ai,raw,WMF,webp,jpeg'
        ])->move('static/upload/'.$class.'/');

        if($info)
        {
            $url = "../static/upload/".$class."/".$info->getSaveName();
            return  json_encode(['status'=>100,'message'=>'上传成功','url'=>$url]);
        }
        else
        {
            return  json_encode(['status'=>200,'message'=>$icon->getError(),'url'=>'']);
        }
    }

}