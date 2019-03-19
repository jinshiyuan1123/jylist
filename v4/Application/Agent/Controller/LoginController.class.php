<?php
namespace Agent\Controller;
use Agent\Controller\AgentController;
/**
 * 登录页面
 */
class LoginController extends AgentController {

	/**
     * 登录页面
     */
    public function index(){
        if(!IS_POST){
            $this->display();
        }else{
            $userName = I('post.username');
            $passWord = I('post.password');

			$verify = new \Think\Verify();
			if(!$verify->check($_POST['captcha'])){
				$this->error("请输入正确的验证码");
			}
			
            if(empty($userName)||empty($passWord)){
                $this->error('用户名或密码未填写！');
            }
            //查询用户
            $map = array();
            $map['user_login'] = $userName;
			$map['tuiguang'] = 1;
            $userInfo = D('AgentUser')->getWhereInfo($map);
			
            if(empty($userInfo)){
                $this->error('登录用户不存在！');
            }
            if(!$userInfo['user_pass']){
                $this->error('该用户已被禁止登录！');
            }

			
            if($userInfo['user_pass'] != md5 ( $userName . $passWord . C ( 'PWD_SALA' ) )){
                $this->error('您输入的密码不正确！');
            }
            $model = D('AgentUser');
            if($model->setLogin($userInfo['id'])){
                $this->success('登录成功！', U('Tuig/index'));
            }else{
                $this->error($model->getError());
            }
            

        }
    }
    /**
     * 退出登录
     */
    public function logout(){
        D('AgentUser')->logout();
        session('[destroy]');
        $this->success('退出系统成功！', U('index'));
    }
}

