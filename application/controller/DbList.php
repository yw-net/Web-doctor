<?php


namespace app\controller;
use app\controller\Base;
use app\model\Patients;
use think\Db;
use app\model\User;
use think\Request;
use think\db\Where;
use think\facade\Session;


class DbList extends Base
{
    //表格（数据回调/选择医生）
    public function tableList(){
        //获取登录用户级别
        $level = Session::get('user_level');
        //获取管理员提交的医生姓名
        $doctor = $this->request->get('doctor');
        //获取每页显示的条数
        $limit= $this->request->param('limit');
        //获取当前页数
        $page= $this->request->param('page');
        //计算出从那条开始查询
        $tol=($page-1)*$limit;
        //登录用户为管理员
        if($level ==1){
            //全部医生名下患者
            if($doctor === '0'){
                //分页-计算数据总数
                $listall = Db::table('patients')->select();
                $count = count($listall);
                //每页所显示的数据
                $list = Db::table('patients')
                    ->limit("$tol","$limit")
                    ->select();
            }
            //选定医生名下患者
            else{
                //分页-计算数据总数
                $listall = Db::table('patients')->where('doctor',$doctor)->select();
                $count = count($listall);
                //每页所显示的数据
                $list = Db::table('patients')
                    ->where('doctor',$doctor)
                    ->limit("$tol","$limit")
                    ->select();
            }
            //返回数据
            return ["code"=>"0","msg"=>"","count"=>$count,"data"=>$list];
        }
        //登录用户为普通用户
        else{
            //获取登录医生姓名
            $doctor = Session::get('user_neckname');
            //分页-计算数据总数
            $listall = Db::table('patients')->where('doctor',$doctor)->select();
            $count = count($listall);
            //每页所显示的数据
            $list = Db::table('patients')
                ->where('doctor',$doctor)
                ->limit("$tol","$limit")
                ->select();
            //返回数据
            return ["code"=>"0","msg"=>"","count"=>$count,"data"=>$list];
        }
    }


    //性别（图）
    public function sexDb(){
        //获取登录用户级别
        $level = Session::get('user_level');
        //获取管理员提交的医生姓名
        $doctor = $this->request->get();
        $doctor = key($doctor);

        //登录用户为管理员
        if($level ==1){
            //获取所有医生名下患者性别
            if($doctor === 0){
                //获取男性患者个数
                $listMan = Db::table('patients')
                    ->where('sex',1)
                    ->select();
                //总条数(男)
                $countMan=count($listMan);

                //获取女性患者个数
                $listWomen = Db::table('patients')
                    ->where('sex',0)
                    ->select();
                //总条数(女)
                $countWomen=count($listWomen);

                //返回数据
                return ["countman"=>$countMan,"countwomen"=>$countWomen,'doctor'=>'全部'];
            }
            //获取特定医生名下患者性别
            else{
                //获取男性患者个数
                $listMan = Db::table('patients')
                    ->where(['doctor'=>$doctor,'sex'=>1])
                    ->select();
                //总条数(男)
                $countMan=count($listMan);

                //获取女性患者个数
                $listWomen = Db::table('patients')
                    ->where(['doctor'=>$doctor,'sex'=>0])
                    ->select();
                //总条数(女)
                $countWomen=count($listWomen);

                //返回数据
                return ["countman"=>$countMan,"countwomen"=>$countWomen,"doctor"=>$doctor];

            }
        }
        //登录用户为普通用户
        else{
            //获取登录医生姓名
            $doctor = Session::get('user_neckname');

            //获取男性患者个数
            $listMan = Db::table('patients')
                ->where(['doctor'=>$doctor,'sex'=>1])
                ->select();
            //总条数(男)
            $countMan=count($listMan);

            //获取女性患者个数
            $listWomen = Db::table('patients')
                ->where(['doctor'=>$doctor,'sex'=>0])
                ->select();
            //总条数(女)
            $countWomen=count($listWomen);

            //返回数据
            return ["countman"=>$countMan,"countwomen"=>$countWomen,"doctor"=>$doctor];
        }
    }

    //性别（表格-点击图形筛选数据）
    public function clickListSex(){
        //获取登录用户级别
        $level = Session::get('user_level');
        //获取管理员提交的医生姓名
        $doctor = $this->request->get('doctor');
        //获取用户点击的性别
        $sex = $this->request->get('click');
        //获取每页显示的条数
        $limit= $this->request->param('limit');
        //获取当前页数
        $page= $this->request->param('page');
        //计算出从那条开始查询
        $tol=($page-1)*$limit;
        //数据库转换识别
        if($sex == '男'){$sex = 1;}elseif($sex == '女'){$sex = 0;}else{$sex = null;}
        //登录用户为管理员
        if($level ==1){
            //全部医生名下患者
            if($doctor === '0'){
                //分页-计算数据总数
                $listall = Db::table('patients')->where('sex',$sex)->select();
                $count = count($listall);
                //每页所显示的数据
                $list = Db::table('patients')
                    ->where('sex',$sex)
                    ->limit("$tol","$limit")
                    ->select();
            }
            //选定医生名下患者
            else{
                //分页-计算数据总数
                $listall = Db::table('patients')->where('doctor',$doctor)->where('sex',$sex)->select();
                $count = count($listall);
                //每页所显示的数据
                $list = Db::table('patients')
                    ->where('doctor',$doctor)
                    ->where('sex',$sex)
                    ->limit("$tol","$limit")
                    ->select();
            }
            //返回数据
            return ["code"=>"0","msg"=>"","count"=>$count,"data"=>$list];
        }
        //登录用户为普通用户
        else{
            //获取登录医生姓名
            $doctor = Session::get('user_neckname');
            //分页-计算数据总数
            $listall = Db::table('patients')->where('doctor',$doctor)->where('sex',$sex)->select();
            $count = count($listall);
            //每页所显示的数据
            $list = Db::table('patients')
                ->where('doctor',$doctor)
                ->where('sex',$sex)
                ->limit("$tol","$limit")
                ->select();
            return ["code"=>"0","msg"=>"","count"=>$count,"data"=>$list];
        }
    }


