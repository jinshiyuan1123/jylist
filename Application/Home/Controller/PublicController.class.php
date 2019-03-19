<?php

namespace Home\Controller;

use Home\Controller\SiteController;



/**

 * @author lxphp

 * http://lxphp.com

 * 锦尚中国源码论坛提供

 */



class PublicController extends SiteController {



	public function index() {

		if(C("onlywx")==1 && strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') === false){

		 $this->siteDisplay('jg_qzwxdk');

		exit;

		}

		

		if ($this -> uinfo) {

			redirect(U('Home/Index/index'));

			exit ;

		}

		$media = $this -> getMedia('会员注册');

		$this -> assign('media', $media);

		if(I('get.type',0,'intval')==2)

		$this -> dowxlogin('snsapi_userinfo');

		else

		$this -> dowxlogin('snsapi_base');

		//微信自动登录,只有此处！

		$this -> siteDisplay('reg_01');

	}



	public function log(){

		//dump($this->get_areaid_byname('丰台'));

	

	

	dump(S('reginfoolOpAwSTTP5HWBdARw3mxbEs29cY'));

		exit;

	

	$data[] = array('title'=>'个人消息通知1','description'=>'个人消息通知2','picurl'=>'http://www.yueai.me/v4/jiaocheng_03.jpg','url'=>'http://baidu.com');

	$re = A('Weixin')->makeTextImgbygm('olOpAwX50K4rdeEz0JImnJgBcfv0',$data);

	dump($re);

	//A('Weixin')->sendmb_geren('olOpAwX50K4rdeEz0JImnJgBcfv0','个人消息通知1','个人消息通知2','个人消息通知3',U('Home/User/index'));

		

		exit;

		dump(S('reginfoolOpAwSTTP5HWBdARw3mxbEs29cY'));

		exit;

		dump(S('reginfoolOpAwSTTP5HWBdARw3mxbEs29cY'));

		$this->changejifen(C('gz_jifen'),5,'邀请好友'.$re['user_nicename'].'获得',202821,1,1,get_client_ip());

	}



	public function dowxlogin($scope='snsapi_base') {



		if ($this -> is_weixin()) {



			$APPID = C('APPID');

			$SCRETID = C('SCRETID');



			if (!isset($_GET['code'])) {



				$backurl = $this -> get_url();

				$_SESSION['gourl'] = $backurl;

				$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $APPID . "&redirect_uri=" . urlencode($backurl) . "&response_type=code&scope=".$scope."&state=123#wechat_redirect";

				Header("Location: $url");

				exit ;

			} else {



				$code = $_GET['code'];

				$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $APPID . "&secret=" . $SCRETID . "&code=" . $code . "&grant_type=authorization_code";

				$re = $this -> curl_get_contents($url);



				$rearr = json_decode($re, true);

				$openid = $rearr['openid'];

				$asstoken = $rearr['access_token'];

				if (!$openid) {

					echo '获取openid失败，<a href="' . U("Index/index") . '">点击请返回重试</a>';

					exit ;

				}

								

				$rearr2 = A("Home/Api") -> getwxinfo($openid,0,$scope,$asstoken);

	

				$unionid = $rearr2['unionid'];

				if (!$rearr2['openid']) {

					echo '获取openid失败，<a href="' . U("Index/index") . '">点击请返回重试</a>01';

					exit ;

				}

				$usermod = M("Users");

				if ($unionid){

					$w['unionid'] = $unionid;

					$udb = $usermod -> where($w) -> find();

				}					

				if(!$udb){

					$w2['weixin'] = $openid;

					$udb = $usermod -> where($w2) -> find();

				}

				if (!is_array($udb)){//跳转到绑定注册页

					$data['weixin'] = $openid;

					$data['avatar'] = $rearr2["headimgurl"] ? $rearr2["headimgurl"] : 0;

					$data['user_nicename'] = $rearr2["nickname"] ? $rearr2["nickname"] : '没有昵称';

					$data['sex'] = $rearr2['sex'];

					$data['unionid'] = $rearr2['unionid'] ? $rearr2['unionid'] : 0;

					$data['subscribe'] = $rearr2['subscribe']?$rearr2['subscribe']:0;

					$data['country'] = $rearr2['country'];

					$data['province'] = $rearr2['province'];

					$data['city'] = $rearr2['city'];

					

					$provinceid = $this->get_areaid_byname($data['province']);	

			        $city = $this->get_areaid_byname($data['city']);

					$data ['provinceid']=$provinceid?$provinceid:0;	

					$data ['cityid']=$city?$city:0;

											

					$data['subscribe_time'] = $rearr2['subscribe_time']?$rearr2['subscribe_time']:0;								

					cookie("regopenid",$openid, 3600);

					cookie("regunionid",$unionid, 3600);

					S("reginfo" . $openid, $data, 3600);					

					$userymod = M("User_y_reg");

					$reyreg = $userymod->where("code='$openid'")->find();

					if($reyreg['puid']>0 && !cookie('yq')){

					cookie('yq',$reyreg['puid'],3600);

					}

					if(!$reyreg){

						$yregdata['code']=$openid;

						$yregdata['time']=time();

						$yregdata['data']= serialize($data);

						$puidcookie  =cookie('yq');

						if($puidcookie>0){	

							if($usermod->where("id=".$puidcookie)->count())					

							$yregdata['puid']=$puidcookie;

						}						

						$userymod->add($yregdata);

					}	

					if($data['subscribe']!=1  && $scope=='snsapi_base'){

						redirect(U('index',array('type'=>2)));

						exit;

					}

				

					redirect(U('Home/Public/reg',array('sex'=>$data['sex'])));

					exit;

					//$this -> assign('regdata', $data);

					//$this -> siteDisplay('reg_01');

					

				} else {//登录

					if(!$udb['unionid']&&$unionid)

					$usermod->where("id=".$udb['id'])->setField('unionid',$unionid);

					$this -> loginbyname($udb);

				}



			}

		}



	}



