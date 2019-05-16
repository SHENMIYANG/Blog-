<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use think\facade\Session;
use think\facade\Cookie;
use think\Db;

class Pl extends Common
{
	/**
	 * [评论未审核列表]
	 * @return [type] [description]
	 */
	public function plunpass()
	{	
		$pl = DB('bk_pl')->alias('a')->leftJoin('fr_bw w','a.pl_bw_id = w.bw_id')->where('a.pl_status',0)->select();
		
		$this->assign('pl',$pl);
		
		return $this->fetch('plunpass');
	}
	/**
	 * [评论通过审核列表]
	 * @return [type] [description]
	 */
	public function plpass()
	{
		if(request()->isPost())
		{
			$post = request()->post();

			$res = DB('bk_pl')->where('pl_id',$post['pl_id'])->update(['pl_status'=>1]);
			
			if($res)
			{
				return json(['code'=>'1001','msg'=>'审核成功']);
			}else
			{
				return json(['code'=>'1002','msg'=>'审核失败']);
			}
		}else
		{
			$pl = DB('bk_pl')->alias('a')->leftJoin('fr_bw w','a.pl_bw_id = w.bw_id')->where('a.pl_status',1)->select();
			
			$this->assign('pl',$pl);

			return $this->fetch('plpass');
		}
	}
	/**
	 * [评论未通过审核列表]
	 * @return [type] [description]
	 */
	public function plnopass()
	{
		if(request()->isPost())
		{
			$post = request()->post();

			$res = DB('bk_pl')->where('pl_id',$post['pl_id'])->update(['pl_status'=>2,'pl_reason'=>$post['pl_reason']]);
			if($res)
			{
				$this->success('审核成功','/plnopass');
			}else
			{
				$this->error('审核失败');
			}
		}else
		{
			$pl = DB('bk_pl')->alias('a')->leftJoin('fr_bw w','a.pl_bw_id = w.bw_id')->where('a.pl_status',2)->select();			
			$this->assign('pl',$pl);

			return $this->fetch('plnopass');
		}
	}
	/**
	 * [评论正文]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function pltext($id)
	{

		$pl = DB('bk_pl')->alias('a')->leftJoin('fr_bw w','a.pl_bw_id = w.bw_id')->where('w.bw_id',$id)->find();
	

		$pl['pl_content'] = $this->badword($pl['pl_content']);
	
		$this->assign('pl',$pl);

		$this->view->engine->layout(false);

		return $this->fetch("pltext");
	}

	/**
	 * [敏感词过滤]
	 * @param  [type] $bk_text [description]
	 * @return [type]          [description]
	 */
	public function badword($text)
	{
		$keyword = DB('af_keyword')->field('af_key_word')->select();

		foreach ($keyword as $key => $value) {
			$arr[] = $value['af_key_word'];			
		}

		$keyword1 = array_combine($arr,array_fill(0,count($arr),'***'));

		$str = strtr($text,$keyword1);
		return $str;
	}


}