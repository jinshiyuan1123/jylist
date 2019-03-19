<?php

namespace Home\Controller;

use Home\Controller\SiteController;

/**

 * 橙橙网络科技有限公司

 * http://bbs.52jscn.com

 */

class IndexController extends SiteController {

    public function index($w=""){
		if(!$this->uinfo){

		   redirect(U("Public/index"));

		   exit;

		}  
		if(C("onlywx")==1 && strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') === false){

			$this->siteDisplay('jg_qzwxdk');

			exit;

		}  	

        $media=$this->getMedia('推荐');

    	$this->assign('media', $media);
		

		$where = " 1=1 ";

		

		$sex = I("post.sex",'','intval');

		if(!$sex){//默认

			$sex = $this->uinfo['sex']==1?'2':'1';

			$cookiesex =cookie('defsex');		

			$sex = $cookiesex?$cookiesex:$sex;		

		}else{

			$sex = $sex;

			cookie('defsex',$sex,3600);

		}		

		if($sex>0)

		$where.=" and sex=".$sex;
		
		$where.=" and ismj=1";
		
		$age = I("post.age",'','intval');	

		if($age>0){

			$now = date("Y");

			switch($age){

				case 1:				

				$where.=" and age between ".($now-25)." and ".($now-18);

				break;

				case 2:				

				$where.=" and age between ".($now-35)." and ".($now-26);

				break;

				case 3:				

				$where.=" and age between ".($now-40)." and ".($now-36);

				break;

				case 4:				

				$where.=" and age between ".($now-50)." and ".($now-40);

				break;

				case 5:				

				$where.=" and age between ".($now-100)." and ".($now-50);

				break;

			}

		}	

		

		$provinceid = I("post.provinceid",'','intval');	

		$cityid = I("post.cityid",'','intval');			

		$querypama = $this->get_areaid_toquery($provinceid,$cityid);	

		if($querypama['id']){

			$where2 = $where;

			$where .= " and (".$querypama['type'].'='.$querypama['id'].' or cityid=0)';			

		}
		
		if($w==2){

			$this->assign('qg',1);

			$where =$where2;

		}			

		$_GET['p']=$_POST['p'];		

		$User = M('Users');		

		$count = $User ->cache(true,300)-> where($where) -> count();					

		$Page = new \Think\Page($count, 15);

		$show = $Page -> show();				
		
		$list = $User->cache(true,300)->field('id,user_nicename,avatar,idmd5')-> where($where) -> order('type desc,last_login_time desc,id desc') -> limit($Page -> firstRow . ',' . $Page -> listRows) -> select();	

		if(!$list && $w != 2) {			

			$this->index('2');

			exit;

		}		

		foreach($list as $key=> $val){

			$list[$key]['aurl'] = U("Show/index", array("uid" => $val['idmd5']));

		}	

					

		//地区

		$areaList = A('Home/Site')->get_area();    	
		foreach ($areaList as $v){

			if($v['rootid']==0){

				$province[] =$v;

			}

			if($v['rootid']==$querypama['provinceid']){

				$city[] =$v;

			}    			

		}

		$this->assign('province',$province);

		$this->assign('city',$city);

		//地区

		

		//dump($querypama);

		if($_POST['p']>=200)exit;

		if (I("post.ajax") == 1)

		$this -> ajaxReturn($list);
		shuffle($list);
		$this -> assign('list1', $list);

		$this -> assign('page', $show);

		$this -> assign('sex', $sex);

		$this -> assign('age', $age);

		

		if(cookie("dw"))

			$this -> assign('dw', 0);

		else

			$this -> assign('dw', 1);

		

		

		$today = date("Ymd",time());

		if($today == cookie('qiandaotime'.$this->uinfo["id"])||$today == S('qiandaotime'.$this->uinfo["id"]) || C('qd_config') == 0){

			$this -> assign('qd',1);

		}else{

			$this -> assign('qd', 0);

		}

		//if(cookie('newberenwu'.$this->uinfo["id"]))
		$this -> assign('nonewbe',1);

		$gg = M('Content')->cache(true,300)->where('class_id=8 and (sex=0 or sex='.$this->uinfo['sex'].')')->order('sequence desc,time desc')->limit(1)->find();

		if(cookie('gg')!=$gg['content_id'])	

			cookie('gg',0,300);

		$this->assign('gg',$gg);

        $this->siteDisplay(C('TPL_INDEX'));

    }

	

	

