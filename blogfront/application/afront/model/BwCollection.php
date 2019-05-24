<?php 
namespace app\afront\model;

use think\Model;

class BwCollection extends Model
{


	// protected $table = 'bw_collerction';

	public function getshou($u_id,$bw_id)
	{
		$res = BwCollection::where('u_id',$u_id)->where('bw_id',$bw_id)->find();
		if($res)
		{
			return  json(['code'=>'1002','msg'=>'已收藏，如若要查看请移至我的收藏']);
		}else
		{
			$ress =  BwCollection::save(['u_id'=>$u_id,'bw_id'=>$bw_id]);
			if($ress)
			{
				return  json(['code'=>'1001','msg'=>'收藏成功']);
			}else
			{
				return  json(['code'=>'1003','msg'=>'收藏失败']);
			}
		}
	}

	public function shouget($u_id)
	{

		return  BwCollection::where('u_id',$u_id)->field('bw_id')->select();
	}
	//查看收藏的总数
	public function shoutotal($u_id)
	{	

		return BwCollection::where('u_id',$u_id)->count();
	}
	
	//取消收藏
	
	public function shoudel($u_id,$bw_id)
	{
		return BwCollection::where('u_id',$u_id)->where('bw_id',$bw_id)->delete();
	}
}
