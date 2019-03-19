<?php
namespace Agent\Service;
/**
 * 权限接口
 */
class PurviewService{
	/**
	 * 获取模块权限
	 */
	public function getAgentPurview(){
		return array(
             'Tuig' => array(
                'name' => '推广系统',
                'auth' => array(
                    'index' => '专属推广',
                    'tglist' => '推广记录',
                )
            )
        );
	}
	


}
