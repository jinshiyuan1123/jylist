<?php if (!defined('THINK_PATH')) exit(); if(is_array($list)): foreach($list as $key=>$vo): ?><li onclick='showgift(<?php echo ($vo["gift_id"]); ?>);'><div class="liwu_img"><img src="<?php echo ((isset($vo["images"]) && ($vo["images"] !== ""))?($vo["images"]):'/Public/img/mrtx.jpg'); ?>" alt=""></div><div><p><?php echo ($vo["gift_name"]); ?></p><p><span><?php echo ($vo["price"]); ?></span><?php echo ($config["money_name"]); ?></p></div></li><?php endforeach; endif; ?>