<?php
namespace Agent\Controller;
use Agent\Controller\AgentController;

/**
 * 后台首页
 */
class IndexController extends AgentController {

    /**
     * 当前模块参数
     */
	protected function _infoModule(){
		return array(
            'menu' => array(
					array(
						'name' => '专属推广',
						'url' => U('Agent/Tuig/index'),
						'icon' => 'list',
					),
					array(
						'name' => '推广记录',
						'url' => U('Agent/Tuig/tglist'),
						'icon' => 'list',			
					),    
					array(
						'name' => '申请提现',
						'url' => U('Agent/Tuig/tixian'),
						'icon' => 'list',			
					), 
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
    	$breadCrumb = array('首页'=>U('Agent/Tuig/index'));
	
        $this->assign('breadCrumb',$breadCrumb);
        $this->agentDisplay();
    }
}