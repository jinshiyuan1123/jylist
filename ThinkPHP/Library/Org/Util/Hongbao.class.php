<?php
//header("Content-type: text/html; charset=utf-8");


	/**
	* 现金红包接口
	* 2015-5-9
	*作者博客 http://lxphp.com
	* 
	* @return
	*/
	









namespace Org\Util;

class Hongbao
{
	
/**
* 裂变红包
* 
* @return
*/	
public function liebianhongbao($arr){
	
	/**
	* 
	* $hb = new \Org\Util\Hongbao();		
			$arr['openid']='oFV_Ut-mQYP-tkOcDyVGGjG2Gpxc';
        	$arr['hbname']='紫竹IT互联';
        	$arr['body']='搜索微信号（lxphp）获取更多红包';
        	$arr['fee']=3;        	
        	$arr['num']=3;        	
			$re = $hb->liebianhongbao($arr);
	* 
	*/
	
	
	
//$comm = new Common_util_pub();
$data['mch_id'] = C('MCHID');
$data['mch_billno'] = $data['mch_id'].date("Ymd",time()).date("His",time()).rand(1111,9999);
$data['nonce_str'] = self::createNoncestr();
$data['re_openid'] = $arr['openid'];
$data['wxappid'] = C('APPID');
//$data['nick_name'] = $arr['hbname'];
$data['send_name'] = $arr['hbname'];
$data['total_amount'] = $arr['fee']*100;
//$data['min_value'] = $arr['fee']*100;
//$data['max_value'] = $arr['fee']*100;
$data['amt_type'] = 'ALL_RAND'; // 
//$data['amt_list'] = '200|100|100'; //200|100|100  String(256)  各红包具体金额，自定义金额时必须设置，单位分
$data['total_num'] = $arr['num']?$arr['num']:3; // 红包数量 3-20
//$data['client_ip'] = $_SERVER['REMOTE_ADDR'];
$data['act_name'] = C('site_title');
$data['remark'] = C('site_subtitle');
$data['wishing'] = $arr['body'];
/*
$data['share_content'] = '分享文案';
$data['share_url'] = 'http://lxphp.com';
$data['share_imgurl'] = 'http://www.yueai.me/v4/qrcode_for_gh_1ed7f7d83fad_344.jpg';
$data['logo_imgurl'] = 'http://www.yueai.me/v4/tb19.jpg';
$data['banner_imgurl'] = 'http://www.yueai.me/v4/tb19.jpg';
$data['watermark_imgurl'] = 'http://www.yueai.me/v4/tb19.jpg';
*/
if(!$data['re_openid']) {	
	 $rearr['return_msg']='缺少用户openid';
	 return $rearr;
}

$data['sign'] = self::getSign($data);
$xml = self::arrayToXml($data);
//dump($data);
//var_dump($xml);
$url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/sendgroupredpack";
$re = self::wxHttpsRequestPem($xml,$url);
$rearr = self::xmlToArray($re);


return  $rearr;
	
	
}	
	
	
	
	
	
	/**
	* 
	* 现金红包
* hbname 红包名称  fee 红包金额 /元  body 内容  openid 微信用户id
* @param undefined $arr
* 
* @return
*/

	
public function sendhongbaoto($arr){

//$comm = new Common_util_pub();
$data['mch_id'] = C('MCHID');
$data['mch_billno'] = $data['mch_id'].date("Ymd",time()).date("His",time()).rand(1111,9999);
$data['nonce_str'] = self::createNoncestr();
$data['re_openid'] = $arr['openid'];
$data['wxappid'] = C('APPID');
$data['nick_name'] = $arr['hbname'];
$data['send_name'] = $arr['hbname'];
$data['total_amount'] = $arr['fee']*100;
$data['min_value'] = $arr['fee']*100;
$data['max_value'] = $arr['fee']*100;
$data['total_num'] = 1;
$data['client_ip'] = $_SERVER['REMOTE_ADDR'];
$data['act_name'] = C('site_title');
$data['remark'] = C('site_subtitle');
$data['wishing'] = $arr['body'];
/*
$data['logo_imgurl'] = 'http://lxphp.com/wp-content/themes/weisaysimple/images/random/tb19.jpg';
$data['share_content'] = '分享文案';
$data['share_url'] = 'http://lxphp.com';
$data['share_imgurl'] = 'http://lxphp.com/f/weixin/qrcode_for_gh_1ed7f7d83fad_344.jpg';
*/
if(!$data['re_openid']) {	
	 $rearr['return_msg']='缺少用户openid';
	 return $rearr;
}
$data['sign'] = self::getSign($data);
$xml = self::arrayToXml($data);
//var_dump($xml);
$url ="https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack";
$re = self::wxHttpsRequestPem($xml,$url);
$rearr = self::xmlToArray($re);


return  $rearr;
}




public function send_coupon($mch_id,$appid){//发代金卷 2015-7-9
		$url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/send_coupon";
		$data['coupon_stock_id']=198269;
		$data['openid_count']=1;
		$data['openid']='oFV_Ut-mQYP-tkOcDyVGGjG2Gpxc';
		$data['mch_id']=C('MCHID');
		$data['appid']=C('APPID');
		$data['nonce_str']=self::createNoncestr();
		$data['partner_trade_no']=$data['mch_id'].date("Ymd",time()).date("His",time()).rand(1111,9999);
		$data['sign']= self::getSign($data);
		$xml = self::arrayToXml($data);
		$re = self::wxHttpsRequestPem($xml,$url);
		$rearr = self::xmlToArray($re);
		dump($rearr);
	}
	
	
	

