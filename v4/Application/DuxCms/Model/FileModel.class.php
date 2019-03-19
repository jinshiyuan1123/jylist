<?php
namespace DuxCms\Model;
use Think\Model;
/**
 * 文件操作
 */
class FileModel extends Model {
    //完成
    protected $_auto = array (
        array('time','time',3,'function'),
     );

    /**
     * 上传数据
     * @param array $files 上传$_FILES信息
     * @param array $config 上传配置信息可选
     * @return array 文件信息
     */
    public function uploadData($files, $config = array())
    {
    	//echo time().'----';
        //上传
        $upload = new \Think\Upload($config,'Local');
        $info = $upload->upload($files);
        $info = current($info);
        if($info){
            // 记录文件信息
            $fileDir = 'Uploads/'.$info['savepath'];
            $file = $fileDir.$info['savename'];
            $info['title'] = $info['name'];
            $info['original'] = __ROOT__ .'/'.$file;
            $original = $file;
            $file = $this->cut_photo($file);
            //处理图片数据
            $imgType = array('jpg','jpeg','png','gif','bmp');
            if(in_array($info['ext'], $imgType)){
            	
                //设置图片驱动
                $image = new \Think\Image();
                //设置缩图
                if(C('THUMB_STATUS') && $_GET['slt']!=1){
                    $image->open(ROOT_PATH . $file);
                    $thumbFile = $fileDir.'thumb_'.$info['savename'];
                    $status = $image->thumb(C('THUMB_WIDTH'), C('THUMB_HEIGHT'), C('THUMB_TYPE'))->save(ROOT_PATH . $thumbFile);
                    if($status){
                        $file = $thumbFile;
                    }
                }
                 if($_GET['slt']==1 && C('list_thumb_status')){
					$image->open(ROOT_PATH . $file);
                    $thumbFile = $fileDir.'thumb_'.$info['savename'];
                    $status = $image->thumb(C('list_thumb_width'), C('list_thumb_height'), C('THUMB_TYPE'))->save(ROOT_PATH . $thumbFile);
                    if($status){
                        $file = $thumbFile;
                    }
					
				}
                //设置水印
                if(C('WATER_STATUS')){
                    $image->open(ROOT_PATH . $file);
                    $wateFile = $fileDir.'wate_'.$info['savename'];
                    $status = $image->water(ROOT_PATH . 'Public/watermark/'.C('WATER_IMAGE'),C('WATER_POSITION'))->save(ROOT_PATH . $wateFile);
                    if($status){
                        $file = $wateFile;
                    }
                }
            }
            $info['url'] = __ROOT__ .'/'.$file;
            $url = $file;
            //入库文件信息
            $this->create($info);
            $this->add();

            $info['original'] = $this-> oos_upimg($info['original'],$original);
           $info['url'] = $original == $url ? $info['original']: $this-> oos_upimg($info['url'],$url);

            return $info;
        } else {
            $this->error = $upload->getError();
            return false;
        }
    }
    
    public function cut_photo($file){
    	
    	$image = new \Think\Image();
    	$image->open(ROOT_PATH . $file);
    	$width = $image->width();
    	$height =$image-> height();
    	if($width>640){
    		$status = $image->thumb(640, $height, 1)->save(ROOT_PATH . $file);
    	}
    	
    	return $file;
    }
    
    
    
    //上传图片到阿里云OOS
    public function oos_upimg($url,$ourl){
    
    	if(C('open_oss')>0&&C('OSS_ACCESS_ID')&&C('OSS_ACCESS_KEY')&&C('OSS_ENDPOINT') &&C('OSS_TEST_BUCKET') && C('OSS_URL')) {
    		$ourl = ROOT_PATH.'/'.$ourl;
    		$url ='http://'. $_SERVER['HTTP_HOST'] .$url;
    		require_once ROOT_PATH."OOS_SDK/samples/Common.php";
    		$imgdata =  getimagesize($url);
    		if(!$imgdata['mime']) return false;
    		$type =  str_replace('image/', '', $imgdata['mime']);
    		$tools = new \Common();
    		$root = date('Y-m-d',time());
    		$root2 = date('H',time());
    		$rand= rand(1000,9999);
    		$name = md5(date('YmdHis',time()).$rand);
    		$filename ='dating/'.$root.'/'.$root2.'/'.$name.'.'.$type;
    		$bucket = $tools::getBucketName();
    		$ossClient = $tools::getOssClient();
    		if (is_null($ossClient)) return false;
    		//*******************************简单使用***************************************************************
    		$tempu=parse_url($url);
    		$message=$tempu['host'];

    		$content = $this->curlg($url,"http://".$message);
    		
    		$ossClient->putObject($bucket, $filename, $content);
    		$doesExist = $ossClient->doesObjectExist($bucket, $filename);
    		if($doesExist){
    			if(file_exists($ourl)){
    				$isdel　= @unlink ($ourl);
    			}
    	
    			return $tools::bucketURL.$filename;
    		}else{
    			
    			return  false;
    		}
    		
    	}else{
    		return  $url;
    	}
    	
    
    }
    
    public function curlg($url,$fromurl=NULL,$fromip=NULL,$uagent=NULL,$timeout=1,$host=NULL){//php 模拟get
    	ob_start();
    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //ssl证书不检验
    	if($fromip) curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:'.$fromip, 'CLIENT-IP:'.$fromip));  //构造IP
    	if($fromurl) curl_setopt($ch, CURLOPT_REFERER,$fromurl);   //构造来路
    	//curl_setopt($ch, CURLOPT_ENCODING ,gzip);
    	curl_setopt($ch, CURLOPT_URL, $url);
    	curl_setopt($ch, CURLOPT_USERAGENT,"IE 6.0");
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,$timeout);
    	$file_msg = curl_exec($ch);
    	curl_close($ch);
    	//dump($ch);
    	if($file_msg===false) return file_get_contents($url);
    	return $file_msg;
    }
    
    

}