	/**

	 * @since 20160426

	 * @author lxphp

	 * http://lxphp.com

	 */



	public function reg() {

		if ($this -> uinfo) {

			redirect(U('Home/Index/index'));

			exit ;

		}

		$media = $this -> getMedia('会员注册');

		$this -> assign('media', $media);

		$sex = I('get.sex');

		if ($sex) {

			$this -> assign('sex', $sex);

		}

		cookie("lxphpcode", 1, 600);

		$openid = cookie("regopenid");

		$wxinfo = S("reginfo".$openid);

		if($openid && $wxinfo){

			$this -> assign('avatar', $wxinfo['avatar']);
			$this -> siteDisplay('reg_03');
		}else{
			$this -> siteDisplay('reg_02');

		} 	

	}



	public function login() {

		if(C("onlywx")==1 && strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') === false){

		 $this->siteDisplay('jg_qzwxdk');

		exit;

		}

		if ($this -> uinfo) {

			redirect(U('Home/Index/index'));

			exit ;

		}

		

		$media = $this -> getMedia('会员登陆');

		$this -> assign('media', $media);

		$this -> assign('iswx', $this->is_weixin()?1:0);

		$this -> siteDisplay('login');

	}



	/**

	 * 退出登陆

	 *

	 * @author 紫竹

	 * @since 2014-3-29

	 */

	public function dologout() {

		$cook = cookie('checklogin');

		$ucookie = json_decode(stripslashes($cook), true);

		S('uinfo' . $ucookie['check'], null);

		cookie('checklogin', null,  3600);

		cookie('gourl', null,  3600);

		$this -> success("退出成功！", U('Home/Public/login'), 3);

	}



	/**

	 * 处理登陆后

	 *

	 * @since 2014-3-29

	 *

	 */

	private function after_login($username,$updateweixin="") {

		$ip = get_client_ip();

		$data['last_login_time'] = time();

		$data['last_login_ip'] = $ip;

		if($updateweixin){

		$data['weixin'] = $updateweixin;

		if(C('regunionid'))

		$data['unionid'] = C('regunionid');

		}		

		M('Users') -> where("id='$username'") -> save($data);

		cookie('yq', 0, 60);

		cookie('regopenid', 0, 60);

		cookie('regunionid', 0, 60);

	}



