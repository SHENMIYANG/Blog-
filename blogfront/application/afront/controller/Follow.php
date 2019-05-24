<?php
namespace app\afront\controller;

use think\Controller;
use think\Request;
use think\facade\Session;
use think\facade\Cookie;
use think\Db;
use app\afront\controller\Upload;

use app\afront\model\Follow as c;
use app\afront\model\Fans as d;
class Follow extends Controller
{
	//关注
	public function followadd()
	{
		if(Session::has('user_id'))
		{	
			$fw = new c;
			$fs = new d;
			$post = request()->post();
			$user_id = Session::get('user_id');
			$data = $fw->getfollow($user_id,$post['followed_user']);

			if($data)
			{
			
				$follow_status = 1;
				$fans_status = 0;
				$res = $fw->updfollow($user_id,$post['followed_user'],$follow_status);
				$ress  = $fs->updfans($post['followed_user'],$user_id,$fans_status);

				if($res && $ress)
				{
					return json(['code'=>'1001','msg'=>'关注成功']);
				}else
				{
					return json(['code'=>'1002','msg'=>'关注失败']);
				}
			}else
			{

				$arr = [
					'user_id'=>$user_id,
					'followed_user'=>$post['followed_user'],
					'follow_status'=>'1'
				];
				$arrs = [
					'user_id'=>$post['followed_user'],
					'fans_user'=>$user_id,
					'fans_status'=>0,
					'fans_times'=>time()
				];
				$res = $fs->addfans($arrs);
				$ress = $fw->addfollow($arr);
				if($res && $ress)
				{
					return json(['code'=>'1001','msg'=>'关注成功']);
				}else
				{
					return json(['code'=>'1002','msg'=>'关注失败']);
				}			
			}
		}else{
			return json(['code'=>'1009','msg'=>'请先注册或者登录才能进行此操作']);
		}
		
	}
	//取消关注
	public function followdel()
	{
		if(Session::has('user_id'))
		{
			$fw = new c;
			$fs = new d;
			$post = request()->post();
			$user_id = Session::get('user_id');	
			$follow_status = 2;
			$fans_status = 1;
			$res = $fw->updfollow($user_id,$post['followed_user'],$follow_status);
			$ress  = $fs->updfans($post['followed_user'],$user_id,$fans_status);

			if($res && $ress)
			{
				return json(['code'=>'1001','msg'=>'取消关注成功']);
			}else
			{
				return json(['code'=>'1002','msg'=>'取消关注失败']);
			}
		}else{
			return json(['code'=>'1009','msg'=>'请先注册或者登录才能进行此操作']);
		}
		
	}
}