<?php
namespace app\index\controller;

use think\Controller;
use think\Request;
use think\Db;
use app\Helper;
use app\index\validate\Ping as Pingrule;
use app\facade\User;
use app\facade\FrBw;
use app\facade\Zan;
use app\facade\BwCollection;
use app\facade\Fans;
use app\facade\Follow;
use app\facade\BkPl;
class Action extends Controller
{
	/**
	 * [赞]
	 * @return [type] [description]
	 */
	public function givezan()
	{
		$post = request()->post();
		if((isset($post['bw_id']) && !empty($post['bw_id'])) && (isset($post['u_id']) && !empty($post['u_id'])))
		{
			$res = Zan::Zanget($post);
			//判断有没有赞过 
			if(empty($res))
			{
				//没赞过则添加
				$arr = [
					'bw_id'=>$post['bw_id'],
					'zan'=>1,
					'u_id'=>$post['u_id']
				];
				$res = Zan::create($arr);	
				$ress = FrBw::jia($post['bw_id'],'bw_zan',1);
				if($res && $ress)
				{
					return Helper::Message(1000);
				}else
				{
					return Helper::Message(1023);
				}
						
			}else
			{
				//赞过则修改
				if($res['zan'] == 1)
				{
					$res = Zan::ZanUpd($post,2);
					$ress = FrBw::jia($post['bw_id'],'bw_zan',0);
				}else if($res['zan'] == 2 || $res['zan'] == "")
				{
					$res = Zan::ZanUpd($post,1);
					$ress = FrBw::jia($post['bw_id'],'bw_zan',1);
				}
				if($res && $ress)
				{
					return Helper::Message(1000);
				}else
				{
					return Helper::Message(1023);
				}
			}
		}else
		{
			return Helper::Message(1003);
		}
	}
	/**
	 * [踩]
	 * @return [type] [description]
	 */
	public function givecai()
	{
		$post = request()->post();
		if((isset($post['bw_id']) && !empty($post['bw_id'])) && (isset($post['u_id']) && !empty($post['u_id'])))
		{
			$res = Zan::Zanget($post);

			if(empty($res))
			{
				$arr = [
					'bw_id'=>$post['bw_id'],
					'cai'=>1,
					'u_id'=>$post['u_id']
				];
				$res = Zan::create($arr);		
				$ress = FrBw::jia($post['bw_id'],'bw_cai',1);
				if($res && $ress)
				{
					return Helper::Message(1000);
				}else
				{
					return Helper::Message(1023);
				}
						
			}else
			{
				//如果踩过 则修改状态博文踩减一
				if($res['cai'] == 1)
				{
					$res = Zan::CaiUpd($post,2);
					$ress = FrBw::jia($post['bw_id'],'bw_cai',0);
				}else if($res['cai'] == 2 || $res['cai'] == "")
				{
					$res = Zan::CaiUpd($post,1);
					$ress = FrBw::jia($post['bw_id'],'bw_cai',1);
				}
				if($res && $ress)
				{
					return Helper::Message(1000);
				}else
				{
					return Helper::Message(1023);
				}
			}
		}else
		{
			return Helper::Message(1003);
		}
	}

