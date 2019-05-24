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

//前台

//登录
Route::rule('fr_login','afront/Login/login','GET|POST');
//登录功能
Route::rule('login_do','afront/Login/login_do','GET|POST');


//注册
Route::post('register','afront/Login/register');
//邮件验证激活
Route::get('istoken','afront/Login/istoken');
//链接过期
Route::get('newsend','afront/Login/newsend');
//发送邮件
Route::post('sendemail','afront/Login/sendemail');
//用户重置密码发邮件
Route::post('forget','afront/Login/forget');
//用户重置密码
Route::post('forupd','afront/Login/forupd');


//博客首页
Route::rule('/','afront/Front/front','GET|POST');
//关于我
Route::rule('about','afront/About/about','GET|POST');
//我的收藏
Route::rule('share','afront/Front/share','GET|POST');
//我的评论
Route::rule('ping','afront/Pl/ping','GET|POST');
//我的推荐
Route::rule('list','afront/Front/list','GET|POST');
//信息设置
Route::rule('info','afront/Info/info','GET|POST');
//发表博文
Route::rule('publish','afront/Publish/publish','GET|POST');
//随便看看
Route::rule('golook','afront/Golook/golook','GET|POST');
//信息修改
Route::post('user_upd','afront/Info/user_upd');
//用户头像上传
Route::rule('upp','afront/About/upp','GET|POST');


//添加博文
Route::post('bw_add','afront/Publish/bw_add');


//查看全文
Route::get('bktext/:id','afront/Publish/bktext'); 
//赞
Route::post('zan','afront/Publish/zan');

//踩
Route::post('cai','afront/Publish/cai');
//收藏
Route::post('shou','afront/Publish/shou');

//查看博主

Route::get('bk_user/:id','afront/Front/bk_user'); 

//个人博主分页
Route::rule('page','afront/About/page','GET|POST');
//收藏分页
Route::rule('shoufen','afront/Front/shoufen','GET|POST');
//其他博主分页
Route::rule('user_page','afront/Front/user_page','GET|POST'); 
//首页分页
Route::rule('titlepage','afront/Front/titlepage','GET|POST'); 
//推荐分页
Route::rule('tuipage','afront/Front/tuipage','GET|POST'); 

//取消收藏
Route::post('quxiaoshou','afront/Front/quxiaoshou'); 

//点击关注
Route::post('followadd','afront/Follow/followadd');
//取消关注
Route::post('followdel','afront/Follow/followdel');
//我的粉丝
Route::rule('fans','afront/Fans/fansindex','GET|POST');
//粉丝分页
Route::rule('fspage','afront/Fans/fspage','GET|POST');



//评论
Route::post('pingadd','afront/Pl/pingadd');

//评论分页
Route::post('pingpage','afront/Pl/pingpage');
//我的评论分页数据
Route::rule('meping','afront/Pl/meping','GET|POST');	


//退出登录
Route::post('/loginout','afront/Login/loginout');

//验证修改邮箱是否存在
Route::post('/emailget','afront/Front/emailget');

