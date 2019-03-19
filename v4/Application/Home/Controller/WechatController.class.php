<?php

namespace Home\Controller;

use Home\Controller\SiteController;

	/**

	*  @author lxphp

	 * http://lxphp.com

	 * 锦尚中国源码论坛提供

	 */





class WechatController extends SiteController {

	

	public function __construct() {

		parent::__construct ();

		if(!$this->uinfo){

		   redirect(U("Public/index"));

		   exit;

		} 

		$this->assign('nav', 'Wechat');

		}

		public function sixin(){//私信列表		

        $myuid = $this->uinfo["id"];			

		$user_count = M("User_count")->where("uid=".$myuid)->find();

		$message = M("Message");

		

		//->cache('ltlist',30)	

		/*$list= M()->table("(select * from __MESSAGE__ order by msgid desc) as tb")->field("msgid,content,fromuid,touid,isread,sendtime,hash,sum(if(isread=0,'1','0')) as count")->where("fromuid =".$myuid." or touid=".$myuid)->group("hash")->order('msgid desc')->select();*/

		$where = "fromuid =".$myuid." or touid=".$myuid;		

		$count = $message ->where($where) -> count();

		$Page = new \Think\Page($count, 15);

		$show = $Page -> show();

		//$list = $message->distinct(true)->field("hash")->where($where)->order('msgid desc')->limit($Page -> firstRow . ',' . $Page -> listRows)->select();

		$list = $message->field("max(msgid)as mid,msgid,hash,fromuid,touid")->where($where)->group("hash")->order('mid desc')->limit($Page -> firstRow . ',' . $Page -> listRows)->select();

		foreach($list as $key =>$val){

			$arr[]=$val['fromuid'];

			$arr[]=$val['touid'];

			$arr2[]=$val["mid"];

		}	

		$arr = array_unique($arr);

		$arrstr = implode($arr,',');

		$arrstr2 = implode($arr2,',');

		if($arrstr)

		$userarr = M("Users")->field("id,idmd5,avatar,user_nicename,user_rank")->where("id in (".$arrstr.")")->select();
		if($arrstr2)

		$list2 = $message->field("content,msgid,sendtime,is_zh,fromuid,touid,isread,type")->where("msgid in (".$arrstr2.")")->select();		

		foreach($userarr as $key =>$val){

			unset($userarr[$key]);

			$userarr[$val['id']]=$val;

		}
		

		foreach($list2 as $key =>$val){

			unset($list2[$key]);

			$list3[$val['msgid']]=$val;

		}	
		
		foreach($list as $key =>$val){		

			$list[$key]['list2arr']=$list3[$val['mid']];

			if($val['fromuid']==$myuid){			

				$list[$key]['avatar']=$userarr[$val['touid']]['avatar'];

				$list[$key]['user_nicename']=$userarr[$val['touid']]['user_nicename'];

				$list[$key]['user_rank']=$userarr[$val['touid']]['user_rank'];

					$list[$key]['uid']=$userarr[$val['touid']]['idmd5'];

			}else{				

				$list[$key]['avatar']=$userarr[$val['fromuid']]['avatar'];

				$list[$key]['user_nicename']=$userarr[$val['fromuid']]['user_nicename'];

				$list[$key]['user_rank']=$userarr[$val['fromuid']]['user_rank'];

				$list[$key]['uid']=$userarr[$val['fromuid']]['idmd5'];

			}

			

			if($list[$key]['list2arr']['isread']==0 && $list[$key]['list2arr']['fromuid']!=$myuid){

				$list[$key]['list2arr']['noread']=1;

			}			

			if($list[$key]['list2arr']['is_zh']==1 && $list[$key]['list2arr']['fromuid']==$myuid){

				unset($list[$key]);

			}

	

		}
		

		$media=$this->getMedia('私信');

		cookie('wdsxnum',0,3600);

    	$this->assign('media', $media);

    	$this->assign('list', $list);

    	$this->assign('nav2', 'sixin_a');

    	$this->assign('user_count', $user_count);

    	$this->assign ( 'page', $show );

    	if($_GET['p']>=200)exit;

    	if (I("get.ajax") == 1){

    		if($list) $data = $this->sitefetch('ajax_sixin_a');

    		$this->ajaxReturn($data);

    	}  	

		$this->siteDisplay ( 'sixin_a' );

		}

	

	

