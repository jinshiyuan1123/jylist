<?php

namespace Home\Controller;

use Home\Controller\SiteController;

/**
 * http://lxphp.com
 */
class FormController extends SiteController {
	public function index() {
		$bid = I ( 'get.bid' );
		if (! $bid) {
			// redirect(U('Home/Index/index'));
		}
		$where ['parent_id'] = 0;
		$where ['show'] = 1;
		$bxcatarr = D ( "Admin/Bxcat" )->loadData ( $where );
		// print_r($bxcatarr);
		$media = $this->getMedia ( '保险大分类' );
		$this->assign ( 'media', $media );
		$this->assign ( 'bxlist', $bxcatarr );
		
		if ($this->uinfo) {
			// redirect(U('Home/User/index'));
		}
		
		$this->siteDisplay ( 'form_index' );
	}

	public function subcat() {
		$pid = I ( 'get.pid' );
		if (! $pid) {
			redirect ( U ( 'Home/Form/index' ) );
		}
		$where ['parent_id'] = $pid;
		$where ['show'] = 1;
		$bxinfo = D ( "Admin/Bxcat" )->getInfo ( $pid );
		if (! $bxinfo) {
			$this->error ( '不存在这个分类' );
		}
		$bxcatarr = D ( "Admin/Bxcat" )->loadData ( $where );
		$this->assign ( 'bxlist', $bxcatarr );
		// print_r($bxinfo);
		$media = $this->getMedia ( $bxinfo ['name'] . '- 保险分类' );
		$this->assign ( 'media', $media );
		$this->siteDisplay ( 'form_list' );
	}
	
	public function detail() {
		$this->check_login ();
		if($this->uinfo['user_type']==3) $this->error('抱歉，您无招标权限');
		
		$breadCrumb = array('投保人招标'=>U('index'),'险种列表'=>U('subcat'),'保险详情'=>U());
		$this->assign('breadCrumb',$breadCrumb);
		$id = I ( 'get.id', '', 'intval' );
		if (! $id) {
			redirect ( U ( 'Home/Form/index' ) );
		}
		$bxinfo = D ( "Admin/Bxcat" )->getInfo ( $id );
		if (! $bxinfo) {
			$this->error ( '不存在这个保险' );
		}
		$media = $this->getMedia ( $bxinfo ['name'] . '- 保险分类' );
		$this->assign ( 'media', $media );
		if ($bxinfo ['fromid'] == 0) {
			$this->error ( '后台未设置提交表单' );
		}
		$this->formInfo = D ( 'DuxCms/FieldsetForm' )->getInfo ( $bxinfo ['fromid'] );
		$model = D ( 'DuxCms/FieldData' );
		$model->setTable ( $this->formInfo ['table'] );
		$where = array ();
		$where ['A.fieldset_id'] = $this->formInfo ['fieldset_id'];
		$fieldList = D ( 'DuxCms/FieldForm' )->loadList ( $where );
		
		// 获取HTML
		$html = '';
		foreach ( $fieldList as $value ) {
			$html .= D ( 'DuxCms/Field' )->htmlFieldFull ( $value );
		}
		
		ob_start ();
		
		$this->show ( $html );
		$html = ob_get_clean ();
		$this->assign ( 'html', $html );
		
		$province = M ( 'area' )->where ( "rootid=0" )->select ();
		$this->assign ( 'province', $province );
		$bxlist = M ( 'Bxlist' )->select();
		$this->assign ( 'bxlist', $bxlist );
		$table = $this->formInfo ['table'];
                $t=time()+60*60*24;
                $time=date("Y/m/d H:i",$t);
                $this->assign ( 'time', $time );
		$this->assign ( 'table', $table );
		
		$this->siteDisplay ( 'form_detail' );
	}
	
	
	/**
	 * 文件上传
	 */
	public function upload() {
		$return = array (
				'status' => 1,
				'info' => '上传成功',
				'data' => '' 
		);
		$file = D ( 'DuxCms/File' );
		$info = $file->uploadData ( $_FILES );
		if ($info) {
			$return ['data'] = $info;
		} else {
			$return ['status'] = 0;
			$return ['info'] = $file->getError ();
		}
		$this->ajaxReturn ( $return );
	}

