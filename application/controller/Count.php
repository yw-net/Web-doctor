<?php


namespace app\controller;


use think\Db;

class Count extends Base
{

    //统计报表系统信息页面
    public function sysList ()
    {
        $this->isLogin();

        $disk_total=Tools::flowAutoShow(disk_total_space("/"));
        $disk_free=Tools::flowAutoShow(disk_free_space("/"));
        $disk_max_upload = ini_get("file_uploads")?ini_get("upload_max_filesize"):"Disabled";
        $patients_total = Db::table('patients')->count();
        $user_total = Db::table('user')->count();


        $this->assign([
            'disk_total'=>$disk_total,
            'disk_free'=>$disk_free,
            'disk_max_upload'=>$disk_max_upload,
            'patients_total'=>$patients_total,
            'user_total'=>$user_total,
            ]);
        return $this->view->fetch();
    }

    //统计报表报表页面
    public function baseList ()
    {
        $this->isLogin();
        $sql = "select * from user";
        $doctor = Db::query($sql);

        $this->assign(['doctor'=>$doctor]);
        return $this->view->fetch();
    }
}