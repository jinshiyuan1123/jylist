<?php

namespace Home\Controller;

use Home\Controller\SiteController;

	/**

	*  @author lxphp

	 * http://lxphp.com

	 * 锦尚中国源码论坛提供

	 */





class ShowController extends SiteController {

	

	public function __construct() {

		parent::__construct ();

		if(!$this->uinfo){

		   redirect(U("Public/index"));

		   exit;

		} 

		$this->assign('nav', 'Show');

		}

	

	

	

		public function index(){

			$uid = I("get.uid",'','trim');

			if(!$uid) exit("err");

			$umod = M("Users");			

			$uinfo = $umod->where("idmd5='".$uid."'")->find();
			
			$uid =$uinfo["id"];			

			unset($uinfo['pass']);

			$media=$this->getMedia($uinfo['user_nicename'].'详情');

    		$this->assign('media', $media);

    		

			$area = $this->getuserarea($uinfo);
			if($uinfo['ismj']==1){
				$area=cookie("dw_provinceid").cookie("dw_cityid");
				}
			$age = date("Y",time())-$uinfo['age'];	

			

			$uinfo = $this->get_jifen_rank_name($uinfo);			

			if(C('homehit')>0 && $uinfo['weixin'] && $this->uinfo['sex']!=$uinfo['sex'] && S($uinfo['weixin'].'homeview')!=1){//微信通知主页被异性访问

				if(C('homehit')==$uinfo['sex'] || C('homehit')==3){

					$dmsg[0]=array('title'=>'您的个人主页被访问','description'=>$this->uinfo['user_nicename'].'访问了您的个人首页，去看看Ta吧。','picurl'=>'http://www.yueai.me/v4/homehit.jpg','url'=>"http://".C('site_url').U('Home/Show/index',array('uid'=>$this->uinfo['idmd5'])));					

					$re = A("Home/Weixin")->makeTextImgbygm($uinfo['weixin'],$dmsg);

					S($uinfo['weixin'].'homeview',1,(C('homeview')*60));

				}

			}

			

					

			$pinfo = M("user_profile")->where("uid=".$uid)->find();

			$lxfs_config  = unserialize($pinfo['lxfs_config']);			

			$this->assign ( 'lxfs_config', $lxfs_config );

						

			$usercountmod =  M("User_count");	  			

			$user_count = $usercountmod->where("uid=".$uid)->find();

			

			$photomod = M("User_photo");

			if(!$user_count['photonum']&& $uinfo['ismj']>0){

				$pcount = $photomod->where("uid=".$uid)->count();

				if($pcount>0){

				$this->tongji($uid,'photonum',$pcount);

				$user_count['photonum']=$pcount;

				}

			}


			$photoarr = $photomod->where("uid=".$uid.' and flag=1 and phototype=0')->order("photoid desc")->limit(3)->select();			

			$simi = $photomod->where("uid=".$uid.' and flag=1 and phototype=1')->order("photoid desc")->limit(3)->select();

			

			

			

			$this->assign('simiphoto', $simi);

			$this->assign('photoarr', $photoarr);

	

			$giftlistmod = M("Giftlist");	

			$giftlist = $giftlistmod->where("touid=".$uid)->order("giftlist_id desc")->limit(3)->select();

			

			$this->assign('info', $uinfo);

			$this->assign('giftlist', $giftlist);

			$this->get_gz_status($uid);

			

			if($simi)

			$this->allowsm($uid);			

			else

			$this->get_qmd_val($uid);	

			//dump($photo_config);

			//if(!$pinfo['monolog']) $pinfo['monolog']=getPhotoTitle(2);

			$this->assign('userCount', $user_count);			

			$this->assign('qmd', $qmd);

			$this->assign('pinfo', $pinfo);

			$SetProfile =C('SetProfile');

			if($SetProfile){

				$UserProfileMod =  M('UserProfile');

				foreach ($SetProfile as $k=>$v){

					if($pinfo[$k]){

						$miyu[$v['name']] = $v['info'][$pinfo[$k]] ;

					}else{

						if($uinfo['ismj']>0&&$k != 'code3'){

						  $n =  rand(1,count($v['info']));

						  $re =  $UserProfileMod ->where('uid = '.$uid)->setField($k,$n);

						  if($re) $miyu[$v['name']] = $v['info'][$n] ;

						}

					}

				}

				$this->assign('miyu', $miyu);

			}

			

			$this->assign('lahei', M('User_lahei')->where("fromuid= ".$this->uinfo['id']." and touid=".$uid)->getField('status'));

			$this->assign('age', $age);
			if($uinfo['ismj']==1){
				$area=$this->uinfo['provincename'].$this->uinfo['cityname'];;
				}
			$this->assign('area', $area);

			$this->siteDisplay ( 'index_user_xq' );

		}

	

	

