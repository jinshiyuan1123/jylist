<?php
namespace Agent\Model;
use Think\Model;
/**
 * 用户操作
 */
class AgentUserModel extends Model {
	public function __construct($name='',$tablePrefix='',$connection=''){
		$name = 'users';
		parent::__construct($name , $tablePrefix , $connection);
	}

    //完成
    protected $_auto = array (
        array('user_login','htmlspecialchars',3,'function'),  //用户名
        array('user_nicename','htmlspecialchars',3,'function'),  //昵称
        array('weixin','htmlspecialchars',3,'function'),  //微信
        array('user_pass','md5',1,'function'),  //新增时密码
        array('user_pass','',2,'ignore'),  //编辑时密码
        array('user_status','intval',3,'function'),  //状态
        array('create_time','time',1,'function'),  //注册时间
     );
    //验证
    protected $_validate = array(
        array('user_login','1,20', '用户名称只能为1~20个字符', 1 ,'length',3),
        array('user_nicename', '', '已存在相同的昵称', 1, 'unique',3),
        array('weixin','', '已存在相同的微信', 1 ,'unique',3),
        array('user_pass', '4,250', '请输入最少4位密码', 1, 'length',1),
    );

    /**
     * 获取列表
     * @return array 列表
     */
    public function loadList($where = array(), $limit = 0){

        $data   = $this->table("__USERS__ as A")
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

        return $this->table("__USERS__ as A")
                    ->where($where)
                    ->count();
    }

    /**
     * 获取信息
     * @param int $userId ID
     * @return array 信息
     */
    public function getInfo($userId = 1)
    {
        $map = array();
        $map['id'] = $userId;
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
        return $this->table("__USERS__ as A")
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
            if (!empty($this->user_pass)){ //密码非空，处理密码加密
                $this->user_pass = md5 ( $this->user_login . $this->user_pass . C ( 'PWD_SALA' ) );
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
     * @param int $userId ID
     * @return bool 删除状态
     */
    public function delData($userId)
    {
        $map = array();
        $map['id'] = $userId;
        return $this->where($map)->delete();
    }

    /**
     * 登录用户
     * @param int $userId ID
     * @return bool 登录状态
     */
    public function setLogin($userId)
    {
        $this->tableName = 'users';

		// 更新登录信息
        $data = array(
            'id' => $userId,
            'last_login_time' => NOW_TIME,
            'last_login_ip' => get_client_ip(),
        );
        $this->save($data);
        //设置cookie
        $auth = array(
			'id' => $userId,
			'last_login_ip' => $data['last_login_ip']
        );
		//echo $userId;
        session('agent_user', $auth);
        session('agent_user_sign', data_auth_sign($auth));
        return true;
    }

    /**
     * 注销当前用户
     * @return void
     */
    public function logout(){
        session('agent_user', null);
        session('agent_user_sign', null);
    }

}
