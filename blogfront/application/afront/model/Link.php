<?php 
namespace app\afront\model;

use think\Model;

class Link extends Model
{
	protected $table = 'af_link';

	//查询出所有link
	public function getlink()
	{
		return Link::select();
	}
}