	public function photo(){		

		if(!$this->uinfo){

		   redirect(U("Public/index"));

		   exit;

		}    	

        $media=$this->getMedia('相册');

    	$this->assign('media', $media);

		$where = " 1=1 ";

		

		

		$sex = I("post.sex",'','intval');

		if(!$sex){//默认

			$sex = $this->uinfo['sex']==1?'2':'1';

			$cookiesex =cookie('defsex');		

			$sex = $cookiesex?$cookiesex:$sex;		

		}else{

			$sex = $sex;

			cookie('defsex',$sex,3600);

		}		

		if($sex>0)

		$where.=" and sex=".$sex;

		

		

		$age = I("post.age",'','intval');	

		if($age>0){

			$now = date("Y");

			switch($age){

				case 1:				

				$where.=" and age between ".($now-25)." and ".($now-18);

				break;

				case 2:				

				$where.=" and age between ".($now-35)." and ".($now-26);

				break;

				case 3:				

				$where.=" and age between ".($now-40)." and ".($now-36);

				break;

				case 4:				

				$where.=" and age between ".($now-50)." and ".($now-40);

				break;

				case 5:				

				$where.=" and age between ".($now-100)." and ".($now-50);

				break;

			}

		}

		

		$provinceid = I("post.provinceid",'','intval');	

		$cityid = I("post.cityid",'','intval');			

		$querypama = $this->get_areaid_toquery($provinceid,$cityid);

			if($querypama['id']){

			$where .= " and u.".$querypama['type'].'='.$querypama['id'];			

			}

		$where .=' or cityid=0';

		$where .=" and p.flag =1 and p.phototype=0"; //elite=1 and 	

		$User = M("user_photo");

		$count = $User->alias('p')->cache(true,300)->join("__USERS__ as u ON u.id=p.uid")->where($where) -> count();

		$_GET['p']=$_POST['p'];

		$Page = new \Think\Page($count, 15);		

		$show = $Page -> show();		

		$list = $User->alias('p')->cache(true,300) ->field('p.thumbfiles,u.avatar,p.photoid,p.idmd5,u.user_nicename,p.hits')->join("__USERS__ as u ON u.id=p.uid")-> where($where) -> order('p.elite,p.photoid desc') -> limit($Page -> firstRow . ',' . $Page -> listRows) -> select();		

		foreach($list as $key=> $val){

			$list[$key]['aurl'] = U("Show/photo", array("pid" => $val[idmd5]));

		}

		

		

		

		$areaList = A('Home/Site')->get_area();    	

    		

    		foreach ($areaList as $v){

    			if($v['rootid']==0){

    				$province[] =$v;

    			}

    			if($v['rootid']==$querypama['provinceid']){

    				$city[] =$v;

    			}    			

    		}

    		$this->assign('province',$province);

    		$this->assign('city',$city);

		

		if($_POST['p']>=200)exit;

		if (I("post.ajax") == 1)

		$this -> ajaxReturn($list);

		$this->assign('gg',M('Content')->cache(true,600)->where('class_id=8 and (sex=0 or sex='.$this->uinfo['sex'].')')->order('sequence desc,time desc')->limit(1)->find());
		shuffle($list);
		
		$this -> assign('list1', $list);

		$this -> assign('nav','Nearby');		

		$this -> assign('page', $show);

		$this -> assign('sex', $sex);

		$this -> assign('age', $age);

        $this->siteDisplay('index_photo');

    }

	

	