    //年龄（图）
    public function ageDb(){
        //获取登录用户级别
        $level = Session::get('user_level');
        //获取管理员提交的医生姓名
        $doctor = $this->request->get();
        $doctor = key($doctor);

        //登录用户为管理员
        if($level ==1){
            //获取所有医生名下患者性别
            if($doctor === 0){
                //获取0-10岁患者
                $list10 = Db::table('patients')
                    ->where('age','<=',10)
                    ->select();
                //总条数(0-10)
                $count10=count($list10);

                //获取11-20岁患者
                $list20 = Db::table('patients')
                    ->whereBetween('age','11,20')
                    ->select();
                //总条数(11-20)
                $count20=count($list20);

                //获取21-30岁患者
                $list30 = Db::table('patients')
                    ->whereBetween('age','21,30')
                    ->select();
                //总条数(21-30)
                $count30=count($list30);

                //获取31-40岁患者
                $list40 = Db::table('patients')
                    ->whereBetween('age','31,40')
                    ->select();
                //总条数(31-40)
                $count40=count($list40);

                //获取41-50岁患者
                $list50 = Db::table('patients')
                    ->whereBetween('age','41,50')
                    ->select();
                //总条数(41-50)
                $count50=count($list50);

                //获取51-60岁患者
                $list60 = Db::table('patients')
                    ->whereBetween('age','51,60')
                    ->select();
                //总条数(51-60)
                $count60=count($list60);

                //获取61-70岁患者
                $list70 = Db::table('patients')
                    ->whereBetween('age','61,70')
                    ->select();
                //总条数(61-70)
                $count70=count($list70);

                //获取71-80岁患者
                $list80 = Db::table('patients')
                    ->whereBetween('age','71,80')
                    ->select();
                //总条数(71-80)
                $count80=count($list80);

                //获取80岁以上患者
                $list100 = Db::table('patients')
                    ->where('age','>',80)
                    ->select();
                //总条数(80以上)
                $count100=count($list100);

                //返回数据
                return ['doctor'=>'全部','count10'=>$count10,'count20'=>$count20,'count30'=>$count30,'count40'=>$count40,'count50'=>$count50,'count60'=>$count60,'count70'=>$count70,'count80'=>$count80,'count100'=>$count100];
            }
            //获取特定医生名下患者年龄
            else{
                //获取0-10岁患者
                $list10 = Db::table('patients')
                    ->where('age','<=',10)->where('doctor',$doctor)
                    ->select();
                //总条数(0-10)
                $count10=count($list10);

                //获取11-20岁患者
                $list20 = Db::table('patients')
                    ->where('age','between','11,20')->where('doctor',$doctor)
                    ->select();
                //总条数(11-20)
                $count20=count($list20);

                //获取21-30岁患者
                $list30 = Db::table('patients')
                    ->where('age','between','21,30')->where('doctor',$doctor)
                    ->select();
                //总条数(21-30)
                $count30=count($list30);

                //获取31-40岁患者
                $list40 = Db::table('patients')
                    ->where('age','between','31,40')->where('doctor',$doctor)
                    ->select();
                //总条数(31-40)
                $count40=count($list40);

                //获取41-50岁患者
                $list50 = Db::table('patients')
                    ->where('age','between','41,50')->where('doctor',$doctor)
                    ->select();
                //总条数(41-50)
                $count50=count($list50);

                //获取51-60岁患者
                $list60 = Db::table('patients')
                    ->where('age','between','51,60')->where('doctor',$doctor)
                    ->select();
                //总条数(51-60)
                $count60=count($list60);

                //获取61-70岁患者
                $list70 = Db::table('patients')
                    ->where('age','between','61,70')->where('doctor',$doctor)
                    ->select();
                //总条数(61-70)
                $count70=count($list70);

                //获取71-80岁患者
                $list80 = Db::table('patients')
                    ->where('age','between','71,80')->where('doctor',$doctor)
                    ->select();
                //总条数(71-80)
                $count80=count($list80);

                //获取80岁以上患者
                $list100 = Db::table('patients')
                    ->where('age','>',80)->where('doctor',$doctor)
                    ->select();
                //总条数(80以上)
                $count100=count($list100);

                //返回数据
                return ['doctor'=>$doctor,'count10'=>$count10,'count20'=>$count20,'count30'=>$count30,'count40'=>$count40,'count50'=>$count50,'count60'=>$count60,'count70'=>$count70,'count80'=>$count80,'count100'=>$count100];

            }
        }
        //登录用户为普通用户
        else{
            //获取登录医生姓名
            $doctor = Session::get('user_neckname');
            //获取0-10岁患者
            $list10 = Db::table('patients')
                ->where('age','<=',10)->where('doctor',$doctor)
                ->select();
            //总条数(0-10)
            $count10=count($list10);

            //获取11-20岁患者
            $list20 = Db::table('patients')
                ->whereBetween('age','11,20')->where('doctor',$doctor)
                ->select();
            //总条数(11-20)
            $count20=count($list20);

            //获取21-30岁患者
            $list30 = Db::table('patients')
                ->whereBetween('age','21,30')->where('doctor',$doctor)
                ->select();
            //总条数(21-30)
            $count30=count($list30);

            //获取31-40岁患者
            $list40 = Db::table('patients')
                ->whereBetween('age','31,40')->where('doctor',$doctor)
                ->select();
            //总条数(31-40)
            $count40=count($list40);

            //获取41-50岁患者
            $list50 = Db::table('patients')
                ->whereBetween('age','41,50')->where('doctor',$doctor)
                ->select();
            //总条数(41-50)
            $count50=count($list50);

            //获取51-60岁患者
            $list60 = Db::table('patients')
                ->whereBetween('age','51,60')->where('doctor',$doctor)
                ->select();
            //总条数(51-60)
            $count60=count($list60);

            //获取61-70岁患者
            $list70 = Db::table('patients')
                ->whereBetween('age','61,70')->where('doctor',$doctor)
                ->select();
            //总条数(61-70)
            $count70=count($list70);

            //获取71-80岁患者
            $list80 = Db::table('patients')
                ->whereBetween('age','71,80')->where('doctor',$doctor)
                ->select();
            //总条数(71-80)
            $count80=count($list80);

            //获取80岁以上患者
            $list100 = Db::table('patients')
                ->where('age','>',80)->where('doctor',$doctor)
                ->select();
            //总条数(80以上)
            $count100=count($list100);

            //返回数据
            return ['doctor'=>$doctor,'count10'=>$count10,'count20'=>$count20,'count30'=>$count30,'count40'=>$count40,'count50'=>$count50,'count60'=>$count60,'count70'=>$count70,'count80'=>$count80,'count100'=>$count100];
        }
    }

