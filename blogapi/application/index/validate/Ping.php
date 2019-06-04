<?php

namespace app\index\validate;

use think\Validate;

class Ping extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        "pl_fr_id"=>'require',
        "pl_author"=>'require',
        "pl_content"=>'require',
        "pl_bw_id"=>'require',
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        "pl_fr_id.require"=>'用户id不能为空',
        "pl_author.require"=>'谁评论的呢',
        "pl_content.require"=>'评论内容要写哦',
        "pl_bw_id.require"=>'评论哪篇博文呢',
    ];
}
