<?php
namespace Admin\Controller;
use Admin\Controller\AdminController;
/**
 * 网站设置
 */

class SettingController extends AdminController {

    /**
     * 当前模块参数
     */
    protected function _infoModule(){
        return array(
            'info'  => array(
                'name' => '网站设置',
                'description' => '设置网站整体功能',
                ),
/*            'menu' => array(
                    array(
                        'name' => '站点信息',
                        'url' => U('Setting/site'),
                        'icon' => 'exclamation-circle',
                    ),                  
                    array(
                        'name' => '手机设置',
                        'url' => U('Setting/mobile'),
                        'icon' => 'mobile',
                    ),
                    array(
                        'name' => '模板设置',
                        'url' => U('Setting/tpl'),
                        'icon' => 'eye',
                    ),
                    array(
                        'name' => '上传设置',
                        'url' => U('Setting/upload'),
                        'icon' => 'upload',
                    ),
                    array(
                        'name' => '性能设置',
                        'url' => U('Setting/performance'),
                        'icon' => 'dashboard',
                    ),
                    array(
                        'name' => '安全设置',
                        'url' => U('Setting/shield'),
                        'icon' => 'shield',
                    )
                )*/
        );
    }
	/**
     * 站点设置
     */
    public function site(){
        if(!IS_POST){
		
            $breadCrumb = array('站点信息'=>U());
            $this->assign('breadCrumb',$breadCrumb);
            $this->assign('info',D('Config')->getInfo());
            $this->adminDisplay();
        }else{
            
            if(D('Config')->saveData()){
                $this->success('站点配置成功！');
            }else{
                $this->error('站点配置失败');
            }
        }
    }
	
	  public function mj(){
        if(!IS_POST){
		
            $breadCrumb = array('站点信息'=>U());
            $this->assign('breadCrumb',$breadCrumb);
            $this->assign('info',D('Config')->getInfo());
            $this->adminDisplay();
        }else{
            
            if(D('Config')->saveData()){
                $this->success('站点配置成功！');
            }else{
                $this->error('站点配置失败');
            }
        }
    }
    
    public function yuny(){
        if(!IS_POST){
            $breadCrumb = array('站点信息'=>U());
            $this->assign('breadCrumb',$breadCrumb);
            $this->assign('info',D('Config')->getInfo());
            $this->adminDisplay();
        }else{
            
            if(D('Config')->saveData()){
                $this->success('站点配置成功！');
            }else{
                $this->error('站点配置失败');
            }
        }
    }
	public function tequan(){
        if(!IS_POST){
            $breadCrumb = array('站点信息'=>U());
            $this->assign('breadCrumb',$breadCrumb);
            $this->assign('info',D('Config')->getInfo());
            $this->adminDisplay();
        }else{
            
            if(D('Config')->saveData()){
                $this->success('站点配置成功！');
            }else{
                $this->error('站点配置失败');
            }
        }
    }
	
	   public function gzh(){
        if(!IS_POST){
            $breadCrumb = array('站点信息'=>U());
            $this->assign('breadCrumb',$breadCrumb);
            $this->assign('info',D('Config')->getInfo());
            $this->adminDisplay();
        }else{
            
            if(D('Config')->saveData()){
                $this->success('站点配置成功！');
            }else{
                $this->error('站点配置失败');
            }
        }
    }
	
       public function msetting(){
        if(!IS_POST){
            $breadCrumb = array('站点信息'=>U());
            $this->assign('breadCrumb',$breadCrumb);
            $this->assign('info',D('Config')->getInfo());
            $this->adminDisplay();
        }else{
            
            if(D('Config')->saveData()){
                $this->success('站点配置成功！');
            }else{
                $this->error('站点配置失败');
            }
        }
    }
    
