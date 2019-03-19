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
<title>账号设置</title>
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
     <div class="fujinderen_header">
         账号设置
         <div class="fl" id="ddd"><a href="<?php echo U('Home/User/index');?>" style="color: white;"><img src="images/zuojian.png"  style="width:11px;">返回</a></div>
         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      </div>

      <div class="zhanghaosheizhi">
      	<ul>
      		<li><a href="<?php echo U('Home/User/changepwd');?>" class="cb"><span class="fl">修改密码</span> <span class="fr"><img src="images/youjianhei.png"></span></a></li>
      		<li><a href="<?php echo U('Home/User/bind');?>" class="cb"><span class="fl">登录绑定</span> <span class="fr"><img src="images/youjianhei.png"></span></a></li>
      		<li style="display:none"><a href="<?php echo U('Home/User/ShippingAddress');?>" class="cb"><span class="fl">收货地址</span> <span class="fr"><img src="images/youjianhei.png"></span></a></li>
      		<li><a href="<?php echo U('Home/Public/dologout');?>" class="cb"><span class="fl">账号注销</span> <span class="fr"><img src="images/youjianhei.png"></span></a></li>
      	</ul>
      </div>
      
	   	<!--#include file="footer.html"-->   
  </div>


</body>

</html>