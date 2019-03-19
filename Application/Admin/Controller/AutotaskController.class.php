<?php
namespace Admin\Controller;
use Admin\Controller\AdminController;
/**
 * 充值记录管理
 */
class AutotaskController extends AdminController{
    /**
     * 当前模块参数
     */
    public function _infoModule(){
        $data = array(
        		'info' => array(
	        		'name' => '自动任务记录管理',
	                'description' => '管理网站的自动任务记录',
                ),                
            	'menu' => array(
                	array(
	                	'name' => '任务记录列表',
	                    'url' => U('Admin/Autotask/index'),
	                    'icon' => 'list',
                    ),
                ),
            );
        return $data;
    }
	
    /**
     * 列表
     */
    public function index(){
		
		$breadCrumb = array('自动任务记录列表' => U());//面包屑
		$type =I('request.type',''); //筛选
		$keyword = I('request.keyword','');  //搜索		
		//搜索关键字
		if(!empty($keyword)){
			$where['_string'] = 'uid = '.$keyword.' or touid = '.$keyword; //可以搜索uid
		}	
		if(!empty($type)){
			$where['type'] = $type;
		}
		$pageMaps['keyword'] = $keyword;
		
			
		$model = M("Mjlog");				
		$count = $model -> where($where) -> count();
		$limit = $this -> getPageLimit($count,20);  //获取每页要显示的条数 		
		$list = $model -> where($where) -> order('time desc') -> limit($limit) -> select();		
		foreach($list as $k => $v ){			
			$ids[] = $v['uid'];
			$ids[] = $v['touid'];
		}
		$ids = array_unique($ids);
		$ids = join($ids, ',');
		$result = D('Users') -> getNicename($ids);	
		
		$this -> assign('pageMaps',$pageMaps);	
		$this -> assign('niceName',$result);
		$this -> assign('page',$this->getPageShow($pageMaps));		
		$this -> assign('list',$list);
		$this -> assign('breadCrumb',$breadCrumb);		
		$this -> adminDisplay();
    }
   	
	
	
	
	
	
	
	//单条删除
	public function del(){
		$id = I("post.data",0,"intval");
		if(empty($id)){
			$this -> error('参数不能为空');
		}		
		$res = M('Mjlog') -> where('id = '.$id) -> delete();
		if($res){
			$this -> success("数据删除成功");
		}else{
			$this -> error('数据删除失败');
		}
	}
   
   
   
   
   	//批量删除
   	public function batchAction(){
		$ids  = I('post.ids',''); //接收所选中的要操作id
		$type = I('post.type');//接收要操作的类型   如删除。。。
		if(empty($ids)||empty($type)){
			$this->error('参数不能为空！');
		}
		$ids = count($ids) ? implode(',', $ids) : $ids[0];
		if($type == 1){
			$result = M('Mjlog') -> where('id in('.$ids.')') -> delete();
		}		
		if($result){
			$this -> success('操作成功！');
		}else{
			$this -> error('操作失败！');
		}
	}
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
	
}

