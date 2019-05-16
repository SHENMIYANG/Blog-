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

// Route::get('think', function () {
//     return 'hello,ThinkPHP5!';
// });

// Route::get('hell/:name', 'index/hello');


//登录页面
Route::get('login','index/index/index');
//登录方法
Route::post('login_do','index/index/create');
//退出登录
Route::get('loginout','index/index/loginOut');



//展示首页
Route::get('after', 'index/Person/index');
//管理员添加
Route::any('adminadd', 'index/after/adminadd');
//管理员删除
Route::any('admin_del', 'index/after/admin_del');
//管理员停用
Route::rule('admin_stop', 'index/after/admin_stop','GET|POST');
//管理员启用
Route::rule('admin_start', 'index/after/admin_start','GET|POST');
//管理员列表
Route::get('adminshow', 'index/after/adminshow');
//报错
Route::get('errors','index/common/errors');


//个人信息
Route::get('person','index/Person/person');
//个人信息修改
Route::rule('personupd','index/Person/personupd','GET|POST');
//个人密码修改
Route::rule('personupdpwd','Person/personupdpwd','GET|POST');


//显示表单角色页
Route::rule('user_role','User/user_role','GET|POST');
//角色添加
Route::rule('user_role_add','User/user_role_add','GET|POST');


//查看添加权限
Route::rule('user_node','User/user_node','GET|POST');
//权限添加
Route::rule('user_node_add','User/user_node_add','GET|POST');
//分配权限
Route::rule('auth_node','User/auth_node','GET|POST');
//获取每个角色的权限
Route::rule('auth_node_get','User/auth_node_get','GET|POST');


//广告列表
Route::rule('advertlist','Advert/index','GET|POST');
//广告添加
Route::rule('advertadd','Advert/advertadd','GET|POST');
//广告删除
Route::rule('advertdel','Advert/advertdel','GET|POST');
//广告修改
Route::rule('advertupd','Advert/advertupd','GET|POST');
//广告停用
Route::rule('advertstop','Advert/advertstop','GET|POST');
//广告启用
Route::rule('advertstart','Advert/advertstart','GET|POST');


//友情链接
Route::rule('linklist','Link/index','GET|POST');
//友情链接添加
Route::rule('linkadd','Link/linkadd','GET|POST');
//友情链接删除
Route::rule('linkdel','Link/linkdel','GET|POST');
//友情链接修改
Route::rule('linkupd','Link/linkupd','GET|POST');


//关键词
Route::rule('keyword','Bow/keyword','GET|POST');
//关键词
Route::post('keyworddel','Bow/keyworddel');


//博文未审核列表
Route::rule('bwunpass','Bow/bwunpass','GET|POST');
//博文通过审核
Route::rule('bwpass','Bow/bwpass','GET|POST');
//博文不通过审核
Route::rule('bwnopass','Bow/bwnopass','GET|POST');
//博文正文页
Route::rule('bktext/:id','Bow/bktext','GET|POST');


//评论未审核列表
Route::rule('plunpass','Pl/plunpass','GET|POST');
//评论通过审核
Route::rule('plpass','Pl/plpass','GET|POST');
//评论不通过审核
Route::rule('plnopass','Pl/plnopass','GET|POST');
//评论正文页
Route::rule('pltext/:id','Pl/pltext','GET|POST');



//用户列表
Route::rule('member_list','Member/member_list','GET|POST');
//事件处理
Route::rule('member_start','Member/member_start','GET|POST');
//禁用处理
Route::rule('member_stop','Member/member_stop','GET|POST');
//软删除处理
Route::rule('member_del','Member/member_del','GET|POST');










return [

];