	public function photo(){

			$pid = I("get.pid",'','trim');

			$phohomod = M("User_photo");

			$phoarr = $phohomod->where("idmd5='$pid'")->find();

			$uid = $phoarr["uid"];

			if($phoarr['phototype']==1){

				$res = $this->allowsm($uid);

				if($res===false) $this->error('您无权查看此私密照');

			}			

			$umod = M("Users");			

			$uinfo = $umod->where("id='".$uid."'")->find();

			$uinfo = $this->get_jifen_rank_name($uinfo);

			$usercountmod =  M("User_count");	  			

			$user_count = $usercountmod->where("uid=".$uid)->find();

			if(!$phoarr['title']){

				$monolog = M("user_profile")->where("uid=".$uid)->getField("monolog");

				$this->assign('monolog', $monolog);

			}

			

			$gtphoto = $phohomod->where("uid='$uid' and photoid>".$phoarr['photoid'].' and phototype=0')->order('photoid asc')->find();

			$ltphoto = $phohomod->where("uid='$uid' and photoid<".$phoarr['photoid'].' and phototype=0')->order('photoid desc')->find();

			

		

			$this->assign('userCount', $user_count);

			$this->assign('gtphoto', $gtphoto);

			$this->assign('ltphoto', $ltphoto);

			$this->assign('photo', $phoarr);

			$this->assign('info', $uinfo);

			$media=$this->getMedia($uinfo['user_nicename'].'的照片详情');

			

			

			$giftlistmod = M("Giftlist");	

			$giftlist = $giftlistmod->where("touid=".$uid)->order("giftlist_id desc")->limit(20)->select();	

			

			$fromuids = array();	

		foreach($giftlist as $k => $v ){

			$fromuids[] = $v['fromuid'];

		}

		$fromuids = array_unique($fromuids);

		$fromuids = join(',', $fromuids);		

		

		$result = $this -> getInfo($fromuids);

		

		foreach($result as $k => $v){

			$uinfo[$k] = $this -> get_jifen_rank_name($v);

			

		}

			$report = C("Reporttypes");

			

			

			$this -> commentList($phoarr['photoid']);

			$this -> assign('report',$report);		

			$this -> assign('giftlist', $giftlist);    	

			$this -> assign('jibie',$uinfo);

			$this -> assign('result',$result);

    		$this -> assign('media', $media);

    		$this -> get_gz_status($uid);

			$this -> siteDisplay ( 'index_photo_xq' );

		}

		

/**

 * 评论列表

 * 

 */		

	public function commentList($photoid){

	   

		$mod = M('Comment') ;

		$where['a.photoid'] = $photoid;

		$where['a.flag'] = 1;

		$count = $mod ->alias('a')->where($where) -> count();

		$Page = new \Think\Page($count, 10);

		

		$show = $Page -> show();

		$list = $mod ->alias('a')->join('__USERS__ b on a.uid = b.id')->field('a.*,b.avatar,b.user_nicename,b.user_rank')->where($where) -> order('id desc') -> limit($Page -> firstRow . ',' . $Page -> listRows) -> select();

		foreach ($list as $k=> $v){

			$list[$k]['time'] = $this->q_format_date($v['time']);

		}



		//var_dump($mod->_sql());

		$this->assign('commentList', $list);

		  if($_GET['p']>=200)exit;

		if (I("get.ajax") == 1){

			if($list){

				$data['status'] = 1;

				$data['info'] = $this->sitefetch('ajax_comment');

			}else{

				$data['status'] = -1;

			}

			 $this->ajaxReturn($data);

		}

		

	}	

/**

 * 评论照片

 */

