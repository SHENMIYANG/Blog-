<?php
namespace app\afront\model;

use think\Model;

class Login extends Model
{
	protected $pk = 'id';
	// 设置当前模型对应的完整数据表名称
    protected $table = 'fr_user';
    

  	

  	public function mation()
	{
		return $this->hasOne('UserMation','u_id','id');
	}
    //注册入库
    public function register($post)
    {
    	return  Login::save($post);
    }
    //验证邮箱是否唯一
    public function getemail($stremail)
	{

		return Login::where('stremail',$stremail)->find();
		
	}
	//验证账户是否为空
	public function geteloginname($strloginname)
	{

		return Login::where('strloginname',$strloginname)->find();
		
	}
	//修改激活状态
	public function typeupd($id)
	{

		return Login::save(['stractivation'=>'1'],['id'=>$id]);
	}
	//获取重新激活的用户邮箱
	public function getuid($id)
	{	

		return Login::where('id',$id)->field('stremail')->find();
	}
	//登录判断
	public function login_do($post)
	{
		return Login::where('strloginname',$post['strloginname'])->whereOr('stremail',$post['strloginname'])->find();
	}
	//获取重置密码的用户
	public function forget_do($post)
	{
		return Login::where('strloginname',$post['forge_name'])->where('stremail',$post['forge_email'])->find();
	}

	//重置密码
	public function forget_upd($id,$strpwd)
	{
		return Login::save(['strpwd'=>$strpwd],['id'=>$id]);
	}
	//查询用户
	public function getuser($id)
	{
		return Login::where('id',$id)->find();
	}
	//修改信息
	public function userupd($key,$new,$id)
	{
		
		
	}
	//获取粉丝的用户
	public function fensi($user_id)
	{
		// print_r($user_id);die;
		return Login::whereIn('id',$user_id)->select();
	}


	public function fanshtml($user_id,$limit,$size)
	{
		
				
				
	}
}