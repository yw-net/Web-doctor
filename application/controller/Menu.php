<?php


namespace app\controller;
use app\controller\Base;
use app\model\Complementary_treatment_baxiang;
use app\model\Complementary_treatment_fangliao;
use app\model\Complementary_treatment_gamadao;
use app\model\Complementary_treatment_hualiao;
use app\model\Follow_fufazhuanyi;
use app\model\Follow_lianxiren;
use app\model\History_menstrual;
use app\model\History_self;
use app\model\History_surgical;
use app\model\Induction_therapy_baxiang;
use app\model\Induction_therapy_fangliao;
use app\model\Induction_therapy_houct;
use app\model\Induction_therapy_houfenqi;
use app\model\Induction_therapy_houpet;
use app\model\Induction_therapy_hualiao;
use app\model\Induction_therapy_qianfenqi;
use app\model\Patients;
use app\model\History_family;
use app\model\postoperative_complications_erci;
use app\model\postoperative_complications_icu;
use app\model\postoperative_complications_shuhou;
use app\model\postoperative_complications_shuzhong;
use app\model\Postoperative_pathology_bingli;
use app\model\Postoperative_pathology_jiyin;
use app\model\Postoperative_pathology_linba;
use app\model\Postoperative_pathology_mianyi;
use app\model\Postoperative_pathology_zhongwu;
use app\model\Preoperative_chaosheng;
use app\model\Preoperative_ct;
use app\model\Preoperative_feigongneng;
use app\model\Referral;
use app\model\Surgical_info_shoushu;
use app\model\Surgical_info_zhongwu;
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
                    'id_pathological','identity_card','sex','age','married','doctor','created_time','edit_time','hospital_date'
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

        //查询数据库-前分期
        $res_qianfenqi = Induction_therapy_qianfenqi::where('patients_id',$patientsid)->find();
        if ($res_qianfenqi == null){
            $this->assign(['qianfenqi_info'=>null]);
        }else{
            $this->assign(['qianfenqi_info'=>$res_qianfenqi]);
        }

        //查询数据库-化疗
        $res_hualiao = Induction_therapy_hualiao::where('patients_id',$patientsid)->order('hualiao_id','desc')->select();

        //查询数据库-放疗
        $res_fangliao = Induction_therapy_fangliao::where('patients_id',$patientsid)->order('fangliao_id','desc')->select();

        //查询数据库-靶向
        $res_baxiang = Induction_therapy_baxiang::where('patients_id',$patientsid)->order('baxiang_id','desc')->select();

        //查询数据库-后分期
        $res_houfenqi = Induction_therapy_houfenqi::where('patients_id',$patientsid)->order('houfenqi_id','desc')->select();

        //查询数据库-后CT
        $res_houct = Induction_therapy_houct::where('patients_id',$patientsid)->order('houct_id','desc')->select();

        //查询数据库-后PET
        $res_houpet = Induction_therapy_houpet::where('patients_id',$patientsid)->order('houpet_id','desc')->select();

        $this->assign([
            'patientid'=>$patientsid,
            'patients_name'=>$patientsname,
            'hualiao_info'=>$res_hualiao,
            'fangliao_info'=>$res_fangliao,
            'baxiang_info'=>$res_baxiang,
            'houfenqi_info'=>$res_houfenqi,
            'houct_info'=>$res_houct,
            'houpet_info'=>$res_houpet,
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

        //查询数据库-肿物
        $res_zhongwu = Surgical_info_zhongwu::where('patients_id',$patientsid)->order('zhongwu_id','desc')->select();

        //查询数据库-手术
        $res_shoushu = Surgical_info_shoushu::where('patients_id',$patientsid)->order('shoushu_id','desc')->select();
        //手术医生
        $res_doctor = User::where('user_class','<>','麻醉科')->select();
        //麻醉医生
        $res_doctor_mazui = User::where('user_class','=','麻醉科')->select();

        $this->assign([
            'patientid'=>$patientsid,
            'patients_name'=>$patientsname,
            'zhongwu_info'=>$res_zhongwu,
            'shoushu_info'=>$res_shoushu,
            'doctor'=>$res_doctor,
            'doctor_mazui'=>$res_doctor_mazui,
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

        //查询数据库-术中
        $res_shuzhong = postoperative_complications_shuzhong::where('patients_id',$patientsid)->find();

        //查询数据库-术后
        $res_shuhou = postoperative_complications_shuhou::where('patients_id',$patientsid)->find();

        //查询数据库-二次手术
        $res_erci = postoperative_complications_erci::where('patients_id',$patientsid)->find();

        //查询数据库-SICU
        $res_icu = postoperative_complications_icu::where('patients_id',$patientsid)->find();
        $this->assign([
            'patientid'=>$patientsid,
            'patients_name'=>$patientsname,
            'shuzhong_info'=>$res_shuzhong,
            'shuhou_info'=>$res_shuhou,
            'erci_info'=>$res_erci,
            'icu_info'=>$res_icu,
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

        //查询数据库-肿物
        $res_zhongwu = Postoperative_pathology_zhongwu::where('patients_id',$patientsid)->order('zhongwu_id','desc')->select();
        //查询数据库-淋巴结清扫
        $res_linba= Postoperative_pathology_linba::where('patients_id',$patientsid)->order('linba_id','desc')->select();
        //查询数据库-病理分期
        $res_bingli = Postoperative_pathology_bingli::where('patients_id',$patientsid)->order('bingli_id','desc')->select();
        //查询数据库-免疫组化
        $res_mianyi = Postoperative_pathology_mianyi::where('patients_id',$patientsid)->order('mianyi_id','desc')->select();
        //查询数据库-基因检查
        $res_jiyin = Postoperative_pathology_jiyin::where('patients_id',$patientsid)->order('jiyin_id','desc')->select();

        $this->assign([
            'patientid'=>$patientsid,
            'patients_name'=>$patientsname,
            'zhongwu_info'=>$res_zhongwu,
            'linba_info'=>$res_linba,
            'bingli_info'=>$res_bingli,
            'mianyi_info'=>$res_mianyi,
            'jiyin_info'=>$res_jiyin,
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

        //查询数据库-化疗
        $res_hualiao = Complementary_treatment_hualiao::where('patients_id',$patientsid)->order('hualiao_id','desc')->select();
        //查询数据库-放疗
        $res_fangliao = Complementary_treatment_fangliao::where('patients_id',$patientsid)->order('fangliao_id','desc')->select();
        //查询数据库-伽玛刀
        $res_gamadao = Complementary_treatment_gamadao::where('patients_id',$patientsid)->order('gamadao_id','desc')->select();
        //查询数据库-靶向
        $res_baxiang = Complementary_treatment_baxiang::where('patients_id',$patientsid)->order('baxiang_id','desc')->select();
        $this->assign([
            'patientid'=>$patientsid,
            'patients_name'=>$patientsname,
            'hualiao_info'=>$res_hualiao,
            'fangliao_info'=>$res_fangliao,
            'gamadao_info'=>$res_gamadao,
            'baxiang_info'=>$res_baxiang,
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

        //查询数据库-联系人
        $res_lianxiren = Follow_lianxiren::where('patients_id',$patientsid)->order('lianxiren_id','desc')->select();
        //查询数据库-联系人
        $res_fufazhuanyi = Follow_fufazhuanyi::where('patients_id',$patientsid)->find();
        $this->assign([
            'patientid'=>$patientsid,
            'patients_name'=>$patientsname,
            'lianxiren_info'=>$res_lianxiren,
            'fufazhuanyi_info'=>$res_fufazhuanyi,
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
        //查询数据库-复诊信息
        $res_ref = Referral::where('patients_id',$patientsid)->order('fuzhen_id','desc')->select();
        //手术医生
        $res_doctor = User::where('user_class','<>','麻醉科')->select();
        $this->assign([
            'patientid'=>$patientsid,
            'patients_name'=>$patientsname,
            'doctor'=>$res_doctor,
            'fuzhen_info'=>$res_ref,
        ]);
        return $this->fetch();
    }

}