    //年龄（表格-点击图形筛选数据）
    public function clickListAge(){
        //获取登录用户级别
        $level = Session::get('user_level');
        //获取管理员提交的医生姓名
        $doctor = $this->request->get('doctor');
        //获取用户点击的年龄段
        $age = $this->request->get('click');
        //获取每页显示的条数
        $limit= $this->request->param('limit');
        //获取当前页数
        $page= $this->request->param('page');
        //计算出从那条开始查询
        $tol=($page-1)*$limit;
        //数据库转换识别
        switch ($age)
        {
            case '0-10岁':
                $age = '0,10';
            break;
            case '11-20岁':
                $age = '11,20';
            break;
            case '21-30岁':
                $age = '21,30';
            break;
            case '31-40岁':
                $age = '31,40';
            break;
            case '41-50岁':
                $age = '41,50';
            break;
            case '51-60岁':
                $age = '51,60';
            break;
            case '61-70岁':
                $age = '61,70';
            break;
            case '71-80岁':
                $age = '71,80';
            break;
            case '80岁以上':
                $age = 81;
            break;
        }

        //登录用户为管理员
        if($level ==1){
            //全部医生名下患者
            if($doctor === '0'){
                if($age == 81){
                    //分页-计算数据总数
                    $listall = Db::table('patients')->where('age','>=',$age)->select();
                    $count = count($listall);
                    //每页所显示的数据
                    $list = Db::table('patients')
                        ->where('age','>=',$age)
                        ->limit("$tol","$limit")
                        ->select();
                }else{
                    //分页-计算数据总数
                    $listall = Db::table('patients')->whereBetween('age',$age)->select();
                    $count = count($listall);
                    //每页所显示的数据
                    $list = Db::table('patients')
                        ->whereBetween('age',$age)
                        ->limit("$tol","$limit")
                        ->select();
                }
            }
            //选定医生名下患者
            else{
                if($age == 81){
                    //分页-计算数据总数
                    $listall = Db::table('patients')->where('age','>=',$age)->select();
                    $count = count($listall);
                    //每页所显示的数据
                    $list = Db::table('patients')
                        ->where('doctor',$doctor)
                        ->where('age','>=',$age)
                        ->limit("$tol","$limit")
                        ->select();
                }else{
                    //分页-计算数据总数
                    $listall = Db::table('patients')->where('doctor',$doctor)->whereBetween('age',$age)->select();
                    $count = count($listall);
                    //每页所显示的数据
                    $list = Db::table('patients')
                        ->where('doctor',$doctor)
                        ->whereBetween('age',$age)
                        ->limit("$tol","$limit")
                        ->select();
                }
            }
            //返回数据
            return ["code"=>"0","msg"=>"","count"=>$count,"data"=>$list];
        }
        //登录用户为普通用户
        else{
            //获取登录医生姓名
            $doctor = Session::get('user_neckname');
            if($age == 81){
                //分页-计算数据总数
                $listall = Db::table('patients')->where('age','>=',$age)->select();
                $count = count($listall);
                //每页所显示的数据
                $list = Db::table('patients')
                    ->where('doctor',$doctor)
                    ->where('age','>=',$age)
                    ->limit("$tol","$limit")
                    ->select();
            }else{
                //分页-计算数据总数
                $listall = Db::table('patients')->where('doctor',$doctor)->whereBetween('age',$age)->select();
                $count = count($listall);
                //每页所显示的数据
                $list = Db::table('patients')
                    ->where('doctor',$doctor)
                    ->whereBetween('age',$age)
                    ->limit("$tol","$limit")
                    ->select();
                return ["code"=>"0","msg"=>"","count"=>$count,"data"=>$list];
            }
        }
    }


