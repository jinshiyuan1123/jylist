<?php
namespace Admin\Controller;
use Admin\Controller\AdminController;
/**
 * 管理
 */
class MoenyController extends AdminController
{
    /**
     * 当前模块参数
     */
    public function _infoModule()
    {
        $data = array('info' => array('name' => '现金发放管理',
                'description' => '管理网站现金发放信息',
                ),
            'menu' => array(
                array('name' => '现金发放列表',
                    'url' => U('Admin/Moeny/index'),
                    'icon' => 'list',
                    )
                //$contentMenu
                )
            );
		/*	
        $modelList = getAllService('ContentModel', '');
        $contentMenu = array();
        if (!empty($modelList))
        {
            $i = 0;
            foreach ($modelList as $key => $value)
            {
                $i++;
                $data['menu'][$i]['name'] = '添加' . $value['name'] . '分类';
                $data['menu'][$i]['url'] = U($key . '/AdminBxcat/add');
                $data['menu'][$i]['icon'] = 'plus';
            }
        }
		*/
        return $data;
    }
    /**
     * 列表
     */
    public function index()
    {
    	$keyword = I('request.keyword','','trim');
    	$type =I('request.type','','intval');
    	$status =I('request.status','','intval');
		
        $breadCrumb = array('现金发放列表' => U());
        $this->assign('breadCrumb', $breadCrumb);
        //$this->assign('list', D('User')->loadData());
		
        $pageMaps = array();
        $pageMaps['keyword'] = $keyword;
        $pageMaps['type'] = $type;
        $pageMaps['status'] = $status;
		 $where = array();
		if(!empty($keyword)){
            $where['_string'] = '(B.user_login like "%'.$keyword.'%") OR (A.uid = "'.$keyword.'")';
        }
        if(!empty($type)){
        	$where['A.type'] = $type;
        }
        if(!empty($status)){
        	$where['A.status'] = $status;
        }
 
       // $pageMaps['class_id'] = $classId;
       // $pageMaps['position_id'] = $positionId;
        //查询数据
		
        $count = D('Tixian')->countList($where);
       
        $limit = $this->getPageLimit($count,20);
		//dump($limit);
		
       $list = D('Tixian')->loadList($where,$limit);
	   $this->assign('pageMaps',$pageMaps);
	     
	  
	   $type =array(
	   		1=>'微信',
	   		2=>'支付宝',
	   		3=>'话费充值',
	   );
	   $status =array(
	   		0=>'失败',
	   		1=>'成功',
	   		2=>'审核中'
	   );
	   
	    $this->assign('type',$type);
		$this->assign('page',$this->getPageShow($pageMaps));
		
		$this->assign('list',$list);
		$this->assign('status',$status);
		//print_r($list);
        $this->adminDisplay();
    }
    
    
    
    
 /**
     * 修改
     */
    public function edit(){
    	if(!IS_POST){
    		$breadCrumb = array('列表'=>U('index'),'修改'=>U());
    		$this->assign('breadCrumb',$breadCrumb);
    		$Id = I('get.id','','intval');
    	    if(empty($Id)) $this->error('参数不能为空！');
    	    //获取记录
    		$info = M ( 'Tixian' )->where ( "id=$Id" )->find ();
    		
    		if(!$info) $this->error('无数据！');
    		$type =array(
    				-1=>'后台操作',
    				1=>'分享文章送',
    				2=>'关注送',
    				3=>'分享好友送',
    				4=>'提现',
    				5=>'摇一摇',
    		);
    		$this->assign('info',$info);
    		$this->assign('type',$type);
    		$this->adminDisplay('info');
    	}else{
			if(I('post.status')==1){
				$res = A('Home/Site')->new_tixian(I('post.id'));				
				if($res!= 1) $this->error($res);	
				
			}			
    		if(D('Tixian')->saveData('edit')){				
    			$this->success('修改成功！');
    		}else{
    			$msg = D('Tixian')->getError();
    			if(empty($msg)){
    				$this->error('修改失败');
    			}else{
    				$this->error($msg);
    			}
    		}
    	}
    }
    
    //提现审核
    public function tixian($id='',$status=0){
    	//if(empty($status)) $this->error('参数错误！');
    	$mod = D( 'Tixian' );
    	$info = $mod->where ( "id=".$id )->find ();
    	if(!$info) $this->error('无数据！');
    	$re =  $mod ->editData($id,$status);
    	if($re){
    		
    		switch ($status){
    			case 0:
    				$body ="金额".$info['fee']."元提现失败，已退".$info['fee']*C('moneyBL').C('money_name');
    				$res = $this->changemoney($info['uid'], $info['fee']*C('moneyBL'),101,$body,"","",1,get_client_ip(),0,101);
    				if($res){
    					$this->setSystemTj(array('txFlag'=>-1,'txFee'=>(-1)*$info['fee']));
    					$this->success('操作成功！');
    				}else{
    					$mod->where ( "id=$id" )->setField ('status',2);
    					$this->error('操作失败！');
    				}
    				break;
    			case 1:
    				if($info['status']==2){
    					$res =1;
    					
    				if($info['type']==1){
    					$res = 0;
    					$res = $this->new_tixian($id,'admin');
    				} 
    					if($res == 1){
    						$this->setSystemTj(array('txFlag'=>-1,'txFee'=>(-1)*$info['fee'],'txTotalFee'=>$info['fee'],'txTotalFeeDay'=>$info['fee']));
    						$this->success('操作成功！');
    					}else{
    						$mod->where ( "id=$id" )->setField ('status',2);
    						$this->error($res);
    					} 
    				}
    				break;
    		}
    	}
    	
    	$this->error('操作失败！');
    
    	 
    }
    
    public function clearmoney(){
		$uid = I('post.uid');
		$re = M("Users")->where("id=".$uid)->setField('money',0);
		if($re){
			$this->success("成功");
		}else{
			$this->error('失败');
		}
	}
    
	
 
	
	
	
	
}

