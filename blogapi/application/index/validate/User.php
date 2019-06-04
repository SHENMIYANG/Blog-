<?php

namespace app\index\validate;

use think\Validate;

class User extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        "strloginname"=>'require|regex:/^[-_a-zA-Z0-9]{6,30}$/|unique:fr_user',
        "strlookname"=>'require|regex:/^[\x{4e00}-\x{9fa5}A-Za-z0-9_]+$/u',
        "stremail"=>'require|email|unique:fr_user',
        "strtel"=>'require|mobile',
        "strpwd"=>'require|regex:/^[a-zA-Z]\w{5,17}$/',
        "repwd"=>"require|confirm:strpwd",
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        "strloginname.require"=>'账号不能为空',
        "stremail.require"=>'邮箱不能为空',
        "strlookname.require"=>'昵称不能为空',
        "strtel.require"=>'手机号不能为空',
        "strpwd.require"=>'密码不能为空',
        "repwd.require"=>'请再次确认密码',
        "strloginname.unique"=>'该账号已存在',
        "stremail.unique"=>'该邮箱已存在',
        "strloginname.regex"=>'账号必须英文、数字但不包括下划线等符号的6-30位',       
        "strlookname.regex"=>'昵称必须为汉字字母数字下划线3-30位',        
        "stremail.email"=>'请填写正确的邮箱格式',        
        "strtel.mobile"=>'手机号格式不正确',       
        "strpwd.regex"=>"密码格式不正确",       
        "repwd.confirm"=>'两次密码不一致'
    ];
}
