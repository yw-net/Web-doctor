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

Route::get('think', function () {
    return 'hello,doctor!';
});
//图片路由
Route::rule('file/:date/:name','PreSave/imgRoute')->pattern(['name'=>'[^@]+.[\w]{0,4}']);
//文件下载路由
Route::rule('download/:date/:name','PreSave/download')->pattern(['name'=>'[^@]+.[\w]{0,4}']);
//自动清理冗余图片
Route::rule('clean','app/command/AutoCleanPhoto');

//测试
Route::rule('test','PreSave/ceshi');
Route::rule('t','Menu/ceshi');
Route::rule('ct_image/:ct_id/:patients_id', function () {
    return 'hello,youwei!';
});

