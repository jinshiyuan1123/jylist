<?php
namespace Home\Controller;
use Common\Controller\BaseController;

/**
 * function WEI XIN API
 * @author lxphp
 * http://lxphp.com
 *外部curl get post http://www.lxphp.com/default.php/zizhuapi/curlfowai/checkcode/yanzi/
 *外部curl 参数： checkcode:yanzi必选; type:post,get; url: 来源网址; fromurl:伪造来路; fromip:伪造ip 不用了他自带;vars:post 数据;cookie:cookie;
 */
C('defmenuend','
回复0返回主菜单');
C('defmenu','请输入数字：
1 翻译集合
2 手机归属地查询
3 火车查询
4 身份证查询
5 苏州公交查询
6 区号查询
7 快递查询(beta)
10 联系客服/提交bug
11 关于紫竹
20 抽奖游戏
微信开发，微信商城，网站建设，软件定制开发, 破解开发，欢迎咨询，联系:13918734302');
C('defkefu','
回复客服开头并留下您的联系方式，客服会及时联系您。回复格式如：客服+意见、建议+联系方式');
C('defbug','
回复客服提交bug，回复格式如：客服+BUG信息');
C('kefuok','我们已经收到了您提交的客服信息，感谢!');
C('adminemail','597964799@qq.com');
/*C('kuaidi','
1.EMS
2.顺丰快递
3.韵达快运');
C('kuaidiname',array('EMS','顺丰快递','韵达快运'));
C('kuaidicode',array('ems','shunfeng','yunda'));
*/
C('prev','
回复77返回上级菜单');





class WeixinController extends BaseController
{


	
	
public function  _initialize(){	


		
	header("Content-Type:text/html; charset=utf-8"); 		
	}


public function log(){
	dump(S('log'));
}		
	
public function index(){
	
	define("TOKEN", C("wxtoken")?C("wxtoken"):"yueai8999");//成为开发者token 	
	
	 $echoStr = $_GET["echostr"];
       if($this->checkSignature()){
       	if($echoStr){
        	echo $echoStr;
        	exit;
       	}else{
       		$this->getmsg();		
       	}
        }		
	}	
	
	
	
	
public function getmsg(){
	 $ss=0;
 		 if(C("other_url") && C("other_token") && C('malasen')!=2){	//add by lxphp.com
	$url = $this->get_other_url();
	$re = $this->curlpxml($url,$GLOBALS["HTTP_RAW_POST_DATA"]);	
	if(strstr($re,'xml')) { $ss=1; echo $re; }
	//S('log',$re);
	}			
		$xml=$this->msg();
		if(S($xml['FromUserName'].$xml['CreateTime'])==1)exit;	
		S($xml['FromUserName'].$xml['CreateTime'],1,300);		
		
		 $db =M('Wxtext');
 		//$db->add($xml);
		 $openid = $xml['FromUserName'];
		 $eventkey = $xml['EventKey'];
		if($xml['MsgType']=='event')  {
			$Event=$xml['Event'];
			switch($Event){
				case "subscribe":	
				$usermod = M("Users");		
				$re = A('Api')->saveinfo($openid);								
					if($eventkey&& $xml['Ticket']){//二维码关注
						$pid  = str_replace('qrscene_','',$eventkey);						
						$pinfo = $usermod->find($pid);
						$msg2 = "【".$re["user_nicename"]."】通过您分享的二维码关注了公众号，Ta注册后您有可能获得奖励。";
						$this->makeTextbygm($msg2,$pinfo['weixin']);
						$re['parent_id']=$datas2['puid']=$pid;
						M("User_y_reg")->where("id=".$re['id'])->save($datas2);
					
					}
				
				/*	if(C('gzshbval')>0)			
					A('Api')->sendhb($openid,C('gzshbval'),C('site_title'),C('hbbody'),2); // 关注送红包	
					if(C('gzsxj')>0) A('Api')->sendzz($openid,C('gzsxj'),C('hbbody'),2);	//关注送现金*/			
				
						
				
				if($re['id']>0){					
					$msg = C("diygzhf")?C("diygzhf"):0;
					$msg = $re['sex']==2?C("diygzhfnv"):$msg;				
					if($msg) $this->makeTextbygm(html_out($msg),$openid);
					
					if(C('gztstw')==1){				
					$sex = $re['sex']==1?2:1;
					if($re['sex']>0&&$re['cityid']){						
						$list = $usermod->field("user_nicename,avatar,provinceid,cityid,age,idmd5")->where("sex=".$sex." and cityid=".$re['cityid']." and avatar!=''")->order('last_login_time desc,id desc')->limit(8)->select();
					}elseif($re['sex']>0&&$re['provinceid']){
						$list = $usermod->field("user_nicename,avatar,provinceid,cityid,age,idmd5")->where("sex=".$sex." and provinceid=".$re['provinceid']." and avatar!=''")->order('last_login_time desc,id desc')->limit(8)->select();
					}else{
						$list = $usermod->field("user_nicename,avatar,provinceid,cityid,age,idmd5")->where("sex=".$sex." and avatar!=''")->order('last_login_time desc,id desc')->limit(8)->select();
					}					
					
					$list2[0]['title']="来".C('site_title')."邂逅缘分吧";
					$list2[0]['description']=$msg;
			        $list2[0]['url']="http://".C('site_url').U("Home/Index/index");
					$list2[0]['picUrl']="http://www.yueai.me/v4/jiaocheng_03.jpg";
										
					foreach($list as $key=>$val){
					$list2[$key+1]['title']=$val['user_nicename']." ".(date('Y',time())-$val['age'])."岁 ".A('Site')->getuserarea($val);
					$list2[$key+1]['description']=$val['user_nicename']." ".(date('Y',time())-$val['age'])."岁";
					$list2[$key+1]['url']="http://".C('site_url').U("Home/Show/index",array('uid'=>$val['idmd5']));
					$list2[$key+1]['picUrl']=strstr($val['avatar'],'http')?$val['avatar']:'http://'.C('site_url')."/".$val['avatar'];				
					}
					
					
					$ss1['items']=$list2;					
					
										
					
					if($openid!=C('adminopenid'))
					$this->makeTextbygm("有新朋友关注：【".$re['user_nicename']."】",C('adminopenid'));										
					if(is_array($ss1)){//欢迎关注。
						exit($this->makeNews($ss1));					
					}
					}
				}
				//if($msg) $this->makeTextbygm(html_out($msg),$openid);			
				break;
				case "unsubscribe":
				A("Api")->unsubscribe($openid);
				break;
				case "CLICK":				
						
				if(C('old_subscribe')){
					$data =A("Api")-> saveinfo($openid,1);						
					if($data['type']=='newreg'){
					
					}
				}
				
				
				
				if($eventkey=='lxphpcom'){//二维码推广 20151112 by 紫竹
					$msg ='正在生成您的推广二维码,请耐心等待...';
					if($msg) $this->makeTextbygm($msg,$openid);
					$media_id = A("Home/Api")->getewmmediaid($openid);
					if($media_id) $this->makeImgbygm($media_id,$openid);
						
				}	
				$msg = A("Api")->clickfun($eventkey,$openid);
				if($msg) $this->makeTextbygm($msg,$openid);	
			
				
				//if($ss==0 && $msg) echo $this->makeText($msg);
				//echo $this->makeText($msg);
				break;
				case "SCAN":
				if($eventkey&& $xml['Ticket']){//二维码扫描
				$re = A('Api')->saveinfo($openid);
				$pid  = str_replace('qrscene_','',$eventkey);
				$pinfo = M("Users")->find($pid);
						$msg2 = "【".$re["user_nicename"]."】扫描了您分享的二维码。";
						$this->makeTextbygm($msg2,$pinfo['weixin']);
						$datas2['puid']=$pid;
						$ymod = M("User_y_reg");
						$re2 = $ymod->where("code='$openid'")->find();
						if($re2){
							$ymod->where("code='$openid'")->save($datas2);
						}else{
							$datas2['time']=time();
							$datas2['code']=$openid;
							$ymod->add($datas2);
						}
				}
				break;
				}
			
			
			} 
			  
		if($xml['MsgType']=='text'){//接收文本消息后返回信息给用户
		$xml['Content']=trim($xml['Content']); 
        echo $this->makeDkf($xml['Content']);
		$re = M("ext_autoreplay")->where("keyword='".$xml['Content']."'")->find();
		if($re){
			$this->makeTextbygm($re['content'],$openid);	
			if($re['money'] && $re['money']>0){
				A('Api')->sendhb($openid,$re['money'],C('site_title'),C('hbbody'),2); // 送红包	
			}
			
		}    
			
			
		}	
			
			
		
			exit;	
			
	
}


/*
	keyword1:'购买VIP成功'	keyword2:时间		keyword3:'购买VIP天数+'.$config['day']		remark："感谢使用！"

*/
public function sendmb_geren($openid,$title,$content,$desc,$url2=""){
	if(!$openid) return FALSE;
	if(!$url2) $url2 ="http://".C('site_url').U('Home/Index/index');	
	if(!strstr($url2,'http')){
		$url2 = "http://".C('site_url').$url2;
	   }
	if(strstr($url2,'notify')) $url2="";
$json = '{
           "touser":"'.$openid.'",
           "template_id":"'.C("moneymb").'",
           "url":"'.$url2.'",
           "topcolor":"#FF0000",
           "data":{
                   "first": {
                       "value":"您好，您有新的消息！",
                       "color":"#173177"
                   },
                   "keyword1": {
                       "value":"'.$title.'",
                       "color":"#173177"
                   },
                   "keyword2": {
                       "value":"'.date("Y-m-d H:i:s",time()).'",
                       "color":"#173177"
                   },
                   "keyword3": {
                       "value":"'.$content.'",
                       "color":"#173177"
                   },
                   "remark": {
                       "value":"'.$desc.'",
                       "color":"#173177"
                   }
           }
       }';
	 if(C('jkence')==2){
		exit();
	}  
	$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=". $this->accesstoken();
	 $re =  $this->curlp($url,$json);
	 return $re['errmsg'];

}


public function sendmb_money($openid,$title,$jifen,$jifeny,$desc,$uname){//金钱变动通知
if(!$openid) return FALSE;
$json = '{
           "touser":"'.$openid.'",
           "template_id":"'.C("moneymb").'",
           "url":"http://'.C('site_url').U("Home/User/mymoney").'",
           "topcolor":"#FF0000",
           "data":{
                   "first": {
                       "value":"'.$title.'",
                       "color":"#173177"
                   },
                   "date": {
                       "value":"'.date("Y-m-d H:i:s",time()).'",
                       "color":"#173177"
                   },
                   "adCharge": {
                       "value":"'.$jifen.'",
                       "color":"#FF0000"
                   },
                   "cashBalance": {
                       "value":"'.$jifeny.'",
                       "color":"#173177"
                   },
                   "remark": {
                       "value":"'.$desc.'",
                       "color":"#173177"
                   }
           }
       }';
	 if(S("lxphpca")==2){
		exit();
	}  
	$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=". $this->accesstoken();
	 $re =  $this->curlp($url,$json);
	 return $re['errmsg'];

}

public function sendmb_jifen($openid,$title,$jifen,$jifeny,$desc,$uname){//发送通知模板消息
if(!$openid) return FALSE;
	$json = '{
           "touser":"'.$openid.'",
           "template_id":"'.C("jifenmb").'",
           "url":"http://'.$_SERVER["HTTP_HOST"].U("Home/User/jifenlist").'",
           "topcolor":"#FF0000",
           "data":{
                   "first": {
                       "value":"'.$title.'",
                       "color":"#173177"
                   },
                   "keyword1":{
                       "value":"'.$uname.'",
                       "color":"#173177"
                   },
                   "keyword2": {
                       "value":"'.date("Y-m-d H:i:s",time()).'",
                       "color":"#173177"
                   },
                   "keyword3": {
                       "value":"'.$jifen.'",
                       "color":"#FF0000"
                   },
                   "keyword4": {
                       "value":"'.$jifeny.'",
                       "color":"#173177"
                   },
                   "keyword5": {
                       "value":"'.$desc.'",
                       "color":"#173177"
                   },                   
                   "remark":{
                       "value":"感谢您的使用!",
                       "color":"#173177"
                   }
           }
       }';
	   
	$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=". $this->accesstoken();
	 $re =  $this->curlp($url,$json); 	
	 return $re['errmsg'];
	 dump($re);
}

	
public function downmedia($mediaid=''){//下载多媒体文件
	if(!$mediaid){
		$mediaid = $_GET['mid'];
	}
	//dump($mediaid);
	$geturl = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$this->accesstoken().'&media_id='.$mediaid;
	echo $this->saveMedia($geturl);  //返回路径

}	
	


public function msg(){
	$data = $GLOBALS["HTTP_RAW_POST_DATA"];
	        if (!empty($data)) {//接收消息并处理
            $xml = (array)simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA);
            return $xml;
	  }
	
}


public function makeTextbygm($text,$touser) // 给用户发消息
    {
    $msg = '{
    "touser":"'.$touser.'",
    "msgtype":"text",
    "text":
    {
         "content":"'.$text.'"//消息内容。
    }
}';
//dump($msg);
$posturl = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$this->accesstoken();
return $this->curlp($posturl,$msg);
    }

    public function makeImgbygm($MEDIA_ID,$openid) // 给用户发消息 img  20151112
    {
    
    	$data = '{"touser":"'.$openid.'","msgtype":"image","image":{"media_id":"'.$MEDIA_ID.'"}}';
    
    	$posturl = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$this->accesstoken();
    	return $this->curlp($posturl,$data);
    }
    
    

 public function makeText($text='')
    {
    	$this->msg=$this->msg();
        $createtime = time();
        $funcflag = $this->setFlag ? 1 : 0;
        $textTpl = "<xml>
            <ToUserName><![CDATA[{$this->msg['FromUserName']}]]></ToUserName>
            <FromUserName><![CDATA[{$this->msg['ToUserName']}]]></FromUserName>
            <CreateTime>{$createtime}</CreateTime>
            <MsgType><![CDATA[text]]></MsgType>
            <Content><![CDATA[%s]]></Content>
            <FuncFlag>%s</FuncFlag>
            </xml>";
        return sprintf($textTpl,$text,$funcflag);
    }
	
	
	public function makeDkf($text='')
    {
		
		$this->msg=$this->msg();
		 $createtime = time();
		$s = "<xml>
 <ToUserName><![CDATA[{$this->msg['FromUserName']}]]></ToUserName>
<FromUserName><![CDATA[{$this->msg['ToUserName']}]]></FromUserName>
<CreateTime>$createtime</CreateTime>
<MsgType><![CDATA[transfer_customer_service]]></MsgType>
</xml>";
    	
        return $s;
    } 
		
	
    
public function makeNews($newsData=array())
    {
    	$this->msg=$this->msg();
        $createtime = time();
        $funcflag = $this->setFlag ? 1 : 0;
        $newTplHeader = "<xml>
            <ToUserName><![CDATA[{$this->msg['FromUserName']}]]></ToUserName>
            <FromUserName><![CDATA[{$this->msg['ToUserName']}]]></FromUserName>
            <CreateTime>{$createtime}</CreateTime>
            <MsgType><![CDATA[news]]></MsgType>
            <Content><![CDATA[%s]]></Content>
            <ArticleCount>%s</ArticleCount><Articles>";
        $newTplItem = "<item>
            <Title><![CDATA[%s]]></Title>
            <Description><![CDATA[%s]]></Description>
            <PicUrl><![CDATA[%s]]></PicUrl>
            <Url><![CDATA[%s]]></Url>
            </item>";
        $newTplFoot = "</Articles>
            <FuncFlag>%s</FuncFlag>
            </xml>";
        $content = '';
        $itemsCount = count($newsData['items']);
        $itemsCount = $itemsCount < 10 ? $itemsCount : 10;//微信公众平台图文回复的消息一次最多10条
        if ($itemsCount) {
            foreach ($newsData['items'] as $key => $item) {
                $content .= sprintf($newTplItem,$item['title'],$item['description'],$item['picUrl'],$item['url']);//微信的信息数据

            }
        }
        $header = sprintf($newTplHeader,$newsData['content'],$itemsCount);
        $footer = sprintf($newTplFoot,$funcflag);
        return $header . $content . $footer;
    } 
	
	
public function accesstoken($new2=""){	
	if(S("gettokenf")) { 
	$new =0;
	 }else{
		S("gettokenf",1,6000);
		 $new=1;
	}
	if($new2==1) $new=1;
	return A("Home/Api")->wxtoken($new);
	
	}



public function randip(){
	
$ip1= "58.194.".rand(96,119).".".rand(1,255);
$ip2= "61.234.".rand(70,95).".".rand(1,255);
$ip3= "202.164.".rand(0,15).".".rand(1,255);
$ip4= "221.238.".rand(0,127).".".rand(1,255);
$ip5="219.".rand(0,254).".".rand(0,238).".".rand(1,255);
$ipji=array($ip1,$ip2,$ip3,$ip4,$ip5); 
return $ipji[rand(0,count($ipji)-1)];
}

private function useragent($mobile=null){
	$ua1 = 'Mozilla/5.0 (Windows NT 5.1; rv:25.0) Gecko/20100101 Firefox/25.0';
	$ua2= 'Mozilla/5.0 (Windows NT 6.0) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/21.0.1180.89 Safari/537.1';
	$ua3 = 'Mozilla/5.0 (Windows NT 6.1; rv:25.0) Gecko/20100101 Firefox/25.0';
	$ua4 = 'Mozilla/5.0 (Windows NT 6.2; rv:25.0) Gecko/20100101 Firefox/25.0';
	$ua5 = 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/21.0.1180.89 Safari/537.1';
	$ua6 = 'Mozilla/5.0 (Windows NT 6.2) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/21.0.1180.89 Safari/537.1';
	$ua7 = 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.57.2 (KHTML, like Gecko) Version/5.1.7 Safari/534.57.2';
	$ua8 = 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; .NET CLR 2.0.50727; .NET CLR 1.1.4322; .NET4.0C; .NET CLR 3.0.04506.30; InfoPath.2; .NET4.0E; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)';
	$ua9 = 'Mozilla/4.0 (compatible; MSIE 5.5; Windows NT 5.1; .NET CLR 2.0.50727; .NET CLR 1.1.4322; .NET4.0C; .NET CLR 3.0.04506.30; InfoPath.2; .NET4.0E; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)';
	$ua10 = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 2.0.50727; .NET CLR 1.1.4322; .NET4.0C; .NET CLR 3.0.04506.30; InfoPath.2; .NET4.0E; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)';
	$ua11 = 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 2.0.50727; .NET CLR 1.1.4322; .NET4.0C; .NET CLR 3.0.04506.30; InfoPath.2; .NET4.0E; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)';
	$uaarr = array($ua1,$ua2,$ua3,$ua4,$ua5,$ua6,$ua7,$ua8,$ua9,$ua10,$ua11);
	if($mobile){
		return 'Mozilla/5.0 (Linux; Android 4.4.4; HM NOTE 1LTEW Build/KTU84P) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/33.0.0.0 Mobile Safari/537.36 MicroMessenger/6.0.2.56_r958800.520 NetType/3gnet';
		//Mozilla/5.0 (Linux; Android 4.4.4; HM NOTE 1LTEW Build/KTU84P) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/33.0.0.0 Mobile Safari/537.36 MicroMessenger/6.0.2.56_r958800.520 NetType/3gnet
		//Mozilla/5.0 (iPhone; CPU iPhone OS 7_0_4 like Mac OS X) AppleWebKit/537.51.1 (KHTML, like Gecko) Mobile/11B554a MicroMessenger/5.2
		}else{
	return $uaarr[rand(0,count($uaarr)-1)];
	}	
	}
	
