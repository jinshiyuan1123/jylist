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
<script type="text/javascript" src="js/common.js"></script>
</head>
<style type="text/css">
   .fx_header {
              position: fixed;
              top: 0px;
                  max-width: inherit;
                }
</style>

<body onload="jiaodian()">
<div style="height:40px;display:none ;" class="appdiv fx_header_new"></div>
  <!--#include file="app_share.html"-->
 
  <div class=""  style="height:100%;     position: relative;">
  <div class="new_top" >
      <div class="siliao_header cb" style="    overflow: hidden;">
        <a href="<?php echo U('Show/index', array('uid' => $info['idmd5']));?>" style="color: #fff"><img src="<?php echo ((isset($info["avatar"]) && ($info["avatar"] !== ""))?($info["avatar"]):'/Public/img/mrtx.jpg'); ?>" alt="" class="siliaotouxian"><?php echo ($info["user_nicename"]); ?></a>
         <div class="fl" id="ddd" onclick="window.history.go(-1)"><img src="images/zuojian.png" alt="">返回</div>
         <a href="<?php echo U('Gift/index',array('uid'=>$_GET['uid']));?>"  class="fr"><img src="images/linwu.png" alt="">送礼</a>
      </div>



      <div class="siliao_xingxi">
        <span onclick="gz('<?php echo ($info["id"]); ?>');"><img src="images/siliao_a_06.png"  alt=""><ll class="gz"><?php if($User_subscribe==1) echo '已关注'; elseif($User_subscribe==2) echo '相互关注'; else echo '关注TA';?></ll></span>
        <span>余额 :<i id="yu1e"><?php if($uinfo["money"] > 1000000): ?>1000000+<?php else: echo ((isset($uinfo["money"]) && ($uinfo["money"] !== ""))?($uinfo["money"]):'0'); endif; ?></i> </span>
        <span><img src="images/siliao_a_09.gif" alt="">亲密度<?php echo ((isset($User_qmd) && ($User_qmd !== ""))?($User_qmd):"0"); ?></span>
        <span id="cha_wx">查看微信</span>
      </div>
      </div>


      <div style="height:85px;"></div>
	  
    
   
      <div class="liaotian" id="cscsc">
   
	   <?php if(is_array($msglist)): foreach($msglist as $key=>$vo): if($vo['msgid']%5==0) { ?>
         <span class="shijian"><?php echo (date("m-d H:i",$vo["sendtime"])); ?></span>
		  <?php } ?>	 
		 <?php if($vo['fromuid']==$uinfo['id']) { ?>		 
         <div class="right cb"><img src="<?php echo ((isset($uinfo["avatar"]) && ($uinfo["avatar"] !== ""))?($uinfo["avatar"]):'/Public/img/mrtx.jpg'); ?>" class="touxiang fr"><p class="fr"><?php echo ($vo["content"]); ?></p></div>
		<?php }else{ if($vo['type']==2){?>	
        	<div class="left cb"><img src="<?php echo $info['avatar']; ?>" class="touxiang fl" ><p class="fl"><img src="/themes/lxphp_dating/images/voice_chat_3.png" style="width:3rem;" class="mvideo" data-vid="<?php echo $vo['msgid']; ?>" onClick="theaudie('<?php echo $vo['content']; ?>','<?php echo $vo['msgid']; ?>')"></p></div> 
            <?php }else{ ?>
         <div class="left cb"><img src="<?php echo ((isset($info["avatar"]) && ($info["avatar"] !== ""))?($info["avatar"]):'/Public/img/mrtx.jpg'); ?>" class="touxiang fl" ><p class="fl"><?php echo ($vo["content"]); ?></p></div>
		 <?php }} endforeach; endif; ?>
      </div>
     
     <audio controls id="mmmmv" style="display:none">
      <source src="" id="mssv">
    </audio>
   








       <!-- 用户列表 -->
       <!-- 底部footer -->
       <div class="siliao_footer"> 
       	  <img src="/themes/lxphp_dating/images/voice_mic.png" style="width:6.5%;float:left; padding:0.2rem 0.6rem;" id="mmmic">
          <input type="text" value="" placeholder="说点什么..." id="content">
          <div><a href="javascript:sendmsg('<?php echo ($info["id"]); ?>');" id="tuisong"  >发送</a></div>   
       </div>
        <!-- 底部footer -->



 <!--收费提醒-->
        <div class="shoufe"  style="display:none">
          <div class="heipin" style="display:block"></div>
           <div class="qiandao_div" style="    top: 35%;"><div class=" cb"><a class="qiandao_guanbi"><img src="themes/lxphp_dating/images/xx.png"></a></div>
             <p class="sl_dangchuang">提示</p>
             <p class="sl_shoufei_tishi">与对方聊天需消耗<?php echo ($config["money_name"]); ?>，1条<?php echo ($config["lt_zc_money"]); echo ($config["money_name"]); ?></p>
             <span class="sl_xuanzhong"><i class="hot"></i>不再提示</span>
             <div class="sl_qr">
             <a href="javascript:;" class="sl_qr_qr">确认</a>
             </div>
           </div>
        </div>
 <!--收费提醒-->
 <!--充值提示-->
 <div class="lb_tishi" style="z-index:99; position:absolute; top:25%; width:100%; left:0; display:none">
 	<div class="heipin"></div>
    <div style="z-index:100; width:94%;position:fixed; background:#FFF; border-radius:0.3rem; left:3%;">
 	<p class="sl_dangchuang" style="margin-top:1rem;">&nbsp;&nbsp;&nbsp;&nbsp;提示<span style="float:right; margin-right:3%" id="lb_close">X</span></p>
    <p style=" font-size:1.1rem; color:#999; text-align:center" id="lb_tishi_p"></p>
 	<p style=" font-size:1.1rem; color:#999; text-align:center">请选择聊币充值额度（1元=1000聊币)</p><br>
 	<div class="xuznze2">
      	 <ul class="cb">
      	 	  <li data="13" money="48" price="48000" zmoney="3000" class="hot">48元</li><li data="12" money="98" price="98000" zmoney="6000">98元</li><li data="10" money="298" price="298000" zmoney="20000">298元</li> 
      	 </ul>
      </div>
     <div class="xuznze3" style="font-size:1.1rem;">
         充值对应48000聊币<span>(再赠送3000聊币)</span>               <a href="javascript:;" class="fr zfyuanxin"><img src="/themes/lxphp_dating/images/wxzf.png"></a>        
      </div>
      <div class="xuznze4">
           <input type="button" class="input_button_new tj" datavalue="13" value="充值" data-type="2">
	</div>
    <div>
 </div>
 </div>
 </div>
 <!--充值提示-->
 
 <!--充值提示-->
 <div class="cz_tishi" style="z-index:99; position:absolute; top:25%; width:100%; left:0;display:none">
 	<div class="heipin"></div>
    <div style="z-index:100; width:92%;position:fixed; background:#FFF; border-radius:0.3rem; left:4%;">
 	<p class="sl_dangchuang" style="margin-top:1rem;">&nbsp;&nbsp;&nbsp;&nbsp;提示<span style="float:right; margin-right:3%" id="cz_close">X</span></p>
 	<p style=" font-size:1.1rem; color:#999; text-align:center" id="cz_tishi_p">该功能充值成为VIP后可以使用</p><br>
 	<div class="gai_vip cb">
    	<ul>
                     <li class="cb"><div class="fl"><img src="/themes/lxphp_dating/images/vip90.png">90天49元 </div> <div class="left" style="margin-left:-1.5rem;font-size:1.2rem;"> 3.3折 <i class="yuanjia">原价¥150</i> </div><a href="javascript:;" datavalue="4" class="fr shengji tj" data-type="1">升级</a></li>           <li class="cb"><div class="fl"><img src="/themes/lxphp_dating/images/vip365.png">1年 99元 </div> <div class="left" style="margin-left:-1.5rem;font-size:1.2rem;"> 3.3折 <i class="yuanjia">原价¥300</i> </div><a href="javascript:;" datavalue="5" class="fr shengji tj" data-type="1">升级</a></li>           <li class="cb"><div class="fl"><img src="/themes/lxphp_dating/images/vip730.png"><div style="float:left"><p style="margin:0;margin-top:-0.4rem;">2年 299元</p><p style="font-size:1.2rem; color:#F90;margin:0;margin-top:-3.1rem;">90%用户选择</p></div></div> <div class="left" style="margin-left:-1.5rem;font-size:1.2rem;"> 3.3折 <i class="yuanjia">原价¥900</i> </div><a href="javascript:;" datavalue="6" class="fr shengji tj" data-type="1">升级</a></li>          </ul>
        </div>
    <div>
 </div>
 </div>
 </div>
 <!--充值提示-->
 <!--欠费提醒-->
 <div class="qianfe"  style="display:none">
          <div class="heipin" style="display:block"></div>
           <div class="qiandao_div" style="top: 35%;"><div class=" cb"><br></div>
             <p class="sl_dangchuang">提示</p>
             <p class="sl_shoufei_tishi1">你的金钱余额不足，请先充值</p>
             <br>
             <div class="sl_qf">
              <a class="sl_qf_yi">以后再说</a><a href="<?php echo U('User/VipCenter',array('type'=>1));?>" class="sl_qf_er">点击充值</a>
             </div>
           </div>
  </div>
 <!--欠费提醒-->


