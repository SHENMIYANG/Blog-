<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use think\facade\Session;
use think\facade\Cookie;
use think\Db;

class Bow extends Common
{
	public function index()
	{
		$advert = DB('af_Bow')->select();

		$this->assign('advert',$advert);

		return $this->fetch('advertlist');
	}
	public function keyword(request $request)
	{
		if(request()->isPost())
		{
			$af_key_word = request()->post();
			$res = DB('af_keyword')->insert($af_key_word);
			if($res)
			{
				return  json(['code'=>'1000','msg'=>'添加成功']);
			}else
			{
				return  json(['code'=>'1001','msg'=>'添加失败']);
			}
		}else
		{
			$keyword = DB('af_keyword')->select();

			$this->assign('keyword',$keyword);
			$this->view->engine->layout(false);
			return $this->fetch('keyword');
		}
		
	}
	//删除关键字
	public function keyworddel($af_key_word)
	{
		$res = DB('af_keyword')->where('af_key_word',$af_key_word)->delete();
		if($res)
			{
				return  json(['code'=>'1002','msg'=>'删除成功']);
			}else
			{
				return  json(['code'=>'1003','msg'=>'删除失败']);
			}
	}

	//博文
	
	//未审核
	public function bwunpass(request $request)
	{
		$bw = DB('fr_bw')->where('bw_status','0')->select();
		
		$this->assign('bw',$bw);
		
		return $this->fetch('bwunpass');
	}
	//审核通过
	public function bwpass(request $request)
	{
		if(request()->isPost())
		{
			$post = request()->post();
			
			$res = DB('fr_bw')->where('bw_id',$post['bw_id'])->update(['bw_status'=>1]);
			
			if($res)
			{
				return json(['code'=>'1001','msg'=>'审核成功']);
			}else
			{
				return json(['code'=>'1002','msg'=>'审核失败']);
			}
		}else
		{
			$bw = DB('fr_bw')->where('bw_status','1')->select();

			$this->assign('bw',$bw);
			
			return $this->fetch('bwpass');
		}
	}
	//审核未通过
	public function bwnopass(request $request)
	{
		if(request()->isPost())
		{
			$post = request()->post();
			
			$res = DB('fr_bw')->where('bw_id',$post['bw_id'])->update(['bw_status'=>2,'bw_reason'=>$post['bw_reason']]);
			
			if($res)
			{
				$this->success('审核成功','/plnopass');
			}else
			{
				$this->error('审核失败');
			}
		}else
		{
			$bw = DB('fr_bw')->where('bw_status','2')->select();

			$this->assign('bw',$bw);

			return $this->fetch('bwnopass');
		}
	}
	//博客正文
	public function bktext($id)
	{
		$sql = "SELECT * FROM fr_bw LEFT JOIN bk_text ON fr_bw.bw_id = bk_text.bk_id WHERE fr_bw.bw_id = ".$id."";

		$bktext = Db::query($sql);
	
		$bktext[0]['bk_text'] = $this->badword($bktext[0]['bk_text']);
		$this->assign('bktext',$bktext[0]);
		
		$this->view->engine->layout(false);
		
		return $this->fetch("bktext");
		
	}
	//敏感词过滤
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
