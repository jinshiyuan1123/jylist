<?php
namespace Admin\Controller;
use Admin\Controller\AdminController;
/**
 * 充值记录管理
 */
class RechargeLogController extends AdminController
{
	
	

	
	
    /**
     * 当前模块参数
     */
    public function _infoModule()
    {
        $data = array(
        		'info' => array(
	        		'name' => '充值记录管理',
	                'description' => '管理用户的充值记录',
                ),
                
            	'menu' => array(
                	array(
	                	'name' => '充值记录列表',
	                    'url' => U('Admin/RechargeLog/index'),
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
		
		$breadCrumb = array('充值记录列表' => U());//面包屑
		$payfrom =I('request.payfrom',''); //筛选支付平台	
		$status =I('request.status',''); //筛选充值状态		
		$keyword = I('request.keyword','');  //搜索
		
		//搜索关键字
		if($keyword){
			$where['_string'] = 'uid = '.$keyword.' or out_trade_no = "'.$keyword.'"'; //可以搜索订单号和uid
		}
		
		//支付平台筛选
		if(!empty($payfrom)){
			$where['payfrom'] = $payfrom;
		}
		
		//支付状态筛选
		if(!empty($status)){
			if($status==-1) $status=0;
			$where['status'] = $status;	
		}
				
		$pageMaps['keyword'] = $keyword;
		$pageMaps['payfrom'] = $payfrom;	
		$pageMaps['status'] = $status;
		
		$model = M("ChongzhiLog");
				
		$count = $model -> where($where) -> count();
		$limit = $this -> getPageLimit($count,20);  //获取每页要显示的条数 
		
		$list = $model -> where($where) -> order('time desc') -> limit($limit) -> select();
		
		foreach($list as $k => $v ){
			$ids[] = $v['uid'];
		}
		$ids = array_unique($ids);
		$ids = join($ids, ',');
		$result = D('Users') -> getNicename($ids);
//		
		$this -> assign('pageMaps',$pageMaps);
		$this -> assign('niceName',$result);
		$this -> assign('name','充值记录');
		$this -> assign('page',$this->getPageShow($pageMaps));		
		$this -> assign('list',$list);
		$this -> assign('breadCrumb',$breadCrumb);		
		$this -> adminDisplay();
    }
   
	
}

