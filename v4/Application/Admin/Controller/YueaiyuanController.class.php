<?php
namespace Admin\Controller;
use Admin\Controller\AdminController;
/**
 * 后台首页
 */
class YueaiyuanController extends AdminController {

		public function __construct() {
		parent::__construct ();

		$this->upurl = "http://www.yueai.me/index.php?s=/Home/Yueaime";
	}


    /**
     * 当前模块参数
     */
	protected function _infoModule(){
		return array(
            'menu' => array(
            		array(
	                    'name' => '管理首页',
	                    'url' => U('Index/index'),
	                    'icon' => 'dashboard',
                    )
                ),
            'info' => array(
                    'name' => '管理首页',
                    'description' => '站点运行信息',
            		'icon' => 'home',
                )
            );
	}
	/**
     * 首页
     */
    public function index(){
		//设置目录导航
		$breadCrumb = array('首页'=>U('Index/index'));

		$this->assign('breadCrumb',$breadCrumb);
		$this->assign('enduptime',C('enduptime'));
		$this->assign('curv',C('vstion'));

		$this->adminDisplay();
    }

   
  public  function rmdirs($dir){
        
        $dir_arr = scandir($dir);
        foreach($dir_arr as $key=>$val){
            if($val == '.' || $val == '..'){}
            else {
                if(is_dir($dir.'/'.$val))    
                {                            
                    if(@rmdir($dir.'/'.$val) == 'true'){}            
                    else
                     $this->rmdirs($dir.'/'.$val);                    
                }
                else                
                unlink($dir.'/'.$val);
            }
        }
    }   

	public function checkup(){
		$url = $this->upurl."/docheck/host/".$_SERVER["HTTP_HOST"];
		$re = A("Home/Site")->curl_get_contents($url);

		//$re = "{\"status\":0,\"msg\":\"目前已经是最新版本，无需升级\"}";
		echo $re;
	}


	public function doup(){
		$sqlupid = C("sqlupid");
		$v = C("vstion");
		if(empty($sqlupid)){
			$sqlupid = '0';
		}

		$url = $this->upurl."/doup/host/".$_SERVER["HTTP_HOST"]."/v/".$v."/sqlid/".$sqlupid;
		$re = A("Home/Site")->curl_get_contents($url);
		$res = json_decode($re , true);
		if(!empty($res['newver'])){
			session("newSqlId" , $res['newver']['id']);
			session("newVerNum" , $res['newver']['version_num']);
		}

		echo $re;exit;
	}

	public function doact(){
		$zipFileName = dirname(ROOT_PATH) . "/yueaiUpgrade/version/newVer.zip";
		$zipDirName = dirname($zipFileName);
		file_exists($zipDirName) || mkdir($zipDirName , 0755 , true);
		$act = I("post.act",'','trim');
		$sqlupid = C("sqlupid");
		$v = C("vstion");
		if(empty($sqlupid)){
			$sqlupid = '0';
		}
		$url = $this->upurl."/".$act."/host/".$_SERVER["HTTP_HOST"]."/v/".$v."/sqlid/".$sqlupid;

		switch($act){
			case "filedownload":
				unlink($zipFileName);
				$result = array("status"=>1 , "msg"=>"已经成功下载升级包,正在解压文件……" , "act"=>"upzip");
				$file = fopen($url, 'rb');
				if ($file) {
					$sizeBinary = 1024 * 8;
					$newf = fopen($zipFileName, 'wb');
					if ($newf) {
						while (! feof($file)) {
							$binary = fread($file, $sizeBinary);
							fwrite($newf, $binary , $sizeBinary);
						}

						fclose($newf);
					}else{
						$result['status'] = '0';
						$result['msg'] = '升级包无法保存至本地硬盘，请检查本地目录是否有写入权限。' ;
					}

					fclose($file);
				} else {                   
					$result['status'] = '0';
					$result['msg'] = '升级包无法下载，可能是官方已删除该升级包，请联系紫竹官方人员。' ;
				}
				if(stristr($binary , "status")){
					echo $binary;
					exit;
				}

				$this->ajaxReturn($result);
				break;
			case "upzip":
				$result = $this->unpackFiles($zipFileName);                
				$this->ajaxReturn($result);
				break;
			case "doexe":
				$sqlExeFile = ROOT_PATH . "Application/Common/Util/Upgradsql.class.php";
				if(file_exists($sqlExeFile)){
					include($sqlExeFile);
					unlink($sqlExeFile);
				}

				M("Config")->where("name='sqlupid'")->setField("data",session("newSqlId"));
				M("Config")->where("name='vstion'")->setField("data",session("newVerNum"));
				M("Config")->where("name='enduptime'")->setField("data",time());
				$result = array("status"=>1 , "msg"=>"已经成功完成本次升级!","act"=>"showinfo");
				$this->ajaxReturn($result);
				break;
			case "doup":
				$re = A("Home/Site")->curl_get_contents($url);
				$res = json_decode($re , true);
				if(!empty($res['newver'])){
					session("newSqlId" , $res['newver']['id']);
					session("newVerNum" , $res['newver']['version_num']);
				}
				echo $re;
				break;
			case "showinfo":
				$url = $this->upurl."/".$act."/host/".$_SERVER["HTTP_HOST"]."/newSqlId/" . session("newSqlId");
				$re = A("Home/Site")->curl_get_contents($url);
				echo $re;
				break;
			default:
				$re = A("Home/Site")->curl_get_contents($url);
				echo $re;
		}
	}

	private function unpackFiles($zipFileName){
		if(!file_exists($zipFileName)){
			return array("status"=>0 , "msg"=>"下载到本地的升级包丢失了……");
		}
		$upgradSqlFile = ROOT_PATH . "Application/Common/Util/Upgradsql.class.php";
		if(file_exists($upgradSqlFile)){
			$rs = unlink($upgradSqlFile);
			if(! $rs){
				return array("status"=>0 , "msg"=>"从硬盘删除upgradSqlFile文件失败……");
			}
		}

		$rs = $this->doUnzip($zipFileName);
		if($rs !== true){
			return $rs;
		}
		unlink($zipFileName);
		

		return array("status"=>1 , "msg"=>"已解压完成,正在执行数据更新……" , "act" => "doexe");
	}

	private function doUnzip($zipFileName){
		include(ROOT_PATH."Public/zipFactory/ZipFactory.php");
		$zip = \ZipFactory::open($zipFileName);
		$rs = $zip->extractTo(ROOT_PATH);
		if($rs === true || (is_array($rs) && !empty($rs[0]['filename']))){
			return true;
		}

		return array("status"=>0 , "msg"=>"向硬盘解压升级包失败……");
	}

	private function deleteDirectory($dir){
		if (!file_exists($dir))
			return true;
		if (!is_dir($dir) || is_link($dir))
			return unlink($dir);

		$dirList = array_diff(scandir($dir), array('.','..'));
		foreach($dirList as $item) {
			$this->deleteDirectory($dir . $item);
		}

        return rmdir($dir);
    }
}

