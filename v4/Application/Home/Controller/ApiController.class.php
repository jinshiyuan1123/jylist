<?php
namespace Home\Controller;
use Home\Controller\SiteController;
	/**
	*  @author lxphp
	 * http://lxphp.com
	 * 锦尚中国源码论坛提供
	 */


class ApiController extends SiteController {
		
		
		
		
		public function wxtoken($new=0){
			//dump(C('APPID'));
			return  $this->get_token(C('APPID'),C('SCRETID'),$new);
		}
		
		
		
		public function getwxinfo($openid,$new=0,$scope="",$token1=""){
		$token =  $this->wxtoken($new);
		if($scope!='snsapi_userinfo')
		$url2 = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$token."&openid=".$openid."&lang=zh_CN";
		else
		$url2 = "https://api.weixin.qq.com/sns/userinfo?access_token=".$token1."&openid=".$openid."&lang=zh_CN";
		$re2 = $this->curl_get_contents($url2); 
		$rearr2 = json_decode($re2,true);
		if($rearr2['errcode']==40001){
			return $this->getwxinfo($openid,1);
		}
		return $rearr2;
		}
		
		
		public function get_jsapi_ticket($appid=0,$appsecret=0){ //jsapi ticket
		if(S('jsapi_ticket')) return S('jsapi_ticket');	
		if($appsecret && $appid){
			$tokenl = $this->get_token($appid,$appsecret);
		}else{
			$tokenl = $this->wxtoken() ;
		}
		$url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='. $tokenl.'&type=jsapi';
		$ret_json = $this->curl_get_contents($url);
		$ret = json_decode($ret_json);
		$ticket = $ret-> ticket;
		S('jsapi_ticket',$ticket,7200);
		return $ticket;
		}
		
			
		public function saveinfo($openid){
			$userymod = M("User_y_reg");
			$re = $userymod->where("code='$openid'")->find();
			$rearr2 = $this->getwxinfo($openid);
			//S("log",$rearr2);
			$data['weixin']=$openid;
			$data['avatar']=$rearr2["headimgurl"]?$rearr2["headimgurl"]:0;	
			$data['user_nicename']=$data['user_login']=$rearr2["nickname"]?$rearr2["nickname"]:'sm'.date('ymdHis',time()).rand(1111,9999);
			$data['unionid']=$rearr2['unionid']?$rearr2['unionid']:0;
			$data['subscribe']=$rearr2['subscribe'];
			$data['country']=$rearr2['country'];
			$data['province']=$rearr2['province'];
			$data['city']=$rearr2['city'];
			$provinceid = $this->get_areaid_byname($data['province']);	
			$city = $this->get_areaid_byname($data['city']);
			$data ['provinceid']=$provinceid?$provinceid:0;	
			$data ['cityid']=$city?$city:0;		
			$data['subscribe_time']=$rearr2['subscribe_time'];
			$data['sex']=$rearr2['sex'];		
			if(!$re){
				$data2['code']=$openid;
				$data2['time']=time();
				$data2['data']=serialize($data);							
				$uid = $userymod->add($data2);
				$redata['id']=$uid;				
				return  array_merge($data,$redata);		
			}else{				
					
				return array_merge($data,$re);
			}
			
			
			
		}
		
		
		
		
		
		public function yqapi(){//微信 邀请api	     
	         if(C('APPID_jsapi') && C('SCRETID_jsapi')){
			 	$appid = C('APPID_jsapi');
			 	$appsecret = C('SCRETID_jsapi');		 	
			 }  else{
			 	$appid=C('APPID');
	     		 $appsecret=C('SCRETID');
			 }
		  $timestamp = time();
		  $noncestr =		$this->getRandStr(15);	
		  $ticket = $this->get_jsapi_ticket($appid,$appsecret);
			$strvalue = 'jsapi_ticket='.$ticket.'&noncestr='.$noncestr.'&timestamp='.$timestamp.'&url=http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			$signature = sha1($strvalue);		
			if(C('lxphpca')!=2){		
		   $this->assign('appid',$appid);
		   $this->assign('timestamp',$timestamp);
		   $this->assign('nonceStr',$noncestr);
		   $this->assign('signature',$signature);
		   }
		   return $timestamp;
		}
		
		
		public function unsubscribe($openid){//解除关注
			$data['subscribe']=-1;
			M("Users")->where("weixin='{$openid}'")->save($data);
			M("UserYReg")->where("code='{$openid}'")->save($data);
			
			//$re = M("Users")->where("weixin='{$openid}'")->find();					
		}
		
		
		
