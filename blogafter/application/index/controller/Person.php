<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use think\Db;
use think\facade\Session;
use think\facade\Cookie;
class Person extends Common
{
	/**
     * 展示首页
     *
     * @return \think\Response
     */
    public function index()
    {

        $u_id = Session::get('user');

        $sql = "SELECT `af_user` FROM af_user WHERE af_id = $u_id ";
        
        $user = Db::query($sql);
        
        Cookie::set('af_user',$user[0]['af_user']);
        
        return $this->fetch('index');
       
        
    }
	 /**
     * 渲染个人信息页面
     *
     * @return \think\Response
     */
    public function person(Request $request)
    {
       $u_id = Session::get('user');

       $user = DB('af_user')->where('af_id',$u_id)->find();

       $this->assign('user',$user);

       return $this->fetch('person');

    }

    /**
     * 个人信息修改
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function personupd(Request $request)
    {
        if(request()->isPost())
        {

        }else
        {
            $u_id = Session::get('user');

            $user = DB('af_user')->where('af_id',$u_id)->find();

            $this->view->engine->layout(false);

            $this->assign('user',$user);

            return $this->fetch('personupd');
        }   
    }

    /**
     * 个人密码修改
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function personupdpwd(Request $request)
    {
        if(request()->isPost())
        {
            $post = request()->post();

            $af_id = Session::get('user');

            $data = DB('af_user')->where('af_id',$af_id)->find();

            // $old_af_pwd = password_hash($post['old_af_pwd'], PASSWORD_DEFAULT);
            // echo $old_af_pwd;die;
            if(password_verify($post['old_af_pwd'], $data['af_pwd']))
            {
               $sql = "SELECT `u_pwd` FROM af_pwdstatus WHERE u_id = $af_id ORDER BY u_createtime DESC LIMIT 0,3";

               $arr = Db::query($sql);
               for ($i=0; $i <3 ; $i++) { 
                    if(password_verify($post['new_af_pwd'], $arr[$i]['u_pwd']))
                    {
                        return $this->error('近三个月使用过该密码，请重新填写');
                    }else
                    {
                        $new_af_pwd = password_hash($post['new_af_pwd'], PASSWORD_DEFAULT);
                        $sql = "UPDATE af_user SET `af_pwd`  = '".$new_af_pwd."' WHERE af_id = $af_id";
                     
                        $newpwd = [
                            'u_id'=>$af_id,
                            'u_pwd'=>$new_af_pwd,
                            'u_createtime'=>time(),
                            'u_status'=>1,
                        ];
                        
                        DB('af_pwdstatus')->insert($newpwd);
                        $res = Db::execute($sql);
                        if($res)
                        {
                            return $this->success('恭喜您修改密码成功，请重新登录',url('index/index/index'));
                        }else
                        {
                            return $this->error('修改失败');
                        }
                    }
               }
            }else
            {
                return $this->error('旧密码不正确');
            }
        
        }else
        {
            $this->view->engine->layout(false);
            
            $af_id = Session::get('user');

            $data = DB('af_user')->where('af_id',$af_id)->find();
      
            $this->assign('data',$data);
            return $this->fetch('personupdpwd');
        }   
    }
}