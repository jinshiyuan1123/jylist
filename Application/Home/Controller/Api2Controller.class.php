<?php
namespace Home\Controller;
use Home\Controller\SiteController;
	/**
	*  @author lxphp
	 * http://lxphp.com
	 * 锦尚中国源码论坛提供
	 */


class Api2Controller extends SiteController {
	
	
	
	
		public function getarea($lat,$lon){
			if(!$lon || !$lat) return false;
			$url = 'http://api.map.baidu.com/geocoder/v2/?ak='.C('ak').'&callback=&location='.$lat.','.$lon.'&output=json&pois=0';
			//echo $url;
			$re = $this->curl_get_contents($url);
			$re =  json_decode($re,true);
			return $re;
			dump($re);
		}
		

	public function bdlbsapi2($lat,$lon){
		$data['title']=$this->uinfo['user_nicename'];
		$data['latitude']=$lat;
		$data['tel']=$this->uinfo['user_login'];
		$data['sex']=$this->uinfo['sex'];
		$data['longitude']=$lon;
		$data['hash']=C('SITE_HASH_KEY');
		$re = $this->bdlbsapi($data);
		return $re;
	}
		
	 private function bdlbsapi($data,$lbsid=0){
			$ak = C('ak2');
			if($lbsid<=0){
    $purl = 'http://api.map.baidu.com/geodata/v3/poi/create';
}else{
    $data['id']=$lbsid;
    $purl = "http://api.map.baidu.com/geodata/v3/poi/update";
}     
    $data['ak']=$ak;
    $data['geotable_id']=C('LBS_DB');
    $data['coord_type']="1";
    $re = A("Weixin")->curlparr($purl,$data);
    $re = json_decode($re,true);
    if($re["status"]==0){
        $lbsid =$re['id']; 
    }else{
        $lbsid = -1;
    }
    return  $lbsid;

}
		
		
}

?>