	public function hot(){

		if(!$this->uinfo){

		   redirect(U("Public/index"));

		   exit;

		}

        $media=$this->getMedia('动态');

    	$this->assign('media', $media);

		$where = " 1=1 ";

				

		$sex = I("post.sex",'','intval');

		if(!$sex){//默认

			$sex = $this->uinfo['sex']==1?'2':'1';

			$cookiesex =cookie('defsex');		

			$sex = $cookiesex?$cookiesex:$sex;

		}else{

			$sex = $sex;

			cookie('defsex',$sex,3600);

		}		

		if($sex>0)

		$where.=" and sex=".$sex;

				

		$age = I("post.age",'','intval');	

		if($age>0){

			$now = date("Y");

			switch($age){

				case 1:				

				$where.=" and age between ".($now-25)." and ".($now-18);

				break;

				case 2:				

				$where.=" and age between ".($now-35)." and ".($now-26);

				break;

				case 3:				

				$where.=" and age between ".($now-40)." and ".($now-36);

				break;

				case 4:				

				$where.=" and age between ".($now-50)." and ".($now-40);

				break;

				case 5:				

				$where.=" and age between ".($now-100)." and ".($now-50);

				break;

			}

		}

		

		$provinceid = I("post.provinceid",'','intval');	

		$cityid = I("post.cityid",'','intval');			

		$querypama = $this->get_areaid_toquery($provinceid,$cityid);


		if($querypama['id']){

			$where .= " and u.".$querypama['type'].'='.$querypama['id'];			

		}
		$where .=' or cityid=0';			

		$where .=" and p.flag =1 and p.phototype=0"; //elite=1 and 

		$User = M("user_photo");		

		$count = $User->alias('p')->cache(true,300)->join("__USERS__ as u ON u.id=p.uid")->where($where) -> count();

		$_GET['p']=$_POST['p'];

		$Page = new \Think\Page($count, 15);		

		$show = $Page -> show();

		$list = $User->alias('p')->cache(true,300) ->field('p.thumbfiles,p.title,p.uid,u.avatar,u.user_rank,p.photoid,p.timeline,p.idmd5 as pidmd5,u.idmd5,u.user_nicename,p.hits')->join("__USERS__ as u ON u.id=p.uid")-> where($where) -> order('p.timeline desc') -> limit($Page -> firstRow . ',' . $Page -> listRows) -> select();		
		
		foreach($list as $key=> $val){			

			$photoIds[] = $val['photoid']; //获取照片id集			

			$val['aurl'] = U("Show/photo", array("pid" => $val[idmd5]));

			$imgurl = strstr($val['thumbfiles'],'http')?$val['thumbfiles']:ROOT_PATH.$val['thumbfiles'];

			if($val['timeline']<(time()-86400*180))

				$val['timeline']=time()-86400*5+$val['photoid'];

			//$imgarr = getimagesize($imgurl);

			//$val['class']=$imgarr[0]>$imgarr[1]?'tupian_yi':'tupian_er';

			$list2[$list[$key]['timeline'].$list[$key]['uid']][]=$val;

			

			$photoidsArr[$val['photoid']] = $list[$key]['timeline'].$list[$key]['uid'];

						

		}

		$photoIds = join($photoIds, ',');
		$m = M('Comment');

		$result =  $m-> alias('c,lx_users as u') -> field('c.photoid,c.content,u.user_nicename,u.user_login,u.idmd5,u.user_rank') -> where('c.photoid in('.$photoIds.') and c.flag = 1 and u.id = c.uid') -> select();

		foreach($result as $key=> $val){

			if(isset($photoidsArr[$val['photoid']])&&count($commentlist[$photoidsArr[$val['photoid']]])<5){

				 $commentlist[$photoidsArr[$val['photoid']]][] = $val;

			}

		}

 

		$areaList = A('Home/Site')->get_area();    	

    		

		foreach ($areaList as $v){

			if($v['rootid']==0){

				$province[] =$v;

			}

			if($v['rootid']==$querypama['provinceid']){

				$city[] =$v;

			}    			

		}
	

		$this -> assign('commentlist',$commentlist);

		$this -> assign('province',$province);
		//shuffle($list2);
		$this -> assign('list',$list2);	

		$this -> assign('nav','Dongtai');

		$this -> assign('city',$city);

		$this -> assign('sex', $sex);

		$this -> assign('age', $age);	

			

		if($_POST['p']>=200)exit;

		if (I("post.ajax") == 1)

		$this -> ajaxReturn($this ->sitefetch('ajax_index_dongtai'));			

				

		$this->assign('gg',M('Content')->cache(true,600)->where('class_id=8 and (sex=0 or sex='.$this->uinfo['sex'].')')->order('sequence desc,time desc')->limit(1)->find());

		$this->siteDisplay('index_dongtai');

    }

	

	

    

