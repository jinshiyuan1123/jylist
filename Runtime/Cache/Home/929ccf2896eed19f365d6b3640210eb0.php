<?php if (!defined('THINK_PATH')) exit(); if(C('open_lxzizhu_app')) {?>
<!-- 分享header -->
			  <div class="fx_header appdiv fx_header_new"  style="display:<?php if($_COOKIE['inapp']!=1) echo 'none';?> ;">
			  	<div  class="fx_header_div cb ">
			  		 <a href="/"  class="fx_header_logo fl" ><?php echo $config[site_title];?></a> 
			  		 <span class="fr fx_header_sandian"><img src="images/sandian.png">
                          <div class="fx_header_xiala" style="display:none">
                          <ul>
                          	<li onclick="appsarewx('SESSION','<?php echo $config[site_title];?>','<?php echo $config[site_subtitle];?>');"><img src="images/fx_wx.png">分享给朋友</li>
                          	<li onclick="appsarewx('TIMELINE');"><img src="images/fx_pyq.png">分享到朋友圈</li>
                          	<li onclick="shareweibo('<?php echo $config[site_subtitle];?>');"><img src="images/fx_xl.png">分享到微博</li>
                          	<li onclick="shareqqzone('0','<?php echo $config[site_title];?>','<?php echo $config[site_subtitle];?>');"><img src="images/fx_qq.png">分享到QQ</li>
                          	<li onclick="shareqqzone('1','<?php echo $config[site_title];?>','<?php echo $config[site_subtitle];?>');"><img src="images/fx_qqkj.png">分享到QQ空间</li>
                          </ul>
                          </div>
			  		 </span>
			  	</div>
			  </div>
			  
			  <script type="text/javascript" src="/cordova.js"></script>	
			  <script type="text/javascript">
			  
                  $(".fx_header_sandian").click(function(){
                  	$(".fx_header_xiala").slideToggle(100);
                  })
				  
				  
document.addEventListener("deviceready", function() {
	$(".appdiv").show();
	setCookie1('inapp',1);	
window.plugins.jPushPlugin.init();
window.plugins.jPushPlugin.getRegistrationID(function(id){
	//alert(id);
});
			
});
	

function setCookie1(name,value)
{
var Days = 30;
var exp = new Date();
exp.setTime(exp.getTime() + Days*24*60*60*1000);
document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString();
}
				
document.addEventListener("backbutton", this.BackKeyDownListener, false);
function BackKeyDownListener(){
	window.history.go(-1);
}



function shareweibo(desc){
	 navigator.Weibo.shareToWeibo(success, error, {
            "title" : desc,
            "url" : window.location.href,
            "imageUrl" : 'http://' + window.location.host +"/Public/img/logo.jpg"
        });
}

function shareqqzone(type,title,desc){
	var args = {};
    args.url = window.location.href;
    args.title = title?title:document.title,
    args.description = desc?desc:document.title,   
    args.appName = "share";
 	if(type==1){
		 args.imageUrl = ['http://' + window.location.host +"/Public/img/logo.jpg"];
		 navigator.QQ.shareToQzone(success, error, args);
	}else{
		 args.imageUrl = 'http://' + window.location.host +"/Public/img/logo.jpg";
		 navigator.QQ.shareToQQ(success, error, args);
	}
	
}


function appsarewx(type,title,desc,imgurl){
	switch(type){
		case "SESSION":
		var type2 = navigator.weixin.Scene.SESSION
		break;
		case "TIMELINE":
		var type2 = navigator.weixin.Scene.TIMELINE
		break;
	}
		 navigator.weixin.share({
        message : {
            title : title?title:document.title,
            description : desc?desc:document.title,
            mediaTagName : "Media Tag Name(optional)",
            thumb : imgurl?imgurl:'http://' + window.location.host +"/Public/img/logo.jpg",
            media : {
                // type: weixin.Type.WEBPAGE, // webpage
                webpageUrl : window.location.href
            // webpage
            }
        },
        scene :type2
    }, success, error);		
	}
  function success(result) {
      // alert(JSON.stringify(result));
    }
    function error(result) {
       // alert(JSON.stringify(result));
    }


 var ua = navigator.userAgent.toLowerCase(); 
  if (/iphone|ipad|ipod/.test(ua)) {
        $(".fx_header_new").css('padding-top',15);
  } 
             </script>
             <!-- 分享header -->
			 	<?php } ?>