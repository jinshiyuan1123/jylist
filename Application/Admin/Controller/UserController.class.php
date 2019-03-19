<?php

namespace Admin\Controller;

use Admin\Controller\AdminController;

/**

 * 栏目管理

 */

class UserController extends AdminController

{

	

	    public function __construct()

    {

    	parent::__construct();

        

    	/*

    	 * 用户详情头部设置

    	 */

        $header = array(

        	   array(

        			'action'=>"edit",

        			'action_name'=>"基本资料",

        	   		'url'=>U('edit',array('id'=>I('get.id','0','intval'))),        	   		

        	   ),

        	   array(

        			'action'=>"userProfile",

        			'action_name'=>"详细资料",

        	   		'url'=>U('userProfile',array('id'=>I('get.id','0','intval'))),

        		),

        		array(

        				'action'=>"userPhoto",

        				'action_name'=>"相册资料",

        				'url'=>U('userPhoto',array('id'=>I('get.id','0','intval'))),

        		),

        		array(

        				'action'=>"userQmd",

        				'action_name'=>"亲密度列表",

        				'url'=>U('userQmd',array('id'=>I('get.id','0','intval'))),

        		),

        		array(

        				'action'=>"userVip",

        				'action_name'=>"升级vip",

        				'url'=>U('userVip',array('id'=>I('get.id','0','intval'))),

        		),

        		array(

        				'action'=>"userWx",

        				'action_name'=>"微信红包",

        				'url'=>U('userWx',array('id'=>I('get.id','0','intval'))),

        		),array(

        				'action'=>"usercount",

        				'action_name'=>"用户统计",

        				'url'=>U('usercount',array('id'=>I('get.id','0','intval'))),

        		),

        		

        );

       $this->assign('action',ACTION_NAME);

       $this->assign('header',$header);

    }

    /**

     * 当前模块参数

     */

    public function _infoModule()

    {

        $data = array(

        	'info' => array(

        		'name' => '用户管理',

                'description' => '管理网站所有用户信息',

            ),

            'menu' => array(

                array(

                	'name' => '用户列表',

                    'url' => U('Admin/User/index'),

                    'icon' => 'list',

                ),

                array(

					'name' => '马甲列表',

					'url' => U('Admin/User/index',array('sort' => 1)),

					'icon' => 'list',			

				),    

				array(

					'name' => '添加用户',

                    'url' => U('Admin/User/add'),

                    'icon' => 'plus',

                ),
				
				array(

					'name' => '一键批量添加马甲',

                    'url' => U('Admin/User/addmjall'),

                    'icon' => 'plus',

                ),

                //$contentMenu

            )

        );

		/*	

        $modelList = getAllService('ContentModel', '');

        $contentMenu = array();

        if (!empty($modelList))

        {

            $i = 0;

            foreach ($modelList as $key => $value)

            {

                $i++;

                $data['menu'][$i]['name'] = '添加' . $value['name'] . '分类';

                $data['menu'][$i]['url'] = U($key . '/AdminBxcat/add');

                $data['menu'][$i]['icon'] = 'plus';

            }

        }

		*/

        return $data;

    }

    

   
/**

     * 一键批量添加马甲

     */

