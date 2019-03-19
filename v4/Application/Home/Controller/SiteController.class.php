<?php

namespace Home\Controller;

use Common\Controller\BaseController;

/**

*  @author lxphp

 * http://lxphp.com

 * 前台公共类

 */

class SiteController extends BaseController {



	

    public function __construct()

    {

        parent::__construct();

        $this->initialize();

    }



    /**

     * 控制器初始化

     */

    protected function initialize(){

		    //设置手机版参数

		

        if(MOBILE){

            C('TPL_NAME' , C('MOBILE_TPL'));

        }      

        $cook = cookie('checklogin');

        $ucookie = json_decode ( stripslashes ( $cook ), true );

        $userinfo = S ( 'uinfo' . $ucookie ['check'] );

        if ($userinfo) {

        	if (md5 ( $userinfo ['user_login'] . $userinfo ['user_pass'] . C ( 'PWD_SALA' ) ) == $ucookie ['check']) {

        		$this->assign ( 'username', $userinfo ['user_login'] );

        		unset ( $userinfo ['user_pass'] );

        		$uinfo = $userinfo;   

        		//$this->assign ( 'uinfo', $uinfo );

        	}

        	$this->uinfo=$uinfo;

        }

  		$yquid = I("get.uid",0,'intval');

        if($yquid && $uinfo['id']!=$yquid){//邀请uid

			cookie('yq',$yquid,86400*360);

		}

		$this->assign('wdsx',cookie('wdsxnum'));		

		C('lxphpca',S("lxphpca"));				

		header("Content-Type:text/html; charset=utf-8");

		

		

    }

    

    /**

     * 生成验证码

     */

    public function verifyCode(){

    	$Verify =     new \Think\Verify();

    	$Verify->fontSize = 100;

		$Verify->fontttf  = '5.ttf';

		$Verify->codeSet  = '2345678abcdefghkmnpqrstuvwxy';

    	$Verify->length   = 4;

    	$Verify->useNoise = false;

		$Verify->useCurve = false;

		$Verify->bg       = array(255, 255, 255);

    	$Verify->entry();

    }



public function trimall($str)//删除空格

{

	$qian=array(" ","　","\t","\n","\r");$hou=array("","","","","");

	return str_replace($qian,$hou,$str);

}



 public function g_utf8($str){

   return iconv("GB2312", "UTF-8", $str);

}

	

public function new_format_date($time){

	$new_date = date('Y-m-d',$time);

	$date = date('Y-m-d',time());

	if($new_date == $date){

		if(date('a',$time)=='am'){

			return '上午'.date('h:i',$time);

		}else{

			return '下午'.date('h:i',$time);

		}



	}elseif(date('Y',$time)==date('Y',time())){

		return date('m-d H:i');

	}else{

		return date('Y-m-d H:i');

	}

}



function format_date($time){

	$t=$time-time();

	$f=array(

			'31536000'=>'年',

			'2592000'=>'个月',

			'604800'=>'星期',

			'86400'=>'天',

			'3600'=>'小时',

			'60'=>'分钟',

			'1'=>'秒'

	);

	foreach ($f as $k=>$v) {

		if (0 !=$c=floor($t/(int)$k)) {

			return $c.$v.'后';

		}

	}

}

	

function q_format_date($time){

	$t=time()-$time;

	$f=array(

			'31536000'=>'年',

			'2592000'=>'个月',

			'604800'=>'星期',

			'86400'=>'天',

			'3600'=>'小时',

			'60'=>'分钟',

			'1'=>'秒'

	);

	foreach ($f as $k=>$v) {

		if (0 !=$c=floor($t/(int)$k)) {

			return $c.$v.'前';

		}

	}

}

    // 检测输入的验证码是否正确，$code为用户输入的验证码字符串



	/**

	* 生成静态页面

	* @author nineTea

	* @param undefined $htmlfile

	* @param undefined $htmlpath

	* @param undefined $templateFile

	* 20151215

	*/