	public function loginbyname($re, $jump = 1) {

		if($re['user_status']==0)$this->error('您已经被禁止登陆！');

		$username = $re['user_login'];

		$rearea = $this -> get_area();

		$re['provincename'] = $rearea[$re['provinceid']]['areaname'];

		$re['cityname'] = $rearea[$re['cityid']]['areaname'];

		$logincheck = md5($username . $re['user_pass'] . C('PWD_SALA'));

		if(!$re['weixin'] && cookie("regopenid")) $re['weixin'] =  cookie("regopenid");

		// 用户名+ md5密码 +sala

		$cookie['check'] = $logincheck;

		$cookie['username'] = $username;

		cookie('checklogin', json_encode($cookie), 86400 * 365);

		S('uinfo' . $logincheck, $re);

		$this -> after_login($re['id'],$re['weixin']);

		if ($jump == 0)

			return;

		redirect(U('Home/Index/index'));

		exit ;

		/*	$openid = $re['weixin'];

		 if($openid && $re['subscribe']==0){

		 $data=S("reginfo".$openid);

		 if($data){

		 M("Users")->where("weixin='{$openid}'")->save($data);

		 if($data['subscribe']==1){//新关注更新 uinfo

		 $this->setUserinfo('subscribe',1);

		 }

		 }

		 S("reginfo".$openid,null,60);

		 }*/

		if ($jump == 0)

			return;

		$gourl = $_COOKIE['gourl'] ? $_COOKIE['gourl'] : $_SESSION['gourl'];

		redirect($gourl);

	}



	

	

	//签到************

	public function sign(){

		$sday = strtotime(date('Y-m-d',strtotime('+1 day')))-time();

		

		$money = C('qd_config');

		if(!$money) $this->ajaxReturn('签到未开启');

		$uid = $this->uinfo["id"];	//接收用户id

		if(!$uid) $this->ajaxReturn('您未登录！');

		$now = $_SERVER['REQUEST_TIME'];//请求开始的时间戳    $_SERVER['REQUEST_TIME']

		$today = date("Ymd",$now); //当前日期  如20160510

		if($today == cookie('qiandaotime'.$uid)||$today == S('qiandaotime'.$uid)){

			cookie('qiandaotime'.$uid,$today,$sday);//请求日期如果和签到成功后cookie所保存的日期相同	,说明当天内签到过	

			S('qiandaotime'.$uid,$today,$sday);

			$this -> ajaxReturn('您今天已经签到过了');

		}				

		$list = M('Qiandao') -> where('uid='.$uid) -> find(); //查询用户信息	

		$money = str_replace('，', ',', $money);

		$money = explode(',', $money);

			

		if(!$list){//没有签到记录

			$data['uid'] = $uid;

			$data['continue_days'] = 1;

			$data['last_time'] = $now;	

			$result = M('Qiandao') -> add($data);

			if($result){

				$this -> changemoney($uid,$money[0],301,'签到奖励'.'+'.C('money_name'),'qd',0,0,0,0,3011);

				cookie('qiandaotime'.$uid,$today,$sday);

				S('qiandaotime'.$uid,$today,$sday);

				$this -> ajaxReturn('签到成功！明天不见不散~');

			}else{

				$this -> ajaxReturn('系统正忙！请稍后再试！');

			}

		}//没有签到记录的情况处理完毕

		

		$lastTime = $list['last_time'];	//用户在数据库最后签到的时间戳

		$last_time = date("Ymd",$lastTime);	//转成最后签到的日期   如 20160509		

		if($today == $last_time){ // 今天签到和上次签到是同一天	

			cookie('qiandaotime'.$uid,$today,$sday);

			S('qiandaotime'.$uid,$today,$sday);

			$this -> ajaxReturn('您今天已经签到过了');	

		}else{	//不是同一天	

			if( $last_time == date('Ymd',strtotime("-1 day")) ){  //判断最后签到日期和昨天是否为同一天

				$data['continue_days'] = $list['continue_days'] + 1;

				$today_money =$money[$list['continue_days']];	  //上次签到时间和昨天的是同一天  说明有连续的签到

			}else{

				$data['continue_days'] = 1;

				$today_money =$money[0];   //上次签到和昨天不是同一天  说明有签到记录  然而并没有连续签到

			}

			$data['last_time'] = $now;  //将上次签到时间换成现在的时间

			$result = M('Qiandao') -> where('uid = '.$uid) -> save($data);

			if($result){

				$this->changemoney($uid,$today_money,301,'签到奖励'.'+'.C('money_name'),'qd',0,0,0,0,3011);

				cookie('qiandaotime'.$uid,$today,$sday);

				S('qiandaotime'.$uid,$today,$sday);

				

				$this -> ajaxReturn('签到成功！明天不见不散~');

			}else{

				$this -> ajaxReturn('系统正忙！请稍后再试！');

			}		

		}

	}

	

	

	

