<?php
namespace Admin\Controller;
use Admin\Controller\AdminController;
/**
 * 积分余额管理
 */
class AccountController extends AdminController{
	
	
    /**
     * 当前模块参数
     */
    public function _infoModule(){
    	
		if( U() == U('account') ){  //财富值or魅力值
			$data = array(
	    		'info' => array(
	        		'name' => '财富值和魅力值管理',
	                'description' => '管理用户的财富值或魅力值',
	            ),
	            
	        	'menu' => array(
	            	array(
	                	'name' => '财富值OR魅力值列表',
	                    'url' => U('Admin/Account/account'),
	                    'icon' => 'list',
	                ),
	            ),
	        );
		}else if( U() == U('money') ){   //现金发放管理 
			$data = array(
				'info' => array(
					'name' => '现金发放管理',
                	'description' => '管理网站现金发放信息',
                ),
            	'menu' => array(
                	array(
                		'name' => '现金发放列表',
                    	'url' => U('Admin/Account/money'),
                    	'icon' => 'list',
                    )
                )
            );
		}else if( U() == U('jinqianlog') ){  //金钱记录
			$data = array(
        		'info' => array(
	        		'name' => '金钱记录管理',
	                'description' => '管理用户金钱变化记录',
	            ),
	            'menu' => array(
	                array(
	                	'name' => '金钱记录列表',
	                    'url' => U('Admin/Account/jinqianlog',array('type'=>1)),
	                    'icon' => 'list',
	                ),
					array(
						'name' => '聊天变动列表',
	                    'url' => U('Admin/Account/jinqianlog',array('type'=>2)),
	                    'icon' => 'list',
	                ),
					array(
						'name' => '照片变动列表',
	                    'url' => U('Admin/Account/jinqianlog',array('type'=>3)),
	                    'icon' => 'list',
	                ),
					array(
						'name' => '签到变动列表',
	                    'url' => U('Admin/Account/jinqianlog',array('type'=>4)),
	                    'icon' => 'list',
	                )
	            ),
	       	);
		}else if( U() == U('chongzhi') ){  //充值记录
			$data = array(
        		'info' => array(
	        		'name' => '充值记录管理',
	                'description' => '管理用户的充值记录',
                ),              
            	'menu' => array(
                	array(
	                	'name' => '充值记录列表',
	                    'url' => U('Admin/Account/chongzhi'),
	                    'icon' => 'list',
                    ),
                ),
            );
		}
        return $data;
    }
    /**
     * 魅力值or财富值   列表**************************************************************************************
     */
    public function account(){ 
		//3 收礼物变动 4被关注女 5邀请好友男 201购买VIP获得
		$breadCrumb = array('财富值or魅力值记录列表' => U());//面包屑
		$type =I('request.type','','intval'); //筛选类别
		
		$gender =I('request.gender',0,'intval'); //筛选的性别
		$keyword = I('request.keyword','');  //搜索

		if($keyword){
			$where['_string'] = 'uid in("'.$keyword.'")';
		}
		
		if(!empty($gender)){
			if($gender == 1){
				$where['sex'] = 1;
			}
			if($gender == 2){
				$where['sex'] = 2;
			}			
		}
		
		if(!empty($type)){
			$where['type'] = $type;
		}
		
		$pageMaps['keyword'] = $keyword;
		$pageMaps['type'] = $type;
		
		$model = M("AccountJifenLog");
		
		$count = $model -> where($where) -> count();
		$limit = $this -> getPageLimit($count,19);  //获取每页要显示的条数 
			
		$list = $model -> where($where) -> order('time desc') -> limit($limit) -> select();
		
		
		foreach($list as $k => $v ){
			$ids[] = $v['uid'];
		}
		$ids = array_unique($ids);
		$ids = join($ids, ',');
		$result = D('Users') -> getNicename($ids);
		
		$this -> assign('pageMaps',$pageMaps);
		$this -> assign('niceName',$result);
		$this -> assign('name','财富值or魅力值');
		$this -> assign('page',$this->getPageShow($pageMaps));		
		$this -> assign('list',$list);
		$this -> assign('breadCrumb',$breadCrumb);		
		$this -> adminDisplay("account");
    }
	 /**
     * 增加
     */
    public function add(){
        if(!IS_POST){
            $breadCrumb = array('列表'=>U('index'),'添加'=>U());
            $this->assign('breadCrumb',$breadCrumb);
            $this->assign('name','添加');
            $this->adminDisplay('info');
        }else{
        	  
            if(D('AccountLog')->saveData('add')){
                $this->success('添加成功！');
            }else{
                $msg = D('AccountLog')->getError();
                if(empty($msg)){
                    $this->error('添加失败');
                }else{
                    $this->error($msg);
                }
            }
        }
    }
 	
	/*
	 *  现金发放管理   **************************************************************************************
	 * 
	 */
	
	public function money(){
		
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
        $this->adminDisplay('money');
    }
	
	/**
     * 修改
     */
	public function moneyEdit(){
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
    		$this->adminDisplay('moneyinfo');
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
	
	

	
	
	
	 /**
     * 金钱记录列表                **************************************************************************************
     */
    public function jinqianlog(){
    	
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
		       
        $this->adminDisplay('jinqianlog');
    }
	
	
	
	
	
	
	
	
	
	
	
	/**
     * 充值列表         **************************************************************************************
     */
    public function chongzhi(){
    			
		$breadCrumb = array('充值记录列表' => U());//面包屑
		$payfrom = I('request.payfrom',''); //筛选支付平台	
		$status = I('request.status','');   //筛选充值状态	
		$paytype = I('request.paytype','');	 //筛选充值类型
		$keyword = I('request.keyword','');  //搜索关键字
		
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
		
		//充值类型筛选
		if(!empty($paytype)){
			$where['paytype'] = $paytype;	
		}
		
				
		$pageMaps['keyword'] = $keyword;
		$pageMaps['payfrom'] = $payfrom;
		$pageMaps['paytype'] = $paytype;	
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
		
		$this -> assign('pageMaps',$pageMaps);
		$this -> assign('niceName',$result);
		$this -> assign('name','充值记录');
		$this -> assign('page',$this->getPageShow($pageMaps));		
		$this -> assign('list',$list);
		$this -> assign('breadCrumb',$breadCrumb);		
		$this -> adminDisplay('chongzhi');
    }
	
	
	
	
	
	
	  
	
}