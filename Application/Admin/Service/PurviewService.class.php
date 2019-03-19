<?php
namespace Admin\Service;
/**
 * 权限接口
 */
class PurviewService{
	/**
	 * 获取模块权限
	 */
	public function getAdminPurview(){
		return array(
            'Setting' => array(
                'name' => '系统设置',
                'auth' => array(
                    'site' => '站点设置',
                    'admin' => '后台设置',
                    'performance' => '性能设置',
                    'upload' => '上传设置',
                    'shield' => '安全设置',
                    'tpl' => '模板设置',
                    'gzh' => '公众号设置',
                    'yuny' => '运营设置',
                    'msetting' => '计费设置',
                    'zhifu' => '支付设置',
                    'credit' => '充值/VIP设置',
                    'OSS' => 'OSS',
                    'mj' => '马甲设置',
                    'diySetting' => '提现设置',
                )
            ),
           'Manage' => array(
                'name' => '系统管理',
                'auth' => array(
                    'cache' => '缓存管理'
                )
            ),
			'AdminLog' => array(
                'name' => '系统log管理',
                'auth' => array(
                    'index' => '安全记录日志'
                )
            ),
			'AdminBackup' => array(
                'name' => '系统操作',
                'auth' => array(
                    'index' => '备份还原'
                )
            ),
			'User' => array(
                'name' => '前台用户',
                'auth' => array(
                    'index' => '列表管理',
                    'userPhoto' => '相册列表',
                    'userPhotoFlag' => '相册审核',
                    'edit' => '管理用户',
                    'userProfile' => '详细资料',
                    'batchAction' => '批量操作',
                    'tuijian' => '推荐用户',
                    'sendaccount' => '变动用户余额',
                    'del' => '删除用户',
                    'userVip' => '升级VIP',
                    'userWx' => '操作微信红包',
                    'userQmd' => '亲密度编辑',
                    'usercount' => '用户统计',
                )
            ),
			'Compose' => array(
                'name' => '会员私信',
                'auth' => array(
                    'index' => '列表管理',                 
                )
            ),
			'SystemMsg' => array(
                'name' => '系统消息',
                'auth' => array(
                    'index' => '列表管理',                 
                )
            ),
			'Giftlist' => array(
                'name' => '信件->礼物赠送记录',
                'auth' => array(
                    'index' => '列表管理',                 
                )
            ),
			'Attention' => array(
                'name' => '信件->关注消息',
                'auth' => array(
                    'index' => '列表管理',                 
                )
            ),'Account' => array(
                'name' => '财务管理',
                'auth' => array(
                    'account' => '财富值/魅力值',                 
                    'money' => '提现管理',                 
                    'jinqianlog' => '金钱变动记录',                 
                    'chongzhi' => '充值记录',                 
                )
            ),
			'CommentManage' => array(
                'name' => '评论管理',
                'auth' => array(
                    'index' => '列表管理',                 
                    'del' => '删除',                 
                    'single' => '通过/不通过',                 
                    'batchAction' => '批量操作',                 
                )
            ),
			'Audit' => array(
                'name' => '审核管理',
                'auth' => array(
                    'AuditNickname' => '昵称',
                    'AuditAvatar' => '头像',
                    'AuditMonolog' => '内心独白',
                )
            ),
			'AdminUser' => array(
                'name' => '后台用户管理',
                'auth' => array(
                    'add' => '添加用户',
                    'index' => '用户列表',
                    'edit' => '修改用户',
                    'del' => '删除用户',
                )
            ),
				
			'AdminUserGroup' => array(
                'name' => '系统用户组管理',
                'auth' => array(
                    'add' => '添加用户组',
                    'edit' => '修改',
					 'del' => '删除',
                    'purview' => '权限管理',
                   
                )
            ),
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
