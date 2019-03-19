function setDisabled(o,val){
	o.attr('disabled','disabled');
	o.val(val);	
}

function unDisabled(o,val){
	o.removeAttr("disabled");
	o.attr("readOnly","true");
	o.val(val);	
}

//验证130-139,150-159,180-189号码段的手机号码
function isDigit(s)
{
	   var myreg = /^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1}))+\d{8})$/; 
	   if(!myreg.test(s)) 
	   { 
	       return false; 
	   }
	   return true;
} 

/** 
 * 检查字符串是否为合法QQ号码 
 * @param {String} 字符串 
 * @return {bool} 是否为合法QQ号码 
 */  
 function isQQ(aQQ) {  
     var bValidate = RegExp(/^[1-9][0-9]{4,9}$/).test(aQQ);  
     if (bValidate) {  
         return true;  
     }  
     else  
         return false;  
 }  
 
 /**
  * 提示alert
  */
 
 function alertmsg(msg,times){
 	if(!times) times=3000;
	 var html ='<div class="cehngsongcg">'+msg+'</div>';
	 $('body').append(html);
	  var kuang = $(".cehngsongcg").width();
	  var chuyier = kuang+30;
	  var chuyier2 = chuyier/2;
      $(".cehngsongcg").css('width',kuang)
	  $(".cehngsongcg").css('margin-left',-chuyier2)
	 setTimeout(function(){
		 $(".cehngsongcg").fadeTo("slow", 0.01, function(){//fade
		 $('.cehngsongcg').slideUp("slow", function() {//slide up
		 $('.cehngsongcg').remove();
		 })
		 })
	 },times);
	 return false;
	// alert(html);
	 
 }

 
 
function getLocation()
  {
  if (navigator.geolocation)
    {
    navigator.geolocation.getCurrentPosition(showPosition);
    }
  else{
  	alert("该浏览器不支持获取地理位置。");
	}
  }
function showPosition(position)
  {  
  	postdata(position.coords.latitude,position.coords.longitude);
 
  }
  
  
 
 
 function alertbox(msg,title,url){
 	if(!msg) return false;
 	if(title&&url){
		$("#newconfrim").show();
		$("#newconfrimmsg").html(msg);
		$("#caozuobutton").html(title);
		$("#caozuobutton").attr('href',url);
	}else{
		$("#newalert").show();
		$("#newalertmsg").html(msg);
	}
 }

 function zhuanwu(){
 	var zhuanwu ='<p class="zhuanwu_weizi">'+'暂无记录 . . .'+'</p>';
 	$(".zanwu").append(zhuanwu);
 }

 