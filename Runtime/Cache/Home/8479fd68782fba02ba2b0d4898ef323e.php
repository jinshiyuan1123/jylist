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
              position: fixed;
              top: 0px;
                }</style>

	<body>
	

		<div class="hd_main">
		<div style="height:40px;display:none ;" class="appdiv fx_header_new"></div>
	<!--#include file="app_share.html"-->
			<!--#include file="index_paihang_header.html"--> 
			<div class="paihang_new">
				<ul>				
					<!--#include file="ajax_charmlist.html"-->					
				</ul>
				<input type="hidden" id="superman" value="<?php echo ($type); ?>"/>
			</div>
			<!--#include file="footer.html"-->
		</div>
		
		<script type="text/javascript">
    
			var totalheight = 0; //总高
			var body = '';	//
			var main = $(".hd_main div ul"); //主体元素  
			var range = 50; //距下边界长度/单位px  
			var elemt = 500; //插入元素高度/单位px  
			var maxnum = 200; //设置加载最多次数  
			var num = 2;
			var jz = true;
			var type = $('#superman').val();		
			$(window).scroll(function() {
				if(jz==false) return false;			
				var srollPos = $(window).scrollTop(); //滚动条距顶部距离(页面超出窗口的高度) 	
				totalheight = parseFloat($(window).height()) + parseFloat(srollPos); //滚动条的高度  + 滚动条距离顶部的高度
				if (($(document).height() - range) <= totalheight && num != maxnum) {
					$("#loading").show();
					$.getJSON("<?php echo U('Home/Ranking/Charmlist');?>", {
						p: num,
						ajax: 1,
						type:type,
					}, function(data) {
						$("#loading").hide();
						jz = false;
						if(!data) return false;
						main.append(data);
						jz = true;
					}, 'json');
					num++;
				}
			});	
		</script>
	</body>
</html>