<?php

namespace Home\Controller;

use Home\Controller\SiteController;

	/**

	*  @author lxphp

	 * http://lxphp.com

	 * 锦尚中国源码论坛提供

	 * 定时 Crontab

	 */





class CrontabController extends SiteController {



	 public function __construct()

    {

        parent::__construct();

        if(!$_GET['pass01']) exit();
    }





        public function autoSys(){

			set_time_limit(0);

			#S("sysAutoLock" , 0);
			$sysAutoLock = S("sysAutoLock");

			if($sysAutoLock){

				echo "sss脚本正在运行...";

				exit;

			}
			$st=time();
			S("sysAutoLock" , 1);

            $this->resetWdsxnum();
			
			$msgCount = C('msg_count');

			#$this->messageTips($msgCount);

			//$this->autonoticewdsx();
			$this->autonoreply();
			$openmj = C('openmj');

			$this->autozhmpBatch($openmj , 10);

			//$openmjhp = C('openmjhp');

			//$this->autozhmpBatch($openmjhp , 20);

            //$this->vipnotice();

           // $this->clearvip();


			echo '运行时间：'.(time()-$st).'sssssssssssssssssss';
			S("sysAutoLock" , 0);

		}



		/**

		* 定时清除7天点赞数

		* 每7天执行一次

		*/



	    public function clearzan(){

			$re =  M("user_count")->where("1=1")->setField('sevenzan',0);

			if($re)

			echo 'success:'.time().'|'.date('Ymd H:i:s');

			else

			echo 'error：'.M()->_sql().'|'.date('Ymd H:i:s');

		}





		/**

		* 定时清除魅力值/财富值排行版（周）

		* 每7天执行一次

*/

		 public function clearjifen(){

			$re =  M("user_count")->where("1=1")->setField('sevenjifen',0);

			if($re)

			echo 'clearjifen success:'.'|'.date('Ymd H:i:s');

			else

			echo 'error：'.M()->_sql().'|'.date('Ymd H:i:s');

		}







		/**

		* VIP快到期通知

		*  每天执行一次

*/

		public function vipnotice(){

			$time =time()+6*86400;

			$time2 =time()+4.5*86400;

			$re = M("Users")->field('weixin,rank_time')->where("rank_time between ".$time2.' and '.$time)->select();

			if($re){

				foreach($re as $key=>$val){

					$rank_day =	ceil(($val['rank_time']-time())/(24*3600));

					$title = "您的VIP快到期了";

					$content = "您的VIP还有".$rank_day.'天到期';

					$desc = '请尽快续费，以免影响您的使用，感谢！';

					$url2=U('Home/User/VipCenter');

					if($val['weixin'])

					A('Home/Weixin')->sendmb_geren($val['weixin'],$title,$content,$desc,$url2);

				}

				echo 'success:'.count($re).'|'.date('Ymd H:i:s');

			}

			else{

				echo 'no res'.'|'.date('Ymd H:i:s');	;

			}



		}







		/**

		* 清除过期VIP

		* 每天执行一次

		*/

		public function clearvip(){

			$re = M("Users")->field('rank_time')->where("user_rank>0 and rank_time <".time())->select();

			if($re){

				M("Users")->where("user_rank>0 and rank_time <".time())->setField('user_rank',0);

				echo 'success:'.count($re).'|'.date('Ymd H:i:s');

			}else{

				echo 'no'.'|'.date('Ymd H:i:s');

			}

		}





		/**

		* 清除当天的统计数据

		* 每天执行一次

		*/

		public function clearevery(){

		$re =  M("Systemtj")->where("1=1")->save(array('manUserDay'=>0,'girlUserDay'=>0,'manVipDay'=>0,'girlVipDay'=>0,'txTotalFeeDay'=>0,'vipMoneyDay'=>0,'chongMoneyDay'=>0));

			if($re)

				echo 'success:'.'|'.date('Ymd H:i:s');

			else

				echo 'error：'.M()->_sql().'|'.date('Ymd H:i:s');



		}