    //民族（图）
    public function natDb(){
        //获取登录用户级别
        $level = Session::get('user_level');
        //获取管理员提交的医生姓名
        $doctor = $this->request->get();
        $doctor = key($doctor);

        //登录用户为管理员
        if($level ==1){
            //获取所有医生名下患者性别
            if($doctor === 0){
                //获取患者个数
                $list = Db::table('patients')
                    ->select();
                //总条数
                $count=count($list);
                //遍历民族（把所有患者民族合成为一个数组）
                $national =[];
                for ($i=0;$i<$count;$i++){
                    $national[] = $list[$i]['national'];
                }
                //数组去重并统计数量
                $national = array_count_values($national);
                //获取键名
                $nationalKey = array_keys($national);
                //获取键值
                $nationalValue = array_values($national);
                //返回数据
                return['national'=>$national,'doctor'=>'全部','count'=>$count,'nationalKey'=>$nationalKey,'nationalValue'=>$nationalValue];
            }
            //获取特定医生名下患者年龄
            else{
                $list = Db::table('patients')
                    ->where(['doctor'=>$doctor])
                    ->select();
                //总条数
                $count=count($list);
                //遍历民族（把所有患者民族合成为一个数组）
                $national =[];
                for ($i=0;$i<$count;$i++){
                    $national[] = $list[$i]['national'];
                }
                //数组去重并统计数量
                $national = array_count_values($national);
                //获取键名
                $nationalKey = array_keys($national);
                //获取键值
                $nationalValue = array_values($national);
                //返回数据
                return['national'=>$national,'doctor'=>$doctor,'count'=>$count,'nationalKey'=>$nationalKey,'nationalValue'=>$nationalValue];
            }
        }
        //登录用户为普通用户
        else{
            //获取登录医生姓名
            $doctor = Session::get('user_neckname');
            //获取患者个数
            $list = Db::table('patients')
                ->where(['doctor'=>$doctor])
                ->select();
            //总条数
            $count=count($list);
            //遍历民族（把所有患者民族合成为一个数组）
            $national =[];
            for ($i=0;$i<$count;$i++){
                $national[] = $list[$i]['national'];
            }
            //数组去重并统计数量
            $national = array_count_values($national);
            //获取键名
            $nationalKey = array_keys($national);
            //获取键值
            $nationalValue = array_values($national);
            //返回数据
            return['national'=>$national,'doctor'=>$doctor,'count'=>$count,'nationalKey'=>$nationalKey,'nationalValue'=>$nationalValue];
        }
    }

    //民族（表格-点击图形筛选数据）
    public function clickListNat(){
        //获取登录用户级别
        $level = Session::get('user_level');
        //获取管理员提交的医生姓名
        $doctor = $this->request->get('doctor');
        //获取用户点击的民族
        $nat = $this->request->get('click');
        //获取每页显示的条数
        $limit= $this->request->param('limit');
        //获取当前页数
        $page= $this->request->param('page');
        //计算出从那条开始查询
        $tol=($page-1)*$limit;
        //登录用户为管理员
        if($level ==1){
            //全部医生名下患者
            if($doctor === '0'){
                //分页-计算数据总数
                $listall = Db::table('patients')->where('national',$nat)->select();
                $count = count($listall);
                //每页所显示的数据
                $list = Db::table('patients')
                    ->where('national',$nat)
                    ->limit("$tol","$limit")
                    ->select();
            }
            //选定医生名下患者
            else{
                //分页-计算数据总数
                $listall = Db::table('patients')->where('doctor',$doctor)->where('national',$nat)->select();
                $count = count($listall);
                //每页所显示的数据
                $list = Db::table('patients')
                    ->where('doctor',$doctor)
                    ->where('national',$nat)
                    ->limit("$tol","$limit")
                    ->select();
            }
            //返回数据
            return ["code"=>"0","msg"=>"","count"=>$count,"data"=>$list];
        }
        //登录用户为普通用户
        else{
            //获取登录医生姓名
            $doctor = Session::get('user_neckname');
            //分页-计算数据总数
            $listall = Db::table('patients')->where('doctor',$doctor)->where('national',$nat)->select();
            $count = count($listall);
            //每页所显示的数据
            $list = Db::table('patients')
                ->where('doctor',$doctor)
                ->where('national',$nat)
                ->limit("$tol","$limit")
                ->select();
            return ["code"=>"0","msg"=>"","count"=>$count,"data"=>$list];
        }
    }


    //二次入院（图）
    public function sedDb(){
        //获取登录用户级别
        $level = Session::get('user_level');
        //获取管理员提交的医生姓名
        $doctor = $this->request->get();
        $doctor = key($doctor);

        //登录用户为管理员
        if($level ==1){
            //获取所有医生名下患者性别
            if($doctor === 0){
                //获取二次入院患者个数
                $listSecond = Db::table('patients')
                    ->where('second_hospital_in',1)
                    ->select();
                //总条数(二次入院)
                $countSecond=count($listSecond);

                //获取首次入院患者个数
                $listFirst = Db::table('patients')
                    ->where('second_hospital_in',0)
                    ->select();
                //总条数(首次入院)
                $countFirst=count($listFirst);

                //返回数据
                return ["countSecond"=>$countSecond,"countFirst"=>$countFirst,'doctor'=>'全部'];
            }
            //获取特定医生名下患者性别
            else{
                //获取二次入院患者个数
                $listSecond = Db::table('patients')
                    ->where(['doctor'=>$doctor,'second_hospital_in'=>1])
                    ->select();
                //总条数(二次入院)
                $countSecond=count($listSecond);

                //获取首次入院患者个数
                $listFirst = Db::table('patients')
                    ->where(['doctor'=>$doctor,'second_hospital_in'=>0])
                    ->select();
                //总条数(首次入院)
                $countFirst=count($listFirst);

                //返回数据
                return ["countSecond"=>$countSecond,"countFirst"=>$countFirst,"doctor"=>$doctor];

            }
        }
        //登录用户为普通用户
        else{
            //获取登录医生姓名
            $doctor = Session::get('user_neckname');

            //获取二次入院患者个数
            $listSecond = Db::table('patients')
                ->where(['doctor'=>$doctor,'second_hospital_in'=>1])
                ->select();
            //总条数(二次入院)
            $countSecond=count($listSecond);

            //获取首次入院患者个数
            $listFirst = Db::table('patients')
                ->where(['doctor'=>$doctor,'second_hospital_in'=>0])
                ->select();
            //总条数(首次入院)
            $countFirst=count($listFirst);

            //返回数据
            return ["countSecond"=>$countSecond,"countFirst"=>$countFirst,"doctor"=>$doctor];
        }
    }

