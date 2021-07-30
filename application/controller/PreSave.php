<?php


namespace app\controller;
use app\controller\Base;
use app\controller\Tools;
use app\model\Patients;
use app\model\Preoperative_ct;
use app\model\Preoperative_xuechanggui;
use app\model\Preoperative_xueshenghua;
use app\model\Preoperative_xueliubiaozhiwu;
use app\model\Preoperative_chaosheng;
use app\model\Preoperative_feigongneng;
use app\model\Preoperative_qiguanjing;
use app\model\Preoperative_toulu;
use app\model\Preoperative_gusaomiao;
use app\model\Preoperative_guct;
use app\model\Preoperative_ganzhangct;
use app\model\Preoperative_pet;
use think\Db;
use app\model\User;
use think\facade\Request;
use think\db\Where;
use think\facade\Session;
use think\facade\Env;
use diversen\sendfile;

class PreSave extends Base
{

    //保存文件路径函数
    private function saveFileUrl($newUrl,$fileId,$patients_id,$dbName,$idName,$findName)
    {
        $get = Db::name($dbName)->where([$idName=>$fileId,'patients_id'=>$patients_id])->find();

        //未保存则创建新数据
        if (!isset($get)){
            Db::name($dbName)
                ->insert([
                    $idName=>$fileId,
                    'patients_id'=>$patients_id,
                    'created_time'=>date('Y-m-d'),
                    $findName=>'正常'
                ]);
        }
        //判断数据库文件地址是否为空
        if ($get['img_address'] == null){
            //转换为json格式写入数据库
            $newUrl = json_encode($newUrl);
            Db::name($dbName)
                ->data('img_address',$newUrl)
                ->where([$idName=>$fileId,'patients_id'=>$patients_id])
                ->update();
        }
        else
        {
            $array_img_address= json_decode($get['img_address']);//取出数据库原有json数据

            //判断原有数据是否为数组(单个数据默认为字符串，需转换为数据)
            if (is_array($array_img_address)){
                array_push($array_img_address,$newUrl);//组合新数组（多条数据）
            }
            else{
                $array_img_address = array($array_img_address);//把数据库原有数据转换为数组
                array_push($array_img_address,$newUrl);//组合新数组（单条数据）
            }

            Db::name($dbName)
                ->data('img_address',$array_img_address)
                ->where([$idName=>$fileId,'patients_id'=>$patients_id])
                ->update();
        }

    }

    //删除文件路径函数
    private function delFileUrl($form_id,$patients_id,$idName,$dbName,$folderName,$file_name){
        $get = Db::name($dbName)
            ->where([$idName=>$form_id,'patients_id'=>$patients_id])
            ->find();

        $get_address = json_decode($get['img_address']); //取出json数据并转换为数组
        //判断是否为字符串(多条数据)
        if (gettype($get_address)!='string'){

            foreach ($get_address as $key=>$value)
            {
                if ($value == $file_name)
                    array_splice($get_address,$key,1);   //删除数组对应的值
            }

            //把删除后的数组重新写入数据库
            $res = Db::name($dbName)
                ->data('img_address',$get_address)
                ->where([$idName=>$form_id,'patients_id'=>$patients_id])
                ->update();

            if($res){
                //删除对应文件
                $savePath = Env::get('root_path').'storge/preoperative/'.$folderName.'/';//CT图片保存根目录
                $filePath = $savePath.$file_name;
                unlink($filePath);
            }
        }
        //单条数据直接删除
        if (gettype($get_address) == 'string'){
            $res = Db::name($dbName)
                ->where([$idName=>$form_id,'patients_id'=>$patients_id])
                ->update(['img_address'=>[]]);
            if($res){
                //删除对应文件
                $savePath = Env::get('root_path').'storge/preoperative/'.$folderName.'/';//CT图片保存根目录
                $filePath = $savePath.$file_name;
                unlink($filePath);

            }
        }
    }

    //获取文件信息函数
    private function getFileInfoArr($file_path,$res,$type){//数组
        $res_info = [];
        foreach ($res['img_address'] as $key=>$value)
        {
            $imgInfo = filesize($file_path.$value); //获取文件大小

            $file_name = explode('/',$value);   //截取字符串
            $file_type = $file_name[1];
            $arr=explode(".", $file_type);
//获取最后一个/后边的字符
            $last=$arr[count($arr)-1];
            switch ($last){
                case 'mp4':
                    $filetype = 'video/mp4';
                    $file_type = 'video';
                    break;
                case 'mp3':
                    $filetype = 'audio/mp3';
                    $file_type = 'audio';
                    break;
                case 'jpg':
                case 'png':
                case 'bmp':
                case 'JPEG':
                case 'jpeg':
                case 'gif':
                    $filetype = 'image';
                    $file_type = 'image';
                    break;
                default:
                    $filetype = 'object';
                    $file_type = 'object';
            }
            array_push($res_info,['caption'=>$file_name[1],'downloadUrl'=>'../prefile/'.$type.'/'.$value,'key'=>$value,'size'=>$imgInfo,'type'=>$file_type,'filetype'=>$filetype]);   //组合对象数组
        }
        $res['img_info'] = $res_info;
    }
    private function getFileInfoStr($file_path,$res,$type){//字符串
        $res_info = [];
        $file_name = explode('/',$res['img_address']);   //截取字符串

        $imgInfo = filesize($file_path.$res['img_address']); //获取文件大小

        array_push($res_info,['caption'=>$file_name[1],'downloadUrl'=>'../prefile/'.$type.'/'.$res['img_address'],'key'=>$res['img_address'],'size'=>$imgInfo]);   //组合对象数组
        $res['img_info'] = $res_info;
    }


    //图片显示路由
    public function imgRoute()
    {
        $this->isLogin();

        $fileType = $this->request->param('type');
        $fileDate = $this->request->param('date');
        $fileName = $this->request->param('name');

        $path = $fileDate."/".$fileName;
        $savePath = Env::get('root_path').'storge/preoperative/'.$fileType.'/';//图片保存根目录

        $file_path = $savePath.$path; //图片地址

        $imgInfo = getimagesize($file_path); //解析图片文件信息
        $imgType = $imgInfo[2];//判断图片文件类型

        switch ($imgType) {
            case 1: // gif
                // 采用gif方式载入
                $im = imagecreatefromgif($file_path);
                ob_start();
                imagegif($im);
                $content = ob_get_clean();
                imagedestroy($im);
                return response($content,200,['content-length'=>strlen($content)])->contentType('image/gif');
                break;
            case 2: // jpg
                // 采用jpg方式载入
                $im = imagecreatefromjpeg($file_path);
                ob_start();
                imagejpeg($im);
                $content = ob_get_clean();
                imagedestroy($im);
                return response($content,200,['content-length'=>strlen($content)])->contentType('image/jpeg');
                break;
            case 3: // png
                // 采用png方式载入
                $im = imagecreatefrompng($file_path);
                ob_start();
                imagepng($im);
                $content = ob_get_clean();
                imagedestroy($im);
                return response($content,200,['content-length'=>strlen($content)])->contentType('image/png');
                break;
            default://下载
                $s = new sendfile();
                try {
                    $s->send($file_path);
                } catch (\Exception $e) {
                    echo $e->getMessage();
                }
        }

    }


    //更新、新增血常规信息
    public function xueChangGuiSaveNew(){
        //数据处理-获取表单数据
        $patients_id = $this->request->param('patients_id');
        $form_data = $this->request->param('form_data');
        $xuechanggui_id = $this->request->param('form_id');

        $pre_xuechanggui = new Preoperative_xuechanggui();

        //id
        $pre_xuechanggui->patients_id=$patients_id;
        $pre_xuechanggui->xuechanggui_id=$xuechanggui_id;

        //判断数据库当前患者下form_id是否存在
        $pre_xuechanggui_find = Preoperative_xuechanggui::where(['patients_id'=>$patients_id,'xuechanggui_id'=>$xuechanggui_id])->find();
        //更新数据
        if ($pre_xuechanggui_find){
            $pre_xuechanggui_find->edit_time = date('Y-m-d H:i:s');
            $pre_xuechanggui_find->xuechanggui_find = $form_data['xueChangGuiFind'];
            if (isset($form_data['checkdatexuechanggui'])) {
                $pre_xuechanggui_find->checkdatexuechanggui = $form_data['checkdatexuechanggui'];
            }
            if (isset($form_data['baixibaojishu'])){
                $pre_xuechanggui_find->baixibaojishu = $form_data['baixibaojishu'];
            }
            if (isset($form_data['zhongxinglixibaojishu'])){
                $pre_xuechanggui_find->zhongxinglixibaojishu = $form_data['zhongxinglixibaojishu'];
            }
            if (isset($form_data['xuehongdanbai'])){
                $pre_xuechanggui_find->xuehongdanbai = $form_data['xuehongdanbai'];
            }
            if (isset($form_data['xuexiaoban'])){
                $pre_xuechanggui_find->xuexiaoban = $form_data['xuexiaoban'];
            }
            if (isset($form_data['xuexing'])){
                $pre_xuechanggui_find->xuexing = $form_data['xuexing'];
            }
            $pre_xuechanggui_find->save();
            $res['status'] = 1;
            $res['msg'] ='数据更新成功';
            return $res;
        }
        //新增数据
        $pre_xuechanggui->created_time = date('Y-m-d');
        $pre_xuechanggui->xuechanggui_find = $form_data['xueChangGuiFind'];
        if (isset($form_data['checkdatexuechanggui'])) {
            $pre_xuechanggui->checkdatexuechanggui = $form_data['checkdatexuechanggui'];
        }
        if (isset($form_data['baixibaojishu'])){
            $pre_xuechanggui->baixibaojishu = $form_data['baixibaojishu'];
        }
        if (isset($form_data['zhongxinglixibaojishu'])){
            $pre_xuechanggui->zhongxinglixibaojishu = $form_data['zhongxinglixibaojishu'];
        }
        if (isset($form_data['xuehongdanbai'])){
            $pre_xuechanggui->xuehongdanbai = $form_data['xuehongdanbai'];
        }
        if (isset($form_data['xuexiaoban'])){
            $pre_xuechanggui->xuexiaoban = $form_data['xuexiaoban'];
        }
        if (isset($form_data['xuexing'])){
            $pre_xuechanggui->xuexing = $form_data['xuexing'];
        }
        $pre_xuechanggui->save();
        $res['status'] = 1;
        $res['msg'] ='数据保存成功';
        return $res;



    }