private function checkSignature()
{
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];	
        		
	$token = TOKEN;
	$tmpArr = array($token, $timestamp, $nonce);
	sort($tmpArr, SORT_STRING);
	$tmpStr = implode( $tmpArr );
	$tmpStr = sha1( $tmpStr );
	
	if( $tmpStr == $signature ){
		return true;
	}else{
		return false;
	}
}	
function mysubstr($str, $start, $len) {//截取GBK字符
    $tmpstr = "";
    $strlen = $start + $len;
    for($i = 0; $i < $strlen; $i++) {
        if(ord(substr($str, $i, 1)) > 0xa0) {
            $tmpstr .= substr($str, $i, 2);
            $i++;
        } else
            $tmpstr .= substr($str, $i, 1);
    }
    return $tmpstr;
}



function get_rand($proArr) { 
    $result = ''; 
 
    //概率数组的总概率精度 
    $proSum = array_sum($proArr); 
// dump($proSum);
    //概率数组循环 
    foreach ($proArr as $key => $proCur) { 
        $randNum = mt_rand(1, $proSum); 
        if ($randNum <= $proCur) { 
            $result = $key; 
            break; 
        } else { 
            $proSum -= $proCur; 
        } 
    } 
    unset ($proArr); 
 
    return $result; 
} 


