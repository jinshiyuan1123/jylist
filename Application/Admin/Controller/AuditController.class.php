<?php
namespace Admin\Controller;
use Admin\Controller\AdminController;
/**
 * 头像/昵称 审核管理
 */
class AuditController extends AdminController{
	
	public function _infoModule(){
 		$data = array(
			'info' => array(
				'name' => '审核管理',
				'description' => '审核用户 昵称、头像、内心独白',
			),
			'menu' => array(
				array(
					'name' => '审核昵称',
	                'url' => U('Admin/Audit/AuditNickname'),
	                'icon' => 'list',
				),		
				array(
					'name' => '审核头像',
	                'url' => U('Admin/Audit/AuditAvatar'),
	                'icon' => 'list',
				),
				array(
					'name' => '审核内心独白',
	                'url' => U('Admin/Audit/AuditMonolog'),
	                'icon' => 'list',
				),
			),
		);
		return $data; 		
  	}
	
	//昵称审核
	public function AuditNickname(){
		$breadCrumb = array('昵称审核' => U());
		$keyword = I('request.keyword','');
		$status = I('request.status',''); //筛选充值状态	

		$pageMaps['keyword'] = $keyword;
		$pageMaps['status'] = $status;
		$pageMaps['order_by'] = 'creat_time desc';
		
		if(!empty($keyword)){
			$where['_string'] = 'uid = '.$keyword;
		}
		
		if(!empty($status)){
			if($status==-1) $status=0;
			$where['status'] = $status;
		}
		$mod = M('Audit');
		
		$where['type'] = 1;		
		$count = $mod -> where($where) -> count();//查询满足条件的几条
		$limit = $this -> getPageLimit($count,20);  //获取每页要显示的条数 
		$list =  $mod -> where($where) -> limit($limit) -> order('created_time desc') -> select();
		$ids = array();
		foreach($list as $k => $v ){
			$ids[] = $v['uid'];
		}
		$ids = array_unique($ids);
		$ids = join($ids, ',');
		$result = D('Users') -> getNicename($ids);
		$this -> assign('niceName',$result);	
		$this -> assign('page',$this->getPageShow($pageMaps));		
		$this -> assign('list',$list);
		$this -> assign('pageMaps',$pageMaps);
		$this -> assign('name','昵称');
		$this -> assign('breadCrumb',$breadCrumb);
		$this -> getCount(1);
		$this -> adminDisplay('index');
	}
	
	
	
	//头像审核
	public function AuditAvatar(){
		$breadCrumb = array('头像审核' => U());
		$keyword = I('request.keyword','');
		$status = I('request.status',''); //筛选充值状态		
		$pageMaps['keyword'] = $keyword;
		$pageMaps['order_by'] = 'fromuid desc';
		if(!empty($keyword)){
			$where['_string'] = 'uid = '.$keyword;
		}
		$pageMaps['status'] = $status;
		if(!empty($status)){
			if($status==-1) $status=0;
			$where['status'] = $status;
		}
		$where['type'] = 0;
		$count = M('Audit') -> where($where) -> count();//查询满足条件的几条
		$limit = $this -> getPageLimit($count,20);  //获取每页要显示的条数 
		$list = M('Audit') -> where($where) -> limit($limit) -> order('created_time desc') -> select();
		$ids = array();
		foreach($list as $k => $v ){
			$ids[] = $v['uid'];
		}
		$ids = array_unique($ids);
		$ids = join($ids, ',');
		$result = D("Users") -> getNicename($ids);
		$this -> assign('niceName',$result);			
		$this -> assign('page',$this->getPageShow($pageMaps));
		$this -> assign('list',$list);	
		$this -> assign('pageMaps',$pageMaps);	
		$this -> assign('name','头像');
		$this -> assign('breadCrumb',$breadCrumb);
		$this -> getCount(0);
		$this -> adminDisplay('index');
	}
	
	
	
	
	//头像内心独白
	public function AuditMonolog(){
		$breadCrumb = array('内心独白审核' => U());
		$keyword = I('request.keyword','');	
		$status = I('request.status','');
		
		$pageMaps['keyword'] = $keyword;
		$pageMaps['status'] = $status;
		if(!empty($keyword)){
			$where['_string'] = 'uid = '.$keyword;
		}
		if(!empty($status)){
			if($status==-1) $status=0;
			$where['status'] = $status;
		}
		$where['type'] = 2;
		$count = M('Audit') -> where($where) -> count();//查询满足条件的几条
		$limit = $this -> getPageLimit($count,20);  //获取每页要显示的条数 
		$list = M('Audit') -> where($where) -> limit($limit) -> order('created_time desc') -> select();
		$ids = array();
		foreach($list as $k => $v ){
			$ids[] = $v['uid'];
		}
		$ids = array_unique($ids);
		$ids = join($ids, ',');
		
		
		$result = D("Users") -> getNicename($ids);
		$this -> assign('niceName',$result);			
		$this -> assign('page',$this->getPageShow($pageMaps));
		$this -> assign('list',$list);	
		$this -> assign('pageMaps',$pageMaps);	
		$this -> assign('name','内心独白');
		$this -> assign('breadCrumb',$breadCrumb);
		$this -> getCount(2);
		
		$this -> adminDisplay('index');
	}
	
	
	private function getCount($type = 0){
		 $iscount = I('get.iscount',0,'intval');
		 if(!$iscount) return false;
		 $name = array('avatarFlag','nicknameFlag','monoFlag');
		 $where['type'] =$type;
		 $where['status'] = 0;
	     $count =  M('Audit') -> where($where) -> count();
		 M('Systemtj')->where('id = '.M('Systemtj')->getField('id'))->setField($name[$type],$count);
	}
	
	
	
