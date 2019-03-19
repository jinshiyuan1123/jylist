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
			<a href="<?php echo U('Home/Gift/index');?>" class="shangchen" style="top: 20%;"><img src="images/shangcenruk.png" alt=""></a>
			<div class="touxiang_bj">
				<img src="images/uesr_bj_0<?php echo ($info["sex"]); ?>.jpg" class="touxiang_bj_img">
				<div class="uesr_tx">

					<img src="<?php echo ((isset($info["avatar"]) && ($info["avatar"] !== ""))?($info["avatar"]):'/Public/img/mrtx.jpg'); ?>" alt="" class="uesr_tx_a">
					<a href="<?php echo U('Wechat/index',array('uid'=>$info[idmd5]));?>">
						<span class="gentalianx"><img src="images/user_03_06.png" alt="">跟TA聊天</span>
					</a>
				</div>

				<div class="uesr_zl">
					<p class="uesr_zl1p"><?php echo ((isset($info["user_nicename"]) && ($info["user_nicename"] !== ""))?($info["user_nicename"]):'昵称未设置'); ?></p>
					<p class="uesr_zl2p">地区:&nbsp;&nbsp;<?php echo ($area); ?>&nbsp;&nbsp;&nbsp;年龄:&nbsp;&nbsp;<?php echo ($age); ?>岁</p>
					<p class="uesr_zl3p">生日:&nbsp;<?php echo ($pinfo["birthday"]); ?>&nbsp;性别:<?php if($info["sex"] == 2): ?>女<?php else: ?>男<?php endif; ?></p>
					 <p class="uesr_zl4p"><img src="<?php echo ($info["rank_icon"]); ?>"><?php echo ($info["rank_name"]); ?><a href="javascript:gz('<?php echo ($info["id"]); ?>');" class="gz"><?php if($User_subscribe==1) echo '已关注'; elseif($User_subscribe==2) echo '相互关注'; else echo '关注TA';?></a></p>
				</div>
				<div class="uesr_table">
					<table onmousedown="0" onmousemove="0" border="0">
						<tr>
							<td><img src="images/user_03_16.png" alt=""></td>
							<td><img src="images/user_03_0<?php echo ($info["sex"]); ?>.png" alt=""></td>
							<td><img src="images/user_03_20.png" alt=""></td>
							<td><img src="images/user_03_11.png" alt=""></td>
							<td><img src="images/user_03_18.png" alt=""></td>
						</tr>
						<tr>
							<td><?php echo ((isset($userCount["fansnum"]) && ($userCount["fansnum"] !== ""))?($userCount["fansnum"]):'0'); ?></td>
							<td><?php echo ((isset($info["jifen"]) && ($info["jifen"] !== ""))?($info["jifen"]):'0'); ?></td>
							<td><?php echo ((isset($userCount["photonum"]) && ($userCount["photonum"] !== ""))?($userCount["photonum"]):'0'); ?></td>
							<td><?php echo ((isset($userCount["sumgift"]) && ($userCount["sumgift"] !== ""))?($userCount["sumgift"]):'0'); ?></td>
							<td><?php echo ((isset($userCount["zan"]) && ($userCount["zan"] !== ""))?($userCount["zan"]):'0'); ?></td>
						</tr>
					</table>
				</div>
			</div>

			<?php if($giftlist): ?><div class="new_songli">
				<div class="new_songli_div">收礼</div>
				<a href="<?php echo U('giftlist',array('uid'=>$info[idmd5]));?>" class="luanbo"><img src="images/youjianhei.png" alt=""></a>
				<div class="new_songli_ulli">
					<ul class="cb">
						<?php if(is_array($giftlist)): foreach($giftlist as $key=>$vo): ?><li><div class="wuyu"><div class="paihang"><?php echo ($key+1); ?></div><img src="<?php echo ($vo["gift_image"]); ?>" alt="" class="liwu"> <div class="shulian">×<?php echo ($vo["giftnum"]); ?></div></div></li><?php endforeach; endif; ?>
					</ul>
				</div>
			</div><?php endif; ?>

			<?php if($simiphoto): ?><div class="new_simizhao">
				<div class="new_simizhao_div">私密照</div>
				<div class="new_simizhao_ulli">
					<ul>
						<?php if(is_array($simiphoto)): foreach($simiphoto as $key=>$vo): if($allowsmz): ?><li><a href="<?php echo U('photo',array('pid'=>$vo[idmd5]));?>"><img src="<?php echo ($vo["thumbfiles"]); ?>" alt=""></a></li>	
						<?php else: ?>
							<li><a href="javascript:alertbox1('url');"><img src="images/user_03_28.png" alt=""></a></li><?php endif; endforeach; endif; ?>
					</ul>
				</div>
				<a href="<?php if($allowsmz): echo U('photolist',array('uid'=>$info[idmd5],'phototype' => 1 )); else: ?>javascript:alertbox1('url');<?php endif; ?>" class="simizhao_luanbo"><img src="images/youjianhei.png" alt=""></a>
			</div><?php endif; ?>
			
			<?php if($photoarr): ?><div class="new_simizhao">
				<div class="new_simizhao_div">公开照</div>
				<div class="new_simizhao_ulli">
					<ul>
						<?php if(is_array($photoarr)): foreach($photoarr as $key=>$vo): ?><li>
						<a href="<?php echo U('photo',array('pid'=>$vo[idmd5]));?>">
						<img src="<?php echo ($vo["thumbfiles"]); ?>" alt="">
						<span>
						<img src="images/user_03_32.png" alt="">
						<?php echo ($vo["hits"]); ?>&nbsp;&nbsp;
						</span>
						</a>
						</li><?php endforeach; endif; ?>
					</ul>
				</div>
				<a href="<?php echo U('photolist',array('uid'=>$info[idmd5],'phototype' => 0));?>" class="simizhao_luanbo"><img src="images/youjianhei.png" alt=""></a>
			</div>
			
			<?php else: ?>
			<div class="new_simizhao">
					<div class="new_simizhao_div">公开照</div>
				<div class="new_simizhao_ulli">
					<ul>						
						
							<li><a href="javascript:alertbox('用户暂未上传照片');"><img src="/Public/img/no_picture.gif" alt=""></a></li>
														
							
					</ul>
				</div>
				<a href="javascript:alertbox('用户暂未上传照片');" class="simizhao_luanbo"><img src="images/youjianhei.png" alt=""></a>
			</div><?php endif; ?>
			<?php if(isset($miyu)&&$miyu): ?><div class="neixinduba_div">
				<p class="new_nxindubai">交友密语</p>
				<?php if(is_array($miyu)): foreach($miyu as $k=>$v): ?><div class="new_neixinduba"><?php echo ($k); ?>：<?php echo ($v); ?></div><?php endforeach; endif; ?>
				
			</div><?php endif; ?>
			<div class="neixinduba_div">
				<p class="new_nxindubai">内心独白</p>
				<div class="new_neixinduba"><?php echo ((isset($pinfo["monolog"]) && ($pinfo["monolog"] !== ""))?($pinfo["monolog"]):"暂无"); ?></div>

			</div>
			<div class="neixinduba_div">
				<p class="new_nxindubai">ta的联系方式</p>
				<div class="lianxifangshi_new">
					<ul>
						<li class="cb">
							<div class="fl">手机号码 : </div>
							<div class="fr">
								<?php if($uinfo["user_rank"] > 0): if($lxfs_config["mob"] == 'hot'): echo ((isset($pinfo["mob"]) && ($pinfo["mob"] !== ""))?($pinfo["mob"]):"用户未公开"); ?>
								<?php else: ?>用户未公开<?php endif; ?>
								<?php else: ?>****<a href="<?php echo U('User/VipCenter');?>">VIP可见</a><?php endif; ?>
							</div>
						</li>
						<li class="cb">
							<div class="fl">微信号码 : </div>
							<div class="fr">
								<?php if($uinfo["user_rank"] > 0): if($lxfs_config["weixin"] == 'hot'): echo ((isset($pinfo["weixin"]) && ($pinfo["weixin"] !== ""))?($pinfo["weixin"]):"用户未公开"); ?>
								<?php else: ?>用户未公开<?php endif; ?>
								<?php else: ?>****<a href="<?php echo U('User/VipCenter');?>">VIP可见</a><?php endif; ?>
							</div>
						</li>
					</ul>
				</div>
			</div>
            <p><a href="<?php echo U('Wechat/index',array('uid'=>$info[idmd5]));?>" class="laheta" style="background:#F33; width:96%; color:#FFF; margin:0 auto;">跟TA聊天</a></p>
			<p class="laheta_p"><a href="javascript:;" onclick="lahei();" class="laheta"><?php if($lahei == 0): ?>拉黑TA<?php else: ?>已拉黑<?php endif; ?></a></p>
			
			  <a href="<?php echo U('Gift/index',array('uid'=>$info['idmd5']));?>" class="sonhua"  id="ad1"  >
       <img src="images/songh.png" alt="">
       <div>送礼</div>
     </a>
    
