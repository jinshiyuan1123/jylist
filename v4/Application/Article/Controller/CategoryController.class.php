<?php
namespace Article\Controller;
use Home\Controller\SiteController;
/**
 * 栏目页面
 */

class CategoryController extends SiteController {

	/**
     * 栏目页
     */
    public function index(){
		header("Content-Type:text/html; charset=utf-8"); 
		if(C("onlywx")==1 && strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') === false ){
			 $this->siteDisplay('jg_qzwxdk');
			 exit;
		}
    		$classId = I('get.class_id',0,'intval');
        $urlName = I('get.urlname');
        if (empty($classId)&&empty($urlName)) {
            $this->error404();
        }
        //获取栏目信息
        $model = D('CategoryArticle');
        if(!empty($classId)){
            $categoryInfo=$model->getInfo($classId);
        }else if(!empty($urlName)){
        	$map = array();
        	$map['urlname'] = $urlName;
            $categoryInfo=$model->getWhereInfo($map);
        }else{
            $this->error404();
        }
        $classId = $categoryInfo['class_id'];
        //信息判断
        if (!is_array($categoryInfo)){
            $this->error404();
        }
        if($categoryInfo['app']<>MODULE_NAME){
            $this->error404();
        }
        
        A("Home/Public")->dowxlogin();
        
        //位置导航
        $crumb = D('DuxCms/Category')->loadCrumb($classId);
        //设置查询条件
        $where='';
        if ($categoryInfo['type'] == 0) {
            $classId = D('DuxCms/Category')->getSubClassId($classId);
        }
        if(empty($classId)){
        	$classId = $categoryInfo['class_id'];
        }
        $where['A.status'] = 1;
        $where['C.class_id'] = array('in',$classId);

        //分页参数
        $size = intval($categoryInfo['page']); 
       
        if (empty($size)) {
            $listRows = 20;
        } else {
            $listRows = $size;
        }
        //查询内容数据
        $modelContent = D('ContentArticle');
        $count = $modelContent->countList($where);
        $limit = $this->getPageLimit($count,$listRows);
        if(!empty($categoryInfo['content_order'])){

            $categoryInfo['content_order'] = $categoryInfo['content_order'].',';
        }
        $pageList = $modelContent->loadList($where,$limit,$categoryInfo['content_order'].'A.time desc,A.content_id desc');
        //dump($limit);
        //扩展字段处理   2015-05-06 by Ruizhong Liu copy for lxphp.com
        if(!empty($pageList) && $categoryInfo['fieldset_id']>0){
					$kztable  = D("DuxCms/Fieldset")->getInfo($categoryInfo['fieldset_id']);
					if($kztable['table']){
						$mod1 = D("DuxCms/FieldData");
						$mod1->setTable($kztable['table']);
						foreach($pageList as $key=>$val){
							$kzinfo = $mod1->getInfo($val['content_id']);
							if(is_array($kzinfo)) 
								$pageList[$key] = array_merge($val,$kzinfo);
							
        		}
					}
      	}
        //URL参数
        $pageMaps = array();
        $pageMaps['class_id'] = $classId;
        $pageMaps['urlname'] = $urlName;
        //获取分页
		//var_dump($pageMaps);
		if(I("get.ajax")==1) $this->ajaxReturn($pageList);
        $page = $this->getPageShow($pageMaps);
		//dump($page);
        //查询上级栏目信息
        $parentCategoryInfo = $model->getInfo($categoryInfo['parent_id']);
        //获取顶级栏目信息
        $topCategoryInfo = $model->getInfo($crumb[0]['class_id']);
        //MEDIA信息
        $media = $this->getMedia($categoryInfo['name'],$categoryInfo['keywords'],$categoryInfo['description']);
        //模板赋值
        $this->assign('categoryInfo', $categoryInfo);
       
        $positionId =$this->find_positionId(2);
    if($positionId){		
	
        foreach ($pageList as $k=> $v){
        	$position =array();
        	if($v['position']){
        		$position =explode(',', $v['position']);
        		if($position&&in_array($positionId,$position)) unset($pageList[$k]);
        	}
        }  
		 }
		 
        $this->assign('parentCategoryInfo', $parentCategoryInfo);
        $this->assign('topCategoryInfo', $topCategoryInfo);
        $this->assign('crumb', $crumb);
        $this->assign('pageList', $pageList);
        $this->assign('count', $count);
        $this->assign('page', $page);
        $this->assign('media', $media);
        $this->assign('act','index3');
        $this->siteDisplay($categoryInfo['class_tpl']);
    }
}