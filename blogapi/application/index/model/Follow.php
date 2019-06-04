<?php
namespace app\index\model;

use think\Model;

/**
 *
 */
class Follow extends Model
{


	public function FollowSel($post)
	{
		return Follow::where('user_id',$post['u_id'])->where('followed_user',$post['followed_user'])->find();
	}

	public function FollowUpd($post,$follow_status)
	{
		return Follow::save(['follow_status'=>$follow_status],['user_id'=>$post['u_id'],'followed_user'=>$post['followed_user']]);
	}
}