<?php


namespace app\controller;
use app\controller\Base;
use app\model\History_menstrual;
use app\model\History_self;
use app\model\History_surgical;
use app\model\Patients;
use app\model\History_family;
use app\model\Preoperative_ct;
use app\model\Tizheng;
use app\model\Zhengzhuang;
use app\model\Preoperative_xuechanggui;
use app\model\Preoperative_xueshenghua;
use app\model\Preoperative_xueliubiaozhiwu;
use think\Db;
use app\model\User;
use think\Request;
use think\db\Where;
use think\facade\Session;
use think\View;


class Menu extends Base
{
    //患者信息页面
    public function patients(){

        $this->isLogin();

        $patientsid = $this->request->param('patientsid');
        Session::set('patientsid',null);
        Session::set('patientsid',$patientsid);
        $is_old = Patients::where('patients_id',$patientsid)->find();
        $sql = "select * from user";
        $doctor = Db::query($sql);


        //判断是否已经有此患者
        if ($is_old){
            $where = function ($query) use ($patientsid) {
                $query->field([
                    'patients_name','phone','second_hospital_in','birthday','national','height','weight',
                    'hospitalized_time','leaving_hospital','hospital_out_to','adress1a','adress1b','adress1c','adress2','hospital_id','id_photo_num',
                    'id_pathological','identity_card','sex','age','married','doctor','created_time','edit_time'
                ])->where('patients_id','=',$patientsid);
            };
            $result = Patients::all($where);
            //左侧边栏患者名设置缓存
            Session::set('patientsname',$result[0]['patients_name']);

            $this->assign([
                'doctor'=>$doctor,
                'patientid'=>$patientsid,
                'patients_name'=>$result
            ]);
            return $this->fetch();
        }
        $this->assign(['patientid'=>$patientsid,'patients_name'=>null,'doctor'=>$doctor]);
        return $this->fetch();

    }

    //既往病史
    public function history(){

        $this->isLogin();
        $this->isOld();
        //公共部分--开始
        $patientsid = $this->request->param('patientsid');
        $where = function ($query) use ($patientsid) {
            $query->field(['patients_name'])->where('patients_id','=',$patientsid);
        };
        $patientsname = Patients::all($where);
        Session::set('patientsname',$patientsname[0]['patients_name']);
        //公共部分-结束

        //术前合并症数据库
        $whereshuqian = function ($query) use ($patientsid) {
            $query->field(['circular','respiratory','digestive','urologic','endocrinological','immune','blood','others','icircular','irespiratory','idigestive','iurologic','iendocrinological','iimmune','iblood','iothers','created_time','edit_time'])
                ->where('patients_id','=',$patientsid);
        };

        $resultshuqian = History_surgical::all($whereshuqian);
        //个人史数据库
        $wheregeren = function ($query) use ($patientsid) {
            $query->field(['smoking','drinking','surgical','tumor','carcinogenic_factors','ismoking','idrinking','isurgical','itumor','icarcinogenic_factors','created_time','edit_time'])
                ->where('patients_id','=',$patientsid);
        };

        $resultgeren = History_self::all($wheregeren);
        //月经史数据库
        $whereyuejing = function ($query) use ($patientsid) {
            $query->field(['firstage','endage','regular'])->where('patients_id','=',$patientsid);
        };

        $resultyuejing = History_menstrual::all($whereyuejing);
        //家族史数据库
        $wherejiazu = function ($query) use ($patientsid) {
            $query->field(['jiazu','head_neck','esophageal','lung','mediastinal','stomach','hepatic','pancreatic','small_intestina','colonic','transrectal','renal','vesical','prostatic','reproductive_system','lymphatic_system','galactophore','uterine','melanoma','sarcoma','others','iothercheck','created_time','edit_time'])->where('patients_id','=',$patientsid);
        };

        $resultjiazu = History_family::all($wherejiazu);
        //前端赋值
        $this->assign([
            'patientid'=>$patientsid,
            'patients_name'=>$patientsname,
            'resultshuqian'=>$resultshuqian,
            'resultgeren'=>$resultgeren,
            'resultyuejing'=>$resultyuejing,
            'resultjiazu'=>$resultjiazu,
        ]);
        return $this->fetch();
    }

    //症状体征
    public function symptomatic(){
        $this->isLogin();
        $this->isOld();
        //公共部分--开始
        $patientsid = $this->request->param('patientsid');
        $where = function ($query) use ($patientsid) {
            $query->field('patients_name')->where('patients_id','=',$patientsid);
        };
        $patientsname = Patients::all($where);
        Session::set('patientsname',$patientsname[0]['patients_name']);
        //公共部分-结束

        //查询数据库-症状
        $res_zhengzhuang = Zhengzhuang::where('patients_id',$patientsid)->find();
        if ($res_zhengzhuang == null){
            $this->assign(['zhengzhuang_info'=>null]);
        }else{
            $this->assign(['zhengzhuang_info'=>$res_zhengzhuang]);
        }

        //查询数据库-体征
        $res_tizheng = Tizheng::where('patients_id',$patientsid)->find();
        if ($res_tizheng == null){
            $this->assign(['tizheng_info'=>null]);
        }else{
            $this->assign(['tizheng_info'=>$res_tizheng]);
        }

        $this->assign([
            'patientid'=>$patientsid,
            'patients_name'=>$patientsname
        ]);
        return $this->fetch();
    }

