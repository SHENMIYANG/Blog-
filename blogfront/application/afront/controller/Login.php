<?php

namespace app\afront\controller;

use think\Controller;
use think\Request;
use think\facade\Session;
use think\facade\Cookie;
use think\Db;
use mail\Email;

use app\afront\model\Login as a;
use app\afront\model\Sendemail as b;
use app\afront\model\User as c;
use app\afront\controller\Upload;

class Login extends Controller
{
	
	public function login()
	{

		$this->view->engine->layout(false);
		return $this->fetch('login');
	}
	
	/**
	 * [登录]
	 * @return [type] [description]
	 */
	public function login_do()
	{
		$post = request()->post();
		// print_r($post);die;
		$login = new a;
		$data = $login->login_do($post);
	
		if($data)
		{
			if($data->stractivation == 1)
			{
				if($post['strpwd'] == $data->strpwd)
				{
					if($data->strstatus == 2)
					{
						return json(['code'=>'1004','msg'=>'您的账号已被封号，请注意查看您的邮箱']);
					}else
					{
						if($data->strstatus == 1)
						{
							return json(['code'=>'1005','msg'=>'您的账号因'.$data->strreason.'被禁用'.$data->strstopday.'天，请在'.date('Y-m-d H:i:s',$data->strstoptime).'后尝试登录']);
						}else
						{
							if($data->strstatus == 3)
							{
								return json(['code'=>'1006','msg'=>'该账号已注销']);
							}else
							{		
								Session::set('user_id',$data->id);	
								Session::set('user_name',$data->strlookname);					
								return json(['code'=>'1001','msg'=>'登录成功']);							
							}
						}
					}
				}else
				{
					return json(['code'=>'1007','msg'=>'您账号或者密码错误']);
				}
			}else
			{
				return json(['code'=>'1003','msg'=>'您的账号没激活，请激活后登录']);
			}
		}else
		{
			return json(['code'=>'1002','msg'=>'您账号或者密码错误']);
		}
	}
	/**
	 * [注册]
	 * @param  request $request [description]
	 * @return [type]           [description]
	 */
	public function register(request $request)
	{
		$md = new a;
		$sd = new b;
		$ma = new c;
		$post = request()->post();
		$post['strstatus'] = 0;
		$post['strcreatetime'] =  time();
		$post['stractivation'] = 0;
		
		$res1 = $md->getemail($post['stremail']);
		$res2 = $md->getelookname($post['strelookname']);
		if($res1)
		{
			return json(['code'=>'1002','msg'=>'该邮箱已存在']);
		}else
		{
			if($res2)
			{
				$res = $md->register($post);
				$ma->useradd($post['id']);
				if($res)
				{		
					$md = new a;
					$sd = new b;
					$id = $md->id;	

					$desc = "智行博客欢迎您进行注册！";
					$descs = "请点击此链接进行激活";
					//获取每一条新增的id					
					$post = $md->getuid($id);
					
					//发送的邮箱
					$email = $post->stremail;
					//token
					$str = 'Start activating accounts'.rand(1000,9999);
					$arr['token'] = base64_encode($str);
					//用户id
					$arr['af_uid'] = $id;
					//过期时间
					$arr['stoptime'] = time()+(60*15);		
					//激活的链接
					$arr['sendemail'] = "http://blogafter.test/istoken?token=".$arr['token'];

					$arr['type'] = 0;

					$content = "<h2>".$descs."</h2>"."<a href=".$arr['sendemail'].">".$arr['sendemail']."</a>";
							
					$res = $sd->send($arr);
					if($res)
					{
						$msg =  $this->sendemail($email,$desc,$content);
						if($msg == 'ok')
						{
							return json(['code'=>'1000','msg'=>'邮件发送成功,请点击邮件里的链接来激活账号！']);
						}else{
							return json(['code'=>'1004','msg'=>'邮件发送失败']); 
						}
					}	

									
				}else
				{
					return json(['code'=>'1003','msg'=>'用户入库错误']);
				}	
			}else{
				return json(['code'=>'1005','msg'=>'该账号已存在']);
			}

				
		}
		
	}
	/**
	 * [激活]
	 * @return [type] [description]
	 */
	public function istoken()
	{
		$sd = new b;
		$md = new a;
		$token = request()->get('token');
		
		$data = $sd->gettoken($token);
		
		if($data)
		{
			if( time() < $data->stoptime)
			{

				$res = $md->typeupd($data->af_uid);

				if($res)
				{
					return $this->success('账号激活成功，自动登录中···','/fr_login');
				}
			}else
			{
				return $this->success('该链接地址过期，请重新发送','/newsend?token='.$data->token);
			}
		}		
	}
	/**
	 * [重新发送]
	 * @return [type] [description]
	 */
	public function newsend()
	{
		$token = request()->get('token');
		$sd = new b;
		$data = $sd->gettoken($token);
		$this->assign('data',$data);
		$this->view->engine->layout(false);
		return 	$this->fetch('newsend');
	}	
	/**
	 * [发送邮箱 参数设置 邮箱，发送标题，发送的内容]
	 * @param  [type] $email   [description]
	 * @param  [type] $desc    [description]
	 * @param  [type] $content [description]
	 * @return [type]          [description]
	 */
	// public function sendemail($email,$desc,$content)
	// {
	// 	$mail = new Email;		
	// 	return  $mail->Sendemail($email,$desc,$content);	
	// }
	/**
	 * [重置密码发送邮件]
	 * @return [type] [description]
	 */
	public function forget()
	{
		$login = new a;
		$post = request()->post();
		$data = $login->forget_do($post);
		if($data)
		{
			$sd = new b;
			//验证的用户邮箱
			$email = $post['forge_email'];

			$desc = "智行博客提醒您进行设置！";
			//进行邮件发送入库
			$arr['af_uid'] = $data->id;
			$arr['type'] = "1";
			$arr['code'] = rand(10000,99999);
			$arr['stoptime'] = time()+(60*15);
			$content = "<h2>请保存好此验证码，用来进行您的重置密码:".$arr['code']."，此验证码15分钟内有效</h2>";			
			$res = $sd->send($arr);
			if($res)
			{
				$msg =  $this->sendemail($email,$desc,$content);

				if($msg == 'ok')
				{
					return json(['code'=>'1000','msg'=>'邮件发送成功,请获取邮件里的验证码来进行下一步操作！']);
				}else{
					return json(['code'=>'1004','msg'=>'邮件发送失败']); 
				}
			}
			
		}else
		{
			return json(['code'=>'2001','msg'=>'账号和邮箱不匹配']);
		}
	}
	/**
	 * [重置密码]
	 * @return [type] [description]
	 */
	public function forupd()
	{
		$post = request()->post();
	
		$login = new a;
		$send = new b;
		//获取当前的用户账号
		$arr = $login->forget_do($post);
		//获取code码和验证码过期时间
		$data = $send->forget_code($arr->id);
		if( time() < $data->stoptime)
		{
			if($post['forge_code'] == $data->code)
			{
				$res = $login->forget_upd($arr->id,$post['forge_password']);
				if($res)
				{
					return json(['code'=>'2000','msg'=>'恭喜您，密码修改成功']);
				}else
				{
					return json(['code'=>'2005','msg'=>'密码修改失败，请联系管理员查看问题']);
				}
			}else{
				return json(['code'=>'2003','msg'=>'验证码不正确']);
			}
		}else
		{
			return json(['code'=>'2004','msg'=>'验证码已过期，请重新发送邮件获取验证码']);
		}
		
	}
	/**
	 * [退出登录]
	 * @return [type] [description]
	 */
	public function loginout()
	{
		Session::clear();
		return json(['code'=>'1000','msg'=>'退出成功']);

	}
}