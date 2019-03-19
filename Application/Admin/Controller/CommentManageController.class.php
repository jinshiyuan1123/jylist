<?php
namespace Admin\Controller;
use Admin\Controller\AdminController;
/**
 * 评论管理
 */
class CommentManageController extends AdminController{
	
	public function _infoModule(){
 		$data = array(
			'info' => array(
				'name' => '评论管理',
				'description' => '管理用户的照片评论',
			),
			'menu' => array(
				array(
					'name' => '评论列表',
	                'url' => U(''),
	                'icon' => 'list',
				),		
//				array(
//					'name' => '审核头像',
//	                'url' => U('Admin/Audit/AuditAvatar'),
//	                'icon' => 'list',
//				),
			),
		);
		return $data; 		
  	}
	
	public function index(){
		$breadCrumb = array('评论审核列表管理' => U());
		
		$keyword = I('request.keyword','');
		$flag = I('request.flag');
		if(!empty($flag)){
			if($flag == -1){
				$where['flag'] = 0;
			}else{
				$where['flag'] = $flag;
			} 			
		}
		$pageMaps['flag'] = $flag;
		$pageMaps['keyword'] = $keyword;
		if($keyword){
			$where['_string'] = 'uid = '.$keyword.' or photoid = '.$keyword;
		}
		
		$model = M('Comment');
		$count = $model -> where($where) -> count();//查询满足条件的几条
		
		$limit = $this -> getPageLimit($count,20);  //获取每页要显示的条数 
		$list = $model -> where($where) -> limit($limit) -> order('time desc') -> select();
		
		$ids = array();
		foreach($list as $k => $v ){
			$ids[] = $v['uid'];
			$photoids[] = $v['photoid'];
		}
		$ids = array_unique($ids);
		$ids = join($ids, ',');
		$photoids = array_unique($photoids);
		$photoids = join($photoids, ',');
		
		
		$image = D('Users') -> getPhoto($photoids);
		
		$result = D('Users') -> getNicename($ids);
		$this -> assign('page',$this->getPageShow($pageMaps));
		$this -> assign('pageMaps',$pageMaps);
		$this -> assign("image",$image);
		$this -> assign('list',$list);
		$this -> assign('result',$result);
		$this -> assign('name','评论');
		$this -> assign('breadCrumb',$breadCrumb);
		$this -> getCount();
		$this -> adminDisplay('index');
	}
	private function getCount(){
		$iscount = I('get.iscount',0,'intval');
		if(!$iscount) return false;
		$where['flag'] = 0;
		$count =  M('Comment') -> where($where) -> count();
		M('Systemtj')->where('id = '.M('Systemtj')->getField('id'))->setField('commentFlag',$count);
	}
	
	
	//单条删除
	public function del(){
		$id = I('post.data',0,'intval');
		if(!$id){
			$this -> error("参数不能为空");
		}
		$info =  M('Comment') -> where("id =".$id) -> find();
		$res = M("Comment") -> where("id =".$id) -> delete();
		if($res){
			if($info['flag']==0){
				$this->setSystemTj('commentFlag',-1);
			}
			$this -> success("数据删除成功");
		}else{
			$this -> error('数据删除失败');
		}
		
	}
	
	
	
	//批量操作
	public function batchAction(){
		$ids = I("post.ids",'');
		$type = I('post.type','');
		if(empty($ids)||empty($type)){
			$this -> error($type);
		}
		
		$ids = count($ids) > 1 ? join(',', $ids) : $ids[0];
		$where["_string"] = "id in(".$ids.")";
		$model = M('Comment');
		//删除
		if($type == 3){
			$flag =  M('Comment') -> where($where) -> getField('flag',true);
			$res = $model -> where($where) -> delete();
			if($res){
				$num = 0;
				foreach ($flag as $v){
					if($v==0) $num++;
				}
				if($num) $this->setSystemTj('commentFlag',(-1)*$num);
				$this -> success("删除数据成功");
			}else{
				$this -> error("删除数据失败");
			}			
		}
		
		// ——>不通过
		if($type == 2){
			$data['flag'] = 2;
			$uids = $model ->field('uid,photoid,content') -> where($where) ->  select();
			$res = $model -> where($where) -> save($data);
			$uid = array();
		 	if($res){		 		
				$msgType = 15;
		 			
		 		foreach($uids as $k => $v){
		 			if($v['flag']==0){
		 				$this->setSystemTj('commentFlag',-1);
		 				$user_id = $v['uid'];
		 				$title = M('UserPhoto')->where('photoid = '.$v['photoid']) ->getField('title') ;
		 				$desc = '照片【'.$title.'】，您的评论未能通过审核';
		 				//sendSysmsg($uid,$body,$type)  11=>'昵称审核',12=>'头像审核',13=>'内心独白审核',14=>'相册审核',15=>'评论审核',
		 				A('Home/Site') -> sendSysmsg($user_id,$desc,15);
		 			}
		 							
				}
				$this -> success('操作成功');
			}else{
				$this -> error('操作失败');
			}				
		}
		
		// ——>通过
		if($type == 1){
		 $data['flag'] = 1;
		 $uids = $model -> field('uid,photoid,content,flag') ->where($where) -> select();
		 $result = $model -> where($where) -> save($data);			
		if($result){
			$num = 0;
			foreach($uids as $k => $v){
				if($v['flag']==0){
					$num++;
				}					
			}
			if($num) $this->setSystemTj('commentFlag',$num);
			
			$this -> success('操作成功');
		}else{
			$this -> error('操作失败');
		}
	  }
	}

	//单条    通过or不通过
	public function single(){
		$id = I('post.id',0,'intval');	
		$type = I('post.type',0,'intval');	
		$uid = I('post.uid',0,'intval');
		
		// ->通过
		if($type == 1){  
			$res = M('Comment') -> where('id = '.$id) -> setField('flag',1);
			if($res){
				$this->setSystemTj('commentFlag',-1);
				$this -> success();
			}
		}
		// ->未通过 
		if($type == 2){  
			$res = M('Comment') -> where('id = '.$id) -> setField('flag',2);
			if($res){
				$this->setSystemTj('commentFlag',-1);		
			   $title = M('UserPhoto')->where('photoid = '. M('Comment') ->where('id = '.$id)->getField('photoid')) ->getField('title') ;
			   $desc = '照片【'.$title.'】，您的评论未能通过审核';
				$msgType = 15;			
				A('Home/Site') -> sendSysmsg($uid,$desc,$msgType); 
				$this -> success();
			}
		}
	}
	
	
	
}