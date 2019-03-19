<?php

namespace Home\Controller;

use Home\Controller\SiteController;

	/**

	*  @author lxphp

	 * http://lxphp.com

	 * 锦尚中国源码论坛提供

	 */





class NearbyController extends SiteController {

	

	public function __construct() {

		parent::__construct ();

		if(!$this->uinfo){

		   redirect(U("Public/index"));

		   exit;

		} 

		

		}

	

	

	/**

	* 附近的  

	* @since 20160505

	* @author @紫竹

	* @qq  263960836

	* 

*/

		public function index($w=""){	

		if(cookie("dw")==2){

			$this -> assign('dw', 2);	

			$this -> assign('nav','Nearby');		

			$this->siteDisplay ( 'fujinderen' );

			exit;

		}	

				

		$media=$this->getMedia('附近');

    	$this->assign('media', $media);

		$p =I("get.p",'','intval');

		$querypama = $this->get_areaid_toquery();
		$sex = $this->uinfo['sex']==1?'2':'1';

		$where = "u.sex =  ".$sex;

		if($querypama){

			$where2 = $where;

			$where .= " and (u.".$querypama['type'].'='.$querypama['id'].' or u.cityid=0)';			

		}

		if($w==2){

			$where=$where2;

		}		

		$User = M("User_profile");	

		$count = $User->alias('p')->cache(true,300)->join("__USERS__ as u ON u.id=p.uid")->where($where) -> count();		

		$Page = new \Think\Page($count, 15);	

		$show = $Page -> show();

		$md5key = md5($where.$_GET["p"].'www.yueai.me');		

		$list = $User->alias('p')->cache($md5key,300)->field('u.id,u.idmd5,u.jifen,u.sex,u.last_login_time,u.user_rank,u.user_nicename,u.avatar,u.age,u.provinceid,u.cityid,p.astro,p.monolog')->join("__USERS__ as u ON u.id=p.uid")-> where($where) -> order('u.ismj asc,u.user_rank desc,u.last_login_time desc,id desc') -> limit($Page -> firstRow . ',' . $Page -> listRows) -> select();

		$xinzuo = C("Constellation");		

		//$list = $User ->field('id,user_nicename,avatar,age,provinceid,cityid')-> where($where) -> order('id desc') -> limit($Page -> firstRow . ',' . $Page -> listRows) -> select();	

		if(!$list&&$w!=2) {

			$this->index('2');

			exit;

			}

	

				

		foreach($list as $key=> $val){

			$arr[]=$val['id'];

			$list[$key]['aurl'] = U("Show/index", array("uid" => $val['idmd5']));

			if($val['age']>1000)			

			$list[$key]['age'] = date("Y",time())-$val['age'];

			$list[$key]['area'] = $this->getuserarea($val);

			if(is_numeric($val['astro']))

			$list[$key]['astro'] = $xinzuo[$val['astro']];

			if($val['km']==0)						

			$list[$key]['km'] = round(rand(1,20000)/1000,1);//虚假千米数 			

		}

		

		$arrstr = implode($arr,',');	

		$userarr = M("Message")->field("fromuid,touid")->where("fromuid = ".$this->uinfo['id']."  and touid in (".$arrstr.")")->select();

		foreach($userarr as $key =>$val){

			unset($userarr[$key]);

			$userarr[$val['touid']]=$val['fromuid'];

		}	

		

		foreach($list as $key=> $val){

			if($userarr[$list[$key]['id']]==$this->uinfo['id']){

				$list[$key]['iszhd']=1;

				$arrstr = str_replace($list[$key]['id'],'',$arrstr);

			}			

			if(cookie("dzh".$list[$key]['id'])==1){

				$list[$key]['iszhd']=1;

				$arrstr = str_replace($list[$key]['id'],'',$arrstr);

			}

			

		}

		

		//dump(S($md5key));

		

		if(!S($md5key.'hc')){

		S($md5key.'hc',1,300);

		S($md5key,$list,300);

		}	

		$this -> assign('list1', $list);				

		if($p>=200)exit;

		if (I("get.ajax") == 1){

			$data['info']=$this->sitefetch('ajax_fujinderen');

			$data['ids']=$arrstr;

			$this -> ajaxReturn($data);

		}else

		$this -> assign('ids', $arrstr);

		$this -> assign('dw', 0);

		if(!cookie("dw")){

			$this -> assign('dw', 1);

			cookie('dw',1,86400);

		}		

		$this -> assign('nav','Nearby');	

		$this->siteDisplay ( 'fujinderen' );

		}

	

		

}



?>