<?php



namespace Home\Controller;



use Home\Controller\SiteController;



/**

 * http://lxphp.com

 */

class UserController extends SiteController {

	public function __construct() {

		parent::__construct ();

		if(!$this->uinfo){

		   redirect(U("Public/index"));

		   exit;

		} 

		$this -> assign('nav', 'User');

		if(C("onlywx")==1 && strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') === false){

		 $this->siteDisplay('jg_qzwxdk');

		exit;

		}

	}

	



	public function index(){

		

		$media = $this->getMedia ( '用户中心', '', '', '用户中心', 'ismenu' );

		//$uinfo = $this->uinfo;

		$usermod = M('Users');

		$uinfo =$usermod->where(' id ='.$this->uinfo['id'])->find();

		if(!$uinfo || $uinfo['user_status']==0) redirect(U('Public/dologout'));

		$uinfo['age'] = date('Y',time())-$uinfo['age'];

		$userCount = M('UserCount')->where('uid = '.$uinfo['id'])->find();

		$userProfile  =M('userProfile')->where('uid ='.$uinfo['id'])->find();

		$areaList = $this->get_area();

		$uinfo['province_name'] =$areaList[$uinfo['provinceid']]['areaname'];

		$uinfo['city_name'] =$areaList[$uinfo['cityid']]['areaname'];

		$this->setUserinfo('money',$uinfo['money']);

		if($uinfo['user_nicename']){

			$this->setUserinfo('user_nicename',$uinfo['user_nicename']);

		}else if($uinfo['user_nicename']!=$this->uinfo['user_nicename']){

			$uinfo['user_nicename']=$this->uinfo['user_nicename'];

		}		

		$this->setUserinfo('provinceid',$uinfo['provinceid']);

		$this->setUserinfo('cityid',$uinfo['cityid']);

		$this->setUserinfo('avatar',$uinfo['avatar']);

		$this->setUserinfo('user_rank',$uinfo['user_rank']);

		$this->setUserinfo('rank_time',$uinfo['rank_time']);

		$this->setUserinfo('user_status',$uinfo['user_status']);

		$uinfo = $this->get_jifen_rank_name($uinfo);

		if($userCount['wdsxnum']>0 || $userCount['wdgznum']>0 || $userCount['wdsysnum']>0 || $userCount['wdgiftnum']>0){

			 cookie('wdsxnum',1,3600);

			 }

		$this->assign ( 'userProfile', $userProfile );

		$this->assign ( 'userCount', $userCount );		

		if( S('qiandaotime'.$this->uinfo["id"]) == date('Ymd',time()) )

		$this->assign ( 'isqd', 1 );

		if($this->uinfo['last_login_time']<time()-86400){

			$usermod->where('id='.$this->uinfo['id'])->setField('last_login_time',time());

			$this->setUserinfo('last_login_time',time());

		}

		if($isvip = $this->isvip($uinfo)){

			$uinfo['user_rank'] = $isvip['user_rank'];

		}else{

			if($this->uinfo['user_rank']>0){

				$usermod->where('id='.$this->uinfo['id'])->setField('user_rank',0);

				$this->setUserinfo('user_rank',0);

			}

			$uinfo['user_rank'] = 0;

		}

		$this->assign ( 'uinfo', $uinfo );

		

		$this->assign ( 'media', $media );

		$this->siteDisplay ( 'user_center' );

	}

	

	

	/**

	 * 我的关注/我的粉丝

	 */

	public  function MySubscribe(){

		

		$mod =M('UserSubscribe');

		

		$type = I('get.type',0,'intval');

		

		$type? $where['a.touid'] = $this->uinfo['id']:$where['a.fromuid'] = $this->uinfo['id'];

		$type? $str = 'a.fromuid':$str = 'a.touid';

		

        $name = $type? '我的粉丝':'我的关注';

		$count = $mod ->alias('a')-> where($where) -> count();

		$Page = new \Think\Page($count, 15);



		$show = $Page -> show();

		$list = $mod ->alias('a')->join('__USERS__ b ON b.id='.$str,'LEFT')->join('__USER_PROFILE__ c ON c.uid= '.$str,'LEFT')->field('a.time,b.user_nicename,b.avatar,b.sex,b.age,b.jifen,b.user_rank,b.rank_time,b.provinceid,b.cityid,c.monolog,c.astro,b.idmd5')-> where($where) -> order('a.time desc') -> limit($Page -> firstRow . ',' . $Page -> listRows) -> select();

      

		$areaList = $this->get_area();

	    $Constellation =  C('Constellation');

        if($list){

        	foreach ($list as $k=>$v){

        		       	 

        		$list[$k]['time'] = $this->new_format_date($v['time']);

        		$list[$k]['province_name'] =$areaList[$v['provinceid']]['areaname'];

        		$list[$k]['city_name'] =$areaList[$v['cityid']]['areaname'];

        		$list[$k]['astro'] =$Constellation[$v['astro']];

        		$list[$k]['age'] = date('Y',time())-$v['age'];	

				$list[$k]['vip'] = $this -> isvip($v);

        		//$list[$k] = array_filter($list[$k]);

        	}

        }

        if($_GET['p']>=200)exit;

		if (I("get.ajax") == 1)

			$this -> ajaxReturn($list);

		

//		

//		dump($list);

//		exit;

		$this -> assign('type',$type);

		$media = $this->getMedia ( $name, '', '', $name, 'ismenu' );

		$this->assign ( 'name', $name );

		$this->assign ( 'media', $media );

		$this -> assign('list', $list);

		$this -> assign('page', $show);

		

		$this->siteDisplay ( 'user_subscribe' );

		

	}

	

	

	

	/**

	 * 我的相册

	 */

	public  function MyPhoto(){

		

		$mod = M('UserPhoto');

		$where =array('uid'=>$this->uinfo['id'],'flag'=>1,'phototype'=>0);

		$cphoto = $mod->where($where)->count();

		if($cphoto) $cinfo = $mod->where($where)->order('photoid desc')->getField('thumbfiles');



		$where['phototype'] = 1;

		$sphoto = $mod->where($where)->count();

		if($sphoto) $sinfo = $mod->where($where)->order('photoid desc')->getField('thumbfiles');



		$photo_config = M('UserProfile')->where('uid = '.$this->uinfo['id'])->getField('photo_config');

		if($photo_config) $photo_config = unserialize($photo_config);		

		$this -> assign('photo_config', $photo_config);

		$this -> assign('cphoto', $cphoto);

		$this -> assign('cinfo', $cinfo);

		$this -> assign('sphoto', $sphoto);

		$this -> assign('sinfo', $sinfo);

		

		$media = $this->getMedia ( '我的相册', '', '', '我的相册', 'ismenu' );

		$this->assign ( 'media', $media );

		

		$this->siteDisplay ( 'user_photo' );

	}

	

	/**

	 * 公开照相册 &&私密照相册

	 * */