	//忘记密码

	public function getpwd(){

		if(IS_POST){

			$phone = I('post.phone','','trim');//接收手机号

			$yzm = I('post.yzm','','trim');//接收手机验证码					

			if(!$phone || !$yzm){

				$this -> ajaxReturn('请先填写完整信息'); //信息没写完整

			}				

			

			if(S($phone) != $yzm){

				$this -> ajaxReturn('手机验证码错误！'); //上线后开启验证码

			} 	

			session('phone',$phone,300);		

			$this -> ajaxReturn(1);				

		}

		cookie("lxphpcode",1,300);

		$this -> siteDisplay('getpwd');

	}

	

	

	//  忘记密码  -> 重置新密码

	public function resetpwd(){		

		if(IS_POST){

			if(!session('phone')){

				$this -> ajaxReturn('操作超时！');

			}

			$phone = session('phone');

			$pwd1 = I("post.pwd1",'','trim');

			$pwd2 = I("post.pwd2",'','trim');

			if($pwd1 != $pwd2){

				$this -> ajaxReturn('您输入的密码不一致');

			}

			if(strlen($pwd1)<6||strlen($pwd2)>16){

				$this -> ajaxReturn('请输入6到16位密码！');

			}

			$data['user_pass'] = md5 ( $phone . $pwd1 . C ( 'PWD_SALA' ) );													

			$result = M('Users') -> where('user_login='.$phone) -> save($data);

			if($result){

				$this -> ajaxReturn(1);

			}else{

				$this -> ajaxReturn('重置失败~请稍后再试');

			}		

		}	

		$this -> siteDisplay('resetpwd');

	}

	

	

	

	public function kfwechat($fromuid,$touid){//马甲客服聊天

		

		$uid = $touid;

		if(!$uid) return false;

		$re = M("Users")->where("id=".$uid)->find();

		unset($re['pass']);		

		$this->get_gz_status($uid,$fromuid);

		$this->get_qmd_val($uid,$fromuid);

		

		$message = M("Message");

		if($uid<$fromuid)

		$data["hash"]=md5($uid.$fromuid);

		else

		$data["hash"]=md5($fromuid.$uid);

		$msglist = $message ->where($data)->order("msgid asc")->limit(15)->order("msgid desc")->select();

		//dump($msglist);

		$msglist = $this->array_sort($msglist,'msgid');

		

		if($_GET['noread']==1){

		//更新已读

		$w['hash']=$data["hash"];

		$w['touid']=$fromuid;		

		$w['isread']=0;

		$count = $message ->where($w)->count();

		if($count>0){

			M("User_count")->where("uid=".$fromuid)->setDec("wdsxnum",$count);

			$w2['hash']=$data["hash"];

			$w2['touid']=$fromuid;

			$message ->where($w2)->save(array("isread"=>1,'redtime'=>time()));

		}

		

		}

		

		//更新已读		

		

		$re2 = M("Users")->find($fromuid);

				

		$media=$this->getMedia('和'.$re['user_nicename'].'聊天');

    	$this->assign('media', $media);

    	$this->assign('msglist', $msglist);

    	$this->assign('info', $re);

    	$this->assign('uinfo', $re2);

		$this->siteDisplay ( 'siliao_kf' );

		

	}

	

		

