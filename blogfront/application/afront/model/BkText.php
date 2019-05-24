<?php 
namespace app\afront\model;

use think\Model;

class BkText extends Model
{
	protected $pk = 'bk_id';

	protected $table = 'bk_text';
	//博客正文添加
	public function bkadd($bk)
	{	
		return BkText::save($bk);
	}
	//获取正文
	public function bkget($id)
	{
		return BkText::where('bk_id',$id)->find();
	}
	
}