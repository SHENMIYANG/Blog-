<?php
namespace app\index\model;


use think\Model;

/**
 *
 */
class Sendemail extends Model
{

	public function send($arr)
	{
		return Sendemail::save($arr);
	}


}