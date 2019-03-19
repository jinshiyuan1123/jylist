<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="black" name="apple-mobile-web-app-status-bar-style">
<meta name="format-detection" content="telephone=no">
<meta http-equiv="Cache-Control" content="no-cache, must-revalidate">
<meta name="keywords" content="关健词">
<meta name="description" content="描述">
<title><?php echo ($media["title"]); ?></title>
<link rel="stylesheet" type="text/css" href="css/css.css">
<script type="text/javascript" src="js/jquery-1.8.0.min.js"></script>
</head>
<style type="text/css">
       .fx_header {
              position: fixed;
              top: 0px;
                }
  </style>

<body>

  
  <div class="hd_main">
  <div style="height:40px;display:none ;" class="appdiv fx_header_new"></div>
  <!--#include file="app_share.html"-->
  <div class="siliao_header">
         <?php echo ($name); ?>
         <div class="fl" id="ddd"><a href="javascript:history.go(-1);"><img src="images/zuojian.png" alt="">返回</a></div>
         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      </div>
      <div class="sixin_div">
         <div class="sixin_div_a">
         <ul class="fujin">
         	
         	<?php if(!$list): if($type): ?><div class="zanwushuju">
	    					<img src="images/zanwushuju.png" alt="">
	    					<p>您暂时没有粉丝，赶紧去秀出你的魅力吧~</p>
	    				</div>
         		<?php else: ?>	
						<div class="zanwushuju">
    					<img src="images/zanwushuju.png" alt="">
    					<p>您暂时没有关注的人，赶紧去认识新朋友吧~</p>
    				</div><?php endif; ?>	
					<?php else: ?>	
	          <?php if(is_array($list)): foreach($list as $key=>$vo): ?><li class="cb" onclick="window.location.href='<?php echo U('Show/index', array('uid' => $vo['idmd5']));?>'">
	             	<div class="fl"><img src="<?php echo ((isset($vo["avatar"]) && ($vo["avatar"] !== ""))?($vo["avatar"]):'/Public/img/mrtx.jpg'); ?>" alt=""></div>
	             	<div class="fl" style="margin-top: 3px;">
	             	
		             	<?php if($vo['vip']['user_rank'] > 0): ?><p class="wodefenxi_a"><?php echo ((isset($vo["user_nicename"]) && ($vo["user_nicename"] !== ""))?($vo["user_nicename"]):'昵称未定义'); ?><span style="top: 6px;">VIP</span><i><?php echo ($vo["time"]); ?></i></p><!-- 这个是有vip的字体显示粉色 -->               
		              <?php else: ?>
		              	<p class="wodefenxi_a_a"><?php echo ((isset($vo["user_nicename"]) && ($vo["user_nicename"] !== ""))?($vo["user_nicename"]):'昵称未定义'); ?><i><?php echo ($vo["time"]); ?></i></p><!-- 这个是没有vip的字体显示黑色 --><?php endif; ?>
		               
		              <p class="wodefenxi_b"><?php echo ($vo["age"]); ?>岁 <?php echo ((isset($vo["province_name"]) && ($vo["province_name"] !== ""))?($vo["province_name"]):'未知'); echo ($vo["city_name"]); ?> <?php echo ($vo["astro"]); ?>              
			              <span>	              	
				              <?php if($vo['sex'] == 2): ?><img src="images/meilizhi_02.png" alt=""><i class="meili"><?php echo ((isset($vo["jifen"]) && ($vo["jifen"] !== ""))?($vo["jifen"]):'0'); ?></i>
				              <?php else: ?>
				              	<img src="images/caifuzhi.png" alt=""  class="caifu_img"><i class="caifu"><?php echo ((isset($vo["jifen"]) && ($vo["jifen"] !== ""))?($vo["jifen"]):'0'); ?></i><?php endif; ?>
			              </span>
		              </p>
		              <p class="wodefenxi_c"><?php echo ($vo["monolog"]); ?></p>
	            	</div>
	          	</li><?php endforeach; endif; endif; ?>
       	</ul>
      </div>
    </div>
	  <!--#include file="footer.html"-->
  </div>
