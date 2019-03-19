<?php

namespace Agent\Controller;

use Agent\Controller\AgentController;

/**

 * 栏目管理

 */

class TuigController extends AgentController

{

	

    /**

     * 当前模块参数

     */

    public function _infoModule()

    {

        $data = array(

        	'info' => array(

        		'name' => '推广管理',

                'description' => '管理网站推广信息',

            ),

            'menu' => array(

                array(

                	'name' => '专属推广',

                    'url' => U('index'),

                    'icon' => 'list',

                ),

                array(

					'name' => '推广记录',

					'url' => U('tglist'),

					'icon' => 'list',			

				),    
/*
				array(

					'name' => '申请提现',

					'url' => U('tixian'),

					'icon' => 'list',			

				),  
				
				array(

					'name' => '修改密码',

					'url' => U('changepwd'),

					'icon' => 'list',			

				),  */

            )

        );

		

        return $data;

    }

    

   

    

    /**

     * 专属推广

     */

    public function index(){

		$agentUid = $_SESSION['agent_user']['id'];
		$ticket= M("user_ticket")->where("uid=".$agentUid." and time >".$sctime)->order('id desc')->getField('ticket');
		if(!$ticket){
			$ticket=A('Home/Api')->doticket(1,$agentUid);
			}
		$ewmPicUrl='https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$ticket;
		/*
		//如果文件修改时间大于10小时，则删除，重新生成

		if(file_exists($ewmPicLocal)){

			$mtime = time() - filemtime($ewmPicLocal);

			if($mtime > 3600){

				unlink($ewmPicLocal);

			}

		}



		//生成二维码图片

		$codeUrl = "http://".$_SERVER['SERVER_NAME'] . "/index.php?s=/Home/Public/Myrec/uid/". $agentUid ."/log/1.html" ;

		if(!file_exists($ewmPicLocal)){

			require_once ROOT_PATH."WxpayAPI_php_v3/phpqrcode.php";

			$Qcode = new \QRcode();

			

			if(!is_dir($ewmPicDir)){

				mkdir($ewmPicDir);

			}

			$Qcode->png($codeUrl,$ewmPicLocal,QR_ECLEVEL_L,6,true);

		}

		*/

		$this->assign('ewmimg',$ewmPicUrl);	

		$this->assign('url',$codeUrl);

        $this->agentDisplay();

    }
	/**
	修改密码
	*/
	public function changepwd(){
		$this->agentDisplay();
		}
	public function changepwding(){
		$pwd=I('post.pwd');
		if(!empty($pwd)){
			$agentUid = $_SESSION['agent_user']['id'];
			$users=M('users');
			$acc=$users->where('id='.$agentUid)->getField('user_login');
			$pwd=md5($acc.$pwd.C('PWD_SALA'));
			$res=$users->where('id='.$agentUid)->setField('user_pass',$pwd);
			if($res){
				$this->success('修改完成');
				}else{
				$this->success('修改失败');	
					}
			}
		}
    /**

     * 专属推广

     */

    public function tglist(){

    	$agentUid = $_SESSION['agent_user']['id'];	

    	$stime = I('post.stime','','trim');

    	$etime = I('post.etime','','trim');

        $pageMaps = array("stime"=>$stime , "etime"=>$etime);

    	$UserYReg = M('UserYReg');

    	$chongzhi = M('ChongzhiLog');   

    	$modarr = array(

    			$UserYReg,

    			$UserYReg,

    			M('Users'),

    			$chongzhi,

    			$chongzhi

    	);

    	

    	$wherearr = array(

    		 array('puid'=>$agentUid,'subscribe'=>0,'time_name'=>'time'),

    		 array('puid'=>$agentUid,'subscribe'=>-1,'time_name'=>'time'),	

    		 array('parent_id'=>$agentUid,'time_name'=>'create_time'),

    		 array('parent_id'=>$agentUid,'status'=>1,'paytype'=>1,'time_name'=>'time'),

    		 array('parent_id'=>$agentUid,'status'=>1,'paytype'=>2,'time_name'=>'time'),

    	);

    	$tgkl_ids = C('tgkl_ids');

    	$tgkl_config = C('tgkl_config');

    	foreach ($modarr as $k=>$v){

    	   $where = $wherearr[$k];

    	   $time_name = $wherearr[$k]['time_name'];

    	   unset($where['time_name']);

    		if($stime&&$etime){

    			$stime = strtotime($stime);

    			$etime = strtotime($etime);

    			$where[$time_name]  = array('gt',$stime);

    			$where[$time_name]  = array('lt',$etime);

    		}    		

    		$count[$k] = $v->where($where)->count();

    		

    		if(isset($where['paytype'])){

    			$psum[$k] = $v->where($where)->sum('fee');

    		}

    		

    		if(in_array($agentUid, $tgkl_ids)&&isset($tgkl_config[$k])){

    			if(isset($count[$k])&&$count[$k]) $count[$k] = $count[$k] - ceil($count[$k]*($tgkl_config[$k]/100));

    			if(isset($psum[$k])&&$psum[$k]) $psum[$k] = $psum[$k] - ceil($psum[$k]*($tgkl_config[$k]/100));

    		}

    	}

        

    	$this->assign('count',$count);

    	$this->assign('psum',$psum);

        $this->assign('pageMaps',$pageMaps);

    	$this->agentDisplay('info');

    }