		/**

		* 10分钟

		* 未读私信提醒

		*

*/

		public function autonoticewdsx(){

			#$sxnum = C('wdsxnotice');

			$sxnum = C('msg_count');

			if(!$sxnum){

                echo ('notice:noset');

                return false;

            }

			$uscount = M("User_count");

			$list = $uscount->alias('c')->field('u.sex,u.id,u.weixin,u.idmd5,c.wdsxnum')->join('__USERS__ as u ON c.uid=u.id')->where("c.wdsxnum >=".$sxnum)->order('uptime desc')->select();

            if($list){

				foreach($list as $val){

					$plcheck  = $this->checksxwd($val);

					if($plcheck===false) continue ;

					if(!$val['weixin']) continue;

					$ids[]=$val['id'];

					$dmsg[0]=array('title'=>'您有新的未读消息','description'=>'您已经有'.$val['wdsxnum'].'条消息没有查看了，点击查看所有来信。','picurl'=>'http://'.C('site_url').'/v4/xin_'.$val['sex'].'.jpg','url'=>"http://".C('site_url').U('Home/Wechat/sixin'));

					A("Home/Weixin")->makeTextImgbygm($val['weixin'],$dmsg);

					$this->logmj($val['id'],'未读私信通知',2);

				}

				if($ids){

					$arrstr = implode($ids,',');

				    echo 'success:Id'.$arrstr;

				}else{

					echo 'noweixin error：|'.date('Ymd H:i:s');

				}

			}else{

				echo ('没有查询到需要通知的用户');

			}

			//dump($list);

		}



		private function autozhmpBatch($openmj , $autoType=10){

			if($openmj < 1){

				return false;

			}
			$usermod = M("Users");
			$last_login_time=time()-(3600*24);
			$where = "parent_id>0 and weixin!='' and last_login_time>".$last_login_time;

			$where .= $openmj!=3 ? " and sex = ".$openmj : '';



			$pageSize = 200;

			$count = $usermod->where($where)->count();

			$totalPage = ceil($count/$pageSize);

			for($curPage=0;$curPage<$totalPage;$curPage++){

				$limitStr = ($curPage*$pageSize) . "," . $pageSize;

				$userList   = $usermod->field('id,sex,weixin,provinceid,cityid')->where($where)->order('last_login_time desc')->limit($limitStr)->select();

				if(empty($userList)){

					continue;

				}

				$mjList = $this->returnMutiMj($pageSize);

				if(10 == $autoType){

					$this->autozh($mjList , $userList);

				}
				else if(20 == $autoType){

					//$this->autolookhp($mjList , $userList);

				}

			}

		}



		/**

		* 自动打招呼

		* 2分钟执行一次

   		*/

		private function autozh($mjList , $userList){

			krsort($mjList);

			foreach($userList as $val){

				$plcheck  = $this->checkiszh($val);

				if($plcheck === false){

					continue ;

				}



				$mjinfo = array_pop($mjList);

				if(empty($mjinfo)){

					continue ;

				}



				$uid = $mjinfo['id'];

				$data['content']= getPhotoTitle("1");

				$data['is_zh']= 1;

				$data['fromuid']= $uid;

				$data['touid']= $val['id'];

				$data['mj']=$uid;

				$data['sendtime']= time();

				$data["hash"]=$this->uidgethash($data['touid'],$uid);

				$d[]=$data;

				$ids[]=$val['id'];

				$dmsg[0]=array('title'=>'\"'.$mjinfo['user_nicename'].'\"给你打了个招呼','description'=>'收到一条招呼消息，点击查看来信。','picurl'=>'http://'.C('site_url').'/v4/xin_'.$val['sex'].'.jpg','url'=>"http://".C('site_url').U('Home/Wechat/index',array('uid'=>$mjinfo['idmd5'])));

				A("Home/Weixin")->makeTextImgbygm($val['weixin'],$dmsg);

				$this->logmj($uid,'打招呼',1,$val['id']);

			}



			if(is_array($d)){

				M("Message")->addAll($d);

				$arrstr = implode($ids,',');

				echo 'success:Id'.$arrstr;

			}

		}
		
