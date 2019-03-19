<?php
namespace Admin\Controller;
use Admin\Controller\AdminController;
/**
 * 亲密度控制器
 */
 class IntimacyController extends AdminController{
 	
	protected function _infoModule(){
		$data = array(
			'info' => array(
				'name' =>'亲密度记录管理',
				'description' =>'管理用户的亲密度增减记录',
			),
			'menu' => array(
				array(
					'name' => '记录列表',
					'url' => U('Admin/Intimacy/index'),
					'icon' => 'list',
				),
			),
		);
		return $data;
	}
	
		
	public function index(){
		$breadCrumb = array('亲密度记录管理' => U());//面包屑分类
		$keyword = I('request.keyword','');//接收关键字
		$type = I('request.type',0,'intval');
		
		if(!empty($keyword)){
			$where['_string'] = 'uid in('.$keyword.') or fromuid in('.$keyword.')';
		}
		
		if(!empty($type)){
			$where['type'] = $type;
		}
		
		$pageMaps['keyword'] = $keyword;
		
		$count = M('AccountQmdLog') -> where($where) -> count();//查询满足条件的几条
		$limit = $this -> getPageLimit($count,20);  //获取每页要显示的条数 
		$list = M('AccountQmdLog') -> where($where) -> limit($limit) -> order('id desc') -> select();
		$ids = array();
		foreach($list as $k => $v){
			$ids[] = $v['uid'];
			$ids[] = $v['fromuid'];
		}
		$ids = array_unique($ids);
		$ids = join($ids, ',');
		$result = D('Users') -> getNicename($ids);
		$this->assign('page',$this->getPageShow($pageMaps));
		$this -> assign('userNicename',$result);
		$this -> assign('list',$list);
		$this -> assign("breadCrumb",$breadCrumb);
		$this -> adminDisplay();
	}
	
	
	
		
	public function del(){
		$id = I('post.data',0,'intval');
		if(!$id){
			$this -> error('参数不能为空');
		}
		$where['id'] = $id;
		$result = M('AccountQmdLog') -> where($where) -> delete();
		if($result){
			$this -> success('数据删除成功');
		}else{
			$this -> error('数据删除失败');
		}
	}
	
	
	
 }