    public function log(){

    		header("Content-Type:text/html; charset=utf-8"); 

		var_dump($this->uinfo);

		

		//A("Api2")->getarea('24.610433','118.047218');

			

	}

    

    

    //字符串中间打** 号

public function half_replace($str){

    $len = strlen($str)/2;

    return substr_replace($str,str_repeat('*',$len),floor(($len)/2),$len);

}









public function jubao(){

	if(!IS_POST){

		$this->siteDisplay('jubao');

	}else{

		$data['type']=I("post.type");

		$data['jbdesc']=I("post.content");

		$data['jbname']=$this->uinfo['user_nicename'];

		$data['uid']=$this->uinfo['id'];

		$data['time']=time();

		M("ext_jubao")->add($data);

		$this->success('ok');

		//$this->ajaxReturn($_POST);

	}

	

}









public function mjdr(){//直接上传到OSS

	$p = I("get.p");

	if(!$p) $p=0;

	$page = 10;

	$list = M()->table("lx_users")->limit($p*$page,$page)->order('userid asc')->select();

	foreach($list as $val){

		if($val["avatar"] && !strstr('http:',$val["avatar"])){

			$re = $this->oos_upimg('http://www.aiqing.com/'.$val["avatar"]);

			if($re)

			M()->table("lx_users")->where("userid=".$val['userid'])->setField("avatar",$re);

			echo $val['userid']."<img src='".$re."' width='100'>";

		}

		

	}

	

	$p++;

	$nexurl = U("mjdr",array("p"=>$p));	

	

	

	

	

	

	echo '<script>window.location.href="'.$nexurl.'";</script>';	

	exit;	

	

}





public function mjdr2(){

	$p = I("get.p");

	if(!$p) $p=0;

	$page = 50;

	$list = M()->table("lx_users")->limit($p*$page,$page)->order('userid asc')->select();

	if(!$list) exit("none");

	foreach($list as $val){					

		if($val["avatar"] && !strstr($val["avatar"],'http:')){		

						$uid = 	md5($val["userid"]-652);					

						$filename1 = $uid.".jpg";						

						$re = $this->GrabImage2('http://www.aiqing.com/'.$val['avatar'],$filename1,'http://www.aiqing.com');			

			if(file_exists($re)){

				$re2 = "http://www.yueai.me/".$re;				

				M()->table("lx_users")->where("userid=".$val['userid'])->setField("avatar",$re2);

			}			

			echo $val['userid']."<img src='".$re."' width='100'>";

		}

		

	}

	

	$p++;

	$nexurl = U("mjdr2",array("p"=>$p));	

	

	

	

	echo '<script>window.location.href="'.$nexurl.'";</script>';	

	exit;	

	

}

























public function mjdr4(){

	$p = I("get.p");

	if(!$p) $p=0;

	$page = 100;

	$list = M()->table("lx_users")->join("lx_user_profile ON lx_users.userid = lx_user_profile.userid")->limit($p*$page,$page)->order('lx_users.userid asc')->select();

	

	if(!$list) exit("none");

	foreach($list as $val){	

		

		if($val["uid"]>0) continue;				

		$data['sex'] = $val['gender'];

		$data['avatar'] = $val['avatar'];

		$uname = $val['username'];

		$uname = str_replace('eme','',$uname);

		$uname = str_replace('em','',$uname);

		$data['user_login']= $data['user_nicename'] = str_replace("?",'*',$uname);

		$data['user_pass'] =md5 ( $data['user_login'] . 'yueaiyuan_lxphp_com'. C ( 'PWD_SALA' ) );

		$data['last_login_ip'] ='127.0.0.1';

		$data['last_login_time'] =time()-rand(10000,1000000);

		$data['create_time'] =time()-2000000;

		$data['regip'] ='127.0.0.1';

		$data['user_status'] =1;

		$data['age'] =$val['ageyear'];

		$data['provinceid'] =$val['provinceid'];

		$data['cityid'] =$val['cityid'];

		$data['ismj'] =1;

		$data['idmd5'] =md5($val['userid'].$uname.'www.yueaiyuan.com');

		$data['jifen'] =rand(1,10000);

		$data['user_rank'] =$data['jifen']>9500 ? 1:0;

		

		$uid = M("Users")->add($data);

		echo M()->_sql();

		$data2['birthday'] =$val['birthday'];		

		$data2['weight'] =$val['weight'];

		$data2['height'] =$val['height'];

		$data2['monolog'] =$val['monolog'];

		$data2['mob'] =0;

		$data2['qq'] =0;

		$data2['weixin'] =0;

		$data2['weibo'] =0;

		$data2['momo'] =0;

		$data2['astro'] =$val['astro'];

		if($uid){

			$data2['uid'] =$uid;

			$re = M("User_profile")->add($data2);			

			if($re)

			 M()->table("lx_users")->where("userid=".$val["userid"])->setField("uid",$uid);

		}

		

		

		

		

	}

	



	

	$p++;

	$nexurl = U("mjdr4",array("p"=>$p));	

	

	

	

	echo '<script>window.location.href="'.$nexurl.'";</script>';	

	exit;	

	

}





public function mjdr3(){//采集相册

	$p = I("get.p");

	if(!$p) $p=0;

	$page = 10;

	$list = M()->table("lx_user_photo")->limit($p*$page,$page)->order('photoid asc')->select();

	if(!$list) exit("none");

	foreach($list as $val){					

		if($val["uploadfiles"] && !strstr($val["uploadfiles"],'http:')){		

						$uid = 	md5($val["photoid"]);					

						$filename1 = $uid.".jpg";						

						$re = $this->GrabImage3('http://www.aiqing.com/'.$val['uploadfiles'],$filename1,'http://www.aiqing.com');			

			if(file_exists($re)){

				$re2 = "http://www.yueai.me/".$re;			

				M()->table("lx_user_photo")->where("photoid=".$val['photoid'])->setField("uploadfiles",$re2);

			}			

			echo $val['photoid']."<img src='".$re."' width='100'>";

		}

		

	}

	

	$p++;

	$nexurl = U("mjdr3",array("p"=>$p));	

	

	

	

	echo '<script>window.location.href="'.$nexurl.'";</script>';	

	exit;	

	

}





/*

* 有防盗链的图片

* $url 图片地址

* $filename 图片保存地址

* return 返回下载的图片路径和名称,图片大小

* $fromurl 来源URL，填写来源图片网址可破解防盗链

*/

function GrabImage3($url,$filename="",$fromurl="",$filepath="") {

 if($url=="") return false;



 if(!$filepath){

 	 $filepath="photo/".date("mdHi")."/";

!is_dir($filepath)? mkdir($filepath):null;//生成文件夹

 }

 $randip = A("Weixin")->randip();

 $re = A("Weixin")->curlg($url,$fromurl,$randip);

 $size = file_put_contents($filepath.$filename,$re);//返回大小

 if($size)

 return $filepath.$filename;

}



/*

* 有防盗链的图片

* $url 图片地址

* $filename 图片保存地址

* return 返回下载的图片路径和名称,图片大小

* $fromurl 来源URL，填写来源图片网址可破解防盗链

*/

function GrabImage2($url,$filename="",$fromurl="",$filepath="") {

 if($url=="") return false;



 if(!$filepath){

 	 $filepath="Uploads/".date("ymdHi")."/";

!is_dir($filepath)? mkdir($filepath):null;//生成文件夹

 }

 $randip = A("Weixin")->randip();

 $re = A("Weixin")->curlg($url,$fromurl,$randip);

 $size = file_put_contents($filepath.$filename,$re);//返回大小

 if($size)

 return $filepath.$filename;

}







public function mjaddone(){

	$re = $this->oos_upimg('http://www.aiqing.com/data/attachment/avatar/201503/19/10000/avatar_big.jpg.thumb.jpg');

	dump($re);

}



























public function getcity(){

	include_once( ROOT_PATH.'weibo/saetv2.ex.class.php' );

	$c = new \SaeTClientV2( '1094663963' , '785e721d3c111287e14b83539d07b467' , "2.00WtyaXCEIQTRBab3c55c6c30YQEgt" );

	//$ms  = $c->get_country();

	

	/*$sflist = M("city")->select();

	foreach($sflist as $val){//获取城市

		$pid = "0010".$val["province_id"];

		$ms  = $c->get_city($pid);

		foreach($ms as $key =>$val3){

			foreach($val3 as $key2 =>$val2){

			$data['province_id'] = $val["province_id"];

			$data['name'] = $val2;

			$data['city_id'] = $key+1;

			M("city")->add($data);

		}

		}

	}*/

	//var_dump($ms);

	/*if($ms){ //获取省份

		foreach($ms as $key =>$val){

			foreach($val as $key2 =>$val2){

			$data['province_id'] = str_replace('10','',$key2);

			$data['name'] = $val2;

			M("city")->add($data);

		}

		}

	}*/

}





public function caiji(){ //微博采集 20160427

	//include_once( ROOT_PATH.'weibo/config.php' );

    include_once( ROOT_PATH.'weibo/saetv2.ex.class.php' );

	$rand = rand(0,5);

	dump($rand);

	switch($rand){

		case 1:

		$c = new \SaeTClientV2( '1174304060' , '755865987acfe4092e2dc6759984d5a8' , "2.00O_pKzB4GGFMBe25a449df90Hnvdn" );

		break;

		case 2:

		$c = new \SaeTClientV2( '3664658330' , 'b7e65b0c07c6e7e0a3683ff4b76e03d1' , "2.00O_pKzBEIQTRB64b7394e9ayXiPeC" );

		break;

		case 3:

		$c = new \SaeTClientV2( '1552547884' , '8535c7760153a4614a60446a391ca012' , "2.00ZBTJYDEIQTRBcc71598bbbatFRkB" );

		break;

		case 4:

		$c = new \SaeTClientV2( '4105282009' , 'e7c1c9f817b25d42fcfaf84b2c9450bc' , "2.00vqf3SGEIQTRBd76f74c62awx9f2C" );

		break;

		case 5:

		$c = new \SaeTClientV2( '4105282009' , 'e7c1c9f817b25d42fcfaf84b2c9450bc' , "2.00vKXQFEEIQTRBb4a03d979c06qknY" );

		break;

		default:

		$c = new \SaeTClientV2( '1094663963' , '785e721d3c111287e14b83539d07b467' , "2.00WtyaXCEIQTRBab3c55c6c30YQEgt" );

		break;

	}

	

	//$ms  = $c->public_timeline(1,200,0); // done

	//S("msdata",$ms,3600);

	$ms=S("msdata");

	if(is_array($ms["statuses"])){

		foreach($ms["statuses"] as $key =>$val){

			$data["avatar"] = $val['user']['avatar_large'];

			$data["wbid"] = $val['user']['id'];

			$data["screen_name"] = $val['user']['screen_name'];

			$data["province"] = $val['user']['province'];

			$data["city"] = $val['user']['city'];

			$data["description"] = $val['user']['description'];

			$data["gender"] = $val['user']['gender'];

			$data["followers_count"] = $val['user']['followers_count'];

			

			

			$data["imgtext"] = $val['text'];

			if(is_array($val['pic_urls'])){

				foreach($val['pic_urls'] as $key=> $val2){

					if($val2["thumbnail_pic"])

					$data["pic_urls"][$key] = basename($val2["thumbnail_pic"]);

				}

				

			}			

				//dump($data);

						

			//echo $data["avatar"];

		}

		

		

	

		

		

	var_dump($ms["statuses"][7][pic_urls]);

		

	}else{

		dump($ms["error"]);

		dump($ms["error_code"]);

	}



}

	

	



	

	