		/**
		*自动不回复再发消息
		*
		*/

 		private function autonoreply(){
			$time=time();
			$time1=$time-(3600*24);
			$time2=$time-3600;
			$message=M('message');
			$all=M('users')->where('create_time >'.$time1)->field('id')->select();
			$res=array();
			$noreply=M('config')->where('name="noreply"')->getField('data');
			$noreply=explode('；',$noreply);
			foreach($all as $k=>$v){
				$isfind=$message->where('touid='.$v['id'].' and flag=0 and sendtime>'.$time2)->order('msgid desc')->find();
				if($isfind){
						unset($isfind['msgid']);
						$isfind['content']=$noreply[array_rand($noreply)];
						$isfind['sendtime']=$time;
						$isfind['flag']=1;
						array_push($res,$isfind);
					}
				}
			$res_rep=$message->addAll($res);
			if($res_rep){
				echo 'aaa二次回复成功aaa';
				}else{
				echo 'aaa没有二次回复aaa';
					}
			}

		/**

		* 自动访问主页

		* 2分钟执行一次

   		*/

		private function autolookhp($mjList , $userList){
			foreach($userList as $val){

				$plcheck  = $this->checkishp($val);

				if($plcheck === false){

					continue ;

				}



				$mjinfo = array_pop($mjList);

				if(empty($mjinfo)){

					continue ;

				}



				$uid=$mjinfo['id'];

				$ids[]=$val['id'];

				//微信通知主页被异性访问

				if(S($val['weixin'].'homeview') != 1 && (C('homehit') == $val['sex'] || C('homehit')== 3)){

					$dmsg[0]=array(

						'title'=>'您的个人主页被访问',

						'description'=>$mjinfo['user_nicename'].'访问了您的个人首页，去看看Ta吧。',

						'picurl'=>'http://'.C('site_url').'/v4/homehit.jpg',

						'url'=>"http://".C('site_url').U('Home/Show/index',array('uid'=>$mjinfo['idmd5']))

					);

					$re = A("Home/Weixin")->makeTextImgbygm($val['weixin'] , $dmsg);

					$this->logmj($uid,'踩主页',9,$val['id']);

					S($val['weixin'] . 'homeview' , 1 , (C('homeview')*60));

				}

			}



			if(is_array($ids)){

				$arrstr = implode($ids,',');

				echo 'success:Id'.$arrstr;

			}

		}



		private function messageTips($msgCount){

			$msgCount = intval($msgCount);

			$userMsgList = M('Message')->field("touid , count(msgid) msgnum")->where("isread=0")->group("touid")->having("msgnum > " . $msgCount)->select();

			if(empty($userMsgList)){

				echo "没有新消息需要提示给会员。";

				return false;

			}



			$userModel = M("Users");

			foreach($userMsgList as $msgItem){

				$touid = $msgItem['touid'];

				$msgnum = $msgItem['msgnum'];



				$userInfo = $userModel->field("id,weixin,sex")->where("id=".$touid." and ismj=0")->find();

				if(empty($userInfo)){

					continue;

				}



				$dmsg = array();

				$dmsg[0]=array(

					'title'=>'您有新的未读消息',

					'description'=>'您已经有'. $msgnum .'条消息没有查看了，点击查看所有来信。',

					'picurl'=>'http://'.C('site_url').'/v4/xin_'.$userInfo['sex'].'.jpg',

					'url'=>"http://".C('site_url').U('Home/Wechat/sixin')

				);

				A("Home/Weixin")->makeTextImgbygm($userInfo['weixin'] , $dmsg);

			}



			echo "新消息提醒完成.";

			return true;

		}