	public function photolist(){

				

		$phototype = I('get.phototype',0,'intval');

		$model = M('user_photo');

		$where = array('uid' => $this->uinfo['id'], 'flag' => 1, 'phototype' => $phototype ); //审核通过     公开照片

		$count = $model -> where($where) -> count();

			

		$Page = new \Think\Page($count,8);

				

		$list = M("UserPhoto") -> where($where) ->  limit($Page->firstRow.','.$Page->listRows)->order('photoid desc')->select();

		

		$info = $this -> uinfo;

		

		foreach($list as $k => $v ){

			$list[$k]['user_nicename'] = $info['user_nicename'];

			$list[$k]['avatar'] = $info['avatar'];

			$list[$k]['aurl'] = U('Home/Show/photo',array('pid'=>$v['idmd5']));

		}

		

		if( $_GET['p'] >= 200 ){

			exit;

		}

		if( I("get.ajax") == 1){

			$this -> ajaxReturn($list);

		}

		

		$this -> assign('phototype',$phototype);

		$media = $this->getMedia ( '公开相册', '', '', '公开相册', 'ismenu' );	

		$this -> assign ( 'media', $media );

		$this -> assign('list',$list);

		$this -> siteDisplay("user_public_photo");

	}

	

	/**

	 * 删除相册

	 * 

	 */

	public function delPhoto(){

		$id = I('post.id',0,'intval');

	    $re =  M("UserPhoto")->where('photoid='.$id)->delete();

		if($re){

			$this->success();

		}else{

			$this->error('系统繁忙，请稍候再试！');

		}

		

	}

	

	

	

	

	

	/**

	 * 私密照设置

	 */

	public function setPhotoConfig(){

	    $qmd = I('post.qmd',0,'intval');

	    $jifen = I('post.jifen',0,'intval');

		if(!$qmd&&!$jifen){

			$data = array('status'=>-1,'msg'=>'请填写完整！');

		}else{

			$arr  = serialize(array($qmd,$jifen));

			$re = M('UserProfile')->where('uid = '.$this->uinfo['id'])->setField('photo_config',$arr);

			if($re===false){

				$data = array('status'=>-1,'msg'=>'保存失败');

			}else{

				$data = array('status'=>1,'msg'=>'成功');

			}

		}



	   $this->ajaxReturn($data);

	}

	

	

	/**

	 * 联系方式

	 */

	public function Mylxfs(){

		if(!IS_POST){

			$media = $this->getMedia ( '联系方式', '', '', '联系方式', 'ismenu' );

			$this->assign ( 'media', $media );

			$info  = M('UserProfile')->field('mob,qq,weixin,lxfs_config')->where('uid = '.$this->uinfo['id'])->find();

			if($info['lxfs_config']){

				$lxfs_config  = unserialize($info['lxfs_config']);

				$this->assign ( 'lxfs_config', $lxfs_config );

			}

			$this->assign ( 'info', $info );

			$this->siteDisplay ( 'user_lxfs' );

		}else{

			$mob =I('post.mob','','trim');

			$qq =I('post.qq','','trim');

			$weixin =I('post.weixin','','trim');

			$re = M('UserProfile')->where('uid = '.$this->uinfo['id'])->save(array('mob'=>$mob,'qq'=>$qq,'weixin'=>$weixin));

			if($re ===false){

				$this->error('保存失败');

			}else{

				$this->success();

			}	

		}



	}

	

	

	/**

	 * 联系方式设置

	 */

	public function Setlxfs(){

		   $data = I('post.data','');

		   $value = I('post.value',0,'intval');

		   $varr =array('','hot');

		   if($data){

		   	$mod =  M('UserProfile');

		   	$config = $mod->where('uid = '.$this->uinfo['id'])->getField('lxfs_config');

		   	if($config){

		   		$config = unserialize($config);

		   		if(!isset($config[$data])) $this->error('操作失败！');

		   	    $config[$data] =$varr[$value];

		   	    $config = serialize($config);

		   	 }else{

		   	 	$arr = array('mob'=>'','qq'=>'','weixin'=>'');

		   	 	if(!isset($arr[$data]))  $this->error('操作失败！');

		   	 	$arr[$data] = $varr[$value];

		   	 	$config = serialize($arr);

		   	 }

		   	 

		   	 if($mod->where('uid = '.$this->uinfo['id'])->setField('lxfs_config',$config)){

		   	 	$this->success();

		   	 }else{

		   	 	$this->error('操作失败！');

		   	 }



		   }else{

		   	$this->error('操作失败！');

		   }

		   

		   

	

	}

	

	

	

	/**

	 * 账号设置

	 */

	public function UserSet(){

		

	

		$this->siteDisplay ( 'user_set' );

	}

	

	

	

	/*

	 * 修改密码

	 * */

	public function changepwd(){

		

		if(IS_POST){

			$phone = $this -> uinfo['user_login'];

			$yzm = I('post.yzm','','trim');//接收手机验证码		

			

			if(!$yzm){

				$this -> ajaxReturn('请先填写完整信息'); //信息没写完整

			}	

					

			if(S($phone) != $yzm){

				$this -> ajaxReturn('手机验证码错误！'); //上线后开启验证码

			} 

			

			session('phone',$phone,300);		

			$this -> ajaxReturn(1);		

		}

		

		cookie("lxphpcode",1,300);	

		$list = $this -> uinfo;

		$this -> assign('list',$list);

		$this -> siteDisplay('user_changepwd');

	}

	

	

	

	

	



	/**

	 * 上传照片

	 */

	public function UpPhoto(){

		if(!IS_POST){	

            $media = $this->getMedia ( '上传照片', '', '', '上传照片', 'ismenu' );

            $this->assign ( 'title', getPhotoTitle() );

			$this->assign ( 'media', $media );

			$this->assign ( 'type', I('get.type') );

			$this->siteDisplay ( 'upPhoto' );

		}else{

			$uid = $this->uinfo['id'];

			$title =$this->string_filter(I('post.title','','trim')) ;

			$image = I('post.image'); 

			$uptype = I('post.uptype',0,'intval');

			if(!$image) 

				$this->error('请上传照片');



			$thumb_image = I('post.thumb_image');

			$UserMod = D('Users');



			if($lastId =$UserMod->upPhoto($uid,$title,$uptype,$image,$thumb_image)){

				if(C('photo_flag')!=1) {

					$name = 'count_'.$uptype.'_upPhoto_'.$uid;	

					if(!cookie($name)){

						$image_count = count($image);

						if(S($name)){

							$count = S($name);

						}else{

							$UserPhotoMod = M('UserPhoto');

							$where['uid'] = $uid;

							$where['phototype'] = $uptype;

							$where['flag'] = 1;

							$where['payMoney'] = 1;

							$where['_string'] = "FROM_UNIXTIME( timeline, '%Y%m%d' ) =".date("Ymd",time());

							$count = $UserPhotoMod->where($where)->count();

						}	



						$cif_num = $uptype ? C('up_photo_sm_num') : C('up_photo_gk_num');

						$cif_money = $uptype ? C('up_photo_sm') : C('up_photo_gk');

						$num = 0;

						$tolcount = ($count+$image_count);



						if($tolcount <=  $cif_num){

							$num =  $image_count;

						}else{

							if($count < $cif_num){

								$num =  $cif_num - $count;

							}

						}

						if($num){

							$typeName = $uptype?'私密照':'公开照';

							$desc ="上传".$image_count."张".$typeName.",获取".C('money_name');

							//$arr = array('uid'=>$uid,'money'=>$num*$cif_money,'time'=>time(),'')

							$re = $this->changemoney($uid, $num*$cif_money,0,$desc,'photo','',0,get_client_ip(),0,9);

							if($re >= 0){

								$this->tongjiarr($uid, array('photofmoney'=>$num*$cif_money,'photonum'=>$image_count));

								$UserMod->savePhotoMoney($num,$uid);

								S($name,$count+$num,strtotime(date('Y-m-d',strtotime('+1 day')))-time());

							} 

						}else{

							$this->tongji($uid, 'photonum',count($image));

							cookie($name,$count+$num,strtotime(date('Y-m-d',strtotime('+1 day')))-time());

						}

					}else{

						$this->tongji($uid, 'photonum',count($image));

					}

				}else{

					$this->setSystemTj('photoFlag',count($image));    

				}



				$this->newbchange($uid,3);

				$msg = C('photo_flag')>0?'，待审核！':'';

				$this->success('上传成功'.$msg);

			}else{

				$this->error('上传超时,请刷新后重试！');

			}

		}

	}

	

	

	

	

	



