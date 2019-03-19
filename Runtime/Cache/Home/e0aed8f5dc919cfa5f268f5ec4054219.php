<?php if (!defined('THINK_PATH')) exit(); if(is_array($list)): foreach($list as $key=>$vo): ?><li class="cb">
		<div class="jin fl paihang_yi">
			<?php if($vo['order']==1): ?><img src="themes/<?php echo C('TPL_NAME'); ?>/images/paihan_03.png" alt="">
			<?php elseif($vo['order']==2): ?>		
				<img src="themes/<?php echo C('TPL_NAME'); ?>/images/paihan_06.png" alt="">
			<?php elseif($vo['order']==3): ?>	
				<img src="themes/<?php echo C('TPL_NAME'); ?>/images/paihan_09.png" alt="">
			<?php else: ?>	
				<?php echo ($vo["order"]); endif; ?>
		</div>	
			
		<div class="fl  paihang_er">
			<div class="fl paihang_er_touxian">
				<a href="<?php echo U('Home/Show/index',array('uid'=>$vo['idmd5']));?>">
					<img src="<?php echo ((isset($vo["avatar"]) && ($vo["avatar"] !== ""))?($vo["avatar"]):'/Public/img/mrtx.jpg'); ?>" alt="">
				</a>
			</div>
			<div class="fl">
				<?php if($vo['user_rank']==1): ?><p class="name_vip cb">
						<i class="fl"><?php echo ($vo["user_nicename"]); ?></i>					
						<span class="fl vip_logo"></span>   <!--  VIP logo  -->				
						<img src="<?php echo ($vo["rank_icon"]); ?>" alt="" class="fl name_vip_dengji">					
					</p>
				<?php else: ?>	
					<p class="name_vip_no cb">
						<i class="fl"><?php echo ($vo["user_nicename"]); ?></i>				
						<img src="<?php echo ($vo["rank_icon"]); ?>" alt="" class="fl name_vip_dengji">					
					</p><?php endif; ?>
				<div class="paihang_er_iok">
					<?php if($vo['type'] == 1): ?><img src="themes/<?php echo C('TPL_NAME'); ?>/images/caifuzhi.png" style="" alt=""><?php echo ((isset($vo["jifen"]) && ($vo["jifen"] !== ""))?($vo["jifen"]):"0"); ?>
					<?php elseif($vo['type'] == 2): ?>	
						<img src="themes/<?php echo C('TPL_NAME'); ?>/images/user_03_02.png" style="" alt=""><?php echo ((isset($vo["jifen"]) && ($vo["jifen"] !== ""))?($vo["jifen"]):"0"); ?>
					<?php elseif($vo['type'] == 3): ?>
						<img src="themes/<?php echo C('TPL_NAME'); ?>/images/user_03_01.png" style="" alt=""><?php echo ((isset($vo["sevenjifen"]) && ($vo["sevenjifen"] !== ""))?($vo["sevenjifen"]):"0"); ?>
					<?php elseif($vo['type'] == 4): ?>
						<img src="themes/<?php echo C('TPL_NAME'); ?>/images/user_03_02.png" style="" alt=""><?php echo ((isset($vo["sevenjifen"]) && ($vo["sevenjifen"] !== ""))?($vo["sevenjifen"]):"0"); endif; ?>
					
					&nbsp;&nbsp;&nbsp;&nbsp;
					<!-- 送礼 -->
					<a href="<?php echo U('Home/Gift/index',array('uid'=>$vo['idmd5']));?>">	
						<img src="themes/<?php echo C('TPL_NAME'); ?>/images/user_03_11.png" alt=""  style="width: 19px;"><?php echo ((isset($vo["sumgift"]) && ($vo["sumgift"] !== ""))?($vo["sumgift"]):0); ?>
					</a>
				</div>
			</div>
		</div>
		<a href="<?php echo U('Home/Show/index',array('uid'=>$vo['idmd5']));?>" class="fr paihang_a">
			<img src="themes/<?php echo C('TPL_NAME'); ?>/images/youjianhei.png">
		</a>
	</li><?php endforeach; endif; ?>