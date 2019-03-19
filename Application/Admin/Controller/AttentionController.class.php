<?php
namespace Admin\Controller;
use Admin\Controller\AdminController;
/**
 * 关注消息
 */
 class AttentionController extends AdminController{
  		
  	public function _infoModule(){
 		$data = array(
			'info' => array(
				'name' => '关注消息管理',
				'description' => '管理用户之间的关注信息',
			),
			'menu' => array(
				array(
					'name' => '关注列表',
	                'url' => U('Admin/Attention/index'),
	                'icon' => 'list',
				),		
			),
		);
		return $data; 		
  	}
	
	
	
	public function index(){
		
		$breadCrumb = array('关注列表' => U());//面包屑分类
		$keyword = I('request.keyword','');//接收关键字
		$pageMaps['keyword'] = $keyword;
		
		if(!empty($keyword)){				//判断是否有关键字
			$where['_string'] = "fromuid in(".$keyword.") or touid in(".$keyword.")";
		}	
		
		$model = M('UserSubscribe');
		$count = $model -> where($where) -> count(); //查询满足条件的几条
		$limit = $this -> getPageLimit($count,20);  //获取每页要显示的条数
		$list = $model -> where($where) -> limit($limit) -> order('id desc') -> select();
		$ids = array();	
		foreach($list as $k => $v){			
			$ids[] = $v['fromuid'];
			$ids[] = $v['touid'];
			
			$res = D('Users') -> getNicename($v['touid']);//获取是否有马甲
			foreach($res['ismj'] as $key => $val ){  //遍历
				if($val == '马甲'){
					$re = $model -> where('fromuid = '.$v['touid'].' and touid = '.$v['fromuid']) -> find();	//判断是否有互相关注					
					if(!$re){  //没有互相关注
						$abc[$v['fromuid']][$v['touid']]['hehe'] = 1 ;
					}
				}
			}
					    
		}
		
		$ids = array_unique($ids); //去重
		$ids = join($ids, ','); //变成普通数组
		$result = D('Users') -> getNicename($ids);
		
		$this -> assign('abc',$abc);
		$this -> assign('page',$this->getPageShow($pageMaps));
        $this -> assign('niceName',$result);
		$this -> assign("list",$list);
		$this -> assign('breadCrumb',$breadCrumb);		
		$this -> adminDisplay();
	}
	
	
	
	public function del(){
		$id = I('post.data',0,'intval');
		if(!$id){
			$this -> error('参数不能为空');
		}
		$where['id'] = $id;
		$result = M('UserSubscribe') -> where($where) -> delete();
		if($result){
			$this->success("数据删除成功");
		}else{
			$this->error("数据删除失败");
		}		
	}
	

	
  }