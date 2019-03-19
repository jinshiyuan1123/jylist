<?php

namespace Admin\Controller;

use Admin\Controller\AdminController;

/**

 * 栏目管理

 */

class TuigController extends AdminController

{

	

    /**

     * 当前模块参数

     */

    public function _infoModule()

    {

        $data = array(

        	'info' => array(

        		'name' => '系统联盟',

                'description' => '管理网站推广信息',

            ),

            'menu' => array(

				array(

                	'name' => '提成设置',

                    'url' => U('tichensetting'),

                    'icon' => 'list',

                ),

                array(

                	'name' => '审核代理',

                    'url' => U('index'),

                    'icon' => 'list',

                ),

                array(

					'name' => '查看代理',

					'url' => U('ulist'),

					'icon' => 'list',		

				),    

				array(

					'name' => '添加代理',

					'url' => U('adddaili'),

					'icon' => 'list',		

				),

             

            )

        );

		

        return $data;

    }

    

	

    

    /**

     * 审核代理

     */

    public function index(){

		$keyword = I('post.keyword','','trim');

		$statusNum = I('post.status',0,'intval');

		$acceptId = I('post.acceptId',0,'intval');

		$rejectId = I('post.rejectId',0,'intval');



		if($acceptId){

			$tgUnion = M('TgUnion');

			$tgInfo = $tgUnion->where("id = $acceptId")->find();

			$uid = intval($tgInfo['uid']);

			M("Users")->where("id = $uid")->setField('tuiguang',1);

			$tgUnion->where("id = $acceptId")->setField('status',1);

			$this -> ajaxReturn(1);

			exit;

		}else if($rejectId){

			$tgUnion = M('TgUnion');

			$tgInfo = $tgUnion->where("id = $rejectId")->find();

			if(1 == $tgInfo['status']){

				$uid = intval($tgInfo['uid']);

				M("Users")->where("id = $uid")->setField('tuiguang',0);

			}

			$tgUnion->where("id = $rejectId")->setField('status',2);

			$this -> ajaxReturn(1);

			exit;

		}



		$pageMaps = array();

        $pageMaps['keyword'] = $keyword;

        $pageMaps['status'] = $status;



		$where = array("status" => $statusNum);

		if(!empty($keyword)){

			$where['_string'] = '(realname like "%'.$keyword.'%") or (id = "'.$keyword.'")';

		}

		$count = M('TgUnion')->where($where) -> count();

		$limit = $this->getPageLimit($count,20);

		$list = M("TgUnion")->where($where)->limit($limit)->order('id desc')->select();



		$this->assign('status',$statusNum);

		$this->assign('list',$list);

		$this->assign('pageMaps',$pageMaps);

		$this->assign('page',$this->getPageShow($pageMaps));

        $this->adminDisplay();

    }



	public function tichensetting(){

		$mbl = floatval(C('moneyBL'));



		if(IS_POST){

			$_POST['gz_money'] = $this->commaBLHandle($_POST['gz_money']);

			#$_POST['gz_money_vip'] = $this->commaBLHandle($_POST['gz_money_vip']);



            if(D('Config')->saveData()){

                $this->success('提成配置成功！');

            }else{

                $this->error('提成配置失败');

            }

			exit;

        }



		$info = D('Config')->getInfo();

		if($mbl > 0){

			$info['gz_money'] = $this->commaBLHandle($info['gz_money'] , 2);

			#$info['gz_money_vip'] = $this->commaBLHandle($info['gz_money_vip'] , 2);

		}

		

		$breadCrumb = array('提成配置'=>U('Admin/Tuig/tichensetting'));

		$this->assign('breadCrumb',$breadCrumb);

		$this->assign('info',$info);

		$this->adminDisplay();

	}

	//添加代理列表
	public function adddaili(){
		$this->adminDisplay();
		}
	//添加代理处理
	public function setdaili(){
		$id=(int)$_POST['dailiid'];
		$users=M('users');
		$isfind=$users->where('id='.$id)->find();
		if($isfind){
			$tg_union=M('tg_union');
			$data['uid']=$id;
			$data['realname']=rand(10000,999999);
			$data['tel']=$isfind['user_login'];
			$data['intro']='系统添加代理';
			$data['time']=time();
			$data['status']=1;
			$res=$tg_union->add($data);
			if($res){
				$acc='jy00'.$id;
				$pwd=$acc.rand(1000,9999);
				$data1['user_login']=$acc;
				$data1['user_pass']= md5($acc.$pwd.C('PWD_SALA'));
				$data1['tuiguang']=1;
				$users->where('id='.$id)->save($data1);
				$mhtml='代理帐号：'.$acc.'<br>';
				$mhtml.='代理密码：'.$pwd.'<br>';
				echo $mhtml;
				}else{
				$this->error('添加失败');	
					}
			}else{
			$this->error('没有该ID的账户');	
			}
		}
	//查看代理列表

