<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use think\facade\Session;
use think\facade\Cookie;
use think\Db;

class Link extends Common
{
	public function index()
	{
		$sql = "SELECT * FROM af_link LEFT JOIN af_user ON af_link.li_uid = af_user.af_id";
		
		$link = Db::query($sql);

		$this->assign('link',$link);

		return $this->fetch('linklist');
	}
	public function linkadd(request $request)
	{
		if(request()->isPost())
		{
			$post = request()->post();
			$li_uid = Session::get('user');
			$post['li_createtime'] = time();
			$post['li_uid'] = $li_uid;
			$res = DB('af_link')->insert($post);
			if($res)
			{
				return $this->success('友情链接添加成功','/linklist');
			}else
			{
				return $this->error();
			}
	
		}else
		{
			$this->view->engine->layout(false);
			return $this->fetch('linkadd');
		}
	}
	
	public function linkupd()
	{

	}
	public function linkdel($id)
	{
		$res = DB('af_link')->where('id',$id)->delete();
		if($res)
		{
			return json(['code'=>1001,'msg'=>'删除成功']);
		}else
		{
			return json(['code'=>1002,'msg'=>'删除失败']);
		}
	}
}