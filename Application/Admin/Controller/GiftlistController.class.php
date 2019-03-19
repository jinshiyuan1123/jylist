<?php
namespace Admin\Controller;
use Admin\Controller\AdminController;
/**
 * 关注消息
 */
 class GiftlistController extends AdminController{
  		
  	public function _infoModule(){
 		$data = array(
			'info' => array(
				'name' => '虚拟礼物记录管理',
				'description' => '管理用户的虚拟礼物赠送记录',
			),
			'menu' => array(
				array(
					'name' => '赠送列表',
	                'url' => U('Admin/Giftlist/index'),
	                'icon' => 'list',
				),		
			),
		);
		return $data; 		
  	}
	
	public function index(){
			$breadCrumb = array('系统消息列表' => U());//面包屑分类
			$keyword = I('request.keyword','');//搜索的关键字字段
			$msg_type = I('request.msg_type','','trim');//筛选的字段
			$order_by = I('request.order_by','asc','trim');//排序
			$model = D('Giftlist');
			$pageMaps = array();
			$pageMaps['keyword'] = $keyword;
			$pageMaps['touser_isread'] = $touser_isread;
			$pageMaps['order_by'] = $order_by;
			$where = array();
			if(!empty($keyword)){
				$where['_string'] = 'user_id='.$keyword;
			}
			
			if(!empty($touser_isread)){
				switch($touser_isread){
					case '0':
						$where['touser_isread'] = 0;
						break;
					case '1':
						$where['touser_isread'] = 1;
						break;
				}
			}
			
			$count = $model -> countList($where);//满足条件的总条数
			$limit = $this -> getPageLimit($count,20);//获取每页要显示的条数
			$order = $order? $order: 'giftlist_id desc'; //获取排序规则  虽然好像并没有$order
                        
			$list = $model -> loadList($where,$limit,$order);
                  
                        
                        $ids = array();	
		foreach($list as $k => $v){			
			$ids[] = $v['fromuid'];
			$ids[] = $v['touid'];
		}
		$ids = array_unique($ids); //去重
		$ids = join($ids, ','); //变成普通数组
		$result = D('Giftlist') -> getNicename($ids);
                $this -> assign('niceName',$result);
			$this -> assign('list',$list);
			$this->assign('page',$this->getPageShow($pageMaps));
			$this->assign('breadCrumb', $breadCrumb);// 
			$this->adminDisplay();
		}
	
	
	
	
	public function del(){
			$giftlist_id = I("post.data",0,"intval");
			if(empty($giftlist_id)){
				$this -> error('参数不能为空');
			}
			
			if( D("Giftlist") -> delData($giftlist_id) ){
				$this -> success("数据删除成功");
			}else{
				$this -> error('数据删除失败');
			}
		}
	
	public function batchAction(){
			$ids  = I('post.ids',''); //接收所选中的要操作id
			$type = I('post.type');//接收要操作的类型   如删除。。。
			if(empty($ids)||empty($type)){
				$this->error('参数不能为空！');
			}
			$ids = count($ids) ? implode(',', $ids) : $ids[0];
			$result = D('Giftlist') -> delMsgs($ids);
			if($result){
				$this -> success('操作成功！');
			}else{
				$this -> error('操作失败！');
			}
		}
	
	
  }