<?php
namespace Admin\Model;
use Think\Model;
/**
 * 后台菜单
 */
class BxlistModel extends Model {
	
 	protected $_auto = array (
 			array('bx_name','htmlspecialchars',3,'function'),
 			array('bx_type','htmlspecialchars',3,'function'),
 			array('description','htmlspecialchars',3,'function'),
	        array('create_time','time',1,'function'), 
 	        );
	

	 /**
     * 获取数量
     * @return int 数量
     */
    public function countList($where = array()){
        //$where['C.app'] = 'Admin';
        return $this->where($where)
                    ->order($order)
                    ->count();
    }
	
	public function loadList($where = array(),$limit=0,$order='id desc'){
		return $this->where($where)
                    ->order($order)
                    ->limit($limit)
					->select();
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
   
   
    
	

}