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
		<link rel="stylesheet" type="text/css" href="css/css.css?222">
		<script type="text/javascript" src="js/jquery-1.8.0.min.js"></script>
	</head>
	<style>
			#sendgift.ccc {
				background: #ccc
			}
			 .fx_header {
              position: fixed;
              top: 0px;
                }
		</style>

	<body>
	

		<div class="hd_main" style="background: #fff;">
		<div style="height:40px;display:none ;" class="appdiv fx_header_new"></div>
	<!--#include file="app_share.html"-->
			<div class="fujinderen_header">
				虚拟商城
				<a href="javascript:history.go(-1);" class="fl" id="ddd"><img src="images/zuojian.png" style="width:11px;">返回</a>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			</div>
			
			<div class="shangchengheader">
				<div class="fl">我的<?php echo ($config["money_name"]); ?>余额 : <span id="money"><?php echo ($uinfo["money"]); ?></span> <img src="images/shangcheng.png" alt=""></div>
				<a href="<?php echo U('User/VipCenter',array('type'=>1));?>" class="fr">立即充值</a>
			</div>
			
			<div class="shuanxuan cb">
				<span class="hot" data='-1'>全部</span><span data='0,1000'>1千以下</span><span data='1000,10000'>1千-1万</span><span data='10000,100000'>1万-10万</span><span data='100000,1000000'>10万-100万</span>
				<span data='1000000,0'>100万以上</span>
			</div>

			

			<div class="shangcheng">
				<ul class="cb">
					<!--#include file="ajax_gift_shop.html"-->
				</ul>
			</div>
			<br><br><br><br>
			

			<div class="lxzizhu_shop_dc" style="display:none ">
				<div class="lxzizhu_shop_hei"></div>
				<div class="lxzizhu_shop_div">
					<div class=" cb">
						<a class="qiandao_guanbi"><img src="images/xx.png"></a>
					</div>
					<div class="shangpi">
						<img id="img" src="/Public/img/no_picture.gif" alt="">
						<p id="giftname">加载中</p>
						<p><span id="price">加载中</span><?php echo ($config["money_name"]); ?></p>
						<p style="padding-top: 3px;">您的价格:<span id="myprice">0</span><?php echo ($config["money_name"]); ?></p>
						<p style="padding-top: 3px;">VIP特权价:<span id="pricevip">0</span><?php echo ($config["money_name"]); ?>
							<?php if(($uinfo["user_rank"]) == "0"): ?><a href="<?php echo U('User/VipCenter');?>" style="color:#f03b57">开通VIP</a><?php endif; ?>
							</span>
						</p>
					</div>
					<div class="cb shuominga">
						<div class="fl shuoming_b" id="desc">加载中……</div>
					</div>
					<p class="fenjie"></p>
					<div class="danchuangxia">
						<div class="danchuangxia_a cb">
							<div class="fl xuanzheishulian">
								<select id="giftnum">
	                <option>1</option>
	                <option>9</option>
	                <option>19</option>
	                <option>50</option>
	                <option>99</option>
	                <option>299</option>
	                <option>520</option>
	                <option>1314</option>
	            	</select>
							</div>
							<div class="fr shuaxin"><img src="images/shuaxin.png"></div>
						</div>

						<div class="danchuangxia_b cb">
							<a href="<?php echo U('User/VipCenter',array('type'=>1));?>" class="fl"><img src="images/chouzhi.png" alt="">去充值</a>
							<a href="javascript:;" class="fr " id="sendgift"><img src="images/songlingwu.png">送出礼物</a>
							<input id="giftid" value="0" type="hidden" />
							<input id="myprice1" value="0" type="hidden" />
							<input id="pricevip1" value="0" type="hidden" />
						</div>
					</div>
				</div>
			</div>
			<!--#include file="footer.html"-->
		</div>
		
		
		<script type="text/javascript">
			var touid = '<?php echo ($touid); ?>';
			var mymoney = '<?php echo ($uinfo["money"]); ?>';
			
			// 显示礼物详情
			
			function showgift(gid) {
				$.post("<?php echo U('show');?>", {
					gid: gid
				}, function(data) {
					if (data.status == 1) {
						$(".lxzizhu_shop_dc").show(500);
						$("#img").attr("src", data.info.images);
						$("#giftname").html(data.info.gift_name);
						$("#price").html(data.info.price);
						$("#myprice").html(data.info.myprice);
						$("#myprice1").val(data.info.myprice);
						$("#pricevip").html(data.info.pricevip);
						$("#pricevip1").val(data.info.pricevip);
						$("#desc").html(data.info.desc);
						$("#giftid").val(data.info.gift_id);
						if (Number(data.info.mymoney) < Number(data.info.myprice)) {
							$("#sendgift").addClass("ccc");
						} else {
							$("#sendgift").removeClass("ccc");
						}
					} else {
						alert(data.info);
					}
				}, 'json');
			}
			
$(function(){	
			
			//刷新
			$(".shuaxin").click(function() {
				$(".xuanzheishulian option:first").prop("selected", 'selected');
			})
			
			//筛选
			$(".shuanxuan span").click(function() {
				var index = $(this).index();
				var thisdata = $(this).attr("data");
				if (thisdata) {
					$.post("/index.php/Home/Gift/index", {
						data: thisdata
					}, function(data) {
						$("ul.cb").html(data);
					}, 'json');
				}
				$(".shuanxuan span").removeClass("hot").eq(index).addClass("hot");
			})
			
			//关闭
			$(".qiandao_guanbi").click(function() {
				$(".lxzizhu_shop_dc").hide(500);
			})
			
			
			
			$("#sendgift").click(function() {
				if (!$(this).hasClass("ccc")) {
					$.post("/index.php/Home/Gift/sendgift", {
						touid:touid,
						giftid: $("#giftid").val(),
						giftnum: $("#giftnum").val()
					}, function(data) {
						if (data.status == 1) {
							$(".lxzizhu_shop_dc").hide(500);							
							alertbox("送礼成功，【"+data.info+"】将收到您的礼物。");
							$("#money").html(data.url);
						} else if (data.url == -1) {
							$(".lxzizhu_shop_dc").hide(500);
							alertbox("余额不足，请先充值", '立即充值', "<?php echo U('User/VipCenter',array('type'=>1));?>");
						} else {
							alertbox("送礼失败，请联系管理员");
						}
					}, 'json')
				} else {
					$(".lxzizhu_shop_dc").hide(500);
					alertbox("您的<?php echo ($config["money_name"]); ?>不足，请先充值", '立即充值', "<?php echo U('User/VipCenter',array('type'=>1));?>");					
				}
			})
			
			
			$("#giftnum").change(function() {
				var myprice = Number($("#myprice1").val());
				var pricevip = Number($("#pricevip1").val());
				var giftnum = $(this).val();
				$("#myprice").html(myprice * giftnum);
				$("#pricevip").html(pricevip * giftnum);
			})
})				
			
		</script>

	</body>

</html>