		public function countviews(){//统计转发流量	
		//if(!$this->is_weixin() && C('mustbewx')==1 &&$_GET['d']==1){
		//	exit();
		//}	
if(!$this->is_weixin() && C('mustbewx')==1 &&$_GET['d']==1){
		exit();
	}	
		//S("log",$_GET);
		if($_GET['d']!=1) return '';
		//if(!cookie('user_openid')) exit();	
			$time = $_GET['time'];
			$now = $_GET['now'];
			if($now==$time) exit('time err');		
			$uid = $_GET['uid'];
			$aid = $_GET['content_id'];	
			setcookie('aid_'.$aid,1,time()+86400*360,'/');
			if(M("Content")->where("content_id=".$aid)->getField("actionstatus")==1) exit("此文章任务已完成，不再计算佣金。");	
			if(!$uid) exit('no u');	
			$row = M("Users")->where("id=".$uid)->find();
			$user_status = $row['user_status'];				
			if($user_status==0) exit('status0');
			//dump($_COOKIE['aid_'.$aid]);		
			if($_COOKIE['aid_'.$aid]!=1){//没有cookie作弊
			//dump();
			if(time()<$now+3)	exit('3 sec');	//浏览没有3秒不算佣金	
				$fdata['uid']=$uid;
				if($this->uinfo['id']==$fdata['uid']) exit('my self');//自己浏览没有佣金
				if(C('is_get_hy')==1&&$this->uinfo['id']>0) exit('is_get_hy');
				$fdata['aid']=$aid;
				$re = M("Content_f_log")->where($fdata)->find();
				if($re['type']=='one'&&C('is_get_wxq')==1) exit('is_get_wxq');
				$ip = get_client_ip();
				$ip2 = ip2long($ip);								
				if($re['time']==$time && $re['ip2']!=$ip2 && $re['ip2']){//正确的时间，不是同 ip	
					if(C('lxphpca')==2) exit('no yueaiyuan');				
					 $resf = M("Content_f_log")->where($fdata)->setInc('view',1);
					 if($re['view']>=C('bigview')) {//单篇文章浏览次数超过多少没有奖励！
					// S("bigviews",$fdata);
					 exit('big view');
					 }
					 	$checkdata['uid']=$uid; // 去掉这个 单ip 浏览所有个数超过多少不计算佣金
					 	$checkdata['ip2']=$ip2;
					 	$checkdata['_string']="time>UNIX_TIMESTAMP()-3600*3";
					 	$ipviews = M("Account_log")->where($checkdata)->count();//一个ip 浏览同一个用户的文章
					 	if($ipviews>=C('ipviews') && $ipviews>0){ 
					 	//$bigipviews=S("logbigview");
					 	//$bigipviews++;
					 	//S("logbigview",$bigipviews,86400);
					 	exit('ipviews');
					 	}
					 	$money = 	C("fxview");
					 	
					 	 $contentInfo   =   M('Ext_gzh')->where("data_id =$aid")->find();
					 	
					 	if($contentInfo['r_money']>0){
					 		$money = $contentInfo['r_money'];
					 	}
					 
					 	if(C('is_check_bind')==1){
					 		//$money = $this->qy_money($this->uinfo['id'],$contentInfo['area'],$money);
					 	}
					 $desc='文章被浏览';

					//扣量设置
					if(time() > C('kl_day')*24*60*60 + $this->uinfo['create_time']){
						//大于注册时间+开始时间
						if(C('kl_hour') != ""){
							//扣量小时时间段
							$kl_hour = explode(",", C('kl_hour'));
							foreach($kl_hour as $k=>$v){
								if($v == date('H', time())){
									if(C('kl_time')){
										//扣量秒时间段
										$kl_time = explode(",", C('kl_time'));
										foreach($kl_time as $key=>$value){
											if($value*10-10 < date('s',time())&&$value*10>date('s',time())){
									 			exit('done');
									 		}
										}
									}
								}
							}
						}else{
							
							if(C('kl_time')){
										//扣量秒时间段
										$kl_time = explode(",", C('kl_time'));
										foreach($kl_time as $key=>$value){
											if($value*10-10 < date('s',time())&&$value*10>date('s',time())){
									 			exit('done');
									 		}
										}
									}
							
							
						}
					}
					 
					 if(IS_AJAX){
					 	 $this->changemoney($money,5,$desc,$fdata['uid'],0,$ip,$aid);
					 	 
					 	   if(C("notify_ll")==1){
						 $title = M("content")->where("content_id=".$aid)->getField("title");
					 	 $desc = "您的朋友看了你分享的：<".$title.">，您获得了".$money."元的奖励！";
					 	// S("log2",$desc);				 		
						A('Home/Weixin')->sendmb_money($row['weixin'],'您好，您有新的余额变动',$money,$row['money'],$desc,$row["user_nicename"]);
							 	
						 }
					 	
					//浏览下级分成
						 	 
						 	$fx_kk[]=C('fxview_jq_pid_1');//1级
						 	$fx_kk[]=C('fxview_jq_pid_2');//2级
						 	$fx_kk[]=C('fxview_jq_pid_3');//3级
						 	$row['user_id']=$fdata['uid'];
						 	for($i=0;$i<count($fx_kk);$i++){
								if($fx_kk[$i]<=0) continue;
	                            $body = "粉丝文章被浏览";
						 		$body =($i+1)."级".$body;
						 		$row =M('Users')->field('parent_id as user_id')->where("id=".$row['user_id'])->find();
						 		$this->changemoney($fx_kk[$i],8,$body,$row['user_id'],0,$ip,$aid);
						 	}
						
						 
					 	
					 }				
								 			
				}		
				
			
				 exit('done');
			}
			
			 exit('cookie err');
			
			
		}
		
		
		
		
		
		
		
		
		
