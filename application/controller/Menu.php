<?php


namespace app\controller;
use app\controller\Base;
use app\model\History_menstrual;
use app\model\History_self;
use app\model\History_surgical;
use app\model\Patients;
use app\model\History_family;
use app\model\Preoperative_chaosheng;
use app\model\Preoperative_ct;
use app\model\Preoperative_feigongneng;
use app\model\Tizheng;
use app\model\Zhengzhuang;
use app\model\Preoperative_xuechanggui;
use app\model\Preoperative_xueshenghua;
use app\model\Preoperative_xueliubiaozhiwu;
use app\model\Preoperative_qiguanjing;
use app\model\Preoperative_toulu;
use app\model\Preoperative_gusaomiao;
use app\model\Preoperative_guct;
use app\model\Preoperative_ganzhangct;
use app\model\Preoperative_pet;
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

        //查询数据库
        $res_ct = Preoperative_ct::where('patients_id',$patientsid)->order('ct_id','desc')->select();
        $res_ct_arr = array();
        //查询检查结果组合成数组
        foreach ($res_ct as $value){
            array_push($res_ct_arr,$value['ctFind']);
        }
        //判断数组相同个数，全部相同则为1，再进行判断类型
        if (count(array_unique($res_ct_arr)) != 1){
            $this->assign('ct_find','不相同');
        }
        elseif ($res_ct[0]['ctFind'] =='正常'){
            $this->assign('ct_find','正常');
        }
        else{
            $this->assign('ct_find','异常');
        }

        $res_xuechanggui = Preoperative_xuechanggui::where('patients_id',$patientsid)->order('xuechanggui_id','desc')->select();
        $res_xuechanggui_arr = array();
        //查询检查结果组合成数组
        foreach ($res_xuechanggui as $value){
            array_push($res_xuechanggui_arr,$value['xuechanggui_find']);
        }
        //判断数组相同个数，全部相同则为1，再进行判断类型
        if (count(array_unique($res_xuechanggui_arr)) != 1){
            $this->assign('xuechanggui_find','不相同');
        }
        elseif ($res_xuechanggui[0]['xuechanggui_find'] =='正常'){
            $this->assign('xuechanggui_find','正常');
        }
        else{
            $this->assign('xuechanggui_find','异常');
        }

        $res_xueshenghua = Preoperative_xueshenghua::where('patients_id',$patientsid)->order('xueshenghua_id','desc')->select();
        $res_xueshenghua_arr = array();
        //查询检查结果组合成数组
        foreach ($res_xueshenghua as $value){
            array_push($res_xueshenghua_arr,$value['xueshenghua_find']);
        }
        //判断数组相同个数，全部相同则为1，再进行判断类型
        if (count(array_unique($res_xueshenghua_arr)) != 1){
            $this->assign('xueshenghua_find','不相同');
        }
        elseif ($res_xueshenghua[0]['xueshenghua_find'] =='正常'){
            $this->assign('xueshenghua_find','正常');
        }
        else{
            $this->assign('xueshenghua_find','异常');
        }

        $res_xueliubiaozhiwu = Preoperative_xueliubiaozhiwu::where('patients_id',$patientsid)->order('xueliubiaozhiwu_id','desc')->select();
        $res_xueliubiaozhiwu_arr = array();
        //查询检查结果组合成数组
        foreach ($res_xueliubiaozhiwu as $value){
            array_push($res_xueliubiaozhiwu_arr,$value['xueliubiaozhiwu_find']);
        }
        //判断数组相同个数，全部相同则为1，再进行判断类型
        if (count(array_unique($res_xueliubiaozhiwu_arr)) != 1){
            $this->assign('xueliubiaozhiwu_find','不相同');
        }
        elseif ($res_xueliubiaozhiwu[0]['xueliubiaozhiwu_find'] =='正常'){
            $this->assign('xueliubiaozhiwu_find','正常');
        }
        else{
            $this->assign('xueliubiaozhiwu_find','异常');
        }

        $res_chaosheng = Preoperative_chaosheng::where('patients_id',$patientsid)->order('chaosheng_id','desc')->select();
        $res_chaosheng_arr = array();
        //查询检查结果组合成数组
        foreach ($res_chaosheng as $value){
            array_push($res_chaosheng_arr,$value['chaoShengFind']);
        }
        //判断数组相同个数，全部相同则为1，再进行判断类型
        if (count(array_unique($res_chaosheng_arr)) != 1){
            $this->assign('chaosheng_find','不相同');
        }
        elseif ($res_chaosheng[0]['chaoShengFind'] =='正常'){
            $this->assign('chaosheng_find','正常');
        }
        else{
            $this->assign('chaosheng_find','异常');
        }

        $res_feigongneng = Preoperative_feigongneng::where('patients_id',$patientsid)->order('feigongneng_id','desc')->select();
        $res_feigongneng_arr = array();
        //查询检查结果组合成数组
        foreach ($res_feigongneng as $value){
            array_push($res_feigongneng_arr,$value['feiGongNengFind']);
        }
        //判断数组相同个数，全部相同则为1，再进行判断类型
        if (count(array_unique($res_feigongneng_arr)) != 1){
            $this->assign('feigongneng_find','不相同');
        }
        elseif ($res_feigongneng[0]['feiGongNengFind'] =='正常'){
            $this->assign('feigongneng_find','正常');
        }
        else{
            $this->assign('feigongneng_find','异常');
        }

        $res_qiguanjing = Preoperative_qiguanjing::where('patients_id',$patientsid)->order('qiguanjing_id','desc')->select();
        $res_qiguanjing_arr = array();
        //查询检查结果组合成数组
        foreach ($res_qiguanjing as $value){
            array_push($res_qiguanjing_arr,$value['qiguanjing_find']);
        }
        //判断数组相同个数，全部相同则为1，再进行判断类型
        if (count(array_unique($res_qiguanjing_arr)) != 1){
            $this->assign('qiguanjing_find','不相同');
        }
        elseif ($res_qiguanjing[0]['qiguanjing_find'] =='正常'){
            $this->assign('qiguanjing_find','正常');
        }
        else{
            $this->assign('qiguanjing_find','异常');
        }

        $res_toulu = Preoperative_toulu::where('patients_id',$patientsid)->order('toulu_id','desc')->select();
        $res_toulu_arr = array();
        //查询检查结果组合成数组
        foreach ($res_toulu as $value){
            array_push($res_toulu_arr,$value['toulu_find']);
        }
        //判断数组相同个数，全部相同则为1，再进行判断类型
        if (count(array_unique($res_toulu_arr)) != 1){
            $this->assign('toulu_find','不相同');
        }
        elseif ($res_toulu[0]['toulu_find'] =='正常'){
            $this->assign('toulu_find','正常');
        }
        else{
            $this->assign('toulu_find','异常');
        }

        $res_gusaomiao = Preoperative_gusaomiao::where('patients_id',$patientsid)->order('gusaomiao_id','desc')->select();
        $res_gusaomiao_arr = array();
        //查询检查结果组合成数组
        foreach ($res_gusaomiao as $value){
            array_push($res_gusaomiao_arr,$value['gusaomiao_find']);
        }
        //判断数组相同个数，全部相同则为1，再进行判断类型
        if (count(array_unique($res_gusaomiao_arr)) != 1){
            $this->assign('gusaomiao_find','不相同');
        }
        elseif ($res_gusaomiao[0]['gusaomiao_find'] =='正常'){
            $this->assign('gusaomiao_find','正常');
        }
        else{
            $this->assign('gusaomiao_find','异常');
        }

        $res_guct = Preoperative_guct::where('patients_id',$patientsid)->order('guct_id','desc')->select();
        $res_guct_arr = array();
        //查询检查结果组合成数组
        foreach ($res_guct as $value){
            array_push($res_guct_arr,$value['guct_find']);
        }
        //判断数组相同个数，全部相同则为1，再进行判断类型
        if (count(array_unique($res_guct_arr)) != 1){
            $this->assign('guct_find','不相同');
        }
        elseif ($res_guct[0]['guct_find'] =='正常'){
            $this->assign('guct_find','正常');
        }
        else{
            $this->assign('guct_find','异常');
        }

        $res_ganzhangct = Preoperative_ganzhangct::where('patients_id',$patientsid)->order('ganzhangct_id','desc')->select();
        $res_ganzhangct_arr = array();
        //查询检查结果组合成数组
        foreach ($res_ganzhangct as $value){
            array_push($res_ganzhangct_arr,$value['ganzhangct_find']);
        }
        //判断数组相同个数，全部相同则为1，再进行判断类型
        if (count(array_unique($res_ganzhangct_arr)) != 1){
            $this->assign('ganzhangct_find','不相同');
        }
        elseif ($res_ganzhangct[0]['ganzhangct_find'] =='正常'){
            $this->assign('ganzhangct_find','正常');
        }
        else{
            $this->assign('ganzhangct_find','异常');
        }

        $res_pet = Preoperative_pet::where('patients_id',$patientsid)->order('pet_id','desc')->select();
        $res_pet_arr = array();
        //查询检查结果组合成数组
        foreach ($res_pet as $value){
            array_push($res_pet_arr,$value['pet_find']);
        }
        //判断数组相同个数，全部相同则为1，再进行判断类型
        if (count(array_unique($res_pet_arr)) != 1){
            $this->assign('pet_find','不相同');
        }
        elseif ($res_pet[0]['pet_find'] =='正常'){
            $this->assign('pet_find','正常');
        }
        else{
            $this->assign('pet_find','异常');
        }

        $this->assign([
            'patientid'=>$patientsid,
            'patients_name'=>$patientsname,
            'ct_info'=>$res_ct,
            'xuechanggui_info'=>$res_xuechanggui,
            'xueshenghua_info'=>$res_xueshenghua,
            'xueliubiaozhiwu_info'=>$res_xueliubiaozhiwu,
            'chaosheng_info'=>$res_chaosheng,
            'feigongneng_info'=>$res_feigongneng,
            'qiguanjing_info'=>$res_qiguanjing,
            'toulu_info'=>$res_toulu,
            'gusaomiao_info'=>$res_gusaomiao,
            'guct_info'=>$res_guct,
            'ganzhangct_info'=>$res_ganzhangct,
            'pet_info'=>$res_pet,

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
        $patientsid = 1000088;
        $res_ct = Preoperative_ct::where('patients_id',$patientsid)->order('ct_id','desc')->select();
        $res_ct_arr = array();
        //查询检查结果组合成数组
        foreach ($res_ct as $value){
            array_push($res_ct_arr,$value['ctFind']);
        }
        //判断数组相同个数，全部相同则为1，再进行判断类型
        if (count(array_unique($res_ct_arr)) != 1){
            echo '不相同';
        }
        elseif ($res_ct[0]['ctFind'] =='正常'){
            echo '正常';
        }
        else{
            echo '异常';
        }



    }
}