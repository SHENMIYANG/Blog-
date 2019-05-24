<?php
namespace app\afront\controller;

use think\Controller;
use think\Request;
use think\facade\Session;
use think\facade\Cookie;
use think\Db;
use app\afront\controller\Upload;
use app\afront\model\Login as a;
use app\afront\model\User as b;
use app\afront\model\Follow as c;
use app\afront\model\Fans as d;
use app\afront\model\FrBw as e;
use app\afront\model\BkText as f;
use app\afront\model\Zan as g;
use app\afront\model\BwCollection as h;
use app\afront\model\BkPl as r;
use app\afront\model\Link as l;
class Publish extends Controller
{
	/**
	 * [发表博文]
	 * @return [type] [description]
	 */
	public function publish()
	{
		if(Session::has('user_id'))
		{
			return $this->fetch('publish');
		}else
		{
			return $this->fetch('xian');
		}
		
	}
	/**
	 * [添加博文]
	 * @return [type] [description]
	 */
	public function bw_add()
	{
		$frbw = new e;
		$bktext = new f;
		$id = Session::get('user_id');
		$bw_author = Session::get('user_name');
		$post = request()->post();
		$html = $this->gethtml($post['bk_text'],'150');
		$bw = [
			'bw_name'=>$post['bw_name'],
			'bw_abstract'=>$html,
			'bw_desc'=>$post['bw_desc'],
			'bw_author'=>$bw_author,
			'bw_createtime'=>time(),
			'bw_status'=>0,
			'bw_user_id'=>$id,
			'bw_public'=>$post['bw_public']
		];
		
		$res = $frbw->bwadd($bw);
	
		$bw_id = $frbw->bw_id;

		$bk = [
			'bk_id'=>$bw_id,
			'bk_text'=>$post['bk_text']
		];

		$res = $bktext->bkadd($bk);
	
		if($res)
		{
			return json(['code'=>1000,'msg'=>'发表成功，等待审核']);
		}else
		{
			return json(['code'=>1001,'msg'=>'发表失败']);
		}
		
	}
	/**
	 * [博客正文]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function bktext($id)
	{
		
		$bw = new e;
		$pl = new r;
		$bw = $bw->bwget($id);
		$pls = $pl->getbwpl($id);

		$bw->bk_text->bk_text = $this->badword($bw->bk_text->bk_text);
			

		return view('bktext',['pls'=>$pls,'bwtext'=>$bw]);
	}
	/**
	 * [博客点赞]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function zan($id)
	{
		if(Session::has('user_id'))
		{	
			$zan =  new g;
			$bw = new e;
			$u_id = Session::get('user_id');
			$res = 	$zan->getzan($u_id,$id)->toarray();
			if($res)
			{				
				return json(['code'=>'1003','msg'=>'已经发表您的意见了哦']);
			}else
			{
				$arr = [
					'bw_id'=>$id,
					'zan'=>1,
					'u_id'=>$u_id
				];
				$res = $zan->zanadd($arr);				
				$res = $bw->lizan($id);

				if($res)
				{
					$zan = $bw->zan($id);
					return json(['code'=>'1000','msg'=>'成功','data'=>$zan]);
				}else
				{
					return json(['code'=>'1001','msg'=>'失败']);
				}				
			}
		}else{
			return json(['code'=>'1009','msg'=>'请先注册或者登录才能进行此操作']);
		}
	}
	/**
	 * [博客踩]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function cai($id)
	{
		if(Session::has('user_id'))
		{	
			$cai =  new g;
			$bw = new e;
			$u_id = Session::get('user_id');
			$res = 	$cai->getzan($u_id,$id)->toarray();
			if($res)
			{				
				return json(['code'=>'1003','msg'=>'已经发表您的意见了哦']);
			}else
			{
				$arr = [
					'bw_id'=>$id,
					'cai'=>1,
					'u_id'=>$u_id
				];
				$res = $cai->zanadd($arr);				
				$res = $bw->licai($id);

				if($res)
				{
					$cai = $bw->cai($id);
					return json(['code'=>'1000','msg'=>'成功','data'=>$cai]);
				}else
				{
					return json(['code'=>'1001','msg'=>'失败']);
				}				
			}
		}else{
			return json(['code'=>'1009','msg'=>'请先注册或者登录才能进行此操作']);
		}
	}
	/**
	 * [收藏]
	 * @param  [type] $bw_id [description]
	 * @return [type]        [description]
	 */
	public function shou($bw_id)
	{
		if(Session::has('user_id'))
		{	
			$shou = new h;
			$u_id = Session::get('user_id');
			$res = $shou->getshou($u_id,$bw_id);
			return $res;

		}else{
			return json(['code'=>'1009','msg'=>'请先注册或者登录才能进行此操作']);
		}
	}
	/**
	 * [获取摘要]
	 * @param  [type] $html     [description]
	 * @param  [type] $numchars [description]
	 * @return [type]           [description]
	 */
	public function gethtml($html, $numchars) {
		//剥去字符串中的 HTML 标签
		$html = strip_tags($html);
		//把 HTML 实体转换为字符
		$html = html_entity_decode($html, ENT_QUOTES, 'UTF-8');
		//获取字符串的长度
		$html_len = mb_strlen($html,'UTF-8');
		//获取部分字符串
		$html = mb_substr($html, 0, $numchars, 'UTF-8');
		//判断要获取的字符串长度
		if($html_len>$numchars)
		{
			$html .= "…";
		}
		//去除字符串里的字符1
		//  $oldchar=array(" ","　","\t","\n","\r");
		// 	$newchar=array("","","","","");
		//  return str_replace($oldchar,$newchar,$html);
		//去除字符串里的字符2
		$html = str_replace(" ", '', $html);
		return $html;
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