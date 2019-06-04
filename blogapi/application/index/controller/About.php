<?php
namespace app\index\controller;

use think\Controller;
use think\Request;
use think\Db;
use think\facade\Env;
use think\Validate;
use app\Helper;
use app\facade\User;
use app\facade\FrBw;
use app\facade\BwCollection;
use app\facade\Fans;
use app\facade\BkPl;
use app\facade\UserMation;
class About extends Controller
{
	/**
	 * [我的博文]
	 */
	public function About()
	{
		$post = request()->get();

		//获取总条数
		$count = FrBw::BwCount($post['u_id']);

		//获取分页所需条件
		$page = $this->page($post['p'],$count);
		
		//查询分页数据
		$bwhtml = UserMation::SelMy($post['u_id'],$page['limit'],$page['size']);
		
		return Helper::Message(1000,$bwhtml);
	}
	/**
	 * [我的推荐]
	 */
	public function List()
	{	
		$post = request()->get();		
		//获取总条数
		$count = FrBw::ListCount();
		//获取分页所需条件
		$page = $this->page($post['p'],$count);
		//查询分页数据
		$bwhtml = FrBw::Bwpage($page['limit'],$page['size'],'bw_zan');

		return Helper::Message(1000,$bwhtml);
	}
	/**
	 * [我的收藏]
	 */
	public function share()
	{
		$post = request()->get();
		//获取总条数
		$count = BwCollection::BwCount($post['u_id']);
		//获取分页所需条件
		$page = $this->page($post['p'],$count);
		//查询分页数据
		$data = BwCollection::with('frbw')->where('u_id',$post['u_id'])->limit($page['limit'],$page['size'])->select();
		
		return Helper::Message(1000,$data);
	}
	/**
	 * [信息设置]
	 * @return [type] [用户信息]
	 */
	public function info()
	{
		$post = request()->get();

		$data = User::SelUser($post['u_id']);

		return Helper::Message(1000,$data);
	}
	/**
	 * [我的评论]
	 * @return [type] [评论的分页]
	 */
	public function ping()
	{
		$post = request()->get();
		//获取总条数
		$count = BkPl::PingCount($post['u_id']);
		//获取分页所需条件
		$page = $this->page($post['p'],$count);
		//查询分页数据
		$data = BkPl::where('pl_fr_id',$post['u_id'])->limit($page['limit'],$page['size'])->select();
		return Helper::Message(1000,$data);
	}
	/**
	 * [我的粉丝]
	 * @return [type] [粉丝的分页数据]
	 */
	public function fans()
	{
		$post = request()->get();
		//获取总条数
		$count = Fans::FansCount($post['u_id']);
		//获取分页所需条件
		$page = $this->page($post['p'],$count);
		//查询分页数据
		$data = Fans::with('user')->where('user_id',$post['u_id'])->where('fans_status',0)->limit($page['limit'],$page['size'])->select();
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
	
		
		//偏移量
		$limit = ($p -1)*$size;
		$data = [
			'limit'=>$limit,
			'size'=>$size
		];
		//判断最后一页
		if($p>$total)
		{
			return $data;
		}
		return $data;
	}
	/**
	 * [信息修改]
	 * @return [type] [返回状态值]
	 */
	public function infoupd()
	{
		$post = request()->post();

		if($post['new_value'] == "" || $post['new_value'] == $post['old_value'])
		{
			return  Helper::Message(1028);
		}
		$type = $post['key']."rule";
		// 创建验证规则
		$strlooknamerule = [
        	'new_value|昵称'   => [
                'regex'    => '/^[\x{4e00}-\x{9fa5}A-Za-z0-9_]+$/u',
            ],
        ];		
        $strtelrule = [       
            'new_value|手机号'   => [     
                'mobile'    => 'mobile',
            ],
        ];
        $stremailrule = [
        	'new_value|邮箱'   => [   
                'email'    => 'email',
            ],
        ]; 
        // 要验证的数据
     	$validate = Validate::make($$type);
		if(!$validate->check($post)){
		    return Helper::Message(1023,$validate->getError());die;
		}
		$user = User::get($post['id']);
		$key = $post['key'];
		$new = $post['new_value'];
		$id = $post['id'];
		$user->save(["$key"=>$new],['id'=>$id]);
		$res = $user->mation->save(["$key"=>$new],['id'=>$id]);
		
		if($res)
		{
			return Helper::Message(1000);
		}else
		{
			return Helper::Message(1032);
		}
	}
}