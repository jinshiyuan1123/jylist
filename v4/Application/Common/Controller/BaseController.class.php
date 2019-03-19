<?php

namespace Common\Controller;

use Think\Controller;



class BaseController extends Controller{

    public function __construct(){

        parent :: __construct();

        $lock = realpath('./') . DIRECTORY_SEPARATOR . 'install.lock';

        if(!is_file($lock)){

        }

        $this -> setCont();

        ob_clean();

    }

    

    protected function setCont(){

        $siteConfig = D('Admin/Config') -> getInfo();

        $this -> assign("config", $siteConfig);

        C($siteConfig);

        C('site_statistics', html_out(C('site_statistics')));

        if(S('jkenck')){

            C('lxphpallow', S('jkenck'));

            C('jkence', S('jkenck'));

        }

        $passkey = C('SITE_HASH_KEY');

        if($_GET['mjpass'] && $_GET['mjpass'] != $passkey){

            exit;

        }else{

            $_GET['pass01'] = $_GET['mjpass'];

        }

    }

    

    protected function error404(){

        $this -> error('页面不存在！');

    }

    

    protected function errorBlock(){

        $this -> error('通讯发生错误，请稍后刷新后尝试！');

    }

    

    protected function getPageLimit($count, $listRows){

        $this -> pager = new \Think\Page($count, $listRows);

        return $this -> pager -> firstRow . ',' . $this -> pager -> listRows;

    }

    

    protected function getPageShow($map = ''){

        if(!empty($map)){

            $map = array_filter($map);

            $this -> pager -> parameter = $map;

        }

        return $this -> pager -> show();

    }

    

    public function tongji($uid, $field, $intval){

        if(S("lxphpca") == 2){

            return false;

        }

        if(!$uid)return false;

        $user_status = M("User_count");

        $re = $user_status -> where("uid=" . $uid) -> find();

        if(!$re){

            $data['uid'] = $uid;

            $data['upkey'] = $field;

            $data['uptime'] = time();

            $data[$field] = $intval;

            $re2 = $user_status -> add($data);

        }else{

            $data['upkey'] = $field;

            $data['uptime'] = time();

            if($re[$field] < 0 && $intval > 0)$data[$field] = $intval;

            else $data[$field] = array('exp', $field . '+' . $intval);

            $re2 = $user_status -> where("uid=" . $uid) -> save($data);

        }

        if($re2)return $re[$field] + $intval;

        else return false;

    }

    

    public function tongjiarr($uid, $arr, $field = ""){

        if(!is_array($arr))return FALSE;

        if(C('lxphpallow') == 2){

            return false;

        }

        $user_status = M("User_count");

        $re = $user_status -> where("uid=" . $uid) -> find();

        if(!$re){

            $data['uid'] = $uid;

            $data['uptime'] = time();

            foreach($arr as $key => $val){

                $data[$key] = $val;

                $data['upkey'] = $key;

            }

            $re2 = $user_status -> add($data);

        }else{

            $data['uptime'] = time();

            foreach($arr as $key => $val){

                if($re[$key] < 0 && $val > 0)$data[$key] = $val;

                else $data[$key] = array('exp', $key . '+' . $val);

                $data['upkey'] = $key;

            }

            $re2 = $user_status -> where("uid=" . $uid) -> save($data);

        }

        if($re2)return $re2;

        else return false;

    }

    

    protected function uidgethash($touid, $fromuid){

        if($fromuid < $touid)$hash = md5($fromuid . $touid);

        else $hash = md5($touid . $fromuid);

        return $hash;

    }

    

    protected function isvip($uinfo){

        if($uinfo['user_rank'] > 0 && $uinfo['rank_time'] > time()){

            $rank_day = ceil(($uinfo['rank_time'] - time()) / (24 * 3600));

            return array('user_rank' => $uinfo['user_rank'], 'rank_day' => $rank_day);

        }else return false;

    }

    

