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
class About extends Controller
{
	/**
	 * [个人]
	 * @return [type] [description]
	 */
	public function about()
	{
		if(Session::has('user_id'))
		{
			$user = new a;
			$mation = new b;
			$follow =  new c;
			$fans = new d;
			$bowen = new e;
			$text = new f;
			$id = Session::get('user_id');
			//个人信息
			$user_mation = $mation->userget($id);
			//关注
			$fow = $follow->follow($id);
			//粉丝
			$fans = $fans->fans($id);
			//查询出来这个博主的所有博文			
			$userdata = $user->getuser($id);
			return view('about',['follow'=>$fow,'fans'=>$fans,'mation'=>$user_mation,'user'=>$userdata]);			
		}else
		{
			return $this->fetch('xian');
		}
		
	}

	/**
	 * [分页]
	 * @return [type] [description]
	 */
	public function page()
	{
		if(request()->isAjax())
		{

			$page = request()->get();

			$curPage = $page['curPage'];

			//分页所需数据
			$bw = new e;
			$id = Session::get('user_id');

			$total  = $bw->pagetotal($id); //  总条数

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

	/**
	 * [上传头像]
	 * @return [type] [description]
	 */
	public function upp()
	{
		if(request()->isPost())
		{
			$upload = new Upload;
			$u_id = request()->post('u_id');

			$file_name = request()->file('u_headerimg');

			$path = "./uploads";

			$u_headerimg  = $upload->move($file_name,$path);
		
			if($u_headerimg == "上传文件大小不符！")
			{
				return json(['code'=>'1003','msg'=>'上传文件大小不符！']);
			}
			$mation = new b;
			$res = $mation->upimg($u_id,$u_headerimg);
			if($res)
			{
				return json(['code'=>'1000','msg'=>'上传成功']);
			}else
			{
				return  json(['code'=>'1002','msg'=>'上传失败']);
			}
		}else
		{
			$mation = new b;
			$u_id = Session::get('user_id');
			$mation = $mation->userget($u_id);
			$this->assign('mation',$mation);
			$this->view->engine->layout(false);
			return $this->fetch('upload');
		}
		
	}

}