	/**
	 * [收藏]
	 * @return [type] [返回请求状态]
	 */
	public function collection()
	{
		$post = request()->post();
		if((isset($post['bw_id']) && !empty($post['bw_id'])) && (isset($post['u_id']) && !empty($post['u_id'])))
		{
			$data = BwCollection::BwCollectionSel($post);
			if($data)
			{
				//已收藏
				return Helper::Message(1026);
			}else
			{
				$res = BwCollection::create($post);
				FrBw::jia($post['bw_id'],'bw_shou',1);
				if($res)
				{
					//收藏成功
					return Helper::Message(1000);
				}else
				{
					return Helper::Message(1019);
				}
			}			
		}else
		{
			return Helper::Message(1003);
		}
		

	}
	/**
	 * [取消收藏]
	 * @return [type] [返回请求状态]
	 */
	public function cancel_collection()
	{
		$post = request()->post();
		if((isset($post['bw_id']) && !empty($post['bw_id'])) && (isset($post['u_id']) && !empty($post['u_id'])))
		{
			$res = BwCollection::BwCollectionDel($post);
			FrBw::jia($post['bw_id'],'bw_shou',0);
			if($res)
			{
				//取消收藏成功
				return Helper::Message(1000);

			}else
			{
				//取消收藏失败
				return Helper::Message(1023);
			}
		}else
		{
			return Helper::Message(1003);
		}
	}
	/**
	 * [关注取消关注]
	 * @return [type] [返回请求状态]
	 */
	public function follow()
	{
		$post = request()->post();
		if((isset($post['followed_user']) && !empty($post['followed_user'])) && (isset($post['u_id']) && !empty($post['u_id'])))
		{
			$data = Follow::FollowSel($post);
			if($data)
			{
				//如若用已经关注了 表里面已经有数据 则修改
				//已关注
				if($data['follow_status'] == 1)
				{
					//开始事务
					Follow::startTrans();
					try {
					   //修改关注表的状态
						$res = Follow::FollowUpd($post,2);
						//修改粉丝表的状态
						$ress = Fans::FansUpd($post,1);
					    // 提交事务
					    Follow::commit();
			
					} catch (\Exception $e) {
					    // 回滚事务
					    Follow::rollback();
					}
			
				}else if($data['follow_status'] == 2)//取消关注
				{
					//开始事务
					Fans::startTrans();
					try {
						//修改关注表的状态
						$res = Follow::FollowUpd($post,1);
						//修改粉丝表的状态
						$ress = Fans::FansUpd($post,0);
					    // 提交事务
				    	Fans::commit();
		
					} catch (\Exception $e) {
					    // 回滚事务
					    Fans::rollback();
					}
				}
				if($res && $ress)
				{
					return Helper::Message(1000);
				}else
				{
					return Helper::Message(1023);
				}
			}else
			{
				//如若用户第一次关注 表里面没有数据 则添加
				$arr = [
					'user_id'=>$post['u_id'],
					'followed_user'=>$post['followed_user'],
					'follow_status'=>1
				];
				$arrs = [
					'user_id'=>$post['followed_user'],
					'fans_user'=>$post['u_id'],
					'fans_status'=>0,
					'fans_times'=>time()
				];
				//开始事务
				Follow::startTrans();
				try{
					$res = Follow::create($arr);
					$ress = Fans::create($arrs);
					// 提交事务
				    Follow::commit();
				}catch (\Exception $e) {
					    // 回滚事务
					    Follow::rollback();
				}
				if($res && $ress)
				{
					return Helper::Message(1000);
				}else
				{
					return Helper::Message(1023);
				}
			}
		}else
		{
			return Helper::Message(1003);
		}
	}
	/**
	 * [添加评论]
	 * @return [type] [返回请求状态]
	 */
	public function pingadd()
	{
		$post = request()->post();
		$pingrule = new Pingrule;
		if(!$pingrule->check($post))
		{
			return Helper::Message('1023',$pingrule->getError());
		}
		$arr = [
			'pl_author'=>$post['pl_author'],
			'pl_fr_id'=>$post['pl_fr_id'],
			'pl_content'=>$post['pl_content'],
			'pl_createtime'=>time(),
			'pl_status'=>0,
			'pl_bw_id'=>$post['pl_bw_id']
		];
		$res = BkPl::create($arr);
		FrBw::jia($post['pl_bw_id'],'bw_ping',1);
		if($res)
		{
			return Helper::Message(1000);
		}else
		{
			return Helper::Message(1023);
		}
	}
	/**
	 * [删除评论]
	 * @return [type] [返回请求状态]
	 */
	public function pingdel()
	{
		$post = request()->post();

		if((isset($post['pl_bw_id']) && !empty($post['pl_bw_id'])) && (isset($post['pl_fr_id']) && !empty($post['pl_fr_id'])) && (isset($post['pl_id']) && !empty($post['pl_id'])) )
		{
			
			$res = BkPl::PingDel($post);
			$ress = FrBw::jia($post['pl_bw_id'],'bw_ping',0);
			if($res && $ress)
			{
				return Helper::Message(1000);
			}else
			{
				return Helper::Message(1023);
			}
		}else
		{
			return Helper::Message(1003);
		}
	}
}