	/**
	 * 发布
	 */
	public function push(){
		if(!IS_POST){
			$this->error404();
		}
		 $table = I('post.table');
		 if(empty($table)){
		 	$this->errorBlock();
		 }
		 //uid proid cityid zb_title zb_offtime bxarr
		$arr =I('post.arr');
		//[zb_offtime] => 2015/03/02 18:31 
		$arr['zb_offtime'] =strtotime($arr['zb_offtime']);
		$arr['create_time']=time();
		$arr['status']=2;
		$arr['zb_status']=1;
		$arr['table']=$table;
		
		//获取表单信息
		$where = array();
		$where['table'] = $table;
		$formInfo = D('DuxCms/FieldsetForm')->getWhereInfo($where);
		
		if(empty($formInfo)){
			$this->errorBlock();
		}
		if(!$formInfo['post_status']){
			$this->errorBlock();
		}
		//设置模型
		$model = D('DuxCms/FieldData');
		$model->setTable($formInfo['table']);
		
		//
	    $table_name = 'ext_'.$formInfo['table'];
	    	//增加信息
	   $card =  I('post.Fieldset_b_cph','','trim');
	    if($card){
	    	$card_count = M('ext_'.$formInfo['table'])->where("b_cph ='$card'")->count();
	    	if($card_count)$this->error('该车牌号已有招标记录');
	    }
	    	if ($dataid = $model->saveData('add',$formInfo)){
	    		$wheredata['data_id'] =$dataid;
	    	    //$re = M('ext_'.$formInfo['table'])->where($wheredata)->save($arr);
	    		$arr['data_id']=$dataid;
	    		$re = M('ext_zblist')->add($arr);
	    		 if(!$re)$this->error('发布失败');
	    		 $this->success($formInfo['post_msg'],U('Home/User/insurance',array('id'=>$re)));
	    	}else{
	    		$msg = $model->getError();
	    		if (empty($msg))
	    		{
	    			$this->error($formInfo['name'].'发布失败，请刷新后重新尝试！');
	    		}else{
	    			$this->error($msg);
	    		}
	    	}
	}
	
	
	/**
	 * 标书列表
	 */
	public function bidding(){
	
		$breadCrumb = array('招标列表'=>U('bidding'));
		$this->assign('breadCrumb',$breadCrumb);
		
		$p=I('get.p','1','intval');
		$type =I('get.type','','intval');
		if($type==1){
			$this->check_login();
			if($this->uinfo['user_type']==1){
				$this->error('抱歉,您无竞标权限！');
			}
		}
		$data = $this->newselect_zb('',$type,'',$p);
	
		foreach ($data['list'] as $k =>$v){
			//$data['list'][$k]['table'] =$val['table'];
			$data_id[$k] =$v['id'];
		}
		$data_ids =implode(',', $data_id);
 
		$bid_data =M('Bidding')->cache(true,600)->query("select zb_id,user_login from dux_bidding  where zb_id in($data_ids) GROUP BY zb_id order by update_time DESC,create_time DESC");
		
		$id_data =array_flip($data_id);
		if($bid_data){
			foreach ($bid_data as $kk=>$vv){
				if(isset($id_data[$vv['zb_id']])){
					$data['list'][$id_data[$vv['zb_id']]]['bid_data'] =$bid_data[$kk] ;}
			}
		}
	
		
		$this->assign ( 'data', $data );
		$this->siteDisplay ( 'bidding' );
	}
	
	
	public function bid(){
		$this->check_login ();
		if($this->uinfo['user_type']==1){
			$this->error('抱歉,您无竞标权限！');
		}
		if($this->uinfo['user_status']==2){
			$this->error('对不起，您的竞标权限未通过验证，请您联系在线客服！');
		}
		if(!$this->uinfo['bxarr']){
			$this->error('对不起，您的竞标权限未通过验证，请您联系在线客服！');
		}
		$breadCrumb = array('招标列表'=>U('bidding'),'招标详情'=>U());
		$this->assign('breadCrumb',$breadCrumb);
		$id =I('get.id','','intval');
		$uinfo = $this->uinfo;
	
		if(!$id) $this->error('参数错误');
		$data = $this-> get_zb($id);
		if($uinfo['bxarr']){
			$uinfo['bxarr'] =unserialize($uinfo['bxarr']);
			$data['bxarr']=explode(',', $data['bxarr']);
			if(!array_intersect($uinfo['bxarr'],$data['bxarr'])){
				$this->error('对不起，您的竞标权限未通过验证，请您联系在线客服！');
			}	
		}

		$where['A.status'] =1;
		$where['_string'] ="A.bxid in (".$data['bxarr'].")";
		$bid_data = D('Admin/Prove')->getList($where);
		
	
		
		if($bid_data){
			foreach ($bid_data as $v){
				$bid_user[] =$v['user_login'];
			}
			$data['bid_user'] =implode(",", $bid_user);
		}
		$table = $data['table'];
		$bid_list = $this->bid_list($table);
	   
		$this->assign ( 'bid_list', $bid_list );
		$this->assign ( 'data', $data );
		$this->siteDisplay ( 'bid' );
	}