    public function addmjall(){
		$this->res=M('config')->where('name="name_arr"')->getField('data');
		$this->adminDisplay();
	}
	public function setmjall(){
		$mfile=trim($_POST['mfile']);
		if(empty($mfile))$this->error('请填写服务器相册文件夹');
		$data['sex']=(int)$_POST['msex'];
		$data['user_pass']=md5('jy3000');
		$data['parent_id']=0;
		$data['subscribe']=0;
		$data['jifen']=0;
		$data['money']=0;
		$data['provinceid']=0;
		$data['cityid']=0;
		$data['ismj']=1;
		$data['user_rank']=0;
		$data['tuiguang']=0;
		$data['type']=0;
		$data['rank_time']=0;
		$data['last_login_time']=0;
		$data['last_login_ip']='192.168.0.1';
		$data['regip']='192.168.0.1';
		$data['weixin']='0';
		$data['unionid']=0;
		$users=M('users');
		$upPhoto_title=M('config')->where('name="upPhoto_title"')->getField('data');
		$upPhoto_title=explode('；',$upPhoto_title);
		$nxdbsj=M('config')->where('name="nxdbsj"')->getField('data');
		$nxdbsj=explode('；',$nxdbsj);
		$user_photo=M('user_photo');
		$data_pro['lxfs_config']='a:3:{s:3:"mob";s:3:"hot";s:2:"qq";s:3:"hot";s:6:"weixin";s:3:"hot";}';
		$data_pro['weight']=0;
		$data_pro['height']=0;
		$data_pro['code1']=0;
		$data_pro['code2']=0;
		$data_pro['code3']=0;
		$data_pro['code4']=0;
		$name_arr=$_POST['name_arr'];
		$name_arr=explode('；',$name_arr);
		$user_profile=M('user_profile');
		$j=0;
		if (false != ($handle = opendir ($mfile.'/'))) {
				 while ( false !== ($file = readdir ( $handle ))){
					 if ($file != "." && $file != ".."){
						 if($name_arr[$j]){
							 $data['user_nicename']=$name_arr[$j];
							 }else{
							$data['user_nicename']='明媚的女子';	 
								 }
						$j++;
						//echo $file.'<br>';
						if (false != ($handle1 = opendir ($mfile.'/'.$file.'/'))) {
							$i=0;
							$addid=0;
							while ( false !== ($file1 = readdir ( $handle1 ))){
					 			if ($file1 != "." && $file1 != "..") {
									$mmtime=time();
									if($i==0){
										$data['avatar']='/'.$mfile.'/'.$file.'/'.'1.jpg';
										$data['user_login']=$mmtime-rand(1000,9999);
										$data['idmd5']=md5($mmtime.rand(100000,999999));
										$data['age']=rand(1985,2002);
										$data['create_time']=rand($mmtime-9999999,$mmtime);
										$addid=$users->add($data);
										if($addid){
											$data_pro['uid']=$addid;
											$data_pro['birthday']=$data['age'].'-'.rand(1,12).'-'.rand(1,28);
											$data_pro['monolog']=$nxdbsj[array_rand($nxdbsj)];
											$data_pro['astro']=rand(1,12);
											$user_profile->add($data_pro);
											}
										$i++;
										}else{
											if($addid>0){
												$data1['uid']=$addid;
												$data1['uploadfiles']='/'.$mfile.'/'.$file.'/'.$file1;
												$data1['thumbfiles']='/'.$mfile.'/'.$file.'/'.$file1;
												$data1['timeline']=rand($mmtime-9999999,$mmtime);
												$data1['title']=$upPhoto_title[array_rand($upPhoto_title)];
												$data1['flag']=1;
												$data1['idmd5']=md5($mmtime.rand(100000,999999));
												$user_photo->add($data1);
												}
											}	
								}
							}
							unset($file1);
							unset($handle1);
						}
					}
				 }
			 }
		$this->success('已经在后台执行');
	}
    /**

     * 用户列表

     */
    public function index(){
		$sort = I('get.sort','');  //区别会员和马甲
		$keyword = I('request.keyword','');

		$provinceid = I('request.provinceid','');

		$cityid = I('request.cityid','');

		$sex = I('request.sex','');

		$level = I('request.level','');

		$status = I('request.status',0,'intval');

		$subscribe=I('request.subscribe',0,'intval');

		$type = I('request.type','','trim');

		$order_by = I('request.order_by','asc','trim');

		$vip = I('request.vip',0,'intval');

		

		

        $breadCrumb = array('用户列表' => U());

        $this->assign('breadCrumb', $breadCrumb);

         //$this->assign('list', D('User')->loadData());

        

		$pageMaps = array();

		$pageMaps['sort'] = $sort;

		$pageMaps['cityid'] = $cityid;

		$pageMaps['provinceid'] = $provinceid;

        $pageMaps['keyword'] = $keyword;

        $pageMaps['status'] = $status;

        $pageMaps['subscribe'] = $subscribe;

        $pageMaps['type'] = $type;

        $pageMaps['order_by'] = $order_by;

		$pageMaps['level'] = $level;

		$pageMaps['sex'] = $sex;

		$pageMaps['vip'] = $vip;

		$where = array();

		

		if(empty($sort)){  //会员

			$where['ismj'] = 0;  //有带sort的是会员

			$this -> assign('name',"用户");

		}else{   //马甲

			$where['_string'] = 'ismj != 0';

			if($level != 0 ){

				$where['ismj'] = $level;

			}

			$this -> assign('name',"马甲");

		}

		

		if(!empty($provinceid)){  

			$where['provinceid'] = $provinceid;

		}

		

		if(!empty($cityid)){  

			$where['cityid'] = $cityid;

		}

		

		if(!empty($sex)){

			$where['sex'] = $sex;

		}

		

		if(!empty($vip)){

			$where['rank_time'] = $vip==1?array('egt',time()):array('lt',time());

		}

		

		

		if(!empty($keyword)){

			$where['_string'] = '(user_login like "%'.$keyword.'%") OR (id = "'.$keyword.'")';

        }

        $pid =I('get.pid');

        $pageMaps['pid'] = $pid;

        if(!empty($pid)){

        	$where['parent_id'] = $pid;

        }

		

		

		

		

		if(!empty($status)){

            switch ($status) {

                case '1':

                    $where['user_status'] = 1;

                    break;

                case '2':

                    $where['user_status'] = 2;

                    break;

				case '3':

                    $where['user_status'] = 0;

                    break;

            }

        }

       

        if(!empty($subscribe)){

         	

         	switch ($subscribe) {

                case '1':

                    $where['subscribe'] = 1;

                    break;

                case '2':

                    $where['subscribe'] = 0;

                    break;				

            }     

        }

        

        if($type){

        	$order =$type.' '.$order_by;

        }

      

        

       // $pageMaps['class_id'] = $classId;

       // $pageMaps['position_id'] = $positionId;

        //查询数据

		

        $count = D('Users')->countList($where);

        $limit = $this->getPageLimit($count,20);

		

	    $order = $order? $order: 'id desc';

        $list = D('Users')->loadList($where,$limit,$order);

	    $this->assign('pageMaps',$pageMaps);

	    //地区

	    $areaList = A('Home/Site')->get_area();

		

		foreach($areaList as $v){

			if($v['rootid']==0){

    			$province[] =$v;

    		}

		}

		

//		dump($cityid);

//	    exit;

//		

	    foreach($list as $key=>$val){

		    if($isvip = $this->isvip($val)){

		    	$list[$key]['vip']=	$isvip['user_rank'];

		    	$list[$key]['vipData']=	$isvip['rank_day'];

		    }

	    	$list[$key]['fscount']=	D("Users")->getfensicount($val['id']);

		    if($areaList){

		    	$list[$key]['province_name'] =$areaList[$val['provinceid']]['areaname'];

		    	$list[$key]['city_name'] =$areaList[$val['cityid']]['areaname'];

				

		    }

	    }

		

		//dump($list);

		$this->assign('province',$province);  //省

		$this -> assign('sort',$sort);

		$this->assign('page',$this->getPageShow($pageMaps));

		$this->assign('list',$list);

        $this->adminDisplay();

    }

