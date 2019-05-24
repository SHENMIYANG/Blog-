<?php 
namespace app\afront\model;

use think\Model;

class FrBw extends Model
{
	protected $pk = 'bw_id';

	protected $table = 'fr_bw';
	//join
	public function bkText()
    {
        return $this->hasOne('BkText','bk_id');
    }
	//添加博文
	public function bwadd($bw)
	{

		return  FrBw::save($bw);
	}
	//查询
	public function bwget($id)
	{
		return FrBw::where('bw_id',$id)->find();
	}
	//查询数据总条数

	public function pagetotal($id)
	{
		return FrBw::where('bw_user_id',$id)->count();
	}
	//查询分页数据
	public function gethtml($id,$limit,$size)
	{
		return FrBw::where('bw_user_id',$id)->limit($limit,$size)->select();
	}
	//查询用户数据总条数

	public function usertotal($id)
	{
		return FrBw::where('bw_user_id',$id)->where('bw_status','1')->count();
	}
	//推荐的数据
	public function tuihtml($limit,$size)
	{
		return FrBw::where('bw_status','1')->limit($limit,$size)->order('bw_zan desc')->select();
	}
	//查询该用户收藏的所有数据
	public function shouhtml($bw_id,$limit,$size)
	{

		return FrBw::where('bw_status','1')->whereIn('bw_id',$bw_id)->limit($limit,$size)->select();
	}
	//加赞
	public function zan($bw_id)
	{
		return FrBw::where('bw_id',$bw_id)->field('bw_zan')->find();

	}
	//查询单个赞
	public function lizan($bw_id)
	{

		$data = FrBw::where('bw_id',$bw_id)->field('bw_zan')->find();
		
		return FrBw::save(['bw_zan'=>$data->bw_zan+1],['bw_id'=>$bw_id]);
	}
	//加踩
	public function cai($bw_id)
	{
		return FrBw::where('bw_id',$bw_id)->field('bw_cai')->find();

	}
	//查询单个踩
	public function licai($bw_id)
	{

		$data = FrBw::where('bw_id',$bw_id)->field('bw_cai')->find();
		
		return FrBw::save(['bw_cai'=>$data->bw_cai+1],['bw_id'=>$bw_id]);
	}
	//全站
	public function quanzhan()
	{
		return FrBw::where('bw_status','1')->count();
	}
	//全站的博文
	public function quan_bw($limit,$size)
	{
		return FrBw::where('bw_status','1')->limit($limit,$size)->order('bw_createtime desc')->select();
	}

	
	
}