	public function siteBuildHtml($htmlfile='',$htmlpath='',$templateFile=''){

		C('TAGLIB_PRE_LOAD','Dux');

        C('TAGLIB_BEGIN','<!--{');

        C('TAGLIB_END','}-->');

		C('VIEW_PATH', './themes/');



		$content    =   $this->fetch($templateFile);

		//模板包含

        if(preg_match_all('/<!--#include\s*file=[\"|\'](.*)[\"|\']-->/', $content, $matches)){

            foreach ($matches[1] as $k => $v) {

                $ext=explode('.', $v);

                $ext=end($ext);

                $file=substr($v, 0, -(strlen($ext)+1));

                $phpText = $this->view->fetch(C('TPL_NAME').'/'.$file);

                $content = str_replace($matches[0][$k], $phpText, $content);

            }

        }

        $htmlpath   =   !empty($htmlpath)?$htmlpath:HTML_PATH;

        $htmlfile   =   $htmlpath.$htmlfile.C('HTML_FILE_SUFFIX');

		$Storage = new \Think\Storage();

        $Storage::put($htmlfile,$content,'html');

        return $content;

	}

    

	/**

	* 获取地址信息

	* @param undefined $name

	*

*/

	public function get_areaid_byname($name){
		
		$area = $this->get_area();

		foreach($area as $key=>$val){

			if($val['areaname']==$name)

			return $key;

		}

		return false;

	}

    

	/**

	 * 获取地址信息

	 *

	 *

	 */

	public function get_area(){

		if(S('AreaList')) return S('AreaList');

		$data =  M('Area')->order("orders asc")->select();

		if($data){

			$AreaList=array();

			foreach ($data as $k =>$v){

				$AreaList[$v['areaid']] = $v;

			}



			S('AreaList',$AreaList);

			return $AreaList;

		}

		return false;

	}

    

	/**

	* 获取匹配地区

	* 20160505 自己都搞晕了

	*

*/

	public function get_areaid_toquery($provinceid="",$cityid=""){

		$up = $this->uinfo['provinceid'];

		$uc = $this->uinfo['cityid'];

		$upn = $this->uinfo['provincename'];

		$ucn = $this->uinfo['cityname'];

		$cp = cookie("dw_provinceid");

		$cc = cookie("dw_cityid");

		$cpn = cookie("dw_provincename");

		$ccn = cookie("dw_cityname");
		
		$area = "全国";

		$data = array();

		if($provinceid&&$cityid){//筛选的时候用

		$cc = $cityid;

		$cp = $provinceid;

		$area2 = $this->getuserarea(array('provinceid'=>$provinceid,'cityid'=>$cityid),1);

		}

		if($cc){

			$data['id']=$cc;

			$data['type']='cityid';

			if($cpn||$ccn) $area=$cpn.$ccn;

			$data['area']=$area;

		}elseif($uc){

			$data['id']=$uc;

			$data['type']='cityid';

			if($upn||$ucn) $area=$upn.$ucn;

			$data['area']=$area;

		}elseif($cp){

			$data['id']=$cp;

			$data['type']='provinceid';

			if($cpn||$ccn) $area=$cpn.$ccn;

			$data['area']=$area;

		}elseif($up){

			$data['id']=$up;

			$data['type']='provinceid';

			if($upn||$ucn) $area=$upn.$ucn;

			$data['area']=$area;

		}

		$data['area']=$area2?$area2:$area;

		$data['provinceid']=$cp?$cp:$up;

		$data['cityid']=$cc?$cc:$uc;
		if(empty($data['area'])){
			$data['area']=$this->uinfo['provincename'].$this->uinfo['cityname'];
			}
		if(empty($data['provinceid'])){
			$data['provinceid']=$this->uinfo['provinceid'];
			}
		if(empty($data['cityid'])){
			$data['cityid']=$this->uinfo['cityid'];
			}
		if(empty($data['id'])){
			$data['cityid']=$this->uinfo['cityid'];
			}

		$this -> assign('area', $data['area']);

		$this -> assign('areaarr', $data);

		return $data;



	}

    

