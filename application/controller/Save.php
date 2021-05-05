<?php


namespace app\controller;
use app\controller\Base;
use app\model\History_surgical;
use app\model\Patients;
use app\model\History_self;
use app\model\History_menstrual;
use app\model\History_family;
use think\Db;
use app\model\User;
use think\facade\Request;
use think\db\Where;
use think\facade\Session;

class Save extends Base
{
    //患者信息保存模块
    public function savepatients(){
        $patientsid = $this->request->param('sys');
        $patientsname = $this->request->param('name');
        $patientsidcard = $this->request->param('idcard');
        $patientsphone = $this->request->param('phone');
        $patientssex = $this->request->param('RadioSex');
        $patientsdate = $this->request->param('date');
        $patientsage = $this->request->param('age');
        $patientsethnic = $this->request->param('ethnic');
        $patientsheight = $this->request->param('height');
        $patientsweight = $this->request->param('weight');
        $patientsmarry = $this->request->param('marry');
        $patientsadress1a = $this->request->param('adress1a');
        $patientsadress1b = $this->request->param('adress1b');
        $patientsadress1c = $this->request->param('adress1c');
        $patientsadress2 = $this->request->param('adress2');
        $patientshospitalid = $this->request->param('hospitalid');
        $patientsphotoid = $this->request->param('photoid');
        $patientspathologicalid = $this->request->param('pathologicalid');
        $patientsRadioSedIn = $this->request->param('RadioSedIn');
        $patientsinhospital = $this->request->param('inhospital');
        $patientsouthospital = $this->request->param('outhospital');
        $patientsoutto = $this->request->param('outto');
        $patientsdoctor = $this->request->param('doctor');
        if ($patientsdate == null){
            $patientsdate = '2020-01-01';
        }
        if ($patientsinhospital == null){
            $patientsinhospital = '2020-01-01';
        }
        if ($patientsouthospital == null){
            $patientsouthospital = '2020-01-01';
        }
        $data=[
            'patients_name'=>$patientsname,
            'second_hospital_in'=>$patientsRadioSedIn,
            'birthday'=>$patientsdate,
            'national'=>$patientsethnic,
            'height'=>$patientsheight,
            'weight'=>$patientsweight,
            'hospitalized_time'=>$patientsinhospital,
            'leaving_hospital'=>$patientsouthospital,
            'hospital_out_to'=>$patientsoutto,
            'adress1a'=>$patientsadress1a,
            'adress1b'=>$patientsadress1b,
            'adress1c'=>$patientsadress1c,
            'adress2'=>$patientsadress2,
            'phone'=>$patientsphone,
            'hospital_id'=>$patientshospitalid,
            'id_photo_num'=>$patientsphotoid,
            'id_pathological'=>$patientspathologicalid,
            'identity_card'=>$patientsidcard,
            'sex'=>$patientssex,
            'age'=>$patientsage,
            'married'=>$patientsmarry,
            'doctor'=>$patientsdoctor
        ];
        $where=[
            'patients_id'=>$patientsid
        ];
        $field=[
            'patients_id','patients_name','phone','second_hospital_in','birthday','national','height','weight',
            'hospitalized_time','leaving_hospital','hospital_out_to','adress1a','adress1b','adress1c','adress2','hospital_id','id_photo_num',
            'id_pathological','identity_card','sex','age','married','doctor'
        ];
        //查询患者ID是否已经存在
        $is_old = Patients::where('patients_id',$patientsid)->find();

        if ($is_old){
            Patients::update($data,$where,$field);
        }
        else{
            Db::name('patients')->insert($data);
        }

        $this->redirect('/menu/patients',array('patientsid'=>$patientsid));
    }


