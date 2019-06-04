<?php

namespace app\index\validate;

use think\Validate;

class Bw extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        "bw_user_id"=>'require',
        "bw_name"=>'require',
        "bw_desc"=>'require',
        "bw_text"=>'require',
        "bw_public"=>'require',
        "bw_author"=>'require',
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        "bw_user_id.require"=>'用户id不能为空',
        "bw_name.require"=>'博客标题不能为空',
        "bw_desc.require"=>'博客简介不能为空',
        "bw_text.require"=>'博客正文不能为空',
        "bw_public.require"=>'请设置私有公有',
        "bw_author.require"=>'博客发表人是谁呢，请填写哦'
    ];
}