	/**

	 * 内心独白

	 *

	 */

	public function MyMolog(){

		$uid = $this->uinfo['id'];

		if(!IS_POST){

			$media = $this->getMedia ( '内心独白', '', '', '内心独白', 'ismenu' );

			$text =  M('Audit') ->field('text,status')-> where('uid = '.$uid.' and type = 2 and status <> 1 ') -> find();	

			$info = M('UserProfile') -> where('uid = '.$uid) -> getField('monolog');

			if($text){

				$this -> assign('text',$text['text']);

				$this -> assign('status',$text['status']==0?'*审核中…':'*审核未通过');

			}

			

			$this -> assign ( 'info',$info);

			$this -> assign ( 'media', $media );

			$this->siteDisplay ( 'user_molog' );

		}else{

			$molog = I('post.molog','','trim');

			$type = I('post.type',0,'intval');

			$userP = M('UserProfile');

			

			if($molog){				

				$res = $userP -> where('uid = '.$uid) -> field('monolog') -> find();  //user_profile   里的正式内心独白

				if( $molog == $res['monolog'] ){  //和user_profile正式内心独白 相同

					$re = 1;

					$msg = array('status'=>1);

				}else{

					$audit = M('Audit');

					if($type == 2){ //后台设置的内心独白  ，  无视开关

						$re = $userP -> where('uid = '.$uid) -> setField('monolog',$molog);

						$msg = array('status'=>1);

					}else{					

						if( C('monolog_flag') > 0 ){ //开启审核					

							$data['created_time'] = time();

							$data['text'] = $molog;

							$data['type'] = 2;

							$data['status'] = 0;

							$result = $audit -> where('uid = '.$uid." and type = 2") -> find(); 

							if($result){  //有记录

								if( $result['text'] != $molog ){ //有通过记录 ，并且  

									$re = $audit -> where('uid = '.$uid.' and type = 2') -> save($data);

									if($re&&$result['status'] != 0) $this -> setSystemTj('monoFlag',1);

									$msg = array('status' => -2);

								}else{

									$re = true;	

									$msg = array('status'=>1);									

								}

							}else{

								$data['uid'] = $uid;

								$re = $audit -> add($data); 

								if($re) $this -> setSystemTj('monoFlag',1); 

								$msg = array('status' => -2);

							}					                

						}else{ //关闭审核

							$re = M('UserProfile') -> where('uid = '.$uid) -> setField('monolog',$molog);

							$msg = array('status'=>1);

						}					

					}

				}

				

			    if($re===false){

			    	$this->error('保存失败');

			    }else{

//			    	$this->success();

					$this -> ajaxReturn($msg);

			    }

			}else{

				$this->error('请填写完整！');

			}			

		}

	}

	

	

	/**

	 * 推广联盟

	 */

	public function union(){

		$uid = $this->uinfo['id'];



		if(!IS_POST){

			$media = $this->getMedia ( '推广联盟', '', '', '推广联盟', 'ismenu' );

			$unionArr =  M('TgUnion') ->field('id,uid,realname,tel,intro,status')-> where('uid = '.$uid) -> find();	

			if($unionArr){

				$this -> assign('unionArr',$unionArr);

				$this -> assign('unionStatus',$unionArr['status']==2 ? '*审核未通过' : '*审核中…');

			}

			

			$this -> assign ( 'media', $media );

			$this->siteDisplay ( 'user_union' );

		}else{

			$unionArr = array();

			$unionArr['uid'] = $uid;

			$unionArr['realname'] = I('post.realname','','trim,addslashes');

			$unionArr['tel'] = I('post.tel',0,'trim,addslashes');

			$unionArr['intro'] = I('post.intro',0,'trim');

			$unionArr['status'] = 0;

			if(empty($unionArr['realname']) || empty($unionArr['tel']) || empty($unionArr['intro'])){

				$this->error('信息请填写完整！');

				exit;

			}



			$unionModel = M('TgUnion');

			$unionInfo = $unionModel->field('id')->where('uid = '.$uid) -> find();

			if(empty($unionInfo)){

				$unionArr['time'] = time();

				$unionNewId = $unionModel->add($unionArr);

				if(! $unionNewId){

					$this->error('保存失败');

					exit;	

				}

			}else{

				$unionArr['id'] = $unionInfo['id'];

				$rs = $unionModel->save($unionArr);

				if(false === $rs){

					$this->error('保存失败');

					exit;	

				}

			}

			

			$this -> success("审核中...");

		}

	}

	

	

	

	/**

	 * 获取随机标题

	 */

	public  function getSjTitle(){

		$type = I('post.type',0,'intval');   

		$info = getPhotoTitle($type);		

		if($info){

			$this->success($info);

		}else{

			$this->error();

		}		

	}

	

	

	

	/**

	 * 邀请列表

	 * 

	 */

	public function MyYqList(){

		$media = $this->getMedia ( '邀请列表', '', '', '邀请列表', 'ismenu' );

		$this->assign ( 'media', $media );

		$uids = $this->uinfo['id'];

	

		for($i=0;$i<3;$i++){

			$ids =M('Users')->where("parent_id in(".$uids.")")->getField('id',true);

			if(!$ids) continue;

			$uids = count($ids)>1?implode(',', $ids):$ids[0];

			$ulist[]  = $uids;

			$ucount[] = count($ids);

		}

		if($ulist) S('xhblist_'.$this->uinfo['id'],$ulist);

    

		$this->assign ( 'ulist', $ulist );

		$this->assign ( 'ucount', $ucount );

		$this->siteDisplay ( 'user_yqlist' );

	}

	

	

	/**

	 * 小伙伴列表

	 *

	 */

	public function MyXhbList(){

        $type = I('get.type',0,'intval');

		$name =array('一级小伙伴','二级小伙伴','三级小伙伴');

		

		$media = $this->getMedia ( $name[$type], '', '', $name[$type], 'ismenu' );

		$this->assign ( 'media', $media );

		$this->assign ( 'name', $name[$type] );

		$uid = $this->uinfo['id'];

	    if($ulist = S('xhblist_'.$uid)){

	    	$uids = $ulist[$type];

	    	$mod = M('Users');

	    	$where['id'] = array('in',$uids);

	    	$count = $mod ->where($where) -> count();

	    	$Page = new \Think\Page($count, 15);

	    	

	    	$show = $Page -> show();

	    	$list = $mod ->field('id,user_nicename,avatar,create_time,user_rank,idmd5')->where($where) -> order('id desc') -> limit($Page -> firstRow . ',' . $Page -> listRows) -> select();

	    	

	    	if($list){

	    		foreach ($list as $k=> $v){	    			

	    			$sids =$sids?$sids.','.$v['id']:$v['id'];

	    		}	    		

	    		if($sids){

	    			 $touidlist =  M('UserSubscribe')->where('fromuid='.$uid.' and touid in('.$sids.')')->getField('touid',true);

	    			$this->assign ( 'touidlist',  $touidlist );

	    		}

	    	}

			

	    	$this->assign ( 'page', $show );

	    	$this->assign ( 'list', $list );

	    	  if($_GET['p']>=200)exit;

		if (I("get.ajax") == 1){

			if($list) $data = $this->sitefetch('ajax_xhblist');

			 $this->ajaxReturn($data);

		}



	    }else{

	      $this->error('您暂无小伙伴！',U('MyYqList'));	

	    }



		$this->siteDisplay ( 'user_xhblist' );

	}

	

	



