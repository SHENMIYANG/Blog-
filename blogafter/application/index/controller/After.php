<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use think\facade\Session;
use think\facade\Cookie;
use think\Db;
class After extends Common
{
    
    /**
     * [管理员添加]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function adminadd(Request $request)
    {
        if(request()->isPost())
        {
            $data = request()->post();

            $data['af_pwd'] = password_hash($data['af_pwd'], PASSWORD_DEFAULT);

            $data['af_createtime'] = time();

            $data['af_status'] = 1;
            $arr = DB('af_user')->where('af_user',$data['af_user'])->find();

            if($arr)
            {
                return $this->error('用户已存在');
            }
            $r_id = $data['r_id'];

            unset($data['r_id']);
            
            $id = DB('af_user')->insertGetId($data);  

            DB('af_u_r')->insert(['u_id'=>$id,'r_id'=>$r_id]);
            $arr = [
                'u_id'=>$id,
                'u_pwd'=>$data['af_pwd'],
                'u_createtime'=>time(),
                'u_status'=>1,
            ];
            DB('af_pwdstatus')->insert($arr);
            if($id)
            {
                $this->redirect(url('index/after/adminshow'));
            }else
            {   
                $this->redirect(url('index/after/adminadd'));
            }
        }else
        {
            $role = DB('af_role')->select();
       
            $this->assign('role',$role);

            $this->view->engine->layout(false);
            return $this->fetch('admin_add');
        }      
    }
    /**
     * [管理员列表]
     * @return [type] [description]
     */
    public function adminshow()
    {
        // $sql = "SELECT * FROM af_user LEFT JOIN af_u_r as a ON a.u_id = af_user.af_id WHERE af_user.af_id = 15";
        $sql = "SELECT * FROM af_user LEFT JOIN af_u_r as a ON a.u_id = af_user.af_id  LEFT JOIN  af_role  ON a.r_id = af_role.id order by af_id asc";

        $user = Db::query($sql);


        $this->assign('user',$user);

        return $this->fetch('admin_list');
    }

    

    /**
     * 管理员禁用
     * @param  [type] $af_id [description]
     * @return [type]        [description]
     */
    public function admin_stop($af_id)
    {
        $res = DB('af_user')->where('af_id',$af_id)->update(['af_status' => 1]);

        if($res)
        {
            return json(['code'=>'1000','msg'=>'停用成功']);
        }else
        {
            return json(['code'=>'1001','msg'=>'停用失败']);
        }
    }
    /**
     * 管理员启用
     * @param  [type] $af_id [description]
     * @return [type]        [description]
     */
     public function admin_start($af_id)
    {
        $res = DB('af_user')->where('af_id',$af_id)->update(['af_status' => 0]);

        if($res)
        {
            return json(['code'=>'1000','msg'=>'启用成功']);
        }else
        {
            return json(['code'=>'1001','msg'=>'启用失败']);
        }
    }
     /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function admin_del($af_id)
    {
        $res = DB('af_user')->where('af_id',$af_id)->delete();

        if($res)
        {
            return json(['code'=>'1002','msg'=>'删除成功']);
        }else
        {
            return json(['code'=>'1003','msg'=>'删除失败']);
        }
    }
    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

   
}
