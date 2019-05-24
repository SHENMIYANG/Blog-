<?php 
namespace app\afront\controller;

class Upload 
{
    /**
     * @var 文件信息think\file这个类
     */
    private $file;
    /**
     * @var 上传图片的目录
     */
    private $path;
    /**
     * 上传文件规则
     */
    private $validate =[
        'size' => 500000,
        'ext'  => 'jpg,png,gif,jpeg',
    ];
 
    /**
     * 文件上传
     *
     * @param file think\File
     * @path  上传的目录  upload\goods
     * @return array
     */
    public function move($file,$path)
    {
 
        $this->file = $file;
  
        // 获取上传的文件名
        $fileName = $this->getFileName($file);
        
        // 判断保存的目录是否存在
        if(!file_exists($path)){
            mkdir($path,777,true);
        }
        // 文件保存后的名字加类型
        $image = $fileName['saveName'].'.'.$fileName['fileSuffix'];
    
     
        // 开始上传  参数一：上传路径       参数二：文件名
        $info = $file->validate($this->validate)->move("$path");
        // 获取上传后的文件名
        if($info)
        {
            return  $info->getSaveName();        
        }else
        {
            return  $file->getError();
        }
                   
        $this->path = $path.'.'.$image;      
    }
 

 
    /**
     * 获取上传文件的信息  名字，类型，类型
     *
     * @return array
     */
    public function getFileName($file)
    {
        // 获取文件信息
        $name = $this->file->getInfo('name');
        // 问件名1.jpg   所以需要转数组获取
        $fileName = explode('.',$name);
        return [
            // 文件名
            'formerlyName' => $fileName[0],
            // 保存后的文件名
            'saveName' => $fileName[0].time(),
            // 文件后缀
            'fileSuffix' => $fileName[1]
        ];
    }
 
   
}