	/**

	 * 基础资料  ********************

	 */

	

	public function basedata(){

		$uid = $this->uinfo['id'];

		if(!IS_POST){

			

			$media = $this->getMedia ( '基础资料', '', '', '基础资料', 'ismenu' );

			$this->assign ( 'media', $media );

			$where['A.id'] = $uid;

			$base = M('Users')->alias('A')->join('__USER_PROFILE__  B on A.id = B.uid')->field('A.user_nicename,A.sex,A.provinceid,A.cityid,B.birthday,B.astro,B.code1,B.code2,B.code3,B.code4')->where($where)->find();

//			$base['age'] = date('Y',time())-$base['age'];

			$nick =   M('Audit')->field('text,status')->where('uid = '.$uid.' and  type = 1 ')->order('created_time desc')->find();

			

			if($nick&&$nick['status']!=1&&C('nickname_flag')>0){

				$base['user_nicename'] = $nick['text'];

				$base['open'] = $nick['status']==2?'未通过':'审核中';



			}

		

			$Constellation = C('Constellation');//星座列表

			$areaList = A('Home/Site')->get_area();  //获取地区

			foreach ($areaList as $v){

				if($v['rootid']==0){

					$province[] =$v;

				}

				if($v['rootid']==$base['provinceid']){  //如果城市的父id  是用户所在的省份的id    则  获取所在 省份的全部城市

					$city[] =$v;

				}

			}

			$SetProfile = C('SetProfile');

			

			

			//echo $this->get_xingzuo(substr($base['birthday'],-5));

			$this -> assign('profile',$SetProfile);

			$this -> assign('base',$base);

			$this -> assign('astro',$Constellation);//星座

			$this -> assign('province',$province);//省份

			$this -> assign('city',$city);//城市

			$this -> siteDisplay('user_basedata');

		}else{

			$user_nicename = I('post.user_nicename','','trim');	//昵称

			$user_nicename = $this->string_filter($user_nicename);

			if(!$user_nicename){

				$this->error('昵称必填');

			}

			$pro['birthday'] = I('post.birthday','');

			$age = str_replace("-", "", $pro['birthday']);

			$age = substr($age,0,4);

			$data['age'] = $age;

			

			//$data['sex'] = I('post.sex',0,'intval'); //性别

			$pro['astro'] = I('post.astro',0,'intval'); //星座

			$pro['code1'] = I('post.code1',0,'intval');

			$pro['code2'] = I('post.code2',0,'intval');

			$pro['code3'] = I('post.code3',0,'intval');

			$pro['code4'] = I('post.code4',0,'intval');

			

			

			$data['provinceid'] = I('post.provinceid',0,'intval'); //省份id

			$data['cityid'] = I('post.cityid',0,'intval'); //城市id

			$user = M('Users');//Users表   需要的字段  age  sex  user_nicename

			$profile = M("UserProfile"); //User_profile 表 需要的字段  astro , birthday , provinceid , cityid

			$audit = M("Audit");//审核表   需要的字段   uid , text , type , status , create_time

			$this->setUserinfo('user_nicename',$user_nicename);

			$this->setUserinfo('provinceid',$data['provinceid']);

			$this->setUserinfo('cityid',$data['cityid']);

			

//			if(C('nickname_flag')==0) $data['user_nicename'] = $user_nicename;

			if( C('nickname_flag') > 0 ){  //开启审核

				if($user_nicename == $this -> uinfo['user_nicename']){  //上传的还是原来正式的昵称

					$res = true;

					$returnmsg = array('type' => 1);  //昵称没变   不用提示再审

				}else{

					$xinxi['uid'] = $uid;

					$xinxi['text'] = $user_nicename; //昵称

					$xinxi['type'] =1;

					$xinxi['status'] = 0;

					$xinxi['created_time'] = time();

					$abc = $audit -> where('uid = '.$uid.' and type = 1') -> find();

					if(!$abc){  //没有审核记录

						$res = $audit -> add($xinxi);

					}else{  //有审核记录   不许要考虑是否是通过记录  在审 或没通过可覆盖   通过说明再用 已排除

						$res = $audit -> where('uid = '.$uid.' and type = 1') -> save($xinxi);						

					}

					

					if($res){						

						if(!$abc||$abc['status'] != 0) $this->setSystemTj('nicknameFlag',1);

						$returnmsg = array('type' => 2);  //需要提示在审

					}						

				}

			}else{ //没开启审核				

				$data['user_nicename'] = $user_nicename;				

				

				$returnmsg = array('type' => 1);  //没开启审核   不用提示再审

			}

			$res = $user -> where('id = '.$uid) -> save($data);

			$this->newbchange($uid,2);

			$res = $user -> where('id = '.$uid) -> save($data);

			

			

			if($res === false){

				$returnmsg = array('type' => 3);  //失败的情况

				$this -> ajaxReturn($returnmsg);

			}else{

				$re = $profile -> where('uid = '.$uid) -> save($pro);			

				if($re === false){

					$returnmsg = array('type' => 3);  //失败的情况

					$this -> ajaxReturn($returnmsg);

				}else{

					$this -> ajaxReturn($returnmsg);

				}

			}

		}

		

		

	}

	

	/**

	 * 修改头像列表

	 *

	 */

	

	public function saveAvatarList(){

		$media = $this->getMedia ( '设置头像', '', '', '设置头像', 'ismenu' );

		$this -> assign('media', $media);

		//$phototype = I('get.phototype',0,'intval');

		$model = M('UserPhoto');

		$where = array('uid' => $this->uinfo['id']); //审核通过     公开照片

		$count = $model -> where($where) -> count();

			

		$Page = new \Think\Page($count,15);

		

		$list = M("UserPhoto") -> where($where) ->  limit($Page->firstRow.','.$Page->listRows)->order('isavatr desc, photoid desc')->select();

	    

		$res =  M('Audit')->field('status,photoid')->where('uid = '.$this->uinfo['id'].' and type = 0')->order('created_time desc')->find();

		if($res && $res['status']!=1){

			$dshPhoto = $res['photoid'];

			foreach ($list as $k=>$v){

				if($v['isavatr']==1){

					$list[$k]['isavatr'] = 0;

				}

				if($res['status']==0 && $v['photoid']==$dshPhoto){

					$list[$k]['dshPhoto'] = 1;

				} 

				if($res['status']==2 && $v['photoid']==$dshPhoto){

					$list[$k]['dshPhoto'] = 2;

				}

			}

		}

	

		$this -> assign('list', $list);

		

		if($_GET['p']>=200)exit;

		if (I("get.ajax") == 1){

			if($list) $data = $this->sitefetch('ajax_AvatarList');

			$this->ajaxReturn($data);

		}

		

		$this -> siteDisplay ( 'user_avatarList' );

	}

	

	//设置头像

