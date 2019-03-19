<?php if (!defined('THINK_PATH')) exit();?>﻿ <!-- 用户列表 -->
       <div style="height:60px;"></div>
       <!-- 底部footer -->
       <div class="index_footer">
          <a href="<?php echo U('Home/Index/index');?>" class="index_footer_a <?php if($nav==''){ ?> hot<?php } ?> >">缘分</a>
          <a href="<?php echo U('Home/Wechat/sixin');?>" class="index_footer_b <?php if($nav=='Wechat'){ ?> hot sixina<?php } ?>" <?php if($wdsx == 1): ?>id="youren"<?php endif; ?> >私信</a>
          <a href="<?php echo U('Home/Nearby/index');?>" class="index_footer_e <?php if($nav=='Nearby'){ ?> hot<?php } ?>">附近</a>
          <a href="<?php echo U('Home/Index/hot');?>" class="index_footer_c <?php if($nav=='Dongtai'){ ?> hot<?php } ?>">动态</a>
          <a href="<?php echo U('Home/User/index');?>" class="index_footer_d <?php if($nav=='User'){ ?> hot<?php } ?>">我的</a>
       </div>
        <!-- 底部footer -->
		<div id="loading" style="display: none"><img src="images/jiazai.gif"></div>
		
		
		 <!--收费提醒-->
        <div class="shoufe" id="newalert"  style="display:none;">
          <div class="heipin" style="display:block"></div>
           <div class="qiandao_div" style="    top: 35%;"><br>
             <p class="sl_dangchuang">提示</p>
             <p class="sl_shoufei_tishi" id="newalertmsg">加载中……</p>
			 <br>
             <div class="sl_qr">
             <a href="javascript:;" class="sl_qr_qr">确认</a>
             </div>
           </div>
        </div>
 <!--收费提醒-->
 <!--欠费提醒-->
 <div class="qianfe" id="newconfrim"  style="display:none">
          <div class="heipin" style="display:block"></div>
           <div class="qiandao_div" style="    top: 35%;"><div class=" cb"><br></div>
             <p class="sl_dangchuang">提示</p>
             <p class="sl_shoufei_tishi1" id="newconfrimmsg">加载中……</p>
             <div class="sl_qf">
              <a class="sl_qf_yi">以后再说</a><a href="" class="sl_qf_er" id="caozuobutton">确定</a>
             </div>
           </div>
  </div>
 <!--欠费提醒-->
		
	
<script type="text/javascript" src="js/common.js"></script>
<script>
$(function(){
	var dw = '<?php echo ($dw); ?>';
	//alert(dw)
	if(dw==1|| dw==2){
		 getLocation();	
	}
	
	var qg ='<?php echo ($qg); ?>';
	//if(qg==1)alertbox('没有筛选到任何数据，已显示全国数据。');
	//postdata('39.897445','116.331398');
	//if(dw==2 && confirm('是否重新定位？')){
		 //getLocation();	
	//}
	
	
 $(".qiandao_guanbi,.sl_qr_qr").click(function(){
    $(".shoufe").hide();
 })
 $(".sl_qf_yi").click(function(){
    $(".qianfe").hide();
 })
 
 $(".sixina").click(function(){
 	$.post("<?php echo U('Ajax/sixinaclear');?>",{pid:1},function(data){
		if(data.status==1){
			
		}
	},'json')
 })
 
})


  function postdata(lat,lon){
  	 $.post("<?php echo U('Home/Ajax/area');?>",{lat:lat,lon:lon},function(data){		
		if(data.status==1){
			alertbox('当前定位：'+data.info.provincename+data.info.cityname);
			window.location.reload();
		}				
	},'json')
	
  }
 
  function gz(touid){
  	$.post("<?php echo U('Home/Ajax/guanzhu');?>",{touid:touid},function(data){
		if(data.status==1){
			$(".gz").html("已关注");
			if(data.url=='real')
			$(".gznum").html(Number($(".gznum").text())+1);
		}else if(data.status==-1){
			alertbox(data.info,'去完善',data.url);
		}else{
			alertmsg(data.info);
		}
	},'json')
	
  }
  
  function dozan(o,pid){
	$.post("<?php echo U('Ajax/clickzan');?>",{pid:pid},function(data){
		if(data.status==1){
			$(o).find(".zan").html(data.info);
		}
	},'json')
}	
zhuanwu();




</script>