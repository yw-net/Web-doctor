<?php
namespace app\controller;
use app\controller\Base;
use app\model\Patients;
use think\Db;
use app\model\User;
use think\Request;
use think\db\Where;
use think\facade\Session;

class Index extends Base
{
    //用户登录页面
    public function login()
    {
        $this->alreadyLogin();
        return $this->view->fetch();
    }

    //用户首页（团队简介页面）
    public function index ()
    {
        $this->isLogin();
        return $this->view->fetch();
    }

    //退出登录
    public function logOut(){
        session(null);
        return $this->redirect('Index/login');//跳转到登录页面
    }

    //用户登录验证
    public function checkLogin(Request $request){
            $status = 0;
            $result = '';
            $data = $request -> param();
            $rule =[
                'user|用户名'=> 'require',
                'password|密码'=>'require'
            ];
            //自定义验证失败提示信息
            $msg =[
                'user'=>['require'=>'用户名不能为空，请检查!'],
                'password'=>['require'=>'密码不能为空，请检查!']
            ];
            $result =$this->validate($data,$rule,$msg);
            if ($result === true){
                $where =[
                    'name'=> $data['user'],
                    'password'=> $data['password'],
                    'active'=>1
                ];
                $user = User::get($where);
                if ($user == null){
                    $result ='用户名密码错误';
                }else{
                    $status = 1;
                    $result = '验证成功';
                    //设置全局session登录用户信息
                    Session::set('user_neckname',$user->neckname);
                    Session::set('user_name',$user->name);
                    Session::set('user_id',$user->userid);
                    Session::set('user_level',$user->level);
                    //判断用户头像是否设置
                    $face_img = '../../'.$user->face_img;
                    if($user->face_img == null){
                        Session::set('user_img','/static/image/sys/face.jpg');
                    }else{
                        Session::set('user_img',$face_img);
                    }
                }
            }
            return ['status' =>$status,'message'=>$result,'data'=>$data];
    }

    //空操作（index模块下错误地址）
	public function _empty()
	{
		$this->redirect('user/login');
	}

	//患者列表页面
    public function patientsList()
    {
        $this->isLogin();
        return $this->view->fetch();
    }

    //后台管理页面
    public function manage ()
    {
        $this->isLogin();
        return $this->view->fetch();
    }

    //用户中心页面
    public function myinfo ()
    {
        $this->isLogin();
        return $this->view->fetch();
    }

    //用户中心 加载获取数据
    public function getinfo(){
        $id = Session::get('user_id');
        $where =[
            'userid'=> $id,
        ];
        $user = User::get($where);
        if ($user){
            return['status'=>100,'data'=>$user];
        }
        else{
            return['status'=>200,'msg'=>'数据库查询失败'];
        }
    }

    //修改密码页面
    public function password(){
        $this->isLogin();
        return $this->view->fetch();
    }

}
