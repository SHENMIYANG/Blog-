<?php
namespace app\index\model;

use think\Model;

/**
 *
 */
class FrBw extends Model
{

	public function Frpl()
	{
		return $this->hasOne('FrPl','pl_bw_id','bw_id');
	}
	public function Text()
	{
		return $this->hasOne('BkText','bk_id','bw_id');
	}
	//获取我的博文的总条数
	public function BwCount($bw_user_id)
	{
		return FrBw::where('bw_user_id',$bw_user_id)->count();
	}
	//获取我的博文分页数据
	public function BwSel($bw_user_id,$limit,$size)
	{
		return FrBw::where('bw_user_id',$bw_user_id)->limit($limit,$size)->order('bw_createtime desc')->select();
	}
	//获取我的推荐分页总条数
	public function ListCount()
	{
		return FrBw::where('bw_status',1)->where('bw_public',2)->count();
	}
	//获取首页，推荐，随便看看分页数据
	public function Bwpage($limit,$size,$status)
	{
		return FrBw::where('bw_status',1)->where('bw_public',2)->limit($limit,$size)->order("$status desc")->select();
	}
	//公共加方法
	public function jia($bw_id,$field,$status)
	{
		$data = FrBw::where('bw_id',$bw_id)->field("$field")->find();
		if($status == 1)
		{
			return FrBw::save([$field=>$data->$field+1],['bw_id'=>$bw_id]);
		}else if($status == 0)
		{
			return FrBw::save([$field=>$data->$field-1],['bw_id'=>$bw_id]);
		}
		
	}

	public function bktext($bw_id)
	{
		return FrBw::with(['Text'=>function($query){
				$query->field('bk_id,bk_text');
			}])->field('bw_id,bw_name,bw_desc,bw_author,bw_createtime,bw_zan,bw_cai,bw_ping,bw_shou')->where('bw_id',$bw_id)->find();
	}
		
	


}