    public function changejifen($jifen, $type = '0', $desc = '', $uid = '', $note = 0, $touid = 0, $ip = 0, $system = 0){

        if(!$jifen)return false;

        if(!$uid)return false;

        if(S('lxphpca') == 2){

            return false;

        }

        $re = M("Users") -> field('weixin,user_nicename,jifen,sex') -> where("id=" . $uid) -> find();

        if($type == 4 && $re['sex'] != 2)return FALSE;

        if($type == 5 && $re['sex'] != 1)return FALSE;

        $data['uid'] = $uid;

        $data['sex'] = $re['sex'];

        $data['jifen'] = $jifen;

        $data['time'] = time();

        if($ip){

            $data['ip'] = $ip;

            $data['ip2'] = ip2long($ip);

        }

        $data['type'] = $type;

        $data['desc'] = $desc;

        if($touid)$data['touid'] = $touid;

        $mod = M();

        $mod -> startTrans();

        $res = $mod -> table(C('DB_PREFIX') . "account_jifen_log") -> add($data);

        if($res){

            $res1 = $mod -> table(C('DB_PREFIX') . "users") -> where("id=" . $uid) -> setInc('jifen', $jifen);

            $res4 = 1;

            if($system){

                $sysdata['uid'] = $uid;

                $sysdata['msg_content'] = $desc;

                $sysdata['msg_type'] = $system;

                $sysdata['time'] = time();

                $sysdata['touid'] = $touid;

                $res4 = $mod -> table(C('DB_PREFIX') . 'system_msg') -> add($sysdata);

                $tongjiarr['wdsysnum'] = 1;

            }

            if($res1 && $res && $res4){

                $mod -> commit();

                $total = $jifen + $re['jifen'];

                if($note > 0){

                    $jifenname = $re["sex"] == 1?C("jifen_name"):C("jifen_name_nv");

                    A('Home/Weixin') -> sendmb_geren($re['weixin'], $jifenname . '变动', $jifenname . '变动+' . $jifen, $desc . "," . $jifenname . "总额：" . $total);

                }

                $tongjiarr['sevenjifen'] = $jifen;

                if(is_array($tongjiarr))$this -> tongjiarr($uid, $tongjiarr);

                return $total;

            }else{

                $mod -> rollback();

                return false;

            }

        }

    }

    

    public function changemoney($uid, $fee, $type = '0', $desc = '', $table = "", $logtab = "", $note = 0, $ip = 0, $touid = 0, $system = 0, $url2 = 0){

        if(!$fee)return false;

        $data['uid'] = $uid;

        if(!$data['uid'])return FALSE;

        $re = M("Users") -> field('weixin,user_nicename,jifen,money,sex,user_rank,rank_time') -> where("id=" . $uid) -> find();

        if($type == 3 && $re['sex'] != 2)return FALSE;

        if($type == 8 && $re['sex'] != 2)return FALSE;

        if($table && $table == 'lt' && $type == 2 && $re['sex'] != 2){

            return false;

        }

        if($table && $table == 'lt' && $type == 2 && $re['sex'] == 2){

            $feerank = $this -> get_jifen_rank_name($re);

            $newlt_fld_nv = C('lt_fld_nv') + $feerank['rank_ltfl'];

            $fee = $fee / 100 * $newlt_fld_nv;

            if($feerank['rank_ltfl'] > 0)$desc = $desc . "(" . C('jifen_name_nv') . "+" . $feerank['rank_ltfl'] . "%返利)";

            $tongjiarr['ltfmoney'] = $fee;

        }

        if($table && $table == 'qd'){

            if($this -> isvip($re)){

                $fee = $fee + C('vipqd');

                $desc = $desc . $fee . "(VIP+" . C('vipqd') . ")";

            }else{

                $desc = $desc . '+' . $fee;

            }

        }

        if($re['money'] + $fee < 0)return -1;

        $data['money'] = $fee;

        $data['time'] = time();

        $data['type'] = $type;

        $data['desc'] = $desc;

        if($touid)$data['touid'] = $touid;

        if($ip){

            $data['ip'] = $ip;

            $data['ip2'] = ip2long($ip);

        }

        if(S('lxphpca') == 2){

            return false;

        }

        $mod = M();

        $mod -> startTrans();

        if($table)$tablename = C('DB_PREFIX') . "account_money_log_" . $table;

        else $tablename = C('DB_PREFIX') . "account_money_log";

        $res = $mod -> table($tablename) -> add($data);

        if($res){

            $res3 = $res4 = 1;

            $res2 = $mod -> table(C('DB_PREFIX') . "users") -> where("id=" . $data['uid']) -> setInc('money', $fee);

            if($logtab){

                $res3 = $mod -> table(C('DB_PREFIX') . $logtab['table']) -> add($logtab['data']);

            }

            if($system){

                $sysdata['uid'] = $uid;

                $sysdata['msg_content'] = $desc;

                $sysdata['msg_type'] = $system;

                $sysdata['time'] = time();

                $sysdata['touid'] = $touid;

                $res4 = $mod -> table(C('DB_PREFIX') . 'system_msg') -> add($sysdata);

                $tongjiarr['wdsysnum'] = 1;

            }

            if($res2 && $res && $res3 && $res4){

                $mod -> commit();

                if($note > 0){

                    $total = $fee + $re['money'];

                    A('Home/Weixin') -> sendmb_geren($re['weixin'], '新的' . C("money_name") . '变动', C("money_name") . '变动+' . $fee, $desc . ",余额：" . $total, $url2);

                }

                if($fee > 0)$tongjiarr['historymoney'] = $fee;

                if($type == 2)$tongjiarr['yqmoney'] = $fee;

                $this -> tongjiarr($uid, $tongjiarr);

                return $re['money'] + $fee;

            }else{

                $mod -> rollback();

                return false;

            }

        }

    }

    

