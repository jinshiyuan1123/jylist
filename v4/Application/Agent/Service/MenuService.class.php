<?php
namespace Agent\Service;
/**
 * 后台菜单接口
 */
class MenuService{
	/**
	 * 获取菜单结构
	 */
	public function getAgentMenu(){
		return array(
            'index' => array(
                'name' => '首页',
                'icon' => 'home',
                'order' => 0,
                'menu' => array(
                    array(
                        'name' => '专属推广',
                        'url' => U('Agent/Tuig/index'),
                        'order' => 0
                    )                		
                )
            ),'
			Tuig' => array(
                'name' => '推广系统',
                //'icon' => 'u-icon-list',
                'order' => 11,
                'menu' => array(
                    array(
						'name' => '专属推广',
						'url' => U('Agent/Tuig/index'),
						'order' => 0
					), 
                    array(
						'name' => '推广记录',
						'url' => U('Agent/Tuig/tglist'),
						'order' => 1
					),
					array(
						'name' => '申请提现',
						'url' => U('Agent/Tuig/tixian'),
						'order' => 3
					),                   
                )
            ),
        );
	}
	


}
