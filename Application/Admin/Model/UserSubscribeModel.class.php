<?php
namespace Admin\Model;
use Think\Model;
/**
 * 关注表
 */
 
class UserSubscribeModel extends Model{
	

	
	//已转移到UsersModel下	
	public function getNicename($ids = array()){
		$re = $this -> table("__USERS__") ->field('id,user_nicename,user_login') -> where('id in('.$ids.')') -> select();
		if(!$re){
			return false;
		} 
		$arr = array();
	  	foreach($re as $k =>$v){
	  		$arr[$v['id']] = $v['user_nicename'] ? $v['user_nicename'] : $v['user_login'] ;
	  	}		
		return $arr;
	}
	
	
	
	
	
	
	
	
	
	
	
	
}