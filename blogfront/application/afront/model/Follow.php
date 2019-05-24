<?php 
namespace app\afront\model;

use think\Model;

class Follow extends Model
{
	public function  follow($u_id)
	{
		return Follow::where('user_id',$u_id)->where('follow_status','1')->count();
	}
	public function getfollow($user_id,$followed_user)
	{
		return Follow::where('user_id',$user_id)->where('followed_user',$followed_user)->find();
	}
	public function addfollow($arr){
		return Follow::save($arr);
	}
	public  function updfollow($user_id,$followed_user,$follow_status)
	{
		return Follow::save(['follow_status'=>$follow_status],['user_id'=>$user_id,'followed_user'=>$followed_user]);
	}
}