	/**

	*

	* @param undefined $re 用户信息

	* @param  $remember  记录cookie信息

*/

	public function getuserarea($re,$remember=0){

		$area = "未填写";

    		$areaarr = $this->get_area();

			if($re['cityid'] && $areaarr[$re['cityid']]){

				$cityname = $areaarr[$re['cityid']]['areaname'];

				$provincename =  $areaarr[$re['provinceid']]['areaname'];

			}elseif($re['city']){

				$provincename = $re['province'];

				$cityname = $re['city'];

			}

			if($remember==1){

		cookie("dw_provinceid",$re['provinceid']);

		cookie("dw_cityid",$re['cityid']);

		cookie("dw_provincename",$provincename);

		cookie("dw_cityname",$cityname);

		cookie("dw",2);

			}

			$area = $provincename.$cityname;
			return $area;

	}

    

	/**

	* 获取关注状态

	* @author lxphp

	* @param undefined $uid

	*

*/

	public function get_gz_status($uid,$myuid=""){

			$data['fromuid']=$myuid?$myuid:$this->uinfo['id'];

			$data['touid']=$uid;

			if(!$data['fromuid'] || !$uid) return false;

			$re = M("User_subscribe")->where($data)->find();

			if($re){

				$w['fromuid']=$data['touid'];

				$w['touid']=$data['fromuid'];

				$gzstatus = 1;

				//$re = M("User_subscribe")->where($w)->find();

				//if($re)

				//$gzstatus =2;

				$this->assign('User_subscribe', $gzstatus);

			}

			return  $gzstatus;

	}



	/**

	* 获取亲密度

	* @param undefined $fromuid  我的uid

	* @param undefined $uid  他的uid

	* @since http://lxphp.com

*/

	public function get_qmd_val($uid,$fromuid=""){

			if(!$fromuid) $fromuid = $this->uinfo['id'];

			$data['fromuid']=$fromuid;

			$data['touid']=$uid;

			if(!$data['fromuid'] || !$uid || $uid==$fromuid) return false;

			$w["hash"]=$this->uidgethash($uid,$fromuid);

			$User_qinmidu = M("User_qinmidu");

			$re = $User_qinmidu->where($w)->find();

			if($re){

			$this->assign('User_qmd', $re["qmd"]);

			return array('qmd'=>$re["qmd"],'allowsm'=>$re['allowsm']);

			}

			else{

			$data['hash']=$w["hash"];

			$data['addtime']=time();

			$User_qinmidu->add($data);

			return 0;

			}



	}



	public function getInfo($ids = array()){

		if(!$ids) return false;

		$re = M("Users") ->field('id,user_nicename,sex,jifen,avatar,user_login,idmd5,user_rank') -> where('id in('.$ids.')') -> select();

		if(!$re){

			return false;

		}

		$arr = array();

	  	foreach($re as $k =>$v){

	  		$arr[$v['id']]['user_nicename'] = $v['user_nicename'] ? $v['user_nicename'] : $v['user_login'] ;

			$arr[$v['id']]['avatar'] = $v["avatar"];

			$arr[$v['id']]['sex'] = $v['sex'];

			$arr[$v['id']]['jifen'] = $v['jifen'];

			$arr[$v['id']]['uid'] = $v['id'];

			$arr[$v['id']]['idmd5'] = $v['idmd5'];

			$arr[$v['id']]['user_rank'] = $v['user_rank'];

	  	}

		return $arr;

	}



   public function string_filter($string){

   	 if(C('string_config')&&$string){

   	 	$string_config =explode(',', trim(C('string_config')));

   	 	foreach ($string_config as $v){

   	 		$string = str_replace($v,'**',$string);

   	 	}

   	 }

      return $string;

   }