	public function comment(){

		$content = $this->string_filter(I('post.content','','trim'));

		$photoid = I('post.photoid','','intval');

		

		if(empty($content)) $this->error('请填写评论内容');

		if(empty($photoid)) $this->error('系统繁忙！');

		$flag = C('comment_flag')>0?0:1;

		$re = M('Comment')->add(array('uid'=>$this->uinfo['id'],'photoid'=>$photoid,'content'=>$content,'flag'=>$flag,'time'=>time()));

		if($re){

			if($flag==1){

				$_GET['p'] = 1;

				$_GET['ajax'] = 1;

				$uid = M('UserPhoto')->where('photoid = '.$photoid)->getField('uid');

				$this->tongji($uid, 'comment',1);

				$this->commentList($photoid);

			}else{

				$this->setSystemTj('commentFlag',1);

				$this->success('评论成功，审核中！');

			}

	

		}else{

			$this->error('系统繁忙！');

		}

	

	}



	

	

	

	

		

		

	/**

	* 收到的礼物列表

	* 20160519

	* 

*/

	public function giftlist(){

		$media = $this->getMedia('Ta收到的礼物');

		$this -> assign('media', $media);

		$uid = I("get.uid",'','trim');

		if(!$uid) exit("err");

		$this -> assign('uid',$uid);

		$umod = M("Users");			

		$uid  = $umod -> where("idmd5='".$uid."'") -> getField('id');	

		$giftlistmod = M("Giftlist");	

		$count = $giftlistmod -> where("touid=".$uid) -> count();

		$Page = new \Think\Page($count,12);



		$giftlist = $giftlistmod -> where("touid=".$uid) -> order("giftlist_id desc") -> limit($Page->firstRow.','.$Page->listRows) -> select();

		$this -> assign('giftlist', $giftlist);	

		

		$fromuids = array();	

		foreach($giftlist as $k => $v ){

			$fromuids[] = $v['fromuid'];

		}

		$fromuids = array_unique($fromuids);

		$fromuids = join(',', $fromuids);		

		

		$result = $this -> getInfo($fromuids);

		

		foreach($result as $k => $v){

			$uinfo[$k] = $this -> get_jifen_rank_name($v);		

		}

		

		$this -> assign('jibie',$uinfo);

		$this -> assign('result',$result);

			

		if($_GET['p'] >= 200){ //超过200条   不再下拉加载

			exit;

		}

		

		if(I("get.ajax") == 1){ //下拉加载请求		

			if($giftlist){				

				$data = $this -> sitefetch('ajax_giftlist');	

				$this -> ajaxReturn($data);						

			}

			exit;	

		}

			

		$this -> siteDisplay ( 'index_user_record_gift' );

	}

	

	/**

	* 私密照列表

	* 

*/

