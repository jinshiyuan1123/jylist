<?php
namespace Admin\Controller;
use Admin\Controller\AdminController;
/**
 * 栏目管理
 */
class BxlistController extends AdminController
{
    /**
     * 当前模块参数
     */
    public function _infoModule()
    {
        $data = array('info' => array('name' => '保险公司管理',
                'description' => '管理网站所有保险公司信息',
                ),
            'menu' => array(
                array('name' => '保险公司列表',
                    'url' => U('Admin/Bxlist/index'),
                    'icon' => 'list',
                    ),
					array('name' => '添加保险公司',
                    'url' => U('Admin/Bxlist/add'),
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
		
		$keyword = I('request.keyword','');
        $breadCrumb = array('保险公司列表' => U());
        $this->assign('breadCrumb', $breadCrumb);
        //$this->assign('list', D('User')->loadData());
		
		$pageMaps = array();
        $pageMaps['keyword'] = $keyword;
		 $where = array();
		if(!empty($keyword)){
            $where['bx_name'] = array('like','%'.$keyword.'%');
        }
 
       // $pageMaps['class_id'] = $classId;
       // $pageMaps['position_id'] = $positionId;
        //查询数据
		
        $count = D('Bxlist')->countList($where);
        $limit = $this->getPageLimit($count,20);
		//dump($limit);
		
       $list = D('Bxlist')->loadList($where,$limit);
	    $this->assign('pageMaps',$pageMaps);
	
		$this->assign('page',$this->getPageShow($pageMaps));
		$this->assign('list',$list);
        $this->adminDisplay();
    }
	 /**
     * 增加
     */
    public function add(){
        if(!IS_POST){
            $breadCrumb = array('保险公司列表'=>U('index'),'保险公司添加'=>U());
            $this->assign('breadCrumb',$breadCrumb);
            $this->assign('name','添加');
            $this->adminDisplay('info');
        }else{
            if(D('Bxlist')->saveData('add')){
                $this->success('添加成功！');
            }else{
                $msg = D('Bxlist')->getError();
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
    		$breadCrumb = array('保险公司列表'=>U('index'),'保险公司修改'=>U());
    		$this->assign('breadCrumb',$breadCrumb);
    		$Id = I('get.id','','intval');
    	    if(empty($Id)) $this->error('参数不能为空！');
    	    //获取记录
    		$info = M ( 'Bxlist' )->where ( "id=$Id" )->find ();
    		if(!$info) $this->error('无数据！');
    	    
    		$this->assign('info',$info);
    		$this->assign('name','修改');
    		$this->adminDisplay('info');
    	}else{
    		if(D('Bxlist')->saveData('edit')){
    			$this->success('修改成功！');
    		}else{
    			$msg = D('Bxlist')->getError();
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
        if(D('Bxlist')->delData($contentId)){
            $this->success('删除成功！');
        }else{
            $this->error('删除失败！');
        }
	}
	
}

