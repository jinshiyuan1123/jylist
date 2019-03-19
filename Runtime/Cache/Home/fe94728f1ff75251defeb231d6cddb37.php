<?php if (!defined('THINK_PATH')) exit();?> <?php if(!$list1): ?><li class="cb zanwu"  >
			 </li><?php endif; ?>
 
 
 <?php if(is_array($list1)): foreach($list1 as $key=>$vo): ?><li class="cb"> 
                 <div class="fl"  onclick="window.location.href='<?php echo U('Show/index',array('uid'=>$vo[idmd5]));?>'"><img src="<?php echo ($vo["avatar"]); ?>"></div>
                 <div class="fl"  onclick="window.location.href='<?php echo U('Show/index',array('uid'=>$vo[idmd5]));?>'" style="margin-top: 3px;">
                    <p class="<?php if($vo["user_rank"] > 0): else: ?>heise<?php endif; ?>"><?php echo ($vo["user_nicename"]); if($vo["user_rank"] > 0): ?><span>VIP<?php echo ($vo["user_rank"]); ?></span><?php endif; ?></p>
                    <p><?php echo ((isset($vo["age"]) && ($vo["age"] !== ""))?($vo["age"]):'22'); ?>岁&nbsp;<?php if($vo['area']): echo ($vo["area"]); else: echo ($area); endif; ?>&nbsp;<?php echo ($vo["astro"]); ?>
	  <?php if($vo["sex"] == 2): ?><span>			
<img src="themes/<?php echo C('TPL_NAME'); ?>/images/meilizhi_02.png"alt="" class="meili_img" ><i class="meili"><?php echo ($vo["jifen"]); ?></i></span>
<?php else: ?>
<span><img src="themes/<?php echo C('TPL_NAME'); ?>/images/caifuzhi.png"alt="" class="caifu_img" ><i class="caifu"><?php echo ($vo["jifen"]); ?></i></span><?php endif; ?>
</p>
                    <p><?php echo ($vo["monolog"]); ?></p>
                 </div>
                 <div class="fr">
                    <p><img src="themes/<?php echo C('TPL_NAME'); ?>/images/fujin1.png" alt=""><?php echo ($vo["km"]); ?> km</p>
					 
					  <?php if($vo["iszhd"] == 1): ?><a href="javascript:;" class="ydzh">已招呼</a>
					  <?php else: ?>
                     <a href="javascript:sendmsg('<?php echo ($vo["id"]); ?>');" class="dzh<?php echo ($vo["id"]); ?>">打招呼</a><?php endif; ?>
                 </div>
              </li><?php endforeach; endif; ?>