    //二次入院（表格-点击图形筛选数据）
    public function clickListSed(){
        //获取登录用户级别
        $level = Session::get('user_level');
        //获取管理员提交的医生姓名
        $doctor = $this->request->get('doctor');
        //获取用户点击的性别
        $sed = $this->request->get('click');
        //获取每页显示的条数
        $limit= $this->request->param('limit');
        //获取当前页数
        $page= $this->request->param('page');
        //计算出从那条开始查询
        $tol=($page-1)*$limit;
        //数据库转换识别
        if($sed == '二次入院'){$sed = 1;}elseif($sed == '首次入院'){$sed = 0;}else{$sed = null;}
        //登录用户为管理员
        if($level ==1){
            //全部医生名下患者
            if($doctor === '0'){
                //分页-计算数据总数
                $listall = Db::table('patients')->where('second_hospital_in',$sed)->select();
                $count = count($listall);
                //每页所显示的数据
                $list = Db::table('patients')
                    ->where('second_hospital_in',$sed)
                    ->limit("$tol","$limit")
                    ->select();
            }
            //选定医生名下患者
            else{
                //分页-计算数据总数
                $listall = Db::table('patients')->where('doctor',$doctor)->where('second_hospital_in',$sed)->select();
                $count = count($listall);
                //每页所显示的数据
                $list = Db::table('patients')
                    ->where('doctor',$doctor)
                    ->where('second_hospital_in',$sed)
                    ->limit("$tol","$limit")
                    ->select();
            }
            //返回数据
            return ["code"=>"0","msg"=>"","count"=>$count,"data"=>$list];
        }
        //登录用户为普通用户
        else{
            //获取登录医生姓名
            $doctor = Session::get('user_neckname');
            //分页-计算数据总数
            $listall = Db::table('patients')->where('doctor',$doctor)->where('second_hospital_in',$sed)->select();
            $count = count($listall);
            //每页所显示的数据
            $list = Db::table('patients')
                ->where('doctor',$doctor)
                ->where('second_hospital_in',$sed)
                ->limit("$tol","$limit")
                ->select();
            return ["code"=>"0","msg"=>"","count"=>$count,"data"=>$list];
        }
    }


    //出入院时间（图）
    public function ioDb(){
        //获取登录用户级别
        $level = Session::get('user_level');
        //获取管理员提交的医生姓名
        $doctor = $this->request->get();
        $doctor = key($doctor);

        //登录用户为管理员
        if($level ==1){
            //获取所有医生名下患者性别
            if($doctor === 0){
                //获取入院患者日期
                $listIn = Db::table('patients')
                    ->field('hospitalized_time')
                    ->select();
                //统计入院患者数量
                $countIn = count($listIn);
                //遍历入院日期（把所有入院日期合成为一个数组）
                $arrListIn =[];
                for ($i=0;$i<$countIn;$i++){
                    $arrListIn[] = $listIn[$i]['hospitalized_time'];
                }
                //去重统计日期（入院患者）
                $countIn=array_count_values($arrListIn);
                //获取键名
                $inKey = array_keys($countIn);
                //获取键值
                $inValue = array_values($countIn);

                //获取出院患者日期
                $listOut = Db::table('patients')
                    ->field('leaving_hospital')
                    ->select();
                //统计出院患者数量
                $countOut = count($listOut);
                //遍历出院日期（把所有出院日期合成为一个数组）
                $arrListOut =[];
                for ($i=0;$i<$countOut;$i++){
                    $arrListOut[] = $listOut[$i]['leaving_hospital'];
                }
                //去重统计日期（出院患者）
                $countOut=array_count_values($arrListOut);
                //获取键名
                $outKey = array_keys($countOut);
                //获取键值
                $outValue = array_values($countOut);

                //返回数据
                return ["countIn"=>$countIn,"inKey"=>$inKey,"inValue"=>$inValue,"countOut"=>$countOut,"outKey"=>$outKey,"outValue"=>$outValue,"doctor"=>'全部',];
            }
            //获取特定医生名下患者性别
            else{
                //获取入院患者日期
                $listIn = Db::table('patients')
                    ->field('hospitalized_time')
                    ->where(['doctor'=>$doctor])
                    ->select();
                //统计入院患者数量
                $countIn = count($listIn);
                //遍历入院日期（把所有入院日期合成为一个数组）
                $arrListIn =[];
                for ($i=0;$i<$countIn;$i++){
                    $arrListIn[] = $listIn[$i]['hospitalized_time'];
                }
                //去重统计日期（入院患者）
                $countIn=array_count_values($arrListIn);
                //获取键名
                $inKey = array_keys($countIn);
                //获取键值
                $inValue = array_values($countIn);

                //获取出院患者日期
                $listOut = Db::table('patients')
                    ->field('leaving_hospital')
                    ->where(['doctor'=>$doctor])
                    ->select();
                //统计出院患者数量
                $countOut = count($listOut);
                //遍历出院日期（把所有出院日期合成为一个数组）
                $arrListOut =[];
                for ($i=0;$i<$countOut;$i++){
                    $arrListOut[] = $listOut[$i]['leaving_hospital'];
                }
                //去重统计日期（出院患者）
                $countOut=array_count_values($arrListOut);
                //获取键名
                $outKey = array_keys($countOut);
                //获取键值
                $outValue = array_values($countOut);

                //返回数据
                return ["countIn"=>$countIn,"inKey"=>$inKey,"inValue"=>$inValue,"countOut"=>$countOut,"outKey"=>$outKey,"outValue"=>$outValue,"doctor"=>$doctor,];

            }
        }
        //登录用户为普通用户
        else{
            //获取登录医生姓名
            $doctor = Session::get('user_neckname');

            //获取入院患者日期
            $listIn = Db::table('patients')
                ->field('hospitalized_time')
                ->where(['doctor'=>$doctor])
                ->select();
            //统计入院患者数量
            $countIn = count($listIn);
            //遍历入院日期（把所有入院日期合成为一个数组）
            $arrListIn =[];
            for ($i=0;$i<$countIn;$i++){
                $arrListIn[] = $listIn[$i]['hospitalized_time'];
            }
            //去重统计日期（入院患者）
            $countIn=array_count_values($arrListIn);
            //获取键名
            $inKey = array_keys($countIn);
            //获取键值
            $inValue = array_values($countIn);

            //获取出院患者日期
            $listOut = Db::table('patients')
                ->field('leaving_hospital')
                ->where(['doctor'=>$doctor])
                ->select();
            //统计出院患者数量
            $countOut = count($listOut);
            //遍历出院日期（把所有出院日期合成为一个数组）
            $arrListOut =[];
            for ($i=0;$i<$countOut;$i++){
                $arrListOut[] = $listOut[$i]['leaving_hospital'];
            }
            //去重统计日期（出院患者）
            $countOut=array_count_values($arrListOut);
            //获取键名
            $outKey = array_keys($countOut);
            //获取键值
            $outValue = array_values($countOut);

            //返回数据
            return ["countIn"=>$countIn,"inKey"=>$inKey,"inValue"=>$inValue,"countOut"=>$countOut,"outKey"=>$outKey,"outValue"=>$outValue,"doctor"=>$doctor,];
        }
    }