	 /**

     * 增加

     */

    public function add(){

        if(!IS_POST){

            $breadCrumb = array('用户列表'=>U('index'),'用户添加'=>U());

            $this->assign('breadCrumb',$breadCrumb);

            $this->assign('name','添加');

            $this->adminDisplay('info');

        }else{			

            if(D('Users')->saveData('add')){

                $this->success('用户添加成功！');

            }else{

                $msg = D('Users')->getError();

                if(empty($msg)){

                    $this->error('用户添加失败');

                }else{

                    $this->error($msg);

                }

            }

        }

    }

    

    /**

     * 修改

     */

    public function edit(){

    	if(!IS_POST){

    		$breadCrumb = array('用户列表' => U('index'),'修改用户'=>U());

    		$this->assign('breadCrumb', $breadCrumb);

    		$userId = I('get.id','','intval');

    	    if(empty($userId)) $this->error('参数不能为空！');

    	    //获取记录

    		$info = M ( 'Users' )->where ( "id=$userId" )->find ();

    		if(!$info) $this->error('无数据！');

    	    

    		$areaList = A('Home/Site')->get_area();

    		$info['province_name'] =$areaList[$info['provinceid']]['areaname'];

    		$info['city_name'] =$areaList[$info['cityid']]['areaname'];

    		

    		foreach ($areaList as $v){

    			if($v['rootid']==0){

    				$province[] =$v;

    			}

    			if($v['rootid']==$info['provinceid']){

    				$city[] =$v;

    			}    			

    		}

    		$this->assign('province',$province);

    		$this->assign('city',$city);

    		//$this->assign('info',$info);

    		$this->assign('info',$info);

    		$this->assign('name','修改');

    		$this->adminDisplay('info');

    	}else{

    		if(D('Users')->saveData('edit')){

    			$this->success('修改成功！');

    		}else{

    			$msg = D('Users')->getError();

    			if(empty($msg)){

    				$this->error('修改失败');

    			}else{

    				$this->error($msg);

    			}

    		}

    	}

    }



	