<script>
 var jishu=1;
 var reshtime = Number('<?php echo ($config["reshtime"]); ?>');
 ltnoallow();
 $(".sl_xuanzhong i").click(function(){
    if(jishu>0){
      $(".sl_xuanzhong i").removeClass("hot")
      jishu--
    }else{
      $(".sl_xuanzhong i").addClass("hot")
    jishu++
    }
 })
 $(".qiandao_guanbi,.sl_qr_qr").click(function(){
    $(".shoufe").hide();
	setCookie('sl_qr_qr',1,'d360');
 })
 $(".sl_qf_yi").click(function(){
    $(".qianfe").hide();
 })
 $('#cz_close').click(function(){
	 $('.cz_tishi').hide();
	 })
$('#lb_close').click(function(){
	 $('.lb_tishi').hide();
	 })
 $('#cha_wx').click(function(){
	 $('#cz_tishi_p').text('亲,普通用户无法查看微信，请升级VIP无限查看。');
	 $('.cz_tishi').show();
	 })
 $('#mmmic').click(function(){
	 $('#cz_tishi_p').text('亲,普通用户无法语音聊天，请升级VIP无限畅聊。');
	 $('.cz_tishi').show();
	 })
 $('.tj').live('click',function(){
			var paytype ='<?php echo ($iswx); ?>';
			var type =$(this).data('type');
			var cid = $(this).attr('dataValue');
			if(paytype==1){
			 window.location.href = 'index.php?s=/Home/User/dopay/cid/'+cid+'/type/'+type;
			 return false;
			}
			  $.post('<?php echo U("User/dopay");?>',{cid:cid,type:type},function(data){
				  if(data.status !=1 ){
					  alertmsg(data.info);
				  }else{
					  if(paytype == 1){
						  jspay = data.info;						
					  }else{
						  window.location.href = data.info; 
					  }
					  
					 
				  }
				  
			  },'json')
			
		})