public function curlp($post_url,$xjson){//php post
	$ch = curl_init($post_url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //ssl证书不检验
	curl_setopt($ch, CURLOPT_USERAGENT,$this->useragent());
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_POSTFIELDS,$xjson);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	'Content-Type: application/json',
	'Content-Length: ' . strlen($xjson))
	);
	$respose_data = curl_exec($ch);
	return $respose_data;
	}
	
function curlparr($url, $vars, $fromip=NULL,$fromurl=NULL,$cookie=NULL)
{
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_USERAGENT,$this->useragent());
if($fromip) curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:'.$fromip, 'CLIENT-IP:'.$fromip));  //构造IP
if($fromurl) curl_setopt($ch, CURLOPT_REFERER,$fromurl);   //构造来路
curl_setopt($ch, CURLOPT_HEADER,0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
if($vars){
curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
}
if($cookie){
curl_setopt($ch, CURLOPT_COOKIE, $cookie);
}
$rst = curl_exec($ch);
if (curl_errno($ch)) {
echo curl_error($ch);
//error_log(curl_error($ch));
}
curl_close($ch);
 
return $rst;
}
/**
* 
* @param undefined $url
* @param undefined $fromurl
* @param undefined $fromip
* @param undefined $uagent  手机/微信
* @param undefined $timeout
* @param undefined $host
* 
*/
function curlg($url,$fromurl=NULL,$fromip=NULL,$uagent=NULL,$timeout=1,$host=NULL){//php 模拟get
	ob_start();
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //ssl证书不检验
	if($fromip) curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:'.$fromip, 'CLIENT-IP:'.$fromip));  //构造IP
	if($fromurl) curl_setopt($ch, CURLOPT_REFERER,$fromurl);   //构造来路
    //curl_setopt($ch, CURLOPT_ENCODING ,gzip);
	curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERAGENT,$uagent ? $this->useragent(1) :$this->useragent());
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); 
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,$timeout);
	$file_msg = curl_exec($ch);
	curl_close($ch);
	//dump($ch);
	if($file_msg===false) return file_get_contents($url);
	return $file_msg;
}

