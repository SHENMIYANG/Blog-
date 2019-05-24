<?php 
namespace app\afront\model;

use think\Model;

class User extends Model
{
	 protected $table = 'user_mation';
	 //新增一条用户
	 public function useradd($u_id)
	 {
	 	return User::save(['u_id'=>$u_id]);
	 }
	 //查询该用户的个人信息
	 public function userget($u_id)
	 {
	 	return User::where('u_id',$u_id)->find();
	 }
	 //修改用户的头像
	 public function upimg($u_id,$file)
	 {
	 	return User::save(['u_headimg'=>$file],['u_id'=>$u_id]);
	 }
}