$(".xuznze2 ul li").click(function(){
		var index = $(this).index();
		$(".xuznze2 ul li").removeClass("hot").eq(index).addClass("hot"); 
		var data = $(this).attr('data');
	    var price = $(this).attr('price');
		var zmoney= $(this).attr('zmoney');
		var money= $(this).attr('money');
		var total = parseInt(price)+parseInt(zmoney);
		var name = '<?php echo ($config["money_name"]); ?>';
		<?php if(($iswx) == "1"): ?>var zf = '<a href="" class="fr zfyuanxin"><img src="images/wxzf.png"></a> ';
         <?php else: ?>
         var zf = '<a href="javascript:;" class="fr zfyuanxin"><img src="images/zfb.png"></a>'  ;<?php endif; ?>
		
		var zs = zmoney?'<span>(再赠送'+zmoney+name+')</span>':'';
		var html1=' 充值对应'+price+name+zs+zf;
		$('.xuznze3').html(html1);
		var html2 ='本次充值金额'+money+'元，对应'+name+total;
		$('.ts').text(html2);
		$('.tj').attr('dataValue',data);
	})
function theaudie(op,th){
	$('img[data-vid="'+th+'"]').attr('src','/themes/lxphp_dating/images/voice_gif.gif')
	$('#mssv').attr('src',op);
	$('#mmmmv').load();
	var mmmmv=document.getElementById("mmmmv");	
	mmmmv.play();
	setvoiceend(th)
	}
