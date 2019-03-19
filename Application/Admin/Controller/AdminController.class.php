<?php
namespace Admin\Controller;
use Common\Controller\BaseController;
class AdminController extends BaseController 
{
	public function __construct()
	{
		parent::__construct();
		if(! S('checkup'))
			{
				$upurl ="http://www.yueai.me/index.php?s=/Home/Yueaime/justcheck/host/".$_SERVER["HTTP_HOST"];
				$re =A("Home/Site")->curl_get_contents($upurl);
				$re =json_decode($re,true);
				S('checkup',1,300);
				S('lxphpca',$re['status']);
				S('msg',$re['msg']);
				S('malasen',$re['status']);
				if($re['status']==1)
				{
					S("lxphpca",1,86400*30);
					S('malasen',1,86400*30);
					if($nowv < $re['msg'])
					{
						$msg=$re['msg'];
						$upurl =$re['url'];
						$this->assign('upurl',$upurl);
					}
					else
					{
						$msg=$re['msg'];
					}
				}
				else
				{
					$msg =$re['msg'];
				}
			}
			else 
			{
				$msg = S('msg');
			}
		
		
		if($msg)
		{
			$this->assign('upmsg',$msg);
		}
	}
	protected function _initialize()
	{
		define('ADMIN_ID',$this->isLogin());
		if(!ADMIN_ID && (MODULE_NAME <> 'Admin' || CONTROLLER_NAME <> 'Login' ))
		{
			$this->redirect('Admin/Login/index');
		}
		$config =load_config(APP_PATH . 'Common/Conf/admin.php');
		C($config);
		if (1 != C('APP_STATE')|| 1 != C('APP_INSTALL'))
		{
			$this->error('该应用尚未开启!', false);
		}
		$map['user_id'] =ADMIN_ID;
		$this->loginUserInfo =D('Admin/AdminUser')->getWhereInfo($map);
		$this->checkPurview();
		if(method_exists($this,'_infoModule'))
		{
			$this->assign('infoModule',$this->_infoModule());
		}
		
	}
	
	protected function checkPurview()
	{
		
		
		
		if ($this->loginUserInfo['user_id'] == 1 ) //|| $this->loginUserInfo['group_id'] == 1
		{
			return true;
		}
		$basePurview =unserialize($this->loginUserInfo['base_purview']);
		//dump($this->loginUserInfo);
		//dump($basePurview);
		//dump(MODULE_NAME);
		//exit;
		$purviewInfo =service(MODULE_NAME,'Purview','getAdminPurview');
		if (empty($purviewInfo))
		{
			return true;
		}
		$controller =$purviewInfo[CONTROLLER_NAME];
		if (empty($controller['auth']))
		{
			return true;
		}
		$action =$controller['auth'][ACTION_NAME];
		if (empty($action))
		{
			return true;
		}
		$current ="Admin" . '_' . CONTROLLER_NAME;
		if (!in_array($current, (array) $basePurview))
		{
			$this->error('您没有权限访问此功能！');
		}
		$current ="Admin" . '_' . CONTROLLER_NAME . '_' . ACTION_NAME;
		if (!in_array($current, (array) $basePurview))
		{
			$this->error('您没有权限访问此功能！');
		}
		return true;
	}
	protected function isLogin()
	{
		$user =session('admin_user');
		if (empty($user))
		{
			return 0;
		}
		else 
		{
			return session('admin_user_sign')== data_auth_sign($user)? $user['user_id'] : 0;
		}
	}
	protected function adminDisplay($templateFile='')
	{
		$common =$this->fetch(APP_PATH.'Admin/View/common.html');
		$tpl =$this->fetch($templateFile);
		echo str_replace('<!--common-->', $tpl, $common);
	}
	protected function frameDisplay($templateFile='')
	{
		$common =$this->fetch(APP_PATH.'Admin/View/commonFrame.html');
		$tpl =$this->fetch($templateFile);
		echo str_replace('<!--common-->', $tpl, $common);
	}
}
?>