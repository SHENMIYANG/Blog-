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
class Front extends Controller
{
	/**
	 * [修改邮箱判断]
	 * @return [type] [description]
	 */
	public function emailget()
	{
		$md = new a;
		$post = request()->post();
		$res = $md->getemail($post['stremail']);
		if($post['old_val'] == $post['new_val'] || $post['new_val'] == "")
		{
			return json(['code'=>'1001','msg'=>'未修改']);
		}
		if($res)
		{
			return json(['code'=>'1002','msg'=>'该邮箱已存在']);
		}else
		{
			return json(['code'=>'1003','msg'=>'ok']);
		}
	}
	/**
	 * [首页]
	 * @return [type] [description]
	 */
	public function front()
	{
		$lk = new l;
		$link = $lk->getlink();
		// print_r($link);die;
		return view('index',['link'=>$link]);
	}	
	
	/**
	 * [分页]
	 * @return [type] [description]
	 */
	public function shoufen()
	{
		if(request()->isAjax())
		{
			$page = request()->get();

			$curPage = $page['curPage'];

			//分页所需数据
			$bw = new e;
			$shou = new h;

			$id = Session::get('user_id');

			$bw_id =  $shou->shouget($id)->toarray();
			$bwid = "";
			//whereIn查询
			foreach ($bw_id as $key => $value) {
					
				$bwid .= ",".$value['bw_id'];
			}
				
			$total  = $shou->shoutotal($id); //  总条数
				
			$size = 4; //每页条数

			$totalPage =ceil($total/$size);  //总页数

			$limit = ($curPage-1)*$size;  //偏移量

			$data = $bw->shouhtml(ltrim($bwid,','),$limit,$size);

		    $arr['total']=$total;
	        $arr['size']=$size;
	        $arr['totalPage']=$totalPage;

	       foreach($data as $lab) {
	       		$lab->bw_createtime = date('Y-m-d',$lab->bw_createtime);
	           $arr['data'][] = $lab;
	       }
	 
	       $this->result($arr,'1','成功','json');
	   }else
	   {
	   		$this->view->engine->layout(false); 
	   		return  view('404');
	   }
			
	}
	/**
	 * [我的收藏]
	 * @return [type] [description]
	 */
	public function share()
	{
		if(Session::has('user_id'))
		{
			return $this->fetch('share');
		}else
		{
			return $this->fetch('xian');
		}
	}
	
	/**
	 * [博文博主]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function bk_user($id)
	{
			// print_r($id);die;
			$user = new a;
			$mation = new b;
			$follow =  new c;
			$fans = new d;
			$bowen = new e;
			$text = new f;
			$user_id  = Session::get('user_id');
			//个人信息
			$user_mation = $mation->userget($id);
			//关注
			$fow = $follow->follow($id);
			
			$follows = $follow->getfollow($user_id,$id);
			$status = 0;
			if($user_id == $id)
			{
				return $this->redirect('/about');
			}else
			{
				if($follows)
				{

					if($follows->follow_status == '1')
					{
						$status = 1;
					}else if($follows->follow_status == "2")
					{
						$status = 0;
					}
				}
			}
			//粉丝
			$fans = $fans->fans($id);
			
			$userdata = $user->getuser($id);

			return view('bk_user',['status'=>$status,'follow'=>$fow,'fans'=>$fans,'mation'=>$user_mation,'user'=>$userdata]);
	}	
	/**
	 * [博文博主页分页]
	 * @return [type] [description]
	 */
	public function user_page()
	{
		if(request()->isAjax())
		{

			$page = request()->get();

			$curPage = $page['curPage'];

			//分页所需数据
			$bw = new e;
			
			$id = $page['id'];


			$total  = $bw->usertotal($id); //  总条数

			$size = 4; //每页条数

			$totalPage =ceil($total/$size);  //总页数

			$limit = ($curPage-1)*$size;  //偏移量

			$data = $bw->gethtml($id,$limit,$size);
		   $arr['total']=$total;
	       $arr['size']=$size;
	       $arr['totalPage']=$totalPage;
	         foreach($data as $lab) {
	       		$lab->bw_createtime = date('Y-m-d',$lab->bw_createtime);
	           $arr['data'][] = $lab;
	       }

	       $this->result($arr,'1','成功','json');
	   }else
	   {
	   		$this->view->engine->layout(false); 
	   		return  view('404');
	   }
			
	}

	//随便看看
	public function golook()
	{
		return view('golook');
	}
	/**
	 * [首页分页]
	 * @return [type] [description]
	 */
	public function titlepage()
	{
		if(request()->isAjax())
		{

			$page = request()->get();

			$curPage = $page['curPage'];

			//分页所需数据
			$bw = new e;

			$total  = $bw->quanzhan(); //  总条数

			$size = 4; //每页条数

			$totalPage =ceil($total/$size);  //总页数

			$limit = ($curPage-1)*$size;  //偏移量

			$data = $bw->quan_bw($limit,$size);

			$arr['total']=$total;
		    $arr['size']=$size;
		    $arr['totalPage']=$totalPage;
	        foreach($data as $lab) {
	       		$lab->bw_createtime = date('Y-m-d',$lab->bw_createtime);
	           $arr['data'][] = $lab;
	       }

	       $this->result($arr,'1','成功','json');
	   }else
	   {
	   		$this->view->engine->layout(false); 
	   		return  view('404');
	   }
			
	}
	//推荐
	public function list()
	{
		if(Session::has('user_id'))
		{
			return view('list');
		}else
		{
			return $this->fetch('xian');
		}
	}
	/**
	 * [推荐分页]
	 * @return [type] [description]
	 */
	public function tuipage()
	{
		if(request()->isAjax())
		{

			$page = request()->get();

			$curPage = $page['curPage'];

			//分页所需数据
			$bw = new e;

			$total  = $bw->quanzhan(); //  总条数

			$size = 4; //每页条数

			$totalPage =ceil($total/$size);  //总页数

			$limit = ($curPage-1)*$size;  //偏移量

			$data = $bw->tuihtml($limit,$size); //查询分页数据

			$arr['total']=$total;
		    $arr['size']=$size;
		    $arr['totalPage']=$totalPage;
	        foreach($data as $lab) {
	       		$lab->bw_createtime = date('Y-m-d',$lab->bw_createtime);
	           $arr['data'][] = $lab;
	       	}
	       $this->result($arr,'1','成功','json');
	   }else
	   {
	   		$this->view->engine->layout(false); 
	   		return  view('404');
	   }
			
	}
	//取消收藏
	public function quxiaoshou()
	{
		$post = request()->post();
		$u_id = Session::get('user_id');

		$s = new h;
		$res = $s->shoudel($u_id,$post['bw_id']);

		if($res)
		{
			return json(['code'=>'1001','msg'=>'取消成功']);
		}else
		{
			return json(['code'=>'1002','msg'=>'取消失败']);
		}
	} 
}