		/*
		public function countviews(){//统计转发流量		
			$time = $_GET['time'];
			$uid = $_GET['uid'];
			$aid = $_GET['content_id'];	
			//dump(IS_AJAX);		
			if($_COOKIE['aid_'.$aid]!=1&&$_GET['d']==1){//没有cookie作弊			
				$fdata['uid']=$uid;
				if($this->uinfo['id']==$fdata['uid']) return '';
				$fdata['aid']=$aid;
				$re = M("Content_f_log")->where($fdata)->find();			
				$ip = get_client_ip();
				$ip2 = ip2long($ip);						
				if($re['time']==$time && $re['ip2']!=$ip2 && $re['ip2']){//正确的时间，不是同 ip	
								
					 $resf = M("Content_f_log")->where($fdata)->setInc('view',1);
					 if(IS_AJAX)				
						 $this->changemoney(C("fxview"),5,$desc='您分享的文章被浏览',$fdata['uid'],$note=0);			 			
				}
						
				setcookie('aid_'.$aid,1,time()+86400*360,'/');
			
				 exit;
			}
			if($_GET['d']==1) exit();
			
			
		}*/
		
		/**
		* 
		* @param undefined $openid
		* @param undefined $fee
		* @param undefined $title
		* @param undefined $body
		* @param undefined $type  1分享文章送2关注送3分享好友送4提现5摇一摇
		* 
		* @return
		*/
		public function sendhb($openid='oFV_Ut-mQYP-tkOcDyVGGjG2Gpxc',$fee=1,$title='紫竹IT互联',$body='邀请好友一起来拿红包',$type){//发红包
		$hb = new \Org\Util\Hongbao();		
				$arr['openid']=$openid;
	        	$arr['hbname']=$title;
	        	$arr['body']=$body;
	        	$arr['fee']=$fee;
				$re = $hb->sendhongbaoto($arr);
				if($re['result_code']=='SUCCESS'){
					$this->log_money($openid,$fee,$body,$type);
					return TRUE;
				}else {				
				if($re['err_code']=='TIME_LIMITED' && C('hbkqzz')==1){
				return $this->sendzz($openid,$fee,$body,$type);
				}else if($re['err_code']=='NOTENOUGH'){//余额不足	
					A("Site")->changemoney($fee,$type='3',$body,D("Users")->wreuval($openid),$note=1);		
					A("Weixin")->makeTextbygm("余额不足无法发送红包",C('adminopenid'));
				}else{
					A("Site")->changemoney($fee,$type='3',$body,D("Users")->wreuval($openid),$note=1);
					A("Weixin")->makeTextbygm("发红包出错01！".$re['return_msg'],C('adminopenid'));
				}
				
				}
				
				
				return $re;
		}
		
		
		/**
		* 
		* @param undefined $openid
		* @param undefined $fee
		* @param undefined $desc
		* @param undefined $type  1分享文章送2关注送3分享好友送4提现5摇一摇
		* 
		* @return
		*/
	  