	//单条删除
	public function del(){
		$id = I('post.data',0,'intval');
		if(!$id){
			$this -> error('参数不能为空');
		}
		$where['id'] = $id;		
		$Mod = M('Audit');
		$AuditData =  $Mod->field('type,status')->where($where) ->find();
	
		$result = $Mod -> where($where) -> delete();
		if($result){
			if($AuditData['status']==0){
				$arr =array('avatarFlag','nicknameFlag','monoFlag');
				$this->setSystemTj($arr[$AuditData['type']],-1);
			}
			$this -> success('数据删除成功');	
		}else{
			$this -> error('数据删除失败');
		}
	}
	
	
	
	//批量操作
	public function batchAction(){
		$ids  = I('post.ids',''); //接收所选中的要操作id
		$type = I('post.type');//接收要操作的类型   如删除。。。
		if(empty($ids)||empty($type)){
			$this->error('参数不能为空！');
		}
		$modAudit =  M('Audit');
		//删除
		if($type ==1){	
			$ids = count($ids)>1 ? implode(',', $ids) : $ids[0];
			$where['_string'] = 'id in('.$ids.')';
			$list = $modAudit ->where($where) ->select();
			$result = $modAudit -> where($where) -> delete();
			if($result){
				$arr =array('avatarFlag','nicknameFlag','monoFlag');
				foreach ($list as  $v){
					if($v['status']==0){
						$this->setSystemTj($arr[$v['type']],-1);
					}
				}
				$this -> success('操作成功！');
			}else{
				$this -> error('操作失败！');
			}
		}else{
			$model = M('UserPhoto');
			$modUser =  M('Users');
			$modProfile =  M('UserProfile');
			$data['status'] = $type==2?1:2;
			foreach ($ids as $id){
				$res = $modAudit -> where('id = '.$id) -> save($data);//改audit表的数据
				if($res){
					$info = $modAudit ->where(' id ='.$id)->find();
					if($info){
				            if($type==3){
				               	$sysname = array('avatarFlag','nicknameFlag','monoFlag');
				               	$systype = array(12,11,13);
				               	$desc = array('您的头像未能通过审核','您的昵称未能通过审核','您的内心独白未能通过审核');
				            	$this->setSystemTj($sysname[$info['type']],-1);
				            	A('Home/Site')->sendSysmsg($info['uid'],$desc[$info['type']],$systype[$info['type']]);
				            }else{
				            	if($info['type'] == 0){ //头像
				            		$name['avatar'] = $info['text'];
				            		$re = $modUser -> where("id=".$info['uid']) -> save($name);
				            		if(!$re){
				            			$errorids[] = $id;
				            		}else{
				            			$model -> where('uid ='.$info['uid'].' and isavatr = 1')->setField('isavatr',0);
				            			$model -> where('photoid ='.$info['photoid'])->setField('isavatr',1);
				            			$this->setSystemTj('avatarFlag',-1);
				            		}	
				            	}
				            	if($info['type'] == 1){ //昵称
				            		$name['user_nicename'] = $info['text'];
				            		$re = $modUser -> where("id=".$info['uid']) -> save($name);
				            		if(!$re){
				            			$errorids[] = $id;
				            		}else{
				            			$this->setSystemTj('nicknameFlag',-1);
				            		}
				            	}
				            	if($info['type'] == 2){ // 内心独白
				            		$re = $modProfile -> where("uid=".$info['uid']) -> setField('monolog',$info['text']);
				            		if(!$re){
				            			$errorids[] = $id;
				            		}else{
				            			$this->setSystemTj('monoFlag',-1);
				            		}
				            	}
				            } 
	
						}
					}
			}
			if($errorids){
				$errorids = count($errorids)>1 ? implode(',', $errorids) : $errorids[0];
				$this -> error('编号'.$errorids.'操作失败！');
			}else{
				$this -> success('操作成功！');
			}
		}
		
		
	}
	