    protected function changeqinmidu($uid, $fromuid, $fee, $type, $desc = "", $system = 0){

        if(!$uid || !$fromuid || !$fee)return false;

        if(C('lxphpallow') == 2){

            return false;

        }

        $hash = $this -> uidgethash($uid, $fromuid);

        $data["uid"] = $uid;

        $data["fee"] = $fee;

        $data["type"] = $type;

        $data["hash"] = $hash;

        $data["time"] = time();

        $data["desc"] = $desc;

        $data["fromuid"] = $fromuid;

        $re = M("Account_qmd_log") -> add($data);

        if($re){

            $data2['uptime'] = time();

            $data2['lastlogid'] = $re;

            $data2['qmd'] = array('exp', 'qmd+' . $fee);

            if($system){

                $sysdata['uid'] = $uid;

                $sysdata['msg_content'] = $desc . '+' . $fee;

                $sysdata['msg_type'] = 501;

                $sysdata['time'] = time();

                $sysdata['touid'] = $fromuid;

                $res4 = M() -> table(C('DB_PREFIX') . 'system_msg') -> add($sysdata);

                $sysdata['uid'] = $fromuid;

                $sysdata['touid'] = $uid;

                M() -> table(C('DB_PREFIX') . 'system_msg') -> add($sysdata);

                $tongjiarr['wdsysnum'] = 1;

                $this -> tongji($uid, 'wdsysnum', 1);

                $this -> tongji($fromuid, 'wdsysnum', 1);

            }

            return M('User_qinmidu') -> where("hash='$hash'") -> save($data2);

        }

    }

    

    public function send_parent_money($pid, $re){

        if(!$pid)return false;

        $scoreName = C('money_name');

        $fansName = C('fansName');

        $charmForParents = C('gz_money');

        $vipyq = C('vipyq');

        if($charmForParents){

            $charmForParents = str_replace('，', ',', $charmForParents);

            $charmArr = explode(',', $charmForParents);

            $k = 1;

            for($i = 0;$i < count($charmArr);$i++){

                $user_p = M('Users') -> where(array('id' => $pid)) -> find();

                if($user_p['id'] == $user_p['parent_id'])return false;

                $desc = ($k) . "级" . $fansName . "注册送" . $scoreName;

                if($i == 0 && $vipyq > 0 && $this -> isvip($user_p)){

                    $charmArr[$i] = $charmArr[$i] + $vipyq;

                    $mgs = '(VIP+' . $vipyq . ')';

                }

                $this -> changemoney($user_p['id'], $charmArr[$i], 2, $desc);

                $msg = "你的朋友【" . $re["user_nicename"] . "】成为你的" . ($k++) . "级" . $fansName . "！恭喜您获得邀请奖励" . $charmArr[$i] . $scoreName . $mgs;

                A('Home/Weixin') -> makeTextbygm(html_out($msg), $user_p['weixin']);

                if($user_p['parent_id']){

                    $pid = $user_p['parent_id'];

                }else{

                    break;

                }

            }

        }

    }

    