function setvoiceend(th){
	if(document.getElementById("mmmmv").ended){
		$('img[data-vid="'+th+'"]').attr('src','/themes/lxphp_dating/images/voice_chat_3.png')
		return false;
		}
	setTimeout(function(){
		setvoiceend(th)
		},1000)
	}
function ltnoallow(){
var lahei = '<?php echo ($lahei); ?>';
if(lahei==1){
	alertmsg('对方已经将您拉黑，无法继续聊天！');
	return false;
}

var viltren = '<?php echo ($viltren); ?>';
 if(viltren==1){
 	$(".sl_shoufei_tishi1").html("您不是VIP，聊天人数超限，购买VIP后无限制。");
 	$(".sl_qf_er").html("去购买");
 	$(".sl_qf_er").attr('href',"<?php echo U('User/VipCenter',array('type'=>0));?>");
 	 $(".qianfe").show();
	  return false;
 }	
 return true;
} 
 

function get_lt_list(touid){
		$.post("<?php echo U('Home/Ajax/get_lt_list');?>",{touid:touid},function(data){
		if(data.status==1){
			$.each(data.info,function(index,val){
				if(val.type==2){
					$(".liaotian").append('<div class="left cb"><img src="<?php echo ((isset($info["avatar"]) && ($info["avatar"] !== ""))?($info["avatar"]):"/Public/img/mrtx.jpg"); ?>" class="touxiang fl"><p class="fl"><img src="/themes/lxphp_dating/images/voice_chat_3.png" style="width:3rem;" data-vid="'+val.msgid+'" class="mvideo" onClick="theaudie(\''+val.content+'\',\''+val.msgid+'\')"></p></div>');
					}else{
					$(".liaotian").append('<div class="left cb"><img src="<?php echo ((isset($info["avatar"]) && ($info["avatar"] !== ""))?($info["avatar"]):"/Public/img/mrtx.jpg"); ?>" class="touxiang fl"><p class="fl">'+val.content+'</p></div>');	
						}	
			})
			test();						
			clearInterval(remsg);
			remsg = setInterval("get_lt_list('"+touid+"')",(reshtime+10)*1000);
		}
		},'json')
}

