<?php
namespace Agent\Controller;
use Think\Controller;

/**
 * 后台首页
 */
class AllController extends Controller{
	/**
	代理平台总管理
	*/
	public function allagent(){
		if(!session('aid'))$this->error('请登录');
		$aid=session('aid');
		$stime=I('post.stime');
		$etime=I('post.etime');
		$where_n='1=1';
		$where_w='1=1';
		if($stime&&$etime){
			$this->mdate=$stime.'至'.$etime;
			$stime=strtotime($stime);
			$etime=strtotime($etime);
			$where_n.=' and time>$stime and time<$etime';
			$where_w.=' and time>$stime';
			}else{
			$stime=strtotime(date('Y-m-d'));
			$this->mdate=date('Y-m-d');
			$etime=time();
			$where_n.=' and time>$stime';	
			$where_w.=' and time<$stime';
				}
		$users=M('users');
		$kl=M('agent_admin')->where('id='.$aid)->getField('kl');
		$chongzhi=M('chongzhi_log');
		$all=$users->where('puser_flag='.$aid)->select();
		$money_n=0;
		$money_w=0;
		$uids='';
		foreach($all as $k=>$v){
			$uid=$v['id'];
			$where_n2=$where_n.' and parent_id='.$uid.' and status=1';
			$fee_n=$chongzhi->where($where_n2)->sum('fee');
			$money_n+=$fee_n;
			$where_w2=$where_w.' and parent_id='.$uid.' and status=1';
			$fee_w=$chongzhi->where($where_w2)->sum('fee');
			$money_w+=$fee_w;
			if(empty($uids)){
				$uids=$uid;
				}else{
				$uids.=','.$uid;	
					}
			}
		$money_w=$kl*$money_w/100;
		$money_n=$kl*$money_n/100;
		$sql='select count(*) as mc from lx_users where parent_id in('.$uids.') and create_time>'.$stime.' and create_time<'.$etime;
		$res=M()->query($sql);
		$this->mc=$res[0]['mc'];
		$this->money_w=$money_w;
		$this->money_n=$money_n;
		$this->money_a=$money_n+$money_w;
		$this->mcode=cookie('mcode');
		$this->display('mhome');
		}
	public function accadmin(){
		if(!session('aid'))$this->error('请登录');
		$aid=session('aid');
		$users=M('users');
		$all=$users->where('puser_flag='.$aid)->select();
		$this->res=$all;
		$this->mcode=cookie('mcode');
		$this->display();
		}
	public function index(){
		if(!$_POST){
			$this->display();
			}else{
			$acc=I('post.username');	
			$aid=M('agent_admin')->where('flag="'.$acc.'"')->getField('id');
			if($aid){
				session('aid',$aid);
				cookie('mcode',$acc);
				header('Location:index.php?s=/Agent/All/allagent.html');
				}else{
				$this->error('帐号不存在');
					}
				}
		}
	public function danlook(){
		$mid=I('get.mid');
		$model = D('AgentUser');
            if($model->setLogin($mid)){
                $this->success('登录成功！', U('Tuig/index'));
            }else{
                $this->error($model->getError());
            }
		}
	public function dandel(){
		$mid=I('get.mid');
		$flag=cookie('mcode');
		$pid=M('agent_admin')->where('flag="'.$flag.'"')->getField('id');
		$res=M('users')->where('id='.$mid.' and puser_flag='.$pid)->delete();
		if($res){
			$this->success('删除成功');
			}else{
			$this->error('删除失败');
				}
		}
	//添加代理处理
	public function setdaili(){
		if(!session('aid'))$this->error('请登录');
		$aid=session('aid');
		$dailicode=cookie('mcode');
		$users=M('users');
		$isfind=$users->order('id desc')->find();
		$acc=$dailicode.($isfind['id']+1);
		unset($isfind['id']);
		$pwd='1234';
		$isfind['user_login']=$acc;
		$isfind['user_pass']=md5($acc.$pwd.C('PWD_SALA'));
		$isfind['user_nicename']='代理_'.$acc;
		$isfind['weixin']=$acc;
		$isfind['avatar']='/themes/lxphp_dating/images/daili.jpg';
		$isfind['parent_id']=0;
		$isfind['ismj']=0;
		$isfind['puser_flag']=$aid;
		$isfind['tuiguang']=1;
		$isfind['idmd5']=md5($acc);
		$res=$users->add($isfind);
			if($res){
				$this->success('添加成功');
				}else{
				$this->error('添加失败');
		}
		}
}