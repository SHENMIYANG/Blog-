<?php
namespace app\index\model;

use think\Model;

/**
 *
 */
class Fans extends Model
{
	/**
	 * [设置默认主键]
	 * @var int
	 */
	protected $pk = 'fans_user'; 
	/**
	 * [两表]
	 * @return [type] [description]
	 */
	public function user()
	{
		return $this->hasOne('User','id','fans_user');
	}

	public function FansCount($id)
	{
		return Fans::where('user_id',$id)->count();
	}
	public function FansUpd($post,$fans_status)
	{
		return Fans::save(['fans_status'=>$fans_status],['user_id'=>$post['followed_user'],'fans_user'=>$post['u_id']]);
	}


}