    //出入院时间（表格-点击图形筛选数据）
    public function clickListIo(){
        //获取登录用户级别
        $level = Session::get('user_level');
        //获取管理员提交的医生姓名
        $doctor = $this->request->get('doctor');
        //获取用户点击的性别
        $io = $this->request->get('click');
        //获取用户点击的图表ID
        $clickIndex = $this->request->get('clickIndex');
        //获取每页显示的条数
        $limit= $this->request->param('limit');
        //获取当前页数
        $page= $this->request->param('page');
        //计算出从那条开始查询
        $tol=($page-1)*$limit;
        //数据库转换识别
        $io = date("Y-m-d",strtotime($io));
        //登录用户为管理员
        if($level ==1){
            //全部医生名下患者
            if($doctor === '0'){
                //用户点击入院日期图
                if($clickIndex == 0){
                    //分页-计算数据总数
                    $listall = Db::table('patients')->where('hospitalized_time',$io)->select();
                    $count = count($listall);
                    //每页所显示的数据
                    $list = Db::table('patients')
                        ->where('hospitalized_time',$io)
                        ->limit("$tol","$limit")
                        ->select();
                }
                //用户点击出院日期图
                else{
                    //分页-计算数据总数
                    $listall = Db::table('patients')->where('leaving_hospital',$io)->select();
                    $count = count($listall);
                    //每页所显示的数据
                    $list = Db::table('patients')
                        ->where('leaving_hospital',$io)
                        ->limit("$tol","$limit")
                        ->select();
                }
            }
            //选定医生名下患者
            else{
                //用户点击入院日期图
                if ($clickIndex == 0){
                    //分页-计算数据总数
                    $listall = Db::table('patients')
                        ->where('hospitalized_time',$io)
                        ->where('doctor',$doctor)
                        ->select();
                    $count = count($listall);
                    //每页所显示的数据
                    $list = Db::table('patients')
                        ->where('hospitalized_time',$io)
                        ->where('doctor',$doctor)
                        ->limit("$tol","$limit")
                        ->select();
                }
                //用户点击出院日期图
                else{
                    //分页-计算数据总数
                    $listall = Db::table('patients')
                        ->where('leaving_hospital',$io)
                        ->where('doctor',$doctor)
                        ->select();
                    $count = count($listall);
                    //每页所显示的数据
                    $list = Db::table('patients')
                        ->where('leaving_hospital',$io)
                        ->where('doctor',$doctor)
                        ->limit("$tol","$limit")
                        ->select();
                }
            }
            //返回数据
            return ["code"=>"0","msg"=>"","count"=>$count,"data"=>$list];
        }
        //登录用户为普通用户
        else{
            //获取登录医生姓名
            $doctor = Session::get('user_neckname');
            //用户点击入院日期图
            if ($clickIndex == 0){
                //分页-计算数据总数
                $listall = Db::table('patients')
                    ->where('hospitalized_time',$io)
                    ->where('doctor',$doctor)
                    ->select();
                $count = count($listall);
                //每页所显示的数据
                $list = Db::table('patients')
                    ->where('hospitalized_time',$io)
                    ->where('doctor',$doctor)
                    ->limit("$tol","$limit")
                    ->select();
            }
            //用户点击出院日期图
            else{
                //分页-计算数据总数
                $listall = Db::table('patients')
                    ->where('leaving_hospital',$io)
                    ->where('doctor',$doctor)
                    ->select();
                $count = count($listall);
                //每页所显示的数据
                $list = Db::table('patients')
                    ->where('leaving_hospital',$io)
                    ->where('doctor',$doctor)
                    ->limit("$tol","$limit")
                    ->select();
            }
            return ["code"=>"0","msg"=>"","count"=>$count,"data"=>$list];
        }
    }


