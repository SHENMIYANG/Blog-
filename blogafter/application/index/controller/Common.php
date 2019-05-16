<?php 
namespace app\index\controller;

use think\Controller;
use think\Request;
use think\facade\Session;
use think\Db;
class Common extends Controller
{
	public function __construct()
	{
		parent::__construct();
		//判断是否登录或者登录过期
		if(Session::has('user'))
        {		
        	$u_id = Session::get('user');
        	//判断是否是超级管理员
			$role = DB('af_u_r')->where('u_id',$u_id)->where('r_id',1)->find();

			if($role)
			{
				return true;
			}
			$sql = "SELECT * FROM af_node WHERE id IN(SELECT n_id FROM af_r_n WHERE r_id IN(SELECT id FROM af_role WHERE id IN(SELECT u_id from af_u_r WHERE u_id = $u_id)))";
			// $sql = "SELECT * FROM rm_r_n WHERE  id IN(SELECT n_id FROM af_role WHREE  r_id IN())";
			
			$node = Db::query($sql);
			
			$controller  = request()->controller();

			$action = request()->action();
			//每个管理员都可以访问自己的个人信息
			if($controller == "Person")
			{
				return true;
			}
			foreach ($node as $key => $value) {
				if($controller == $value['n_controller'] && $action == $value['n_action'])
				{
					return true;				
				}
			}
			
			$this->error('没有权限');
		}else
        {
            return $this->success('登录过期,请重新登录',url('index/index/index'));
        }
	}
}



