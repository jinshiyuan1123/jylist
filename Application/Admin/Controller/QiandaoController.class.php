<?php
namespace Admin\Controller;
use Admin\Controller\AdminController;
/**
 * 充值记录管理
 */
class QiandaoController extends AdminController{
    /**
     * 当前模块参数
     */
    public function _infoModule(){
        $data = array(
        		'info' => array(
	        		'name' => '签到记录管理',
	                'description' => '管理用户的签到记录',
                ),                
            	'menu' => array(
                	array(
	                	'name' => '签到记录列表',
	                    'url' => U('Admin/Qiandao/index'),
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
		
		$breadCrumb = array('签到记录列表' => U());//面包屑
		$status =I('request.status',''); //筛选充值状态		
		$keyword = I('request.keyword','');  //搜索		
		//搜索关键字
		if($keyword){
			$where['uid'] = $keyword; //可以搜索uid
		}		
		$model = M("Qiandao");				
		$count = $model -> where($where) -> count();
		$limit = $this -> getPageLimit($count,20);  //获取每页要显示的条数 		
		$list = $model -> where($where) -> order('last_time desc') -> limit($limit) -> select();		
		foreach($list as $k => $v ){
			$ids[] = $v['uid'];
		}
		$ids = join($ids, ',');
		$result = D('Users') -> getNicename($ids);		
		$this -> assign('niceName',$result);
		$this -> assign('page',$this->getPageShow($pageMaps));		
		$this -> assign('list',$list);
		$this -> assign('breadCrumb',$breadCrumb);		
		$this -> adminDisplay();
    }
   
	
}