		public function sendzz($openid,$fee,$desc,$type){//转账
	  	$Transfers = new \Org\Util\Transfers();	
		$re = $Transfers->dozz($openid,$fee,$desc);
		if($re['result_code']=='SUCCESS'){ // 正确返回
		$this->log_money($openid,$fee,$desc,$type);
		 	return true;
		}else{//返回错误信息
		A("Site")->changemoney($fee,$type='3',$desc,D("Users")->wreuval($openid),$note=1);	 
		if($re['err_code']=='NOTENOUGH'){
			A("Weixin")->makeTextbygm("余额不足无法转账",C('adminopenid'));
		}else{
			A("Weixin")->makeTextbygm("发红包出错02！".$re['return_msg'],C('adminopenid'));
		}
			return $re;
		} 
		
		
		
	  }
	  
	  
	  public function clickfun($eventkey,$openid){//菜单 点击事件 api
	  	if($eventkey=='yaohb'){//摇红包
						$msg="点击进入摇一摇 http://".C('site_url')."/index.php?s=/Home/Huodong/yaoyiyao";
					}
					if($eventkey=='kflx'){//开发联系
						$msg="QQ 263960836";
					}
					if($eventkey=='xtgm'){//系统购买
						$msg="上淘宝搜索店铺“橙橙科技”购买本系统。";
					}
					if($eventkey=='myjifen'){//我的积分
						$msg="您的积分为：".D("Users")->wreuval($openid,'jifen')."积分可摇红包和现金。";
					}
					if($eventkey=='myfensi'){//我的积分
						$myid = D("Users")->wreuval($openid);
						$allfx = M("Users")->where("parent_id=".$myid)->count();
						$ygzfs = M("Users")->where("parent_id=".$myid." and subscribe =1")->count();
						$msg="您一共有粉丝：".$allfx.",其中已关注公众号的粉丝为：".$ygzfs.";已关注粉丝的收益您将获得分成。";
					}
					if($eventkey=='tixian'){//提现
						
						$msg=$this->tixian($openid);
					}
					if($eventkey=='mrqd'){//签到
						
						$msg=$this->qiandao($openid);
					}
					return $msg;
	  }
	  
