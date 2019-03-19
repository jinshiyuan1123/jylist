<?php
namespace Admin\Controller;
use Admin\Controller\AdminController;
/**
 * 栏目管理
 */
class BxcatController extends AdminController
{
    /**
     * 当前模块参数
     */
    public function _infoModule()
    {
        $data = array('info' => array('name' => '保险分类',
                'description' => '管理网站全部保险分类',
                ),
            'menu' => array(
                array('name' => '保险分类列表',
                    'url' => U('Admin/Bxcat/index'),
                    'icon' => 'list',
                    ),
					array('name' => '添加保险分类',
                    'url' => U('Admin/Bxcat/add'),
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
     * 列表
     */
    public function index()
    {
        $breadCrumb = array('分类列表' => U());
        $this->assign('breadCrumb', $breadCrumb);
       $this->assign('list', D('Bxcat')->loadList());
        $this->adminDisplay();
    }
	
	public function add(){
		 if(!IS_POST){
		 	 $this->assign('categoryList',D('Bxcat')->loadList());
			  $breadCrumb = array('分类列表'=>U('index'),'保险分类添加'=>U());
            $this->assign('breadCrumb',$breadCrumb);
            $this->assign('name','添加');
			 $this->assign('fromlist',D('DuxCms/FieldsetForm')->loadList());	
		 $this->adminDisplay('info');
		 }else{
		 	$_POST['app'] = MODULE_NAME;
            $model = D('Bxcat');
            if($model->saveData('add')){
                $this->success('添加成功！');
            }else{
                $msg = $model->getError();
                if(empty($msg)){
                    $this->error('添加失败');
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
            $classId = I('get.class_id','','intval');
            if(empty($classId)){
                $this->error('参数不能为空！');
            }
            $model = D('Bxcat');
            $info = $model->getInfo($classId);
            if(!$info){
                $this->error($model->getError());
            }
            $breadCrumb = array('分类列表'=>U('Admin/Bxcat/index'),'分类修改'=>U());
            $this->assign('breadCrumb',$breadCrumb);
            $this->assign('name','修改');
            $this->assign('categoryList',D('Bxcat')->loadList());
			//print_r(D('DuxCms/FieldsetForm')->loadList());
            $this->assign('fromlist',D('DuxCms/FieldsetForm')->loadList());
           // $this->assign('tplList',D('Admin/Config')->tplList());
            $this->assign('info',$info);
            $this->adminDisplay('info');
        }else{
            $_POST['app'] = MODULE_NAME;
            $model = D('Bxcat');
            if($model->saveData('edit')){
                $this->success('页面修改成功！');
            }else{
                $msg = $model->getError();
                if(empty($msg)){
                    $this->error('页面修改失败');
                }else{
                    $this->error($msg);
                }
                
            }
        }
    }
	
	public function del(){
		$classId = I('post.data');
        if(empty($classId)){
            $this->error('参数不能为空！');
        }
		
		$re = D('Bxcat')->delData($classId);
		if($re){
			  $this->success('删除成功！');
		}else{
			  $this->error('删除失败！');
		}
	}
	
	
	
}