    //患者既往病史保存模块
    public function history(){
        //获取病人系统ID
        $patientsid = $this->request->param('patientsid');
        //【术前合并症】前端传入数据
        $xunhuan = $this->request->param('radioxunhuan');
        $huxi = $this->request->param('radiohuxi');
        $xiaohua = $this->request->param('radioxiaohua');
        $miliao = $this->request->param('radiomiliao');
        $neifenmi = $this->request->param('radioneifenmi');
        $mianyi = $this->request->param('radiomianyi');
        $xueye = $this->request->param('radioxueye');
        $shuqianother = $this->request->param('radioother');
        $ixunhuan = $this->request->param('ixunhuan');
        $ihuxi = $this->request->param('ihuxi');
        $ixiaohua = $this->request->param('ixiaohua');
        $imiliao = $this->request->param('imiliao');
        $ineifenmi = $this->request->param('ineifenmi');
        $imianyi = $this->request->param('imianyi');
        $ixueye = $this->request->param('ixueye');
        $ishuqianother = $this->request->param('iother');
        //【个人史】前端传入数据
        $xiyan = $this->request->param('radioxiyan');
        $yingjiu = $this->request->param('radioyingjiu');
        $shoushu = $this->request->param('radioshoushu');
        $zhongliu = $this->request->param('radiozhongliu');
        $zhiai = $this->request->param('radiozhiai');
        $ixiyan = $this->request->param('ixiyan');
        $iyingjiu = $this->request->param('iyingjiu');
        $ishoushu = $this->request->param('ishoushu');
        $izhongliu = $this->request->param('izhongliu');
        $izhiai = $this->request->param('izhiai');
        //【个人史-月经】前端传入数据
        $firstage = $this->request->param('firstage');
        $endage = $this->request->param('endage');
        $guilv = $this->request->param('radioguilv');
        //【家族史】前端传入数据
        $radiojiazu = $this->request->param('radiojiazu');
        $toujing = $this->request->param('toujing');
        $shiguan = $this->request->param('shiguan');
        $fei = $this->request->param('fei');
        $zongge = $this->request->param('zongge');
        $wei = $this->request->param('wei');
        $ganzhang = $this->request->param('ganzhang');
        $yixian = $this->request->param('yixian');
        $xiaochang = $this->request->param('xiaochang');
        $jiechang = $this->request->param('jiechang');
        $zhichang = $this->request->param('zhichang');
        $sheng = $this->request->param('sheng');
        $pangguang = $this->request->param('pangguang');
        $qianliexian = $this->request->param('qianliexian');
        $shengzhi = $this->request->param('shengzhi');
        $lingba = $this->request->param('lingba');
        $ruxian = $this->request->param('ruxian');
        $zigong = $this->request->param('zigong');
        $heise = $this->request->param('heise');
        $rouliu = $this->request->param('rouliu');
        $othercheck = $this->request->param('othercheck');
        $iothercheck = $this->request->param('iothercheck');


        //【术前合并症】 数据表
        //根据病人id查询数据库
        $result = Db::table('History_surgical')
            ->field('patients_id')
            ->where('patients_id','=',$patientsid)
            ->find();
        //如果数据库有这条数据就执行更新
        if ($result){
            $data=[
            'circular'=>$xunhuan,
            'respiratory'=>$huxi,
            'digestive'=>$xiaohua,
            'urologic'=>$miliao,
            'endocrinological'=>$neifenmi,
            'immune'=>$mianyi,
            'blood'=>$xueye,
            'others'=>$shuqianother,
            'icircular'=>$ixunhuan,
            'irespiratory'=>$ihuxi,
            'idigestive'=>$ixiaohua,
            'iurologic'=>$imiliao,
            'iendocrinological'=>$ineifenmi,
            'iimmune'=>$imianyi,
            'iblood'=>$ixueye,
            'iothers'=>$ishuqianother,
            ];
            $where=[
                'patients_id'=>$patientsid
            ];
            $field=[
                'circular','respiratory','digestive','urologic','endocrinological','immune','blood','others','icircular','irespiratory','idigestive','iurologic','iendocrinological','iimmune','iblood','iothers'
            ];
            History_surgical::update($data,$where,$field);
        }
        //如果数据库没有则执行添加
        else{
            History_surgical::create([
            'patients_id'=>$patientsid,
            'circular'=>$xunhuan,
            'respiratory'=>$huxi,
            'digestive'=>$xiaohua,
            'urologic'=>$miliao,
            'endocrinological'=>$neifenmi,
            'immune'=>$mianyi,
            'blood'=>$xueye,
            'others'=>$shuqianother,
            'icircular'=>$ixunhuan,
            'irespiratory'=>$ihuxi,
            'idigestive'=>$ixiaohua,
            'iurologic'=>$imiliao,
            'iendocrinological'=>$ineifenmi,
            'iimmune'=>$imianyi,
            'iblood'=>$ixueye,
            'iothers'=>$ishuqianother,]
            );
        }


        //【个人史】 数据表
        //根据病人id查询数据库
        $result = Db::table('History_self')
            ->field('patients_id')
            ->where('patients_id','=',$patientsid)
            ->find();
        //如果数据库有这条数据就执行更新
        if ($result){
            $data=[
                'smoking'=>$xiyan,
                'drinking'=>$yingjiu,
                'surgical'=>$shoushu,
                'tumor'=>$zhongliu,
                'carcinogenic_factors'=>$zhiai,
                'ismoking'=>$ixiyan,
                'idrinking'=>$iyingjiu,
                'isurgical'=>$ishoushu,
                'itumor'=>$izhongliu,
                'icarcinogenic_factors'=>$izhiai,
            ];
            $where=[
                'patients_id'=>$patientsid
            ];
            $field=[
                'smoking','drinking','surgical','tumor','carcinogenic_factors','ismoking','idrinking','isurgical','itumor','icarcinogenic_factors'
            ];
            History_self::update($data,$where,$field);
        }
        //如果数据库没有则执行添加
        else{
            History_self::create([
                    'patients_id'=>$patientsid,
                    'smoking'=>$xiyan,
                    'drinking'=>$yingjiu,
                    'surgical'=>$shoushu,
                    'tumor'=>$zhongliu,
                    'carcinogenic_factors'=>$zhiai,
                    'ismoking'=>$ixiyan,
                    'idrinking'=>$iyingjiu,
                    'isurgical'=>$ishoushu,
                    'itumor'=>$izhongliu,
                    'icarcinogenic_factors'=>$izhiai,]
            );
        }


        //【月经史】 数据表
        //根据病人id查询数据库
        $result = Db::table('History_menstrual')
            ->field('patients_id')
            ->where('patients_id','=',$patientsid)
            ->find();
        //如果数据库有这条数据就执行更新
        if ($result){
            $data=[
                'firstage'=>$firstage,
                'endage'=>$endage,
                'regular'=>$guilv,
            ];
            $where=[
                'patients_id'=>$patientsid
            ];
            $field=[
                'firstage','endage','regular'
            ];
            History_menstrual::update($data,$where,$field);
        }
        //如果数据库没有则执行添加
        else{
            History_menstrual::create([
                    'patients_id'=>$patientsid,
                    'firstage'=>$firstage,
                    'endage'=>$endage,
                    'regular'=>$guilv]
            );
        }


        //【家族史】 数据表
        //根据病人id查询数据库
        $result = Db::table('History_family')
            ->field('patients_id')
            ->where('patients_id','=',$patientsid)
            ->find();
        //如果数据库有这条数据就执行更新
        if ($result){
            $data=[
                'jiazu'=>$radiojiazu,
                'head_neck'=>$toujing,
                'esophageal'=>$shiguan,
                'lung'=>$fei,
                'mediastinal'=>$zongge,
                'stomach'=>$wei,
                'hepatic'=>$ganzhang,
                'pancreatic'=>$yixian,
                'small_intestina'=>$xiaochang,
                'colonic'=>$jiechang,
                'transrectal'=>$zhichang,
                'renal'=>$sheng,
                'vesical'=>$pangguang,
                'prostatic'=>$qianliexian,
                'reproductive_system'=>$shengzhi,
                'lymphatic_system'=>$lingba,
                'galactophore'=>$ruxian,
                'uterine'=>$zigong,
                'melanoma'=>$heise,
                'sarcoma'=>$rouliu,
                'others'=>$othercheck,
                'iothercheck'=>$iothercheck,
            ];
            $where=[
                'patients_id'=>$patientsid
            ];
            $field=[
                'jiazu','head_neck','esophageal','lung','mediastinal','stomach','hepatic','pancreatic','small_intestina','colonic','transrectal','renal','vesical','prostatic','reproductive_system','lymphatic_system','galactophore','uterine','melanoma','sarcoma','others','iothercheck'
            ];
            History_family::update($data,$where,$field);
        }
        //如果数据库没有则执行添加
        else{
            History_family::create([
                    'patients_id'=>$patientsid,
                    'jiazu'=>$radiojiazu,
                    'head_neck'=>$toujing,
                    'esophageal'=>$shiguan,
                    'lung'=>$fei,
                    'mediastinal'=>$zongge,
                    'stomach'=>$wei,
                    'hepatic'=>$ganzhang,
                    'pancreatic'=>$yixian,
                    'small_intestina'=>$xiaochang,
                    'colonic'=>$jiechang,
                    'transrectal'=>$zhichang,
                    'renal'=>$sheng,
                    'vesical'=>$pangguang,
                    'prostatic'=>$qianliexian,
                    'reproductive_system'=>$shengzhi,
                    'lymphatic_system'=>$lingba,
                    'galactophore'=>$ruxian,
                    'uterine'=>$zigong,
                    'melanoma'=>$heise,
                    'sarcoma'=>$rouliu,
                    'others'=>$othercheck,
                    'iothercheck'=>$iothercheck,]
            );
        }

        //页面重定向
        $this->redirect('/menu/history',array('patientsid'=>$patientsid));

    }