	public function photolist(){

			$phototype = I('phototype',2,'intval');

			

			

    		$this->assign('media', $media);

    		$uid = I("get.uid",'','trim');

			$this -> assign('uid',$uid);

			if(!$uid) exit("err");

			$umod = M("Users");			

			$re = $umod->where("idmd5='".$uid."'")->find();

			$uid =$re["id"];			

			unset($re['pass']);

			

			if($phototype == 1){

				$media=$this->getMedia('Ta的私密照列表');

				$this -> assign("name","Ta的私密照");

				$res = $this->allowsm($uid);

				if($res===false) $this->error('您无权查看此私密照');

			}

			

			if($phototype == 0){

				$media=$this->getMedia('Ta的公开照列表');

				$this -> assign("name","Ta的公开照");

			}

			

			

			

			

			$photomod = M("User_photo");

			$count = $photomod -> where("uid=".$uid.' and phototype='.$phototype) -> count();

			$Page = new \Think\Page($count,10);

			

			$photoarr = $photomod->where("uid=".$uid.' and phototype='.$phototype)->order("photoid desc")->limit($Page->firstRow.','.$Page->listRows)->select();

//			dump($photoarr);

//			exit;

			if( $_GET['p'] >= 200 ){

				exit;

			}

			

			if( I("get.ajax") == 1){

				$this -> ajaxReturn($photoarr);

			}

			

					

			$this -> assign('phototype',$phototype);

			$this -> assign('info', $re);			

			$this -> assign('photoarr', $photoarr);

		

			$this -> siteDisplay ( 'index_user_photo_list' );

	}

	

		/**

	* 公开照列表

	* 

*/

	

	public function gkphotolist(){

		$media=$this->getMedia('Ta的公开照列表');

    		$this->assign('media', $media);

    		$uid = I("get.uid",'','trim');

			if(!$uid) exit("err");

			$umod = M("Users");			

			$re = $umod->where("idmd5='".$uid."'")->find();

			$uid =$re["id"];			

			unset($re['pass']);

			

			$photomod = M("User_photo");

			$photoarr = $photomod->where("uid=".$uid.' and phototype=0')->order("photoid desc")->limit(3)->select();

			

			$this->assign('info', $re);			

			$this->assign('photoarr', $photoarr);

		

			$this->siteDisplay ( 'index_user_photo_list' );

	}

	

	/**

	* 私密照权限

	* 

*/

	public function allowsm($uid){

		$this->assign('allowsmz', 1);

		if(!$uid) return false;

		if($this->uinfo['id']==$uid) return true;

			$qmdarr = $this->get_qmd_val($uid);

				

			$photo_config = M('UserProfile')->where('uid = '.$uid)->getField('photo_config');

			if($photo_config) $photo_config = unserialize($photo_config);

			if(!$photo_config[0]) $photo_config[0]= C('check_simi_moren');

			if(!$photo_config[1]) $photo_config[1]= C('sphoto_default');

			$pay = I("get.pay",'','intval');			

			if($pay==1 && $qmdarr['allowsm']!=$this->uinfo['id'] && $qmdarr['allowsm']!=-1){

				$ffee = (-1)*$photo_config[1];

				$re = $this->changemoney($this->uinfo['id'],$ffee,7,'查看私密照'.$ffee,'photo','',0,0,$uid,7);

				if($re>0){

					$saveid = $qmdarr['allowsm']==$uid?'-1':$this->uinfo['id'];					

					$qmdarr['allowsm']=-1;						

					$User_qinmidu = M("User_qinmidu");

					$w['hash']=$this->uidgethash($uid,$this->uinfo['id']);

					$User_qinmidu->where($w)->setField('allowsm',$saveid);

								

					$fee = $photo_config[1]/100*C('sphoto_fld_nv');

					$this->changemoney($uid,$fee,8,'私密照被查看获得返利+'.$fee,'photo','',1,0,$this->uinfo['id'],8);

					$ajax = I("get.ajax",'','intval');

					if($ajax) $this->success('pay ok');

				}				

				if($re==-1) $this->error('您的余额不足与支付，请先充值',-1,5);

			}

			

							

			$this -> assign('photo_config', $photo_config);					

			if($photo_config[0]<=$qmdarr['qmd']){

				$this->assign('allowsmz', 1);

				return true;

			}else if($qmdarr['allowsm']==$this->uinfo['id'] || $qmdarr['allowsm']==-1){

				$this->assign('allowsmz', 1);

				return true;

			}else{

				$this->assign('allowsmz', 0);

				return false;

			}

			

	}

	

	

}

		