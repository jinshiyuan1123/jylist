<?php
namespace Admin\Service;
/**
 * 后台菜单接口
 */
class MenuService{
	/**
	 * 获取菜单结构
	 */
	public function getAdminMenu(){
		return array(
            'index' => array(
                'name' => '首页',
                'icon' => 'home',
                'order' => 0,
                'menu' => array(
                    array(
                        'name' => '系统升级',
                        'url' => U('Admin/Yueaiyuan/index'),
                        'order' => 0
                    ),
                		array(
                				'name' => '系统统计',
                				'url' => U('Admin/Index/systemTj'),
                				'order' => 1
                		)
                		
                )
            ),
			
			'User' => array(
                'name' => '用户管理',
                //'icon' => 'u-icon-list',
                'order' => 6,
                'menu' => array(
                    array(
                        'name' => '前台用户管理',
                        'url' => U('Admin/User/index'),
                        'order' => 0
                    ), 
                    array(
                        'name' => '审核昵称',
                        'url' => U('Admin/Audit/AuditNickname'),
                        'order' => 1
                    ), 
                    array(
                        'name' => '审核头像',
                        'url' => U('Admin/Audit/AuditAvatar'),
                        'order' => 2
                    ), 
                    array(
                        'name' => '审核内心独白',
                        'url' => U('Admin/Audit/AuditMonolog'),
                        'order' => 3
                    ),
                    array(
                        'name' => '相册列表/审核',
                        'url' => U('Admin/User/userPhotoFlag'),
                        'order' => 4
                    )
                )
            ),
			'Account' => array(
						'name' => '账务管理',
						//'icon' => 'u-icon-list',
						'order' => 7,
						'menu' => array(
								array(
									'name' => '魅力值/财富值管理',
									'url' => U('Admin/Account/account'),
									'order' => 0
								),
								array(
									'name' => '提现/现金发放管理',
									'url' => U('Admin/Account/money'),
									'order' => 1
								),
								array(
									'name' => '金钱记录管理',
									'url' => U('Admin/Account/jinqianlog',array('type'=>1)),
									'order' => 2
								),								
								array(
									'name' => '充值记录管理',
									'url' => U('Admin/Account/chongzhi'),
									'order' => 3
								),
								/*array(
										'name' => '收银管理',
										'url' => U('Admin/PayLog/getmoney'),
										'order' => 2
								)*/
						)
				),
				'Msg' => array(
                'name' => '信件/消息',
                //'icon' => 'u-icon-list',
                'order' => 8,
                'menu' => array(
					array(
                        'name' => '私信提示设置',
                        'url' => U('Admin/Compose/setting'),
                        'order' => 2
                    ),
                    array(
                        'name' => '会员私信',
                        'url' => U('Admin/Compose/index',array('ishello'=>-1)),
                        'order' => 3
                    ),
					array(
                        'name' => '系统消息',
                        'url' => U('Admin/SystemMsg/index'),
                        'order' => 4
                    ),
					array(
                        'name' => '虚拟礼物记录',
                        'url' => U('Admin/Giftlist/index'),
                        'order' => 5
                    ),
					array(
                        'name' => '关注消息',
                        'url' => U('Admin/Attention/index'),
                        'order' => 6
                    ),
					array(
                        'name' => '评论管理',
                        'url' => U('Admin/CommentManage/index'),
                        'order' => 7
                    )
                )
            ),		
            'system' => array(
                'name' => '系统',
                'icon' => 'bars',
                'order' => 9,
                'menu' => array(
                    array(
                        'name' => '站点设置',
                        'url' => U('Admin/Setting/site'),
                        'order' => 0,
                        'divider' => true,
                    ),
                      array(
                        'name' => '模板设置',
                        'url' => U('Admin/Setting/tpl'),
                        'icon' => 'eye',
						 'order' => 10,
                    ),
                    array(
                        'name' => '性能设置',
                        'url' => U('Admin/Setting/performance'),
                        'icon' => 'dashboard',
						'order' => 10,
                    ),
                    array(
                        'name' => '安全设置',
                        'url' => U('Admin/Setting/shield'),
                        'icon' => 'shield',
						'order' => 10,
                    ),           		
                    array(
                        'name' => '缓存管理',
                        'url' => U('Admin/Manage/cache'),
                        'order' => 3,
                        'divider' => true,
                    ),
                    array(
                        'name' => '备份还原',
                        'url' => U('Admin/AdminBackup/index'),
                        'order' => 6
                    ),
                    array(
                        'name' => '上传设置',
                        'url' => U('Admin/Setting/upload'),
                        'icon' => 'upload',
						 'order' => 6,
                    ),
                    array(
                        'name' => '用户管理',
                        'url' => U('Admin/AdminUser/index'),
                        'order' => 7,
                        'divider' => true,
                    ),
                    array(
                        'name' => '用户组管理',
                        'url' => U('Admin/AdminUserGroup/index'),
                        'order' => 8,
                    ),
                    /*
					array(
                        'name' => '城市管理',
                        'url' => U('Admin/Area/index'),
                        'order' => 20,
                        'divider' => true,
                    ),
                    */
                )
            )
            ,'Settings' => array(
                'name' => '运营设置',
                'icon' => 'bars',
                'order' => 10,
                'menu' => array(
                    array(
                        'name' => '公众号设置',
                        'url' => U('Admin/Setting/gzh'),
                        'order' => 0,
                        'divider' => true,
                    ),
                    array(
                        'name' => '运营设置',
                        'url' => U('Admin/Setting/yuny'),
                        'order' => 1,
                        'divider' => true,
                    ),
					array(
                        'name' => '特权提示设置',
                        'url' => U('Admin/Setting/tequan'),
                        'order' => 2,
                        'divider' => true,
                    ),
                     array(
                        'name' => '计费设置',
                        'url' => U('Admin/Setting/msetting'),
						'order' => 3,
                        'icon' => 'group',
                    ),
                     array(
                        'name' => '支付设置',
                        'url' => U('Admin/Setting/zhifu'),
						'order' => 4,
                        'icon' => 'group',
                    ),
                	array(
                		'name' => '充值设置',
                		'url' => U('Admin/Setting/credit'),
                		'order' => 5,
                		
                	),
                	array(
                	     'name' => '购买VIP设置',
                		 'url' => U('Admin/Setting/credit',array('type'=>1)),
                		 'order' =>6,
                		), 
                	array(
						'name' => '阿里云OSS设置',
						'url' => U('Admin/Setting/OSS'),
						'order' => 7,
                		),
                	array(
						'name' => '提现设置',
						'url' => U('Admin/Setting/diySetting',array('Set'=>'tixian')),
						'order' => 8,
                		),
					array(
						'name' => '马甲设置',
						'url' => U('Admin/Setting/mj'),
						'order' => 9,
                		),
                   
                )
            ),
            'Log' => array(
                'name' => '信息记录',
                //'icon' => 'u-icon-list',
                'order' => 11,
                'menu' => array(
                    array(
						'name' => '签到记录管理',
						'url' => U('Admin/Qiandao/index'),
						'order' => 0
					), 
                    array(
						'name' => '亲密度记录管理',
						'url' => U('Admin/Intimacy/index'),
						'order' => 1
					), 
                    array(
                        'name' => '安全记录',
                        'url' => U('Admin/AdminLog/index'),
                        'order' => 2
                    ), 
                    array(
                        'name' => '定时记录',
                        'url' => U('Admin/Autotask/index'),
                        'order' => 3
                    )
                )
            ),
			'Tuig' => array(
                'name' => '系统联盟',
                //'icon' => 'u-icon-list',
                'order' => 11,
                'menu' => array(
					array(
						'name' => '提成设置',
						'url' => U('/Admin/Tuig/tichensetting'),
						'order' => 1
					),
					array(
						'name' => '提现管理',
						'url' => U('/Admin/Account/money'),
						'order' => 2
					),
                    array(
						'name' => '审核代理',
						'url' => U('Admin/Tuig/index'),
						'order' => 5
					), 
					array(
						'name' => '查看代理',
						'url' => U('Admin/Tuig/ulist'),
						'order' => 6
					),                   
                )
            ),

        );
	}
	


}
