<?php

// 定义应用目录
define('BIND_MODULE', 'Pay');
define('APP_PATH', './Application/');
define('APP_DEBUG', false);
define('RuntimePHP_Cache', false);

// 定义运行时目录
define('RUNTIME_PATH', './Runtime/');

require './ThinkPHP/ThinkPHP.php';


/*
require_once "./WxpayAPI_php_v3/lib/WxPay.Api.php";
require_once './WxpayAPI_php_v3/lib/WxPay.Notify.php';
require_once './WxpayAPI_php_v3/example/log.php';

//初始化日志
$logHandler= new CLogFileHandler("./paylog/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);


Log::DEBUG("begin notify");
$notify = new PayNotifyCallBack();
$re = $notify->Handle(false);
Log::DEBUG($GLOBALS['HTTP_RAW_POST_DATA']);

*/


$data = $GLOBALS["HTTP_RAW_POST_DATA"];

//S("logfee",$data);
/*$data = '<xml><appid><![CDATA[wx0a702703ef19cd2b]]></appid>
<attach><![CDATA[系统充值]]></attach>
<bank_type><![CDATA[CFT]]></bank_type>
<cash_fee><![CDATA[1]]></cash_fee>
<fee_type><![CDATA[CNY]]></fee_type>
<is_subscribe><![CDATA[Y]]></is_subscribe>
<mch_id><![CDATA[1251521901]]></mch_id>
<nonce_str><![CDATA[wnnuy9xzpepsue674okl48flsez47oap]]></nonce_str>
<openid><![CDATA[oFV_Ut-mQYP-tkOcDyVGGjG2Gpxc]]></openid>
<out_trade_no><![CDATA[125152190120151020171208]]></out_trade_no>
<result_code><![CDATA[SUCCESS]]></result_code>
<return_code><![CDATA[SUCCESS]]></return_code>
<sign><![CDATA[F4728AAE05C5ECF55824B4103F9509B8]]></sign>
<time_end><![CDATA[20151020171216]]></time_end>
<total_fee>1</total_fee>
<trade_type><![CDATA[JSAPI]]></trade_type>
<transaction_id><![CDATA[1007150314201510201267920991]]></transaction_id>
</xml>';
*/
//exit;

$rexml = '<xml>
  <return_code><![CDATA[SUCCESS]]></return_code>
  <return_msg><![CDATA[OK]]></return_msg>
</xml>';

if (!empty($data)) {//接收消息并处理
    $xml = (array)simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA);
    $orderid = $xml['out_trade_no'];
    $fee = $xml['total_fee'];
    $openid1 = $xml['openid'];
    $nonce_str = $xml['nonce_str'];
    $transaction_id = $xml['transaction_id'];
    if ($xml['result_code'] == "SUCCESS") {
        $w['out_trade_no'] = $orderid;
        $w['nonce_str'] = $nonce_str;
        //$w['openid']=$openid1;
        $re = M("chongzhi_log")->where($w)->find();
        if ($re) {
            if ($re['fee'] == $fee / 100) {
                //$data=array();
                $data2['status'] = 1;
                //$data2['openid']=$openid1;
                $data2['transaction_id'] = $transaction_id;
                $re2 = M("chongzhi_log")->where("id=" . $re['id'])->save($data2);
                if ($re2) {
                    $name = array(1 => 'ConfigVip', 2 => 'ConfigCredit');
                    $config = M($name[$re['paytype']])->where('id = ' . $re['cid'])->find();
                    if ($re['paytype'] == 1) {
                        $umod = M("Users");
                        $userData = $umod->field('sex,rank_time')->where("id=" . $re['uid'])->find();
                        $sexname = $userData['sex'] > 1 ? 'manVip' : 'girlVip';
                        $oldrank_time = $userData['rank_time'];
                        if ($oldrank_time < time()) {
                            $newranktime = time();
                            if (!$oldrank_time)
                                $newvip = 1;
                        } else {
                            $newranktime = $oldrank_time;
                        }

                        $data3 = array('user_rank' => 1, 'rank_time' => $newranktime + intval($config['day']) * 24 * 3600);
                        if ($config['price'] == $fee / 100)
                            $re3 = $umod->where("id=" . $re['uid'])->save($data3);
                        if ($re3) {
                            if ($newvip == 1) {//购买VIP返利
								$parentFee = $config['price'] * M("Config")->where('name="moneyBL"')->getField('data');
                                A('Common/Base')->send_parent_money_vip($re['parent_id'] , $parentFee);
                                $SysArr = array($sexname => 1, 'vipMoney' => $fee / 100, 'vipMoneyDay' => $fee / 100);
                            } else {
                                $SysArr = array('vipMoney' => $fee / 100, 'vipMoneyDay' => $fee / 100);
                            }
                            $jifenfee = M("Config")->where('name="buy_vip_jifen"')->getField('data');
                            A('Common/Base')->changejifen($jifenfee, 201, '购买VIP奖励+' . $jifenfee, $re['uid'], 0, 0, 0, 1);
                            A('Home/Weixin')->sendmb_geren($re['openid'], '购买VIP成功', '购买VIP天数+' . $config['day'], "感谢使用！");
                            A('Common/Base')->setSystemTj($SysArr);
                        }
                    } else {
                        $newmoney = $fee / 100 * M("Config")->where('name="moneyBL"')->getField('data') + $config['zmoney'];
                        if ($config['money'] == $fee / 100)
                            $re5 = A('Common/Base')->changemoney($re['uid'], $newmoney, 401, date('Y-m-d') . '充值获得:' . $newmoney, '', '', 1, '', '', 1);
                        if ($re5) {
                            $jifenfee2 = M("Config")->where('name="buy_cz_jifen"')->getField('data');
                            $jifenfee2 = $newmoney / 100 * $jifenfee2;
                            A('Common/Base')->changejifen($jifenfee2, 201, '充值奖励+' . $jifenfee2, $re['uid'], 0, 0, 0, 1);
                            A('Common/Base')->send_parent_money_cz($re['parent_id'], $newmoney);
                            A('Common/Base')->setSystemTj(array('chongMoney' => $fee / 100, 'chongMoneyDay' => $fee / 100));
                        }
                        //M("Users")->where("id=".$re['uid'])->save($data3);
                    }
                    exit($rexml);
                }
            }
        }
    }
}


?>