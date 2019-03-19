<?php

namespace Admin\Model;

use Think\Model;

/**
 * 关注表
 */
class GiftlistModel extends Model {

    public function getNicename($ids = array()) {
        $re = $this->table("__USERS__")->field('id,user_nicename,user_login,ismj')->where('id in(' . $ids . ')')->select();
        if (!$re) {
            return false;
        }
        $arr = array();
        foreach ($re as $k => $v) {
            $arr[$v['id']] = $v['user_nicename'] ? $v['user_nicename'] : $v['user_login'];
        }
        return $arr;
    }

    public function countList($where = array()) {
        return $this->where($where)->order($order)->count();
    }

    public function loadList($where = array(), $limit = 0, $order = 'giftlist_id desc') {
        return $this->where($where)->order($order)->limit($limit)->select();
    }

    //删除单条数据
    public function delData($giftlist_id) {
        $where['giftlist_id'] = $giftlist_id;
        return $this->where($where)->delete();
    }

    //批量删除
    public function delMsgs($ids) {
        $where['_string'] = 'giftlist_id in(' . $ids . ')';
        return $this->where($where)->delete();
    }

}