	public function del(){

		$contentId = I('post.data',0,'intval');

        if(empty($contentId)){

            $this->error('参数不能为空！');

        }

        $mod = D('Users');

        $user =  $mod ->field('sex','ismj','rank_time')->where('id = '.$contentId)->find();

        

        if($mod->delData($contentId)){

        	if(!$user['ismj']){

          	$sexname = $user['sex']==1?'manUser':'girlUser';

          	$field[$sexname] = -1;

          	if($user['rank_time']>time()){

          		$vipname = $user['sex']==1?'manVip':'girlVip';

          		$field[$vipname] = -1;

          	}

          	 if($field) $this->setSystemTj($field);

        	}

        	 

			M("audit")->where("uid in('".$contentId."')")->delete();

			M("User_profile")->where("uid in('".$contentId."')")->delete();

			M("User_y_reg")->where("reguid in('".$contentId."')")->delete();

			M("User_count ")->where("uid in('".$contentId."')")->delete();			

			M("User_photo")->where("uid in('".$contentId."')")->delete();

			M("User_ticket")->where("uid in('".$contentId."')")->delete();			

			M("user_area")->where("uid in('".$contentId."')")->delete();			

			M("user_address")->where("uid in('".$contentId."')")->delete();			

			M("qiandao")->where("uid in('".$contentId."')")->delete();			

			M("account_money_log")->where("uid in('".$contentId."')")->delete();			

			M("account_jifen_log")->where("uid in('".$contentId."')")->delete();			

			M("account_money_log_lt")->where("uid in('".$contentId."')")->delete();			

			M("account_money_log_photo")->where("uid in('".$contentId."')")->delete();			

			M("account_money_log_qd")->where("uid in('".$contentId."')")->delete();			

			M("user_subscribe")->where("touid in('".$contentId."') or fromuid in('".$contentId."')")->delete();			

			M("user_qinmidu")->where("uid=".$contentId." or fromuid=".$contentId)->delete();			

			M("account_qmd_log")->where("uid=".$contentId." or fromuid=".$contentId)->delete();			

			M("giftlist")->where("touid in('".$contentId."') or fromuid in('".$contentId."')")->delete();			

			M("message")->where("touid in('".$contentId."') or fromuid in('".$contentId."')")->delete();			

			M("message_log")->where("touid in('".$contentId."') or fromuid in('".$contentId."')")->delete();			

            $this->success('用户删除成功！');

        }else{

            $this->error('用户删除失败！');

        }

	}



	//批量操作

