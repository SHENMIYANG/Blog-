<?php
namespace app\index\controller;

use think\Controller;
use think\Request;
use think\Db;
use app\Helper;
use app\index\validate\Bw as Bwrule;
use app\facade\FrBw;
use app\facade\BkPl;
use app\facade\BkText;
class Boke extends Controller
{
	/**
	 * [博文添加]
	 * @param  request $request [description]
	 * @return [type]           [description]
	 */
	public function bwadd(request $request)
	{
		$bwrule = new Bwrule;
		$post = request()->post();
		
		if(!$bwrule->check($post))
		{
			return Helper::Message('1023',$bwrule->getError());
		}		
		$bw = [
			'bw_name'=>$post['bw_name'],
			'bw_abstract'=>$this->badword($this->gethtml($post['bw_text'],150)),
			'bw_desc'=>$post['bw_desc'],
			'bw_author'=>$post['bw_author'],
			'bw_createtime'=>time(),
			'bw_status'=>0,
			'bw_user_id'=>$post['bw_user_id'],
			'bw_public'=>$post['bw_public']
		];		
		$res = FrBw::create($bw);

		$bktext =[
			'bk_id'=>$res->id,
			'bk_text'=>$this->badword($post['bw_text'])
		];
		$res = BkText::create($bktext);
		if($res)
		{
			return Helper::Message('1000');
		}else
		{
			return Helper::Message('1025');
		}
	}
	/**
	 * [获取摘要]
	 * @param  [type] $html     [要截取的文章]
	 * @param  [type] $numchars [要截取的多少字符]
	 * @return [type]           [截取完成的摘要]
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
	/**
	 * [博客正文]
	 * @return [type] [description]
	 */
	public function bktext()
	{
		$post = request()->post();
		if(isset($post['bw_id']) && !empty($post['bw_id']))
		{
			$pl = BkPl::Bwping($post['bw_id'],1,3);
			//时间转换
			foreach($pl as $key => $lab) {	     
	       		$pl[$key]['pl_createtime'] = date('Y-m-d',$lab['pl_createtime']);
		    }

			$data = FrBw::bktext($post['bw_id']);
			//过滤敏感字符
			$data->text->bk_text = $this->badword($data->text->bk_text);
			$data->bw_createtime = date('Y-m-d',$data->bw_createtime);
			$data->pl = $pl;
			return Helper::Message(1000,$data);
		}else
		{
			return Helper::Message(1023);
		}
		
	}
	/**
	 * [博客评论分页]
	 * @return [type] [description]
	 */
	public function pingpage()
	{
		$post = request()->post();
	
		if((isset($post['bw_id']) && !empty($post['bw_id'])) && (isset($post['p']) && !empty($post['p'])))
		{
			$size = 3;
			$pl = BkPl::Bwping($post['bw_id'],$post['p'],$size);
			if($pl)
			{
				foreach($pl as $key => $lab) {	     
	       			$pl[$key]['pl_createtime'] = date('Y-m-d',$lab['pl_createtime']);
		       	}
		      	return Helper::Message(1000,$pl);
			}else
			{
				return Helper::Message(1029);
			}
			
	    }else
		{
			return Helper::Message(1023);
		}
   }
}