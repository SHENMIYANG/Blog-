<?php
namespace app\index\model;

use think\Model;

/**
 *
 */
class UserMation extends Model
{
	protected $pk = 'u_id';

	public function FrBw()
	{
		return $this->hasMany('FrBw','bw_user_id','u_id');
	}
	public function Uptou($u_id,$u_headimg)
	{
		return UserMation::save(['u_headimg'=>$u_headimg],['u_id'=>$u_id]);
	}
	public function SelMy($bw_user_id,$limit,$size)
	{

		return UserMation::with(['FrBw'=>function($query) use ($limit,$size){
			$query->field('bw_user_id,bw_name,bw_desc,bw_abstract,bw_author,bw_createtime,bw_zan,bw_cai,bw_ping,bw_shou')->limit($limit,$size)->order('bw_createtime desc');
		}])->field('u_id,u_headimg,u_signature')->where('u_id',$bw_user_id)->select();
	}
}