    //术前检查
    public function preoperative(){
        $this->isLogin();
        $this->isOld();

        //公共部分--开始
        $patientsid = $this->request->param('patientsid');

        $patientsname = Patients::where('patients_id',$patientsid)->field('patients_name')->select();

        Session::set('patientsname',$patientsname[0]['patients_name']);
        //公共部分-结束

        //查询数据库-ct
        $res_ct = Preoperative_ct::where('patients_id',$patientsid)->order('ct_id','desc')->select();
        $res_xuechanggui = Preoperative_xuechanggui::where('patients_id',$patientsid)->order('xuechanggui_id','desc')->select();
        $res_xueshenghua = Preoperative_xueshenghua::where('patients_id',$patientsid)->order('xueshenghua_id','desc')->select();
        $res_xueliubiaozhiwu = Preoperative_xueliubiaozhiwu::where('patients_id',$patientsid)->order('xueliubiaozhiwu_id','desc')->select();

        $this->assign([
            'patientid'=>$patientsid,
            'patients_name'=>$patientsname,
            'ct_info'=>$res_ct,
            'xuechanggui_info'=>$res_xuechanggui,
            'xueshenghua_info'=>$res_xueshenghua,
            'xueliubiaozhiwu_info'=>$res_xueliubiaozhiwu,

        ]);
        return $this->fetch();
    }

    //诱导治疗
    public function inductionTherapy(){
        $this->isLogin();
        $this->isOld();
        //公共部分--开始
        $patientsid = $this->request->param('patientsid');

        $where = function ($query) use ($patientsid) {
            $query->field('patients_name')->where('patients_id','=',$patientsid);
        };

        $patientsname = Patients::all($where);

        Session::set('patientsname',$patientsname[0]['patients_name']);
        //公共部分-结束
        $this->assign([
            'patientid'=>$patientsid,
            'patients_name'=>$patientsname
        ]);
        return $this->fetch();
    }

    //手术信息
    public function surgicalInformation(){
        $this->isLogin();
        $this->isOld();
        //公共部分--开始
        $patientsid = $this->request->param('patientsid');

        $where = function ($query) use ($patientsid) {
            $query->field('patients_name')->where('patients_id','=',$patientsid);
        };

        $patientsname = Patients::all($where);

        Session::set('patientsname',$patientsname[0]['patients_name']);
        //公共部分-结束
        $this->assign([
            'patientid'=>$patientsid,
            'patients_name'=>$patientsname
        ]);
        return $this->fetch();
    }

    //术后并发症
    public function postoperativeComplications(){
        $this->isLogin();
        $this->isOld();
        //公共部分--开始
        $patientsid = $this->request->param('patientsid');

        $where = function ($query) use ($patientsid) {
            $query->field('patients_name')->where('patients_id','=',$patientsid);
        };

        $patientsname = Patients::all($where);

        Session::set('patientsname',$patientsname[0]['patients_name']);
        //公共部分-结束
        $this->assign([
            'patientid'=>$patientsid,
            'patients_name'=>$patientsname
        ]);
        return $this->fetch();
    }

    //术后病理
    public function postoperativePathology(){
        $this->isLogin();
        $this->isOld();
        //公共部分--开始
        $patientsid = $this->request->param('patientsid');

        $where = function ($query) use ($patientsid) {
            $query->field('patients_name')->where('patients_id','=',$patientsid);
        };

        $patientsname = Patients::all($where);

        Session::set('patientsname',$patientsname[0]['patients_name']);
        //公共部分-结束
        $this->assign([
            'patientid'=>$patientsid,
            'patients_name'=>$patientsname
        ]);
        return $this->fetch();
    }

    //辅助治疗
    public function complementaryTreatment(){
        $this->isLogin();
        $this->isOld();
        //公共部分--开始
        $patientsid = $this->request->param('patientsid');

        $where = function ($query) use ($patientsid) {
            $query->field('patients_name')->where('patients_id','=',$patientsid);
        };

        $patientsname = Patients::all($where);

        Session::set('patientsname',$patientsname[0]['patients_name']);
        //公共部分-结束
        $this->assign([
            'patientid'=>$patientsid,
            'patients_name'=>$patientsname
        ]);
        return $this->fetch();
    }

    //末次随访
    public function follow(){
        $this->isLogin();
        $this->isOld();
        //公共部分--开始
        $patientsid = $this->request->param('patientsid');

        $where = function ($query) use ($patientsid) {
            $query->field('patients_name')->where('patients_id','=',$patientsid);
        };

        $patientsname = Patients::all($where);

        Session::set('patientsname',$patientsname[0]['patients_name']);
        //公共部分-结束
        $this->assign([
            'patientid'=>$patientsid,
            'patients_name'=>$patientsname
        ]);
        return $this->fetch();
    }

    //复诊信息
    public function referral(){
        $this->isLogin();
        $this->isOld();
        //公共部分--开始
        $patientsid = $this->request->param('patientsid');

        $where = function ($query) use ($patientsid) {
            $query->field('patients_name')->where('patients_id','=',$patientsid);
        };

        $patientsname = Patients::all($where);

        Session::set('patientsname',$patientsname[0]['patients_name']);
        //公共部分-结束
        $this->assign([
            'patientid'=>$patientsid,
            'patients_name'=>$patientsname
        ]);
        return $this->fetch();
    }

    //测试
    public function ceshi(){
        $patientsid = 1000106;
        //查询数据库-症状
        $res_ct = Preoperative_xuechanggui::where('patients_id',$patientsid)->order('xuechanggui_id','desc')->select();
        dump($res_ct);
    }
}