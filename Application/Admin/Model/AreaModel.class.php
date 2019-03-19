<?php
namespace Admin\Model;
use Think\Model;
/**
 * 用户操作
 */
class AreaModel extends Model {
	private $_city_depth;
    //完成
    protected $_auto = array (
        array('rootid','intval',3,'function'),  //父级id
        array('depth','intval',3,'function'),  //深度
        array('areaname','htmlspecialchars',3,'function'),  //城市名称
        array('flag','intval',1,'function'),  //标志
        array('orders','intval',3,'function'),  //排序
        array('spreadname','htmlspecialchars',1,'function'),  //后缀名称
     );
    //验证
    protected $_validate = array(
        array('areaname', '', '已存在相同的城市名', 1, 'unique',3),
    );

    /**
     * 获取列表
     * @return array 列表
     */
    public function loadList($where = array(), $limit = 0){

        $data   = $this->table("__AREA__ as A")
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

        return $this->table("__AREA__ as A")
                    ->where($where)
                    ->count();
    }

    /**
     * 获取信息
     * @param int $areaId ID
     * @return array 信息
     */
    public function getInfo($areaId = 1)
    {
        $map = array();
        $map['areaid'] = $areaId;
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
        return $this->table("__AREA__ as A")
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
		$_POST["flag"] = 1;
		$this->_city_depth = 0;
		$this->getCityDepth($_POST["rootid"]);
		$_POST["depth"] = $this->_city_depth;

        $data = $this->create();
        if(!$data){
            return false;
        }
        if($type == 'add'){
            return $this->add();
        }
        if($type == 'edit'){
            if(empty($data['areaid'])){
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
     * 删除信息
     * @param int $areaId ID
     * @return bool 删除状态
     */
    public function delData($areaId)
    {
		return $this->delCityRecursion($areaId);
    }

	private function getCityDepth($rootId){
		if($rootId < 1){
			return false;
		}

		$this->_city_depth ++;
		$newRootId = $this->table("__AREA__")
					->where("areaid={$rootId}")
                    ->getField('rootid');
		if($newRootId > 0){
			$this->getCityDepth($newRootId);
		}

		return true;
	}

	private function delCityRecursion($cityId){
		$areaModel = $this->table("__AREA__");
		$cityList = $areaModel->field("areaid")
					->where("rootid={$cityId}")
                    ->select();

		if(!empty($cityList)){
			foreach($cityList as $cityItem){
				$newAreaId = $cityItem['areaid'];
				$this->delCityRecursion($newAreaId);
			}
		}

		return $areaModel->where("areaid={$cityId}")->delete();
	}
}
