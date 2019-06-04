<?php
namespace app\index\model;

use think\Model;

/**
 *
 */
class Zan extends Model
{
	//获取赞或者踩
	public function Zanget($post)
	{
		return Zan::where('bw_id',$post['bw_id'])->where('u_id',$post['u_id'])->find();
	}
	//修改赞的状态值
	public function ZanUpd($post,$zan)
	{
		return Zan::save(['zan'=>$zan],['bw_id'=>$post['bw_id'],'u_id'=>$post['u_id']]);
	}
	//修改踩的状态值
	public function CaiUpd($post,$cai)
	{
		return Zan::save(['cai'=>$cai],['bw_id'=>$post['bw_id'],'u_id'=>$post['u_id']]);
	}


}