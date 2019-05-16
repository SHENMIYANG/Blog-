<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use think\Db;
use think\facade\Session;
use think\facade\Cookie;
class User extends Common
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        return $this->fetch();
    }

  
    /**
     * 显示角色表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function user_role()
    {       
        // $sql = "SELECT `af_role`,`af_desc`,`af_user`,`n_id`,`n_name` FROM af_u_r 
                // LEFT JOIN af_user ON af_u_r.u_id = af_user.af_id 
                // LEFT JOIN af_role ON af_u_r.r_id = af_role.id 
                // LEFT JOIN af_r_n ON  af_role.id = af_r_n.r_id
                // LEFT JOIN af_node ON  af_r_n.n_id = af_node.id";
        $sql = "SELECT  `id`,`af_role`,`af_user`,`af_role`,`af_desc` FROM af_user LEFT JOIN af_u_r as a ON a.u_id = af_user.af_id  LEFT JOIN  af_role  ON a.r_id = af_role.id  ORDER BY id ASC";

        $data = Db::query($sql);
        $arr =array();
        foreach ($data as $key => $value) {   
            $k = $value['af_role'];  
            if(isset($arr[$k])) 
            {
                $arr[$k]['af_user'] .=  ','.$value['af_user'];
            }
            else
            {
                $arr[$k]['id'] = $value['id'];
                $arr[$k]['af_role'] = $k;
                $arr[$k]['af_user'] = $value['af_user'];               
                $arr[$k]['af_desc'] = $value['af_desc'];
            }      
        }
        
        
        $this->assign('role',$arr);
        return $this->fetch('user_role');
    }
    /**
     * [角色]
     * @return [type] [description]
     */
    public function user_role_add(Request $request)
    {
        if(request()->isPost())
        {
            $post = request()->post();
            $role = [
                'af_role'=>$post['af_role'],
                'af_desc'=>$post['af_desc']
            ];
            $id = DB('af_role')->insertGetId($role);

            for ($i=0; $i < count($post['n_id']); $i++) { 

                $sql = "INSERT INTO af_r_n(`r_id`,`n_id`) VALUES('".$id."','".$post['n_id'][$i]."')";
                $res =  Db::execute($sql);
            }
            if($res)
            {
                $this->success('添加角色成功','/user_role');
            }else
            {
                $this->success('添加角色失败');
            }
            
        }else
        {
            $this->view->engine->layout(false);
            $node = DB('af_node')->select();

            $node = $this->getNode($node,0);
            $this->assign('node',$node);
            return $this->fetch('user_role_add');
        }
       
    }
    /**
     * 获取权限列表递归
     * @param  [type] $node [description]
     * @param  [type] $n_id [description]
     * @return [type]       [description]
     */
    public function getNode($node,$p_id)
    {
       $parent=[];
        foreach ($node as $key => $value) {
            if($value['p_id']==$p_id)
            {               
                $parent[]=$value;
                unset($node[$key]); 
            }           
        }       
        foreach ($parent as $k => $v) 
        {            
            $r=$this->getNode($node,$v['id']);   
         
            $parent[$k]['two']=$r;                                          
        }
        return $parent;       
    }
   /**
    * [查看添加权限]
    * @param  Request $request [description]
    * @return [type]           [description]
    */
    public function user_node(Request $request)
    {
        $node = DB('af_node')->select();
        
        $this->assign('node',$node);

        return  $this->fetch('user_node');
    }
    /**
     * [权限添加]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function user_node_add(Request $request)
    {
        if(request()->isPost())
        {
            $post = request()->post();
            
            $res = DB('af_node')->insert($post);

            if($res)
            {
                $this->success('添加权限成功','/user_node');
            }else
            {
                $this->error('权限添加失败');
            }
        }else
        {
            $sql = "SELECT `id`,`n_name` FROM af_node WHERE p_id = 0 ";
            $node = Db::query($sql);
            $this->assign('node',$node);
            $this->view->engine->layout(false);
            return  $this->fetch('user_node_add');
        }
        
    }
    /**
     * [分配权限]
     * @param  request $request [description]
     * @return [type]           [description]
     */
    public function auth_node(request $request)
    {
        if(request()->isPost())
        {
            $data = request()->post();
    
            DB('af_r_n')->where('r_id',$data['r_id'])->delete();

            for ($i=0; $i < count($data['n_id']); $i++) { 

                $sql = "INSERT INTO af_r_n(`r_id`,`n_id`) VALUES('".$data['r_id']."','".$data['n_id'][$i]."')";

                $res =  Db::execute($sql);
            }
            if($res)
            {
                $this->success('分配权限成功','/auth_node');
            }else
            {
                $this->success('权限分配失败');
            }           
        }else
        {
            $role = DB('af_role')->select();
            
            $node = DB('af_node')->select();

            $node = $this->getNode($node,0);
            //角色
            $this->assign('role',$role);
            //权限
            $this->assign('node',$node);
            
            return $this->fetch('auth_node');
        }
       
    }
    /**
     * [获取每个角色的权限]
     * @param  request $request [description]
     * @return [type]           [description]
     */
    public function auth_node_get(request $request)
    {
       
            $data = request()->post();

            $node = DB('af_node')->select();
        
            $sql = "SELECT `id` FROM af_node LEFT JOIN af_r_n as a ON a.n_id = af_node.id WHERE a.r_id = '".$data['r_id']."'";

            $r_node = Db::query($sql);
          
            return json(['r_node'=>$r_node]);       
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
