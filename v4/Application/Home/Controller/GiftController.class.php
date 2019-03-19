<?php
namespace Home\Controller;
use Home\Controller\SiteController;
	/**
	*  @author lxphp
	 * http://lxphp.com
	 * 锦尚中国源码论坛提供
	 */


class GiftController extends SiteController {
	
	public function __construct() {
		parent::__construct ();
		if(!$this->uinfo){
		   redirect(U("Public/index"));
		   exit;
		} 
		$this->assign('nav', 'Gift');
		}
	
	
	/**
	* 礼物  
	* @since 20160516
	* @author @紫竹
	* @qq  263960836
	* 
*/
		public function index(){
		
		$touid = I("get.uid",'','trim');
		 $media=$this->getMedia('虚拟商城');
    	$this->assign('media', $media);	
		$w = I("post.data",'','trim');
		if($w){
			if($w!=-1){
				$w2= explode(',',$w);
				if($w2[0]==0 && $w2[1]>0)
				$where['_string']="price <".$w2[1];
				if($w2[0]>0 && $w2[1]>0)
				$where['_string']="price between ".$w2[0]." and ".$w2[1];
				if($w2[1]==0 && $w2[0]>0)
				$where['_string']="price >".$w2[0];			
			}
			//$where['price']=$w;
		}
		$User = M('Gift');		
		$count = $User ->where($where) -> count();					
		$Page = new \Think\Page($count, 30);		
		$show = $Page -> show();				
		$list = $User->field('*')-> where($where) -> order('create_time desc,gift_id desc') -> limit($Page -> firstRow . ',' . $Page -> listRows) -> select();
		//echo M()->_sql();
		
		$this -> assign('touid', M("Users")->where("idmd5='$touid'")->getField("id"));
		$this -> assign('list', $list);
		if(IS_AJAX){
		if($list) $data = $this->sitefetch('ajax_gift_shop');		
		$this -> ajaxReturn($data);
		}		
		$this->siteDisplay ( 'gift_shop' );
		}
		
		/**
		* 获取礼物价格之类
		* 
*/
		public function show(){
			$gid = I("post.gid",'','intval');
			$User = M('Gift');
			$re = $User->where("gift_id=".$gid)->find();
			$userdb = M("Users")->field('money,user_rank')->where("id=".$this->uinfo["id"])->find();
			$this->setUserinfo('money',$userdb['money']);
			$this->setUserinfo('user_rank',$userdb['user_rank']);
			$re['mymoney']=$userdb['money'];
			$giftvipzhe = C("vipgiftzhe");
			$re['pricevip']=$re['price']/10*$giftvipzhe;
			if($userdb['user_rank']>0)
			$re['myprice'] = $re['pricevip'];
			else
			$re['myprice'] =$re['price'];
			if($re)
			$this->success($re);
			else
			$this->error("获取失败！");
		}
		
		
		/**
		* 送礼
		* 
*/
		public  function sendgift(){
			$myid = $this->uinfo["id"];
			$touid = I("post.touid",'','intval');
			$giftid = I("post.giftid",'','intval');
			if(!$giftid || !$touid || !$myid) $this->error('err');		
			$giftmod = M('Gift');
			$giftinfo = $giftmod->where("gift_id=".$giftid)->find();
			$data["fromuid"]=$myid;
			$data["touid"]=$touid;
			$data["gift_price"]=$giftinfo['price'];;
			$data["time"]=time();
			$data["giftnum"]=I("post.giftnum",1,'intval');
			$data["gift_id"]=$giftid;
			$data["gift_image"]= $giftinfo['images'];			
					
			$logtab['table']="giftlist";
			$logtab['data']=$data;	
			
			if($this->uinfo['user_rank']>0){
				$giftvipzhe = C("vipgiftzhe");
				$data['gift_price'] = $data['gift_price']/10*$giftvipzhe;
			}	
			$ip =get_client_ip();	
			$re = $this->changemoney($myid,(-1)*$data['gift_price']*$data['giftnum'],1,'送礼消耗','',$logtab,0,$ip,$touid,1);	//付费					
			if($re>0){
			$this->tongji($myid,'sendgiftmoney',$data['gift_price']*$data['giftnum']);	
			$fljifen = 	$giftinfo['jifen']?$giftinfo['jifen']*$data['giftnum']:$giftinfo['price']/100*C('gift_def')*$data['giftnum'];
			$this->changejifen($fljifen,3,'收到'.$this->uinfo['user_nicename'].'礼物获得',$touid,0,$myid,$ip);//积分
			$flmoney = $giftinfo['rebate']?$giftinfo['rebate']*$data['giftnum']:$giftinfo['price']/100*C('gift_fld_nv')*$data['giftnum'];
			if(C('giftnotice')>0 && $giftinfo['price']>C('giftnotice'))
			$notice = 1;
			else
			$notice = 0;
			$reff = $this->changemoney($touid,$flmoney,3,'收到'.$this->uinfo['user_nicename'].'礼物获得返利',0,0,$notice,$ip,$myid,3);//返利
			$qmdfee = $giftinfo['qmd']?$giftinfo['qmd']*$data['giftnum']:C('gift_qmd')*$data['giftnum'];
			$this->changeqinmidu($touid,$myid,$qmdfee,2,'收到礼物');//亲密度
			$tongji['wdgiftnum']=1;
			$tongji['sumgift']=$data["giftnum"];
			if($reff>0)
			$tongji['giftmoney']=$flmoney;
			$this->tongjiarr($touid,$tongji);
			$this->setUserinfo('money',$re);
			$touser_nicename = M('Users')->where('id='.$touid)->getField('user_nicename');				
			$this->success($touser_nicename,$re);	
			}else{
				$this->error('err',$re);
			}
		}
		
		
			public function giftlist(){//礼物
	
	   	$myuid = $this->uinfo["id"];
		$ucoundmod =  M("User_count");		
		$user_count =$ucoundmod->where("uid=".$myuid)->find();
			
		$where ="touid=".$myuid;	
		$User = M("Giftlist as s");
		$count = $User -> where($where) -> count();		
		$Page = new \Think\Page($count, 15);		
		$show = $Page -> show();	
		$list = $User->field("u.avatar,u.user_nicename,s.gift_price,s.giftnum,s.gift_image,s.time,u.user_rank,u.id,u.idmd5")->join("__USERS__ as u ON u.id=s.fromuid")->where($where) -> order('s.giftlist_id desc') -> limit($Page -> firstRow . ',' . $Page -> listRows) -> select();
		
		$this->assign('list', $list);
		if($_GET['p']>=200)exit;
		if (I("get.ajax") == 1){
			$this -> ajaxReturn($this->sitefetch('ajax_sixin_c'));
		}else{
			$User->where("touid=".$myuid)->setField("touser_isread",1);
			$ucoundmod->where("uid=".$myuid)->setField("wdgiftnum",0);
		}				
		$this->assign('user_count', $user_count);
		$media=$this->getMedia('礼物');
    	$this->assign('media', $media);
		$this->assign('nav', 'Wechat');
		$this->assign('nav2', 'sixin_c');
		$this->siteDisplay ( 'sixin_c' );
	}
	
	
}

?>