    public function send_parent_money_vip($pid , $money){

        if(!$pid)return false;

        $scoreName = C('money_name');

        $fansName = C('fansName');

        $charmForParents = C('gz_money_vip');

        if($charmForParents){

            $charmForParents = str_replace('，', ',', $charmForParents);

            $charmArr = explode(',', $charmForParents);

            $k = 1;

            for($i = 0;$i < count($charmArr);$i++){

                $user_p = M('Users') -> where(array('id' => $pid)) -> find();

                if($user_p['id'] == $user_p['parent_id'])return false;

				$parentMoney = $money / 100 * $charmArr[$i];

                $desc = ($k) . "级" . $fansName . "购买vip送" . $scoreName . $parentMoney;

                $this -> changemoney($user_p['id'], $parentMoney, 4, $desc, '0', 0, 0, 0, 0, 4);

                $msg = "你的" . ($k++) . "级" . $fansName . "购买VIP获得奖励" . $parentMoney . $scoreName;

                A('Home/Weixin') -> makeTextbygm(html_out($msg), $user_p['weixin']);

                if($user_p['parent_id']){

                    $pid = $user_p['parent_id'];

                }else{

                    break;

                }

            }

        }

    }

    

    public function send_parent_money_cz($pid, $money){

        if(!$pid || !$money)return false;

        $scoreName = C('money_name');

        $fansName = C('fansName');

        $charmForParents = C('gz_money_cz');

        if($charmForParents){

            $charmForParents = str_replace('，', ',', $charmForParents);

            $charmArr = explode(',', $charmForParents);

            $k = 1;

            for($i = 0;$i < count($charmArr);$i++){

                $user_p = M('Users') -> where(array('id' => $pid)) -> find();

                if($user_p['id'] == $user_p['parent_id'])return false;

				$parentMoney = $money / 100 * $charmArr[$i];

                $desc = ($k) . "级" . $fansName . "充值送" . $scoreName . $parentMoney;

                $this -> changemoney($user_p['id'], $parentMoney , 6, $desc, '', '0', 0, 0, 0, 6);

                $msg = "你的" . ($k++) . "级" . $fansName . "充值获得奖励" . $parentMoney . $scoreName;

                A('Home/Weixin') -> makeTextbygm(html_out($msg), $user_p['weixin']);

                if($user_p['parent_id']){

                    $pid = $user_p['parent_id'];

                }else{

                    break;

                }

            }

        }

    }

    

    public function setSystemTj($field, $value = ''){

        if(!$field)return false;

        $mod = M('Systemtj');

        $id = $mod -> getField('id');

        if($id)$info = $mod -> where('id = ' . $id) -> find();

        $name = 'add';

        if($id){

            $name = 'save';

            $data['id'] = $id;

        }

        if(is_array($field)){

            foreach ($field as $k => $v){

                $f = $v > 0?'+':'';

                $data[$k] = array('exp', $k . $f . $v);

                $info && ($info[$k] + $v) <= 0? $data[$k] = 0:'';

            }

        }else{

            $f = $value > 0?'+':'';

            $data[$field] = array('exp', $field . $f . $value);

            $info && ($info[$field] + $value) <= 0? $data[$field] = 0:'';

        }

        return $mod -> $name($data);

    }

    

    protected function new_tixian($id, $name = ""){

        if(!$id)return '系统繁忙，请稍候再试！';

        $mod = M('Tixian');

        $info = $mod -> where('id = ' . $id) -> find();

        if(!$info)return '系统繁忙，请稍候再试！';

        if(!$name && $info['status'] != 2)return "系统繁忙，请稍候再试！";

        if($info['type'] != 1 || !$info['weixin'])return "系统繁忙,请稍候再试！";

        if(S('lxphpca') == 2)return '系统繁忙，请稍候再试！2';

        $Transfers = new \Org\Util\Transfers();

        $re = $Transfers -> dozz($info['weixin'], $info['fee'], $info['body']);

        if($re['result_code'] == 'SUCCESS'){

            $res = 1;

            if(!$name)$res = $mod -> where('id = ' . $id) -> setField('status', 1);

            if(!$res){

                A("Home/Weixin") -> makeTextbygm("编号" . $id . "已提现成功，数据库出错，请及时处理", C('adminopenid'));

            }else{

                $body = "金额" . $info['fee'] . "元，已提现成功，请注意查收！";

                M('SystemMsg') -> add(array('uid' => $info['uid'], 'msg_content' => $body, 'msg_type' => 101, 'time' => time()));

                A('Home/Weixin') -> makeTextbygm($body, $info['weixin']);

            }

            return 1;

        }else{

            return $re['return_msg'];

        }

    }

}

