<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

//图片路由（术前检查）
Route::rule('prefile/:type/:date/:name','PreSave/imgRoute')->pattern(['name'=>'[^@]+.[\w]{0,4}']);
//文件下载路由（术前检查）
//Route::rule('download/:/type/:date/:name','PreSave/download')->pattern(['name'=>'[^@]+.[\w]{0,4}']);

//图片路由（手术信息）
Route::rule('surfile/:type/:date/:name','SurgicalInfoSave/imgRoute')->pattern(['name'=>'[^@]+.[\w]{0,4}']);
//图片路由（复诊信息）
Route::rule('reffile/:type/:date/:name','ReferralSave/imgRoute')->pattern(['name'=>'[^@]+.[\w]{0,4}']);

//自动清理冗余图片
Route::rule('clean','app/command/AutoCleanPhoto');
Route::rule('PreSave/getCTAddress','PreSave/getCTAddress');

//测试
Route::rule('test','PreSave/ceshi');
Route::rule('t','Menu/ceshi');
Route::rule('ct_image/:ct_id/:patients_id', function () {
    return 'hello,youwei!';
});