	/**

	 * 邀请好友

	 *

	 */

	public function MyYq(){

		$media = $this->getMedia ( '邀请好友', '', '', '邀请好友', 'ismenu' );		

		

		$this->assign ( 'media', $media );

		$uid = $this->uinfo['id'];

		A('Api')->yqapi();

	    $uidget = I("get.uid",0,'trim');

	    $ajax = I("get.ajax",0,'intval');

		if(!$uidget && $uid){

			redirect(U('MyYq',array("uid"=>$uid)));

			exit;

		}

		

		if($uidget && !$uid && !iswx()){

			redirect(U('index'));

			exit;

		}

		

		$uid = $uidget;

		$usermod = M("Users");

		$udb = $usermod->where("id=".$uid)->find();

		$logo = $udb['avatar'] ? $udb['avatar'] : 'Public/img/mrtx.jpg';

		

		$address = M('Area')-> where('areaid = '.$udb['cityid']) -> find(); //地址		

		$num = $usermod -> where('cityid = '.$udb['cityid'].' and sex = 2') -> count();	//数量		

		if($this->is_weixin()){//

			if(!$ajax){//正常显示

				$user_ticket = A("Home/Api")->doticket(2,$uid);

				//echo $user_ticket;

			}else{//发送到微信

				if($uid!=$this->uinfo['id']) exit('uid');

				if(S('ewmhb'.$udb['weixin']))  exit('0');

				$lxphppic = ROOT_PATH.'Public/img/lxphp.jpg';

				if(file_exists($lxphppic)){				

				 	$filepath = ROOT_PATH."Uploads/ewmhb".date('Ymd',time())."/";

				 	$imgpath =$uid.'.jpg';

				 	if(!is_dir($filepath)){

						mkdir($filepath);

					}

                	$ticket = $filepath.$imgpath;				

					$user_ticket = A("Home/Api")->doticket(0,$uid);

					$timg = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".$user_ticket;

					$re =  A('Home/Weixin')->curlg($timg);	

					

 					$size = file_put_contents($ticket,$re);						

					if(!$size) exit('size0');	

																					

					if(strstr($logo,'http')){

						$re2 = A('Home/Weixin')->curlg($logo,'http://'.$_SERVER['HTTP_HOST']);

						$logo2=$filepath.$uid.'_logo.jpg';

						$size2 = file_put_contents($logo2,$re2);

						if(!$size2) exit('size0');						

					}else{

						$logo=ROOT_PATH.$logo;

					}	

										

					$user_pic = $filepath."user_".$uid.".jpg";					

					$yxq = "将于".date("Y-m-d",time()+2592000)."失效";

					$img =  new \Think\Image();	

					$img->open($ticket)->thumb(252, 252)->save($ticket);

					$img->open($logo)->thumb(151, 151)->save($logo);				 				

					$img->open($lxphppic)

						->water($logo, array(241,195))

						->water($ticket, array(194,558))

						->text($udb['user_nicename'],'./hei.ttf','20','#ffffff', array(238,382))

						->text($yxq,'./hei.ttf','15','#dedede', array(250,950))

						->save($user_pic); 	

							

					if(file_exists($user_pic)){

						unlink($ticket);

						unlink($logo2);

						$upre = A("Home/Weixin")->uploadMedia($user_pic);

						$upre2 = json_decode($upre,true);

						if($upre2['media_id']){

							M("user_ticket")->where("ticket='{$ticket}'")->setField('mediaid',$upre2['media_id']);

						}else{

							$msg = $upre2['errmsg'];

							A("Home/Weixin")->makeTextbygm($msg,C('adminopenid'));

						}

				

						if($upre2['media_id']) {

							A("Home/Weixin")->makeImgbygm($upre2['media_id'],$udb['weixin']);

							S('ewmhb'.$udb['weixin'],1,86400);

						}			

				

					}	

				}				

			}			

			$this->assign('ewmimg',"https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".$user_ticket);

		}else{

			if($ajax) exit;

			$imgpath ="Uploads/ewm".date('YmdH',time())."/";

			$filepath = ROOT_PATH.$imgpath;

			$user_pic = $filepath."tg_".$uid.".jpg";

			$ticketimg =$imgpath."tg_".$uid.".jpg";

			if(!file_exists($user_pic)){

				require_once ROOT_PATH."WxpayAPI_php_v3/phpqrcode.php";

				$Qcode = new \QRcode();

				$url = "http://".$_SERVER['SERVER_NAME'].U ( 'MyYq' ,array('uid'=>$uid,'log'=>1));

				if(!is_dir($filepath)){

					mkdir($filepath);

				}

				$Qcode->png($url,$user_pic,QR_ECLEVEL_L,6,true);	

			}

			$this->assign('ewmimg',$ticketimg);	

		}		

		$this -> assign('sdesc','发现一个很好玩的平台，可以交友，还可以领红包哦~');

		$this -> assign('num',($num+100));

		$this -> assign('address',$address['areaname']);

		$this -> assign("info",$udb);

		$this -> siteDisplay ( 'user_yq' );

	}

	

	

	

