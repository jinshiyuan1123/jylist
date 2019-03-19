<?php 

	namespace Admin\Controller;

	use Admin\Controller\AdminController;

	

	/*

	 * 充值记录管理

	 * 

	 * */

	

class ComposeController extends  AdminController{

	

	protected function _infoModule(){

		$data = array(

			'info' => array(

				'name' =>'私信管理',

				'description' =>'管理所有用户的私信聊天',

			),

			'menu' => array(

				array(

					'name' => '私信提示设置',

					'url' => U('Admin/Compose/setting'),

					'icon' => 'list',

				),

				array(

					'name' => '会员私信列表',

					'url' => U('Admin/Compose/index',array('ishello'=>-1)),

					'icon' => 'list',

				),

				array(

					'name' => '马甲私信列表',

					'url' => U('Admin/Compose/mjlt',array('ishello'=>-1)),

					'icon' => 'list',

				),

				array(

						'name' => '快捷回复列表',

						'url' => U('Admin/Compose/Reply'),

						'icon' => 'list',

					),

				array(

						'name' => '添加快捷回复',

						'url' => U('Admin/Compose/addReply'),

						'icon' => 'plus',

					),
				array(

						'name' => '关键字回复',

						'url' => U('Admin/Compose/codereply'),

						'icon' => 'list',

					),

			),

		);

		return $data;

	}
	/**
	关键字回复设置
	*/
	public function codereply(){
		$code_reply=M('code_reply');
		$list=$code_reply->select();
		$this->res=$list;
		$this -> adminDisplay();
		}
	/**
	关键字回复添加
	*/
	public function addcodereply(){
		$code=I('post.mcode');
		$reply=I('post.mreply');
		$type=I('post.mtype');
		if(!empty($reply)){
			$data['code']=$code;
			$data['reply']=$reply;
			$data['type']=$type;
			$res=M('code_reply')->add($data);
			if($res){
				$this->success('添加成功');
				}else{
				$this->error('添加失败');	
					}
			}else{
				$this->error('回复内容不可缺少');
				}
		}
	public function delReplycode(){
		$id=I('post.delid');
		$res=M('code_reply')->where('id='.$id)->delete();
		if($res){
			$this->success('删除成功');
			}else{
			$this->error('删除失败');		
				}
		}
	//会员私信

	public function index(){

		

		$adminuid = $_SESSION['admin_user']['user_id'];	

		

		$this -> assign('name','会员');

		$isread = I("request.isread",''); // 是否阅读

		$ishello = I("request.ishello",''); // 是否招呼消息

		$keyword = I("request.keyword",'');

		

		$breadCrumb = array('会员私信管理' => U());//面包屑分类

		$pageMaps['keyword'] = $keyword;

		$pageMaps['order_by'] = 'fromuid desc';

		$pageMaps['isread'] = $isread;

		$pageMaps['ishello'] = $ishello;

		$where ="1=1";

		$where = $where." and A.mj=0";

		if(!empty($keyword)){

			$where = $where.' and ( A.fromuid = "'.$keyword.'" or A.touid = "'.$keyword.'" or A.kfname = "'.$keyword.'" )' ;

		}

		

		if(!empty($ishello)){

			if($ishello == -1){

				$where = $where.' and A.is_zh = 0';

			}else{

				$where = $where.' and A.is_zh = '.$ishello;

			}

		}

		

		if(!empty($isread)){

			if($isread == -1){

				$where = $where.' and A.isread = 0';

			}else{

				$where = $where.' and A.isread = '.$isread;

			}

		}

		$mmod = M();

		

		

		

		

		$count = $mmod -> table('__MESSAGE__ as A') -> where($where) -> count();

		//echo M()->_sql();

		

		$limit = $this -> getPageLimit($count,20);  //获取每页要显示的条数

		

		$list = $mmod ->  table('__MESSAGE__ as A') -> field('A.*') -> where($where)-> limit($limit) -> order('A.isread asc,A.msgid desc') -> select();

		

		$ids = array();

		foreach($list as $k => $v){

			$ids[] = $v['fromuid'];

			$ids[] = $v['touid'];

			if($v['isread']==0)

			$list[$k]['noread']=1;

		}

		$ids = array_unique($ids);

		$ids = join($ids, ',');		

		$result = D('Users') -> getNicename($ids);

		//dump($result);

		$this -> assign('pageMaps',$pageMaps);

		$this -> assign('userNicename',$result);

		$this -> assign('page',$this->getPageShow($pageMaps));

		$this -> assign('breadCrumb',$breadCrumb);

		$this -> assign('list',$list);

		$this -> adminDisplay();

		

	}

	

	

