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
                }
  </style>

<body>

  
  <div class="hd_main">
  <div style="height:40px;display:none ;" class="appdiv fx_header_new"></div>
  <!--#include file="app_share.html"-->
      <div class="siliao_header">
               设置头像
         <a  href="<?php echo U('User/Index');?>" class="fl" id="ddd"><img src="images/zuojian.png"  style="width:11px;">返回</a>
         &nbsp;&nbsp;&nbsp;&nbsp;
         
         <a href="javascript:fileuploadshow();" class="fr wodexiangce_shizhi"><img src="images/jia.png"></a>
            <input id="fileupload" type="file" name="files[]" style="display:none;">
             
      </div>
      <div class="index_lise" id="container">
      <!--#include file="ajax_AvatarList.html"--> 
      
	   
      </div>


      <script type="text/javascript">
      //设置图片正方形
      var img_width  = $(".w_dengyu_h img").width();
      var img_higdt = img_width;
      $(".w_dengyu_h img").css("height",img_higdt)

      var apcshikuangdu =$(".hd_main").width();//获取显示宽度
          var ulliwidth = $(".index_lise .box").width();// 获取ul li宽度
          var sangzhi = ulliwidth*3; // 获取ul li 一排的宽度
          var kongzhi = apcshikuangdu - sangzhi;  // 显示宽度减去一排ulli的宽度
          var yigzhi =kongzhi/4;// 剩下的宽度除以4
          $(".index_lise .box").css('padding-left',yigzhi)// 赋予ulli剩下的宽度
      </script>
      
	  


	  <div class="heipin heipin1"></div>
       <!-- div class="wodexiangce_shangchun" style="height: 135px;background: #e9e9e9;">
         <p class="simi_xc ct">设置新头像</p>
         <div class="touxiang_kk">
           <div><input type="button" class="input_button_touxiang_kk" value="选择手机中的照片"></div>
         </div>
         <div class="input_button_touxiang_kk_aa">
            <a href="javascript:;" class="guangbi_be">取消</a>

         </div>

       </div-->
    
<script type="text/javascript">
    
    
      var totalheight = 0;
		var body = '';			
		var main = $(".index_lise"); //主体元素  
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
				$.getJSON("<?php echo U();?>", {
					p: num,
					ajax: 1,
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
  </div>
 <script src="/upimg/js/vendor/jquery.ui.widget.js"></script>
	<script src="/upimg/js/jquery.iframe-transport.js"></script>
	<script src="/upimg/js/jquery.fileupload.js"></script>
	<script>
		$(function () {
    	'use strict';
   		 var  url = "<?php echo U('Home/Form/upload');?>";
   	 	$('#fileupload').fileupload({
        url: url,
        dataType: 'json',
        done: function (e, data) {
           if(data.result.status==1){
        	
        	 upAvatar(data.result.data.original,data.result.data.url)
   
           
           }else{
        	   alertmsg(data.result.info);
           }
        },
        progressall: function (e, data) {
          var progress = parseInt(data.loaded / data.total * 100, 10);
          $('#progress .progress-bar').css('width',progress + '%');
        }
    		}).prop('disabled', !$.support.fileInput).parent().addClass($.support.fileInput ? undefined : 'disabled');			
		
		});
		
		 function fileuploadshow(){
			
             $("#fileupload").click();
           }
		
	    function upAvatar(image,thumb_image){
	    	$.post("<?php echo U('UpAvatar');?>",{image:image,thumb_image:thumb_image},function(data){
	    		if(data.status==1){
	    			window.location.href="index.php?s=/Home/User/saveAvatar/photoid/"+data.info+".html";
	    		}else{
	    			alertmsg(data.info);
	    		}
	    		
	    	},'json');
	    	
	    	
	    }	



		
	</script>



</body>   
</html>