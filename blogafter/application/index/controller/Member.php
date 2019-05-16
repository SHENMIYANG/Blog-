<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use think\facade\Session;
use think\facade\Cookie;
use think\Db;

class Member extends Controller
{
	public function member_list()
	{
		$member = DB('fr_user')->select();

		$this->assign('member',$member);

		return $this->fetch('member_list');
	}
	//启用
	public function member_start()
	{
		$post = request()->post();
		$bw_public = 1;
		DB('fr_bw')->where('bw_user_id',$post['id'])->update(['bw_public'=>$bw_public]);
		$res = DB('fr_user')->where('id',$post['id'])->update(['strstatus'=>$post['strstatus']]);
		
		if($res)
		{
			return json(['code'=>'1001','msg'=>'执行成功']);
		}else
		{
			return json(['code'=>'1002','msg'=>'执行失败']);
		}
	}
	//禁用
	public function member_stop(request $request)
	{
		if(request()->isPost())
		{
			$post = request()->post();
			$strstoptime = ($post['strstopday']*3600*24)+time();
			$res = DB('fr_user')->where('id',$post['id'])->update(['strstatus'=>1,'strstopday'=>$post['strstopday'],'strstoptime'=>$strstoptime,'strreason'=>$post['strreason']]);
			if($res)
			{
				return $this->success('执行成功','/Member_list');
			}else
			{
				return $this->error('执行失败');
			}
		}else
		{
			return $this->redirect('/Member_list');
		}
	}
	//
	
	//删除
	public function member_del(request $request)
	{
		if(request()->isPost())
		{
			$post = request()->post();
			$bw_public =0;
			DB('fr_bw')->where('bw_user_id',$post['id'])->update(['bw_public'=>$bw_public]);
			$res = DB('fr_user')->where('id',$post['id'])->update(['strstatus'=>$post['strstatus']]);
			if($res)
			{
				return json(['code'=>'1001','msg'=>'执行成功']);
			}else
			{
				return json(['code'=>'1002','msg'=>'执行失败']);
			}
		}else
		{
			return $this->redirect('/Member_list');
		}
	}
}