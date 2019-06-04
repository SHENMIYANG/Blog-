<?php
namespace app;

use mail\Email;

use Firebase\JWT\JWT;
use think\facade\Env;
class Helper
{
	public static $code =[	
		'1000'=>'请求成功',
		'1001'=>'接口认证失败',
		'1002'=>'token过期',
		'1003'=>'缺失参数',
		'1004'=>'登录用户不存在',
		'1005'=>'登录密码错误',		
		'1006'=>'注册失败',
		'1007'=>'账号被封号',
		'1008'=>'账号被禁用',
		'1009'=>'账号被注销',		
		'1016'=>'密码修改失败',
		'1019'=>'收藏失败',
		'1020'=>'关注失败',
		'1023'=>'参数不正确',
		'1024'=>'无数据了',
		'1025'=>'发表失败',
		'1026'=>'已收藏',
		'1028'=>'未修改',
		'1029'=>'没有评论啦',
		'1030'=>'旧密码不正确',
		'1031'=>'上传失败',
		'1032'=>'修改失败'
	];
	public static function Message($code,$data=array())
	{
		$arr = [
			'code'=>$code,
			'msg'=>self::$code[$code],
			'data'=>$data	
		];
		return  json($arr);exit;
	}

	/**
	 * [获取token]
	 * @param [type] $id [用户id]
	 */
	public static function GetToken($id)
	{
		$key = Env::get('KEY');
		$token = [
			"iss"=>"",  //签发者 可以为空
            "aud"=>"", //面象的用户，可以为空
            "iat" => time(), //签发时间
            "nbf" => time(), //在什么时候jwt开始生效  （这里表示生成100秒后才生效）
            "exp" => time()+Env::get('STOPTIME'), //token 过期时间
            "uid" => $id //记录的userid的信息，这里是自已添加上去的，如果有其它信息，可以再添加数组的键值对
		];

		$tokencontent = JWT::encode($token,$key);

		return $tokencontent;
	}

	/**
	 * [发送邮箱]
	 * @param  [type] $email   [邮箱]
	 * @param  [type] $desc    [发送标题]
	 * @param  [type] $content [发送的内容]
	 * @return [type]          [description]
	 */
	public static function sendemail($email,$desc,$content)
	{
		$mail = new Email;		
		return  $mail->Sendemail($email,$desc,$content);	
	}

	public static function TokenDecode($post)
	{		 
		try{
            $key = Env::get('KEY');

            $token = JWT::decode($post['token'],$key,['HS256']);
           
          	return $token;

          
        }catch(\Exception $e)
        {
        	// echo $e->getMessage();die;
             return false;
        }   
	}
}