<?php
namespace Home\Controller;
use Home\Controller\SiteController;
/**
 * 排行
 * 2016年5月17日16:43:58
 * http://bbs.52jscn.com
 */
class RankingController extends SiteController {
	
	
	//排行榜              魅力榜和财富榜
	public function Charmlist(){
				
		$type = I('get.type',''); //4种类型
		
		if(!$type){
			$this -> error('err');
			exit;
		}
		
		switch($type){
			case 1:		//总财富榜	
				$where['sex'] = 1;
				$order = 'A.jifen desc';
				break;
			case 2:		//总魅力榜
				$where['sex'] = 2;
				$order = 'A.jifen desc';
				break;
			case 3:		//周财富榜
				$where['_string'] = 'B.sex = 1';
				$order = 'A.sevenjifen desc';
				break;
			case 4:		//周魅力榜
				$where['_string'] = 'B.sex = 2';
				$order = 'A.sevenjifen desc';
				break;
		}
		
		$users = M('Users');
		$userCount = M('UserCount');
				
		if($type == 1 || $type == 2){  //这是总的排行
			$count = $users -> where($where) -> count();//总条数
		}else{  //周排行
			$count = $userCount -> alias('A') -> join('LEFT JOIN __USERS__ B ON A.uid = B.id') -> where($where) -> count();
		}
		
		$Page = new \Think\Page($count,20);//每页显示5条
		
		//数据           users 表里的 user_nicename,user_login,jifen,idmd5,user_rank
		if($type == 1 || $type == 2){  //这是总的排行										
			$list = $users -> alias('A') -> join('LEFT JOIN __USER_COUNT__ B ON A.id = B.uid') -> where($where) -> field('A.user_nicename,A.user_rank,A.idmd5,A.sex,A.user_login,A.avatar,A.jifen,A.id,B.zan,B.sumgift,B.sevenjifen,B.sevenzan') -> order($order) -> limit($Page -> firstRow . ',' . $Page -> listRows) -> select();
		}else{  //周排行
			$list = $userCount -> alias('A') -> join('LEFT JOIN __USERS__ B ON A.uid = B.id') -> where($where) -> field('A.zan,A.sumgift,A.sevenjifen,A.sevenzan,B.user_nicename,B.idmd5,B.sex,B.user_login,B.user_rank,B.avatar,B.jifen,B.id') -> order($order) -> limit($Page -> firstRow . ',' . $Page -> listRows) -> select();
		}
		
		
		$_GET['p'] = $_GET['p'] ? $_GET['p'] : 1;
		foreach($list as $k => $v){
			$list[$k] = $this -> get_jifen_rank_name($v);
			$list[$k]['order'] = 20*($_GET['p'] - 1) + $k + 1;
			$list[$k]['type'] = $type;
		}
		
		$this -> assign('type',$type);
		$this -> assign('list',$list);
		
		if($_GET['p'] >= 200){ //超过200条   不再下拉加载
			exit;
		}
		
		if(I("get.ajax") == 1){ //下拉加载请求
			if($list){	
				$data = $this -> sitefetch('ajax_charmlist');			
				$this -> ajaxReturn($data);
			}
			exit;
		}
		
		$media = $this -> getMedia ( '排行榜', '', '', '排行榜', 'ismenu' );		
		$this -> assign ( 'media', $media );			
		$this -> siteDisplay('index_charmlist');
	}
	
	
	
	
	//  亲密榜
	public function qinmibang(){
		
		$uid = $this -> uinfo['id'];
			
		$model = M("UserQinmidu");
		
		$count = $model -> where('fromuid = '.$uid.' or touid = '.$uid) -> count();//总条数
		
		$Page = new \Think\Page($count,20);//每页显示5条
		
		$list = $model -> field('id,qmd,fromuid,touid') -> where('fromuid = '.$uid.' or touid = '.$uid) -> order("qmd desc") -> limit($Page->firstRow.','.$Page->listRows) -> select();
		
		if(!$list){//没有亲密榜数据
			$this -> assign('data',111);
		}
		
		foreach($list as $k => $v){
			if($v['fromuid'] == $uid){
				$ids[] = $v['touid'];
				$list[$k]['hehe'] = 1;
				$sumgift = M('UserCount') -> where('uid = '.$v['touid']) -> field('sumgift') -> find();
				$list[$k]['sumgift'] = $sumgift['sumgift'];
			}
			
			if($v['touid'] == $uid){
				$ids[] = $v['fromuid'];
				$list[$k]['hehe'] = 2;
				$gift = M('UserCount') -> where('uid = '.$v['fromuid']) -> field('sumgift') -> find();	
				$list[$k]['sumgift'] = $sumgift['sumgift'];	
			}
		}
		
		$ids = join(',', $ids);		
		$result = $this -> getInfo($ids);
		
		foreach($result as $k => $v){
			$result[$k] = $this -> get_jifen_rank_name($v);
		}
		
		$_GET['p'] = $_GET['p'] ? $_GET['p'] : 1;
		foreach($list as $k => $v){
			$list[$k]['order'] = 20*($_GET['p'] - 1) + $k +1;
		}
		
		$this -> assign('list',$list);
		$this -> assign('uid',$uid);
		$this -> assign('result',$result);
						
		if($_GET['p'] >= 200){ //超过200条   不再下拉加载
			exit;
		}
		
		if(I("get.ajax") == 1){ //下拉加载请求
			if($list){	
				$data = $this -> sitefetch('ajax_charmlist_qmd');			
				$this -> ajaxReturn($data);
			}
			exit;
		}
		
		$media = $this -> getMedia ( '排行榜', '', '', '排行榜', 'ismenu' );
		$this -> assign ( 'media', $media );
		$this -> siteDisplay('index_qingmiban');
		
	}	
	
	
	
	
}