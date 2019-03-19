<?php
namespace Admin\Controller;
use Admin\Controller\AdminController;
/**
 * 后台用户
 */
class DomainCopyrightController extends AdminController {

    /**
     * 当前模块参数
     */
    protected function _infoModule(){
        return array(
            'info'  => array(
                'name' => '授权用户管理',
                'description' => '管理网站授权用户',
                ),
            'menu' => array(
                    array(
                        'name' => '授权用户列表',
                        'url' => U('index'),
                        'icon' => 'list',
                    ),
                    array(
                        'name' => '添加授权用户',
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
        if(!empty($keyword)){
            $where['_string'] = ' A.domain like "%'.$keyword.'%"';
        }
        //URL参数
        $pageMaps = array();
        $pageMaps['keyword'] = $keyword;
        //查询数据
        $count = D('DomainCopyright')->countList($where);
        $limit = $this->getPageLimit($count,20);
        $list = D('DomainCopyright')->loadList($where,$limit);
        //位置导航
        $breadCrumb = array('授权列表'=>U());
        //模板传值
        $this->assign('breadCrumb',$breadCrumb);
        $this->assign('list',$list);
        $this->assign('page',$this->getPageShow($pageMaps));
        $this->assign('keyword',$keyword);
        $this->adminDisplay();
    }

    /**
     * 增加
     */
    public function add(){
        if(!IS_POST){
            $breadCrumb = array('授权列表'=>U('index'),'添加'=>U());
            $this->assign('breadCrumb',$breadCrumb);
            $this->assign('name','添加');
            $this->adminDisplay('info');
        }else{       	
            if(D('DomainCopyright')->saveData('add')){
                $this->success('用户授权添加成功！');
            }else{
                $msg = D('DomainCopyright')->getError();
                if(empty($msg)){
                    $this->error('用户授权添加失败');
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
            $domainId = I('get.id','','intval');
            if(empty($domainId)){
                $this->error('参数不能为空！');
            }
            //获取记录
            $model = D('DomainCopyright');
            $info = $model->getInfo($domainId);
			
			//dump($info);
            if(!$info){
                $this->error($model->getError());
            }
            $breadCrumb = array('用户授权列表'=>U('index'),'修改'=>U('',array('id'=>$domainId)));
            $this->assign('breadCrumb',$breadCrumb);
            $this->assign('name','修改');
            $this->assign('info',$info);
            $this->adminDisplay('info');
        }else{
            if(D('DomainCopyright')->saveData('edit')){
                $this->success('用户授权修改成功！');
            }else{
                $msg = D('DomainCopyright')->getError();
                if(empty($msg)){
                    $this->error('用户授权修改失败');
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
        $domainId = I('post.data');
        if(empty($domainId)){
            $this->error('参数不能为空！');
        }
        if($domainId == 1){
            $this->error('保留授权无法删除！');
        }
        //获取用户数量
        if(D('DomainCopyright')->delData($domainId)){
            $this->success('用户删除成功！');
        }else{
            $msg = D('DomainCopyright')->getError();
            if(empty($msg)){
                $this->error('用户删除失败！');
            }else{
                $this->error($msg);
            }
        }
    }


}

