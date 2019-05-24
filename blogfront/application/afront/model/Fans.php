<?php 
namespace app\afront\model;

use think\Model;

class Fans extends Model
{
	protected $pk = 'fans_user';

	public function user()
  	{
  		return $this->hasOne('login','id','fans_user');
  	}

	public function  fans($u_id)
	{
		return Fans::where('user_id',$u_id)->where('fans_status','0')->count();
	}
	//添加粉丝
	public function  addfans($arrs)
	{
		return Fans::save($arrs);
	}
	public function updfans($user_id,$fans_user,$fans_status)
	{
		return Fans::save(['fans_status'=>$fans_status,'fans_times'=>time()],['user_id'=>$user_id,'fans_user'=>$fans_user]);
	}

	//获取到本人的粉丝
	public function getfans($user_id)
	{
		return Fans::where('user_id',$user_id)->where('fans_status','0')->select();
	}
	//获取到本人的粉丝分页
	public function fanspage($user_id,$limit,$size)
	{
		return Fans::where('user_id',$user_id)->where('fans_status','0')->limit($limit,$size)->withJoin('Login', 'LEFT')->select();
	}
}