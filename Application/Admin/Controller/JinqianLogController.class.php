<?php
namespace Admin\Controller;
use Admin\Controller\AdminController;
/**
 * 管理
 */
class JinqianLogController extends AdminController
{
    /**
     * 当前模块参数
     */
    public function _infoModule()
    {
        $data = array(
        	'info' => array(
        		'name' => '金钱记录管理',
                'description' => '管理用户金钱变化记录',
            ),
            'menu' => array(
                array(
                	'name' => '金钱记录列表',
                    'url' => U('Admin/JinqianLog/index',array('type'=>1)),
                    'icon' => 'list',
                ),
				array(
					'name' => '聊天变动列表',
                    'url' => U('Admin/JinqianLog/index',array('type'=>2)),
                    'icon' => 'list',
                ),
				array(
					'name' => '照片变动列表',
                    'url' => U('Admin/JinqianLog/index',array('type'=>3)),
                    'icon' => 'list',
                ),
				array(
					'name' => '签到变动列表',
                    'url' => U('Admin/JinqianLog/index',array('type'=>4)),
                    'icon' => 'list',
                )
            ),
       	);
        return $data;
    }
    /**
     * 列表
     */
    public function index(){
    	
		$type = I('get.type','');	
		$flag = I('request.flag','');	
					
		if($type == 1){
			$model = M("AccountMoneyLog");
			if(!empty($flag)) $where['type'] = $flag;
			$this -> assign('name','金钱变动');
			$breadCrumb = array('金钱记录列表' => U('index',array('type'=>1)));
		}elseif($type == 2){  
			$model = M("AccountMoneyLogLt");	//聊天
			$this -> assign('name','聊天变动');
			$breadCrumb = array('聊天变动列表' => U('index',array('type'=>2)));
		}elseif($type == 3){
			$model = M("AccountMoneyLogPhoto");  //上传照片
			$this -> assign('name','照片变动');
			$breadCrumb = array('照片变动列表' => U('index',array('type'=>3)));
		}elseif($type == 4){
			$model = M("AccountMoneyLogQd");	//签到
			$this -> assign('name','签到变动');
			$breadCrumb = array('签到变动列表' => U('index',array('type'=>4)));
		}
		
		$keyword = I('request.keyword','','trim');
		
        $pageMaps['keyword'] = $keyword;
       	$pageMaps['type'] = $type;
		if(!empty($keyword)){
            $where['_string'] = 'uid = '.$keyword;
        } 
				
		$count = $model -> where($where) -> count();
		$limit = $this -> getPageLimit($count,19);  //获取每页要显示的条数 
		$list = $model -> where($where) -> order('time desc') -> limit($limit) -> select();
		
		foreach($list as $k => $v ){
			$ids[] = $v['uid'];
		}
		$ids = array_unique($ids);
		$ids = join($ids, ',');
		$result = D('Users') -> getNicename($ids);
		
		
		$this->assign('breadCrumb', $breadCrumb);		
		$this -> assign('niceName',$result);
		$this -> assign('page',$this->getPageShow($pageMaps));
		$this -> assign('list',$list);
		$this -> assign('type',$type);
		       
        $this->adminDisplay();
    }
    
    
    
    

	
}