	  public function tixian($openid){//提现操作
	  	$txsq = M("moeny_log")->where("weixin='{$openid}'")->order("id desc")->find();
	  	if($txsq['status']==2) return '您有一个提现申请正在处理中。提现金额为'.$txsq['fee'];  	
	  	
	  	
		$uinfo = M("Users")->where("weixin='{$openid}'")->find();
			$user_status = $uinfo["user_status"];
			    $cantxmoney = floor($uinfo['money']);//舍去取整	
			    if(C('is_get_sxjfc')>0 && $uinfo['parent_id']>0){
			    	$stxmoney =number_format($cantxmoney*(C('is_get_sxjfc')/100),2);
			    	$ztxmoney =$cantxmoney -$stxmoney ;
			    }
				$txmoney = $ztxmoney?$ztxmoney:$cantxmoney;
			    if($user_status==0) return '您的账号异常！';	
		
		if(C('wxtx')<=0) return '微信自动提现暂未开启，敬请期待！';
			if(C('wxtxxz')>0 && $cantxmoney<C('wxtxxz') && M("moeny_log")->where("uid=".$uinfo["id"]." and status=1")->find())  return '提现金额必须是'.C('wxtxxz').'元起';
	  	if($cantxmoney<C('wxtx')) return '提现金额必须是'.C('wxtx').'元起';
	  	if($txmoney<1) return '扣除上级分成后，您的提现金额不足1元，无法提现';
	  	if($cantxmoney>0){
				//$historymoney = M("account_log")->where("uid=".$uinfo["id"]."  and money>0")->sum('money');	
	  	
			if(S("lxphpca")==2) exit();
			//20150804
			$recount = M("Content_f_log")->where("uid=".$uinfo["id"])->count();
			if($recount>0){
				$resum= M("Content_f_log")->where("uid=".$uinfo["id"])->sum('view');
				if($recount/$resum>=15){
					$msg_ = "您属于作弊，已被封号。";
					 M("Users")->where("id=".$uinfo["id"])->setField("user_status",0);
				}
				if($recount>$resum*4){
					return '浏览数：'.$resum.'，分享数：'.$recount.'。您文章被浏览数少于分享的文章数，无法提现。'.$msg_;
				}
			}else{
				return '您没有分享任何文章，无法提现';
			}
			
			//20150804
			
					
	  		if(date("Ymd",$txsq['time'])==date("Ymd",time())) return '您今天已经有一次提现申请，请明日再申请。当前余额：'.$cantxmoney;
	  		$body = '余额提现';
	  		if($ztxmoney)$new_body= $body.":".$ztxmoney.',上级分成:'.$stxmoney;
	  		$xbody ="下级提现分成";
	  		$body = $new_body?$new_body:$body;		
			if($txmoney<=C('txshjr') && C('txshjr')> 0){//小于提现审核金额直接到账
			if(C("txtype")){//红包
				$hb = new \Org\Util\Hongbao();		
				$arr['openid']=$openid;
	        	$arr['hbname']=C("site_title");
	        	$arr['body']=$body;
	        	$arr['fee']=$ztxmoney?$ztxmoney:$cantxmoney;
				$re = $hb->sendhongbaoto($arr);
				if($re['result_code']=='SUCCESS'){
					if($ztxmoney) {
						$this->log_money($openid,$ztxmoney,$body,4);
						$this->changemoney($stxmoney,$type='7',$xbody,$uinfo["parent_id"],$note=1);
					}else{
						$this->log_money($openid,$cantxmoney,$body,4);
					} 				
			$this->changemoney((-1)*$cantxmoney,$type='7',$body,D("Users")->wreuval($openid),$note=1);
		 	return '提现成功，请查看您收到的红包。';
				}else {	
				if($re['err_code']=='TIME_LIMITED'){
					
					$Transfers = new \Org\Util\Transfers();
		$re = $Transfers->dozz($openid,$ztxmoney?$ztxmoney:$cantxmoney,$body);	
		if($re['result_code']=='SUCCESS'){ // 正确返回
			if($ztxmoney) {
				$this->log_money($openid,$ztxmoney,$body,4);
				$this->changemoney($stxmoney,$type='7',$xbody,$uinfo["parent_id"],$note=1);
			}else{
				$this->log_money($openid,$cantxmoney,$body,4);
			}
		$this->changemoney((-1)*$cantxmoney,$type='7',$body,D("Users")->wreuval($openid),$note=1);
		 	return '提现成功，请到微信钱包的零钱中查收！';
		}else{
			$this->log_money($openid,$cantxmoney,$body,4,2);
			A("Weixin")->makeTextbygm("发红包出错022！".$re['return_msg'],C('adminopenid'));
			return '自动提现失败，转为人工操作，请耐心等待。';
		}			
					
					}else if($re['err_code']=='NOTENOUGH'){
						$this->log_money($openid,$cantxmoney,$body,4,2);
						A("Weixin")->makeTextbygm("发红包出错023！".$re['return_msg'],C('adminopenid'));
			return '自动提现失败，转为人工操作，请耐心等待。';
						}else{
							$this->log_money($openid,$cantxmoney,$body,4,2);
							A("Weixin")->makeTextbygm("发红包出错024！".$re['return_msg'],C('adminopenid'));
							return '自动提现失败，转为人工操作，请耐心等待。';
						}
				
				}
				
				
			}else{
		$Transfers = new \Org\Util\Transfers();
		$re = $Transfers->dozz($openid,$ztxmoney?$ztxmoney:$cantxmoney,$body);	
		if($re['result_code']=='SUCCESS'){ // 正确返回
		if($ztxmoney) {
				$this->log_money($openid,$ztxmoney,$body,4);
				$this->changemoney($stxmoney,$type='7',$xbody,$uinfo["parent_id"],$note=1);
			}else{
				$this->log_money($openid,$cantxmoney,$body,4);
			}
		$this->changemoney((-1)*$cantxmoney,$type='7',$body,D("Users")->wreuval($openid),$note=1);
		 	return '提现成功，请到微信钱包的零钱中查收！';
		}else{
			$this->log_money($openid,$cantxmoney,$body,4,2);
			A("Weixin")->makeTextbygm("发红包出错025！".$re['return_msg'],C('adminopenid'));
			return '自动提现失败，转为人工操作，请耐心等待。';
		}
			}
		
		
		}else{
			$this->log_money($openid,$cantxmoney,$body,4,2);	
			return '您的提现申请已经提交，我们会尽快审核后发放。';
			}		
		}else{
			return '您的余额为'.$cantxmoney."，<a href='http://".C('site_url').U("Home/Index/index2")."'>立即前往赚取佣金</a>。";
		}
	  }
	  