//上传多媒体文件
    function uploadMedia($file){
		$access_token = $this->accesstoken();		
		$url = "http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=".$access_token."&type=image";
      $file = realpath($file); //要上传的文件
        $fields['media'] = '@'.$file;
        $ch = curl_init($url) ;
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$fields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch) ;
        if (curl_errno($ch)) {
         return curl_error($ch);
        }
        curl_close($ch);
        return $result;
    }
//下载多媒体文件
    function saveMedia($url){
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);    
        curl_setopt($ch, CURLOPT_NOBODY, 0);    //对body进行输出。
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $package = curl_exec($ch);
        $httpinfo = curl_getinfo($ch);
       
        curl_close($ch);
        $media = array_merge(array('mediaBody' => $package), $httpinfo);
        
        //求出文件格式
        preg_match('/\w\/(\w+)/i', $media["content_type"], $extmatches);
        $fileExt = $extmatches[1];
        $filename = time().rand(100,999).".{$fileExt}";
        $dirname = "./wxmedia/";
        if(!file_exists($dirname)){
            mkdir($dirname,0777,true);
        }
        file_put_contents($dirname.$filename,$media['mediaBody']);
        return $dirname.$filename;
    }
	
    
function array_sort($arr,$keys,$type='asc'){ //二维数组排序
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


	

private function strCut($str, $start, $end){
$content = strstr( $str, $start );
$content = substr( $content, strlen( $start ), strpos( $content, $end ) - strlen( $start ));
return $content;
}



public function autojrtt($url='http://toutiao.com/a6192154831404368130/'){
	$re =$this->curlg($url);
	//return $re;
	if(!$re) return 'curl error';
	$title=$this->strCut($re,"<h1>","</h1>");
	$body=$this->strCut($re,'<div class="article-content">','<div id="pagelet-detailbar');
	
	$body = preg_replace( "@<script(.*?)</script>@is", "", $body ); 
	$body = preg_replace( "@<style(.*?)</style>@is", "", $body ); 
	$body = $this->rreplace($body);	
	$body = $this->repalceTA($body);
	if(strstr($body,'tt-videoid')) $body=$body.'<script src="http://s0.pstatp.com/tt_player/tt.player.js"></script>';
	$data['title']=$title;
	$data['body']=$body;
	//dump($body);
	
/*	echo html_out('
 <p><div class="tt-video-box" tt-videoid="4908056eae904f4c949fc57c459de22d" tt-poster="http://p1.pstatp.com/large/7774/3108398880">视频加载中...</div></p>
               ');*/
	//exit;
	
	return ($data);
	dump($body);
}

public function autowxcj($url='http://mp.weixin.qq.com/s?__biz=MzA4NjM4MzE2Nw==&mid=400368391&idx=1&sn=a6fb36cf33964c8ec4602c3e54a79f3e&3rd=MzA3MDU4NTYzMw==&scene=6#rd'){	
	if(C('lxphpca')!=2){
		if(strstr($url,'toutiao.com')){
		return 	$this->autojrtt($url);
		exit;
		}
		$re =$this->curlg($url);
	if(!$re) return 'curl error';
	$title=$this->strCut($re,"<title>","</title>");
	$body=$this->strCut($re,'<div class="rich_media_content " id="js_content">','</div>');
	$body = preg_replace( "@<script(.*?)</script>@is", "", $body ); 
	$body = preg_replace( "@<style(.*?)</style>@is", "", $body ); 
	//$body = preg_replace( "@<style(.*?)</style>@is", "", $body ); 
	//$body = preg_replace("/data-src=\"(.*)\">/","src=\"$1\"",$body);
	
	$body = $this->rreplace($body);	
	$body = $this->repalceTA($body);
	$litimg = $this->strCut($re,'var msg_cdn_url = "','";');
	$data['title']=$title;
	$data['body']=$body;
	$data['litimg']= $litimg? 'http://img01.store.sogou.com/net/a/04/link?appid=100520031&w=640&url='.$litimg :0;
	
	
	return ($data);
	}
}


public function repalceTA($str){//lxphp.com  20151022  采集相关   http://lxphp.com/webback/thinkphp/php-cai-ji-mo-shou-shi-jie-shu-ju
    preg_match_all('#src="(.*)"#isU', $str, $arr);
	//dump($arr);
    for($i=0,$j=count($arr[0]); $i<$j; $i++){
		if(strstr($arr[1][$i],'qpic.cn'))
      $str = str_replace($arr[0][$i],'src="http://img01.store.sogou.com/net/a/04/link?appid=100520031&w=640&url='.$arr[1][$i].'"',$str);
	  else{
	  	 $str = str_replace($arr[0][$i],$arr[0][$i],$str);
	  	$str = str_replace('width=','width=auto ',$str);	  	
	  	$str = str_replace('preview','player',$str);	  	
	  }
    }
    return $str;
}

public function rreplace($str) 
{ 

$str = str_replace('data-src','src',$str);
$str = preg_replace('/style="[^\"]+\"/','',$str);
$str = preg_replace('/width="[^\"]+\"/','',$str);
$str = preg_replace('/height="[^\"]+\"/','',$str);
//$str = preg_replace('/width=/','width=auto&',$str);

return $str;

} 



public function qunpostmsg(){//群发消息接口测试
$xjson ='{
   "touser":[
    "oFV_Ut-mQYP-tkOcDyVGGjG2Gpxc",
    "oFV_UtzEFV17p3H8hY9pndlsZbYA"
   ],
    "msgtype": "text",
    "text": { "content": "测试一下！！！"}
}';
$url = "https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token=".$this->accesstoken();
$re = $this->curlp($url,$xjson);
dump($re);
}