	//单个通过
	public function audit(){
		$id = I('post.id',0,'intval');
		$type = I('post.type',0,'intval');   //   
        if(!$id || !$type){
        	$this -> error('参数错误');
			exit;
        }
		
    	$info = M('Audit') -> where('id = '.$id) -> find(); 
    	$uid = $info['uid']; //uid
    	$audittype = $info['type'];
    	
    	
		if( $type == 1 ){   //待审 -> 通过
			
			$re = M('Audit') -> where('id = '.$id) -> setField('status',1);
			if($re){
				if($audittype == 0){ //头像
					$data['avatar'] = $info['text'];
					$model = M('Users');
					$where['id'] = $uid; 
					A("Home/Site")->newbchange($uid,1);
				}
			
				if($audittype == 1){ //昵称
					$data['user_nicename'] = $info['text'];
					$model = M('Users');
					$where['id'] = $uid; 
				}
				
				if($audittype == 2){ //内心独白
					$data['monolog'] = $info['text'];
					$model = M('UserProfile');
					$where['uid'] = $uid; 
				}
				
				$res = $model -> where($where) -> save($data);

				if($res !== false){
					if($audittype == 0){ //头像
						$mod = M('UserPhoto');
						$mod -> where('uid ='.$uid.' and isavatr = 1') -> setField('isavatr',0);
						$mod -> where('photoid ='.$info['photoid']) -> setField('isavatr',1);
						$this -> setSystemTj('avatarFlag',-1);						
					}
					
					if($audittype == 1){ //昵称
						$this -> setSystemTj('nicknameFlag',-1);
					}
					if($audittype == 2){ // 内心独白
						$this->setSystemTj('monoFlag',-1);
					}
					$this -> ajaxReturn(1);       				
				}
			}
		}
			
		if($type == 2){   //待审 -> 未通过
			$re = M('Audit') -> where('id = '.$id) -> setField('status',2);
			if($re){
				if($audittype == 0){   //头像审核未通过
					$desc = '您的头像未能通过审核';
					$msgType = 12;
					$this -> setSystemTj('avatarFlag',-1);	
				}
				
				if($audittype == 1){   //昵称未通过
					$desc = '您的昵称未能通过审核';
					$msgType = 11;
					$this -> setSystemTj('nicknameFlag',-1);
				}
				
				if($audittype == 2){   //内心独白未通过
					$desc = '您的内心独白未能通过审核';
					$msgType = 13;
					$this->setSystemTj('monoFlag',-1);
				}
				//sendSysmsg($uid,$body,$type)  11=>'昵称审核',12=>'头像审核',13=>'内心独白审核',14=>'相册审核',15=>'评论审核',
				A('Home/Site') -> sendSysmsg($uid,$desc,$msgType); 
				
				$this -> ajaxReturn(2);
			}
		}	
			      
	}
	
	
	
	
	
	
}