	public function get_xingzuo($bir) {

    $month = (int)substr($bir,0,2);

    $day = (int)substr($bir,3);

    $strValue = '';

    if (($month == 1 && $day >= 20) || ($month == 2 && $day <= 18)) {

        $strValue = "水瓶座";

    } else if (($month == 2 && $day >= 19) || ($month == 3 && $day <= 20)) {

        $strValue = "双鱼座";

    } else if (($month == 3 && $day > 20) || ($month == 4 && $day <= 19)) {

        $strValue = "白羊座";

    } else if (($month == 4 && $day >= 20) || ($month == 5 && $day <= 20)) {

        $strValue = "金牛座";

    } else if (($month == 5 && $day >= 21) || ($month == 6 && $day <= 21)) {

        $strValue = "双子座";

    } else if (($month == 6 && $day > 21) || ($month == 7 && $day <= 22)) {

        $strValue = "巨蟹座";

    } else if (($month == 7 && $day > 22) || ($month == 8 && $day <= 22)) {

        $strValue = "狮子座";

    } else if (($month == 8 && $day >= 23) || ($month == 9 && $day <= 22)) {

        $strValue = "处女座";

    } else if (($month == 9 && $day >= 23) || ($month == 10 && $day <= 23)) {

        $strValue = "天秤座";

    } else if (($month == 10 && $day > 23) || ($month == 11 && $day <= 22)) {

        $strValue = "天蝎座";

    } else if (($month == 11 && $day > 22) || ($month == 12 && $day <= 21)) {

        $strValue = "射手座";

    } else if (($month == 12 && $day > 21) || ($month == 1 && $day <= 19)) {

        $strValue = "魔羯座";

    }

    return $strValue;



}



public function checkbasedata($type=""){

	$avatar = $this->uinfo['avatar'];

	$provinceid = $this->uinfo['provinceid'];

	$cityid = $this->uinfo['cityid'];

	$user_nicename= $this->uinfo['user_nicename'];

	if(!$avatar || !$user_nicename || !$cityid){

		if($type){

			return true;

		}else{

			$this->ajaxReturn(array('info'=>'请先完善您的个人基本信息：头像，昵称，所在城市等','status'=>-1,'url'=>U('Home/User/index')));

		}

	}else{

		return false;

	}





}



public function sendSysmsg($uid,$body,$type){



	return   M('SystemMsg')->add(array('uid'=>$uid,'msg_content'=>$body,'msg_type'=>$type,'time'=>time()));



}

 

/**

* 新手任务

* $type  1头像 2基础资料 3 相册

*/

public function newbchange($uid,$type){

	$user_task  = M("User_task");

	$re = $user_task->where("uid=".$uid)->find();

	if($re){

		if(!$re['have_avatar'] && $type==1){

			$fee = C('newbe1');

			$re2 = $this->changemoney($uid,$fee,11,'完成新手任务上传头像+'.$fee,'','',1,'',0,1);

			if($re2>0)  $user_task->where("uid=".$uid)->setField('have_avatar',1);

		}

		if(!$re['have_basedata'] && $type==2){

			$re3 = M("Users")->where("id=".$uid)->find();

			if($re3['provinceid']&&$re3['cityid']){

			$fee = C('newbe2');

			$re2 = $this->changemoney($uid,$fee,11,'完成新手任务完善资料+'.$fee,'','',1,'',0,1);

			if($re2>0)  $user_task->where("uid=".$uid)->setField('have_basedata',1);

			}else{

				return FALSE;

			}

		}

		if(!$re['have_photo'] && $type==3){

			$user_task->where("uid=".$uid)->setField('have_photo',1);

		}

	}else{

		return false;

	}

}



