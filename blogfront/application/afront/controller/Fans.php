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
class Fans extends Controller
{
	public function fansget()
	{
		$post = request()->post();
		$fans = new d;
		$data = $fans->getfans($post['user_id']);
		print_r($data);die;
	}
	public function fansindex()
	{
		return view('fans');
	}
	public function fspage()
	{
		if(request()->isAjax())
		{
			$page = request()->get();

			$curPage = $page['curPage'];

			//分页所需数据
			$us  = new a;
			$fs = new d;
			$id = Session::get('user_id');		
			$total  = $fs->fans($id); //  总条数

			$size = 5; //每页条数

			$totalPage =ceil($total/$size);  //总页数

			$limit = ($curPage-1)*$size;  //偏移量

			$data = $fs::with('user.mation')->where('user_id',$id)->limit($limit,$size)->select();
			
			// $data = $us->fanshtml($id,$limit,$size);
			// $data = $us->with('fans')->select();
		   $arr['total']=$total;
	       $arr['size']=$size;
	       $arr['totalPage']=$totalPage;
	         foreach($data as $lab) {
	       		$lab->fans_times = date('Y-m-d',$lab->fans_times);
	         $arr['data'][] = $lab;
	       } 
	      	if(!isset($arr['data']))
	      	{	
	      		$arr['data'] = [];
	      	}   	
	       $this->result($arr,'1','成功','json');
	   }else
	   {
	   		$this->view->engine->layout(false); 
	   		return  view('404');
	   }
			
	}
}