	function trimString($value)
	{
		$ret = null;
		if (null != $value) 
		{
			$ret = $value;
			if (strlen($ret) == 0) 
			{
				$ret = null;
			}
		}
		return $ret;
	}
	
	/**
	 * 	作用：产生随机字符串，不长于32位
	 */
	public function createNoncestr( $length = 32 ) 
	{
		$chars = "abcdefghijklmnopqrstuvwxyz0123456789";  
		$str ="";
		for ( $i = 0; $i < $length; $i++ )  {  
			$str.= substr($chars, mt_rand(0, strlen($chars)-1), 1);  
		}  
		return $str;
	}
	
	/**
	 * 	作用：格式化参数，签名过程需要使用
	 */
	function formatBizQueryParaMap($paraMap, $urlencode)
	{
		$buff = "";
		ksort($paraMap);
		foreach ($paraMap as $k => $v)
		{
		    if($urlencode)
		    {
			   $v = urlencode($v);
			}
			//$buff .= strtolower($k) . "=" . $v . "&";
			$buff .= $k . "=" . $v . "&";
		}
		$reqPar;
		if (strlen($buff) > 0) 
		{
			$reqPar = substr($buff, 0, strlen($buff)-1);
		}
		return $reqPar;
	}
	
	/**
	 * 	作用：生成签名
	 */
	public function getSign($Obj)
	{
		foreach ($Obj as $k => $v)
		{
			$Parameters[$k] = $v;
		}
		//签名步骤一：按字典序排序参数
		ksort($Parameters);
		$String = $this->formatBizQueryParaMap($Parameters, false);
		//echo '【string1】'.$String.'</br>';
		//签名步骤二：在string后加入KEY
		$String = $String."&key=".C("MCKEY");
		//echo "【string2】".$String."</br>";
		//签名步骤三：MD5加密
		$String = md5($String);
		//echo "【string3】 ".$String."</br>";
		//签名步骤四：所有字符转为大写
		$result_ = strtoupper($String);
		//echo "【result】 ".$result_."</br>";
		return $result_;
	}
	
	/**
	 * 	作用：array转xml
	 */
public	function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key=>$val)
        {
        	 if (is_numeric($val))
        	 {
        	 	$xml.="<".$key.">".$val."</".$key.">"; 

        	 }
        	 else
        	 	$xml.="<".$key."><![CDATA[".$val."]]></".$key.">";  
        }
        $xml.="</xml>";
        return $xml; 
    }
	
	/**
	 * 	作用：将xml转为array
	 */
	public function xmlToArray($xml)
	{		
        //将XML转为array        
        $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);		
		return $array_data;
	}



	
	
	
	 public function wxHttpsRequestPem( $vars,$url, $second=30,$aHeader=array()){
	 	
                $ch = curl_init();
                //超时时间
                curl_setopt($ch,CURLOPT_TIMEOUT,$second);
                curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
                //这里设置代理，如果有的话
                //curl_setopt($ch,CURLOPT_PROXY, '10.206.30.98');
                //curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
                curl_setopt($ch,CURLOPT_URL,$url);
                curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
                curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
 
                //以下两种方式需选择一种
 
                //第一种方法，cert 与 key 分别属于两个.pem文件
                //默认格式为PEM，可以注释
                curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
                curl_setopt($ch,CURLOPT_SSLCERT,dirname(__FILE__).'/hongbao/apiclient_cert.pem');
                //默认格式为PEM，可以注释
                curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
                curl_setopt($ch,CURLOPT_SSLKEY,dirname(__FILE__).'/hongbao/apiclient_key.pem');
 
                curl_setopt($ch,CURLOPT_CAINFO,'PEM');
                curl_setopt($ch,CURLOPT_CAINFO,dirname(__FILE__).'/hongbao/rootca.pem');
 
                //第二种方式，两个文件合成一个.pem文件
                //curl_setopt($ch,CURLOPT_SSLCERT,getcwd().'/all.pem');
 
                if( count($aHeader) >= 1 ){
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader);
                }
 
                curl_setopt($ch,CURLOPT_POST, 1);
                curl_setopt($ch,CURLOPT_POSTFIELDS,$vars);
                $data = curl_exec($ch);
                if($data){
                        curl_close($ch);
                        return $data;
                }
                else { 
                        $error = curl_errno($ch);
                        echo "call faild, errorCode:$error\n"; 
                        curl_close($ch);
                        return false;
                }
        }
	
	
	
	
	
	

}


?>