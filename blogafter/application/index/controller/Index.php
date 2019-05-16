<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use think\facade\Session;
use think\facade\Cookie;
class Index extends Controller
{

    /**
     * 渲染登录页面
     *
     * @return \think\Response
     */
    public function index()
    {
        $this->view->engine->layout(false);

        return $this->fetch('login');
    }

    /**
     * 登录.
     *
     * @return \think\Response
     */
    public function create(Request $request)
    {
        //接值
        $data = request()->post();
      
        //查询管理员数据库
        $user = DB('af_user')->where('af_user',$data['af_user'])->find();

    
        //判断用户是否存在       
        if($user)
        {   
            //判断是否禁用了
            if($user['af_status'] != 1)
            {   
                //判断是否封号了
                if($user['af_status'] != 2)
                {
                     //判断密码是否正确
                    if(password_verify($data['af_pwd'], $user['af_pwd']))
                    {   
                        if(time() < $user['af_createtime']+(3600*24*30)  )
                        {
                             //用户id存在session
                            Session::set('user',$user['af_id']);

                            return json(['code'=>'1000','msg'=>'Login success']);
                        }else
                        {
                            return json(['code'=>'1003','msg'=>'PassWord 过期']);
                        }               
                    }else
                    {
                        return json(['code'=>'1002','msg'=>'PassWord Error']);
                    }
                }else
                {
                    return  json(['code'=>'1005','msg'=>'User Title']);
                }
               
            }else{
            
                return  json(['code'=>'1004','msg'=>'User Prohibit']);
            }
            
        }else
        {
            return json(['code'=>'1001','msg'=>'User Error']);
        }
    }

    /**
     * 退出登录
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function loginOut()
    {

        $user = Session::get('user');  
        if(!empty($user)){
            Session::clear(); 
            Cookie::delete('af_user');         
        }
        $this->redirect(url('index/index/index'));
    }
 
}