	public function batchAction(){

		$ids  = I('post.ids',''); //接收所选中的要操作id	

		$type = I('post.type');//接收要操作的类型   如删除。。。

		

		if(empty($ids)||empty($type)){

			$this -> error('参数不能为空');

		}

		$ids = count($ids) > 1 ? implode(',', $ids) : $ids[0];	

		$mod = D('Users');

		//删除

		if($type == 1){					

			if( $mod-> delData($ids) ){

	

			$contentId = $ids;

			M("audit")->where("uid in('".$contentId."')")->delete();

			M("User_profile")->where("uid in('".$contentId."')")->delete();

			M("User_y_reg")->where("reguid in('".$contentId."')")->delete();

			M("User_count ")->where("uid in('".$contentId."')")->delete();			

			M("User_photo")->where("uid in('".$contentId."')")->delete();

			M("User_ticket")->where("uid in('".$contentId."')")->delete();			

			M("user_area")->where("uid in('".$contentId."')")->delete();			

			M("user_address")->where("uid in('".$contentId."')")->delete();			

			M("qiandao")->where("uid in('".$contentId."')")->delete();			

			M("account_money_log")->where("uid in('".$contentId."')")->delete();			

			M("account_jifen_log")->where("uid in('".$contentId."')")->delete();			

			M("account_money_log_lt")->where("uid in('".$contentId."')")->delete();			

			M("account_money_log_photo")->where("uid in('".$contentId."')")->delete();			

			M("account_money_log_qd")->where("uid in('".$contentId."')")->delete();			

			M("user_subscribe")->where("touid in('".$contentId."') or fromuid in('".$contentId."')")->delete();			

			M("user_qinmidu")->where("uid=".$contentId." or fromuid=".$contentId)->delete();			

			M("account_qmd_log")->where("uid=".$contentId." or fromuid=".$contentId)->delete();			

			M("giftlist")->where("touid in('".$contentId."') or fromuid in('".$contentId."')")->delete();			

			M("message")->where("touid in('".$contentId."') or fromuid in('".$contentId."')")->delete();			

			M("message_log")->where("touid in('".$contentId."') or fromuid in('".$contentId."')")->delete();



			/*

            $manVip = $mod->where('sex =1  and  ismj==0  and rank_time >"')->count();

			$girlVip = $mod->where('sex =2  and  ismj==0 ')->count();

			$field =array();

			$this->setSystemTj($field);

			*/

				$this->success('用户删除成功！');				

			}else{

				$this->error('用户删除失败！');		

			}				

		}

		//推荐

		if($type == 2){

			$data['type'] = 1;

			$res = M('Users') -> where('id in ('.$ids.')') -> save($data);

			if($res){

				$this->success('用户删除成功！');

			}else{

				$this->error('用户删除失败！');		

			}

		}

	}

	

	

	//单条推荐

	public function tuijian(){

		$id = I('post.id',0,'intval');	

		$model = M('Users');		

		$res = $model -> where('id = '.$id) -> field('type') -> find();

		//  推荐 --> 撤销推荐

		if($res['type'] == 1){  

			$re1 = $model -> where('id = '.$id) -> setField('type',0);

		}else{   //  撤销推荐 --> 推荐

			$re2 = $model -> where('id = '.$id) -> setField('type',1);

		}

		if($re1){

			$this -> ajaxReturn(1);

		}

						

		if($re2){

			$this -> ajaxReturn(2);

		}

	}

	

	

	

	

	

	protected function sendhb($openid='o1vOjjlUZML2Kqxwj1snkSTPKvpw',$title='奖励',$body='',$fee=1,$type=''){//发红包 http://lxphp.com

	$hb = new \Org\Util\Hongbao();		

			$arr['openid']=$openid;

        	$arr['hbname']=$title;

        	$arr['body']=$body;

        	$arr['fee']=$fee;        	

        	if($type=='lb'){

				$arr['num']=I("post.num");

				//dump($arr);

				$re = $hb->liebianhongbao($arr);	

			}else{

			$re = $hb->sendhongbaoto($arr);	

			}

			if($re['result_code']=='SUCCESS')

			$this->log_money1($openid,$fee,$body,-1);			

			return $re['return_msg'];

}

  

  

  public function sendhbs(){// http://lxphp.com

  		$openid = I("post.openid");

  		$title = I("post.hbtitle");

  		$body = I("post.hbbody");

  		$fee = I("post.fee");

  		$type = I("post.type");

  		

  		exit($this->sendhb($openid,$title,$body,$fee,$type));

  }

  

  public function sendzz(){

  	$Transfers = new \Org\Util\Transfers();	

	$re = $Transfers->dozz(I("post.openid"),I("post.fee"),I("post.desc"));

	if($re['result_code']=='SUCCESS'){ // 正确返回

	$this->log_money1(I("post.openid"),I("post.fee"),I("post.desc"),-1);

	 	exit("转账成功！");

	}else{//返回错误信息	 

	exit($re['return_msg']);

	} 

	

	

	

  }

  

