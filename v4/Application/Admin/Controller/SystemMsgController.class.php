<?php
namespace Admin\Controller;
use Admin\Controller\AdminController;
	/*
	 * 系统消息
	*/
	class SystemMsgController extends AdminController{
		
		public function _infomodule(){
			$data = array(
				'info' => array(
					'name' =>'系统消息管理',
					'description' => '管理所有发送给用户的系统消息',
				),
				'menu' => array(
					array(
						'name' => '消息列表',
						'url' => U('Admin/SystemMsg/index'),
						'icon' => 'list',
					),
					array(
						'name' => '新消息',
						'url'  => U('Admin/SystemMsg/add'),
						'icon' => 'plus',
					),
					
				)			
			);
			return $data;
		}
		
		
		public function index(){
			$breadCrumb = array('系统消息列表' => U());//面包屑分类
			$keyword = I('request.keyword','');//搜索的关键字字段
			$msg_type = I('request.msg_type','','trim');//筛选的字段
			$order_by = I('request.order_by','asc','trim');//排序
			$model = D('SystemMsg');
			$pageMaps = array();
			$pageMaps['keyword'] = $keyword;
			$pageMaps['msg_type'] = $msg_type;
			$pageMaps['order_by'] = $order_by;
			$where = array();
			if(!empty($keyword)){
				$where['_string'] = 'uid='.$keyword;
			}
			
			$msgtype = C('Systemmsgtype');
			
			if(!empty($msg_type)){
				$where['msg_type'] = $msg_type;				
			}
			
			
			 $this->assign('pageMaps',$pageMaps);
			$count = $model -> countList($where);//满足条件的总条数
			$limit = $this -> getPageLimit($count,20);//获取每页要显示的条数
			$order = $order? $order: 'msg_id desc'; //获取排序规则  虽然好像并没有$order
			$list = $model -> loadList($where,$limit,$order);
			$this -> assign('msgtype',$msgtype);
			$this -> assign('list',$list);
			$this->assign('page',$this->getPageShow($pageMaps));
			$this->assign('breadCrumb', $breadCrumb);// 
			$this->adminDisplay();
		}
		
		
		
		public function add(){
			$uid = I('get.uid',0,'intval');
			if(IS_POST){
				if(D('SystemMsg') -> saveData()){
					$this -> success('消息发送成功');
				}else{
					$errMsg = D('SystemMsg') -> getError();
					if($errMsg){
						$this -> error($errMsg);
					}else{
						$this -> error('消息发送失败');
					}
				}
			}else{
				$this -> assign('uid',$uid);
				$this -> adminDisplay('info');
			}
		}
		
		
		
		public function del(){
			$msg_id = I("post.data",0,"intval");
			if(empty($msg_id)){
				$this -> error('参数不能为空');
			}
			
			if( D("SystemMsg") -> delData($msg_id) ){
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
			$result = D('SystemMsg') -> delMsgs($ids);
			if($result){
				$this -> success('操作成功！');
			}else{
				$this -> error('操作失败！');
			}
		}
		
	}
	