    /**

 * 设置用户信息到缓存

 * @author 紫竹

 * @since 2014-3-29

 * @param string $field,$val

 *

 *

 *

 */

protected function setUserinfo($field,$val) {

	$cook = cookie('checklogin');

	$ucookie=json_decode(stripslashes($cook),true);

	$userinfo = S('uinfo'.$ucookie['check']);

	if($userinfo){

		if(md5($userinfo['user_login'].$userinfo['user_pass'].C('PWD_SALA'))==$ucookie['check']){

			$userinfo[$field]=$val;

			S('uinfo'.$ucookie['check'],$userinfo);

			return true;

		}else{



			return false;

		}

	}else{



		return false;

	}

}



/**

 * 引用模板片段

 */

protected function sitefetch($name='') {

	 C('TAGLIB_PRE_LOAD','Dux');

        C('TAGLIB_BEGIN','<!--{');

        C('TAGLIB_END','}-->');

        C('VIEW_PATH','./themes/');

	return $this->fetch(C('TPL_NAME').'/'.$name);



}



    /**

     * 前台模板显示 调用内置的模板引擎显示方法

     * @access protected

     * @param string $name 模板名

     * @param bool $type 模板输出

     * @return void

     */

    protected function siteDisplay($name='',$type = true) {

        C('TAGLIB_PRE_LOAD','Dux');

        C('TAGLIB_BEGIN','<!--{');

        C('TAGLIB_END','}-->');

        C('VIEW_PATH','./themes/');

        $data = $this->view->fetch(C('TPL_NAME').'/'.$name);

        //模板包含

        if(preg_match_all('/<!--#include\s*file=[\"|\'](.*)[\"|\']-->/', $data, $matches)){

            foreach ($matches[1] as $k => $v) {

                $ext=explode('.', $v);

                $ext=end($ext);

                $file=substr($v, 0, -(strlen($ext)+1));

                $phpText = $this->view->fetch(C('TPL_NAME').'/'.$file);

                $data = str_replace($matches[0][$k], $phpText, $data);

            }

        }

        //替换资源路径

        $tplReplace=array(

            //普通转义

            'search' => array(

                //转义路径

                "/<(.*?)(src=|href=|value=|background=)[\"|\'](images\/|img\/|css\/|js\/|style\/)(.*?)[\"|\'](.*?)>/",

            ),

            'replace' => array(

                "<$1$2\"".__ROOT__."/themes/".C('TPL_NAME')."/"."$3$4\"$5>",

            ),

        );

        $data = preg_replace(  $tplReplace['search'] , $tplReplace['replace'] , $data);

        if($type){

            echo $data;

        }else{

            return $data;

        }



    }



	 protected function sitemDisplay($name='',$type = true) {

        C('TAGLIB_PRE_LOAD','Admin');

       // C('TAGLIB_BEGIN','<!--{');

       // C('TAGLIB_END','}-->');

        C('VIEW_PATH','./themes/');

        $data = $this->view->fetch(C('TPL_NAME').'/'.$name);

        //模板包含

        if(preg_match_all('/<!--#include\s*file=[\"|\'](.*)[\"|\']-->/', $data, $matches)){

            foreach ($matches[1] as $k => $v) {

                $ext=explode('.', $v);

                $ext=end($ext);

                $file=substr($v, 0, -(strlen($ext)+1));

                $phpText = $this->view->fetch(C('TPL_NAME').'/'.$file);

                $data = str_replace($matches[0][$k], $phpText, $data);

            }

        }

        //替换资源路径

        $tplReplace=array(

            //普通转义

            'search' => array(

                //转义路径

                "/<(.*?)(src=|href=|value=|background=)[\"|\'](images\/|img\/|css\/|js\/|style\/)(.*?)[\"|\'](.*?)>/",

            ),

            'replace' => array(

                "<$1$2\"".__ROOT__."/themes/".C('TPL_NAME')."/"."$3$4\"$5>",

            ),

        );

        $data = preg_replace(  $tplReplace['search'] , $tplReplace['replace'] , $data);

        if($type){

            echo $data;

        }else{

            return $data;

        }



    }



