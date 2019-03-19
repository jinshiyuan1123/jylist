<?php
namespace Admin\Model;
use Think\Model;
/**
 * 用户操作
 */
class ServerVersionModel extends Model {
    //完成
    protected $_auto = array (
        array('package_name','htmlspecialchars',3,'function'),  //域名
        array('version_num','htmlspecialchars',3,'function'),  //IP
        array('version_intro','htmlspecialchars',3,'function'),  //QQ
        array('pub_time','time',1,'function'),  //注册时间
     );
    //验证
    protected $_validate = array(
        array('package_name','1,20', '包名只能为1~20个字符', 1 ,'length',3),
		array('version_num','1,20', '版本号只能为1~20个字符', 1 ,'length',3),
		array('version_intro','1,20', '版本说明只能为1~20个字符', 1 ,'length',3),
    );

    /**
     * 获取列表
     * @return array 列表
     */
    public function loadList($where = array(), $limit = 0){

        $data   = $this->table("__SERVER_VERSION__ as A")
                    ->field('A.*')
                    ->where($where)
                    ->limit($limit)
                    ->select();
        return $data;
    }

    /**
     * 获取数量
     * @return int 数量
     */
    public function countList($where = array()){

        return $this->table("__SERVER_VERSION__ as A")
                    ->where($where)
                    ->count();
    }

    /**
     * 获取信息
     * @param int $versionId ID
     * @return array 信息
     */
    public function getInfo($versionId = 1)
    {
        $map = array();
        $map['id'] = $versionId;
        return $this->where($map)->find();
    }

    /**
     * 获取信息
     * @param array $where 条件
     * @return array 信息
     */
    public function getWhereInfo($where)
    {
		//echo $where;
        return $this->table("__SERVER_VERSION__ as A")
                    ->field('A.*')
                    ->where($where)
                    ->find();
    }

    /**
     * 更新信息
     * @param string $type 更新类型
     * @return bool 更新状态
     */
    public function saveData($type = 'add'){
        $data = $this->create();
        if(!$data){
            return false;
        }
        if($type == 'add'){
            return $this->add();
        }
        if($type == 'edit'){
            if(empty($data['id'])){
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

    /**
     * 更新权限
     * @param string $type 更新类型
     * @return bool 更新状态
     */
    public function savePurviewData(){
        $this->_auto = array();
        $data = $this->create();
        $this->menu_purview = serialize($this->menu_purview);
        $this->base_purview = serialize($this->base_purview);
        $status = $this->save();
        if($status === false){
            return false;
        }
        return true;
    }

    /**
     * 删除信息
     * @param int $versionId ID
     * @return bool 删除状态
     */
    public function delData($versionId)
    {
        $map = array();
        $map['id'] = $versionId;
        return $this->where($map)->delete();
    }
}
