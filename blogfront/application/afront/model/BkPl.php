<?php 
namespace app\afront\model;

use think\Model;

class BkPl extends Model
{
	/**
	 * [获取博文的评论]
	 * @param  [type] $pl_bw_id [description]
	 * @return [type]           [description]
	 */
	public function getbwpl($pl_bw_id)
	{
		return BkPl::where('pl_bw_id',$pl_bw_id)->where('pl_status','1')->limit(3)->order('pl_id desc')->select();
	}
	/**
	 * [博文评论添加]
	 * @param  [type] $arr [description]
	 * @return [type]      [description]
	 */
	public function bwpladd($arr)
	{
		return BkPl::save($arr);
	}
	//获取评论分页总条数
	public function getplpage($pl_bw_id)
	{
		return BkPl::where('pl_bw_id',$pl_bw_id)->where('pl_status','1')->order('pl_id desc')->count();
	}
	//获取评论分页数据
	public function getplhtml($pl_bw_id,$limit,$size)
	{
		
		return BkPl::where('pl_bw_id',$pl_bw_id)->where('pl_status','1')->limit($limit,$size)->order('pl_id desc')->select();
	}
	//获取我的评论分页总条数
	public function getmyping($pl_fr_id)
	{
		return BkPl::where('pl_fr_id',$pl_fr_id)->order('pl_id desc')->count();
	}
	//获取评论分页数据
	public function getmyplhtml($pl_fr_id,$limit,$size)
	{
		
		return BkPl::where('pl_fr_id',$pl_fr_id)->limit($limit,$size)->order('pl_createtime desc')->select();
	}
}