<?php 
namespace app\afront\model;

use think\Model;

class UserMation extends Model
{
	protected $pk = 'u_id';

	public function login()
	{
		return $this->hasOne('Login','id','u_id');
	}
}