    //患者分布图（地图/右侧柱图）
    public function mapDb(){
        //获取登录用户级别
        $level = Session::get('user_level');
        //获取用户点击的省份及医生姓名
        $getDoctor = $this->request->get();
        //获取医生姓名
        $doctor = key($getDoctor);

        //登录用户为管理员
        if($level ==1){
            //获取所有医生名下患者
            if($doctor === 0){
                //获取点击的省份
                $addr = $getDoctor[0][$doctor];
                //查询数据库并统计省级下的患者数量
                $adress1a = "select adress1a as name,count(*) as value from patients group by adress1a";
                $listAdress1a = Db::query($adress1a);
                //查询数据库并统计市/区级下的患者数量
                $adress1b = "select adress1b as name,count(*) as value from patients where adress1a = '$addr' group by adress1b";
                $adress1c = "select adress1c as name,count(*) as value from patients where adress1b = '$addr' group by adress1c";
                $tempb = Db::query($adress1b);
                $tempc = Db::query($adress1c);
                //市级和区级的判断
                if(empty($tempc)){//如果在区级数据库未查询到值则返回市级数据库的值
                    $listAdress1b = Db::query($adress1b);
                }
                elseif (empty($tempb)){
                    $listAdress1b = Db::query($adress1c);
                }

                //统计个数
                $count1a = count($listAdress1a);
                $count1b = count($listAdress1b);
                //遍历省份数据（把所有省份患者个数合成为一个数组）
                $listAdress1aName =[];
                $listAdress1aValue =[];
                for ($i=0;$i<$count1a;$i++){
                    $listAdress1aName[] = $listAdress1a[$i]['name'];
                    $listAdress1aValue[] = $listAdress1a[$i]['value'];
                }
                //遍历市级数据（把所有市级患者个数合成为一个数组）
                $listAdress1bName =[];
                $listAdress1bValue =[];
                for ($i=0;$i<$count1b;$i++){
                    $listAdress1bName[] = $listAdress1b[$i]['name'];
                    $listAdress1bValue[] = $listAdress1b[$i]['value'];
                }
                return ['doctor'=>'全部','listAdress1a'=>$listAdress1a,'listAdress1b'=>$listAdress1b,'listAdress1aName'=>$listAdress1aName,'listAdress1aValue'=>$listAdress1aValue,'get'=>$doctor,'addr'=>$addr,'listAdress1bName'=>$listAdress1bName,'listAdress1bValue'=>$listAdress1bValue];
            }
            //获取特定医生名下患者
            else{

                //获取点击的省份
                $addr = $getDoctor[$doctor][0];

                //查询数据库并统计省级下的患者数量
                $adress1a = "select adress1a as name,count(*) as value from patients where doctor ='$doctor' group by adress1a";
                $listAdress1a = Db::query($adress1a);
                //查询数据库并统计市/区级下的患者数量
                $adress1b = "select adress1b as name,count(*) as value from patients where doctor ='$doctor'and adress1a = '$addr' group by adress1b";
                $adress1c = "select adress1c as name,count(*) as value from patients where doctor ='$doctor'and adress1b = '$addr' group by adress1c";
                $tempa = Db::query($adress1a);
                $tempb = Db::query($adress1b);
                $tempc = Db::query($adress1c);
                //市级和区级的判断
                if(empty($tempc)){//如果在区级数据库未查询到值则返回市级数据库的值
                    $listAdress1b = Db::query($adress1b);
                }
                elseif (empty($tempb)){
                    $listAdress1b = Db::query($adress1c);
                }
                //统计个数
                $count1a = count($listAdress1a);
                $count1b = count($listAdress1b);
                //遍历省份数据（把所有省份患者个数合成为一个数组）
                $listAdress1aName =[];
                $listAdress1aValue =[];
                for ($i=0;$i<$count1a;$i++){
                    $listAdress1aName[] = $listAdress1a[$i]['name'];
                    $listAdress1aValue[] = $listAdress1a[$i]['value'];
                }
                //遍历市级数据（把所有市级患者个数合成为一个数组）
                $listAdress1bName =[];
                $listAdress1bValue =[];
                for ($i=0;$i<$count1b;$i++){
                    $listAdress1bName[] = $listAdress1b[$i]['name'];
                    $listAdress1bValue[] = $listAdress1b[$i]['value'];
                }
                return ['doctor'=>$doctor,'listAdress1a'=>$listAdress1a,'listAdress1b'=>$listAdress1b,'listAdress1aName'=>$listAdress1aName,'listAdress1aValue'=>$listAdress1aValue,'get'=>$doctor,'addr'=>$addr,'listAdress1bName'=>$listAdress1bName,'listAdress1bValue'=>$listAdress1bValue];
            }
        }
        //登录用户为普通用户
        else{
            //获取登录医生姓名
            $doctor = Session::get('user_neckname');
            //获取点击的省份
            $addr = $getDoctor[$doctor][0];

            //查询数据库并统计省级下的患者数量
            $adress1a = "select adress1a as name,count(*) as value from patients where doctor ='$doctor' group by adress1a";
            $listAdress1a = Db::query($adress1a);
            //查询数据库并统计市/区级下的患者数量
            $adress1b = "select adress1b as name,count(*) as value from patients where doctor ='$doctor'and adress1a = '$addr' group by adress1b";
            $adress1c = "select adress1c as name,count(*) as value from patients where doctor ='$doctor'and adress1b = '$addr' group by adress1c";
            $tempa = Db::query($adress1a);
            $tempb = Db::query($adress1b);
            $tempc = Db::query($adress1c);
            //市级和区级的判断
            if(empty($tempc)){//如果在区级数据库未查询到值则返回市级数据库的值
                $listAdress1b = Db::query($adress1b);
            }
            elseif (empty($tempb)){
                $listAdress1b = Db::query($adress1c);
            }
            //统计个数
            $count1a = count($listAdress1a);
            $count1b = count($listAdress1b);
            //遍历省份数据（把所有省份患者个数合成为一个数组）
            $listAdress1aName =[];
            $listAdress1aValue =[];
            for ($i=0;$i<$count1a;$i++){
                $listAdress1aName[] = $listAdress1a[$i]['name'];
                $listAdress1aValue[] = $listAdress1a[$i]['value'];
            }
            //遍历市级数据（把所有市级患者个数合成为一个数组）
            $listAdress1bName =[];
            $listAdress1bValue =[];
            for ($i=0;$i<$count1b;$i++){
                $listAdress1bName[] = $listAdress1b[$i]['name'];
                $listAdress1bValue[] = $listAdress1b[$i]['value'];
            }
            return ['doctor'=>$doctor,'listAdress1a'=>$listAdress1a,'listAdress1b'=>$listAdress1b,'listAdress1aName'=>$listAdress1aName,'listAdress1aValue'=>$listAdress1aValue,'get'=>$doctor,'addr'=>$addr,'listAdress1bName'=>$listAdress1bName,'listAdress1bValue'=>$listAdress1bValue];

        }
    }