	public function setting(){

		 if(IS_POST){

            if(D('Config')->saveData()){

                $this->success('提示配置成功！');

            }else{

                $this->error('提示配置失败');

            }

			exit;

        }



		$breadCrumb = array('提示配置'=>U('Admin/Compose/setting'));

		$this->assign('breadCrumb',$breadCrumb);

		$this->assign('info',D('Config')->getInfo());

		$this->adminDisplay();

	}



	

	//马甲私信

	

	public function mjlt(){

		

		$adminuid = $_SESSION['admin_user']['user_id'];	

		

		$this -> assign('name','马甲');

		

		$keyword = I("request.keyword",'');

		$ishello = I("request.ishello",''); // 是否招呼消息

		$isread = I("request.isread",-1,'intval');// 是否阅读

		

		

		$breadCrumb = array('马甲私信管理' => U('mjlt',array('ishello'=>-1)));//面包屑分类

		$pageMaps['keyword'] = $keyword;

		$pageMaps['order_by'] = 'fromuid desc';

		$pageMaps['isread'] = $isread;

		$pageMaps['ishello'] = $ishello;

		$where ="1=1";

		$where = $where." and A.mj!=0";

		

		if(!empty($keyword)){

			$where = $where.' and ( A.fromuid = "'.$keyword.'" or A.touid = "'.$keyword.'" or A.kfname = "'.$keyword.'" )' ;

		}

		

		if(!empty($ishello)){

			if($ishello == -1){

				$where = $where.' and A.is_zh = 0';

			}else{

				$where = $where.' and A.is_zh = '.$ishello;

			}

		}

		

		if(!empty($isread)){

			if($isread == -1){

				$where = $where.' and A.isread = 0';

			}else{

				$where = $where.' and A.isread = '.$isread;

			}

		}

		

		$mmod = M('Message');

		$count = $mmod -> alias('A') -> where($where)  ->count();



		$limit = $this -> getPageLimit($count,20);  //获取每页要显示的条数

			

		$list = $mmod -> alias('A') ->  field('A.*,min(A.isread) as isread') -> where($where) ->group('A.hash') -> order('A.isread asc,A.msgid desc') -> limit($limit)-> select();

		

		foreach($list as $k => $v){

			$ids[] = $v['fromuid'];

			$ids[] = $v['touid'];

			if($v['isread']==0)

			$list[$k]['noread']=1;

		}

		

		$ids = array_unique($ids);

		$ids = join($ids, ',');	

			

		$result = D('Users') -> getNicename($ids);

		

		$this -> assign('pageMaps',$pageMaps);

		$this -> assign('userNicename',$result);

		$this -> assign('page',$this->getPageShow($pageMaps));

		$this -> assign('breadCrumb',$breadCrumb);

		$this -> assign('list',$list);

		$this -> adminDisplay('index');

		

	}

	

	

	

	

	

	

	

	

	

	

	

	

	

	

	

	

	

	

	

	

	

