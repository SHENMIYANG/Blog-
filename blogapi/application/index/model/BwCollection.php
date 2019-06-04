<?php
namespace app\index\model;

use think\Model;

/**
 *
 */
class BwCollection extends Model
{
	protected $pk = 'bw_id';
	public function frbw()
	{
		return $this->hasOne('FrBw','bw_id','bw_id');
	}
	//获取我的收藏的总条数
	public function BwCount($u_id)
	{
		return BwCollection::where('u_id',$u_id)->count();
	}

	//添加收藏
	public function BwCollectionSel($post)
	{
		return BwCollection::where('u_id',$post['u_id'])->where('bw_id',$post['bw_id'])->find();
	}
	//取消收藏
	public function BwCollectionDel($post)
	{
		return BwCollection::where('u_id',$post['u_id'])->where('bw_id',$post['bw_id'])->delete();
	}

}