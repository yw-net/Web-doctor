<?php

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;
use app\controller\Base;
use app\controller\Api;
use app\model\User;
use think\Db;

class ClearPhoto extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('clearphoto')->setDescription('clear the photo in service');;
        // 设置参数
        
    }

    protected function execute(Input $input, Output $output)
    {
    	// 指令输出
    	$output->writeln('正在查询用户数据库...');

    	//指定目录绝对地址
    	$path = realpath(dirname(__FILE__).'/../../public/static/upload');

    	//查找指定目录下所有照片文件
        $arr=glob($path.'/userhead/*/*.{jpg,png,gif,jpeg,JPG,PNG,GIF,JPEG}',GLOB_BRACE);

        //服务器数据库目录绝对地址
        $dataPath = realpath(dirname(__FILE__).'/../../public/');
        //计算地址长度供查询数据库是截取使用
        $dataPath = strlen($dataPath);
        echo ($dataPath.PHP_EOL);


        if(count($arr)>0){

            $j=count($arr);
            $ndel=0;

            for($i=0;$i<$j;$i++){
                //截取图片在服务器中的绝对地址，转换为数据库中的地址
                $dataArr = '..'.substr($arr[$i],$dataPath);
                //查询数据库中是否存在此图片
                $sql = Db::table('user')->where('face_img','=',$dataArr)->find();
                //不存在则删除
                if(!$sql){
                    echo ("$arr[$i]此照片不存在于数据库中，正在删除中......".PHP_EOL);
                    unlink ($arr[$i]);
                    echo ("删除成功!!!".PHP_EOL);
                    $ndel++;
                }
            }

            $n=count($arr)-$ndel;

            if($ndel>0){
                echo "已删除".$ndel."张冗余照片，";
            }
            echo ("当前相册中共有 ".$n."张照片！".PHP_EOL);

        }

        if(count($arr)==0){
            echo ("对不起，此目录下没有照片！".PHP_EOL);
        }


    }
}