  public function sendaccount(){

  	$mtype =I('post.mtype','','intval');

  	$moeny =I('post.moeny');

  	$uid = I('post.uid','','intval');

  	$type = I('post.type','','intval');

  	

  	if($mtype == 1){

  	   $jifen =	 $this->changejifen($moeny,$type,I("post.desc"),$uid);

  	   if($jifen>=0){

  	   	exit("操作成功！");

  	   }

  	}

  	if($mtype == 2){

  		$jifen =  $this->changemoney($uid,$moeny,$type,I("post.desc").C('money_name').'+'.$moeny,0,0,1,0,0,10);

  		if($jifen>=0){

  			exit("操作成功！");

  		}

  	}

  

  	

  	

  	

  	exit('操作失败');

  }

  

  

  

  

   public function log_money1($openid=0,$fee=0,$desc=0,$type=0){//记录现金记录  	

$data['time']=time();

  	$data['weixin']=$openid;

  	$data['uid']=M("Users")->where("weixin='{$openid}'")->getField('id');

  	$data['fee']=$fee;

  	$data['body']=$desc;

  	$data['type']=$type;

  	M("moeny_log")->add($data);

  }

  

  

  /**

   * 用户基本资料

   */

    public function  userProfile(){

    	$uid  = I('get.id');

    	if(!IS_POST){

    		$info = M('UserProfile')->where('uid = '.$uid)->find();

    		$info['id'] = $uid;

    		$Constellation = C('Constellation');

  

    		$SetProfile = C('SetProfile');

 

    		$this->assign('profile',$SetProfile);

    		$this->assign('Constellation',$Constellation);

    		$this->assign('info',$info);

    		$this->adminDisplay('userProfile');

    	}else{

    		$data = I('post.');

    		$re =   M('UserProfile')->save($data);

    		if($re){

    			  $this->success('修改成功！');

    		}else{

    			$this->success('修改失败！');

    		}

    	}



    }

  

    

    /**

     * 相册审核

     */

    public function  userPhotoFlag(){

 

		$keyword = I('request.keyword','');

		$flag = I('request.flag',''); 

		$elite = I('request.elite',''); //筛选推荐状态

		$phototype = I('request.phototype',''); //照片类型

		

    	$where =array();

		

		if(!empty($keyword)){

			$where['_string'] = 'uid = '.$keyword.' or photoid = '.$keyword;

		}

		$pageMaps['keyword'] = $keyword;

		$pageMaps['phototype'] = $phototype;

		$pageMaps['flag'] = $flag;

		$pageMaps['elite'] = $elite;

	

		if(!empty($flag)){

			if($flag == -1) $flag=0;

			$where['flag'] = $flag;

		}

		

		if(!empty($phototype)){

			if($phototype == -1) $phototype = 0;

			$where['phototype'] = $phototype;

		}

		

		if(!empty($elite)){

			if($elite == 2) $elite=0;

			$where['elite'] = $elite;

		}

		

    	$count = D('Users')->countPhotoList($where);

    	$limit = $this->getPageLimit($count,20);

    	 

    	$order = $order? $order: 'a.photoid desc';

    	$list = D('Users')->loadPhotoList($where,$limit,$order);

  		

		$this->assign('pageMaps',$pageMaps);

    	$this->assign('page',$this->getPageShow($pageMaps));

    	$this->assign('list',$list);

    	$this->assign('info',$info);

    	$this->getCount();

    	$this->adminDisplay('userPhotoFlag');

    }

    

    

    private function getCount(){

    	$iscount = I('get.iscount',0,'intval');

    	if(!$iscount) return false;

    	$where['flag'] = 0;

    	$count =  M('UserPhoto') -> where($where) -> count();

    	M('Systemtj')->where('id = '.M('Systemtj')->getField('id'))->setField('photoFlag',$count);

    }

    

    /**

     * 用户相册

     */

    public function  userPhoto(){

    	$uid  = I('get.id');

    	$info['id'] = $uid;

        

    	$where =array();

    	$order = '';

    	$where['a.uid'] = $uid;

    	$count = D('Users')->countPhotoList($where);

    	$limit = $this->getPageLimit($count,20);

    	

    	$order = $order? $order: 'a.photoid desc';

    	$list = D('Users')->loadPhotoList($where,$limit,$order);

    	

    	

    	$this->assign('page',$this->getPageShow($pageMaps));

    	$this->assign('list',$list);

    	$this->assign('info',$info); 

    	$this->adminDisplay('userPhoto');

    }

    

