<?php
namespace Article\Controller;
use Home\Controller\SiteController;
/**
 * 栏目页面
 */

class ContentController extends SiteController {

	/**
     * 栏目页
     */
    public function index()
    {
		header("Content-Type:text/html; charset=utf-8"); 
		if(C("onlywx")==1 && strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') === false ){
			 $this->siteDisplay('jg_qzwxdk');
			 exit;
		}
		/*
		if(C("onlywx")==1){
			$APPID = C('APPID');
			if(!isset($_GET['code'])){
				$site_url = C('site_url');
				$backurl = A("Home/Site")->get_url();
				//替换为站点域名
				$pattern = '~^(([^:/?#]+):)?(//([^/?#]*))?([^?#]*)(\?([^#]*))?(#(.*))?~i';
				preg_match_all($pattern, $backurl ,$rr);
				$backurl = $rr[1][0]."//".$site_url.$rr[5][0];
				$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$APPID
					."&redirect_uri=".urlencode($backurl)
					."&response_type=code&scope=snsapi_base&state=123#wechat_redirect"; //  snsapi_userinfo

				Header("Location: $url");
			} else {
				$code = $_GET['code'];
				$SCRETID = C('SCRETID');	
				$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$APPID."&secret=".$SCRETID."&code=".$code."&grant_type=authorization_code";
				$re = $this->curl_get_contents($url);
				$rearr = json_decode($re,true);
				$openid = $rearr['openid'];
				cookie("user_openid", $openid, 300);
			}	
		}*/
		
        $contentId = I('get.content_id',0,'intval');
        $urlTitle = I('get.urltitle');
        if (empty($contentId)&&empty($urlTitle)) {
            $this->error404();
        }
        $model = D('ContentArticle');
        //获取内容信息
        if(!empty($contentId)){
            $contentInfo=$model->getInfo($contentId);
          
        }else if(!empty($urlTitle)){
            $contentInfo=$model->getInfoUrl($urlTitle);
        }else{
            $this->error404();
        }
        $contentId = $contentInfo['content_id'];
        //信息判断
        if (!is_array($contentInfo)){
            $this->error404();
        }
        
        //A("Home/Public")->dowxlogin();
        
        //获取栏目信息
        $modelCategory = D('CategoryArticle');
        $categoryInfo=$modelCategory->getInfo($contentInfo['class_id']);
        if (!is_array($categoryInfo)){
            $this->error404();
        }
        if($categoryInfo['app']<>MODULE_NAME){
            $this->error404();
        };
        //判断跳转
        if (!empty($contentInfo['url']))
        {
            ob_start();
            $this->show($contentInfo['url']);
            $link = ob_get_clean();
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: ".$link."");
            exit;
        }
		/*
        //位置导航
        $crumb = D('DuxCms/Category')->loadCrumb($contentInfo['class_id']);
        //查询上级栏目信息
        $parentCategoryInfo = $modelCategory->getInfo($categoryInfo['parent_id']);
        //获取顶级栏目信息
        $topCategoryInfo = $modelCategory->getInfo($crumb[0]['class_id']);
        //更新访问计数*/
		
        $viewsData=array();
        $viewsData['views'] = array('exp','views+1');
        $viewsData['content_id'] = $contentInfo['content_id'];
        D('DuxCms/Content')->editData($viewsData);
        //内容处理
        $contentInfo['content'] = html_out($contentInfo['content']);
        
        //$contentInfo['content'] = preg_replace("/<img(.*)>/","<img $1 >",$contentInfo['content']);
        
        
   /*     //上一篇
        $prevWhere = array();
        $prevWhere['A.status'] = 1;
        $prevWhere['A.time'] = array('lt',$contentInfo['time']);
        $prevWhere['C.class_id'] = $categoryInfo['class_id'];
        $prevInfo=$model->getWhereInfo($prevWhere,' A.time DESC,A.content_id DESC');
        if(!empty($prevInfo)){
            $prevInfo['aurl']=$model->getUrl($prevInfo,$appConfig);
            $prevInfo['curl']=$modelCategory->getUrl($prevInfo,$appConfig);
        }
        //下一篇
        $nextWhere = array();
        $nextWhere['A.status'] = 1;
        $nextWhere['A.time'] = array('gt',$contentInfo['time']);
        $nextWhere['C.class_id'] = $categoryInfo['class_id'];
        $nextInfo=$model->getWhereInfo($nextWhere,' A.time ASC,A.content_id ASC');
        if(!empty($nextInfo)){
            $nextInfo['aurl']=$model->getUrl($nextInfo,$appConfig);
            $nextInfo['curl']=$modelCategory->getUrl($nextInfo,$appConfig);
        }*/
        
        $timestamp = A("Home/Api")->yqapi();//分享
        A("Home/Api")->countviews();//统计 和赠送 佣金      
		
        
        //MEDIA信息
        $media = $this->getMedia($contentInfo['title'],$contentInfo['keywords'],$contentInfo['description']);
        //模板赋值
if($categoryInfo['fieldset_id']>0){//扩展字段处理   2015-3-27 by lxphp.com
			$kztable  = D("DuxCms/Fieldset")->getInfo($categoryInfo['fieldset_id']);
			if($kztable['table']){
				$mod1 = D("DuxCms/FieldData");
				$mod1->setTable($kztable['table']);
				$kzinfo = $mod1->getInfo($contentId);
				if(is_array($kzinfo)) $contentInfo = array_merge($contentInfo,$kzinfo);
			}
			
		}
		
		//多域名 by lxphp.com  2015 - 09 -03
		$dm = C('moredomai');
		if($dm){
		$dm = explode('
',$dm);
$dmnum = count($dm);
$r = C('lxphpca');
$r = $r?$r:2;
$randnum = rand($r,$dmnum);
$thisdm = trim($dm[$randnum-1]);
if(strstr($thisdm,'*')){
	$string =  new \Org\Util\String();
	$randstr = $string->randString(5,3);
	$thisdm =  str_replace('*',$randstr,$thisdm);
}
		
		$domain = $thisdm;
		  // dump($domain);
		     $this->assign('domain', $domain);
		   //多域名 by lxphp.com  2015 - 09 -03
		  } 

		  //评论列表
		  $fieldset_id = 0; //$this->get_pl_fieldset_id()  隐藏评论 20151218
		 // $fieldset_id = M('Fieldset')->where("name='评论列表'")->getField('fieldset_id');
		  if($fieldset_id){
		    $model = D('DuxCms/FieldData');
		  	$this->formInfo = D('DuxCms/FieldsetForm')->getInfo($fieldset_id);
		  
		  	$model->setTable($this->formInfo['table']);
		  	
		  	$searchWhere['content_id'] =$contentInfo['content_id'];
		  	$searchWhere['status'] = 2;
		  	
		  	$pl_count = $model->countList($searchWhere);
		  	$limit = $this->getPageLimit($count,50);
		  	$pl_list = $model->loadList($searchWhere,$limit);
		  	if(!$pl_list){
		  		$pl_list = $model->where('type=2 and status=2')->select();
		  	}
		    
		    //$pl_list =array_merge($pl_list,$zz_list);
		  	foreach ($pl_list as $k=>$v){
		  		$addtime =$v['addtime'];
		  		$v['addtime'] =$this->q_format_date($v['addtime']);
		  		$v['user_data'] = M('Users')->field('avatar,user_nicename')->where('id='.$v['uid'])->find();      
		  		$new_pl_list[$addtime] = $v;
		  	}
		  	krsort($new_pl_list);
		  	$this->assign('pl_list', $new_pl_list);	
		  }
		  
		  
		  
		$contentInfo["image"] = html_out($contentInfo["image"]);  
		$this->assign('contentInfo', $contentInfo);
        $this->assign('categoryInfo', $categoryInfo);
        $this->assign('parentCategoryInfo', $parentCategoryInfo);
        $this->assign('topCategoryInfo', $topCategoryInfo);
        $this->assign('crumb', $crumb);
        $this->assign('count', $count);      
        $this->assign('page', $page);
        $this->assign('isweixin', $this->is_weixin()?1:0);
        $this->assign('media', $media);
        $this->assign('prevInfo', $prevInfo);
        $this->assign('nextInfo', $nextInfo);
		$this->assign('random_or_ad', C('random_or_ad'));
		$this->assign('fxapi', I('get.fxapi')?I('get.fxapi'):'0');
       
        if($contentInfo['wxurl']&&$contentInfo['user_id']&&!strstr($contentInfo['wxurl'],'mp.weixin.qq.com')){
        	$this->siteDisplay('user_content');
        	exit;
        }
        if($contentInfo['tpl']){
            $this->siteDisplay($contentInfo['tpl']);
        }else{
            $this->siteDisplay($categoryInfo['content_tpl']);
        }
    }
	/**
	* 换一换文章
	* @author nineTea
	* 20151218
	*/
	public function randomArticle(){
		$where = array(
			'status'=>1
		);
		$re = M('Content')->where($where)->order('rand()')->limit('1')->find();
		//dump($re);exit;
		redirect ( U ( 'Article/Content/index' ,array('content_id'=>$re['content_id'])) );
	}
}