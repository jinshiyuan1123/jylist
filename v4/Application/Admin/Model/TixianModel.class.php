<?php
namespace Admin\Model;
use Think\Model;
/**
 * 后台菜单
 */
class TixianModel extends Model {
	
 	
	
	
	/*
	 protected $_auto = array (
 			array('bx_name','htmlspecialchars',3,'function'),
 			array('bx_type','htmlspecialchars',3,'function'),
 			array('description','htmlspecialchars',3,'function'),
	        array('create_time','time',1,'function'), 
 	        );
	 */

    
    /**
     * 获取数量
     * @return int 数量
     */
    public function countList($where = array()){
    	return $this->table("__TIXIAN__ as A")
    	->join('__USERS__ as B ON A.uid = B.id')
    	->where($where)
    	->count();
    }
    
     /**
     * 获取列表
     * @return array 列表
     */
    public function loadList($where = array(), $limit = 0){
    	$data   = $this->table("__TIXIAN__ as A")
    	->join('__USERS__ as B ON A.uid = B.id')
    	->field('A.*,B.user_login,B.money,B.jifen,B.id as uid')
    	->where($where)
    	->limit($limit)
    	->order('A.id desc')
    	->select();
    	return $data;
    
    }
    
    
 
	
	/**
     * 更新信息
     * @param string $type 更新类型
     * @return bool 更新状态
     */
    public function saveData($type = 'add'){
        
        //事务总表处理
        $this->startTrans();
        
      
        //分表处理
        $data = $this->create();
     
        if(!$data){
            $this->rollback();
            return false;
        }
        if($type == 'add'){
        	
            //$this->content_id = $contentId;
            $status = $this->add();
           
            if($status){
                $this->commit();
            }else{
                $this->rollback();
            }
            return $status;
        }
        if($type == 'edit'){ 
            $status = $this->where('id='.$data['id'])->save();
            if($status === false){
                $this->rollback();
                return false;
            }
            $this->commit();
            return true;
        }
        $this->rollback();
        return false;
    }
	
	 public function delData($contentId)
    {
        $this->startTrans();       
        $map = array();
        $map['id'] = $contentId;
        $status = $this->where($map)->delete();
        if($status){
            $this->commit();
        }else{
            $this->rollback();
        }
        return $status;
    }
   
    public function editData($contentId,$status)
    {
    	$this->startTrans();
    	$map = array();
    	$map['id'] = $contentId;
    	$status = $this->where($map)->save(array('status'=>$status));
    	if($status){
    		$this->commit();
    	}else{
    		$this->rollback();
    	}
    	return $status;
    }
    
   
    
    
    
	

}