function gz(touid){
  	$.post("<?php echo U('Home/Ajax/guanzhu');?>",{touid:touid},function(data){
		if(data.status==1){
			$(".gz").html(data.info);
		}else if(data.status==-1){
			$(".sl_shoufei_tishi1").html(data.info);
 	$(".sl_qf_er").html("去完善");
 	$(".sl_qf_er").attr('href',data.url);
 	 $(".qianfe").show();
		}
	},'json')
  }
  
  var remsg = '';
  var sending = false;
  function sendmsg(touid){
  	if(ltnoallow()===false) return;
  	var content = $("#content").val();
	if(content==""){
		alertmsg("请输入要提交的文字。");
		return false;
	}
  	if(sending) return false;
	sending = true;	
  	$.post("<?php echo U('Home/Ajax/sendmsg');?>",{touid:touid,content:content},function(data){
		sending = false;
		if(data.status==1){
			$("#content").val('');
			$(".liaotian").append('<div class="right cb"><img src="<?php echo ((isset($uinfo["avatar"]) && ($uinfo["avatar"] !== ""))?($uinfo["avatar"]):"/Public/img/mrtx.jpg"); ?>" class="touxiang fr"><p class="fr">'+content+'</p></div>');
			//window.location.reload();						
			remsg = setInterval("get_lt_list('"+touid+"')",reshtime*1000);			
			if(data.url>1000000){
				$("#yu1e").html('1000000+');
			}else{				
				$("#yu1e").html(data.url);	
			}

      test();
		}else if(data.status==-1){
			$(".sl_shoufei_tishi1").html(data.info);
 	$(".sl_qf_er").html("去完善");
 	$(".sl_qf_er").attr('href',data.url);
 	 $(".qianfe").show();
		}else{
			if(data.url==-1){
				$('#lb_tishi_p').text('亲,您的聊币不足,请充值');
	 			$('.lb_tishi').show();
				//$(".qianfe").show();
			}else{
				alertmsg(data.info,900000000);
			}			
		}
	},'json')
  }  
  
  
  
function test(){
  var limiangao = $("#cscsc div");
  var divgao = 0;
  var spangao = 0;
    $.each(limiangao, function(index, val) {
      divgao += parseInt ($(this).height()+20) ;
    })
 var limiangaospan = $("#cscsc span");
    $.each(limiangaospan, function(index, val) {
      spangao += parseInt ($(this).height()+20) ;
    })
 var cscsczonggao = divgao+spangao;
 document.getElementById("cscsc").scrollTop=cscsczonggao; 
 var content = $("#content").val();
  if(content==""){
    $(".siliao_footer>div a").removeClass("hot");
  }
 
}
function jiaodian(){
  var limiangao = $("#cscsc div");
  var divgao = 0;
  var spangao = 0;
      $.each(limiangao, function(index, val) {
      divgao += parseInt ($(this).height()+20) ;
    })
 var limiangaospan = $("#cscsc span");
    $.each(limiangaospan, function(index, val) {
      spangao += parseInt ($(this).height()+20) ;
    })
 var cscsczonggao = divgao+spangao;
 document.getElementById("cscsc").scrollTop=cscsczonggao; 
  $("#content").focus()
}  
  
  

<?php if($uinfo["sex"] == 1): ?>if(getCookie('sl_qr_qr')!=1){
	$(".shoufe").show();
}<?php endif; ?>
	


$("#content").focus(function(){ 
   $(".siliao_footer>div a").addClass("hot");
  clearInterval(remsg);
  });
$("#content").blur(function(){ 
  var content = $("#content").val();
  if(content==""){
    $(".siliao_footer>div a").removeClass("hot");
  }
});


var apcs =$(window).height();
  var apcs2 = apcs-85-50;
  $("#cscsc").css('height',apcs2)



function getCookie(name)
{
var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
if(arr=document.cookie.match(reg))
return unescape(arr[2]);
else
return null;
}

function delCookie(name)
{
var exp = new Date();
exp.setTime(exp.getTime() - 1);
var cval=getCookie(name);
if(cval!=null)
document.cookie= name + "="+cval+";expires="+exp.toGMTString();
}


function setCookie(name,value,time)
{
var strsec = getsec(time);
var exp = new Date();
exp.setTime(exp.getTime() + strsec*1);
document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString();
}

function getsec(str)
{
var str1=str.substring(1,str.length)*1;
var str2=str.substring(0,1);
if (str2=="s")
{
return str1*1000;
}
else if (str2=="h")
{
return str1*60*60*1000;
}
else if (str2=="d")
{
return str1*24*60*60*1000;
}
}




</script> 
  </div>



</body>

</html>