    //患者分布图（表格-右侧柱图-点击图形筛选数据）
    public function clickListRightMap(){
        //获取登录用户级别
        $level = Session::get('user_level');
        //获取管理员提交的医生姓名
        $doctor = $this->request->get('doctor');
        //获取用户点击的省份
        $addr = $this->request->get('click');
        //获取每页显示的条数
        $limit= $this->request->param('limit');
        //获取当前页数
        $page= $this->request->param('page');
        //计算出从那条开始查询
        $tol=($page-1)*$limit;
        //登录用户为管理员
        if($level ==1){
            //全部医生名下患者
            if($doctor === '0'){
                //分页-计算数据总数
                $listalla = Db::table('patients')->where('adress1a',$addr)->select();
                $listallb = Db::table('patients')->where('adress1b',$addr)->select();
                $listallc = Db::table('patients')->where('adress1c',$addr)->select();
                if (empty($listallb) && empty($listallc)){
                    $count = count($listalla);
                    //每页所显示的数据
                    $list = Db::table('patients')
                        ->where('adress1a',$addr)
                        ->limit("$tol","$limit")
                        ->select();
                }
                else if (empty($listalla) && empty($listallc)){
                    $count = count($listallb);
                    //每页所显示的数据
                    $list = Db::table('patients')
                        ->where('adress1b',$addr)
                        ->limit("$tol","$limit")
                        ->select();
                }
                else{
                    $count = count($listallc);
                    //每页所显示的数据
                    $list = Db::table('patients')
                        ->where('adress1c',$addr)
                        ->limit("$tol","$limit")
                        ->select();
                }
                return ["code"=>"0","msg"=>"","count"=>$count,"data"=>$list];

            }
            //选定医生名下患者
            else{
                //分页-计算数据总数
                $listalla = Db::table('patients')->where('doctor',$doctor)->where('adress1a',$addr)->select();
                $listallb = Db::table('patients')->where('doctor',$doctor)->where('adress1b',$addr)->select();
                $listallc = Db::table('patients')->where('doctor',$doctor)->where('adress1c',$addr)->select();
                if (empty($listallb) && empty($listallc)){
                    $count = count($listalla);
                    //每页所显示的数据
                    $list = Db::table('patients')
                        ->where('adress1a',$addr)
                        ->where('doctor',$doctor)
                        ->limit("$tol","$limit")
                        ->select();
                }
                else if (empty($listalla) && empty($listallc)){
                    $count = count($listallb);
                    //每页所显示的数据
                    $list = Db::table('patients')
                        ->where('adress1b',$addr)
                        ->where('doctor',$doctor)
                        ->limit("$tol","$limit")
                        ->select();
                }
                else{
                    $count = count($listallc);
                    //每页所显示的数据
                    $list = Db::table('patients')
                        ->where('adress1c',$addr)
                        ->where('doctor',$doctor)
                        ->limit("$tol","$limit")
                        ->select();
                }

            }
            //返回数据
            return ["code"=>"0","msg"=>"","count"=>$count,"data"=>$list];
        }
        //登录用户为普通用户
        else{
            //获取登录医生姓名
            $doctor = Session::get('user_neckname');
            //分页-计算数据总数
            $listalla = Db::table('patients')->where('doctor',$doctor)->where('adress1a',$addr)->select();
            $listallb = Db::table('patients')->where('doctor',$doctor)->where('adress1b',$addr)->select();
            $listallc = Db::table('patients')->where('doctor',$doctor)->where('adress1c',$addr)->select();
            if (empty($listallb) && empty($listallc)){
                $count = count($listalla);
                //每页所显示的数据
                $list = Db::table('patients')
                    ->where('adress1a',$addr)
                    ->where('doctor',$doctor)
                    ->limit("$tol","$limit")
                    ->select();
            }
            else if (empty($listalla) && empty($listallc)){
                $count = count($listallb);
                //每页所显示的数据
                $list = Db::table('patients')
                    ->where('adress1b',$addr)
                    ->where('doctor',$doctor)
                    ->limit("$tol","$limit")
                    ->select();
            }
            else{
                $count = count($listallc);
                //每页所显示的数据
                $list = Db::table('patients')
                    ->where('adress1c',$addr)
                    ->where('doctor',$doctor)
                    ->limit("$tol","$limit")
                    ->select();
            }
            return ["code"=>"0","msg"=>"","count"=>$count,"data"=>$list];
        }
    }

}