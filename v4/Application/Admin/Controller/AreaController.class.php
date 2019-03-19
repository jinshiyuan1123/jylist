<?php
namespace Admin\Controller;
use Admin\Controller\AdminController;
/**
 * 后台城市管理
 */
class AreaController extends AdminController {

    /**
     * 当前模块参数
     */
    protected function _infoModule(){
        return array(
            'info'  => array(
                'name' => '城市管理',
                'description' => '管理网站城市',
                ),
            'menu' => array(
                    array(
                        'name' => '城市列表',
                        'url' => U('index'),
                        'icon' => 'list',
                    ),
                    array(
                        'name' => '添加城市',
                        'url' => U('add'),
                        'icon' => 'plus',
                    ),
                ),
            );
    }
	/**
     * 列表
     */
    public function index(){
        //筛选条件
        $where = array();
        $keyword = I('request.keyword','');
		$rootid = I('request.rootid','0','intval');
		$where['rootid'] = $rootid;
        if(!empty($keyword)){
            $where['_string'] = ' and (A.areaname like "%'.$keyword.'%") ';
        }
        //URL参数
        $pageMaps = array();
        $pageMaps['keyword'] = $keyword;
		$pageMaps['rootid'] = $rootid;

        //查询数据
        $count = D('Area')->countList($where);
        $limit = $this->getPageLimit($count,20);
        $list = D('Area')->loadList($where,$limit);
		//位置导航
        $breadCrumb = array('城市列表'=>U());
        //模板传值
        $this->assign('breadCrumb',$breadCrumb);
		$this->assign('rootId',$rootid);
        $this->assign('list',$list);
		$this->assign('groupList',$list);
        $this->assign('page',$this->getPageShow($pageMaps));
        $this->assign('keyword',$keyword);
        $this->adminDisplay();
    }

    /**
     * 增加
     */
    public function add(){
        if(!IS_POST){
            $breadCrumb = array('城市列表'=>U('index'),'添加'=>U());
            $this->assign('breadCrumb',$breadCrumb);
            $this->assign('name','添加');
            $this->adminDisplay('info');
        }else{
            if(D('Area')->saveData('add')){
                $this->success('城市添加成功！');
            }else{
                $msg = D('Area')->getError();
                if(empty($msg)){
                    $this->error('城市添加失败');
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
            $areaId = I('get.areaid','','intval');
            if(empty($areaId)){
                $this->error('参数不能为空！');
            }
            //获取记录
            $model = D('Area');
            $info = $model->getInfo($areaId);
			
			//dump($info);
            if(!$info){
                $this->error($model->getError());
            }
            $breadCrumb = array('城市列表'=>U('index'),'修改'=>U('',array('areaid'=>$areaId)));
            $this->assign('breadCrumb',$breadCrumb);
            $this->assign('name','修改');
            $this->assign('info',$info);
            $this->adminDisplay('info');
        }else{
            if(D('Area')->saveData('edit')){
                $this->success('城市修改成功！');
            }else{
                $msg = D('Area')->getError();
                if(empty($msg)){
                    $this->error('城市修改失败');
                }else{
                    $this->error($msg);
                }
            }
        }
    }

    /**
     * 删除
     */
    public function del(){
        $areaId = I('post.data');
        if(empty($areaId)){
            $this->error('参数不能为空！');
        }
        //获取城市数量
        if(D('Area')->delData($areaId)){
            $this->success('城市删除成功！');
        }else{
            $msg = D('Area')->getError();
            if(empty($msg)){
                $this->error('城市删除失败！');
            }else{
                $this->error($msg);
            }
        }
    }


}

