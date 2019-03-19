<?php if (!defined('THINK_PATH')) exit(); if($list): if(is_array($list)): foreach($list as $key=>$vo): ?><li class="cb" onclick="window.location.href='<?php echo U('Wechat/index',array('uid'=>$vo[uid],'noread'=>$vo['list2arr']['noread']));?>'">
             <div class="fl"><img src="<?php echo ((isset($vo["avatar"]) && ($vo["avatar"] !== ""))?($vo["avatar"]):'/Public/img/mrtx.jpg'); ?>" alt=""></div>
             <div class="fl">
               <p class="<?php if($vo["user_rank"] > 0): ?>huiyuan_vip<?php else: ?>huiyuan<?php endif; ?>"><?php echo ((isset($vo["user_nicename"]) && ($vo["user_nicename"] !== ""))?($vo["user_nicename"]):'昵称未填'); if($vo["user_rank"] > 0): ?><span>VIP<?php echo ($vo["user_rank"]); ?></span><?php endif; ?><i><?php echo (date("m-d H:i",$vo["list2arr"]["sendtime"])); ?></i></p>
               <p class="sixin_pl"><span><?php if($vo['list2arr']['type'] == 2): ?>[语音]<?php else: echo ($vo["list2arr"]["content"]); endif; ?></span><?php if($vo['list2arr']['isread']==0 && $vo['list2arr']['fromuid']!=$uinfo['id']) { ?><i>未读</i><?php } ?></p>
             </div>
           </li><?php endforeach; endif; ?>
			
			   <?php else: ?>
  <li class="cb zanwu"  >
			 </li><?php endif; ?>