	public function saveAvatar(){

		$model = M('UserPhoto');

		if(!IS_POST){

			$id = I('get.photoid',0,'intval');

			$info =   $model->where('photoid = '.$id)->find();

			$url = $info['uploadfiles'];

			

			if(!$file_name = $this->GrabImage2($url,'','','','avatar_'.$this->uinfo['id'])){

				$this ->error('系统繁忙，请稍候再试！');

			}else{

				$info['uploadfiles'] = $file_name;				

			}   

			$this -> assign('info', $info);

			$this -> siteDisplay ( 'user_avatar' );

		}else{

			$uid = $this->uinfo['id'];

			$base64_image_content = I('post.ret');

			$id = I('post.id');

		    $this->qxAvatar();

			if (preg_match('/^(data:image\/(\w+);base64,)/', $base64_image_content, $result)){

				$type = $result[2];

                $filepath="/Uploads/avatar/";

				!is_dir(getcwd().$filepath)? mkdir(getcwd().$filepath):null;

				$file_name = __ROOT__ .$filepath.$uid."_".time().".{$type}";

				$new_file = ROOT_PATH.$filepath.$uid."_".time().".{$type}";

						

				if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))){

                    if(file_exists($new_file)){

                    	$image = new \Think\Image(); 

                    	$image->open($new_file)->text('','./hei.ttf',20,'#000000',\Think\Image::IMAGE_WATER_SOUTHEAST)->save($new_file);

                    	//$file_name = D('DuxCms/File')->oos_upimg($new_file,$new_file);

                    	$file_name = D('DuxCms/File')->oos_upimg($file_name,$file_name);

                        $this->setUserinfo('avatar',$file_name);

                    	if(C('avatar_flag')>0){

							$Auditmod = M('Audit');



							$AudiData = $Auditmod->field('id,status')->where('uid = '.$uid.' and type = 0')->find();

							$oid = $AudiData['id'];

							if($oid){

								$name ='save';

								$arr = array('id'=>$oid,'created_time'=>time(),'text'=>$file_name,'status'=>0,'photoid'=>$id);

							}else{

								$name ='add';

								$arr = array('uid'=>$uid,'created_time'=>time(),'text'=>$file_name,'type'=>0,'status'=>0,'photoid'=>$id);

							}

							$re =	$Auditmod->$name($arr);



							if($re){

								if($name =='add'||(isset($AudiData['status'])&& $AudiData['status'] >= 1)){

									$this->setSystemTj('avatarFlag',1);

								}

							}

                    	}else{

                    		$re = M('Users')->where('id='.$uid)->setField('avatar',$file_name);

                    		if($re){

                    			$model -> where('uid ='.$uid.' and isavatr = 1')->setField('isavatr',0);

                    			$model -> where('photoid ='.$id)->setField('isavatr',1);

                    		} 

                    	}

                    	if($re){

							$this->newbchange($uid,1);

                    		$this->success('设置成功',U('saveAvatarList'));

						}

                    }

				}

			}

			$this->error('设置失败');

		}

	}

	

	//上传头像

	

	public function UpAvatar(){

		$uid = $this->uinfo['id'];

		$image = I('post.image');

		$thumb_image = I('post.thumb_image');

		if(!$image||!$thumb_image) $this->error('请选择照片！');

		$imageArr[] =$image; $thumb_imageArr[] = $thumb_image;		

		if($lastId = D('Users')->upPhoto($uid,"我上传了新的头像照片~",0,$imageArr,$thumb_imageArr)){

			if(C('photo_flag')!=1){

				$this->tongji($uid, 'photonum',1);

			}else{

				$this->setSystemTj('photoFlag',1);

			} 



			$this->success($lastId);

		}else{

			$this->error('设置失败');

		}

	

	}

	

	

	

	//删除图片

	public function qxAvatar(){

	

		if(C('open_oss')>0&&C('OSS_ACCESS_ID')&&C('OSS_ACCESS_KEY')&&C('OSS_ENDPOINT') &&C('OSS_TEST_BUCKET') && C('OSS_URL')) {

			del_file(I('post.uploadfiles'));

		}

		

	}

	

	

	