    //删除血常规信息
    public function delXueChangGuiInfo()
    {
        $patients_id = $this->request->param('patients_id');
        $id = $this->request->param('form_id');
        $del = Preoperative_xuechanggui::where(['xuechanggui_id'=>$id,'patients_id'=>$patients_id])->delete();
        if ($del){
            $res['msg'] = '删除成功';
            $res['status'] = 1 ;
            return $res;
        }
        $res['msg'] = '数据库连接失败';
        $res['status'] = 0 ;
        return $res;
    }


    //更新、新增血生化信息
    public function xueShengHuaSaveNew(){
        //数据处理-获取表单数据
        $patients_id = $this->request->param('patients_id');
        $form_data = $this->request->param('form_data');
        $xueshenghua_id = $this->request->param('form_id');

        $pre_xueshenghua = new Preoperative_xueshenghua();

        //id
        $pre_xueshenghua->patients_id=$patients_id;
        $pre_xueshenghua->xueshenghua_id=$xueshenghua_id;

        //判断数据库当前患者下form_id是否存在
        $pre_xueshenghua_find = Preoperative_xueshenghua::where(['patients_id'=>$patients_id,'xueshenghua_id'=>$xueshenghua_id])->find();
        //更新数据
        if ($pre_xueshenghua_find){
            $pre_xueshenghua_find->edit_time = date('Y-m-d H:i:s');
            $pre_xueshenghua_find->xueshenghua_find = $form_data['xueShengHuaFind'];
            if (isset($form_data['checkdate'])) {
                $pre_xueshenghua_find->checkdate = $form_data['checkdate'];
            }
            if (isset($form_data['gubinzhuananmei'])){
                $pre_xueshenghua_find->gubinzhuananmei = $form_data['gubinzhuananmei'];
            }
            if (isset($form_data['gucaozhuananmei'])){
                $pre_xueshenghua_find->gucaozhuananmei = $form_data['gucaozhuananmei'];
            }
            if (isset($form_data['zongdanhongsu'])){
                $pre_xueshenghua_find->zongdanhongsu = $form_data['zongdanhongsu'];
            }
            if (isset($form_data['niaosuan'])){
                $pre_xueshenghua_find->niaosuan = $form_data['niaosuan'];
            }
            if (isset($form_data['jigan'])){
                $pre_xueshenghua_find->jigan = $form_data['jigan'];
            }
            if (isset($form_data['xuedanbai'])){
                $pre_xueshenghua_find->xuedanbai = $form_data['xuedanbai'];
            }
            if (isset($form_data['zongdanbai'])){
                $pre_xueshenghua_find->zongdanbai = $form_data['zongdanbai'];
            }
            if (isset($form_data['jianxinglingsuanmei'])){
                $pre_xueshenghua_find->jianxinglingsuanmei = $form_data['jianxinglingsuanmei'];
            }
            if (isset($form_data['xuetang'])){
                $pre_xueshenghua_find->xuetang = $form_data['xuetang'];
            }
            $pre_xueshenghua_find->save();
            $res['status'] = 1;
            $res['msg'] ='数据更新成功';
            return $res;
        }
        //新增数据
        $pre_xueshenghua->created_time = date('Y-m-d');
        $pre_xueshenghua->xueshenghua_find = $form_data['xueShengHuaFind'];
        if (isset($form_data['checkdate'])) {
            $pre_xueshenghua->checkdate = $form_data['checkdate'];
        }
        if (isset($form_data['gubinzhuananmei'])){
            $pre_xueshenghua->gubinzhuananmei = $form_data['gubinzhuananmei'];
        }
        if (isset($form_data['gucaozhuananmei'])){
            $pre_xueshenghua->gucaozhuananmei = $form_data['gucaozhuananmei'];
        }
        if (isset($form_data['zongdanhongsu'])){
            $pre_xueshenghua->zongdanhongsu = $form_data['zongdanhongsu'];
        }
        if (isset($form_data['niaosuan'])){
            $pre_xueshenghua->niaosuan = $form_data['niaosuan'];
        }
        if (isset($form_data['jigan'])){
            $pre_xueshenghua->jigan = $form_data['jigan'];
        }
        if (isset($form_data['xuedanbai'])){
            $pre_xueshenghua->xuedanbai = $form_data['xuedanbai'];
        }
        if (isset($form_data['zongdanbai'])){
            $pre_xueshenghua->zongdanbai = $form_data['zongdanbai'];
        }
        if (isset($form_data['jianxinglingsuanmei'])){
            $pre_xueshenghua->jianxinglingsuanmei = $form_data['jianxinglingsuanmei'];
        }
        if (isset($form_data['xuetang'])){
            $pre_xueshenghua->xuetang = $form_data['xuetang'];
        }
        $pre_xueshenghua->save();
        $res['status'] = 1;
        $res['msg'] ='数据保存成功';
        return $res;



    }

    //删除血生化信息
    public function delXueShengHuaInfo()
    {
        $patients_id = $this->request->param('patients_id');
        $id = $this->request->param('form_id');
        $del = Preoperative_xueshenghua::where(['xueshenghua_id'=>$id,'patients_id'=>$patients_id])->delete();
        if ($del){
            $res['msg'] = '删除成功';
            $res['status'] = 1 ;
            return $res;
        }
        $res['msg'] = '数据库连接失败';
        $res['status'] = 0 ;
        return $res;
    }


    //更新、新增血瘤标志物信息
    public function xueLiuBiaoZhiWuSaveNew(){
        //数据处理-获取表单数据
        $patients_id = $this->request->param('patients_id');
        $form_data = $this->request->param('form_data');
        $xueliubiaozhiwu_id = $this->request->param('form_id');

        $pre_xueliubiaozhiwu = new Preoperative_xueliubiaozhiwu();

        //id
        $pre_xueliubiaozhiwu->patients_id=$patients_id;
        $pre_xueliubiaozhiwu->xueliubiaozhiwu_id=$xueliubiaozhiwu_id;

        //判断数据库当前患者下form_id是否存在
        $pre_xueliubiaozhiwu_find = Preoperative_xueliubiaozhiwu::where(['patients_id'=>$patients_id,'xueliubiaozhiwu_id'=>$xueliubiaozhiwu_id])->find();
        //更新数据
        if ($pre_xueliubiaozhiwu_find){
            $pre_xueliubiaozhiwu_find->edit_time = date('Y-m-d H:i:s');
            $pre_xueliubiaozhiwu_find->xueliubiaozhiwu_find = $form_data['xueLiuBiaoZhiWuFind'];
            if (isset($form_data['checkdate'])) {
                $pre_xueliubiaozhiwu_find->checkdate = $form_data['checkdate'];
            }
            if (isset($form_data['cea'])){
                $pre_xueliubiaozhiwu_find->cea = $form_data['cea'];
            }
            if (isset($form_data['cyfra211'])){
                $pre_xueliubiaozhiwu_find->cyfra211 = $form_data['cyfra211'];
            }
            if (isset($form_data['ca199'])){
                $pre_xueliubiaozhiwu_find->ca199 = $form_data['ca199'];
            }
            if (isset($form_data['nse'])){
                $pre_xueliubiaozhiwu_find->nse = $form_data['nse'];
            }
            if (isset($form_data['scc'])){
                $pre_xueliubiaozhiwu_find->scc = $form_data['scc'];
            }
            if (isset($form_data['ca125'])){
                $pre_xueliubiaozhiwu_find->ca125 = $form_data['ca125'];
            }
            $pre_xueliubiaozhiwu_find->save();
            $res['status'] = 1;
            $res['msg'] ='数据更新成功';
            return $res;
        }
        //新增数据
        $pre_xueliubiaozhiwu->created_time = date('Y-m-d');
        $pre_xueliubiaozhiwu->xueliubiaozhiwu_find = $form_data['xueLiuBiaoZhiWuFind'];
        if (isset($form_data['checkdate'])) {
            $pre_xueliubiaozhiwu->checkdate = $form_data['checkdate'];
        }
        if (isset($form_data['cea'])){
            $pre_xueliubiaozhiwu->cea = $form_data['cea'];
        }
        if (isset($form_data['cyfra211'])){
            $pre_xueliubiaozhiwu->cyfra211 = $form_data['cyfra211'];
        }
        if (isset($form_data['ca199'])){
            $pre_xueliubiaozhiwu->ca199 = $form_data['ca199'];
        }
        if (isset($form_data['nse'])){
            $pre_xueliubiaozhiwu->nse = $form_data['nse'];
        }
        if (isset($form_data['scc'])){
            $pre_xueliubiaozhiwu->scc = $form_data['scc'];
        }
        if (isset($form_data['ca125'])){
            $pre_xueliubiaozhiwu->ca125 = $form_data['ca125'];
        }
        $pre_xueliubiaozhiwu->save();
        $res['status'] = 1;
        $res['msg'] ='数据保存成功';
        return $res;



    }

    //删除血瘤标志物信息
    public function delXueLiuBiaoZhiWuInfo()
    {
        $patients_id = $this->request->param('patients_id');
        $id = $this->request->param('form_id');
        $del = Preoperative_xueliubiaozhiwu::where(['xueliubiaozhiwu_id'=>$id,'patients_id'=>$patients_id])->delete();
        if ($del){
            $res['msg'] = '删除成功';
            $res['status'] = 1 ;
            return $res;
        }
        $res['msg'] = '数据库连接失败';
        $res['status'] = 0 ;
        return $res;
    }