		/**

		* 返回马甲

	    *

		*   $mjrank =1 普通马甲

*/



		private function returnmj($val,$mjrank=1){

			$usermod = M("Users");

			$sex = $val['sex']==2?1:2;

			$w = "ismj=".$mjrank;

			$w .=" and sex =".$sex;

			if($val['cityid']>0){

				$w .=" and cityid =".$val['cityid'];

			}elseif($val['provinceid']){

				$w .=" and provinceid =".$val['provinceid'];

			}

			return $usermod->field('id,user_nicename,idmd5')->where($w)->order("rand()")->find();

		}



		private function returnMutiMj($val,$limit=200){

			$usermod = M("Users");

			$sex = $val['sex']==2?1:2;

			$w = "ismj=1";

			$w .=" and sex =".$sex;

			if($val['cityid']>0){

				$w .=" and cityid =".$val['cityid'];

			}elseif($val['provinceid']){

				$w .=" and provinceid =".$val['provinceid'];

			}

			return $usermod->field('id,user_nicename,idmd5')->where($w)->order("rand()")->limit($limit)->select();

		}



		/**

		* 检查私信未读通知间隔

		*

		*

*/

		public function checksxwd($val){

			$time = time()-86400;//一天提醒一次

			$re = M("Mjlog")->where("type=2 and time >".$time." and uid=".$val['id'])->count();

			if($re>=1){

				echo ('私信未读频率限制ID：'.$val['id']);

				return false;

			}else{

				return true;

			}

		}



		/**

		* 检查是否已打招呼

		* @param undefined $val

		*

		*/



		private function checkiszh($val){

			$zhpl = C('mjzhpl');

			$randfen = $zhpl+rand(1,5);

			$betwtime = time()-60*$randfen;

			$re = M("Mjlog")->where("type=1 and time >".$betwtime." and touid=".$val['id'])->count();

			if($re>=1){

				echo ('频率限制ID：'.$val['id']);

				return false;

			}else{

				return true;

			}

		}





		/**

		* 检查是否已踩个人主页

		* @param undefined $val

		*

		*/

		private function checkishp($val){

			$zhpl = C('mjmppl');

			$randfen = $zhpl + rand(1,5);

			$betwtime = time() - 60 * $randfen;

			$re = M("Mjlog")->where("type=9 and time >".$betwtime." and touid=".$val['id'])->count();

			if($re>=1){

				echo ('频率限制ID：'.$val['id']);

				return false;

			}else{

				return true;

			}

		}





		/**

		* 自动送礼

		*

*/

		public function autogift(){



		}









		/**

		* 马甲操作记录log

		* @param undefined $uid

		* @param undefined $type  1 马甲招呼  2未读私信提醒

		* @param undefined $text

		* @param undefined $tuid

		* @param undefined $remark

		*

*/

		private function logmj($uid,$text,$type=0,$tuid=0,$remark=0){

			$data['text']=$text;

			$data['uid']=$uid;

			$data['type']=$type;

			$data['touid']=$tuid;

			$data['time']=time();

			$data['remark']=$remark;

			M("Mjlog")->add($data);

		}



        private function resetWdsxnum(){

            $model = M();



            $sql = "update lx_user_count set wdsxnum=0 where wdsxnum<0";

            $model->execute($sql);

            

            $sql = "update lx_user_count set wdghnum=0 where wdghnum<0";

            $model->execute($sql);

            

            $sql = "update lx_user_count set wdgznum=0 where wdgznum<0";

            $model->execute($sql);



            $sql = "update lx_user_count set wdgiftnum=0 where wdgiftnum<0";

            $model->execute($sql);



            $sql = "update lx_user_count set wdsysnum=0 where wdsysnum<0";

            $model->execute($sql);

        }



}



?>