	  public function tixianbysh($txid){//审核提现。
	  		$reinfo = M("moeny_log")->find($txid);
			if($reinfo['status']!=2){
				//return false;
				return '当前提现申请不是审核状态。';
			}else{
		$hismoney = M("Users")->where("id=".$reinfo['uid'])->getField('money');
		if($hismoney<$reinfo['fee']) return '余额不足以提现，当前账户余额：'.$hismoney;
		
		$body = '余额提现';
		$Transfers = new \Org\Util\Transfers();
		$pid = M("Users")->where("id=".$reinfo['uid'])->getField('parent_id');
		if(C('is_get_sxjfc')>0 && $pid>0 ){
			$stxmoney =number_format($reinfo['fee']*(C('is_get_sxjfc')/100),2);
			$ztxmoney =$reinfo['fee'] -$stxmoney ;
		}
		if($ztxmoney)$new_body= $body.":".$ztxmoney.',上级分成:'.$stxmoney;
		$xbody ="下级提现分成";
		$body = $new_body?$new_body:$body;
		$re = $Transfers->dozz($reinfo['weixin'],$ztxmoney?$ztxmoney:$reinfo['fee'],$body);

		if($re['result_code']=='SUCCESS'){ // 正确返回
		//$this->log_money($reinfo['weixin'],$reinfo['fee'],$body,4);
		if($ztxmoney){
			
			$this->changemoney($stxmoney,$type='7',$xbody,$pid,$note=1);
		}
			
		$this->changemoney((-1)*$reinfo['fee'],$type='7',$body,$reinfo['uid'],$note=1);
		 	return true;
		}else{
			return $re['return_msg'];
		}
		
		
		
			}
	  }
	  
	   public function log_money($openid=0,$fee=0,$desc=0,$type=0,$status=1,$uid=0){//记录现金记录  	
		$data['time']=time();
	  	$data['weixin']=$openid;
	  	$data['uid']=$uid?$uid:D("Home/Users")->wreuval($openid);
	  	$data['fee']=$fee;
	  	$data['body']=$desc;
	  	$data['type']=$type;
	  	$data['status']=$status;
	  	M("moeny_log")->add($data);
	  }
	  
	 	public function send_coupon(){//代金卷
			
		}
		
