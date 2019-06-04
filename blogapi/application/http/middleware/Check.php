<?php

namespace app\http\middleware;

use app\Helper;
use think\facade\Env;
use Firebase\JWT\JWT;
class Check
{
    public function handle($request, \Closure $next )
    {      
       
        $post = request()->get();
        
        if(!request()->get('u_id'))
        {
            return Helper::Message(1003);
        }
        if(!request()->get('token'))
        {
            return Helper::Message(1003);
        }
        $token = Helper::TokenDecode($post);
       
        if(!$token)
        {
            return Helper::Message(1001);   
        }

        if($token->exp < time())
        {
            return Helper::Message(1002);
        }else
        {
            return $next($request);    
        }
     
        
    }
}
