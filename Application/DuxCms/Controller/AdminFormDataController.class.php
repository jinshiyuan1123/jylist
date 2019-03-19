<?php
namespace DuxCms\Controller;
use Admin\Controller\AdminController;
/**
 * 表单内容管理
 */
class AdminFormDataController extends AdminController {

    /**
     * 当前模块参数
     */
    public function _infoModule()
    {
        $fieldsetId = I('request.fieldset_id', 0, 'intval');
        if (empty($fieldsetId))
        {
            $this->error('参数不能为空！');
        }
        $this->formInfo = D('DuxCms/FieldsetForm')->getInfo($fieldsetId);
        $data = array('info' => array(
                'name' => $this->formInfo['name'].'管理',
                'description' => '管理'.$this->formInfo['name'].'内容',
                ),
            'menu' => array(
                array('name' => '内容列表',
                    'url' => U('index',array('fieldset_id' => $this->formInfo['fieldset_id'])),
                    'icon' => 'list',
                    ),
                array('name' => '添加内容',
                    'url' => U('add',array('fieldset_id' => $this->formInfo['fieldset_id'])),
                    'icon' => 'plus',
                    ),
                )
            );
        return $data;
    }

    /**
     * 列表
     */
    public function index()
    {
        //筛选条件
        $keyword = I('request.keyword','');
        $status = I('request.status','0');
        //字段列表
        $where = array();
        $where['A.fieldset_id'] = $fieldset_id = $this->formInfo['fieldset_id'];
        $fieldList = D('FieldForm')->loadList($where);
        $tableTh = array();
        $searchWhere = array();
        if($status) $searchWhere =array('status'=>$status);
        if(!empty($fieldList)){
            foreach ($fieldList as $key => $value) {
                if($value['show']){
                    $tableTh[] = $value['name'];
                }
                if($value['search']&&!empty($keyword)){
                    $searchWhere[$value['field']] = $keyword;
                }
            }
        }
        //设置模型
        $model = D('DuxCms/FieldData');
        $model->setTable($this->formInfo['table']);
        //查询数据
        $count = $model->countList($searchWhere);
        $limit = $this->getPageLimit($count,20);
        $list = $model->loadList($searchWhere,$limit);
        //URL参数
        $pageMaps = array();
        $pageMaps['fieldset_id'] = $fieldset_id;
        $pageMaps['keyword'] = $keyword;
        $pageMaps['status'] = $status;
        //面包屑
        $breadCrumb = array($this->formInfo['name'].'列表' => U('index',array('fieldset_id' => $this->formInfo['fieldset_id'])));
        $this->assign('breadCrumb', $breadCrumb);
        $this->assign('fieldList',  $fieldList);
        $this->assign('list',  $list);
        $this->assign('page',$this->getPageShow($pageMaps));
        $this->assign('formInfo', $this->formInfo);
        $this->assign('tableTh', $tableTh);
        $this->assign('keyword',$keyword);
        $this->assign('status',$status);
        $this->assign('url', U('index',array('fieldset_id' => $this->formInfo['fieldset_id'])));
        $this->adminDisplay();
    }

    /**
     * 增加
     */
    public function add()
    {
        //设置模型
        $model = D('DuxCms/FieldData');
        $model->setTable($this->formInfo['table']);
        if (!IS_POST)
        {
            //字段列表
            $where = array();
            $where['A.fieldset_id'] = $this->formInfo['fieldset_id'];
            $fieldList = D('FieldForm')->loadList($where);
            //获取HTML
            $html='';
            foreach ($fieldList as $value) {
                $html .= D('Field')->htmlFieldFull($value);
            }
			//dump($html);
			//print_r($this);
            ob_start();
			
            $this->show($html);
            $html = ob_get_clean();
			//dump($html);
            //面包屑
            $breadCrumb = array($this->formInfo['name'].'列表' => U('index',array('fieldset_id' => $this->formInfo['fieldset_id'])), '字段列表' => U('index', array('fieldset_id' => $fieldsetId)));
            $this->assign('breadCrumb', $breadCrumb);
            $this->assign('name', '添加');
            $this->assign('formInfo', $this->formInfo);
            $this->assign('html', $html);
            $this->adminDisplay('info');
        }else{
            if ($model->saveData('add',$_POST['fieldset_id'])){
                $this->success('表单内容添加成功！');
            }else{
                $msg = $model->getError();
                if (empty($msg))
                {
                    $this->error('表单内容添加失败');
                }else{
                    $this->error($msg);
                }
            }
        }
    }