</body>
   
<script>

var totalheight = 0;
				var body = '';			
				var main = $(".fujin"); //主体元素  
				var range = 10; //距下边界长度/单位px  
				var elemt = 500; //插入元素高度/单位px  
				var maxnum = 200; //设置加载最多次数  
				var num = 2;
				var jz = true;
	$(window).scroll(function() {
		if(jz==false) return false;
					var srollPos = $(window).scrollTop(); //滚动条距顶部距离(页面超出窗口的高度) 					
					totalheight = parseFloat($(window).height()) + parseFloat(srollPos);
					if (($(document).height() - range) <= totalheight && num != maxnum) {
						$("#loading").show();
						$.getJSON("<?php echo U('User/MySubscribe',array('type'=>$_GET['type']));?>", {
							p: num,
							ajax: 1,
						}, function(data) {
							$("#loading").hide();
							jz = false;
							if(!data) return false;
							$.each(data, function(index, val) {
								jz =true;
		/*
								 <li class="cb">
					             <div class="fl"><img src="<?php echo ((isset($vo["avatar"]) && ($vo["avatar"] !== ""))?($vo["avatar"]):'/Public/img/mrtx.jpg'); ?>" alt=""></div>
					             <div class="fl">
					               <p class="wodefenxi_a_a"><?php echo ((isset($vo["user_nicename"]) && ($vo["user_nicename"] !== ""))?($vo["user_nicename"]):'昵称未定义'); ?><i><?php echo ($vo["time"]); ?></i></p><!-- 这个是没有vip的字体显示黑色 -->
					               <p class="wodefenxi_b"><?php echo ($vo["age"]); ?>岁 <?php echo ((isset($vo["province_name"]) && ($vo["province_name"] !== ""))?($vo["province_name"]):'未知'); echo ($vo["city_name"]); ?> <?php echo ($vo["astro"]); ?><span>
					               <?php if($vo['sex'] == 2): ?><img src="images/meilizhi_02.png" alt=""><i class="meili"><?php echo ((isset($vo["jifen"]) && ($vo["jifen"] !== ""))?($vo["jifen"]):'0'); ?></i>
					               <?php else: ?>
					              <img src="images/caifuzhi.png" alt=""><i class="caifu"><?php echo ((isset($vo["jifen"]) && ($vo["jifen"] !== ""))?($vo["jifen"]):'0'); ?></i></span><?php endif; ?>
					               </span>
					               </p>
					               <p class="wodefenxi_c"><?php echo ($vo["monolog"]); ?></p>
					             </div>
					           </li>
		*/
		
		                        var avatar = val.avatar?val.avatar:'/Public/img/mrtx.jpg';
		                        var user_nicename = val.user_nicename?val.user_nicename:'昵称未定义';
								var province_name = val.province_name?val.province_name:'未知';
								if(val.user_rank>0){
								var	user_html = '<p class="wodefenxi_a">'+user_nicename+'<span>VIP</span><i>'+val.time+'</i></p>';
								}else{
								var	user_html = '<p class="wodefenxi_a_a">'+user_nicename+'<i>'+val.time+'</i></p>';
								}
								
								
								if(val.sex==2){
								 var jifen = '<span><img src="images/meilizhi_02.png"alt=""><i class="meili">'+val.jifen+'</i></span>';	
								}else{
								 var jifen = '<span><img src="images/caifuzhi.png"alt=""><i class="caifu">'+val.jifen+'</i></span>';	
								}								
								body = '<li class="cb"><div class="fl"><img src="'+avatar+'"></div><div class="fl">'+user_html+'<p class="wodefenxi_b">'+val.age+'岁'+province_name+val.city_name+val.astro+jifen+'</p><p class="wodefenxi_c">'+val.monolog+'</p></div></li>';
								main.append(body);							
															
							});
						}, 'json');
						num++;
					}
				});		


	
</script>


</html>