    /**
     * 手机设置
     */
    public function mobile(){
        if(!IS_POST){
            $breadCrumb = array('模板设置'=>U());
            $this->assign('breadCrumb',$breadCrumb);
            $this->assign('themesList',D('Config')->themesList());
            $this->assign('tplList',D('Config')->tplList());
            $this->assign('info',D('Config')->getInfo());
            $this->adminDisplay();
        }else{
            if(D('Config')->saveData()){
                $this->success('模板配置成功！');
            }else{
                $this->error('模板配置失败');
            }
        }
    }
    /**
     * 模板设置
     */
    public function tpl(){
        if(!IS_POST){
            $breadCrumb = array('模板设置'=>U());
            $this->assign('breadCrumb',$breadCrumb);
            $this->assign('themesList',D('Config')->themesList());
            $this->assign('tplList',D('Config')->tplList());
            $this->assign('info',D('Config')->getInfo());
            $this->adminDisplay();
        }else{
            if(D('Config')->saveData()){
                $this->success('模板配置成功！');
            }else{
                $this->error('模板配置失败');
            }
        }
    }
    /**
     * 上传设置
     */
    public function upload(){
        if(!IS_POST){
            $breadCrumb = array('站点信息'=>U());
			var_dump($breadCrumb);
            $this->assign('breadCrumb',$breadCrumb);
            $this->assign('info',D('Config')->getInfo());
            $this->adminDisplay();
        }else{
            if(D('Config')->saveData()){
                $this->success('上传配置成功！');
            }else{
                $this->error('上传配置失败');
            }
        }
    }
    /**
     * 性能设置
     */
    public function performance(){
        $file = APP_PATH . 'Common/Conf/performance.php';
        if(!IS_POST){
            $breadCrumb = array('性能设置'=>U());
            $this->assign('breadCrumb',$breadCrumb);
            $this->assign('info',load_config($file));
            $this->adminDisplay();
        }else{
            if(write_config($file, $_POST)){
                $this->success('性能配置成功！');
            }else{
                $this->error('性能配置失败');
            }
        }
    }
    /**
     * 安全设置
     */
    public function shield(){
        $file = APP_PATH . 'Common/Conf/shield.php';
        if(!IS_POST){
            $breadCrumb = array('安全设置'=>U());
            $this->assign('breadCrumb',$breadCrumb);
            $this->assign('info',load_config($file));
            $this->adminDisplay();
        }else{
            if(write_config($file, $_POST)){
                $this->success('安全配置成功！');
            }else{
                $this->error('安全配置失败');
            }
        }
    }
    /**
     * 支付设置
     */
  public function zhifu(){
        if(!IS_POST){
            $breadCrumb = array('站点信息'=>U());
            $this->assign('breadCrumb',$breadCrumb);
            $this->assign('info',D('Config')->getInfo());
            $this->adminDisplay('pay');
        }else{
            
            if(D('Config')->saveData()){
                $this->success('站点配置成功！');
            }else{
                $this->error('站点配置失败');
            }
        }
    }
    
    /**
     * 充值vip设置
     */
    public function credit(){
        $type = I('get.type',0,'intval');
        $name = array('ConfigCredit','ConfigVip');
    	$this->assign('list',M($name[$type])->select());
    	$this->assign('type',$type);
    	$this->adminDisplay();
    }    
    public function saveCredit(){
    	$type = I('request.type',0,'intval');
    	$mod = array('ConfigCredit','ConfigVip');
    	if(!IS_POST){
    		$id = I('get.id',0,'intval');
    		//if(!$id) $this->error('参数错误');
    		$this->assign('info',M($mod[$type])->where('id='.$id)->find());
    		
    		$this->assign('type',$type);
    		$this->adminDisplay();
    	}else{
    		$name = 'add';
    		if(!$type){
    			$arr =array('money'=>I('post.money'),'zmoney'=>I('post.zmoney'),'time'=>time());
    		}else{
    			$arr =array('day'=>I('post.day'),'original'=>I('post.original'),'price'=>I('post.price'),'time'=>time());
    		}

    		$id = I('post.id',0,'intval');
    		if($id){
    			$name= "save";
    			$arr['id'] = $id;
    		}
    		$re = M($mod[$type])->$name($arr);
    		if($re===false){
    			$this->error('操作失败');
    		}else{
    			$this->success('操作成功');
    		}
    	}
    	
    }
    public function delCredit(){
    	$type = I('request.type',0,'intval');
    	$mod = array('ConfigCredit','ConfigVip');
    	$id = I('post.data',0,'intval');
      	if(!$id){
      		$this->error('参数错误');
      	} 
    	$re = M($mod[$type])->where('id='.$id)->delete();
  
        if($re===false){
        	$this->error('操作失败');
        }else{
        	$this->success('删除成功');
        }
    }
    
    public function OSS(){
    	if(!IS_POST){
    		$breadCrumb = array('站点信息'=>U());
    		$this->assign('breadCrumb',$breadCrumb);
    		$this->assign('info',D('Config')->getInfo());
    		$this->adminDisplay();
    	}else{
    
    		if(D('Config')->saveData()){
    			$this->success('站点配置成功！');
    		}else{
    			$this->error('站点配置失败');
    		}
    	}
    }
    
    public function diySetting(){
    	if(!IS_POST){
    		$Setting = I('get.Set');
    		if(!$Setting) $this->error('参数错误');
    		$breadCrumb = array('站点信息'=>U());
    		$this->assign('breadCrumb',$breadCrumb);
    		$this->assign('info',D('Config')->getInfo());
    		$this->adminDisplay($Setting);
    	}else{
    
    		if(D('Config')->saveData()){
    			$this->success('站点配置成功！');
    		}else{
    			$this->error('站点配置失败');
    		}
    	}
    }
    
    
    
}