		//上传图片到阿里云OOS

	public function oos_upimg($url){			

		require_once ROOT_PATH."OOS_SDK/samples/Common.php";

		 $imgdata =  getimagesize($url);

		 if(!$imgdata['mime']) return false;

		 $type =  str_replace('image/', '', $imgdata['mime']);

    	$tools = new \Common();

		$root = date('Y-m-d',time());

		$root2 = date('H',time());

        $rand= rand(1000,9999);

	    $name = md5(date('YmdHis',time()).$rand);

     	$filename ='jiaoyou/'.$root.'/'.$root2.'/'.$name.'.'.$type;

    	$bucket = $tools::getBucketName();

    	$ossClient = $tools::getOssClient();

    	if (is_null($ossClient)) return false;

    	//*******************************简单使用***************************************************************

    	/*$tempu=parse_url($url);

    	$message=$tempu['host'];

		if(strstr($message,'mmbiz')){

			$message = 'mp.weixin.qq.com';

			

		}*/

		

		$randip = A("Weixin")->randip();		

    	$content = A("Weixin")->curlg($url,"http://www.baidu.com",$randip);

        

		$ossClient->putObject($bucket, $filename, $content);    	

    	$doesExist = $ossClient->doesObjectExist($bucket, $filename);

		if($doesExist){

			return $tools::bucketURL.$filename;

		}else{

			return  false;

		}

	}

	

    

	