    /**

     * 页面Meda信息组合

     * @return array 页面信息

     */

    protected function getMedia($title='',$keywords='',$description='',$mod='',$css='')

    {

    	$title2 = $title;

        if(empty($title)){

            $title=C('SITE_TITLE').' - '.C('SITE_SUBTITLE');

        }else{

            $title=$title.' - '.C('SITE_TITLE').' - '.C('SITE_SUBTITLE');

        }

        if(empty($keywords)){

            $keywords=C('SITE_KEYWORDS');

        }

        if(empty($description)){

            $description=C('SITE_DESCRIPTION');

        }





        return array(

            'title'=>$title,

            'keywords'=>$keywords,

            'description'=>$description,

        	'mod'=>$mod,

        	'title2'=>$title2,

        	 $css =>'menu-hover',

        );

    }

	

    protected function check_verify($code, $id = ''){

    	$verify = new \Think\Verify();

    	return $verify->check($code, $id);

    }

	

protected function send_mobcode($mob,$code){

	if($this->isTel($mob)===false) return '请检查您的手机号是否正确。';

//		if(S('check'.$mob)==1) return '两次发送间隔需1分钟。';

	$url = 'http://106.dxton.com/webservice/sms.asmx/Submit?account='.C('mobaccount').'&password='.C('mobpass').'&mobile='.$mob.'&content='."您的验证码是：【".$code."】。请不要把验证码泄露给其他人。如非本人操作，可不用理会！";



			$res  = $this->curl_get_contents($url);

			//var_dump($res);

			$ref = json_decode(json_encode((array) simplexml_load_string($res)), true);

			if($ref['result']==100){

				S('check'.$mob,1,60);

				S($mob,$code,600);

				return true;

			}else{

				return  $ref['message'];

			}

}

	

    /**

     * 验证手机号码

     *

     * @param string $email

     * @return boolean

     */

    protected function isTel($mobilePhone) {

    	if (preg_match ( "/1[34587]{1}\d{9}$/", $mobilePhone )) {

    		return true;

    	} else {

    		return false;

    	}

    }

	

public function curl_get_contents($url){

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);

	 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    curl_setopt($ch, CURLOPT_TIMEOUT, 5);

    curl_setopt($ch, CURLOPT_USERAGENT, "IE 6.0");

    $r = curl_exec($ch);

    curl_close($ch);

	if($r===false) return file_get_contents($url);

    return $r;

}

	

    /**

     * 邮件验证

     *

     * @return true,false;

     * @since 2014-4-5

     */

    protected function isEmail($email) {

    	if (ereg ( "^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-])+", $email )) {

    		return true;

    	} else {

    		return FALSE;

    	}

    }

	

	

	

	//用于 地域 选择   例：  江苏-苏州-吴中   



    /**

     * 验证身份证号

     *

     * @param

     *        	$vStr

     * @return bool

     */

    protected function isCreditNo($vStr) {

    	$vCity = array (

    			'11',

    			'12',

    			'13',

    			'14',

    			'15',

    			'21',

    			'22',

    			'23',

    			'31',

    			'32',

    			'33',

    			'34',

    			'35',

    			'36',

    			'37',

    			'41',

    			'42',

    			'43',

    			'44',

    			'45',

    			'46',

    			'50',

    			'51',

    			'52',

    			'53',

    			'54',

    			'61',

    			'62',

    			'63',

    			'64',

    			'65',

    			'71',

    			'81',

    			'82',

    			'91'

    	);



    	if (! preg_match ( '/^([\d]{17}[xX\d]|[\d]{15})$/', $vStr ))

    		return false;



    	if (! in_array ( substr ( $vStr, 0, 2 ), $vCity ))

    		return false;



    	$vStr = preg_replace ( '/[xX]$/i', 'a', $vStr );

    	$vLength = strlen ( $vStr );



    	if ($vLength == 18) {

    		$vBirthday = substr ( $vStr, 6, 4 ) . '-' . substr ( $vStr, 10, 2 ) . '-' . substr ( $vStr, 12, 2 );

    	} else {

    		$vBirthday = '19' . substr ( $vStr, 6, 2 ) . '-' . substr ( $vStr, 8, 2 ) . '-' . substr ( $vStr, 10, 2 );

    	}



    	if (date ( 'Y-m-d', strtotime ( $vBirthday ) ) != $vBirthday)

    		return false;

    	if ($vLength == 18) {

    		$vSum = 0;



    		for($i = 17; $i >= 0; $i --) {

    			$vSubStr = substr ( $vStr, 17 - $i, 1 );

    			$vSum += (pow ( 2, $i ) % 11) * (($vSubStr == 'a') ? 10 : intval ( $vSubStr, 11 ));

    		}



    		if ($vSum % 11 != 1)

    			return false;

    	}



    	return true;

    }

	