	function array_sort($arr,$keys,$type='asc'){ //2维数组排序

    	$keysvalue = $new_array = array();

		

	    foreach ($arr as $k=>$v){

	        $keysvalue[$k] = $v[$keys];

	    }

		

	    if($type == 'asc'){

	        asort($keysvalue);

	    }else{

	        arsort($keysvalue);

	    }

		

    	reset($keysvalue);

		

	    foreach ($keysvalue as $k=>$v){

	        $new_array[$k] = $arr[$k];

	    }

    	return $new_array;

	}

	

	/**

	 * 新手教程

	 */

	public function NewbieGuide(){

		$media = $this->getMedia ( '新手教程', '', '', '用户中心', 'ispro' );

		//查找栏目为  新手教程

		$cid = M("Category")->where("name='新手教程'")->getField("class_id");

		$where = 'class_id='.$cid." and sex = 0";

		//根据class_id查找文章

		if(isset($this->uinfo['sex'])&&$this->uinfo['sex']){

			$sex = $this->uinfo['sex'];

			$where = 'class_id='.$cid." and sex in(0,".$sex.")";

		}else{

			if($openid = cookie("regopenid")){

				$wxinfo = S("reginfo".$openid);

				$sex =$wxinfo['sex'];

				if($sex){

					$where = 'class_id='.$cid." and sex in(0,".$sex.")";

				}				

			}	

			

		}

		

		

		$count = M('Content')->where($where)->count();//查询满足条件的总记录数

		$Page = new \Think\Page($count, 5);//实例化分页类，传入总记录数

		$nowPage = I("get.p")?I("get.p"):1;

		

		$row = M("Content")->where($where)->order("sequence desc,time desc")->page($nowPage.','.$Page->listRows)->select();

		$show = $Page->show();//分页显示输出

		

		$lists = $row;

		foreach($row as $key=>$value){

			//获取文章内容

			$re = M('Content_article')->where("content_id='".$value['content_id']."'")->find();

			$lists[$key]['content'] = html_out($re['content']);

		}

		

		$this->assign('media', $media);

		$this->assign('sex', $sex);

		$this->assign('page', $show);

		$this->assign("lists", $lists);

		$this -> siteDisplay ( 'user_guide' );

	}

	

	

	/**

	 * 公告

	 */

