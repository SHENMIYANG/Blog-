<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use think\facade\Session;
use think\facade\Cookie;
use think\Db;
use app\index\controller\Upload;
class Advert extends Common
{
	public function index()
	{
		$advert = DB('af_advert')->select();

		$this->assign('advert',$advert);

		return $this->fetch('advertlist');
	}
	public function advertadd(request $request)
	{
		if(request()->isPost())
		{
			$upload = new Upload;
			$post = request()->post();

			$ad_imgs = request()->file('ad_imgs');
			$post['ad_uid'] = Session::get('user');
			$path = "../public/uploads";
			$info = $upload->move($ad_imgs,$path);
			$post['ad_imgs'] = $info;
			$post['ad_timestart'] = strtotime($post['ad_timestart']);
			$post['ad_timestop'] = strtotime($post['ad_timestop']);
			$post['ad_status'] = 1;
			$res = DB('af_advert')->insert($post);
			if($res)
			{
				return $this->success('广告添加成功','/advertlist');
			}else
			{
				return $this->error();
			}
	
		}else
		{

			$this->view->engine->layout(false);
			return $this->fetch('advertadd');
		}
	}
	
	public function advertupd()
	{

	}
	public function advertdel($id)
	{
		$res = DB('af_advert')->where('id',$id)->delete();
		if($res)
		{
			return json(['code'=>1001,'msg'=>'删除成功']);
		}else
		{
			return json(['code'=>1002,'msg'=>'删除失败']);
		}
	}
	public function advertstop($id)
    {
        $res = DB('af_advert')->where('id',$id)->update(['ad_status' => 0]);

        if($res)
        {
            return json(['code'=>'1000','msg'=>'下架成功']);
        }else
        {
            return json(['code'=>'1001','msg'=>'下架失败']);
        }
    }
    public function advertstart($id)
    {
        $res = DB('af_advert')->where('id',$id)->update(['ad_status' => 1]);

        if($res)
        {
            return json(['code'=>'1000','msg'=>'发布成功']);
        }else
        {
            return json(['code'=>'1001','msg'=>'发布失败']);
        }
    }
}