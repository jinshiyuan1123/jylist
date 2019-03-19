<?php
namespace Admin\Model;
use Think\Model;
/**
 * 后台菜单
 */
class BxcatModel extends Model {
	
	
	
	
	
	 /**
     * 获取列表
     * @return array 列表
     */
    public function loadList($where = array(), $classId=0){
        import("Common.Util.Category");
        $data = $this->loadData($where);
        $cat = new \Common\Util\Category(array('class_id', 'parent_id', 'name', 'cname')); //分类id 上级id 分类名称 格式化后分类名称
        $data=$cat->getTree($data, intval($classId));
		//print_r($data);
       
		
        return $data;
    }

    /**
     * 获取列表(前台调用)
     * @return array 列表
     */
    public function loadData($where = array(), $limit = 0 ,$order=" class_id ASC"){
        $pageList = $this->where($where)->limit($limit)->order($order)->select();
        $list=array();
        if(!empty($pageList)){
            $i = 0;
            foreach ($pageList as $key=>$value) {
                $list[$key]=$value;
              //  $list[$key]['curl'] = D('DuxCms/Category')->getUrl($value);
                $list[$key]['i'] = $i++;
            }
        }
        return $list;
    }

    /**
     * 获取栏目数量
     * @return array 列表
     */
    public function countList($where = array()){
        return $this->where($where)->count();
    }
	
	
	 /**
     * 删除信息
     * @param int $classId ID
     * @return bool 删除状态
     */
    public function delData($classId)
    {
        $map = array();
        $map['class_id'] = $classId;
        return $this->where($map)->delete();
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
            $status = $this->add();
            if($status){
                $this->commit();
            }else{
                $this->rollback();
            }
            return $status;
        }
        if($type == 'edit'){
            $status = $this->where('class_id='.$data['class_id'])->save();
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
	
	public function getInfo($classId){
		 $map['class_id'] = $classId;
		return $this->where($map)->find();
	}
	
	
	

}