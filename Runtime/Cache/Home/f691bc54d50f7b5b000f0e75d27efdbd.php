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

<body >
  <div class="zhuce">
  <!--#include file="app_share.html"-->
     <div class="zhuce_a">
       <p><img src="images/yingdao_logo.png" alt=""><?php echo C("site_title");?></p>
     </div>
	 
	  <div class="zhuce_b" id="new_zhuce_b">
	    <p class="xuanzei">请正确选择您的年龄</p>
	    <span class="xuanzeinianl cb">
	    <ul>
	    	<li><a href="javascript:;">17</a></li>
	    	<li><a href="javascript:;">18</a></li>
	    	<li><a href="javascript:;">19</a></li>
	    	<li class="hot"><a href="javascript:;">20</a></li>
	    	<li><a href="javascript:;">21</a></li>
	    	<li><a href="javascript:;">22</a></li>
	    	<li><a href="javascript:;">23</a></li>
	    	<li><a href="javascript:;">24</a></li>
	    	<li><a href="javascript:;">25</a></li>
	    	<li><a href="javascript:;">26</a></li>
	    	<li><a href="javascript:;">27</a></li>
	    	<li><a href="javascript:;">28</a></li>
	    	<li><a href="javascript:;">29</a></li>
	    	<li><a href="javascript:;">30</a></li>
	    	<li><a href="javascript:;">31</a></li>
	    	<li><a href="javascript:;">32</a></li>
	    	<li><a href="javascript:;">33</a></li>
	    	<li><a href="javascript:;">34</a></li>
	    	<li><a href="javascript:;">35</a></li>
	    	<li><a href="javascript:;">36</a></li>
	    	<li><a href="javascript:;">37</a></li>
	    	<li><a href="javascript:;">38</a></li>
	    	<li><a href="javascript:;">39</a></li>
	    	<li><a href="javascript:;">40</a></li>
	    	<li><a href="javascript:;">41</a></li>
	    	<li><a href="javascript:;">42</a></li>
	    	<li><a href="javascript:;">43</a></li>
	    	<li><a href="javascript:;">44</a></li>
	    	<li><a href="javascript:;">45</a></li>
	    	<li><a href="javascript:;">46</a></li>
	    	<li><a href="javascript:;">47</a></li>
	    	<li><a href="javascript:;">48</a></li>
	    	<li><a href="javascript:;">49</a></li>
	    	<li><a href="javascript:;">50</a></li>
	    	<li><a href="javascript:;">51</a></li>
	    	<li><a href="javascript:;">52</a></li>
	    	<li><a href="javascript:;">53</a></li>
	    	<li><a href="javascript:;">54</a></li>
	    	<li><a href="javascript:;">55</a></li>
       </ul>

	    </span>
	    
        <a href="javascript:;" id="next01" >下一步</a>
        <br><br><br>
     </div>
	 
	 
     <div class="zhuce_b" style=" display: none">
        <div class="baikuang"><input type="text" id="mob" placeholder="请输入手机号" maxlength="11" ></div>
        <div class="baikuang"><input type="text" id="yzm" placeholder="请输入验证码" style="color:#999"> <input type="button" id="btn" class="btn_yzm_03 fr" value="获取验证码" /></div>
        <div class="baikuang"><input type="password" id="pass" placeholder="请输入6到16位密码"></div>
        <a href="javascript:;" id="ljreg" >立即注册</a>
        <br><br><br>
     </div>


<input  type="hidden" name="age" id="age" value="20"/>

<style>
#ljreg.ccc{ background: #ccc; border: 2px solid #ccc}	
</style>
</div>
<script type="text/javascript">
	var pimuheight = $(window).height();
    $(".zhuce").css('height',pimuheight);
</script>
<script type="text/javascript">


var wait=60;
document.getElementById("btn").disabled = false;   
function time(o) {
        if (wait == 0) {
            o.removeAttribute("disabled");           
            o.value="获取验证码";
            wait = 60;
        } else {
            o.setAttribute("disabled", true);
            o.value="重新发送(" + wait + ")";
            wait--;
            setTimeout(function() {
                time(o)
            },
            1000)
        }
    }
document.getElementById("btn").onclick=function(){
		var o = this;
	$.post("<?php echo U('Ajax/getvcodebymob');?>",{mob:$("#mob").val()},function(data){
		if(data.status==1){
			time(o);
		}else{
			alert(data.msg);
		}
	},'json')
}

$(function(){
	$("#ljreg").click(function(){
		if($(this).hasClass('ccc')){			
			return false;
		}
		
		var sex = '<?php echo ($sex); ?>';
		var age = $("#age").val();
		var yzm = $("#yzm").val();		
		var pass = $("#pass").val();
		var mob = $("#mob").val();
		
		if(!mob){
			alert('请填写手机号码');
			return false;
		}
		
		if(!yzm){
			alert('请输入验证码');
			return false;
		}
		
		if(!pass){
			alert('请填写密码');
			return false;
		}
		$(this).addClass('ccc').html('提交中，请稍后……');
		$.post("<?php echo U('Ajax/reg');?>",{mob:mob,yzm:yzm,pass:pass,age:age,sex:sex},function(data){			
			if(data.status==1){
			window.location.href='<?php echo U("Index/index");?>';	
			}	
			else{
			$("#ljreg").removeClass('ccc').html('立即注册');	
			alert(data.info);
			}
		},'json');		
	});
		
	$("#next01").click(function(){
		$(".zhuce_b").toggle();	
		document.getElementById("mob").focus()	
	});

  $(".xuanzeinianl ul li").click(function(){
       var index = $(this).index();
	   var ageval = $(this).find("a").text();
	   $("#age").val(ageval);
   $(".xuanzeinianl ul li").removeClass("hot").eq(index).addClass("hot");


  })
})




</script>

</body>

</html>