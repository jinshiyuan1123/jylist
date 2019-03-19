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
		<meta name="keywords" content="<?php echo ($media["keywords"]); ?>">
		<meta name="description" content="<?php echo ($media["description"]); ?>">
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
			<a href="<?php echo U('Home/Gift/index');?>" class="shangchen" style="top: 20%;"><img src="images/shangcenruk.png" alt=""></a>
			<div class="touxiang_bj">
				<img src="images/uesr_bj_0<?php echo ($uinfo["sex"]); ?>.jpg" class="touxiang_bj_img">
				
				<div class="uesr_tx">

					<a href="<?php echo U('saveAvatarList');?>"><img src="<?php echo ((isset($uinfo["avatar"]) && ($uinfo["avatar"] !== ""))?($uinfo["avatar"]):'/Public/img/mrtx.jpg'); ?>" alt="" class="uesr_tx_a"></a>
					<a href="<?php echo U('VipCenter');?>">
						<span class="gentalianx"><?php if(($uinfo["user_rank"]) == "0"): ?><img src="images/vip1.png" alt="" style="width: 23px;">购买VIP<?php else: ?><img src="images/vip.png" alt="" style="width: 23px;">VIP会员<?php endif; ?></span>
					</a>
				</div>

				<div class="uesr_zl">
					<p class="uesr_zl1p"><a href="<?php echo U('Home/User/basedata');?>" style="color: #f61a24"><?php echo ((isset($uinfo["user_nicename"]) && ($uinfo["user_nicename"] !== ""))?($uinfo["user_nicename"]):'昵称未填写'); ?></a></p>
					<p class="uesr_zl2p">地区:&nbsp;&nbsp;<?php echo ((isset($uinfo["province_name"]) && ($uinfo["province_name"] !== ""))?($uinfo["province_name"]):'未知'); echo ($uinfo["city_name"]); ?>&nbsp;&nbsp;&nbsp;年龄:&nbsp;&nbsp;<?php echo ((isset($uinfo["age"]) && ($uinfo["age"] !== ""))?($uinfo["age"]):'未知'); ?></p>
					<?php if($userProfile['birthday']): ?><p class="uesr_zl3p">生日:&nbsp;&nbsp;<?php echo ((isset($userProfile["birthday"]) && ($userProfile["birthday"] !== ""))?($userProfile["birthday"]):'未知'); ?></p><?php endif; ?>
					<p class="uesr_zl4p"><img src="<?php echo ($uinfo["rank_icon"]); ?>"><?php echo ($uinfo["rank_name"]); if($isqd == 0): ?><a style="cursor: pointer;" onclick="qiandao(<?php echo ($uinfo["id"]); ?>)">签到</a><?php else: ?><a style="cursor: pointer;">已签到</a><?php endif; ?></p>
				</div>
				<div class="uesr_table">
					<table onmousedown="0" onmousemove="0" border="0">
						<tr>
							<td><a href="<?php echo U('MySubscribe',array('type'=>1));?>"><img src="images/user_03_16.png" alt=""></a></td>
							<td><img src="images/user_03_0<?php echo ($uinfo["sex"]); ?>.png" alt=""></td>
							<td><a href="<?php echo U('MyPhoto');?>"><img src="images/user_03_20.png" alt=""></a></td>
							<td><a href="<?php echo U('Home/Gift/giftlist');?>"><img src="images/user_03_11.png" alt=""></a></td>
							<td><img src="images/user_03_18.png" alt=""></td>
						</tr>
						<tr>
							<td><?php echo ((isset($userCount["fansnum"]) && ($userCount["fansnum"] !== ""))?($userCount["fansnum"]):'0'); ?></td>
							<td><?php echo ((isset($uinfo["jifen"]) && ($uinfo["jifen"] !== ""))?($uinfo["jifen"]):'0'); ?></td>
							<td><?php echo ((isset($userCount["photonum"]) && ($userCount["photonum"] !== ""))?($userCount["photonum"]):'0'); ?></td>
							<td><?php echo ((isset($userCount["sumgift"]) && ($userCount["sumgift"] !== ""))?($userCount["sumgift"]):'0'); ?></td>
							<td><?php echo ((isset($userCount["zan"]) && ($userCount["zan"] !== ""))?($userCount["zan"]):'0'); ?></td>
						</tr>
					</table>

				</div>
			</div>
			<div class="cb lia_nav">
				<div>
					<a href="<?php echo U('VipCenter',array('type'=>1));?>"><img src="images/uesr_wode_03.png" alt=""><?php echo ($config["money_name"]); ?>: <?php echo ((isset($uinfo["money"]) && ($uinfo["money"] !== ""))?($uinfo["money"]):'0'); ?></a>
				</div>
				<div>
					<a href="<?php echo U('tixian');?>"><img src="images/uesr_wode_01.png" alt="" style="    width: 20px;">点击提现</a>
				</div>

			</div>

			<div class="uesr_nav" style="margin-top: 10px;overflow: hidden; padding-bottom: 10px;border-bottom: #dfdfdf solid 1px; border-top: #dfdfdf solid 1px;background: #fff;">
				<ul class="uesr_wode_nav">
					<li>
						<a href="<?php echo U('VipCenter',array('type'=>1));?>" class="cb">
							<img src="images/new_uesr_19.png" alt=""><br>充值中心
						</a>
					</li>


					<!--<li>-->
					<?php if(($uinfo["tuiguang"]) == "1"): ?><!--<a href="<?php echo U('Agent/Tuig/index');?>" class="cb">
							<img src="images/new_uesr_07.png" alt=""><br>联盟登录
						</a>-->
					<?php else: ?>
						<!--<a href="<?php echo U('User/union');?>" class="cb">
							<img src="images/new_uesr_07.png" alt=""><br>申请联盟
						</a>--><?php endif; ?>
					<!--</li>-->

					<!--

					<li>
						<a href="<?php echo U('Public/MyYq');?>" class="cb">
							<img src="images/new_uesr_07.png" alt=""><br>邀请好友
						</a>
					</li>
					
					<li>
						<a href="<?php echo U('MyYqList');?>" class="cb">
                            <img src="images/new_uesr_24.png" alt=""><br>邀请列表          
                        </a>
					</li>

					-->
					
					<li>
						<a href="<?php echo U('MySubscribe',array('type'=>1));?>" class="cb">
							<img src="images/new_uesr_05.png" alt=""><br>我的粉丝

						</a>
					</li>
					
					<li>
						<a href="<?php echo U('MySubscribe');?>" class="cb">
							<img src="images/new_uesr_03.png" alt=""><br>我的关注
						</a>
					</li>
					
					
					<li>
						<a href="<?php echo U('MyPhoto');?>" class="cb">
							<img src="images/new_uesr_12.png" alt=""><br>我的相册

						</a>
					</li>
					<li>
						<a href="<?php echo U('MyMoney');?>" class="cb">
							<img src="images/new_uesr_25.png" alt=""><br>我的钱包
						</a>
					</li>
					
					<li>
						<a href="<?php echo U('MyMolog');?>" class="cb">
							<img src="images/new_uesr_13.png" alt=""><br>内心独白
						</a>
					</li>

					
					<li>
						<a href="<?php echo U('Mylxfs');?>" class="cb">
							<img src="images/new_uesr_18.png" alt=""><br>联系方式
						</a>
					</li>
					
					
					
					<li>
						<a href="<?php echo U('basedata');?>" class="cb">
							<img src="images/new_uesr_14.png" alt=""><br>基础资料
						</a>
					</li>
					
					
					<li>
						<a href="<?php echo U('UserSet');?>" class="cb">
							<img src="images/new_uesr_20.png" alt=""><br>账号设置
						</a>
					</li>
					
					
					<li>
						<a href="<?php echo U('Home/Public/NewbieGuide');?>" class="cb">
							<img src="images/new_uesr_26.png" alt=""><br>新手教程
						</a>
					</li>
				</ul>
			</div>
			<!--#include file="footer.html"-->
		</div>
	</body>
	<script type="text/javascript">
		function qiandao(id){
			$.ajax({
				type:"post",
				url:"<?php echo U('Home/Public/sign');?>",
				data:"id="+id,
				success:function(str){
					alertbox(eval(str));
				}
			});
		}
	</script>
</html>