    //更新、新增CT信息
    public function ctSaveNew()
    {
        //数据处理-获取表单数据
        $patients_id = $this->request->param('patients_id');
        $ct_data = $this->request->param('form_data');
        $ct_id = $this->request->param('form_id');

        //判断数据库当前患者下ct_id是否存在
        $ct_id_res = Preoperative_ct::where(['ct_id'=>$ct_id,'patients_id'=>$patients_id])->find();
        //更新数据
        if ($ct_id_res){

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

            $res_ct = Db::name('Preoperative_ct')
                ->where(['ct_id'=>$ct_id,'patients_id'=>$patients_id])
                ->data([
                    'edit_time'=>date('Y-m-d H:i:s'),
                    'ctFind'=>$ct_data['ctFind'],
                    'ggo'=>$ct_data['ggo'],
                    'buwei'=>$ct_data['buwei'],
                    'daxiao1'=>$ct_data['daxiao1'],
                    'daxiao2'=>$ct_data['daxiao2'],
                    'daxiao3'=>$ct_data['daxiao3'],
                    'gaihua'=>$ct_data['gaihua'],
                    'fenyezheng'=>$ct_data['fenyezheng'],
                    'maocizheng'=>$ct_data['maocizheng'],
                    'xiongmozhousuo'=>$ct_data['xiongmozhousuo'],
                    'jizhuangtuqi'=>$ct_data['jizhuangtuqi'],
                    'kongdong'=>$ct_data['kongdong'],
                    'zhiqiguanzheng'=>$ct_data['zhiqiguanzheng'],
                    'zushaixingfeiyan'=>$ct_data['zushaixingfeiyan'],
                    'zhongliuqingfanxueguan'=>$ct_data['zhongliuqingfanxueguan'],
                    'qingfangeji'=>$ct_data['qingfangeji'],
                    'qingfanzhuiti'=>$ct_data['qingfanzhuiti'],
                    'qingfanxiongbi'=>$ct_data['qingfanxiongbi'],
                    'xiongshui'=>$ct_data['xiongshui'],
                    'tongyejiejie'=>$ct_data['tongyejiejie'],
                    'butongyejiejie'=>$ct_data['butongyejiejie'],
                    'spn'=>$ct_data['spn'],
                    'linbajiezhongda'=>$ct_data['linbajiezhongda'],
                ])
                ->update();
            if ($res_ct){
                $res['status'] = 1;
                $res['msg'] ='数据保存成功';
                return $res;
            }
            $res['status'] =0;
            $res['msg'] ='数据未更改';
            return $res;
        }


        //新增数据
        $pre_ct = new Preoperative_ct();
        $pre_ct->ct_id =$ct_id;
        $pre_ct->patients_id =$patients_id;
        $pre_ct->created_time = date('Y-m-d');

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
        $pre_ct->ctFind = $ct_data['ctFind'];
        $pre_ct->ggo = $ct_data['ggo'];
        $pre_ct->gaihua = $ct_data['gaihua'];
        $pre_ct->fenyezheng = $ct_data['fenyezheng'];
        $pre_ct->maocizheng = $ct_data['maocizheng'];
        $pre_ct->xiongmozhousuo = $ct_data['xiongmozhousuo'];
        $pre_ct->jizhuangtuqi = $ct_data['jizhuangtuqi'];
        $pre_ct->kongdong = $ct_data['kongdong'];
        $pre_ct->zhiqiguanzheng = $ct_data['zhiqiguanzheng'];
        $pre_ct->zushaixingfeiyan = $ct_data['zushaixingfeiyan'];
        $pre_ct->zhongliuqingfanxueguan = $ct_data['zhongliuqingfanxueguan'];
        $pre_ct->qingfangeji = $ct_data['qingfangeji'];
        $pre_ct->qingfanzhuiti = $ct_data['qingfanzhuiti'];
        $pre_ct->qingfanxiongbi = $ct_data['qingfanxiongbi'];
        $pre_ct->xiongshui = $ct_data['xiongshui'];
        $pre_ct->tongyejiejie = $ct_data['tongyejiejie'];
        $pre_ct->butongyejiejie = $ct_data['butongyejiejie'];
        $pre_ct->spn = $ct_data['spn'];
        $pre_ct->linbajiezhongda = $ct_data['linbajiezhongda'];
        $pre_ct->save();

        if ($pre_ct){
            $res['status'] = 1;
            $res['msg'] ='数据保存成功';
            return $res;
        }

        $res['status'] = 0;
        $res['msg'] ='新增数据失败';
        return $res;
    }