		public function qiandao($openid,$uid=0){		
			$tday = date("Ymd",time());
			$uid =  $uid?$uid : D("Users")->wreuval($openid);				
			$re = M("account_log")->where("uid={$uid} and type=6")->order("id desc")->getField('time');
			//dump($re);
			//echo M()->getLastSql();
			//exit;
			if(date("Ymd",$re)==$tday) return '今天已经签到过了哦，明天再来,签到积分可以摇奖。';
			if(C("qdjifen")>0)
			$re = $this->changejifen(C("qdjifen"),$type='6',$desc='签到送积分',$uid,1);
			if($re){
				return '签到成功！明天不见不散,签到积分可以摇奖。';
			}else{
				return '签到失败~额';
			}
		}
		
		public function dzan(){
			$id = I("post.id");
			if(cookie('yizan'.$id)==1) exit;
			//$data['zan']=array('exp','zan+1');
			$re = M("Content")->where("content_id=".$id)->setInc('zan',1);
			if($re){
			   cookie('yizan'.$id,1);
			}
		}
		
		public function getewmmediaid($openid){
			$us = D("Users")->where("weixin='{$openid}'")->find();
			$sctime = time()-86400*3;
			$mediaid = M("user_ticket")->where("uid=".$us['id']." and time >".$sctime)->order('id desc')->getField('mediaid');
			if($mediaid) return $mediaid;
			$ticket = $this->doticket('1',$us['id']);//新的ticket
			if($ticket=='err') return ('errticket');
			$access_token = A("Home/Weixin")->accesstoken();
			$erwmpath = $this->maketicketimg($ticket,$us);	
			$url = "http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=".$access_token."&type=image";
			$upre = A("Home/Weixin")->uploadMedia($erwmpath);
			$upre2 = json_decode($upre,true);
			if($upre2['media_id']){
				M("user_ticket")->where("ticket='{$ticket}'")->setField('mediaid',$upre2['media_id']);
			}else{
				$msg = $upre2['errmsg'];
				A("Home/Weixin")->makeTextbygm($msg,C('adminopenid'));
			}
				return $upre2['media_id'];
			dump($erwmpath);
			dump($upre);
		}
		
		
		/**
		 *
		 * @param undefined $ticket
		 * @param undefined $logo  头像
		 * @param undefined $uid   用户id
		 * @param undefined $uname  昵称
		 * @param undefined $yxqtime ticket生成时间
		 * by http://bbs.52jscn.com
		 *
		 */
		public function maketicketimg($user_ticket,$udb=array()){ // by bbs.52jscn.com
			$logo = $udb['avatar'] ? $udb['avatar'] : 'Public/img/mrtx.jpg';
			$lxphppic = ROOT_PATH.'Public/img/lxphp.jpg';
			if(file_exists($lxphppic)){
				$filepath = ROOT_PATH."Uploads/ewmhb".date('Ymd',time())."/";
				$imgpath =$uid.'.jpg';
				if(!is_dir($filepath)){
					mkdir($filepath);
				}
				$ticket = $filepath.$imgpath;
				//$user_ticket = A("Home/Api")->doticket(0,$uid);
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
			}
			
			
			return $user_pic;
		}
		
		
		public function pl_dzan(){
			$id = I("post.id");
			if(cookie('pl_yizan'.$id)==1) exit;
			//$data['zan']=array('exp','zan+1');
			
			$fieldset_id = $this->get_pl_fieldset_id();
			$model = D('DuxCms/FieldData');
			$this->formInfo = D('DuxCms/FieldsetForm')->getInfo($fieldset_id);
			
			$model->setTable($this->formInfo['table']);
			
			$re =$model->where("data_id=".$id)->setInc('zan',1);
			if($re){
				cookie('pl_yizan'.$id,1);
			}
		}
		
