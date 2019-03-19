<?php if (!defined('THINK_PATH')) exit();?>	 	 
<?php if($list): if(is_array($list)): foreach($list as $key=>$vo): ?><li class="cb"  onclick="window.location.href='<?php echo U('Show/index',array('uid'=>$vo[idmd5]));?>'">
             <div class="fl"><img src="<?php echo ((isset($vo["avatar"]) && ($vo["avatar"] !== ""))?($vo["avatar"]):'/Public/img/mrtx.jpg'); ?>" alt=""></div>
             <div class="fl">
               <p style="padding-top: 0px;" class="<?php if($vo["user_rank"] > 0): ?>huiyuan_vip<?php else: ?>huiyuan<?php endif; ?>"><?php echo ((isset($vo["user_nicename"]) && ($vo["user_nicename"] !== ""))?($vo["user_nicename"]):"昵称未填"); if($vo["user_rank"] > 0): ?><span>VIP<?php echo ($vo["user_rank"]); ?></span><?php endif; ?><i><?php echo (date("m-d H:i",$vo["time"])); ?></i></p>
                <p class="sixin_linwu"><span>送给你<?php if($vo["giftnum"] > 1): ?><font color="red"><?php echo ($vo["giftnum"]); ?>个</font><?php endif; ?>价值<?php echo ($vo["gift_price"]); ?>金币的礼物， TA也很期待你的问候哦</span><i class="po-re"><img src="<?php echo ($vo["gift_image"]); ?>" alt="" style="width: 35px;"><span class="shulian_b">×<?php echo ($vo["giftnum"]); ?></span></i></p>
             </div>
           </li><?php endforeach; endif; ?>
		   
		   <?php else: ?>
  <li class="cb zanwu"  >
			 </li><?php endif; ?>