    /**
     * 修改
     */
    public function edit()
    {
    	
        //设置模型
        $model = D('DuxCms/FieldData');
        $model->setTable($this->formInfo['table']);
        if (!IS_POST)
        {
            $dataId = I('get.data_id', '', 'intval');
            if (empty($dataId))
            {
                $this->error('参数不能为空！');
            }
            $info = $model->getInfo($dataId);
            if (!$info)
            {
                $this->error($model->getError());
            }
            //字段列表
            $where = array();
            $where['A.fieldset_id'] = $this->formInfo['fieldset_id'];
            $fieldList = D('FieldForm')->loadList($where);
            //获取HTML
            $html='';
            foreach ($fieldList as $value) {
                $html .= D('Field')->htmlFieldFull($value,$info[$value['field']]);
            }
            ob_start();
            $this->show($html);
            $html = ob_get_clean();
            //面包屑
            $breadCrumb = array('表单列表' => U('index'), '表单修改' => U('edit', array('fieldset_id' => $fieldsetId)));
            $this->assign('breadCrumb', $breadCrumb);
            $this->assign('name', '修改');
            $this->assign('info', $info);
            $this->assign('tplList',D('Admin/Config')->tplList());
            $this->assign('formInfo', $this->formInfo);
            $this->assign('html', $html);
            $this->adminDisplay('info');
        }
        else
        {
        	$ids = I('post.ids');
        	$type = I('post.type',0,'intval');
        	if($ids&&$type){
        		foreach ($ids as $id) {
        			$data = array();
        			//$data['id'] = $id;
        			switch ($type) {
        				case 1:
        					//通过
        				   $data =	M('Ext_tixian')->where('data_id='.$id)->find();
        				   
        					if($data){
        						$reinfo = M('Users')->where('id='.$data['uid'])->find();
        						$money =$reinfo['money'];
        						if($money>=$data['money']){
        							$desc='支付宝提现';
        							A("Home/Site")->changemoney((-1)*$data['money'],7,$desc,$data['uid'],$note=1,$ip=0);
        							A("Home/Api")->log_money(0,$data['money'],$desc,4,1,$data['uid']);
        							M('Ext_tixian')->where('data_id='.$id)->save(array('status'=>1));
        						}else{
        							//$this->error('用户余额不足提现，请检查。');
        						}
        					
        					}
        					break;
        				case 2:
        					//未通过
        					M('Ext_tixian')->where('data_id='.$id)->save(array('status'=>2));
        					
        					break;
        				
        			}
        		
        		}
        		$this->success('批量操作执行完毕！');
        	}
            if ($model->saveData('edit',$_POST['fieldset_id']))
            {
            	
            	if($_POST['Fieldset_status']==1 && $_POST['Fieldset_money'] >0 && $_POST['Fieldset_uid']>0 && $this->formInfo['table']=='tixian'){            		
            		$reinfo = M('Users')->where('id='.$_POST['Fieldset_uid'])->find();
            		$money =$reinfo['money'];
            		if($money>=$_POST['Fieldset_money']){
            			$desc='支付宝提现，后台操作';
						A("Home/Site")->changemoney((-1)*$_POST['Fieldset_money'],7,$desc,$_POST['Fieldset_uid'],$note=0,$ip=0);
						A("Home/Api")->log_money(0,$_POST['Fieldset_money'],$desc,4,1,$_POST['Fieldset_uid']);
					}else{
					 $this->error('用户余额不足提现，请检查。');	
					}
					
					
				}
            	
            	
                $this->success('表单修改成功！');
            }
            else
            {
            
                $msg = $model->getError();
                if (empty($msg))
                {
                    $this->error('表单修改失败');
                }
                else
                {
                    $this->error($msg);
                }
            }
        }
    }

    /**
     * 删除
     */
    public function del()
    {
        $dataId = I('post.data');
        if (empty($dataId))
        {
            $this->error('参数不能为空！');
        }
        //设置模型
        $model = D('DuxCms/FieldData');
        $model->setTable($this->formInfo['table']);
        // 删除操作
        if ($model->delData($dataId))
        {
            $this->success('内容删除成功！');
        }
        else
        {
            $msg = $model->getError();
            if (empty($msg))
            {
                $this->error('内容删除失败！');
            }
            else
            {
                $this->error($msg);
            }
        }
    }
}