protected function get_token($appid,$appsecret,$new=0){

	//dump(S('access_tokens'));

	if(S('access_tokens'.$appid) && $new==0) return S('access_tokens'.$appid);



	$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";

        $ret_json = $this->curl_get_contents($url);

        $ret = json_decode($ret_json);

       // dump($ret);

        if($ret -> access_token){



			S('access_tokens'.$appid,$ret -> access_token,7000);

			return $ret -> access_token;

			}

}

	

protected function is_weixin(){

if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false && C("APPID") && C('SCRETID')) {

return true;

}

return false;

}

	

	

	//传入ids 获取头像 昵称



protected function getRandStr($length){

	$str = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

	$randString = '';

	$len = strlen($str)-1;

	for($i = 0;$i < $length;$i ++){

		$num = mt_rand(0, $len);

		$randString .= $str[$num];

	}

	return $randString;

}



   //字符过滤



protected function get_url() {

$sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';

$php_self = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];



$path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';

$relate_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $php_self.(isset($_SERVER['QUERY_STRING']) ? '?'.$_SERVER['QUERY_STRING'] : $path_info);

return $sys_protocal.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '').$relate_url;

}

	

protected function curlp($post_url,$xjson){//php post

	$ch = curl_init($post_url);

	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");

	curl_setopt($ch, CURLOPT_POSTFIELDS,$xjson);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);

	 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

	curl_setopt($ch, CURLOPT_HTTPHEADER, array(

	'Content-Type: application/json',

	'Content-Length: ' . strlen($xjson))

	);

	$respose_data = curl_exec($ch);

	return $respose_data;

	}



	protected function get_city($id = 0){

		$region = $this->setRegion();

		if($region){

			foreach ($region as $v){

				if($v['parent_id'] == $id){

					$data[] =$v;

				}

			}

			return $data;

		}

	}



//发送系统消息



	protected function setRegion(){

		if(S('region')) return S('region');

		$region = M('Region')->field('region_id,region_name,parent_id')->select();

		if($region){

			foreach ($region as $ke=>$va){

				$region_list[$va['region_id']] =$va;

			}

			S('region',$region_list);

			return $region_list;

		}



	}



	protected function get_jifen_rank_name($uinfo){



		$rank = C('rank');

		if($rank){

             if(!$uinfo['jifen']){

             	$num = 0;

             }else{



             	foreach ($rank[$uinfo['sex']] as $k=>$v){

             		if($uinfo['jifen']<=$v['value']){

             			$num =$k-1;

             		    break;

             		}else{

             			$num =$k;

             		}

             	}

             }



             $uinfo['rank_name'] = $rank[$uinfo['sex']][$num]['name'];

             //$uinfo['rank_icon'] = $rank[$uinfo['sex']][$num]['icon'];

             $uinfo['rank_icon'] ='themes/lxphp_dating/images/rank/'.($num+1).'.png';

             $uinfo['rank_ltfl'] = $rank[$uinfo['sex']][$num]['ltfl'];

		}





		return $uinfo;

	}













 

	

}