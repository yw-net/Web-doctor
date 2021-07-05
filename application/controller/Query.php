<?php


namespace app\controller;


use think\Db;

class Query extends Base
{
    //用户中心页面
    public function patientsQuery ()
    {
        $this->isLogin();
        return $this->view->fetch();
    }

    //查询按钮
    public function resQuery()
    {
        $get_data = $this->request->param();
        Db::query($get_data['data']);
//        return $get_data['data'][1]['fenlei'];

//            Db::view($get_data['data'][0]['fenlei'], 'patients_id')
//        for ($i = 0; $i < count($get_data['data']); $i++){
//                ->view('Profile', 'truename,phone,email', 'Profile.user_id=$get_data['data'][0]['fenlei'].patients_id')
//                ->view('Score', 'score', 'Score.user_id=Profile.id')
//                ->where('score', '>', 80)
//                ->select();

//        }



    }

}