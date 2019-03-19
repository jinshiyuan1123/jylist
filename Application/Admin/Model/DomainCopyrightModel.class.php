<?php
namespace Admin\Model;
use Think\Model;
/**
 * 用户操作
 */
class DomainCopyrightModel extends Model {
    //完成
    protected $_auto = array (
        array('domain','htmlspecialchars',3,'function'),  //域名
        array('ip','htmlspecialchars',3,'function'),  //IP
        array('qq','htmlspecialchars',3,'function'),  //QQ
        array('mobile','htmlspecialchars',1,'function'),  //手机
        array('install_time','time',1,'function'),  //注册时间
     );
    //验证
    protected $_validate = array(
        array('domain','1,20', '域名只能为1~20个字符', 1 ,'length',3),
		array('ip','1,20', 'IP只能为1~20个字符', 1 ,'length',3),
		array('qq','1,20', 'QQ只能为1~20个字符', 1 ,'length',3),
		array('mobile','1,20', '手机只能为1~20个字符', 1 ,'length',3),
    );

    /**
     * 获取列表
     * @return array 列表
     */
    public function loadList($where = array(), $limit = 0){

        $data   = $this->table("__DOMAIN_COPYRIGHT__ as A")
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

        return $this->table("__DOMAIN_COPYRIGHT__ as A")
                    ->where($where)
                    ->count();
    }

    /**
     * 获取信息
     * @param int $domainId ID
     * @return array 信息
     */
    public function getInfo($domainId = 1)
    {
        $map = array();
        $map['id'] = $domainId;
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
        return $this->table("__DOMAIN_COPYRIGHT__ as A")
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
     * @param int $domainId ID
     * @return bool 删除状态
     */
    public function delData($domainId)
    {
        $map = array();
        $map['id'] = $domainId;
        return $this->where($map)->delete();
    }
}
