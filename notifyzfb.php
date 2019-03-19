<?php

// 定义应用目录
define('BIND_MODULE', 'Pay');
define('APP_PATH', './Application/');
define('APP_DEBUG', false);
define('RuntimePHP_Cache', false);

// 定义运行时目录
define('RUNTIME_PATH', './Runtime/');

require './ThinkPHP/ThinkPHP.php';


require_once("./AlipayAPI/alipay.config.php");
require_once("./AlipayAPI/lib/alipay_notify.class.php");


//计算得出通知验证结果
$alipayNotify = new AlipayNotify($alipay_config);

$verify_result = $alipayNotify->verifyNotify();


if ($verify_result) {//验证成功
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //请在这里加上商户的业务逻辑程序代


    //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——

    //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表

    //商户订单号

    $out_trade_no = $_POST['out_trade_no'];

    //支付宝交易号

    $trade_no = $_POST['trade_no'];

    //交易状态
    $trade_status = $_POST['trade_status'];


    if ($_POST['trade_status'] == 'TRADE_FINISHED') {
        //判断该笔订单是否在商户网站中已经做过处理
        //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
        //请务必判断请求时的total_fee、seller_id与通知时获取的total_fee、seller_id为一致的
        //如果有做过处理，不执行商户的业务程序

        //注意：
        //退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知

        //调试用，写文本函数记录程序运行情况是否正常
        //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
    } else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
        //S('fsdfsdfsdf',$_POST);
        $re = M('chongzhi_log')->where(array('out_trade_no' => $out_trade_no))->find();
        if ($re) {
            $re2 = M('chongzhi_log')->where(array('id' => $re['id']))->save(array('status' => 1));
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
                    if ($config['price'] == $_POST['total_fee'])
                        $re3 = $umod->where("id=" . $re['uid'])->save($data3);
                    if ($re3) {
                        if ($newvip == 1) {//购买VIP返利
							$parentFee = $_POST['total_fee'] * M("Config")->where('name="moneyBL"')->getField('data');
                            A('Common/Base')->send_parent_money_vip($re['parent_id'] , $parentFee);
                            $SysArr = array($sexname => 1, 'vipMoney' => $_POST['total_fee'], 'vipMoneyDay' => $_POST['total_fee']);
                        } else {
                            $SysArr = array('vipMoney' => $_POST['total_fee'], 'vipMoneyDay' => $_POST['total_fee']);
                        }
                        $jifenfee = M("Config")->where('name="buy_vip_jifen"')->getField('data');
                        A('Common/Base')->changejifen($jifenfee, 201, '购买VIP奖励+' . $jifenfee, $re['uid'], 0, 0, 0, 1);
                        A('Home/Weixin')->sendmb_geren($re['openid'], '购买VIP成功', '购买VIP天数+' . $config['day'], "感谢使用！");

                        A('Common/Base')->setSystemTj($SysArr);
                    }
                } else {
                    $newmoney = $_POST['total_fee'] * M("Config")->where('name="moneyBL"')->getField('data') + $config['zmoney'];
                    //$data3['money']=array('exp','money+'.$newmoney);
                    if ($config['money'] == $_POST['total_fee'])
                        $re5 = A('Common/Base')->changemoney($re['uid'], $newmoney, 401, date('Y-m-d') . '充值获得:' . $newmoney, '', '', 1, '', '', 1);
                    if ($re5) {
                        $jifenfee2 = M("Config")->where('name="buy_cz_jifen"')->getField('data');
                        $jifenfee2 = $newmoney / 100 * $jifenfee2;
                        A('Common/Base')->changejifen($jifenfee2, 201, '充值奖励+' . $jifenfee2, $re['uid'], 0, 0, 0, 1);
                        A('Common/Base')->send_parent_money_cz($re['parent_id'], $newmoney);
                        A('Common/Base')->setSystemTj(array('chongMoney' => $_POST['total_fee'], 'chongMoneyDay' => $_POST['total_fee']));
                    }
                    //M("Users")->where("id=".$re['uid'])->save($data3);
                }
            }
        }
        //logResult("out_trade_no=".$out_trade_no.",支付成功\n");
        //判断该笔订单是否在商户网站中已经做过处理
        //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
        //请务必判断请求时的total_fee、seller_id与通知时获取的total_fee、seller_id为一致的
        //如果有做过处理，不执行商户的业务程序

        //注意：
        //付款完成后，支付宝系统发送该交易状态通知

        //调试用，写文本函数记录程序运行情况是否正常
        //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
    }

    //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——

    echo "success";        //请不要修改或删除

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
} else {
    logResult("out_trade_no=" . $_POST['out_trade_no'] . ",支付失败\n");
    //验证失败
    echo "fail";

    //调试用，写文本函数记录程序运行情况是否正常
    //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
}

?>