	public function wechat(){

		$msgid = I("get.msgid",'','intval');

			$message = M("Message");

			$msg = $message->where("msgid=".$msgid)->find();

			$adminname = M("Admin_user")->where("user_id=".$_SESSION['admin_user']['user_id'])->getField("username");

			$kfmsglog = M("Message_kf_log");

			$data["msgid"]=$msgid;			

			$data["adminname"]=$adminname;

			$data["msghash"] = $w['msghash']=$msg["hash"];

			$data["adminid"]=$w['adminid']=$_SESSION['admin_user']['user_id'];

			$data["createtime"]=time();

			$data["rpnum"]=0;			

			$kflogrow = $kfmsglog->where($w)->find();

			if($kflogrow){

				$this->assign('kflogarr',$kflogrow);

			}else{		

				    $re = $kfmsglog->add($data);

					$this->assign('kflogarr',$data);

			}

			

			

		$toismj = M('Users')->where("id=".$msg['touid'])->getField('ismj');			

		if($toismj==0){

			$tuid = $msg['touid'];

			$msg['touid']=$msg['fromuid'];	

			$msg['fromuid']=	$tuid;	

		}

		if($_GET['noread']==1){

			//更新已读

		$w1['hash']=$msg["hash"];

		$w1['touid']=$msg['touid'];

		$w1['isread']=0;

		$count = $message ->where($w1)->count();

		if($count>0){

			M("User_count")->where("uid=".$msg['touid'])->setDec("wdsxnum",$count);

			$w2['hash']=$msg["hash"];

			$w2['touid']=$msg['touid'];

			$message ->where($w2)->save(array("isread"=>1,'redtime'=>time(),'kfname'=>$adminname));

		}

			}

		    $replylist = $this->getReplylist();

		    $this->assign('replylist',$replylist);

		    

		    //var_dump();

		    $user_status = D('Users')->where('id = '.$msg['fromuid'])->getField('user_status');

		    if($user_status > time()){

		    	$jyday  =  ceil(($user_status-time())/86400);

		    	$this->assign('jyday',$jyday);

		    }

		  $lahei = M("User_lahei")->where("fromuid=".$msg['touid']." and touid=".$msg['fromuid'])->getField('status');

		   $this->assign('lahei',$lahei);

			//A("Home/Site")->siteDisplay( 'sixin_a' );

			if($msg)

			A("Home/Public")->kfwechat($msg['touid'],$msg['fromuid'] );

	}

	

	

	//获取回复列表

	public function getReplylist(){

		$replylist = M('KfReplyConfig')->cache('KfReplyConfig_'.$_SESSION['admin_user']['user_id'],24*3600)->where('type = 0 or uid = '.$_SESSION['admin_user']['user_id'])->select();

		if($replylist){

			foreach ($replylist as $v){

				$list['#'.$v['id']] = $v;

			}

		   return $list;

		}

		return '';

	}

	

	

	public function del(){

		$id = I('post.data',0,'intval');

		if(!$id){

			$this -> error('参数不能为空');

		}

		$where['msgid'] = $id;

		$message = M("Message");

		$re = $message -> where($where) -> find();

		if($re["isread"]==0)

		M("User_count")->where("uid=".$re["touid"])->setDec("wdsxnum",1);

				

		$result = $message -> where($where) -> delete();

		if($result){

			$this -> success("删除数据成功");

		}else{

			$this -> error("删除数据失败");

		}

		

	}

	

	

	//批量删除

	public function batchAction(){

		$ids  = I('post.ids',''); //接收所选中的要操作id		

		$type = I('post.type');//接收要操作的类型   如删除。。。

		if(empty($ids)||empty($type)){

			$this->error('参数不能为空！');

		}

		$ids = count($ids) ? implode(',', $ids) : $ids[0];

		

		$where['_string'] = 'msgid in('.$ids.')';

		$result = M('Message') -> where($where) -> delete();

//		dump($ids);

//		dump(M('Message')->_sql());

//		exit;

		if($result){

			$this -> success('数据删除成功！');

		}else{

			$this -> error('数据删除失败！');

		}

	}

	

	

	

	public function listsx(){

		$message=M("Message");

		$uid = I("post.touid",'','intval');

		$fromuid = I("post.fromuid",'','intval');

		if($uid<$fromuid)

		$data["hash"]=md5($uid.$fromuid);

		else

		$data["hash"]=md5($fromuid.$uid);

		$msglist = $message ->where($data)->limit(30)->order("msgid desc")->select();

		foreach($msglist as $key =>$val){

			$msglist[$key]['ktime']=date("m-d H:i",$val['sendtime']);

		}

		//$this->assign("list",$msglist);

		//if($msglist) $data2 = $this->sitefetch('ajax_message_by_kf');		

		$this -> ajaxReturn($msglist);

		dump($msglist);

		

	}

	

	

	protected function sitefetch($name='') {

	 C('TAGLIB_PRE_LOAD','Dux');

        C('TAGLIB_BEGIN','<!--{');

        C('TAGLIB_END','}-->');

        C('VIEW_PATH','./themes/');

	return $this->fetch(C('TPL_NAME').'/'.$name);

  

}

	

   //快捷回复设置

