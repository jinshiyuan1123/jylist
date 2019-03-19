<?php
	namespace DuxCms\Model;
	use Think\Model;
	
	class GiftModel extends Model{
		//自动完成
		protected $_auto = array(
			array('create_time','time',1,'function'),
		);
		//自动验证	
		protected $_validate = array(
			array('gift_name','require','礼物名字不能为空',1),
		);
		
		//计算总数
		public function countList($where = array()){
	        return $this->where($where) -> order($order) ->count();
   		}
		
		
		public function loadList($where = array(),$limit=0,$order='gift_id desc'){
			return $this -> where($where) ->order($order) -> limit($limit) -> select();
		}
		
			
		//操作类型 包含 增、改
		public function saveData($type = 'add'){
        	$data = $this->create();
        	if(!$data){
            	return false;
        	}
			
	        if($type == 'add'){
	            return $this->add();
	        }
			
	        if($type == 'edit'){
	            if(empty($data['gift_id'])){
	                return false;
	            }
	            $status = $this->save();
	            if($status === false){
	                return false;
	            }
	            return true;
	        }
	        return false;
	    }
		
		
		//删除数据
		public function delData($gift_id){
			$where['gift_id'] = $gift_id;
			return $this -> where($where) -> delete();
		}
		
		
		
		//获取信息方法
		public function getInfo($gift_id){
			$where['gift_id'] = $gift_id;
			return $this -> where($where) -> find();
		}
		
		
		
		
		
		
		
		
		
		
		
		
		
		
			
	}	