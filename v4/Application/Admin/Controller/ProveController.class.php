<?php
namespace Admin\Controller;
use Admin\Controller\AdminController;
/**
 * 认证管理
 */
class ProveController extends AdminController
{
    /**
     * 当前模块参数
     */
    public function _infoModule()
    {
        $data = array('info' => array('name' => '保险公司认证管理',
                'description' => '管理网站所有保险公司认证信息',
                ),
            'menu' => array(
                array('name' => '认证列表',
                    'url' => U('Admin/Prove/index'),
                    'icon' => 'list',
                    ),
// 					array('name' => '添加保险公司',
//                     'url' => U('Admin/Prove/add'),
//                     'icon' => 'plus',
//                     ),
                //$contentMenu
                )
            );
		
        return $data;
    }
    /**
     * 列表
     */
    public function index()
    {
		
		$keyword = I('request.keyword','');
		$status  =I('request.status','');
        $breadCrumb = array('认证列表' => U());
        $this->assign('breadCrumb', $breadCrumb);

		$pageMaps = array();
        $pageMaps['keyword'] = $keyword;
        $pageMaps['status'] = $status;
        $where = array();
		if(!empty($keyword)){
            $where['_string'] = "A.account like '%$keyword%' or B.user_login like '%$keyword%' or C.bx_name like '%$keyword%' ";      
        }
        //查询数据
        if(!empty($status)){
        	switch ($status) {
        		case '1':
        			$where['A.status'] = 1;
        			break;
        		case '2':
        			$where['A.status'] = 2;
        			break;
        		case '3':
        			$where['A.status'] = 3;
        			break;
        	}
        }
        $count = D('Prove')->countList($where);
        $limit = $this->getPageLimit($count,20);
		//dump($limit);
  
        $list = D('Prove')->getList($where,$limit);
        
	    $this->assign('pageMaps',$pageMaps);
		$this->assign('page',$this->getPageShow($pageMaps));
		$this->assign('list',$list);
        $this->adminDisplay();
    }
    
	 /**
     * 增加
     */
    public function add(){
        if(!IS_POST){
            $breadCrumb = array('保险公司列表'=>U('index'),'保险公司添加'=>U());
            $this->assign('breadCrumb',$breadCrumb);
            $this->assign('name','添加');
            $this->adminDisplay('info');
        }else{
            if(D('Prove')->saveData('add')){
                $this->success('添加成功！');
            }else{
                $msg = D('Prove')->getError();
                if(empty($msg)){
                    $this->error('添加失败');
                }else{
                    $this->error($msg);
                }
            }
        }
    }
    
    /**
     * 修改
     */
    public function edit(){
    		$Id = I('get.id','','intval');
    		$status = I('get.status','','intval');
    		$uid = I('get.uid','','intval');
    		$bxid = I('get.bxid','','intval');
    	    if(empty($Id)||empty($status)) $this->error('参数不能为空！');
    	    //获取记录
    	    $data['status'] = $status;
    	    	if($status==1){
    	    		if(empty($uid)||empty($bxid)) $this->error('参数不能为空！');
    	    		$info = D ( 'Users' )->where ( "id=$uid" )->find();
    	    		if($info['bxarr']){
    	    			$da= unserialize($info['bxarr']);
    	    			if(in_array($bxid,$da)) $this->error('该保险公司已认证！');
    	    			$da[] =$bxid;
    	    			$bxids['bxarr'] =serialize($da);
    	    		    $res = D ( 'Users' )->where ( "id=$uid" )->save($bxid);
    	    		    if($res !=1) $this->error('操作失败');
    	    		}else{
    	    		 $da['bxarr'] =serialize(array("$bxid"));
    	    	     $res = D ( 'Users' )->where ( "id=$uid" )->save($da);
    	    	     if($res !=1) $this->error('操作失败');
    	    		}
    	    	}	
    	    	$re = D ( 'Prove' )->where ( "id=$Id" )->save($data);
    	    	if($re==1){
    	    		$this->success('操作成功！');
    	    	}
    	    	
    	    
    }

	
	public function del(){
		$contentId = I('post.data',0,'intval');
        if(empty($contentId)){
            $this->error('参数不能为空！');
        }
        if(D('Prove')->delData($contentId)){
            $this->success('删除成功！');
        }else{
            $this->error('删除失败！');
        }
	}
	
}

