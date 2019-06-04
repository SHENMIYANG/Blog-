<?php
namespace app\index\model;

use think\Model;

/**
 *
 */
class User extends Model
{
	
	/**
	 * [设置默认数据表]
	 * @var string
	 */
	protected $table = 'fr_user';
	/**
	 * [设置默认主键]
	 * @var int
	 */
	protected $pk = 'id'; 
	/**
	 * [两表]
	 * @return [type] [两表关联数据]
	 */
	public function mation()
	{
		return $this->hasOne('UserMation','u_id','id');
	}

	
	public function sellogin($loginname)
	{
		return User::where('strloginname',$loginname)->whereOr('stremail',$loginname)->find();
	}
	/**
	 * [查询用户]
	 * @param [type] $id [用户的id]
	 */
	public function SelUser($id)
	{

		return User::with(['mation'=>function($query){
			$query->field('u_id,u_signature,u_jobs');
		}])->field('id,strloginname,strlookname,stremail,strtel')->where('id',$id)->find();
	}
	//修改
	public function InfoUpd($post)
	{
		return User::save([$post['key']=>$post['new_value']],['id'=>$post['id']]);
	}	
	//查询修改密码的用户
	public function userSel($id)
	{
		return User::where('id',$id)->field('strpwd')->find()->toarray();
	}
	//修改密码
	public function pwdUpd($post)
	{
		return User::save(['strpwd'=>$post['newpwd']],['id'=>$post['u_id']]);
	}
}