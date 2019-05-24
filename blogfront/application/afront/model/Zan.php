<?php 
namespace app\afront\model;

use think\Model;

class Zan extends Model
{
	//获取赞的状态
	public function  getzan($u_id,$bw_id)
	{
		return Zan::where('u_id',$u_id)->where('bw_id',$bw_id)->select();
	}
	//赞上
	public function zanadd($arr)
	{
		return Zan::save($arr);
	}


}