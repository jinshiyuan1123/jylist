<?php
namespace Home\Model;
use Think\Model;

class UsersModel extends Model {
	
	
	/**
	*  openid 返回用户信息字段。
	* @param undefined $openid
	* @param undefined $field
	* 
*/
	public function wreuval($openid,$field='id'){
		return $this->where("weixin='{$openid}'")->getField($field);
	}
	
	public function idreinfo($id,$field='weixin'){//id获取 info
	$re = $this->find($id);
	return $re[$field];
	} 

	public function qd(){
		
	}
	
	public function myfens($uid){
		return $this->where("parent_id='{$uid}'")->count();
	}
	
	
	//纯手机注册
	public function reg($log="",$pwd="",$sex="",$age=""){
		$age = date('Y')-$age;
		$arr ['user_login'] = $log;
		$re = $this->where($arr)->find();
		if($re) return false;
		$arr ['last_login_ip'] = $arr ['regip'] = get_client_ip ();
		$arr ['create_time'] = time ();
		$parent_id = cookie('yq');
		if($parent_id>0){
			$re2 = $this->where("id=".$parent_id)->find();
			if($re2)
			$arr ['parent_id'] = $parent_id;
		}		
		$arr ['sex'] = $sex;
		$arr ['age'] = $age;
		$arr ['idmd5'] = md5($log. C ( 'PWD_SALA' ).$pwd);
		$arr ['last_login_time'] = time ();
		$arr ['user_pass'] = md5 ( $log . $pwd . C ( 'PWD_SALA' ) );
		$openid = cookie("regopenid");
		$wxinfo = S("reginfo".$openid);	
	    if($wxinfo){
			$arr= array_merge($arr,$wxinfo);
			S("reginfo".$openid,null);		
		}			
		$res = $this->add ( $arr );
		if($res) {
			$data['uid']=$res;
			$data['birthday']=$age."-01-01";
			$arr = array('mob'=>'hot','qq'=>'hot','weixin'=>'hot');
			$data['lxfs_config'] = serialize($arr);
			M("User_profile")->add($data);
			$userymod = M("User_y_reg");
			$reyreg = $userymod->where("code='$openid'")->save(array('regtime'=>time(),'reguid'=>$res));			
			return $res;
			}
		return false;
	}
	
	
	
	
	//上传照片
	public function upPhoto($uid=0,$title="",$phototype=0,$uploadfiles=array(),$thumbfiles=array()){
		if(!$uploadfiles) return false;
		$flag = C('photo_flag')>0?0:1;
		$time = time();
		foreach ($uploadfiles as $k => $v){			
			$data[] = array('uid'=>$uid,'title'=>$title,'uploadfiles'=>$v,'thumbfiles'=>$thumbfiles[$k],'timeline'=>$time,'phototype'=>$phototype,'flag'=>$flag,'idmd5'=>md5($thumbfiles[$k]));			
		}
    	//$data =array('uid'=>$uid,'title'=>$title,'title'=>I('post.title','','trim'),'title'=>I('post.title','','trim'),'title'=>I('post.title','','trim'));
		return  M('UserPhoto')->addAll($data);
	
	}
	//更改发放佣金字段
	public function savePhotoMoney($num,$uid){
		$mod = M('UserPhoto');
		$photoids  = $mod->where("uid ='".$uid."'")->order('photoid desc')->limit($num)->getField('photoid',true);
		//$sql = "select photoid from ".$mod->trueTableName." where uid ='".$uid."' order by photoid desc limit 0,".$num;
		$photoids = count($photoids)>1?implode(',', $photoids):$photoids[0];
		return $mod ->where('photoid in('.$photoids.')')->setField('payMoney',1);
	
	}
	
	
	
	
	
}

?>