	public function subscribe(){//关注

	

	   	$myuid = $this->uinfo["id"];

		$usercountmod =  M("User_count");		

		$user_count = $usercountmod->where("uid=".$myuid)->find();

			

		$where ="touid=".$myuid;	

		$User = M("User_subscribe");		

		$count = $User -> where($where) -> count();		

		$Page = new \Think\Page($count, 15);		

		$show = $Page -> show();	

		$list = $User->alias('fs')->field("u.avatar,u.user_nicename,fs.time,u.user_rank,u.id,u.idmd5")->join("__USERS__ as u ON u.id=fs.fromuid")->where($where) -> order('fs.id desc') -> limit($Page -> firstRow . ',' . $Page -> listRows) -> select();

		

		$this->assign('list', $list);

		if($_GET['p']>=200)exit;

		if (I("get.ajax") == 1){

			if($list) $data = $this->sitefetch('ajax_sixin_b');

			$this->ajaxReturn($data);

		}else{

			$User->where("touid=".$myuid)->setField("touser_isread",1);		

			$usercountmod->where("uid=".$myuid)->setField("wdgznum",0);		

		}		

	    $this->assign('user_count', $user_count);

		$media=$this->getMedia('关注');

		cookie('wdsxnum',0,3600);

    	$this->assign('media', $media);

		$this->assign('nav2', 'sixin_b');

		$this->siteDisplay ( 'sixin_b' );

	}

	

	



	

	

	

	public function system(){//系统

		$usercountmod =  M("User_count");	

	    $myuid = $this->uinfo["id"];		

		$user_count = $usercountmod->where("uid=".$myuid)->find();

	

		$where ="uid=".$myuid;	

		$User = M("System_msg");		

		$count = $User -> where($where) -> count();		

		$Page = new \Think\Page($count, 15);		

		$show = $Page -> show();	

		$list = $User->field("time,msg_content,msg_type,touid")->where($where) -> order('msg_id desc') -> limit($Page -> firstRow . ',' . $Page -> listRows) -> select();

		$typearr = C("Systemmsgtype");

		

		foreach($list as $key=>$val){

			$list[$key]['type']=$typearr[$val['msg_type']];

			if($val['touid']>0)

			$arr[]=$val['touid'];

		}

		$ids = implode($arr,',');

		$idsinfo = $this->getInfo($ids);

		

		$this->assign('list', $list);

		if($_GET['p']>=200)exit;

		if (I("get.ajax") == 1){

			if($list) $data = $this->sitefetch('ajax_sixin_d');	

			$this -> ajaxReturn($data);

		}else{

			$User->where("uid=".$myuid)->setField('msg_status',1);

			$usercountmod->where("uid=".$myuid)->setField("wdsysnum",0);

		}	

		cookie('wdsxnum',0,3600);	

		$this->assign('idsinfo', $idsinfo);

	    $this->assign('user_count', $user_count);

		$media=$this->getMedia('系统');

    	$this->assign('media', $media);

		$this->assign('nav2', 'sixin_d');

		$this->siteDisplay ( 'sixin_d' );

	}

	

	

	

	

	public function index(){//聊天

		$uid = I("get.uid",'','trim');

		if(!$uid) exit("err");

			$umod = M("Users");			

			$re = $umod->where("idmd5='".$uid."'")->find();

			$uid =$re["id"];		

		unset($re['pass']);		

		$this->get_gz_status($uid);

		$this->get_qmd_val($uid);

		

		$message = M("Message");

		

		$data["hash"]=$this->uidgethash($uid,$this->uinfo['id']);	

		

		$msglist = $message ->where($data)->limit(15)->order("msgid desc")->select();

		$msglist = A("Home/Public")->array_sort($msglist,'msgid');

		if($_GET['noread']==1){	

		//更新已读

		$w['hash']=$data["hash"];

		$w['touid']=$this->uinfo['id'];

		$w['isread']=0;

		$count = $message ->where($w)->count();

		if($count>0){

			M("User_count")->where("uid=".$this->uinfo['id'])->setDec("wdsxnum",$count);

			$w2['hash']=$data["hash"];

			$w2['touid']=$this->uinfo['id'];

			$message ->where($w2)->save(array("isread"=>1,'redtime'=>time()));

		}		

		}

		

		//更新已读		

		if($this->uinfo['sex']==1){

			$isvip  = $this->isvip($this->uinfo);		

		if(!$isvip){

			$resf = M("message_log")->where("hash='".$data["hash"]."'")->count();

			if(!$resf){

			$ltrnum = M("User_count")->where("uid=".$this->uinfo['id'])->getField('ltrennum');

			if($ltrnum>C('viltren')){			

				$this->assign('viltren', 1);

			}			

			}			

		}

		}

		cookie('wdsxnum',0,3600);						

		$media=$this->getMedia('和'.$re['user_nicename'].'聊天');
		$this->assign('iswx',iswx()?1:'');
		
		$this->assign('lahei', M('User_lahei')->where("touid= ".$this->uinfo['id']." and fromuid=".$uid)->getField('status'));

    	$this->assign('media', $media);

    	$this->assign('msglist', $msglist);

    	$this->assign('info', $re);

		$this->siteDisplay ( 'siliao' );

		}

		

		

		

		



		

}



?>