	public function ulist(){

		$where = array("tuiguang" => 1);

		$keyword = I('post.keyword','','trim');

		$pageMaps['keyword'] = $keyword;



		if(!empty($keyword)){

			$where['_string'] = '(user_login like "%'.$keyword.'%") or (id = "'.$keyword.'")';

		}

		

		$count = M('Users')->where($where) -> count();

		$limit = $this->getPageLimit($count,20);

		$list = M('Users')->field("id,user_login,avatar,weixin,parent_id,tuiguang,last_login_time")->where($where)->limit($limit)->order('id desc')->select();



		$this->assign('list',$list);

		$this->assign('pageMaps',$pageMaps);

		$this->assign('page',$this->getPageShow($pageMaps));

        $this->adminDisplay();

	}

	

    /**

     * 代理推广记录

     */

    public function tglist(){

		// Admin/Tuig/tglist

   

    	$adminuid = I('get.uid','0','intval');

		$agentUid = I('post.agentUid','0','intval');

		if(empty($agentUid)){

			$agentUid = $adminuid;

		}else{

			$adminuid = $agentUid;

		}

		$sqlWhere = array('id' => $agentUid);

		$agentInfo = M('Users')->field("id,user_login,avatar")->where($sqlWhere)->find();

		

    	$oldStime = I('post.stime','','trim');

    	$oldEtime = I('post.etime','','trim');

		$stime = strtotime($oldEtime);

    	$etime = strtotime($oldEtime);

    	$UserYReg = M('UserYReg');

    	$chongzhi = M('ChongzhiLog');   

    	$modarr = array(

    			$UserYReg,

    			$UserYReg,

    			M('Users'),

    			$chongzhi,

    			$chongzhi

    	);

    	

    	$wherearr = array(

    		 array('puid'=>$adminuid,'subscribe'=>0,'time_name'=>'time'),

    		 array('puid'=>$adminuid,'subscribe'=>-1,'time_name'=>'time'),	

    		 array('parent_id'=>$adminuid,'time_name'=>'create_time'),

    		 array('parent_id'=>$adminuid,'status'=>1,'paytype'=>1,'time_name'=>'time'),

    		 array('parent_id'=>$adminuid,'status'=>1,'paytype'=>2,'time_name'=>'time'),

    	);

    	$tgkl_ids = C('tgkl_ids');

    	$tgkl_config = C('tgkl_config');

    	foreach ($modarr as $k=>$v){

    	   $where = $wherearr[$k];

    	   $time_name = $wherearr[$k]['time_name'];

    	   unset($where['time_name']);

			if($stime){

				$where[$time_name]  = array('gt',$stime);

			}

			if($etime){

				$where[$time_name]  = array('lt',$etime);

			}    		

    		$count[$k] = $v->where($where)->count();

    		

    		if(isset($where['paytype'])){

    			$psum[$k] = $v->where($where)->sum('fee');

    		}

    		

    		if(in_array($adminuid, $tgkl_ids)&&isset($tgkl_config[$k])){

    			if(isset($count[$k])&&$count[$k]) $count[$k] = $count[$k] - ceil($count[$k]*($tgkl_config[$k]/100));

    			if(isset($psum[$k])&&$psum[$k]) $psum[$k] = $psum[$k] - ceil($psum[$k]*($tgkl_config[$k]/100));

    		}

    	}

  

    	$this->assign('count',$count);

		$this->assign('agentUid',$agentUid);

    	$this->assign('psum',$psum);

		$this->assign('stime',$oldStime);

		$this->assign('etime',$oldEtime);

		$this->assign('agentInfo',$agentInfo);

    

    	$this->adminDisplay('info');

    }



	private function commaBLHandle($gzMoney , $isEncode=1){

		$mbl = floatval(C('moneyBL'));



		$gzMoney = str_replace("，",",",$gzMoney);

		if(strpos($gzMoney , ',')){

			$gzMoneyList = explode(",",$gzMoney);

			foreach($gzMoneyList as $index => $tmpMoney){

				if(1 == $isEncode){

					$gzMoneyList[$index]=floor($tmpMoney * $mbl);

				}else{

					$gzMoneyList[$index]= $tmpMoney / $mbl;

				}

			}



			$gzMoney = implode(",",$gzMoneyList);

		}else{

			if(1 == $isEncode){

				$gzMoney = floor($gzMoney * $mbl);

			}else{

				$gzMoney = $gzMoney / $mbl;

			}

		}



		return $gzMoney;

	}

    

	

}



