<?php if (!defined('THINK_PATH')) exit(); if($list): if(is_array($list)): foreach($list as $key=>$vo): ?><li class="cb">
		<div class="fl"><img src="/Public/img/morentouxian_03.jpg" alt=""></div>
		<div class="fl">
			<p class="huiyuan"><?php echo ($vo["type"]); ?><i><?php echo (date("m-d H:i",$vo["time"])); ?></i></p>
			<p class="sixin_pl_b"><?php echo ($vo["msg_content"]); ?>
				<?php if($idsinfo[$vo['touid']]['user_nicename']): ?>(<a href="<?php echo U('Home/Show/index',array('uid'=>$idsinfo[$vo['touid']]['idmd5']));?>"><?php echo ($idsinfo[$vo['touid']]['user_nicename']); ?></a>)<?php endif; ?>
			</p>
		</div>
	</li><?php endforeach; endif; ?>
<?php else: ?>
	<li class="cb zanwu"></li><?php endif; ?>