<script src="js/user_xq_ad.js"></script>
<script>

var ad1=new AdMove("ad1"); 
ad1.Run(); 


function nishuo() {
$(".new_neixinduba").html("");
				}

function nishuo1() {
					
}

function lahei(){
	var touid = '<?php echo ($info["id"]); ?>';
	$.post("<?php echo U('Ajax/lahei');?>",{touid:touid},function(data){
		if(data.status==1){
			$(".laheta").html(data.info);
		}else{
			alertbox(data.info);
		}
	},'json')
}
  
  function paysm(){
  	var url = "<?php echo U('photolist',array('uid'=>$info[idmd5],'phototype' =>1,'pay'=>1));?>";
	$.get(url,{ajax:1},function(data){
		if(data.status==1){
			window.location.href=url;
		}else if(data.url==-1){
			alertbox(data.info,'立即充值',"<?php echo U('User/Vipcenter',array('type'=>1));?>");
		}else{
			alert('操作失败，请联系管理员。');
		}
		//console.data;
	},'json')
	
  }
  
  function alertbox1(url){
  	alertbox('您和ta的亲密度不够（当前<?php echo ($User_qmd); ?>。需要<?php echo ($photo_config["0"]); ?>），没有权限查看此用户私密照哦~,您还可以付费<?php echo ($photo_config["1"]); echo ($config["money_name"]); ?>查看。','付费查看',"javascript:paysm();");
  }
 </script>
			<!--#include file="footer.html"-->
		</div>
	</body>
</html>