   public function Reply(){

   	$user =session('admin_user');

   	

    $list = $this->getReplylist();

    $this->assign('list',$list);

    $this -> adminDisplay('reply');

   

   }



   

   

   

   



   public function addReply(){

   	if(IS_POST){

   		$user =session('admin_user');

   		$uid =  $user['user_id'];

   		$type = I('post.type',0,'intval');

   		$content = I('post.content','','trim');

   		$kj_set = I('post.kj_set','','trim');

   		$q = I('post.q',0,'intval');

   		if(!$content) $this->error('参数错误');

 

   		$arr = array('uid'=>$uid,'type'=>$type,'content'=>$content,'kj_set'=>$kj_set,'time'=>time());

   		$name ='add';

   		if($id = I('post.id')){

   		  $name = 'save';

   		  if(!$kj_set) $kj_set = "#".$id; 	

   		  $arr = array('id'=>$id,'type'=>$type,'content'=>$content,'kj_set'=>$kj_set,'time'=>time());

   		}

   		

   		$re =  M('KfReplyConfig')->$name($arr);

   		if($re){

   			if($type==0&&$name =='add'){

   				$kj_set = '##'.$re;

   				M('KfReplyConfig')->where('id='.$re)->setField('kj_set','##'.$re);

   			}

   			if($type==1&&!$kj_set){

   				$id = $id?$id:$re;

   				$kj_set ='#'.$id;

   				M('KfReplyConfig')->where('id='.$id)->setField('kj_set','#'.$id);

   			}

   			

   			

   			S('KfReplyConfig_'.$uid,NULL);

   			if($q==1){

   				$this->success($kj_set);

   			}else{

   				$this->success('操作成功');

   			}

   			

   		}else{

   			$this->error('操作失败');

   		}

 

   	}else{

   		$id  = I('get.id',0,'intval');

   	    if($id){

   	    	$info = M('KfReplyConfig')->where('id = '.$id)->find();

   	    	$this->assign('info',$info);

   	    }

   	    $this -> adminDisplay('addreply');

   	}

   

   }

 

   public function delReply(){

   	$user =session('admin_user');

   	$uid =  $user['user_id'];

   	    $id  = I('post.data',0,'intval');

   	    $re= M('KfReplyConfig')->where('id = '.$id)->delete();

   		if($re){

   			S('KfReplyConfig_'.$uid,NULL);

   			$this->success('操作成功');

   		}else{

   			$this->error('操作失败');

   		}	

   }

   

   //马甲送礼

   public function gift(){

    $id = I('get.id');

   	$uid = I('get.uid');

   	if(!$uid||!$id) $this->error('参数错误！');

   	$user = M('Users')->where('id = '.$id)->find();

   	if($user){

   		$re =  A('Home/Public')->loginbyname($user,0);

   	}

    	redirect(U('Home/Gift/index/',array('uid'=>$uid)));

   }

   

   public function wechatcheck(){

   	$type =$_GET['type'];

	if($type==2)

	 $where = "mj=touid and is_zh=1 and isread=0";

	else

   $where = "mj=touid and is_zh=0 and isread=0";

   $msgid = 	M("Message")->where($where)->getField('msgid');

   if($msgid)

   	$url = U('Admin/Compose/wechat',array('msgid'=>$msgid,'adminid'=>$_SESSION['admin_user']['user_id'],'noread'=>1));

	else

	$this->error('没有了');

	redirect($url);

   }

   

   

   //拉黑

   public function setjy(){

   	$day = I('post.content',0,'intval');

   	$uid = I('post.uid',0,'intval');

    if($day){

        $time = $day*3600*24;        

        $user_status = D('Users')->where('id = '.$uid)->getField('user_status');

        if($user_status==0) $this->error('该用户已被禁');

        	if($user_status <= time()){

        		$user_status = time()+$day*24*3600;        		

        	}else{

        		$user_status = $user_status+$day*24*3600;

        	}        	

        $re = D('Users')->where('id = '.$uid)->save(array('user_status'=>$user_status));

        if($re===false){

        	$this->error('操作失败');

        }else{

        	$newDay  = ceil(($user_status-time())/86400);        	

        	A('Home/Site') ->sendSysmsg($uid,'您已被禁言'.$newDay.'天',16);        	

        	$this->success('(已禁言'.$newDay.'天)');

        }

  	

    }

   

   }

   

	

	

}

