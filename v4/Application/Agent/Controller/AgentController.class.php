<?php
namespace Agent\Controller;
use Common\Controller\BaseController;

class AgentController extends BaseController 
{
	public function __construct()
	{
		parent::__construct();
		$msg = '';
		if($msg)
		{
			$this->assign('upmsg',$msg);
		}
	}

	public function index(){
		
	}

	protected function _initialize()
	{
		define('AGENT_ID',$this->isLogin());
		if(!AGENT_ID && (MODULE_NAME != 'Agent' || CONTROLLER_NAME != 'Login'))
		{
			echo "dd";
			$this->redirect('Agent/Login/index');
		}
		$config =load_config(APP_PATH . 'Common/Conf/agent.php');
		C($config);
		if (1 != C('APP_STATE'))
		{
			$this->error('该应用尚未开启!', false);
		}
		$map['id'] =AGENT_ID;
		$this->loginUserInfo =D('Agent/AgentUser')->getWhereInfo($map);
		if(method_exists($this,'_infoModule'))
		{
			$this->assign('infoModule',$this->_infoModule());
		}
		
	}

	protected function isLogin()
	{
		$user =session('agent_user');
		if (empty($user))
		{
			return 0;
		}
		else 
		{
			return session('agent_user_sign') == data_auth_sign($user) ? $user['id'] : 0;
		}
	}

	protected function agentDisplay($templateFile='')
	{
		$common =$this->fetch(APP_PATH.'Agent/View/common.html');
		$tpl =$this->fetch($templateFile);
		echo str_replace('<!--common-->', $tpl, $common);
	}

	protected function frameDisplay($templateFile='')
	{
		$common =$this->fetch(APP_PATH.'Agent/View/commonFrame.html');
		$tpl =$this->fetch($templateFile);
		echo str_replace('<!--common-->', $tpl, $common);
	}
}
?>