<?php
namespace app\index\model;

use think\Model;

/**
 *
 */
class BkPl extends Model
{
	/**
	 * [设置默认主键]
	 * @var int
	 */
	protected $pk = 'pl_bw_id'; 

	/**
	 * [获取用户评论的总数]
	 * @param [type] $pl_fr_id [评论用户的id]
	 */
	public function PingCount($pl_fr_id)
	{
		return BkPl::where('pl_fr_id',$pl_fr_id)->count();
	}

	//删除评论
	public function PingDel($post)
	{
		return BkPl::where('pl_fr_id',$post['pl_fr_id'])->where('pl_id',$post['pl_id'])->where('pl_bw_id',$post['pl_bw_id'])->delete();
	}

	public function Bwping($pl_bw_id,$limit,$size)
	{
		return BkPl::where('pl_bw_id',$pl_bw_id)->order('pl_createtime desc')->page($limit,$size)->select()->toArray();
	}


}