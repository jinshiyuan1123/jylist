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
<title><?php echo ($media["title"]); ?></title>
<link rel="stylesheet" type="text/css" href="css/css.css">
<script type="text/javascript" src="js/jquery-1.8.0.min.js"></script>
</head>
<style type="text/css">
	.fx_header {
              position: fixed; top: 0px;
                }
</style>

<body>

  
  <div class="hd_main">
  <div style="height:40px;display:none ;" class="appdiv fx_header_new"></div>
   <!--#include file="app_share.html"-->
    
	
			<!--#include file="index_header.html"-->
	
			
		<div class="index_nav cb" style="  overflow: hidden;">
				<ul class="cb">
				<li style="width:50%"><a href="<?php echo U('hot');?>"><i class="hot"></i>动态</a></li>
				<li><a href="<?php echo U('photo');?>"><i></i>相册</a></li>
				<!--<li style="width:50%"><a href="<?php echo U('Home/Nearby/index');?>"><i></i>附近</a></li>		-->			
				</ul>
			</div>


       <div class="dongtai">

        <!--#include file="ajax_index_dongtai.html"-->



       </div>





<script>

     //设置图片正方形
      var img_width  = $(".w_dengyu_h img").width();
      var img_higdt = img_width;
      //$(".w_dengyu_h img").css("height",img_higdt)
      


var totalheight = 0;
				var body = '';			
				var main = $(".dongtai"); //主体元素  
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
						$.post("<?php echo U('');?>", {
							p: num,
							ajax: 1,
							type:'tj',
							provinceid:$("#provinceid").val(),
							cityid:$("#cityid").val(),
							sex:$("#sex").val(),
							age:$("#age").val()
						}, function(data) {
							$("#loading").hide();
							jz = false;
							if(!data) return false;
							main.append(data);						
							jz =true;										
						}, 'json');
						
						num++;
					}
				});	

/*
function imgclass(){
	$(".tupian_er").each(function(){
		var imgurl = $(this).attr('src');
		if($(this).hasClass('check')) return false;
		getImageWidth(imgurl,function(w,h,o){
		if(w>h) $(o).removeClass('tupian_er').addClass('check tupian_yi');
		else
		$(o).addClass('check');
		},this);
		
	})
}


function getImageWidth(url,callback,o){
	var img = new Image();
	img.src = url;
	if(img.complete){
	    callback(img.width, img.height,o);
	}else{         
	    img.onload = function(){
		callback(img.width, img.height,o);
	    }
        }
	
}
imgclass();	
*/						
</script>



<!--#include file="footer.html"-->









      
	      
  </div>

</body>

</html>