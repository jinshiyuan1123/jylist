<?php
namespace Home\Controller;
use Home\Controller\SiteController;
	/**
	*  @author lxphp
	 * http://lxphp.com
	 * 锦尚中国源码论坛提供
	 */


class PayController extends SiteController {
		
     public function alipay($arr){
  
		require_once(ROOT_PATH."AlipayAPI/alipay.config.php");
		require_once(ROOT_PATH."AlipayAPI/lib/alipay_submit.class.php");
		/**************************请求参数**************************/

        //支付类型
        $payment_type = "1";
        //必填，不能修改
        //服务器异步通知页面路径
        $notify_url = "http://".$_SERVER['HTTP_HOST'].str_replace('index.php','',$_SERVER['SCRIPT_NAME'])."notifyzfb.php";
        //$notify_url = "http://商户网关地址/alipay.wap.create.direct.pay.by.user-PHP-UTF-8/notify_url.php";
        //需http://格式的完整路径，不能加?id=123这类自定义参数

        //页面跳转同步通知页面路径
        $return_url = "http://".$_SERVER['HTTP_HOST'].U("Home/User/index");//{U:("Home/Weishang/alipayReturn")}
        //$return_url = "http://商户网关地址/alipay.wap.create.direct.pay.by.user-PHP-UTF-8/return_url.php";
        //需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/

        //商户订单号
        $out_trade_no = $arr['out_trade_no'];
        //商户网站订单系统中唯一订单号，必填

        //订单名称
        $subject = $arr['subject'];
        //必填

        //付款金额
        $total_fee = $arr['fee'];
        //必填

        //商品展示地址
        $show_url = $_POST['WIDshow_url'];
        //必填，需以http://开头的完整路径，例如：http://www.商户网址.com/myorder.html

        //订单描述
        $body = $_POST['WIDbody'];
        //选填

        //超时时间
        $it_b_pay = $_POST['WIDit_b_pay'];
        //选填

        //钱包token
        $extern_token = $_POST['WIDextern_token'];
        //选填
		/************************************************************/
		$alipay_config['partner'] = C('alipay_config_partner');
		$alipay_config['seller_id'] = C('alipay_config_seller_id');
		$alipay_config['key'] = C('alipay_config_key');
		
		//构造要请求的参数数组，无需改动
		$parameter = array(
			"service" => "alipay.wap.create.direct.pay.by.user",
			"partner" => trim($alipay_config['partner']),
			"seller_id" => trim($alipay_config['seller_id']),
			"payment_type"	=> $payment_type,
			"notify_url"	=> $notify_url,
			"return_url"	=> $return_url,
			"out_trade_no"	=> $out_trade_no,
			"subject"	=> $subject,
			"total_fee"	=> $total_fee,
			"show_url"	=> $show_url,
			"body"	=> $body,
			"it_b_pay"	=> $it_b_pay,
			"extern_token"	=> $extern_token,
			"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
		);	
		
		
		//建立请求
		$alipaySubmit = new \AlipaySubmit($alipay_config);
		$html_text = $alipaySubmit->buildRequestParaToString($parameter);
		//$html_text = $alipaySubmit->buildRequestForm($parameter,"get", "支付宝支付");
		return $html_text;

	}
	
	
	public function wxpay($arr){
		
		require_once ROOT_PATH."WxpayAPI_php_v3/lib/WxPay.Api.php";
		require_once ROOT_PATH."WxpayAPI_php_v3/example/WxPay.JsApiPay.php";
		$tools = new \JsApiPay();
		$input = new \WxPayUnifiedOrder();
		$input->SetBody($arr['subject']);
		$input->SetAttach($arr['subject']);
		$out_trade_no =$arr['out_trade_no'];
		$input->SetOut_trade_no($out_trade_no);
		$input->SetTotal_fee("".$arr['fee']*100);
		$input->SetTime_start(date("YmdHis"));
		$input->SetTime_expire(date("YmdHis", time() + 3600));
		$input->SetGoods_tag("test");
		$input->SetNotify_url("http://".$_SERVER['HTTP_HOST'].str_replace('index.php','',$_SERVER['SCRIPT_NAME'])."notify.php");
		$input->SetTrade_type("JSAPI");
		$input->SetOpenid($arr['openid']);
		$order = \WxPayApi::unifiedOrder($input);
   
		$re = M('ChongzhiLog')->where('id ='.$arr['id'])->setField('nonce_str',$input->values['nonce_str']);
		if($re){
			$jsApiParameters = $tools->GetJsApiParameters($order);
			return $jsApiParameters;
		}else{
			return false;
		}

		
	}
	
	
	
	
		
	
		
}
	?>