	/**
	 * 招标详情页
	 */
	public function insurance(){
		if (! IS_POST) {
		$this->check_login ();
		$uinfo = $this->uinfo;
		$id =I('get.id','','intval');
		if(!$id) $this->error('参数错误');
	    $breadCrumb = array('招标列表'=>U('bidding'),'招标详情'=>U('bid',array('id'=>$id)),'招标详情页'=>'javascript:;');
		$this->assign('breadCrumb',$breadCrumb);
		
		
		$data =$this->get_zb_data($id);
		
		$where['A.table']=$data['table'];
		$re = D('DuxCms/Fieldset')->getList($where);
		
		foreach ($re as $k=>$v){
			$config=array();$key =array();$val =array();$str=array();
			if(isset($data['data'][$v['field']])){
				if($v['type']==6){
					$da =unserialize($data['data'][$v['field']]);
					$title[$v['name']] =$da;
				}elseif ($v['type']==9||$v['type']==8||$v['type']==7){
				
					$config = explode(',', $v['config']);
					$field =explode(',', $data['data'][$v['field']]);
					foreach ($field as $kee =>$vaa){
						$ke =intval($vaa)-1;
						if(isset($config[$ke])){
							$str[] =$config[$ke];
						}
					}
					 $str = implode(',', $str);
					  $title[$v['name']]  =  $str;
					
				}elseif($v['type']==10){
				   $title[$v['name']] =	date ( 'Y-m-d H:i:s', $data['data'][$v['field']]);
				}else{
					$title[$v['name']] =$data['data'][$v['field']];
				}
			}
		}
	
		$bxlist = $this->check_bx();
		$bxids =explode(',', $data['bxarr']);
		foreach ($bxids as $va){
			$bxname[] = $bxlist[$va];
		}
	    if(I('get.act')=='dobid'){
	        if($uinfo['user_type']==1)  $this->error('您没有竞标权限！');
	    	if($data['uid']==$uinfo['id']) $this->error('业务员不能竞自己的招标！');

	    	$str_where['A.status'] =1;
	    	$str_where['_string'] ="A.bxid in (".$data['bxarr'].")";
	    	$bid_data = D('Admin/Prove')->getList($str_where);
	    	foreach ($bid_data as $val){
	    		if($val['uid']==$uinfo['id']){
	    			$data['isbid'] =1;
	    			$data['cb'][$val['bxid']] =$val['bx_name'];
	    		}
	    		
	    	}
	    }
  
		$this->assign('city', $this->check_city());
		$this->assign ( 'bxname', $bxname );
		$this->assign ( 'title', $title );
		$this->assign ( 'data', $data );
		$this->assign ( 'media', $media );
		$this->siteDisplay ( 'insurance' );
		}else{
			$arr = $_POST;
			$uid = $arr['zb_uid'];
			unset($arr['zb_uid']); 
		   	$arr['charge'] = array_filter($arr['charge']);
		   	$arr['form'] = array_filter($arr['form']);
		   	$arr['create_time'] =time();
		   	$arr['status'] = 1 ;
		   	if(!$arr['uid']||!$arr['zb_id']||!$arr['charge']||!$arr['form']) $this->error('请填写完整');
		   	// if(!is_numeric($arr['sx_cost'])) $this->error('费用必须为数字'); $arr['sx_cost'] = intval($arr['sx_cost']);
		   	 $arr['charge'] =implode(',', $arr['charge']);$arr['form'] =implode(',', $arr['form']);
		   	 $re =  M('bidding')->add($arr);
             if ($re) {
             	 $sql ="update dux_ext_zblist set bid_num = bid_num+1 where id={$arr['zb_id']}";
             	 M('ext_'.$arr['table'])-> execute($sql);
             	 $this->sendsysmsg($uid,'系统消息',"您有新的竞标,请查看！");
             	 //$this->sendmobmsg($uid,$this->xmsg);
             	 ///Home/User/myBidDetail/id/30/zb_id/10
             	 $this->sendsysmsg(0,'新竞标',$arr['zb_id']);
             	$this->success('提交成功',U('Home/User/myBidDetail',array('id'=>$re,'zb_id'=>$arr['zb_id'])));
             }else{
             	$this->error('提交失败');
             }
		}
	}
	
	public function text(){
		$this->sendsysmsg(0,'新竞标',11);
	}
	
	
	public function Catlist(){
		$type = I('get.type','');
		$p=I('get.p','1','intval');
		if($type==1){
			$id =I('get.id','');
			if(!$id) $this->error('参数错误');
			$where['parent_id'] =$id;
		    $ids = M('bxcat')->field('fromid')->where($where)->select();
            foreach ($ids as $v){
            	if($v['fromid']!=0){
            		$c_ids[] =$v['fromid'];
            	}
            }
		    $c_ids = implode(',', $c_ids);
		    $data =$this->cat_list($c_ids,true,$p);
		}
		
		if($type==2){
			$id =I('get.id','');
			$data =$this->cat_list($id,false,$p);
		}
		
		$this->assign ( 'data', $data );
		$this->siteDisplay ( 'bidding' );

	}
	
	public function search(){
		$p=I('get.p','1','intval');
		$key = I('post.key','');
		$id =I('get.id','');
		$data =$this->search_key($key,$p);
		$this->assign ( 'data', $data );
		$this->siteDisplay ( 'bidding' );
	
	}
	
	
		public function lyb(){
		if(!$this->uinfo) A('Public')->dowxlogin();
			$media = $this->getMedia ( '提交反馈', '', '', '提交反馈', 'ismenu' );
		$this->assign ( 'media', $media );
		$this->siteDisplay ( 'lyb' );
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}