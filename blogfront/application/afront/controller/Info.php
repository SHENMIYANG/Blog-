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
class Info extends Controller
{


	/**
	 * [信息设置]
	 * @return [type] [description]
	 */
	public function info()
	{
		if(Session::has('user_id'))
		{
			$user = new a;
			$mation = new b;
			$id = Session::get('user_id');


			//基本资料
			$userdata = $user->getuser($id);

			$this->assign('user',$userdata);
			return $this->fetch('info');
		}else
		{
			return $this->fetch('xian');
		}
	}
	/**
	 * [信息修改]
	 * @return [type] [description]
	 */
	public function user_upd()
	{
		$post = request()->post();

		if($post['old_val'] == $post['new_val'] || $post['new_val'] == "")
		{
			return json(['code'=>'1001','msg'=>'未修改']);
		}
		$user = a::get($post['id']);

		$key = $post['key'];
		$new = $post['new_val'];
		$id = $post['id'];
		$user->save(["$key"=>$new]);
		$res = $user->mation->save(["$key"=>$new]);
		if($res)
		{
			return json(['code'=>'1000','msg'=>'修改成功']);
		}else
		{
			return json(['code'=>'1003','msg'=>'修改失败']);
		}
	}
}