	public function tixian(){

		$agentUid = $_SESSION['agent_user']['id'];	

		$info = M('Users')->field('user_status,weixin,money')->where(array('id'=>$agentUid))->find();

		if(!$info) $this->error('登录超时，请退出重试!');

		$info['uid'] = $agentUid;

		if($info['user_status'] == 0)	$this->error('您的账号异常，不能提现！');

		$info['tmoney'] = intval($info['money']/C('moneyBL'));

		

		if(IS_POST){

			$type = I('post.type',0,'intval');

			$money = I('post.money',0,'intval');

			if(!$type&&!$money) $this->error('系统繁忙，请稍候再试！');

			switch ($type){

				case 1:

					$info['weixin']?'':$this->error('请关注公众号'.C('gzhcode').',并在微信内提现！');

				break;		

				case 2:

					$zfb_account = I('post.zfb_account','','trim');

					$zfb_lxr = I('post.zfb_lxr','','trim');

					if(!$zfb_account||!$zfb_lxr) $this->error('请填写完整！');

					S('ZfbInfo_'.$info['uid'],array('zfb_account'=>$zfb_account,'zfb_lxr'=>$zfb_lxr),24*3600);

				break;

				case 3:

					$mob = I('post.mob','','trim');

					if(!$mob||!$this->isTel($mob)) $this->error('请输入正确手机号！');

					S('MobInfo_'.$info['uid'],$mob,24*3600);

				break;

			}

			

	        if($money>$info['tmoney']){

	        	$this->error('您的余额不足！');

	        }	        

	        if($money<C('tx_qt_money')){

	        	$this->error('提现金额必须大于起提金额'.C('tx_qt_money').'元');

	        }

	        $mod = M("Tixian");

			$res = $mod -> where("uid = ".$info['uid']) -> order('time desc') -> find();//查找最近的提现记录				

			if($res){

				if($res['status'] == 2){   

					$this->error('您还有一笔正在处理的提现申请哦~');

					

				}					

				if( date('Ymd',$res['time']) == date('Ymd',time()) ){

					$this->error("亲~，一天只能提现一次哦");

					

				}													

			}

			

			$bodyarr = array(1=>'微信提现',2=>'支付宝提现',3=>'话费充值');

			$body = $bodyarr[$type]."，提现金额".$money."元";

			$data =array('uid'=>$info['uid'],'weixin'=>$info['weixin'],'fee'=>$money,'body'=>$body,'time'=>time(),'type'=>$type,'status'=>2,'zfb_account'=>$zfb_account,'zfb_lxr'=>$zfb_lxr,'mob'=>$mob);

		    

			$mod->startTrans();		

			$result = $mod -> add($data);

			if($result){

				$mod->commit();

			    $re = $this->changemoney($info['uid'], (-1)*$money*C('moneyBL'),101,$body,"","",0,get_client_ip(),0,101);				

			    if($re>=0){

			    	$this->setSystemTj(array('txFlag'=>1,'txFee'=>$money));		    	

			    	if($type==1){

			    		if(C('tx_wx_money')==0||$money<C('tx_wx_money')){

			    			$msg = $this->new_tixian($result);

			    			if($msg==1){

			    				$this->setSystemTj(array('txFlag'=>-1,'txFee'=>(-1)*$money,'txTotalFee'=>$money,'txTotalFeeDay'=>$money));

			    				$mod->where('id = '.$result)->setField('body',$body.'，系统已自动发放！');

			    				$this->success('提现成功，请注意查收！');

			    			}else{

			    				$this->error($msg);

			    			}

			    		

			    		    exit;

			    		}

			    	}

			    		$this->success('提交成功，待审核！');

			    }else{

			    	$mod ->where('id = '.$result)->delete();

			    	$this->error('系统繁忙，请稍候再试！');

			 

			    }

			    

			}else{

				$mod->rollback();

			}

			

		}else{

			$mod = M("Tixian");

			//S('ZfbInfo_'.$info['uid'],);

			$zfbInfo = $mod->cache('ZfbInfo_'.$info['uid'],24*3600)->field('zfb_account,zfb_lxr')->where('uid ='.$info['uid']." and type = 2")->order('id desc')->find();

			$MobInfo = $mod->cache('MobInfo_'.$info['uid'],24*3600)->field('mob')->where('uid ='.$info['uid']." and type = 1")->order('id desc')->find();

			

			$this->assign ( 'zfbInfo', $zfbInfo );

			$this->assign ( 'MobInfo', $MobInfo['mob']);

			$this->assign ( 'info', $info);

			$this->agentDisplay();

		}

	}
}