/*

* 有防盗链的图片

* $url 图片地址

* $filename 图片保存地址

* return 返回下载的图片路径和名称,图片大小

* $fromurl 来源URL，填写来源图片网址可破解防盗链

*/

 Public  function GrabImage2($url,$fromurl,$i="",$filepath="",$filename="") {

 	if(!strstr($url,'http')) return $url;

 	if(C('open_oss')>0&&C('OSS_ACCESS_ID')&&C('OSS_ACCESS_KEY')&&C('OSS_ENDPOINT') &&C('OSS_TEST_BUCKET') && C('OSS_URL')) {

 	

 if($url=="") return false;



$ext=strrchr($url,".");

 if($filename=="") {



 if($ext!=".gif" && $ext!=".jpg" && $ext!=".jpeg" && $ext!=".png") return false;



 $filename=date("YmdHis");

 //$ext = str_replace('=','.',$ext);

 }

 if(!$filepath)

 $filepath="/Uploads/aliavatar/".date("Ymd")."/";

!is_dir(getcwd().$filepath)? mkdir(getcwd().$filepath):null;//生成文件夹

 

 $re = curlg($url,$fromurl);

 $size = file_put_contents(ROOT_PATH.$filepath.$filename.$ext,$re);//返回大小





 if($size) 

 return __ROOT__.$filepath.$filename.$ext;

 	}else{

 		return $url;

 	}

}

	









	

	

	

	

	

	

	/**

	 * 我的钱包

	 */

	

	public function MyMoney(){

		$media = $this->getMedia ( '我的钱包', '', '', '我的钱包', 'ismenu' );

		$this->assign ( 'media', $media );

		$money = M('Users')->where('id='.$this->uinfo['id'])->getField('money');

		$info = M('UserCount')->where('uid='.$this->uinfo['id'])->find();

		

		$this->assign ( 'money', $money );

		$this->assign ( 'info', $info );

		$this -> siteDisplay("user_money");

	}

	

	

	/**

	 * 收入明细

	 */

	

	public function MyMoneyLog(){

		$media = $this->getMedia ( '收入明细', '', '', '收入明细', 'ismenu' );

		$uid = $this -> uinfo['id'];

		$sex = $this -> uinfo['sex'];

		

		$type = I('get.type',0,'intval');

		

		//签到

		if($type == 1){

			$model = M("AccountMoneyLogQd");

			$where["_string"] = "uid = ".$uid;		

		}	

		//邀请

		if($type == 2){

			$model = M("AccountMoneyLog");

			$where["_string"] = "uid = ".$uid." and type = 2";	

		}

		//收礼

		if($type == 3){

			$model = M("AccountMoneyLog");

			$where["_string"] = "uid = ".$uid." and type = 3";	

		}

		//图片

		if($type == 4){

			$model = M("AccountMoneyLogPhoto");

			$where["_string"] = "uid = ".$uid;	

		}

		//聊天  2  女  收入 

		if($type == 5){

			$model = M("AccountMoneyLogLt");

			$where["_string"] = "uid = ".$uid." and type = 2";	

		}

		

		if($type == 6){

			$model = M("AccountMoneyLog");

			$where["_string"] = "uid = ".$uid;	

		}

		

		$count =  $model -> where($where) -> count();

		$Page = new \Think\Page($count,15);

		$show = $Page -> show();

		

		$list = $model -> where($where) -> field("money,time,desc") -> order('time desc') -> limit($Page->firstRow.','.$Page->listRows) -> select();

		

		$this -> assign('list',$list);

		

		if($_GET['p'] >= 200){ //超过200条   不再下拉加载

			exit;

		}

		

		if(I("get.ajax") == 1){ //下拉加载请求

			if($list){	

				$data = $this -> sitefetch('ajax_user_money_log');			

				$this -> ajaxReturn($data);

			}

		}

		

		$this -> assign('batman',999);

		$this -> assign('page',$show);

		$this -> assign('sex',$sex);		

		$this -> assign('type',$type);

		$this -> assign('media', $media);

		$this -> siteDisplay("user_moneylog");

	}



	

	/**

	 * 支出明细

	 */

	

	public function MyCostLog(){

		$media = $this->getMedia ( '支出明细', '', '', '支出明细', 'ismenu' );		

		$type = I('get.type',0,'intval');

		$uid = $this -> uinfo['id'];

		$sex = $this -> uinfo['sex'];

		//送礼

		if($type == 1){

			$model = M("AccountMoneyLog");

			$where["_string"] = "uid = ".$uid." and type = 1";

		}

		//聊天    1 男 支出    

		if($type == 5){

			$model = M("AccountMoneyLogLt");

			$where["_string"] = "uid = ".$uid." and type = 1";  

		}

		//查看私密照  1 男 支出

		if($type == 7){

			$model = M("AccountMoneyLogPhoto");

			$where["_string"] = "uid = ".$uid." and type = 7";  

		}

		

		//提现   type 101

		if($type == 9){

			$model = M("AccountMoneyLog");

			$where["_string"] = "uid = ".$uid." and type = 101";

		}

				

		$count =  $model -> where($where) -> count();

		

		$Page = new \Think\Page($count,15);

		$show = $Page -> show();

		

		$list = $model -> where($where) -> field("money,time,desc") -> order("time desc") -> limit($Page->firstRow.','.$Page->listRows) -> select();

		

		$this -> assign('list',$list);

		

		if($_GET['p'] >= 200){ //超过200条   不再下拉加载

			exit;

		}

		

		if(I("get.ajax") == 1){ //下拉加载请求

			if($list){	

				$data = $this -> sitefetch('ajax_user_money_log');			

				$this -> ajaxReturn($data);

			}

		}

		

		$this -> assign('batman',888);

		$this -> assign ( 'media', $media );

		$this -> assign('page',$show);

		$this -> assign('type',$type);

		$this -> assign('sex',$sex);

		$this -> siteDisplay("user_costlog");

	}



	/**

	 * VIP充值记录

	 */

	

	public function MyCreditLog(){

		$media = $this->getMedia ( 'VIP充值记录', '', '', 'VIP充值记录', 'ismenu' );

		$this->assign ( 'media', $media );

		$mod = M('ChongzhiLog');

	    $where = array('uid'=>$this->uinfo['id']);

		$count = $mod->where($where)->count();

		$Page = new \Think\Page($count, 10);//实例化分页类，传入总记录数

		

		$list = $mod->where($where)->order("time desc")->page($Page->firstRow.','.$Page->listRows)->select();

	    if($list){

	   

		$ConfigCreditData = M('ConfigCredit')->Cache(true,3600)->select();

		foreach ($ConfigCreditData as $v){

			$ConfigCredit[$v['id']] =$v; 

		}

	    $ConfigVipData = M('ConfigVip')->Cache(true,3600)->select();

	    foreach ($ConfigVipData as $v){

	    	$ConfigVip[$v['id']] =$v;

	    }

	 

		foreach ($list as $k =>$v){

			if($v['paytype']==1){

				if(isset($ConfigVip[$v['cid']])){

					$list[$k]['info'] = '购买VIP'.$ConfigVip[$v['cid']]['day'].'天';

				}

			}

			if($v['paytype']==2){

				if(isset($ConfigCredit[$v['cid']])){

					$list[$k]['info'] = '充值'.$ConfigCredit[$v['cid']]['money'].'元';

				}

			}

			$list[$k]['status'] = $v['status']==1?'成功':'失败';

		  }

	    }

	   

		

		//$show = $Page->show();//分页显示输出



		$this->assign ( 'type', $type );

		$this->assign ( 'list', $list );

	  if($_GET['p']>=200)exit;

		if (I("get.ajax") == 1){

			if($list) $data = $this->sitefetch('ajax_cz_log');

			 $this->ajaxReturn($data);

		}

		

		

		$this -> siteDisplay("user_creditlog");

	}

	

	

	/**

	 * 充值中心

	 */

	

	public function VipCenter(){

		$media = $this->getMedia ( '充值中心', '', '', '充值中心', 'ismenu' );

		$this->assign ( 'media', $media );

		$type = I('get.type',0,'intval');	

		$this->assign ( 'type', $type );

		

	    $info = M('Users')->field('avatar,user_nicename,money,user_rank,rank_time')->where('id = '.$this->uinfo['id'])->find();	    

	    if($info['rank_time']){

	    	$info['rank_time'] = ceil(($info['rank_time']-time())/(24*3600));

	    }

	    $name = array('ConfigVip','ConfigCredit');

	    $list = M($name[$type])->select();

	    if($type==0){

	    	foreach ($list as $v){

	    		$v['zk'] = (round($v['price']/$v['original'],2))*10 ;

	    		$nlist[$v['day']] =$v; 

	    	

	    	}

	    	$list = $nlist;

	    }

        

	    $this->assign('iswx',iswx()?1:'');

	    if($isvip = $this->isvip($info)){

	    	$info['user_rank'] = $isvip['user_rank'];

	    }else{

	    	$info['user_rank'] = 0;

	    }

	   // $this->assign('iswx',1);

	    $this->assign('list',$list);

	    $this->assign('type',$type);

	    $this->assign ( 'info', $info );

		$this -> siteDisplay("vipCenter");

	}

	

	

	//支付操作

	public  function dopay(){

		$type = I('request.type',0,'intval');	

		$cid = I('request.cid',0,'intval');

		$name = array(1=>'ConfigVip',2 =>'ConfigCredit');

		$text = array(1=>C('site_title').'购买vip',2 =>C('site_title').'平台充值');

		if(!$type||!$cid) 	$this->error('系统繁忙,请稍候再试！0');

		

		$payname = iswx()?'wxpay':'alipay';

		//$payname = 'wxpay';

		$openid = M('Users')->where('id = '.$this->uinfo['id'])->getField('weixin');

		if($payname=='wxpay'&&!$openid) $this->error('请关注公众号',C('newsubscribeurl'));

		

		$config_data =  M($name[$type])->where('id = '.$cid)->find();

		

		if($config_data){

			$fee = $type==1 ? $config_data['price']:$config_data['money'];

		    $datapay = array(

		    		'fee'=>$fee,

		    		'out_trade_no'=>C('MCHID').date("YmdHis").rand(1, 10000),

		    		'openid'=>$openid,

		    		'nonce_str'=>'',

		    		'time'=>time(),

		    		'uid'=>$this->uinfo['id'],

		    		'transaction_id'=>'',

		    		'status'=>0,

		    		'cid'=>$cid,

		    		'payfrom'=>$payname,

		    		'paytype'=>$type,

		    		'parent_id'=>$this->uinfo['parent_id']?$this->uinfo['parent_id']:0

		      );

			$re = M('ChongzhiLog')->add($datapay);

		    if($re){

		    

		    		$arr =  array('id'=>$re,'openid'=>$openid,'out_trade_no'=>$datapay['out_trade_no'],'subject'=>$text[$type],'fee'=>$fee);

		    

		    	$html_text = A('Pay')->$payname($arr);

		    	if($payname=='wxpay'){

					$this->assign('jspay',$html_text);

					$this->siteDisplay('user_wxpay');

					exit;

				}

		    	$html_text?$this->success($html_text):$this->error('系统繁忙,请稍候再试！2');

		    

		    }else{

		    	$this->error('系统繁忙,请稍候再试！1');

		    }

		}else{

			$this->error('系统繁忙,请稍候再试！3');

		}

		

		

	}

	

	

	public function wxpay(){

		

	}

	

	

	



	

	/**

	 * 提现

	 */



	public function tixian(){

		$info = M('Users')->field('user_status,weixin,money')->where(array('id'=>$this->uinfo['id']))->find();

		if(!$info) $this->error('登录超时，请退出重试!');

		$info['uid'] = $this->uinfo['id'];

		if($info['user_status'] == 0)	$this->error('您的账号异常，不能提现！');

		$info['tmoney'] = intval($info['money']/C('moneyBL'));

		

		if(IS_POST){

			$type = I('post.type',0,'intval');

			$money = I('post.money',0,'intval');

			if(!$type&&!$money) $this->error('系统繁忙，请稍候再试！');

			switch ($type){

				case 1:

					$info['weixin']?'':$this->error('请关注公众号'.C('gzhcode').',并在微信内提现！');

				break;		

				case 2:

					$zfb_account = I('post.zfb_account','','trim');

					$zfb_lxr = I('post.zfb_lxr','','trim');

					if(!$zfb_account||!$zfb_lxr) $this->error('请填写完整！');

					S('ZfbInfo_'.$info['uid'],array('zfb_account'=>$zfb_account,'zfb_lxr'=>$zfb_lxr),24*3600);

				break;

				case 3:

					$mob = I('post.mob','','trim');

					if(!$mob||!$this->isTel($mob)) $this->error('请输入正确手机号！');

					S('MobInfo_'.$info['uid'],$mob,24*3600);

				break;

					

			}

			

	        if($money>$info['tmoney']){

	        	$this->error('您的余额不足！');

	        }	        

	        if($money<C('tx_qt_money')){

	        	$this->error('提现金额必须大于起提金额'.C('tx_qt_money').'元');

	        }

	        $mod = M("Tixian");

			$res = $mod -> where("uid = ".$info['uid']) -> order('time desc') -> find();//查找最近的提现记录				

			if($res){

				if($res['status'] == 2){   

					$this->error('您还有一笔正在处理的提现申请哦~');

					

				}					

				if( date('Ymd',$res['time']) == date('Ymd',time()) ){

					$this->error("亲~，一天只能提现一次哦");

					

				}													

			}

			

			$bodyarr = array(1=>'微信提现',2=>'支付宝提现',3=>'话费充值');

			$body = $bodyarr[$type]."，提现金额".$money."元";

			$data =array('uid'=>$info['uid'],'weixin'=>$info['weixin'],'fee'=>$money,'body'=>$body,'time'=>time(),'type'=>$type,'status'=>2,'zfb_account'=>$zfb_account,'zfb_lxr'=>$zfb_lxr,'mob'=>$mob);

		    

			$mod->startTrans();		

			$result = $mod -> add($data);

			if($result){

				$mod->commit();

			    $re = $this->changemoney($info['uid'], (-1)*$money*C('moneyBL'),101,$body,"","",0,get_client_ip(),0,101);				

			    if($re>=0){

			    	$this->setSystemTj(array('txFlag'=>1,'txFee'=>$money));		    	

			    	if($type==1){

			    		if(C('tx_wx_money')==0||$money<C('tx_wx_money')){

			    			$msg = $this->new_tixian($result);

			    			if($msg==1){

			    				$this->setSystemTj(array('txFlag'=>-1,'txFee'=>(-1)*$money,'txTotalFee'=>$money,'txTotalFeeDay'=>$money));

			    				$mod->where('id = '.$result)->setField('body',$body.'，系统已自动发放！');

			    				$this->success('提现成功，请注意查收！');

			    			}else{

			    				$this->error($msg);

			    			}

			    		

			    		    exit;

			    		}

			    	}

			    		$this->success('提交成功，待审核！');

			   

			    	

			    }else{

			    	$mod ->where('id = '.$result)->delete();

			    	$this->error('系统繁忙，请稍候再试！');

			 

			    }

			    

			}else{

				$mod->rollback();

			}

			

		}else{

			$mod = M("Tixian");

			//S('ZfbInfo_'.$info['uid'],);

			$zfbInfo = $mod->cache('ZfbInfo_'.$info['uid'],24*3600)->field('zfb_account,zfb_lxr')->where('uid ='.$info['uid']." and type = 2")->order('id desc')->find();

			$MobInfo = $mod->cache('MobInfo_'.$info['uid'],24*3600)->field('mob')->where('uid ='.$info['uid']." and type = 1")->order('id desc')->find();

			

			$this->assign ( 'zfbInfo', $zfbInfo );

			$this->assign ( 'MobInfo', $MobInfo['mob'] );

			$media = $this->getMedia ( '提现申请', '', '', '提现申请', 'ismenu' );

			$this->assign ( 'media', $media );

			$this->assign ( 'info', $info );

			$this->siteDisplay ( 'user_tixian' );

		}

	

	}

	

	

    /**

	 *

	 * 收货地址

	 *  

	 * */

	 public function ShippingAddress(){

	 	if(IS_POST){

	 		$uid = I('post.uid',0,'intval');

			$data['linkman'] = I('post.linkman','','trim');

			$data['tel'] = I('post.tel','','trim');

			$data['province'] = I('post.province');

			$data['city'] = I('post.city');

			$data['region'] = I('post.region');

			$data['address'] = I('post.address','','trim');

			$model = M('UserAddress');

			$res = $model -> where('uid='.$uid) -> find();

			if(!$res){ //新增

				$data['uid'] = $uid;

				$data['created_time'] = time();

				$re = $model -> add($data);

			}else{  //编辑

				$data['province'] = I('post.province') ? I('post.province') : $res["province"];

				$data['city'] = I('post.city') ? I('post.city') : $res["city"];

				$data['region'] = I('post.region') ? I('post.region') : $res["region"];								

				$re = $model -> where('uid='.$uid) -> setField($data);

			}			

			if($re===false){

				$this -> ajaxReturn('系统忙，请稍后再试');				

			}else{

				$this -> ajaxReturn('保存成功');

			}	

	 	}else{

	 		$media = $this->getMedia ( '收货地址', '', '', '收货地址', 'ismenu' );

	 		$this->assign ( 'media', $media );

	 		$info = $this -> uinfo;//用户信息

	 		$uid = $info['id'];//用户id

	 		$list = M("UserAddress") -> where('uid='.$uid) -> find();

	 		$list['uid'] = $uid;

	 		$result = M("UserAddress") -> field('province,city,region') -> where('uid='.$uid) -> find();

	 		

	 		$province = $this->get_city(0);

	 		$city =  $this->get_city($result['province']);

	 		$region  =  $this->get_city($result['city']);

	 

	 		$this -> assign('province',$province);

	 		$this -> assign('city',$city);

	 		$this -> assign('region',$region);

	 		$this -> assign('list',$list);

	 		$this -> siteDisplay("user_address");

	 	}



	 	

	 }

	

	

	

	/**

	 * 密码修改

	 * 

	 * @author chen

	 * @since 2015-3-23

	 */

	public function password() {

		if (! IS_POST) {

			$uinfo = $this->uinfo;

			$media = $this->getMedia ( '密码修改', '', '', '用户中心', 'ispwd' );

			$this->assign ( 'media', $media );

			$this->siteDisplay ( 'password' );

		} else {

			$arr = I ( 'post.arr' );

			$data = array ();

			$opwd = trim ( $arr ["opwd"] );

			$npwd = trim ( $arr ["npwd"] );

			$npwd2 = trim ( $arr ["npwd2"] );

			if (empty ( $opwd ) || empty ( $npwd ) || empty ( $npwd2 ))

				$this->error ( "请输入密码!" );

			if ($arr ["npwd"] != $arr ["npwd2"])

				$this->error ( "新密码输入不一致!" );

			$uinfo = $this->uinfo;

			if ($uinfo) {

				$username = $uinfo ['user_login'];

				$re = M ( 'users' )->where ( "user_login='$username'" )->find ();

				if ($re ['user_pass'] != md5 ( $username . $arr ["opwd"] . C ( 'PWD_SALA' ) ))

					$this->error ( "密码错误!" );

				$data ['user_pass'] = md5 ( $re ["user_login"] . $arr ["npwd"] . C ( 'PWD_SALA' ) );

				M ( "User" )->where ( "id=" . $re ["id"] )->save ( $data );

				$this->success ( "修改成功" );

			}

		}

	}

	

	

	

	

	

	

	

	

	

	public function jifenlist(){

		$media = $this->getMedia ( '积分变动明细', '', '', '积分变动明细', 'ismenu' );

		$this->assign ( 'media', $media );

		$uinfo = $this->uinfo;

		$page = isset($_GET ['p']) ? intval($_GET ['p']) : 1;

		$pagesize =10;

		$User = M ( 'Account_log' );

		// 实例化User对象

		// 进行分页数据查询 注意page方法的参数的前面部分是当前的页数使用 $_GET[p]获取

		$list = $User->where ( 'uid='.$uinfo['id'].' and jifen<>0' )->order ( 'id desc' )->page ( $page . ','.$pagesize )->select ();



		$this->assign ( 'list', $list ); 

		// 赋值数据集

		$count = $User->where ( 'uid='.$uinfo['id'].' and jifen<>0' )->count();

		// 查询满足要求的总记录数

		$Page = new \Think\Page($count,$pagesize);

		// 实例化分页类 传入总记录数和每页显示的记录数

		$show = $Page->show();

		

		// 分页显示输出

		$this->assign('page',$show);// 赋值分页输出

			

			

		$this->siteDisplay ( 'jifenlist' );

		

	}

	

	public function moneylist(){

			$media = $this->getMedia ( '奖金变动明细', '', '', '奖金变动明细', 'ismenu' );

		$this->assign ( 'media', $media );

			$uinfo = $this->uinfo;

			$page = isset($_GET ['p']) ? intval($_GET ['p']) : 1;

			$pagesize =10;

			$User = M ( 'Account_log' );

			// 实例化User对象

			// 进行分页数据查询 注意page方法的参数的前面部分是当前的页数使用 $_GET[p]获取

			$list = $User->where ( 'uid='.$uinfo['id'].' and money<>0 and type not in(10,11,12)  ' )->order ( 'id desc' )->page ( $page . ','.$pagesize )->select ();

	

			$this->assign ( 'list', $list ); 

			// 赋值数据集

			$count = $User->where ( 'uid='.$uinfo['id'].' and money<>0 and type not in(10,11,12)' )->count();

			// 查询满足要求的总记录数

			$Page = new \Think\Page($count,$pagesize);

			// 实例化分页类 传入总记录数和每页显示的记录数

			$show = $Page->show();

			

			// 分页显示输出

			$this->assign('page',$show);// 赋值分页输出

			

			

		$this->siteDisplay ( 'jifenlist' );

		

	}

	

	



	

	public function tixianlist(){

			$media = $this->getMedia ( '提现列表', '', '', '提现列表明细', 'ismenu' );

		$this->assign ( 'media', $media );

			$uinfo = $this->uinfo;

			$page = isset($_GET ['p']) ? intval($_GET ['p']) : 1;

			$pagesize =10;

			$User = M ( 'moeny_log' );

			// 实例化User对象

			// 进行分页数据查询 注意page方法的参数的前面部分是当前的页数使用 $_GET[p]获取

			$list = $User->where ( 'uid='.$uinfo['id'].' and type=4' )->order ( 'id desc' )->page ( $page . ','.$pagesize )->select ();

	

			$this->assign ( 'list', $list ); 

			// 赋值数据集

			$count = $User->where ( 'uid='.$uinfo['id'].' and type=4' )->count();

			// 查询满足要求的总记录数

			$Page = new \Think\Page($count,$pagesize);

			// 实例化分页类 传入总记录数和每页显示的记录数

			$show = $Page->show();

			

			// 分页显示输出

			$this->assign('page',$show);// 赋值分页输出

			

			

		$this->siteDisplay ( 'tixianlist' );

	}

	

	

	public function ajaxqiandao(){

		echo A("Api")->qiandao('',$this->uinfo['id']);

	}

	

	public function add_pl(){

		

	    $content = I('post.content','','trim');

	    $content_id = I('post.content_id',0,'intval');

	    if(!$content||!$content_id) $this->error('请输入评论内容！');

	    

		$_POST['Fieldset_status'] = 1;

		$_POST['Fieldset_uid'] =$this->uinfo['id'];

		$_POST['Fieldset_content'] =$content;

		$_POST['Fieldset_zan'] = 0;

		$_POST['Fieldset_content_id'] =$content_id;

		$_POST['Fieldset_type'] = 1;

		$model = D('DuxCms/FieldData');

		$fieldset_id = $this->get_pl_fieldset_id();

		 

		 if(!$fieldset_id) $this->error('参数错误');

		 $_POST['fieldset_id'] = $fieldset_id;

		$this->formInfo = D('DuxCms/FieldsetForm')->getInfo($fieldset_id);

		$model->setTable($this->formInfo['table']);

		$arr['fieldset_id'] =$fieldset_id;

		if ($model->saveData('add',$_POST['fieldset_id'])){

			$this->success('提交成功,待审核！');

		}else{

			$msg = $model->getError();

			if (empty($msg))

			{

				$this->error('提交失败');

			}else{

				$this->error($msg);

			}

		}

		

		

	}

	

	

	//购买vip

	public function buy_vip(){

		$list = S('user_rank');

		if($list){

			$this->assign('list',$list);

			$this->siteDisplay ( 'buy_vip' );

		}else

			$this->error('无会员等级',U('index'));

	}

	

	

	public function wxtixian(){

		$id = $this->uinfo['id'];

		$weixin = M('Users')->where('id='.$id)->getField('weixin');

		$msg = A("Api")->tixian($weixin);

		if($msg) {

			A("Weixin")->makeTextbygm($msg,$weixin);

		}

		$this->ajaxReturn(array('msg'=>$msg));

	}

	

/**

* 登录绑定

* 

*/	

public function bind(){

	$re = M("User_y_reg")->where("code='".$this->uinfo['weixin']."'")->getField('data');	

	if($re) $data = unserialize($re);

	$this->assign('data',$data);

	$this->siteDisplay ( 'user_bind' );

}

	

public function deletebind(){

	$code = I('get.code','','trim');

	$new['weixin']="";

	$new['unionid']="";

	$re = M('Users')->where('weixin="'.$code.'"')->save($new);	

	if($re){

		 $this->setUserinfo('weixin','');

		 $this->success('删除成功！','',5);

		 }

	else

	$this->error('操作失败');

}	

	

	

	

}