    /**

     * 删除相册

     */

    public  function delPhoto(){

    	$Id = I('post.data','');

    	if(empty($Id)){

    		$this->error('参数不能为空！');

    	}

    	$flag = M('UserPhoto')->where('photoid in('.$Id.')')->getField('flag',true);



    	if(D('Users')->delPhoto($Id)){

    		$num = 0;

            foreach ($flag as $v){

              if($v==0)

              	$num++;

            }

    		if($num) $this->setSystemTj('photoFlag',(-1)*$num);

    		$this->success('用户删除成功！');

    	}else{

    		

    		$this->error('用户删除失败！');

    	}

    	

    	

    }

    

    

    /**

     * 推荐或隐藏

     */

    public  function operatePhoto(){

    	$Id = I('get.id','');

    	$type = I('get.type','');

		$result = M('UserPhoto') -> where('photoid = '.$Id) -> field('uid') -> find();

		$uid = $result['uid'];

    	if(empty($Id)||empty($type)){

    		$this->error('参数不能为空！');

    	}

    	$typearr =array(

    			1=>1,

    			2=>2,

    			3=>1,

    			4=>0,

    	);

    	$typename =array(

    			1=>'flag',

    			2=>'flag',

    			3=>'elite',

    			4=>'elite',

    	);

    	

    	if(D('Users')->operatePhoto($Id,$typename[$type],$typearr[$type])){

    		if($typename[$type]=='flag'){

    			$this->setSystemTj('photoFlag',-1);

    			if($type==2){

    				$udata= M('UserPhoto')->field('uid,title')->where('photoid = '.$Id)->find();

    				A('Home/Site')->sendSysmsg($udata['uid'],'照片【'.$udata['title'].'】，审核失败！',14);

    			}

    		} 

    		$this->success('操作成功！');

    	}else{

    		$this->error('操作失败！');

    	}

    	 

    	 

    }

  

    

    /**

     * 批量处理

     */

    public function batchPhotoAction(){ 

    	$Ids = I('post.ids','');

    	$type = I('post.type');

    	

    	if(empty($Ids)||empty($type)){

    		$this->error('参数不能为空！');

    	}

        $n =  count($Ids);

    	$Ids = $n>1?implode(',', $Ids):$Ids[0];

    	

    	if($type==5){

    		$flag = M('UserPhoto')->where('photoid in('.$Ids.')')->getField('flag',true);

    		$re = D('Users')->delPhoto($Ids);

    		if($re){

    			$num = 0;

    			foreach ($flag as $v){

    				if($v==0)

    					$num++;

    			}

    			if($num) $this->setSystemTj('photoFlag',(-1)*$num);

    		}

    		

    	}else{

    		$typearr =array(

    				1=>1,

    				2=>2,

    				3=>1,

    				4=>0,

    		);

    		$typename =array(

    				1=>'flag',

    				2=>'flag',

    				3=>'elite',

    				4=>'elite',

    		);

    		$re = D('Users')->operatePhoto($Ids,$typename[$type],$typearr[$type]);

    	}

    	if($re){

    		if($typename[$type]=='flag'){

    			$this->setSystemTj('photoFlag',(-1)*$n);

    			if($type==2){

    				$udatalist= M('UserPhoto')->field('uid,title')->where('photoid in('.$Ids.') ')->select();

    				if($udatalist){

    					foreach ($udatalist as  $v){

    						A('Home/Site')->sendSysmsg($v['uid'],'照片【'.$v['title'].'】，审核失败！',14);

    					} 

    				}	

    			}

    		} 

    	  $this->success('操作成功！');

    	}else{

    		$this->error('操作失败！');

    	}

    	

    	 

    	 

    }

    

    

    

    

    

    

    /**

     * 添加用户相册

     */

    public function ajax_add_user_photo($id=0,$url='',$thumburl=''){

    	 if(empty($id)||empty($url)) $this->ajaxReturn(array('status'=>-1,'msg'=>'参数错误'));

    	 

    	 $re =  M('UserPhoto')->add(array('uid'=>$id,'title'=>'','uploadfiles'=>$url,'thumbfiles'=>$thumburl,'timeline'=>time(),'flag'=>1,'idmd5'=>md5($thumburl)));

    	

    	 if($re){

            $this->tongji($id, 'photonum', 1);

    	 	$this->ajaxReturn(array('status'=>1,'msg'=>'添加成功'));

    	 }else{

    	 	$this->ajaxReturn(array('status'=>-1,'msg'=>'添加失败'));

    	 }

    	 

    }

    

    

