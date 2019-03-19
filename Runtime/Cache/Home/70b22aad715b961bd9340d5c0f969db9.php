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
<link rel="stylesheet" type="text/css" href="css/css.css?11">
<script type="text/javascript" src="js/jquery-1.8.0.min.js"></script>
</head>
<style type="text/css">
	.fx_header {
		position: fixed;
		top: 0px;
	}
</style>
<body>
  <div class="hd_main" style="background: #fff;">
  <div style="height:40px;display:none ;" class="appdiv fx_header_new"></div>
  <!--#include file="app_share.html"-->
      <div class="fujinderen_header cb" style="overflow: hidden;">
         充值中心
         <a  href="javascript:history.go(-1);" class="fl" id="ddd"><img src="images/zuojian.png"  style="width:11px;">返回</a>
         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      </div>
      <div class="chongzhizhongxin">
      	<div class="fl"><a href="<?php echo U('VipCenter');?>" <?php if(($type) == "0"): ?>class="hot"<?php endif; ?> >购买VIP会员</a></div>
      	<div class="fl"><a href="<?php echo U('VipCenter',array('type'=>1));?>" <?php if(($type) == "1"): ?>class="hot"<?php endif; ?>>充值<?php echo ($config["money_name"]); ?></a></div>
      </div>
        <?php if($type == 1): ?><div class="chongzhizhongxin_a cb">
         <div class="fl touxiang_new"><img src="<?php echo ((isset($info["avatar"]) && ($info["avatar"] !== ""))?($info["avatar"]):'/Public/img/mrtx.jpg'); ?>" alt=""></div>
         
         <?php if($info['user_rank'] > 0): ?><div class="fl huiyuan_vip_new"><?php echo ((isset($info["user_nicename"]) && ($info["user_nicename"] !== ""))?($info["user_nicename"]):'昵称未填写'); ?><span>VIP</span></div>
         <?php else: ?> 
          <div class="fl huiyuan_new_vip"><?php echo ((isset($uinfo["user_nicename"]) && ($uinfo["user_nicename"] !== ""))?($uinfo["user_nicename"]):'昵称未填写'); ?> </div><?php endif; ?>        
         <div class="fr chongzhizhongxin_vip"><?php echo ($config["money_name"]); ?>余额 :<span>&nbsp;<?php echo ($uinfo["money"]); ?>&nbsp;<img src="images/jinqian.png"></span></div>
      </div>
      <p class="xuznze">请选择<?php echo ($config["money_name"]); ?>充值额度（1元=<?php echo ($config["moneyBL"]); echo ($config["money_name"]); ?>）</p>
      <div class="xuznze2">
      	 <ul class="cb">
      	 	  <?php if(is_array($list)): foreach($list as $key=>$vo): ?><li  data="<?php echo ($vo["id"]); ?>" money="<?php echo ($vo["money"]); ?>" price="<?php echo ($vo['money']*$config['moneyBL']); ?>" zmoney="<?php echo ($vo["zmoney"]); ?>" <?php if($key == 0): ?>class="hot"<?php endif; ?> ><?php echo ($vo["money"]); ?>元</li><?php endforeach; endif; ?>
      
      	 </ul>
      </div>
      <div class="xuznze3">
    
         充值对应<?php echo ($list[0]['money']*$config['moneyBL']); echo ($config["money_name"]); if($list[0]['zmoney']): ?><span>(再赠送<?php echo ($list[0]['zmoney']); echo ($config["money_name"]); ?>)</span><?php endif; ?>
     <?php if(($iswx) == "1"): ?><a href="" class="fr zfyuanxin"><img src="images/wxzf.png"></a> 
         <?php else: ?>
          <a href="javascript:;" class="fr zfyuanxin"><img src="images/zfb.png"></a><?php endif; ?>
        
      </div>
      <p class="xuznze ts">本次充值金额<?php echo ($list[0]['money']); ?>元，对应<?php echo ($config["money_name"]); echo ($list[0]['money']*$config['moneyBL']+$list[0]['zmoney']); ?></p>
      <div class="xuznze4">
           <input type="button" class="input_button_new tj" dataValue="<?php echo ($list[0]['id']); ?>"  value="充值">
           <p  style="color: #f03b57">&nbsp;<?php echo ($config["money_name"]); ?>特权介绍</p>
		   <?php if(empty($config['buycoinintro'])): ?><p>&nbsp;私聊需要消耗<?php echo ($config["money_name"]); ?>，每条N元</p>
			   <p>&nbsp;会员通过<?php echo ($config["money_name"]); ?>可以查阅对方的私密照片</p>
			   <p>&nbsp;<?php echo ($config["money_name"]); ?>可购买商城礼物，送给对方表达爱意</p>
		   <?php else: ?>
			   <div>
				<?php echo ($config["buycoinintro"]); ?>
			   </div><?php endif; ?>
      </div>

	   <?php else: ?>
	   
       <div class="chongzhizhongxin_a cb">
         <div class="fl touxiang_new"><img src="<?php echo ((isset($info["avatar"]) && ($info["avatar"] !== ""))?($info["avatar"]):'/Public/img/mrtx.jpg'); ?>" alt=""></div>

            <?php if($info['user_rank'] > 0): ?><div class="fl huiyuan_vip_new"><?php echo ((isset($info["user_nicename"]) && ($info["user_nicename"] !== ""))?($info["user_nicename"]):'昵称未填写'); ?><span>VIP</span></div>
          <div class="fr chongzhizhongxin_vip">VIP特权余期 :<span><?php echo ($info["rank_time"]); ?> 天</span></div>
         <?php else: ?> 
          <div class="fl huiyuan_new_vip"><?php echo ((isset($uinfo["user_nicename"]) && ($uinfo["user_nicename"] !== ""))?($uinfo["user_nicename"]):'昵称未填写'); ?></div>
          <div class="fr chongzhizhongxin_vip">VIP特权待升级</span></div><?php endif; ?>      
        
      </div>
      <p class="xuznze">请选择购买VIP会员的天数</p>
        <div class="gai_vip cb">
          <ul>
          <?php if(is_array($list)): foreach($list as $key=>$vo): if($vo['day'] == 360): ?><li class="cb"><div class="fl"><img src="images/vip<?php echo ($vo["day"]); ?>.png"><p class="nian_ww">1年&nbsp;&nbsp; <?php echo ($vo["price"]); ?>元</p><p  class="nian_ee">92%用户的选择</p></div> <div class="left"><?php echo ($vo["zk"]); ?>折 <i class="yuanjia">原价¥<?php echo ($vo["original"]); ?></i></div><a href="javascript:;" dataValue="<?php echo ($vo["id"]); ?>"  class="fr shengji tj">升级</a></li>
          <?php else: ?>
           <li class="cb"><div class="fl"><img src="images/vip<?php echo ($vo["day"]); ?>.png"><?php echo ($vo["day"]); ?>天<?php echo ($vo["price"]); ?>元 </div> <div class="left"> <?php if(($vo["zk"]) != "10"): echo ($vo["zk"]); ?>折 <i class="yuanjia">原价¥<?php echo ($vo["original"]); ?></i><?php endif; ?> </div><a href="javascript:;" dataValue="<?php echo ($vo["id"]); ?>"  class="fr shengji tj">升级</a></li><?php endif; endforeach; endif; ?>
          </ul>
        </div>

      <div class="xuznze4"  style="margin-top: 15px;">
           <p style="color: #f03b57">&nbsp;VIP特权介绍</p>
		   <?php if(empty($config['buyvipintro'])): ?><p>&nbsp;VIP会员每天签到获更多<?php echo ($config["money_name"]); ?>(VIP+<?php echo ($config["vipqd"]); ?>)</p>
           <p>&nbsp;VIP会员商城购买礼物享受特权折扣价(VIP<?php echo ($config["vipgiftzhe"]); ?>折)</p>
           <p>&nbsp;VIP会员邀请好友享受更多<?php echo ($config["money_name"]); ?>返利(VIP+<?php echo ($config["vipyq"]); ?>)</p>
           <p>&nbsp;VIP会员可以查看会员联系方式</p>
           <p>&nbsp;VIP会员附近的人优先显示</p>
           <p>&nbsp;VIP会员聊天不限制(非VIP会员每天限聊1000条)</p>
		   <?php else: ?>
		   <div>
			<?php echo ($config["buyvipintro"]); ?>
		   </div><?php endif; ?>
      </div><?php endif; ?>
	   <!--#include file="footer.html"-->
	   
  </div>
  
  
  
  
  
    
<script type="text/javascript">
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

	 var jspay = '';
	$(function(){
		$('.tj').live('click',function(){
			var paytype ='<?php echo ($iswx); ?>';
			var type ='<?php echo ($type+1); ?>';
			var cid = $(this).attr('dataValue');
			if(paytype==1){
			 window.location.href = 'index.php?s=/Home/User/dopay/cid/'+cid+'/type/'+type;
			 return false;
			}
			  $.post('<?php echo U("dopay");?>',{cid:cid,type:type},function(data){
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
	})
	
	
	
</script>

</body>

</html>