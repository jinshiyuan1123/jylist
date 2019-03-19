<?php
	namespace Admin\Model;
	use Think\Model;
	/**
	 * 
	 */
	 
	
	class SystemMsgModel extends Model{
		
		protected $_auto = array(
			array('time','time',1,'function'),
		);
		
		protected $_validate = array(
			array('msg_content','require','消息内容必须填写',1),
			array('uid','require','用户ID必须填写',1),
		);
		
		
		public function countList($where=array()){
			return $this -> where($where) -> order($order) -> count();
 		}
		
		public function loadList($where=array(),$limit=0,$order = 'msg_id desc'){
			return $this -> where($where) -> order($order) -> limit($limit) ->select();
		}
		
		
		public function saveData(){
			$data = $this -> create();
			if(!$data){
				return false;
			}
			return $this -> add();			
		}
		
		//删除单条数据
		public function delData($msg_id){
			$where['msg_id'] = $msg_id;
			return $this -> where($where) -> delete();
		}
		
		//批量删除
		public function delMsgs($ids){
			$where['_string'] = 'msg_id in('.$ids.')';
			return $this -> where($where) -> delete();
		}
		
		
	}
