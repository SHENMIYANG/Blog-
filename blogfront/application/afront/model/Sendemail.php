<?php 
namespace app\afront\model;

use think\Model;

class Sendemail extends Model
{
	//邮件发送入库
	public function send($arr)
	{
		return Sendemail::save($arr);
	}
	//验证激活token
	public function gettoken($token)
	{
		return Sendemail::where('token',$token)->find();		
	}
	//获取验证码
	public function forget_code($id)
	{
		return Sendemail::where('af_uid',$id)->order('id desc')->limit(1)->find();
	}
	
	
}



 ?>