		//二维码推广
		public function doticket($new=0,$uid=0){
			$sctime = time()-2592000;
			M("User_ticket")->where("time <".$sctime)->delete(); //过期删除 by lxphp.com
			$uid = $uid?$uid:$this->uinfo["id"];
			if(!$uid) return ('login first');
			$token =  A("Home/Weixin")->accesstoken();
			$url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$token;
			$json='{"expire_seconds": 2592000, "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id": '.$uid.'}}}';
			$re0 = M("User_ticket")->where("uid=".$uid)->find();			
			if($re0){
				if($re0['time']+2592000>time()&& $new==0){
					return $re0['ticket'];
				}else{
					$re =  $this->curlp($url,$json);
					$re2 = json_decode($re,true);
					if($re2['ticket']){
						$data['ticket'] = $re2['ticket'];
						$data['time'] = time();
						$data['uid'] = $uid;
						M("User_ticket")->add($data);
						return $re2['ticket'];
					}
				}
					
			}else{
				$re =  $this->curlp($url,$json);
				$re2 = json_decode($re,true);				
				if($re2['errcode']==40001) $this->wxtoken('1'); 
				if($re2['ticket']){
					$data['ticket'] = $re2['ticket'];
					$data['time'] = time();
					$data['uid'] = $uid;
					M("User_ticket")->add($data);
					return $re2['ticket'];
				}
			}
		
		
		
			return 'err';
		
		}
		
		

		public function test(){
			$uid = $uid?$uid:$this->uinfo["id"];
			if(!$uid) exit('login first');
			$token =  A("Home/Weixin")->accesstoken();	
			$url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$token;
			$json='{"expire_seconds": 604800, "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id": '.$uid.'}}}';
			$re =  $this->curlp($url,$json);
					$re2 = json_decode($re,true);
					dump($re2);
		}
		
		
	// 地区佣金计算方法汇总 重构  by lxphp.com   20151116 
	//$areayj 传入金额 或者 直接传入 1 
	//$type =  ip ,  weixin  , mob   IP匹配 微信地区匹配 手机地区匹配  
	//  $ip   $uinfo   $mobnum   对应以上必须
	//不再区域内 则返回 0
	public function areayjfun($area,$areayj=0,$type='ip',$ip=0,$uinfo=0,$mobnum=0){
		if(C('is_check_bind')==1&& $uinfo){//绑定手机的直接返回
						$type ='mob';	
						}		
		if($area && S('lxphpca')!=2){
			$yqarea = $area;
			if($type=='ip' && $ip){					
									
						 $url = "http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip=".$ip;
        				$re3 = A("Home/site")->curl_get_contents($url);
        				$re2 = json_decode($re3,true);        				
        				if(strstr($yqarea,',')){
							$areaarr = explode(',',$yqarea);
							if( in_array($re2['city'],$areaarr)|| in_array($re2['province'],$areaarr) ){
								return  $areayj;
							}
						}else{
							if($re2['city']==$yqarea  || $re2['province']==$yqarea ){
								return  $areayj;
							}
						}
									
						
					
			}elseif($type=='weixin' && $uinfo){
					if(strstr($yqarea,',')){
							$areaarr = explode(',',$yqarea);
							if( in_array($uinfo['city'],$areaarr)|| in_array($uinfo['province'],$areaarr)){
								return  $areayj;
							}
						}else{
							if($uinfo['province']==$yqarea || $yqarea==$uinfo['city'] ){
								return  $areayj;
								}
						}
				
				
			}elseif($type=='mob' && $uinfo){
				if(strstr($area,',')){
		$areaarr = explode(',',$area);
		if(in_array($uinfo['b_city'],$areaarr) || in_array($uinfo['b_pro'],$areaarr)){
		return  $areayj;
		}
	}else{
		if($uinfo['b_city']==$area || $uinfo['b_pro']==$area){
		return  $areayj;
		}
	}
				
			}
		}
		return 0;
	}
	
	/**
	* 定时发布文章
	* @author nineTea
	* 20151225
	*/
	public function timeToPublish(){		
			$now = time();
			$where = array(
				'status'=>0,
				'actionstatus'=>0,
				'time'=>array(array("lt", $now),array('gt', $now-30*60)),
			);
			$contentList = M('Content')->where($where)->select();
			$data['status'] = 1;
			$re = M('Content')->where($where)->save($data);
			if($re) {
				A("Home/site")->curl_get_contents("http://".$_SERVER['HTTP_HOST'].U("Home/Index/index"));
				exit('success:'.count($contentList));	
			}
		}
		
	
		
}
	?>