    //删除CT信息
    public function delCtInfo()
    {
        $patients_id = $this->request->param('patients_id');
        $ct_id = $this->request->param('form_id');
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

    //保存CT图像
    public function imgCtSave()
    {
        $savePath = Env::get('root_path').'storge/preoperative/ct/'.date('Ymd');//CT图片保存根目录

        $info = $this->request->param();//获取附加信息

        $patients_id =$info['patients_id'];
        $form_id =$info['form_id'];


        $file = $this->request->file();//获取上传文件本体


        //文件保存
        if ($file){
            $res = $file['imgShowCt'.$form_id]->move($savePath,'');
        }
        else{
            return (['status'=>0,'error'=>'文件保存失败']);
        }

        //写入数据库
        if($res)
        {
            $url = date('Ymd')."/".$res->getSaveName();

            //数据库查重
            $get = Preoperative_ct::where(['ct_id'=>$form_id,'patients_id'=>$patients_id])->find();

            if ($get['img_address']!=null){
                $get_address = json_decode($get['img_address']); //取出json数据并转换为数组
//                return  (['status'=>1,'message'=>is_array($get_address)]);
                if (is_array($get_address)){
                    foreach ($get_address as $key=>$value)
                    {
                        if ($value == $url)
                            return  (['status'=>0,'error'=>'文件名重复！写入数据库失败！请更改文件名称后重试！']);
                    }
                }
                if ($get_address == $url){
                    return  (['status'=>0,'error'=>'文件名重复！写入数据库失败！请更改文件名称后重试！']);
                }


            }
            //正式写入数据库
            $this->saveFileUrl($url,$form_id,$patients_id,'Preoperative_ct','ct_id','ctFind');

            return  (['status'=>1,'message'=>'文件上传成功','fileid'=>$url]);
        }
        else
        {
            return  (['status'=>0,'error'=>'写入数据库失败']);
        }
    }

    //删除CT图像
    public function imgCtDel()
    {
        $info = $this->request->param();//获取附加信息

        $patients_id =$info['patients_id'];
        $form_id =$info['form_id'];
        $file_name = $info['key'];
        $idName = 'ct_id';
        $dbName = 'Preoperative_ct';
        $folderName = 'ct';

        $this->delFileUrl($form_id,$patients_id,$idName,$dbName,$folderName,$file_name);

        return ['status'=>1,'message'=>'删除成功'];

    }

    //获取CT图像预览信息
    public function getCTAddress()
    {
        $file_path = Env::get('root_path').'storge/preoperative/ct/';//CT图片保存根目录

        $patients_id = $this->request->param('patients_id');
        $fileId = $this->request->param('ct_id');

        $res = Preoperative_ct::where(['patients_id'=>$patients_id,'ct_id'=>$fileId])->field('img_address')->find();
        $res['img_address'] = json_decode($res['img_address']);

        //判断数据库字段是否为数组
        if (is_array($res['img_address'])){
            $this->getFileInfoArr($file_path,$res,'ct');
            return $res;
        }
        //判断数据库字段是否有数据(字符串)
        if ($res['img_address']!=null){
            $this->getFileInfoStr($file_path,$res,'ct');
            return $res;
        }
        //数据库字段无数据则输出空数组
        $res['img_address'] = [];
        return $res;
    }



    //更新、新增超声信息
    public function chaoShengSaveNew(){
        //数据处理-获取表单数据
        $patients_id = $this->request->param('patients_id');
        $form_data = $this->request->param('form_data');
        $chaosheng_id = $this->request->param('form_id');

        $pre_chaosheng = new Preoperative_chaosheng();

        //id
        $pre_chaosheng->patients_id=$patients_id;
        $pre_chaosheng->chaosheng_id=$chaosheng_id;

        //判断数据库当前患者下form_id是否存在
        $pre_chaosheng_find = Preoperative_chaosheng::where(['patients_id'=>$patients_id,'chaosheng_id'=>$chaosheng_id])->find();
        //更新数据
        if ($pre_chaosheng_find){
            $pre_chaosheng_find->edit_time = date('Y-m-d H:i:s');
            $pre_chaosheng_find->jingbulingbajiezhongda = $form_data['jingbulingbajiezhongda'];
            $pre_chaosheng_find->huojian1 = $form_data['huojian1'];
            if (isset($form_data['binglihao1'])){
                $pre_chaosheng_find->binglihao1 = $form_data['binglihao1'];
            }
            $pre_chaosheng_find->who1 = $form_data['who1'];
            $pre_chaosheng_find->ganzanwei = $form_data['ganzanwei'];
            $pre_chaosheng_find->huojian2 = $form_data['huojian2'];
            if (isset($form_data['binglihao2'])){
                $pre_chaosheng_find->binglihao2 = $form_data['binglihao2'];
            }
            $pre_chaosheng_find->who2 = $form_data['who2'];
            $pre_chaosheng_find->shengshangxianjiejie = $form_data['shengshangxianjiejie'];
                $pre_chaosheng_find->huojian3 = $form_data['huojian3'];
            if (isset($form_data['binglihao3'])){
                $pre_chaosheng_find->binglihao3 = $form_data['binglihao3'];
            }
            $pre_chaosheng_find->who3 = $form_data['who3'];
            $pre_chaosheng_find->chaoShengFind = $form_data['chaoShengFind'];
            $pre_chaosheng_find->save();
            $res['status'] = 1;
            $res['msg'] ='数据更新成功';
            return $res;
        }
        //新增数据
        $pre_chaosheng->created_time = date('Y-m-d');
        $pre_chaosheng->jingbulingbajiezhongda = $form_data['jingbulingbajiezhongda'];
        $pre_chaosheng->huojian1 = $form_data['huojian1'];
        if (isset($form_data['binglihao1'])){
            $pre_chaosheng->binglihao1 = $form_data['binglihao1'];
        }
        $pre_chaosheng->who1 = $form_data['who1'];
        $pre_chaosheng->ganzanwei = $form_data['ganzanwei'];
        $pre_chaosheng->huojian2 = $form_data['huojian2'];
        if (isset($form_data['binglihao2'])){
            $pre_chaosheng->binglihao2 = $form_data['binglihao2'];
        }
        $pre_chaosheng->who2 = $form_data['who2'];
        $pre_chaosheng->shengshangxianjiejie = $form_data['shengshangxianjiejie'];
        $pre_chaosheng->huojian3 = $form_data['huojian3'];
        if (isset($form_data['binglihao3'])){
            $pre_chaosheng->binglihao3 = $form_data['binglihao3'];
        }
        $pre_chaosheng->who3 = $form_data['who3'];
        $pre_chaosheng->chaoShengFind = $form_data['chaoShengFind'];
        $pre_chaosheng->save();
        $res['status'] = 1;
        $res['msg'] ='数据新增成功';
        return $res;

    }

    //删除超声信息
    public function delChaoShengInfo()
    {
        $patients_id = $this->request->param('patients_id');
        $id = $this->request->param('form_id');
        $del = Preoperative_chaosheng::where(['chaosheng_id'=>$id,'patients_id'=>$patients_id])->delete();
        if ($del){
            $res['msg'] = '删除成功';
            $res['status'] = 1 ;
            return $res;
        }
        $res['msg'] = '数据库连接失败';
        $res['status'] = 0 ;
        return $res;
    }

    //保存超声图像
    public function imgChaoShengSave()
    {
        $savePath = Env::get('root_path').'storge/preoperative/chaosheng/'.date('Ymd');//CT图片保存根目录

        $info = $this->request->param();//获取附加信息

        $patients_id =$info['patients_id'];
        $form_id =$info['form_id'];


        $file = $this->request->file();//获取上传文件本体


        //文件保存
        if ($file){
            $res = $file['imgShowChaoSheng'.$form_id]->move($savePath,'');
        }
        else{
            return (['status'=>0,'error'=>'文件保存失败']);
        }

        //写入数据库
        if($res)
        {
            $url = date('Ymd')."/".$res->getSaveName();

            //数据库查重
            $get = Preoperative_chaosheng::where(['chaosheng_id'=>$form_id,'patients_id'=>$patients_id])->find();

            if ($get['img_address']!=null){
                $get_address = json_decode($get['img_address']); //取出json数据并转换为数组
//                return  (['status'=>1,'message'=>is_array($get_address)]);
                if (is_array($get_address)){
                    foreach ($get_address as $key=>$value)
                    {
                        if ($value == $url)
                            return  (['status'=>0,'error'=>'文件名重复！写入数据库失败！请更改文件名称后重试！']);
                    }
                }
                if ($get_address == $url){
                    return  (['status'=>0,'error'=>'文件名重复！写入数据库失败！请更改文件名称后重试！']);
                }


            }
            //正式写入数据库
            $this->saveFileUrl($url,$form_id,$patients_id,'Preoperative_chaosheng','chaosheng_id','chaoShengFind');

            return  (['status'=>1,'message'=>'文件上传成功','fileid'=>$url]);
        }
        else
        {
            return  (['status'=>0,'error'=>'写入数据库失败']);
        }
    }

    //删除超声图像
    public function imgChaoShengDel()
    {
        $info = $this->request->param();//获取附加信息

        $patients_id =$info['patients_id'];
        $form_id =$info['form_id'];
        $file_name = $info['key'];
        $idName = 'chaosheng_id';
        $dbName = 'Preoperative_chaosheng';
        $folderName = 'chaosheng';

        $this->delFileUrl($form_id,$patients_id,$idName,$dbName,$folderName,$file_name);

        return ['status'=>1,'message'=>'删除成功'];
    }

    //获取超声图像预览信息
    public function getChaoShengAddress(){
        $file_path = Env::get('root_path').'storge/preoperative/chaosheng/';//超声图片保存根目录

        $patients_id = $this->request->param('patients_id');
        $fileId = $this->request->param('form_id');

        $res = Preoperative_chaosheng::where(['patients_id'=>$patients_id,'chaosheng_id'=>$fileId])->field('img_address')->find();
        $res['img_address'] = json_decode($res['img_address']);

        //判断数据库字段是否为数组
        if (is_array($res['img_address'])){
            $this->getFileInfoArr($file_path,$res,'chaosheng');
            return $res;
        }
        //判断数据库字段是否有数据(字符串)
        if ($res['img_address']!=null){
            $this->getFileInfoStr($file_path,$res,'chaosheng');
            return $res;
        }
        //数据库字段无数据则输出空数组
        $res['img_address'] = [];
        return $res;
    }



    //更新、新增肺功能信息
    public function feiGongNengSaveNew(){
        //数据处理-获取表单数据
        $patients_id = $this->request->param('patients_id');
        $form_data = $this->request->param('form_data');
        $feigongneng_id = $this->request->param('form_id');

        $pre_feigongneng = new Preoperative_feigongneng();

        //id
        $pre_feigongneng->patients_id=$patients_id;
        $pre_feigongneng->feigongneng_id=$feigongneng_id;

        //判断数据库当前患者下form_id是否存在
        $pre_feigongneng_find = Preoperative_feigongneng::where(['patients_id'=>$patients_id,'feigongneng_id'=>$feigongneng_id])->find();
        //更新数据
        if ($pre_feigongneng_find){
            $pre_feigongneng_find->edit_time = date('Y-m-d H:i:s');
            $pre_feigongneng_find->feiGongNengFind = $form_data['feiGongNengFind'];
            if (isset($form_data['pre_fvc'])) {
                $pre_feigongneng_find->pre_fvc = $form_data['pre_fvc'];
            }
            if (isset($form_data['act_fvc'])){
                $pre_feigongneng_find->act_fvc = $form_data['act_fvc'];
            }
            if (isset($form_data['pre_fev1'])){
                $pre_feigongneng_find->pre_fev1 = $form_data['pre_fev1'];
            }
            if (isset($form_data['act_fev1'])){
                $pre_feigongneng_find->act_fev1 = $form_data['act_fev1'];
            }
            if (isset($form_data['pre_dlco'])){
                $pre_feigongneng_find->pre_dlco = $form_data['pre_dlco'];
            }
            if (isset($form_data['act_dlco'])){
                $pre_feigongneng_find->act_dlco = $form_data['act_dlco'];
            }
            if (isset($form_data['pre_vc'])){
                $pre_feigongneng_find->pre_vc = $form_data['pre_vc'];
            }
            if (isset($form_data['act_vc'])){
                $pre_feigongneng_find->act_vc = $form_data['act_vc'];
            }
            if (isset($form_data['pre_po2'])){
                $pre_feigongneng_find->pre_po2 = $form_data['pre_po2'];
            }
            if (isset($form_data['pre_pco2'])){
                $pre_feigongneng_find->pre_pco2 = $form_data['pre_pco2'];
            }
            if (isset($form_data['after_po2'])){
                $pre_feigongneng_find->after_po2 = $form_data['after_po2'];
            }
            if (isset($form_data['after_pco2'])){
                $pre_feigongneng_find->after_pco2 = $form_data['after_pco2'];
            }
            $pre_feigongneng_find->save();
            $res['status'] = 1;
            $res['msg'] ='数据更新成功';
            return $res;
        }
        //新增数据
        $pre_feigongneng->created_time = date('Y-m-d');
        $pre_feigongneng->feiGongNengFind = $form_data['feiGongNengFind'];
        if (isset($form_data['pre_fvc'])) {
            $pre_feigongneng->pre_fvc = $form_data['pre_fvc'];
        }
        if (isset($form_data['act_fvc'])){
            $pre_feigongneng->act_fvc = $form_data['act_fvc'];
        }
        if (isset($form_data['pre_fev1'])){
            $pre_feigongneng->pre_fev1 = $form_data['pre_fev1'];
        }
        if (isset($form_data['act_fev1'])){
            $pre_feigongneng->act_fev1 = $form_data['act_fev1'];
        }
        if (isset($form_data['pre_dlco'])){
            $pre_feigongneng->pre_dlco = $form_data['pre_dlco'];
        }
        if (isset($form_data['act_dlco'])){
            $pre_feigongneng->act_dlco = $form_data['act_dlco'];
        }
        if (isset($form_data['pre_vc'])){
            $pre_feigongneng->pre_vc = $form_data['pre_vc'];
        }
        if (isset($form_data['act_vc'])){
            $pre_feigongneng->act_vc = $form_data['act_vc'];
        }
        if (isset($form_data['pre_po2'])){
            $pre_feigongneng->pre_po2 = $form_data['pre_po2'];
        }
        if (isset($form_data['pre_pco2'])){
            $pre_feigongneng->pre_pco2 = $form_data['pre_pco2'];
        }
        if (isset($form_data['after_po2'])){
            $pre_feigongneng->after_po2 = $form_data['after_po2'];
        }
        if (isset($form_data['after_pco2'])){
            $pre_feigongneng->after_pco2 = $form_data['after_pco2'];
        }
        $pre_feigongneng->save();
        $res['status'] = 1;
        $res['msg'] ='数据保存成功';
        return $res;



    }

    //删除肺功能信息
    public function delFeiGongNengInfo()
    {
        $patients_id = $this->request->param('patients_id');
        $id = $this->request->param('form_id');
        $del = Preoperative_feigongneng::where(['feigongneng_id'=>$id,'patients_id'=>$patients_id])->delete();
        if ($del){
            $res['msg'] = '删除成功';
            $res['status'] = 1 ;
            return $res;
        }
        $res['msg'] = '数据库连接失败';
        $res['status'] = 0 ;
        return $res;
    }

    //保存肺功能图像
    public function imgFeiGongNengSave()
    {
        $savePath = Env::get('root_path').'storge/preoperative/feigongneng/'.date('Ymd');//CT图片保存根目录

        $info = $this->request->param();//获取附加信息

        $patients_id =$info['patients_id'];
        $form_id =$info['form_id'];


        $file = $this->request->file();//获取上传文件本体


        //文件保存
        if ($file){
            $res = $file['imgShowFeiGongNeng'.$form_id]->move($savePath,'');
        }
        else{
            return (['status'=>0,'error'=>'文件保存失败']);
        }

        //写入数据库
        if($res)
        {
            $url = date('Ymd')."/".$res->getSaveName();

            //数据库查重
            $get = Preoperative_feigongneng::where(['feigongneng_id'=>$form_id,'patients_id'=>$patients_id])->find();

            if ($get['img_address']!=null){
                $get_address = json_decode($get['img_address']); //取出json数据并转换为数组
//                return  (['status'=>1,'message'=>is_array($get_address)]);
                if (is_array($get_address)){
                    foreach ($get_address as $key=>$value)
                    {
                        if ($value == $url)
                            return  (['status'=>0,'error'=>'文件名重复！写入数据库失败！请更改文件名称后重试！']);
                    }
                }
                if ($get_address == $url){
                    return  (['status'=>0,'error'=>'文件名重复！写入数据库失败！请更改文件名称后重试！']);
                }


            }
            //正式写入数据库
            $this->saveFileUrl($url,$form_id,$patients_id,'Preoperative_feigongneng','feigongneng_id','feiGongNengFind');

            return  (['status'=>1,'message'=>'文件上传成功','fileid'=>$url]);
        }
        else
        {
            return  (['status'=>0,'error'=>'写入数据库失败']);
        }
    }

    //删除肺功能图像
    public function imgFeiGongNengDel()
    {
        $info = $this->request->param();//获取附加信息

        $patients_id =$info['patients_id'];
        $form_id =$info['form_id'];
        $file_name = $info['key'];
        $idName = 'feigongneng_id';
        $dbName = 'Preoperative_feigongneng';
        $folderName = 'feigongneng';

        $this->delFileUrl($form_id,$patients_id,$idName,$dbName,$folderName,$file_name);

        return ['status'=>1,'message'=>'删除成功'];
    }

    //获取肺功能图像预览信息
    public function getFeiGongNengAddress(){
        $file_path = Env::get('root_path').'storge/preoperative/feigongneng/';//超声图片保存根目录

        $patients_id = $this->request->param('patients_id');
        $fileId = $this->request->param('form_id');

        $res = Preoperative_feigongneng::where(['patients_id'=>$patients_id,'feigongneng_id'=>$fileId])->field('img_address')->find();
        $res['img_address'] = json_decode($res['img_address']);

        //判断数据库字段是否为数组
        if (is_array($res['img_address'])){
            $this->getFileInfoArr($file_path,$res,'feigongneng');
            return $res;
        }
        //判断数据库字段是否有数据(字符串)
        if ($res['img_address']!=null){
            $this->getFileInfoStr($file_path,$res,'feigongneng');
            return $res;
        }
        //数据库字段无数据则输出空数组
        $res['img_address'] = [];
        return $res;
    }



    //更新、新增气管镜信息
    public function qiGuanJingSaveNew(){
        //数据处理-获取表单数据
        $patients_id = $this->request->param('patients_id');
        $form_data = $this->request->param('form_data');
        $qiguanjing_id = $this->request->param('form_id');

        //判断数据库当前患者下form_id是否存在
        $pre_qiguanjing_find = Preoperative_qiguanjing::where(['patients_id'=>$patients_id,'qiguanjing_id'=>$qiguanjing_id])->find();

        //更新数据
        if ($pre_qiguanjing_find){
            //解决多选框及输入框为空
            if (isset($form_data['zhuzhiqiguan'])){
                //判断多选框值是否为数组（当选择两个以下强制转换为数组）
                if (!is_array($form_data['zhuzhiqiguan'])){
                    $form_data['zhuzhiqiguan'] = json_encode($form_data['zhuzhiqiguan']);
                }
            }else{
                $form_data['zhuzhiqiguan'] =null;
            }
            if (isset($form_data['zuofei'])){
                //判断多选框值是否为数组（当选择两个以下强制转换为数组）
                if (!is_array($form_data['zuofei'])){
                    $form_data['zuofei'] = json_encode($form_data['zuofei']);
                }
            }else{
                $form_data['zuofei'] =null;
            }
            if (isset($form_data['youfei'])){
                //判断多选框值是否为数组（当选择两个以下强制转换为数组）
                if (!is_array($form_data['youfei'])){
                    $form_data['youfei'] = json_encode($form_data['youfei']);
                }
            }else{
                $form_data['youfei'] =null;
            }
            if (isset($form_data['jingxiahuojian'])){
                //判断多选框值是否为数组（当选择两个以下强制转换为数组）
                if (!is_array($form_data['jingxiahuojian'])){
                    $form_data['jingxiahuojian'] = json_encode($form_data['jingxiahuojian']);
                }
            }else{
                $form_data['jingxiahuojian'] =null;
            }
            if (isset($form_data['tbna'])){
                //判断多选框值是否为数组（当选择两个以下强制转换为数组）
                if (!is_array($form_data['tbna'])){
                    $form_data['tbna'] = json_encode($form_data['tbna']);
                }
            }else{
                $form_data['tbna'] =null;
            }
            if (isset($form_data['ebus'])){
                //判断多选框值是否为数组（当选择两个以下强制转换为数组）
                if (!is_array($form_data['ebus'])){
                    $form_data['ebus'] = json_encode($form_data['ebus']);
                }
            }else{
                $form_data['ebus'] =null;
            }

            if (!isset($form_data['jingxiahuojian_other'])){
                $form_data['jingxiahuojian_other'] =null;
            }
            if (!isset($form_data['tbna_other'])){
                $form_data['tbna_other'] =null;
            }
            if (!isset($form_data['ebus_other'])){
                $form_data['ebus_other'] =null;
            }
            if (!isset($form_data['tblb_other'])){
                $form_data['tblb_other'] =null;
            }
            if (!isset($form_data['other'])){
                $form_data['other'] =null;
            }
            $res_qiguanjing = Db::name('Preoperative_qiguanjing')
                ->where(['qiguanjing_id'=>$qiguanjing_id,'patients_id'=>$patients_id])
                ->data([
                    'edit_time'=>date('Y-m-d H:i:s'),
                    'qiguanjing_find'=>$form_data['qiguanjing_find'],
                    'zhuzhiqiguan'=>$form_data['zhuzhiqiguan'],
                    'zuofei'=>$form_data['zuofei'],
                    'youfei'=>$form_data['youfei'],
                    'jingxiahuojian'=>$form_data['jingxiahuojian'],
                    'tbna'=>$form_data['tbna'],
                    'ebus'=>$form_data['ebus'],
                    'jingxiahuojian_other'=>$form_data['jingxiahuojian_other'],
                    'tbna_other'=>$form_data['tbna_other'],
                    'ebus_other'=>$form_data['ebus_other'],
                    'tblb_other'=>$form_data['tblb_other'],
                    'who'=>$form_data['who'],
                    'other'=>$form_data['other'],
                ])
                ->update();
            if ($res_qiguanjing){
                $res['status'] = 1;
                $res['msg'] ='数据保存成功';
                return $res;
            }
            $res['status'] =0;
            $res['msg'] ='数据未更改';
            return $res;
        }

        //新增数据
        $pre_qiguanjing = new Preoperative_qiguanjing();
        $pre_qiguanjing->qiguanjing_id =$qiguanjing_id;
        $pre_qiguanjing->patients_id =$patients_id;
        $pre_qiguanjing->created_time = date('Y-m-d');

        //处理空值问题
        //解决多选框及输入框为空
        if (isset($form_data['zhuzhiqiguan'])){
            //判断多选框值是否为数组（当选择两个以下强制转换为数组）
            if (!is_array($form_data['zhuzhiqiguan'])){
                $form_data['zhuzhiqiguan'] = json_encode($form_data['zhuzhiqiguan']);
            }
            $pre_qiguanjing->zhuzhiqiguan = $form_data['zhuzhiqiguan'];
        }
        if (isset($form_data['zuofei'])) {
            //判断多选框值是否为数组（当选择两个以下强制转换为数组）
            if (!is_array($form_data['zuofei'])) {
                $form_data['zuofei'] = json_encode($form_data['zuofei']);
            }
            $pre_qiguanjing->zuofei = $form_data['zuofei'];
        }
        if (isset($form_data['youfei'])) {
            //判断多选框值是否为数组（当选择两个以下强制转换为数组）
            if (!is_array($form_data['youfei'])) {
                $form_data['youfei'] = json_encode($form_data['youfei']);
            }
            $pre_qiguanjing->youfei = $form_data['youfei'];
        }
        if (isset($form_data['jingxiahuojian'])) {
            //判断多选框值是否为数组（当选择两个以下强制转换为数组）
            if (!is_array($form_data['jingxiahuojian'])) {
                $form_data['jingxiahuojian'] = json_encode($form_data['jingxiahuojian']);
            }
            $pre_qiguanjing->jingxiahuojian = $form_data['jingxiahuojian'];
        }
        if (isset($form_data['tbna'])) {
            //判断多选框值是否为数组（当选择两个以下强制转换为数组）
            if (!is_array($form_data['tbna'])) {
                $form_data['tbna'] = json_encode($form_data['tbna']);
            }
            $pre_qiguanjing->tbna = $form_data['tbna'];
        }
        if (isset($form_data['ebus'])) {
            //判断多选框值是否为数组（当选择两个以下强制转换为数组）
            if (!is_array($form_data['ebus'])) {
                $form_data['ebus'] = json_encode($form_data['ebus']);
            }
            $pre_qiguanjing->ebus = $form_data['ebus'];
        }
        if (isset($form_data['jingxiahuojian_other'])){
            $pre_qiguanjing->jingxiahuojian_other = $form_data['jingxiahuojian_other'];
        }
        if (isset($form_data['tbna_other'])){
            $pre_qiguanjing->tbna_other = $form_data['tbna_other'];
        }
        if (isset($form_data['ebus_other'])){
            $pre_qiguanjing->ebus_other = $form_data['ebus_other'];
        }
        if (isset($form_data['tblb_other'])){
            $pre_qiguanjing->tblb_other = $form_data['tblb_other'];
        }
        if (isset($form_data['other'])){
            $pre_qiguanjing->other = $form_data['other'];
        }


        //数据写入
        $pre_qiguanjing->qiguanjing_find = $form_data['qiguanjing_find'];
        $pre_qiguanjing->who = $form_data['who'];
        $pre_qiguanjing->save();

        if ($pre_qiguanjing){
            $res['status'] = 1;
            $res['msg'] ='数据保存成功';
            return $res;
        }

        $res['status'] = 0;
        $res['msg'] ='新增数据失败';
        return $res;

    }

    //删除气管镜信息
    public function delQiGuanJingInfo()
    {
        $patients_id = $this->request->param('patients_id');
        $id = $this->request->param('form_id');
        $del = Preoperative_qiguanjing::where(['qiguanjing_id'=>$id,'patients_id'=>$patients_id])->delete();
        if ($del){
            $res['msg'] = '删除成功';
            $res['status'] = 1 ;
            return $res;
        }
        $res['msg'] = '数据库连接失败';
        $res['status'] = 0 ;
        return $res;
    }

    //保存气管镜图像
    public function imgQiGuanJingSave()
    {
        $savePath = Env::get('root_path').'storge/preoperative/qiguanjing/'.date('Ymd');//CT图片保存根目录

        $info = $this->request->param();//获取附加信息

        $patients_id =$info['patients_id'];
        $form_id =$info['form_id'];


        $file = $this->request->file();//获取上传文件本体


        //文件保存
        if ($file){
            $res = $file['imgShowQiGuanJing'.$form_id]->move($savePath,'');
        }
        else{
            return (['status'=>0,'error'=>'文件保存失败']);
        }

        //写入数据库
        if($res)
        {
            $url = date('Ymd')."/".$res->getSaveName();

            //数据库查重
            $get = Preoperative_qiguanjing::where(['qiguanjing_id'=>$form_id,'patients_id'=>$patients_id])->find();

            if ($get['img_address']!=null){
                $get_address = json_decode($get['img_address']); //取出json数据并转换为数组
//                return  (['status'=>1,'message'=>is_array($get_address)]);
                if (is_array($get_address)){
                    foreach ($get_address as $key=>$value)
                    {
                        if ($value == $url)
                            return  (['status'=>0,'error'=>'文件名重复！写入数据库失败！请更改文件名称后重试！']);
                    }
                }
                if ($get_address == $url){
                    return  (['status'=>0,'error'=>'文件名重复！写入数据库失败！请更改文件名称后重试！']);
                }


            }
            //正式写入数据库
            $this->saveFileUrl($url,$form_id,$patients_id,'Preoperative_qiguanjing','qiguanjing_id','qiguanjing_find');

            return  (['status'=>1,'message'=>'文件上传成功','fileid'=>$url]);
        }
        else
        {
            return  (['status'=>0,'error'=>'写入数据库失败']);
        }
    }

    //删除气管镜图像
    public function imgQiGuanJingDel()
    {
        $info = $this->request->param();//获取附加信息

        $patients_id =$info['patients_id'];
        $form_id =$info['form_id'];
        $file_name = $info['key'];
        $idName = 'qiguanjing_id';
        $dbName = 'Preoperative_qiguanjing';
        $folderName = 'qiguanjing';

        $this->delFileUrl($form_id,$patients_id,$idName,$dbName,$folderName,$file_name);

        return ['status'=>1,'message'=>'删除成功'];
    }

    //获取气管镜图像预览信息
    public function getQiGuanJingAddress(){
        $file_path = Env::get('root_path').'storge/preoperative/qiguanjing/';//超声图片保存根目录

        $patients_id = $this->request->param('patients_id');
        $fileId = $this->request->param('form_id');

        $res = Preoperative_qiguanjing::where(['patients_id'=>$patients_id,'qiguanjing_id'=>$fileId])->field('img_address')->find();
        $res['img_address'] = json_decode($res['img_address']);

        //判断数据库字段是否为数组
        if (is_array($res['img_address'])){
            $this->getFileInfoArr($file_path,$res,'qiguanjing');
            return $res;
        }
        //判断数据库字段是否有数据(字符串)
        if ($res['img_address']!=null){
            $this->getFileInfoStr($file_path,$res,'qiguanjing');
            return $res;
        }
        //数据库字段无数据则输出空数组
        $res['img_address'] = [];
        return $res;
    }



    //更新、新增头颅信息
    public function touLuSaveNew(){
        //数据处理-获取表单数据
        $patients_id = $this->request->param('patients_id');
        $form_data = $this->request->param('form_data');
        $toulu_id = $this->request->param('form_id');

        //判断数据库当前患者下form_id是否存在
        $pre_toulu_find = Preoperative_toulu::where(['patients_id'=>$patients_id,'toulu_id'=>$toulu_id])->find();

        //更新数据
        if ($pre_toulu_find){
            $res_toulu = Db::name('Preoperative_toulu')
                ->where(['toulu_id'=>$toulu_id,'patients_id'=>$patients_id])
                ->data([
                    'edit_time'=>date('Y-m-d H:i:s'),
                    'toulu_find'=>$form_data['toulu_find'],
                    'zhuanyi'=>$form_data['zhuanyi'],
                ])
                ->update();
            if ($res_toulu){
                $res['status'] = 1;
                $res['msg'] ='数据保存成功';
                return $res;
            }
            $res['status'] =0;
            $res['msg'] ='数据未更改';
            return $res;
        }

        //新增数据
        $pre_toulu = new Preoperative_toulu();
        $pre_toulu->toulu_id =$toulu_id;
        $pre_toulu->patients_id =$patients_id;
        $pre_toulu->created_time = date('Y-m-d');

        //数据写入
        $pre_toulu->toulu_find = $form_data['toulu_find'];
        $pre_toulu->zhuanyi = $form_data['zhuanyi'];
        $pre_toulu->save();

        if ($pre_toulu){
            $res['status'] = 1;
            $res['msg'] ='数据保存成功';
            return $res;
        }

        $res['status'] = 0;
        $res['msg'] ='新增数据失败';
        return $res;

    }

    //删除头颅信息
    public function delTouLuInfo()
    {
        $patients_id = $this->request->param('patients_id');
        $id = $this->request->param('form_id');
        $del = Preoperative_toulu::where(['toulu_id'=>$id,'patients_id'=>$patients_id])->delete();
        if ($del){
            $res['msg'] = '删除成功';
            $res['status'] = 1 ;
            return $res;
        }
        $res['msg'] = '数据库连接失败';
        $res['status'] = 0 ;
        return $res;
    }

    //保存头颅图像
    public function imgTouLuSave()
    {
        $savePath = Env::get('root_path').'storge/preoperative/toulu/'.date('Ymd');//CT图片保存根目录

        $info = $this->request->param();//获取附加信息

        $patients_id =$info['patients_id'];
        $form_id =$info['form_id'];


        $file = $this->request->file();//获取上传文件本体


        //文件保存
        if ($file){
            $res = $file['imgShowTouLu'.$form_id]->move($savePath,'');
        }
        else{
            return (['status'=>0,'error'=>'文件保存失败']);
        }

        //写入数据库
        if($res)
        {
            $url = date('Ymd')."/".$res->getSaveName();

            //数据库查重
            $get = Preoperative_toulu::where(['toulu_id'=>$form_id,'patients_id'=>$patients_id])->find();

            if ($get['img_address']!=null){
                $get_address = json_decode($get['img_address']); //取出json数据并转换为数组
                if (is_array($get_address)){
                    foreach ($get_address as $key=>$value)
                    {
                        if ($value == $url)
                            return  (['status'=>0,'error'=>'文件名重复！写入数据库失败！请更改文件名称后重试！']);
                    }
                }
                if ($get_address == $url){
                    return  (['status'=>0,'error'=>'文件名重复！写入数据库失败！请更改文件名称后重试！']);
                }


            }
            //正式写入数据库
            $this->saveFileUrl($url,$form_id,$patients_id,'Preoperative_toulu','toulu_id','toulu_find');

            return  (['status'=>1,'message'=>'文件上传成功','fileid'=>$url]);
        }
        else
        {
            return  (['status'=>0,'error'=>'写入数据库失败']);
        }
    }

    //删除头颅图像
    public function imgTouLuDel()
    {
        $info = $this->request->param();//获取附加信息

        $patients_id =$info['patients_id'];
        $form_id =$info['form_id'];
        $file_name = $info['key'];
        $idName = 'toulu_id';
        $dbName = 'Preoperative_toulu';
        $folderName = 'toulu';

        $this->delFileUrl($form_id,$patients_id,$idName,$dbName,$folderName,$file_name);

        return ['status'=>1,'message'=>'删除成功'];

    }

    //获取头颅图像预览信息
    public function getTouLuAddress(){
        $file_path = Env::get('root_path').'storge/preoperative/toulu/';//超声图片保存根目录

        $patients_id = $this->request->param('patients_id');
        $fileId = $this->request->param('form_id');

        $res = Preoperative_toulu::where(['patients_id'=>$patients_id,'toulu_id'=>$fileId])->field('img_address')->find();
        $res['img_address'] = json_decode($res['img_address']);

        //判断数据库字段是否为数组
        if (is_array($res['img_address'])){
            $this->getFileInfoArr($file_path,$res,'toulu');
            return $res;
        }
        //判断数据库字段是否有数据(字符串)
        if ($res['img_address']!=null){
            $this->getFileInfoStr($file_path,$res,'toulu');
            return $res;
        }
        //数据库字段无数据则输出空数组
        $res['img_address'] = [];
        return $res;
    }



    //更新、新增骨扫描信息
    public function guSaoMiaoSaveNew(){
        //数据处理-获取表单数据
        $patients_id = $this->request->param('patients_id');
        $form_data = $this->request->param('form_data');
        $gusaomiao_id = $this->request->param('form_id');

        //判断数据库当前患者下form_id是否存在
        $pre_gusaomiao_find = Preoperative_gusaomiao::where(['patients_id'=>$patients_id,'gusaomiao_id'=>$gusaomiao_id])->find();

        //更新数据
        if ($pre_gusaomiao_find){
            $res_gusaomiao = Db::name('Preoperative_gusaomiao')
                ->where(['gusaomiao_id'=>$gusaomiao_id,'patients_id'=>$patients_id])
                ->data([
                    'edit_time'=>date('Y-m-d H:i:s'),
                    'gusaomiao_find'=>$form_data['gusaomiao_find'],
                    'zhuanyi'=>$form_data['zhuanyi'],
                ])
                ->update();
            if ($res_gusaomiao){
                $res['status'] = 1;
                $res['msg'] ='数据保存成功';
                return $res;
            }
            $res['status'] =0;
            $res['msg'] ='数据未更改';
            return $res;
        }

        //新增数据
        $pre_gusaomiao = new Preoperative_gusaomiao();
        $pre_gusaomiao->gusaomiao_id =$gusaomiao_id;
        $pre_gusaomiao->patients_id =$patients_id;
        $pre_gusaomiao->created_time = date('Y-m-d');

        //数据写入
        $pre_gusaomiao->gusaomiao_find = $form_data['gusaomiao_find'];
        $pre_gusaomiao->zhuanyi = $form_data['zhuanyi'];
        $pre_gusaomiao->save();

        if ($pre_gusaomiao){
            $res['status'] = 1;
            $res['msg'] ='数据保存成功';
            return $res;
        }

        $res['status'] = 0;
        $res['msg'] ='新增数据失败';
        return $res;

    }

    //删除骨扫描信息
    public function delGuSaoMiaoInfo()
    {
        $patients_id = $this->request->param('patients_id');
        $id = $this->request->param('form_id');
        $del = Preoperative_gusaomiao::where(['gusaomiao_id'=>$id,'patients_id'=>$patients_id])->delete();
        if ($del){
            $res['msg'] = '删除成功';
            $res['status'] = 1 ;
            return $res;
        }
        $res['msg'] = '数据库连接失败';
        $res['status'] = 0 ;
        return $res;
    }

    //保存骨扫描图像
    public function imgGuSaoMiaoSave()
    {
        $savePath = Env::get('root_path').'storge/preoperative/gusaomiao/'.date('Ymd');//CT图片保存根目录

        $info = $this->request->param();//获取附加信息

        $patients_id =$info['patients_id'];
        $form_id =$info['form_id'];


        $file = $this->request->file();//获取上传文件本体


        //文件保存
        if ($file){
            $res = $file['imgShowGuSaoMiao'.$form_id]->move($savePath,'');
        }
        else{
            return (['status'=>0,'error'=>'文件保存失败']);
        }

        //写入数据库
        if($res)
        {
            $url = date('Ymd')."/".$res->getSaveName();

            //数据库查重
            $get = Preoperative_gusaomiao::where(['gusaomiao_id'=>$form_id,'patients_id'=>$patients_id])->find();

            if ($get['img_address']!=null){
                $get_address = json_decode($get['img_address']); //取出json数据并转换为数组
//                return  (['status'=>1,'message'=>is_array($get_address)]);
                if (is_array($get_address)){
                    foreach ($get_address as $key=>$value)
                    {
                        if ($value == $url)
                            return  (['status'=>0,'error'=>'文件名重复！写入数据库失败！请更改文件名称后重试！']);
                    }
                }
                if ($get_address == $url){
                    return  (['status'=>0,'error'=>'文件名重复！写入数据库失败！请更改文件名称后重试！']);
                }


            }
            //正式写入数据库
            $this->saveFileUrl($url,$form_id,$patients_id,'Preoperative_gusaomiao','gusaomiao_id','gusaomiao_find');

            return  (['status'=>1,'message'=>'文件上传成功','fileid'=>$url]);
        }
        else
        {
            return  (['status'=>0,'error'=>'写入数据库失败']);
        }
    }

    //删除骨扫描图像
    public function imgGuSaoMiaoDel()
    {
        $info = $this->request->param();//获取附加信息

        $patients_id =$info['patients_id'];
        $form_id =$info['form_id'];
        $file_name = $info['key'];
        $idName = 'gusaomiao_id';
        $dbName = 'Preoperative_gusaomiao';
        $folderName = 'gusaomiao';

        $this->delFileUrl($form_id,$patients_id,$idName,$dbName,$folderName,$file_name);

        return ['status'=>1,'message'=>'删除成功'];
    }

    //获取骨扫描图像预览信息
    public function getGuSaoMiaoAddress(){
        $file_path = Env::get('root_path').'storge/preoperative/gusaomiao/';//超声图片保存根目录

        $patients_id = $this->request->param('patients_id');
        $fileId = $this->request->param('form_id');

        $res = Preoperative_gusaomiao::where(['patients_id'=>$patients_id,'gusaomiao_id'=>$fileId])->field('img_address')->find();
        $res['img_address'] = json_decode($res['img_address']);

        //判断数据库字段是否为数组
        if (is_array($res['img_address'])){
            $this->getFileInfoArr($file_path,$res,'gusaomiao');
            return $res;
        }
        //判断数据库字段是否有数据(字符串)
        if ($res['img_address']!=null){
            $this->getFileInfoStr($file_path,$res,'gusaomiao');
            return $res;
        }
        //数据库字段无数据则输出空数组
        $res['img_address'] = [];
        return $res;
    }



    //更新、新增骨CT信息
    public function guCtSaveNew(){
        //数据处理-获取表单数据
        $patients_id = $this->request->param('patients_id');
        $form_data = $this->request->param('form_data');
        $guct_id = $this->request->param('form_id');

        //判断数据库当前患者下form_id是否存在
        $pre_guct_find = Preoperative_guct::where(['patients_id'=>$patients_id,'guct_id'=>$guct_id])->find();

        //更新数据
        if ($pre_guct_find){
            $res_guct = Db::name('Preoperative_guct')
                ->where(['guct_id'=>$guct_id,'patients_id'=>$patients_id])
                ->data([
                    'edit_time'=>date('Y-m-d H:i:s'),
                    'guct_find'=>$form_data['guct_find'],
                    'zhuanyi'=>$form_data['zhuanyi'],
                ])
                ->update();
            if ($res_guct){
                $res['status'] = 1;
                $res['msg'] ='数据保存成功';
                return $res;
            }
            $res['status'] =0;
            $res['msg'] ='数据未更改';
            return $res;
        }

        //新增数据
        $pre_guct = new Preoperative_guct();
        $pre_guct->guct_id =$guct_id;
        $pre_guct->patients_id =$patients_id;
        $pre_guct->created_time = date('Y-m-d');

        //数据写入
        $pre_guct->guct_find = $form_data['guct_find'];
        $pre_guct->zhuanyi = $form_data['zhuanyi'];
        $pre_guct->save();

        if ($pre_guct){
            $res['status'] = 1;
            $res['msg'] ='数据保存成功';
            return $res;
        }

        $res['status'] = 0;
        $res['msg'] ='新增数据失败';
        return $res;

    }

    //删除骨CT信息
    public function delGuCtInfo()
    {
        $patients_id = $this->request->param('patients_id');
        $id = $this->request->param('form_id');
        $del = Preoperative_guct::where(['guct_id'=>$id,'patients_id'=>$patients_id])->delete();
        if ($del){
            $res['msg'] = '删除成功';
            $res['status'] = 1 ;
            return $res;
        }
        $res['msg'] = '数据库连接失败';
        $res['status'] = 0 ;
        return $res;
    }

    //保存骨CT图像
    public function imgGuCtSave()
    {
        $savePath = Env::get('root_path').'storge/preoperative/guct/'.date('Ymd');//CT图片保存根目录

        $info = $this->request->param();//获取附加信息

        $patients_id =$info['patients_id'];
        $form_id =$info['form_id'];


        $file = $this->request->file();//获取上传文件本体


        //文件保存
        if ($file){
            $res = $file['imgShowGuCt'.$form_id]->move($savePath,'');
        }
        else{
            return (['status'=>0,'error'=>'文件保存失败']);
        }

        //写入数据库
        if($res)
        {
            $url = date('Ymd')."/".$res->getSaveName();

            //数据库查重
            $get = Preoperative_guct::where(['guct_id'=>$form_id,'patients_id'=>$patients_id])->find();

            if ($get['img_address']!=null){
                $get_address = json_decode($get['img_address']); //取出json数据并转换为数组
                if (is_array($get_address)){
                    foreach ($get_address as $key=>$value)
                    {
                        if ($value == $url)
                            return  (['status'=>0,'error'=>'文件名重复！写入数据库失败！请更改文件名称后重试！']);
                    }
                }
                if ($get_address == $url){
                    return  (['status'=>0,'error'=>'文件名重复！写入数据库失败！请更改文件名称后重试！']);
                }
            }
            //正式写入数据库
            $this->saveFileUrl($url,$form_id,$patients_id,'Preoperative_guct','guct_id','guct_find');

            return  (['status'=>1,'message'=>'文件上传成功','fileid'=>$url]);
        }
        else
        {
            return  (['status'=>0,'error'=>'写入数据库失败']);
        }
    }

    //删除骨CT图像
    public function imgGuCtDel()
    {
        $info = $this->request->param();//获取附加信息

        $patients_id =$info['patients_id'];
        $form_id =$info['form_id'];
        $file_name = $info['key'];
        $idName = 'guct_id';
        $dbName = 'Preoperative_guct';
        $folderName = 'guct';

        $this->delFileUrl($form_id,$patients_id,$idName,$dbName,$folderName,$file_name);

        return ['status'=>1,'message'=>'删除成功'];
    }

    //获取骨CT图像预览信息
    public function getGuCtAddress(){
        $file_path = Env::get('root_path').'storge/preoperative/guct/';//超声图片保存根目录

        $patients_id = $this->request->param('patients_id');
        $fileId = $this->request->param('form_id');

        $res = Preoperative_guct::where(['patients_id'=>$patients_id,'guct_id'=>$fileId])->field('img_address')->find();
        $res['img_address'] = json_decode($res['img_address']);

        //判断数据库字段是否为数组
        if (is_array($res['img_address'])){
            $this->getFileInfoArr($file_path,$res,'guct');
            return $res;
        }
        //判断数据库字段是否有数据(字符串)
        if ($res['img_address']!=null){
            $this->getFileInfoStr($file_path,$res,'guct');
            return $res;
        }
        //数据库字段无数据则输出空数组
        $res['img_address'] = [];
        return $res;
    }



    //更新、新增肝脏CT信息
    public function ganZhangCtSaveNew(){
        //数据处理-获取表单数据
        $patients_id = $this->request->param('patients_id');
        $form_data = $this->request->param('form_data');
        $ganzhangct_id = $this->request->param('form_id');

        //判断数据库当前患者下form_id是否存在
        $pre_ganzhangct_find = Preoperative_ganzhangct::where(['patients_id'=>$patients_id,'ganzhangct_id'=>$ganzhangct_id])->find();

        //更新数据
        if ($pre_ganzhangct_find){
            $res_ganzhangct = Db::name('Preoperative_ganzhangct')
                ->where(['ganzhangct_id'=>$ganzhangct_id,'patients_id'=>$patients_id])
                ->data([
                    'edit_time'=>date('Y-m-d H:i:s'),
                    'ganzhangct_find'=>$form_data['ganzhangct_find'],
                    'zhuanyi'=>$form_data['zhuanyi'],
                ])
                ->update();
            if ($res_ganzhangct){
                $res['status'] = 1;
                $res['msg'] ='数据保存成功';
                return $res;
            }
            $res['status'] =0;
            $res['msg'] ='数据未更改';
            return $res;
        }

        //新增数据
        $pre_ganzhangct = new Preoperative_ganzhangct();
        $pre_ganzhangct->ganzhangct_id =$ganzhangct_id;
        $pre_ganzhangct->patients_id =$patients_id;
        $pre_ganzhangct->created_time = date('Y-m-d');

        //数据写入
        $pre_ganzhangct->ganzhangct_find = $form_data['ganzhangct_find'];
        $pre_ganzhangct->zhuanyi = $form_data['zhuanyi'];
        $pre_ganzhangct->save();

        if ($pre_ganzhangct){
            $res['status'] = 1;
            $res['msg'] ='数据保存成功';
            return $res;
        }

        $res['status'] = 0;
        $res['msg'] ='新增数据失败';
        return $res;

    }

    //删除肝脏CT信息
    public function delGanZhangCtInfo()
    {
        $patients_id = $this->request->param('patients_id');
        $id = $this->request->param('form_id');
        $del = Preoperative_ganzhangct::where(['ganzhangct_id'=>$id,'patients_id'=>$patients_id])->delete();
        if ($del){
            $res['msg'] = '删除成功';
            $res['status'] = 1 ;
            return $res;
        }
        $res['msg'] = '数据库连接失败';
        $res['status'] = 0 ;
        return $res;
    }

    //保存肝脏CT图像
    public function imgGanZhangCtSave()
    {
        $savePath = Env::get('root_path').'storge/preoperative/ganzhangct/'.date('Ymd');//CT图片保存根目录

        $info = $this->request->param();//获取附加信息

        $patients_id =$info['patients_id'];
        $form_id =$info['form_id'];


        $file = $this->request->file();//获取上传文件本体


        //文件保存
        if ($file){
            $res = $file['imgShowGanZhangCt'.$form_id]->move($savePath,'');
        }
        else{
            return (['status'=>0,'error'=>'文件保存失败']);
        }

        //写入数据库
        if($res)
        {
            $url = date('Ymd')."/".$res->getSaveName();

            //数据库查重
            $get = Preoperative_ganzhangct::where(['ganzhangct_id'=>$form_id,'patients_id'=>$patients_id])->find();

            if ($get['img_address']!=null){
                $get_address = json_decode($get['img_address']); //取出json数据并转换为数组
//                return  (['status'=>1,'message'=>is_array($get_address)]);
                if (is_array($get_address)){
                    foreach ($get_address as $key=>$value)
                    {
                        if ($value == $url)
                            return  (['status'=>0,'error'=>'文件名重复！写入数据库失败！请更改文件名称后重试！']);
                    }
                }
                if ($get_address == $url){
                    return  (['status'=>0,'error'=>'文件名重复！写入数据库失败！请更改文件名称后重试！']);
                }


            }
            //正式写入数据库
            $this->saveFileUrl($url,$form_id,$patients_id,'Preoperative_ganzhangct','ganzhangct_id','ganzhangct_find');

            return  (['status'=>1,'message'=>'文件上传成功','fileid'=>$url]);
        }
        else
        {
            return  (['status'=>0,'error'=>'写入数据库失败']);
        }
    }

    //删除肝脏CT图像
    public function imgGanZhangCtDel()
    {
        $info = $this->request->param();//获取附加信息

        $patients_id =$info['patients_id'];
        $form_id =$info['form_id'];
        $file_name = $info['key'];
        $idName = 'ganzhangct_id';
        $dbName = 'Preoperative_ganzhangct';
        $folderName = 'ganzhangct';

        $this->delFileUrl($form_id,$patients_id,$idName,$dbName,$folderName,$file_name);

        return ['status'=>1,'message'=>'删除成功'];
    }

    //获取肝脏CT图像预览信息
    public function getGanZhangCtAddress(){
        $file_path = Env::get('root_path').'storge/preoperative/ganzhangct/';//超声图片保存根目录

        $patients_id = $this->request->param('patients_id');
        $fileId = $this->request->param('form_id');

        $res = Preoperative_ganzhangct::where(['patients_id'=>$patients_id,'ganzhangct_id'=>$fileId])->field('img_address')->find();
        $res['img_address'] = json_decode($res['img_address']);

        //判断数据库字段是否为数组
        if (is_array($res['img_address'])){
            $this->getFileInfoArr($file_path,$res,'ganzhangct');
            return $res;
        }
        //判断数据库字段是否有数据(字符串)
        if ($res['img_address']!=null){
            $this->getFileInfoStr($file_path,$res,'ganzhangct');
            return $res;
        }
        //数据库字段无数据则输出空数组
        $res['img_address'] = [];
        return $res;
    }




    //更新、新增PET信息
    public function petSaveNew(){
        //数据处理-获取表单数据
        $patients_id = $this->request->param('patients_id');
        $form_data = $this->request->param('form_data');
        $pet_id = $this->request->param('form_id');

        //判断数据库当前患者下form_id是否存在
        $pre_pet_find = Preoperative_pet::where(['patients_id'=>$patients_id,'pet_id'=>$pet_id])->find();

        //更新数据
        if ($pre_pet_find){

            //解决输入框为空
            if (!isset($form_data['pet_num'])){
                $form_data['pet_num'] =null;
            }
            if (!isset($form_data['suv'])){
                $form_data['suv'] =null;
            }
            if (!isset($form_data['other_find'])){
                $form_data['other_find'] =null;
            }
            if (!isset($form_data['check_time'])){
                $form_data['check_time'] =null;
            }

            $res_pet = Db::name('Preoperative_pet')
                ->where(['pet_id'=>$pet_id,'patients_id'=>$patients_id])
                ->data([
                    'edit_time'=>date('Y-m-d H:i:s'),
                    'pet_find'=>$form_data['pet_find'],
                    'benyuan_check'=>$form_data['benyuan_check'],
                    'pet_num'=>$form_data['pet_num'],
                    'check_time'=>$form_data['check_time'],
                    'suv'=>$form_data['suv'],
                    'pet_fenqi_t'=>$form_data['pet_fenqi_t'],
                    'pet_fenqi_n'=>$form_data['pet_fenqi_n'],
                    'pet_fenqi_m'=>$form_data['pet_fenqi_m'],
                    'other_find'=>$form_data['other_find'],
                ])
                ->update();
            if ($res_pet){
                $res['status'] = 1;
                $res['msg'] ='数据保存成功';
                return $res;
            }
            $res['status'] =0;
            $res['msg'] ='数据未更改';
            return $res;
        }

        //新增数据
        $pre_pet = new Preoperative_pet();
        $pre_pet->pet_id =$pet_id;
        $pre_pet->patients_id =$patients_id;
        $pre_pet->created_time = date('Y-m-d');

        //解决输入框为空
        if (!isset($form_data['pet_num'])){
            $form_data['pet_num'] =null;
        }
        if (!isset($form_data['suv'])){
            $form_data['suv'] =null;
        }
        if (!isset($form_data['other_find'])){
            $form_data['other_find'] =null;
        }
        if (!isset($form_data['check_time'])){
            $form_data['check_time'] =null;
        }

        //数据写入
        $pre_pet->pet_find = $form_data['pet_find'];
        $pre_pet->benyuan_check = $form_data['benyuan_check'];
        $pre_pet->pet_num = $form_data['pet_num'];
        $pre_pet->check_time = $form_data['check_time'];
        $pre_pet->suv = $form_data['suv'];
        $pre_pet->pet_fenqi_t = $form_data['pet_fenqi_t'];
        $pre_pet->pet_fenqi_n = $form_data['pet_fenqi_n'];
        $pre_pet->pet_fenqi_m = $form_data['pet_fenqi_m'];
        $pre_pet->other_find = $form_data['other_find'];
        $pre_pet->save();

        if ($pre_pet){
            $res['status'] = 1;
            $res['msg'] ='数据保存成功';
            return $res;
        }

        $res['status'] = 0;
        $res['msg'] ='新增数据失败';
        return $res;

    }

    //删除PET信息
    public function delPETInfo()
    {
        $patients_id = $this->request->param('patients_id');
        $id = $this->request->param('form_id');
        $del = Preoperative_pet::where(['pet_id'=>$id,'patients_id'=>$patients_id])->delete();
        if ($del){
            $res['msg'] = '删除成功';
            $res['status'] = 1 ;
            return $res;
        }
        $res['msg'] = '数据库连接失败';
        $res['status'] = 0 ;
        return $res;
    }

    //保存PET图像
    public function imgPETSave()
    {
        $savePath = Env::get('root_path').'storge/preoperative/pet/'.date('Ymd');//CT图片保存根目录

        $info = $this->request->param();//获取附加信息

        $patients_id =$info['patients_id'];
        $form_id =$info['form_id'];


        $file = $this->request->file();//获取上传文件本体


        //文件保存
        if ($file){
            $res = $file['imgShowPET'.$form_id]->move($savePath,'');
        }
        else{
            return (['status'=>0,'error'=>'文件保存失败']);
        }

        //写入数据库
        if($res)
        {
            $url = date('Ymd')."/".$res->getSaveName();

            //数据库查重
            $get = Preoperative_pet::where(['pet_id'=>$form_id,'patients_id'=>$patients_id])->find();

            if ($get['img_address']!=null){
                $get_address = json_decode($get['img_address']); //取出json数据并转换为数组
//                return  (['status'=>1,'message'=>is_array($get_address)]);
                if (is_array($get_address)){
                    foreach ($get_address as $key=>$value)
                    {
                        if ($value == $url)
                            return  (['status'=>0,'error'=>'文件名重复！写入数据库失败！请更改文件名称后重试！']);
                    }
                }
                if ($get_address == $url){
                    return  (['status'=>0,'error'=>'文件名重复！写入数据库失败！请更改文件名称后重试！']);
                }


            }
            //正式写入数据库
            $this->saveFileUrl($url,$form_id,$patients_id,'Preoperative_pet','pet_id','pet_find');

            return  (['status'=>1,'message'=>'文件上传成功','fileid'=>$url]);
        }
        else
        {
            return  (['status'=>0,'error'=>'写入数据库失败']);
        }
    }

    //删除PET图像
    public function imgPETDel()
    {
        $info = $this->request->param();//获取附加信息

        $patients_id =$info['patients_id'];
        $form_id =$info['form_id'];
        $file_name = $info['key'];
        $idName = 'pet_id';
        $dbName = 'Preoperative_pet';
        $folderName = 'pet';

        $this->delFileUrl($form_id,$patients_id,$idName,$dbName,$folderName,$file_name);

        return ['status'=>1,'message'=>'删除成功'];
    }

    //获取PET图像预览信息
    public function getPETAddress(){
        $file_path = Env::get('root_path').'storge/preoperative/pet/';//超声图片保存根目录

        $patients_id = $this->request->param('patients_id');
        $fileId = $this->request->param('form_id');

        $res = Preoperative_pet::where(['patients_id'=>$patients_id,'pet_id'=>$fileId])->field('img_address')->find();
        $res['img_address'] = json_decode($res['img_address']);

        //判断数据库字段是否为数组
        if (is_array($res['img_address'])){
            $this->getFileInfoArr($file_path,$res,'pet');
            return $res;
        }
        //判断数据库字段是否有数据(字符串)
        if ($res['img_address']!=null){
            $this->getFileInfoStr($file_path,$res,'pet');
            return $res;
        }
        //数据库字段无数据则输出空数组
        $res['img_address'] = [];
        return $res;
    }


    //测试
    public function ceshi()
    {
        $res_t= [];

        $res=Db::query('SELECT * FROM Surgical_info_zhongwu  WHERE zhongwu_name <> "1"') ;
        dump($res);
        for ($i=0;$i < count($res);$i++){
            $patients_id = $res[$i]['patients_id'];
            dump($res[$i]['patients_id']);
            $res_patients = Db::table('patients')->where('patients_id',$patients_id)->find();
            dump($res_patients);
            array_push($res_t,$res_patients);
        }
        $count=count($res_t);
        dump(["code"=>"0","msg"=>"","count"=>$count,"data"=>$res_t]) ;

    }

}