    //新建用户模块
    public function saveUser(){
        $postdata = $this->request->param();
        $name = $postdata['username'];
        $user_age = $postdata['age'];
        $user_sex = $postdata['sex'];
        $level = $postdata['level'];
        $password = $postdata['password'];
        $neckname = $postdata['neckname'];
        $user_class = $postdata['userClass'];
        $user_level = $postdata['userLevel'];
        $face_img = $postdata['img'];
        $active = $postdata['active'];
        $user_reg_date = date("Y-m-d");
        $result = Db::table('user')
            ->where('name',$name)->whereOr('neckname',$neckname)
            ->select();
        if ($result){
            return['msg'=>'用户名或医生姓名已存在！请重新输入','status'=>200];
        }else{
            $res = User::create(['name'=>$name,
                'user_age'=>$user_age,
                'user_sex'=>$user_sex,
                'level'=>$level,
                'password'=>$password,
                'neckname'=>$neckname,
                'user_class'=>$user_class,
                'user_level'=>$user_level,
                'face_img'=>$face_img,
                'active'=>$active,
                'user_reg_date'=>$user_reg_date
            ]);
            if ($res){
                return['msg'=>'添加新用户成功,请勿重复点击','status'=>100];
            }else{
                return['msg'=>'添加失败！请重试！','status'=>200];
            }
        }
    }


}