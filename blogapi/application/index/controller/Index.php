<?php
namespace app\index\controller;

use think\Controller;
use think\Request;
use think\Db;
use app\Helper;
use app\index\validate\User as Usrule;
use app\facade\FrBw;
class Index extends Controller
{
	/**
	 * [首页]
	 * @return [type] [首页分页数据]
	 */
	public function index()
	{
		$post = request()->get();
	
		//获取总条数
		$count = FrBw::ListCount();
		//获取分页所需条件
		$page = $this->page($post['p'],$count);
	
		//查询分页数据
		$data = FrBw::Bwpage($page['limit'],$page['size'],'bw_createtime');
		
		return Helper::Message(1000,$data);
	}
	/**
	 * [随便看看]
	 * @return [type] [随便看看分页数据]
	 */
	public function golook()
	{
		$post = request()->get();
		//获取总条数
		$count = FrBw::ListCount();
		//获取分页所需条件
		$page = $this->page($post['p'],$count);
		//查询分页数据
		$data = FrBw::Bwpage($page['limit'],$page['size'],'bw_ping');

		return Helper::Message(1000,$data);
	}

	/**
	 * [分页公共方法]
	 * @param  [type] $p     [当前页]
	 * @param  [type] $count [总条数]
	 * @return [type] $data  [返回的偏移量，每页条数]
	 */
	public function page($p,$count)
	{
		//每页条数
		$size = 8;
		//总页数
		$total = ceil($count/$size);
		//判断最后一页
		if($p>$total)
		{
			return Helper::Message(1024);
		}
		//偏移量
		$limit = ($p -1)*$size;
		$data = [
			'limit'=>$limit,
			'size'=>$size
		];
		return $data;
	}
	
}