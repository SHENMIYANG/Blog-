<?php
namespace app\index\model;

use think\Model;

/**
 *
 */
class Token extends Model
{
	protected $pk = 'u_id'; 
	//注册添加token
	public function TokenAdd($arr)
	{
		return Token::save($arr);
	}
	//登录修改token 
	public function TokenUpd($id,$token,$endtimes)
	{

		return Token::save(['token'=>$token,'endtimes'=>$endtimes],['u_id'=>$id]);
	}
	//查询token
	public function TokenSel($id,$token)
	{
		return Token::where('id',$id)->where('token',$token)->find();
	}
	//退出登录删除token
	public function TokenDel($id)
	{
		return Token::where('id',$id)->delete();
	}
}