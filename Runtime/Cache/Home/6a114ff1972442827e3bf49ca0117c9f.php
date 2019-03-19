<?php if (!defined('THINK_PATH')) exit();?><div class="sixin_header">
         <ul class="cb">
           <li <?php if($nav2 == 'sixin_a'): ?>class="hot"<?php endif; ?> ><a href="<?php echo U('Wechat/sixin');?>">私信</a><?php if($user_count["wdsxnum"] > 0): ?><span><?php echo ($user_count["wdsxnum"]); ?></span><?php endif; ?></li>
		   
           <li <?php if($nav2 == 'sixin_b'): ?>class="hot"<?php endif; ?>><a href="<?php echo U('Wechat/subscribe');?>">关注</a><?php if($user_count["wdgznum"] > 0): ?><span><?php echo ($user_count["wdgznum"]); ?></span><?php endif; ?></li>
           <li <?php if($nav2 == 'sixin_c'): ?>class="hot"<?php endif; ?>><a href="<?php echo U('Gift/giftlist');?>">收礼</a><?php if($user_count["wdgiftnum"] > 0): ?><span><?php echo ($user_count["wdgiftnum"]); ?></span><?php endif; ?></li>
           <li <?php if($nav2 == 'sixin_d'): ?>class="hot"<?php endif; ?>><a href="<?php echo U('Wechat/system');?>">系统</a><?php if($user_count["wdsysnum"] > 0): ?><span><?php echo ($user_count["wdsysnum"]); ?></span><?php endif; ?></li>
         </ul>
      </div>