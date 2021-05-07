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
    return 'hello,ThinkPHP5!';
});
Route::get('youwei/test', function () {
    return 'hello,youwei!';
});

Route::get('hello/:name', 'index/hello');
Route::rule('clean','app/command/AutoCleanPhoto');
Route::rule('test','PreSave/imgCtShow');
Route::rule('ct_image/:ct_id/:patients_id', function () {
    return 'hello,youwei!';
});

