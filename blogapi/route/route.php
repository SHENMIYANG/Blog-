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



 
//登录
Route::post('login','Login/login');

//注册
Route::post('register', 'Login/register');



Route::group('/', function() {
	//用户的查询页面
	//关于我
    Route::get('about', 'About/about');
    //我的收藏
    Route::get('share', 'About/share');
    //我的粉丝
    Route::get('fans', 'About/fans');
    //我的评论
    Route::get('ping', 'About/ping');
    //信息设置
    Route::get('info', 'About/info');
    //我的推荐
    Route::get('list', 'About/list');
    //信息修改
    Route::post('infoupd', 'About/infoupd');

    //用户的动作
    //赞
    Route::post('givezan', 'Action/givezan');
    //踩
    Route::post('givecai', 'Action/givecai');
    //收藏
    Route::post('collection', 'Action/collection');
    //取消收藏
    Route::post('cancel_collection', 'Action/cancel_collection');
    //关注
    Route::post('follow', 'Action/follow');
    //发表评论
    Route::post('pingadd', 'Action/pingadd');
    //删除评论
    Route::post('pingdel', 'Action/pingdel');
    //发表博文
    Route::post('bwadd', 'Boke/bwadd');
    //评论分页
    Route::post('pingpage', 'Boke/pingpage');
   	//重置密码
	Route::post('forgetupd', 'Login/forgetupd');
	//上传头像
	Route::post('upimage', 'Login/upimage');
	 
    
})->middleware('Check');

//首页
Route::get('index', 'index/index');
//随便看看
Route::get('golook', 'index/golook');
//博客正文
Route::post('bktext', 'Boke/bktext');






