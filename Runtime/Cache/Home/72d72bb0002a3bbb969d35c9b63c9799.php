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

  <img src="images/yingdao_logo.png" alt="" style="display:none">
  <div class="hd_main">
  <div style="height:40px;display:none ;" class="appdiv fx_header_new"></div>
<!--#include file="app_share.html"-->
  
  
     <!--#include file="index_header.html"-->


 <!-- 导航 -->
      <div class="index_nav cb" style="  overflow: hidden;">
       <ul class="cb">
	   <li style="width:50%"><a href="<?php echo U('hot');?>"><i></i>动态</a></li>
		<li><a href="<?php echo U('photo');?>"><i  class="hot"></i>相册</a></li>
          <!-- <li><a href="<?php echo U('index');?>"><i ></i>推荐</a></li>
           <li><a href="<?php echo U('photo');?>"><i class="hot"></i>相册</a></li>
         <li><a href="<?php echo U('Home/Ranking/Charmlist',array('type' => 4));?>"><i></i>排行</a></li>-->
       </ul>
      </div>
      <!-- 导航 -->



      <!-- 用户列表 -->
      <div class=" index_lise_new " id="container">
	  <?php if(is_array($list1)): foreach($list1 as $key=>$vo): ?><li  class="box">
        <a href="<?php echo ($vo["aurl"]); ?>"><img src="<?php echo ($vo["thumbfiles"]); ?>" alt=""></a>
         <div class="dibu_new">
		      <i class="fl"><img src="<?php echo ((isset($vo["avatar"]) && ($vo["avatar"] !== ""))?($vo["avatar"]):'/Public/img/mrtx.jpg'); ?>" alt=""><i><?php echo ($vo["user_nicename"]); ?></i></i>
		      <i class="fr" onclick="dozan(this,'<?php echo ($vo["idmd5"]); ?>');"><img src="images/zan.png" alt=""><br><span class="zan"><?php echo ($vo["hits"]); ?></span></i>
		      </div>
		      <div style="height:25px;"></div>
      </li><?php endforeach; endif; ?>
    
	  
	      
      </div>
     
      
	  
	  
	  
	  
	   <!--#include file="footer.html"-->



  </div>
  <script type="text/javascript">
          var apcshikuangdu =$(".hd_main").width();
          var ulliwidth = $(".index_lise_new  .box").width();
          var sangzhi = ulliwidth*2;
          var kongzhi = apcshikuangdu - sangzhi;
          var yigzhi =kongzhi/3;
          $(".index_lise_new .box").css('padding-left',yigzhi)
           var apcs =$(window).height();
          var apcs2 = apcs-44-48;
          $(".shuaxuan_dangchu").css('height',apcs2);
                 //setTimeout("ulliheight()",3000);
				 // function ulliheight(){
				 //    var ulliheight = $(".index_lise_new .box:nth-child(1)").height();//获取ulli的高度
				 //     $(".index_lise_new .box").css('height',ulliheight)
				 //  }
		</script>


<script>
var totalheight = 0;
				var body = '';			
				var main = $(".index_lise_new"); //主体元素  
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
						$.post("<?php echo U('Home/Index/photo');?>", {
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
							$.each(data, function(index, val) {
								jz =true;
								body = '<li  class="box"><a href="'+val.aurl+'"><img src="'+val.thumbfiles+'" alt=""></a><div class="dibu_new"><i class="fl"><img src="'+val.avatar+'" alt=""><i>'+val.user_nicename+'</i></i><i class="fr" onclick="dozan(this,\''+val.idmd5+'\');"><img src="images/zan1.png" alt=""><br><span class="zan">'+val.hits+'</span></i></div><div style="height:25px;"></div></li>';
								
								main.append(body);															
							});
							setTimeout('lxzizhufn()',500);
						}, 'json');
						num++;
					}
				});				


function lxzizhufn(){
var wrap = document.getElementById("container");
var margin = 0;//这里设置间距
var li=$(".box");//这里是区块名称
var li_W = li[0].offsetWidth+margin;//取区块的实际宽度（包含间距，这里使用源生的offsetWidth函数，不适用jQuery的width()函数是因为它不能取得实际宽度，例如元素内有pandding就不行了）
var h=[];
li.css("position","absolute");
var n = wrap.offsetWidth/li_W|0;//容器的宽度除以区块宽度就是一行能放几个区块
for(var i = 0;i < li.length;i++){
li_H = li[i].offsetHeight;
if(i<n)
{
h[i]=li_H;
li.eq(i).css("top",0);
li.eq(i).css("left",i * li_W);

}
else{
	min_H =Math.min.apply(null,h) ;//取得数组中的最小值，区块中高度值最小的那个
            minKey = getarraykey(h, min_H);//最小的值对应的指针
            h[minKey] += li_H+margin ;//加上新高度后更新高度值
            li.eq(i).css("top",min_H+margin);//先得到高度最小的Li，然后把接下来的li放到它的下面
            li.eq(i).css("left",minKey * li_W); //第i个li的左坐标就是i*li的宽度
            li.eq(i).css("padding-left",yigzhi);
// li.eq(i).css("height",ulliheight);
        }     
    }
}
/* 使用for in运算返回数组中某一值的对应项数(比如算出最小的高度值是数组里面的第几个) */
function getarraykey(s, v) {for(k in s) {if(s[k] == v) {return k;}}}
/*这里一定要用onload，因为图片不加载完就不知道高度值*/
window.onload = function() {lxzizhufn();};		
var ua = navigator.userAgent.toLowerCase(); 
  if (/iphone|ipad|ipod/.test(ua)) {
        $(".index_header").css('top',55);
  } else if (/android/.test(ua)) {
		    $(".index_header").css('top',40);
		}

			
</script>


</body>

</html>