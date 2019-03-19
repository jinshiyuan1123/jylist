<?php
	namespace DuxCms\Controller;
	use Admin\Controller\AdminController;
	/*
	 * 
	 * 
	 */
	class GiftController extends AdminController{
		
		
		protected function _infoModule(){
			return array(
				'info' => array(
					'name' => '虚拟礼物管理',
					'description' => '管理网站所有的虚拟礼物',
				),
				
				'menu' => array(
					array('name' => '礼物列表' , 'url' => U('DuxCms/Gift/index'), 'icon' => 'list'),
					array('name' => '添加礼物' , 'url' => U('DuxCms/Gift/add'),   'icon' => 'plus'),
				)
			);
		}
		
	/*******虚拟礼物列表(首页)*******/	
		public function index(){
			$breadCrumb = array('虚拟礼物管理' => U());
			$where = "";
			$order = $order ? $order : 'gift_id desc';
			$count = D('Gift')->countList($where); 
			$limit = $this->getPageLimit($count,20);
			$list = D('Gift') -> loadList($where,$limit,$order);
			$this->assign('page',$this->getPageShow($where));  			
			$this->assign('list',$list );
			$this->assign('breadCrumb', $breadCrumb);
			$this->adminDisplay();
		}
		
	/*******虚拟礼物添加*******/	
		
		public function add(){
			if(IS_POST){			
				$model = D("Gift");
				if( $model -> saveData('add') ){
					$this -> success('礼物添加成功！');
				}else{
					$msg = $model -> getError();
					if($msg){
						$this -> error( $msg );
					}else{
						$this -> error('礼物添加失败');
					}
				}
			}else{				
           		$this->assign('name','添加');
				$this->adminDisplay('info');
			}
		}
		
	/*******虚拟礼物修改*******/	
		public function edit(){
			$model = D('Gift');
			if(IS_POST){
				if($model -> saveData("edit")){
					$this -> success("修改礼物成功");
				}else{
					$msg = $model -> getError();
					if($msg){
						$this -> error($msg);
					}else{
						$this -> error("修改礼物失败");
					}
				}
			}else{
				$gift_id = I('get.gift_id',0,'intval');
				if(empty($gift_id)){
					$this -> error('参数不能为空');
				}
				$info = $model -> getInfo($gift_id);
				if(!$info){
					$this -> error($model -> getError());
 				}
				$BreadCrumb = array('碎片列表'=>U('index'),'修改'=>U('',array('gift_id'=>$gift_id)));
				$this -> assign('name','修改');
				$this -> assign('info',$info);
				$this -> adminDisplay('info');
			}
		}
		
		
		/*******虚拟礼物删除*******/	
		public function del(){
			$gift_id = I('post.data',0,'intval');
			if(!$gift_id){
				$this -> error('参数不能为空');
			}
			if(D('Gift') -> delData($gift_id)){
				$this -> success('删除礼物成功',U('index'),3);
			}else{
				$this -> error('删除礼物失败');
			}			
		}
			
		
	}