	//

	public function qiandaoshuju(){

		

		$uid = $this -> uinfo['id'];

		$result = M('Qiandao') -> where('uid='.$uid) -> find();		

		$money = C('qd_config');//后台设置签到可获得的金钱

		$money = str_replace('，', ',', $money);

		$money = explode(',', $money);

		

		foreach($money as $k => $v ){			

			//如果连续签到天数为零，也就是新注册的用户

			if(!$result['continue_days']){

				$result['today_money'] = $money[0];

			}			

			//如果连续签到天数大于0，但是中间有中断		即：最后签到日期不是昨天的日期 ,也不是今天(防止有签到 但是删除cookie)

			if( date("Ymd",$result['last_time']) != date('Ymd',strtotime("-1 day")) && date("Ymd",$result['last_time']) != date("Ymd",time()) ){

				$result['continue_days'] = 0;

				$result['today_money'] = $money[0];

			}			

			//如果有签到    但是删除了cookie

			if(date("Ymd",$result['last_time']) == date("Ymd",time()) ){

				$result['today_money'] = $money[$result['continue_days']-1];

				$result['type'] = 1; 

				

			}

			//今日签到可得多少金钱

			if(($k+1) == $result['continue_days']){

				$result['today_money'] = $money[$k+1];

			}			

		}

		

//		dump($result);

//		exit;

		$count = count($money);

		

		$this -> assign('count',$count);

		$this -> assign('money',$money);

		$this -> assign('result',$result);

		

		$this -> ajaxReturn($this->sitefetch('ajax_index_qd'));

		

	}



