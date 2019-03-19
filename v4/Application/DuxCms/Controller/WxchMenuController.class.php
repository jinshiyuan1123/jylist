<?php
namespace DuxCms\Controller;
use Admin\Controller\AdminController;
/**
 * 微信菜单管理
 */
class WxchMenuController extends AdminController
{
    /**
     * 当前模块参数
     */
    public function _infoModule()
    {
        $data = array('info' => array('name' => '微信菜单管理',
                'description' => '管理微信菜单信息',
                ),
            'menu' => array(
                array('name' => '菜单列表',
                    'url' => U('DuxCms/WxchMenu/index'),
                    'icon' => 'list',
                    ),
            
                //$contentMenu
                )
            );
		
        return $data;
    }
    /**
     * 列表
     */
    public function index()
    {
    	
    	
    	
    	$breadCrumb = array('列表' => U());
    	$this->assign('breadCrumb', $breadCrumb);
    	if(!IS_POST){
    	$data['first'] =	M('WxchMenu')->where("`aid` =0")->select();
    	$data['second1'] =	M('WxchMenu')->where("`aid` =1")->select();
    	$data['second2'] =	M('WxchMenu')->where("`aid` =2")->select();
    	$data['second3'] =	M('WxchMenu')->where("`aid` =3")->select();
    
    	$this->assign('data',$data);
    	
    	$this->adminDisplay();
    	}else{
    		$level = 1;
    		$aid = 0;
    		M('Wxch_menu')->query('TRUNCATE TABLE `lx_wxch_menu`');
    		$first_type = I('post.first_type','array()');
    		$first = I('post.first','array()');
    		$first_value = I('post.first_value','array()');
    		foreach($first as $k=>$v)
    		{
    			$sql = "INSERT INTO `lx_wxch_menu` (`menu_type`, `level`, `name`, `value`, `aid`) VALUES ( '$first_type[$k]', $level, '$first[$k]', '$first_value[$k]', $aid)";
    			M('Wxch_menu')->execute($sql);
    		}
    		$level = 2;
    		$aid = 1;
    		$menu_type1 = $_POST['menu_type1'];
    		$second1 = $_POST['second1'];
    		$value1 = $_POST['value1'];
    		foreach($second1 as $k=>$v)
    		{
    			$sql = "INSERT INTO `lx_wxch_menu` (`menu_type`, `level`, `name`, `value`, `aid`) VALUES ( '$menu_type1[$k]', $level, '$second1[$k]', '$value1[$k]', $aid)";
    			M('Wxch_menu')->execute($sql);
    		}
    		$aid = 2;
    		$menu_type2 = $_POST['menu_type2'];
    		$second2 = $_POST['second2'];
    		$value2 = $_POST['value2'];
    		foreach($second2 as $k=>$v)
    		{
    			$sql = "INSERT INTO `lx_wxch_menu` (`menu_type`, `level`, `name`, `value`, `aid`) VALUES ( '$menu_type2[$k]', $level, '$second2[$k]', '$value2[$k]', $aid)";
    			M('Wxch_menu')->execute($sql);
    		}
    		$aid = 3;
    		$menu_type3 = $_POST['menu_type3'];
    		$second3 = $_POST['second3'];
    		$value3 = $_POST['value3'];
    		foreach($second2 as $k=>$v)
    		{
    			$sql = "INSERT INTO `lx_wxch_menu` (`menu_type`, `level`, `name`, `value`, `aid`) VALUES ( '$menu_type3[$k]', $level, '$second3[$k]', '$value3[$k]', $aid)";
    			M('Wxch_menu')->execute($sql);
    		}
    		$this->success('保存成功！');
    	}
		
		//print_r($list);
       
    }
    //生成自定义菜单
    public function create_menu(){
    	
    	$data = array();
    	$sql = "SELECT * FROM  `lx_wxch_menu` WHERE  `aid` =0";
    	$data['first'] = M('Wxch_menu')->query($sql);
    	foreach($data['first'] as $k=>$v)
    	{
    		if(empty($data['first'][$k]['name']))
    		{
    			unset($data['first'][$k]);
    		}
    		else
    		{
    			$data['first'][$k]['name'] = urlencode($v['name']);
    			if($v['menu_type'] == 'click')
    			{
    				$data['first'][$k]['array'] = array('type'=>$v['menu_type'],'name'=>$data['first'][$k]['name'],'key'=>$v['value']);
    			}
    			elseif($v['menu_type'] == 'view')
    			{
    				$data['first'][$k]['array'] = array('type'=>$v['menu_type'],'name'=>$data['first'][$k]['name'],'url'=>$v['value']);
    			}
    		}
    	}
    	$sql = "SELECT * FROM  `lx_wxch_menu` WHERE  `aid` =1";
    	$data['second1'] = M('Wxch_menu')->query($sql);
    	$second1 = 'no';
    	foreach($data['second1'] as $k=>$v)
    	{
    		if(empty($data['second1'][$k]['name']))
    		{
    			unset($data['second1'][$k]);
    		}
    		else
    		{
    			$v['value'] = urlencode($v['value']);
    			$v['name'] = urlencode($v['name']);
    			if($v['menu_type'] == 'click')
    			{
    				$array1[] = array('type'=>$v['menu_type'],'name'=>$v['name'],'key'=>$v['value']);
    			}
    			elseif($v['menu_type'] == 'view')
    			{
    				$array1[] = array('type'=>$v['menu_type'],'name'=>$v['name'],'url'=>$v['value']);
    			}
    			$second1 = 'yes';
    		}
    	}
    	$sql = "SELECT * FROM  `lx_wxch_menu` WHERE  `aid` =2";
    	$data['second2'] = M('Wxch_menu')->query($sql);
    	$second2 = 'no';
    	foreach($data['second2'] as $k=>$v)
    	{
    		if(empty($data['second2'][$k]['name']))
    		{
    			unset($data['second2'][$k]);
    		}
    		else
    		{
    			$v['value'] = urlencode($v['value']);
    			$v['name'] = urlencode($v['name']);
    			if($v['menu_type'] == 'click')
    			{
    				$array2[] = array('type'=>$v['menu_type'],'name'=>$v['name'],'key'=>$v['value']);
    			}
    			elseif($v['menu_type'] == 'view')
    			{
    				$array2[] = array('type'=>$v['menu_type'],'name'=>$v['name'],'url'=>$v['value']);
    			}
    			$second2 = 'yes';
    		}
    	}
    	$sql = "SELECT * FROM  `lx_wxch_menu` WHERE  `aid` =3";
    	$data['second3'] = M('Wxch_menu')->query($sql);
    	$second3 = 'no';
    	foreach($data['second3'] as $k=>$v)
    	{
    		if(empty($data['second3'][$k]['name']))
    		{
    			unset($data['second3'][$k]);
    		}
    		else
    		{
    			$v['value'] = urlencode($v['value']);
    			$v['name'] = urlencode($v['name']);
    			if($v['menu_type'] == 'click')
    			{
    				$array3[] = array('type'=>$v['menu_type'],'name'=>$v['name'],'key'=>$v['value']);
    			}
    			elseif($v['menu_type'] == 'view')
    			{
    				$array3[] = array('type'=>$v['menu_type'],'name'=>$v['name'],'url'=>$v['value']);
    			}
    			$second3 = 'yes';
    		}
    	}
    	if($second1 == 'yes')
    	{
    		$sarr1 = array('name'=>$data['first'][0]['name'],'sub_button'=>$array1);
    	}
    	elseif($second1 == 'no')
    	{
    		$sarr1 = $data['first'][0]['array'];
    	}
    	if($second2 == 'yes')
    	{
    		$sarr2 = array('name'=>$data['first'][1]['name'],'sub_button'=>$array2);
    	}
    	elseif($second2 == 'no')
    	{
    		$sarr2 = $data['first'][1]['array'];
    	}
    	if($second3 == 'yes')
    	{
    		$sarr3 = array('name'=>$data['first'][2]['name'],'sub_button'=>$array3);
    	}
    	elseif($second3 == 'no')
    	{
    		$sarr3 = $data['first'][2]['array'];
    	}
		if($sarr1){
			$arr['button'][] = $sarr1;
		}
		if($sarr2){
			$arr['button'][] = $sarr2;
		}
		if($sarr3){
			$arr['button'][] = $sarr3;
		}
    	//$arr = array( 'button' => array($sarr1,$sarr2,$sarr3) );
    	$menu = urldecode(json_encode($arr));
    	A("Home/Weixin")->menu($menu);
    	exit;
    	
    }
    
    
    
	
   
	
}