    /**

     * 升级VIP

     */

    public function  userVip(){

    	if(!IS_POST){

    		$uid  = I('get.id');

    		$info['id'] = $uid;

    		

    		$vipday = M('Users')->field('user_rank,rank_time')->where('id ='.$uid)->find();

    		$info['rankData'] = $this->isvip($vipday);



    		$this->assign('info',$info);    		

    		$this->adminDisplay('userVip');    		

    	}else{

    		$id  = I('post.id');

    		$vipDay = I('post.vipDay');

    		if($id&&$vipDay){

    			$rank_time = D('Users')->where('id = '.$id)->getField('rank_time');

    			if($rank_time <= time()){

    				$rank_time = time()+$vipDay*24*3600;

    			}else{

    				$rank_time = $rank_time+$vipDay*24*3600;

    			}



    			$re = D('Users')->where('id = '.$id)->save(array('user_rank'=>1,'rank_time'=>$rank_time));

    			if($re===false){

    				$this->error('操作失败');

    			}else{

    				$this->success('操作成功');

    			}

    		}else{

    			$this->error('参数错误');

    		}

    		

    		

    		

    		

    	}

    	

    	

    }

    

    /**

     * 微信红包

     */

    public function  userWx(){

    	$uid  = I('get.id');

    	$info = D('Users')->find($uid);

 

    	

    	

    	$this->assign('info',$info);

    	 

    	$this->adminDisplay('userWx');

    }

    

 

    /**

	 * 亲密度列表

	 * 

	 */

    public function userQmd(){

    	$uid = I('get.id',0,'intval');

		

		$where['_string'] = "fromuid = ".$uid. " or touid = ".$uid;

		$model = M("UserQinmidu");

		$count = $model -> query();

		$Page = new \Think\Page($count,25);

		$list = $model -> query("select id,qmd,fromuid as fuid from lx_user_qinmidu where touid = $uid union select id,qmd,touid as fuid from lx_user_qinmidu where fromuid = $uid");

		

		foreach($list as $k => $v){

			$fuids[] = $v['fuid'];

		}

		

		$fuids = join(',', $fuids);

		

		$res = D('Users') -> getNicename($fuids);

		

		$this -> assign('res',$res);

		$this -> assign('list',$list);		

		$this -> assign('name',"亲密度");

		$this -> adminDisplay("userQmd");

    }

	

	

    //亲密度管理   -->增加亲密度

   	public function editQmd(){ 		

		

		if(IS_POST){

			$ids = I('post.id',0,'intval');

			$data['qmd'] = I('post.qmd',0,'intval');

			$res = M("UserQinmidu") -> where('id ='.$ids) -> save($data);

			if($res){

				$this -> success('操作成功');

			}else{

				$this -> error('操作失败');

			}

		}

		$id = I('get.id',0,'intval');

		$qmd = I('get.qmd',0,'intval');

		$this -> assign('qmd',$qmd);

		$this -> assign('id',$id);

		$this -> adminDisplay("editQmd");

   	} 

  

  /**

  * 用户统计

  * 

*/

  public function usercount(){

  	$usermod = M("Users");

  	$czlog = M("Chongzhi_log");

	$uid = I("get.id",'','intval');

	$count['fensi']=$usermod->where("parent_id=".$uid)->count();

	$count['cz']=$czlog->where("status=1 and paytype=2 and parent_id=".$uid)->count();

	$count['czmoney']=$czlog->where("status=1 and paytype=2 and parent_id=".$uid)->sum('fee');

	$count['vip']=$czlog->where("status=1 and paytype=1 and parent_id=".$uid)->count();

	$count['vipmoney']=$czlog->where("status=1 and paytype=1 and parent_id=".$uid)->sum('fee');;

	$count['nopay']=$czlog->where("status=0 and parent_id=".$uid)->count();;

	$this -> assign('count',$count);

  	$this -> adminDisplay("usercount");

  }

  

	

}



