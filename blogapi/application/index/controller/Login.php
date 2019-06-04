<?php
namespace app\index\controller;

use think\Controller;
use think\Request;
use think\Db;
use think\Validate;
use app\Helper;
use app\index\validate\User as Usrule;
use app\facade\User;
use app\facade\UserMation;
class Login extends Controller
{

	/**
	 * [用户登录]
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function login(Request $request)
	{

		$user = request()->post();	

		$us = User::sellogin($user['loginname']);
		if(!empty($us))
		{
			//判断密码是否正确
			if($user['strpwd'] == $us->strpwd)
			{		
			
				//判断用户是否封号
				if($us->strstatus == 2)
				{
					return Helper::Message(1007);
						
				}else{
					//判断用户是否禁用
					if($us->strstatus == 1)
					{
						$data = ["禁用理由：".$us->strreason.",被禁用".$us->strstopday."天,请于".date('Y-m-d H:i:s',$us->strstoptime)."后尝试登录"];
						return Helper::Message(1008,$data);		
					}else{
						
						//判断用户是否注销
						if($us->strstatus == 3)
						{
							return Helper::Message(1009);
						}else{
							$token =  Helper::GetToken($us->id);
							//判断用户是否退出登录			
							$data = [
								'id'=>$us->id,
								'strlookname'=>$us->strlookname,
								'stremail'=>$us->stremail,
								'strcreatetime'=>$us->strcreatetime,
								'token'=>$token
							]; 
							return Helper::Message(1000,$data);							
						}		
					}			
				}
			}else
			{
				return Helper::Message(1005);
			}
		}else
		{
			return Helper::Message(1004);
		}
	}
	/**
	 * [用户注册]
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function register(Request $request)
	{
		
		$usrule = new Usrule;
		$data = request()->post();
		$channel_id = request()->server('HTTP_CHANNELID');
		//验证规则
		if(!$usrule->check($data))
		{
			return Helper::Message(1023,$usrule->getError());
		}
		$userdata = User::sellogin($data['strloginname'],$data['stremail']);
		
		//注册时间
		$data['strcreatetime'] = time();
		//注册未激活
		$data['stractivation'] = 1;
		//用户来源
		$data['channel_id'] = $channel_id;
		unset($data['repwd']);
		//用户状态
		$data['strstatus'] = 1;	
		$res = User::create($data);
		$arr=[
			'u_id'=>$res->id,
		];
		UserMation::create($arr);
		if($res)
		{
			//获取token
			$token = Helper::GetToken($res->id);
			$data = [
				'id'=>$res->id,
				'strlookname'=>$data['strlookname'],
				'stremail'=>$data['stremail'],
				'strcreatetime'=>$data['strcreatetime'],
				'token'=>$token
			]; 
			
			return Helper::Message(1000,$data);				
		}else
		{
			//注册失败
			return Helper::Message(1006);
		}
	}	


	/**
	 * [重置密码]
	 * @return [type] [description]
	 */
	public function forgetupd()
	{
		$post = request()->post();

		$rule = [  
			"oldpwd|旧密码"=>'require', 
            "newpwd|新密码"=>'require|regex:/^[a-zA-Z]\w{5,17}$/',
        ];
        $validate = Validate::make($rule);
		if(!$validate->check($post)){
		    return  Helper::Message(1023,$validate->getError());
		}
		$user = User::userSel($post['u_id']);
		if($user)
		{
			if($post['oldpwd'] == $user['strpwd'])
			{
				$res = User::pwdUpd($post);
				if($res)
				{
					return Helper::Message(1000);
				}else
				{
					return Helper::Message(1016);
				}
			}else
			{
				return Helper::Message(1030);
			}
		}
		
	}

	/**
	 * [头像上传]
	 * @return [type] [返回状态值]
	 */
	public function upimage()
	{

		$u_id = request()->post('u_id');

		$file = request()->file('u_headimg');

		$info = $file->validate(['size'=>700000 ,'ext'=>'jpg,png,gif'])->move('./uploads');
		if($info){

	        // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
	        $u_headimg =  $info->getSaveName();

	    }else{
	        // 上传失败获取错误信息
	       return Helper::Message(1023,$file->getError());
	    }

	    $res = UserMation::Uptou($u_id,$u_headimg);
	    if($res)
	    {
	    	return Helper::Message(1000);
	    }else
	    {
	    	return Helper::Message(1031);
	    }
	} 


}