	//新手任务

	public function renwushuju(){

		$uid = $this -> uinfo['id'];

		if($this -> checkbasedata(1) || cookie('renwu'.$uid)){  //没有完成任务

			$res = M('UserTask') -> where('uid ='.$uid) -> find();

			if(!$res){   //任务表没记录

				$data['uid'] = $uid;

				//$this -> uinfo['avatar'] ? $data['have_avatar'] = 1 : $data['have_avatar'] = 0;

				if($this->uinfo['provinceid'] && $this->uinfo['cityid'] && $this->uinfo['user_nicename'] && $this -> uinfo['avatar']){

					//$data['have_basedata'] = 1;

					$newb = 1;

				}else{

					$data['have_basedata'] = 0;

				}

				$data['have_photo'] = 0;

				$data['time'] = time();								

				$re = M('UserTask') -> add($data);

				cookie('renwu'.$uid,$uid,86400*360);				

			}else{   //有记录

				if($res['have_basedata']==1 && $res['have_avatar']==1)	

					$res['renwu1']=1;

				if($newb==1){

					$res['renwu1']=2;

					$res['msg']="跳过新手任务";

				}				

				$this -> assign('task',$res);

			}	

		}else{ //完成任务了

			cookie('newberenwu'.$uid,1,86400*365);
			$this -> assign('love',1);

		}
		$this -> ajaxReturn($this->sitefetch('ajax_xinshourenwu'));

	}

	

	

	

	

	

	

	

	

	

	

	

	

	

	

	

	

}