	public function gongGao(){

		$media = $this->getMedia ( '公告', '', '', '用户中心', 'ispro' );

		//查找栏目为  新手教程

		$id = I('get.id',0,'intval');

		if(!$id) $this->error('系统繁忙，请稍候再试！');

		$mod =  M('Content');

		$cid = $mod->where('content_id = '.$id)->getField('class_id');

        if(!$cid) $this->error('系统繁忙，请稍候再试！');

		

		$where = 'class_id='.$cid." and sex = 0";

		//根据class_id查找文章

		if(isset($this->uinfo['sex'])&&$this->uinfo['sex']){

			$sex = $this->uinfo['sex'];

			$where = 'class_id='.$cid." and sex in(0,".$sex.")";

		}else{

			if($openid = cookie("regopenid")){

				$wxinfo = S("reginfo".$openid);

				$sex =$wxinfo['sex'];

				if($sex){

					$where = 'class_id='.$cid." and sex in(0,".$sex.")";

				}				

			}	

			

		}

		

		

		//根据class_id查找文章

		$count = $mod->where($where)->count();//查询满足条件的总记录数

		$Page = new \Think\Page($count, 5);//实例化分页类，传入总记录数

		$nowPage = I("get.p")?I("get.p"):1;

		

		$row = $mod->where($where)->order("sequence desc,time desc")->page($nowPage.','.$Page->listRows)->select();

		$show = $Page->show();//分页显示输出

		

		$lists = $row;

		$modArticle =M('Content_article');

		foreach($row as $key=>$value){

			//获取文章内容

			$re = $modArticle->where("content_id='".$value['content_id']."'")->find();

			$lists[$key]['content'] = html_out($re['content']);

		}

	

		

		$this->assign('media', $media);

		$this->assign('page', $show);

		$this->assign("lists", $lists);

		$this -> siteDisplay ( 'user_gonggao' );

	}

	

	

	public function app(){

		$this -> siteDisplay ( 'xiazia' );

	}

	

	public function myrec(){

		$media = $this->getMedia ( '邀请好友', '', '', '邀请好友', 'ismenu' );		


		$this->assign ( 'media', $media );

		$uid = $this->uinfo['id'];

		A('Api')->yqapi();

	    $uidget = I("get.uid",0,'trim');

		if(!$uidget && $uid){

			redirect(U('myrec',array("uid"=>$uid)));

			exit;

		}

		

		if($uidget && !$uid){

			redirect(U('myhome'));

			exit;

		}

		

		$uid = $uidget;

		$usermod = M("Users");

		$udb = $usermod->where("id=".$uid)->find();

		$logo = $udb['avatar'] ? $udb['avatar'] : 'Public/img/mrtx.jpg';

		

		$address = M('Area')-> where('areaid = '.$udb['cityid']) -> find(); //地址		

		$num = $usermod -> where('cityid = '.$udb['cityid'].' and sex = 2') -> count();	//数量		



		$ewmPicDir = ROOT_PATH . 'Uploads/ewm/';

		$ewmPicUrl = "Uploads/ewm/tg_".$uid.".jpg";

		$ewmPicLocal = ROOT_PATH . $ewmPicUrl;



		//如果文件修改时间大于10小时，则删除，重新生成

		if(file_exists($user_pic)){

			$mtime = time() - filemtime($user_pic);

			if($mtime > 3600){

				unlink($user_pic);

			}

		}



		if(!file_exists($user_pic)){

			$url = "http://".$_SERVER['SERVER_NAME'].U ( 'Myrec' ,array('uid'=>$uid,'log'=>1));

			require_once ROOT_PATH."WxpayAPI_php_v3/phpqrcode.php";

			$Qcode = new \QRcode();

			if(!is_dir($ewmPicDir)){

				mkdir($ewmPicDir);

			}

			$Qcode->png($url,$ewmPicLocal,QR_ECLEVEL_L,6,true);	

		}



		$this->assign('ewmimg',$ewmPicUrl);

		$this -> assign('sdesc','发现一个很好玩的平台，可以交友，还可以领红包哦~');

		$this -> assign('num',($num+100));

		$this -> assign('address',$address['areaname']);

		$this -> assign("info",$udb);

		$this -> siteDisplay ( 'user_yq' );

	}



    public function myhome(){
		
        $qrimg = trim(C("gzhqrimg"));

        if(empty($qrimg)){

            redirect(U('index'));

		    exit;

        }

        $media = $this->getMedia ( '邀请好友', '', '', '邀请好友', 'ismenu' );

        $this->assign("media",$media);

        $this->assign("qrimg",$qrimg);

        $this->siteDisplay('user_myhome');
    }



}

