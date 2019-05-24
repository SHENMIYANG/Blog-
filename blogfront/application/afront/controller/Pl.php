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
class Pl extends Controller
{


	public function ping()
	{
		return $this->fetch('ping');
	}
	public function pingadd()
	{
		if(Session::has('user_id'))
		{
			$post = request()->post();
			$pl_fr_id = Session::get('user_id');
			$pl_author = Session::get('user_name');
			$pl = new r;
			$arr = [
				'pl_author'=>$pl_author,
				'pl_fr_id'=>$pl_fr_id,
				'pl_content'=>$post['pl_content'],
				'pl_createtime'=>time(),
				'pl_status'=>0,
				'pl_bw_id'=>$post['bw_id']
			];
			$res  = $pl->bwpladd($arr);
			if($res)
			{
				return json(['code'=>'1000','msg'=>'评论成功，请耐心等待审核！']);
			}else
			{
				return json(['code'=>'1001','msg'=>'评论失败，请联系管理员']);
			}
			echo $res;die;
		}else
		{
			return json(['code'=>'1002','msg'=>'您需要先登录或者注册才可以发表您的意见哦']);
		}
		
	}

	public function pingpage()
	{
		if(Session::has('user_id'))
		{
			$page = request()->post();

			$pl = new r;

			$size = 3;

			$count = $pl->getplpage($page['bw_id']);


			$totalPage =ceil($count/$size);

			if($page['page'] > $totalPage)
			{
				return json(['code'=>'1004','msg'=>'没有更多评论了']);
			}
			$limit = ($page['page']-1)*$size;

			$data = $pl->getplhtml($page['bw_id'],$limit,$size);
			foreach($data as $lab) {
	     
	       		$lab->pl_createtime = date('Y-m-d',$lab->pl_createtime);
	       }			
			return json(['code'=>'1001','msg'=>'ok','data'=>$data]);
		}else
		{
			return json(['code'=>'1002','msg'=>'您需要先登录或者注册才可以看到更多的评论']);
		}
	}

	//我的评论
	public function meping()
	{
		if(request()->isAjax())
		{
			$page = request()->get();

			$curPage = $page['curPage'];

			//分页所需数据
			$ping = new r;
			$id = Session::get('user_id');

			$total  = $ping->getmyping($id); //  总条数
			
			$size = 4; //每页条数

			$totalPage =ceil($total/$size);  //总页数

			$limit = ($curPage-1)*$size;  //偏移量

			$data = $ping->getmyplhtml($id,$limit,$size);
		   $arr['total']=$total;
	       $arr['size']=$size;
	       $arr['totalPage']=$totalPage;
	         foreach($data as $lab) {
	       		$lab->pl_createtime = date('Y-m-d',$lab->pl_createtime);
	           $arr['data'][] = $lab;
	       }

	       $this->result($arr,'1','成功','json');
	   }else
	   {
	   		$this->result($arr,'0','失败','json');
	   }
	}

}