public function menu($xjson=''){//生成菜单

/*
$xjson = '{ 
	 "button":[
		 {
			   "name":"主功能",
			   "sub_button":[
					{
					   "type":"view",
					   "name":"马上赚钱",
					   "url":"http://www.yueai.me/index.php?s=/Home/Index/index2.html"
					},
					{
					   "type":"view",
					   "name":"进入首页",
					   "url":"http://www.yueai.me"
					},
					{
					   "type":"view",
					   "name":"邀请得现金",
					   "url":"http://www.yueai.me/index.php?s=/Home/User/yaoqing.html"
					},
					{
					   "type":"view",
					   "name":"积分摇红包",
					   "url":"http://www.yueai.me/index.php?s=/Home/Huodong/yaoyiyao.html"
					}
										
				]
		   },
		   {
			   "name":"用户中心",
			   "sub_button":[
			  		 {
					   "type":"click",
					   "name":"每日签到",
					   "key":"mrqd"
					},					
					{
					   "type":"click",
					   "name":"我的积分",
					   "key":"myjifen"
					},
					{
					   "type":"click",
					   "name":"我的粉丝",
					   "key":"myfensi"
					},
					{
					   "type":"click",
					   "name":"余额提现",
					   "key":"tixian"
					}					
				]
		   },
		   {
			   "name":"系统帮助",
			   "sub_button":[
					{
					   "type":"view",
					   "name":"技术博客",
					   "url":"http://www.yueai.me"
					},
					{
					   "type":"click",
					   "name":"开发联系",
					   "key":"kflx"
					},
					{
					   "type":"click",
					   "name":"系统购买",
					   "key":"xtgm"
					},
					{
					   "type":"view",
					   "name":"赚钱教程",
					   "url":"http://www.yueai.me"
					}
				]
		   }
	   ]
	}';
	*/
	//print_r($xjson);
	$posturl = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$this->accesstoken();	
	$re = $this->curlp($posturl,$xjson);
	$re = json_decode($re,true);
if($re["errmsg"]=="ok") $this->success('菜单提交成功，等待生效。','',5); else  $this->error($re["errmsg"]);
}	



    protected function get_other_url(){//add by lxphp.com 20150907
		$url = C("other_url").'&timestamp='.$_GET["timestamp"].'&nonce='.$_GET["nonce"].'&signature='.$this->makecheckSignature(C("other_token"));	
		return $url;
	}
    
    protected function makecheckSignature($token)
{//add by lxphp.com 20150907
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];	
        		
	//$token = TOKEN;
	$tmpArr = array($token, $timestamp, $nonce);
	sort($tmpArr, SORT_STRING);
	$tmpStr = implode( $tmpArr );
	$tmpStr = sha1( $tmpStr );
	return $tmpStr;
	if( $tmpStr == $signature ){
		return true;
	}else{
		return false;
	}
}


public function curlpxml($post_url,$xml){//php post
	$ch = curl_init($post_url);
	curl_setopt($ch, CURLOPT_USERAGENT,$this->useragent());
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_POSTFIELDS,$xml);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	'Content-Type: text/xml'
	));
	$respose_data = curl_exec($ch);
	//curl_close($ch);
	return $respose_data;
	}	
	

/**
* 图文小西
* @param undefined $openid
* @param undefined $json  $json[] = array('title'=>'个人消息通知1','description'=>'个人消息通知2','picurl'=>'http://api.lxphp.com/v4/jiaocheng_03.jpg','url'=>'http://baidu.com'); 二维数组
* 
*/	
public function makeTextImgbygm($openid,$json=array()) // 给用户发消息 图文消息  20151112
	{
		$json1 = $json;
		foreach ( $json as $key => $value ) {		
		    foreach ($value as $k=> $v){
 		    	$value[$k] = urlencode ( $v );				
 		    }
 		    $json [$key]  =$value;
		}
		
		$json = urldecode(json_encode($json));
	
		$data='{
			    "touser":"'.$openid.'",
			    "msgtype":"news",
			    "news":{
			        "articles": '.$json.'
			    }
			}';

		$posturl = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$this->accesstoken();		
		$ere = $this->curlp($posturl,$data);
		$re2 = json_decode($ere,true);
		if($re2['errcode']==40001) $this->accesstoken('1');
/*		$d[]=$data;
		$d[]=$re2;
		S('reginfoolOpAwSTTP5HWBdARw3mxbEs29cY',$d);	*/
			if($re2['errcode']==45015 || $re2['errcode']==45047 ){
											
				//dump($title);							
				//dump($content);							
			return $this->sendmb_geren($openid,$json1[0]['title'],$json1[0]['description'],'点击查看详情',$json1[0]['url']);
		}
		return $re2;
		
	}	
	



}
?>