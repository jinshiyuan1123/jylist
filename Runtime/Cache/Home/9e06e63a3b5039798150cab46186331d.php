<?php if (!defined('THINK_PATH')) exit(); if(is_array($list)): foreach($list as $key=>$vo): if(count($vo)>1){ ?>
        <div class="dongtai_a cb">
            <div class="dongtai_a_tx fl"><a href="<?php echo U('Home/Show/index',array('uid'=>$vo[0]['idmd5']));?>"><img src="<?php echo ((isset($vo["0"]["avatar"]) && ($vo["0"]["avatar"] !== ""))?($vo["0"]["avatar"]):'/Public/img/mrtx.jpg'); ?>" alt=""></a></div>
            <div class="fl dongtai_a_pl">
              	<p class="<?php if($vo['0']['user_rank'] == 0): ?>dt_name<?php else: ?>dt_name_vip<?php endif; ?>">- <?php echo ((isset($vo["0"]["user_nicename"]) && ($vo["0"]["user_nicename"] !== ""))?($vo["0"]["user_nicename"]):'昵称未填写'); ?><span></span></p>
              	<p class="dt_pl"><?php echo ((isset($vo["0"]["title"]) && ($vo["0"]["title"] !== ""))?($vo["0"]["title"]):'天若有情'); ?></p>
              	<div class="tusspian_p_ulLi">
              	   	<ul class="cb">
						<?php if(is_array($vo)): foreach($vo as $k=>$vo1): ?><li><div><a href="<?php echo U('Home/Show/photo',array('pid'=>$vo1['pidmd5']));?>" class="w_dengyu_h"><img src="<?php echo ($vo1["thumbfiles"]); ?>" alt=""></a></div></li><?php endforeach; endif; ?>              	   	 
              	   	</ul>
              	</div>
              	<p class="address"><?php echo ($area); ?></p>
              	<p class="dt_date"><?php echo (date('Y-m-d',$vo["0"]["timeline"])); ?></p>
              	<?php if(isset($commentlist[$key])): ?><div class="dt_pl_new">
     		            <?php if(is_array($commentlist[$key])): foreach($commentlist[$key] as $key=>$val): if($val['user_rank'] == 1): ?><p class="dt_pl_novip"><i><?php echo ($val['user_nicename']); ?>：</i><?php echo ($val['content']); ?></p>
		                	<?php else: ?>	
		                		<p class="dt_pl_vip"><i><?php echo ($val['user_nicename']); ?>：</i><?php echo ($val['content']); ?></p><?php endif; endforeach; endif; ?>      	              	
		            </div><?php endif; ?>
            </div>
        </div>
	<?php }else{ ?>    
         <div class="dongtai_a cb">
            <div class="dongtai_a_tx fl"><a href="<?php echo U('Home/Show/index',array('uid'=>$vo[0]['idmd5']));?>"><img src="<?php echo ((isset($vo["0"]["avatar"]) && ($vo["0"]["avatar"] !== ""))?($vo["0"]["avatar"]):'/Public/img/mrtx.jpg'); ?>" alt=""></a></div>
            <div class="fl dongtai_a_pl">
               	<p class="<?php if($vo['0']['user_rank'] == 0): ?>dt_name<?php else: ?>dt_name_vip<?php endif; ?>">- <?php echo ((isset($vo["0"]["user_nicename"]) && ($vo["0"]["user_nicename"] !== ""))?($vo["0"]["user_nicename"]):'昵称未填写'); ?><span></span></p>
              	<p class="dt_pl"><?php echo ((isset($vo["0"]["title"]) && ($vo["0"]["title"] !== ""))?($vo["0"]["title"]):'天若有情'); ?></p>
              	<div class="tusspian_p"><a href="<?php echo U('Home/Show/photo',array('pid'=>$vo[0]['pidmd5']));?>"><img src="<?php echo ($vo["0"]["thumbfiles"]); ?>" alt="" class="tupian_yi"></a></div>
          		<p class="address"><?php echo ($area); ?></p>
          	  	<p class="dt_date"><?php echo (date('Y-m-d',$vo["0"]["timeline"])); ?></p>
          	  	
          	  	<?php if(isset($commentlist[$key])): ?><div class="dt_pl_new">
     		              <?php if(is_array($commentlist[$key])): foreach($commentlist[$key] as $key=>$val): if($val['user_rank'] == 1): ?><p class="dt_pl_novip"><i><?php echo ($val['user_nicename']); ?>：</i><?php echo ($val['content']); ?></p>
		                	<?php else: ?>	
		                		<p class="dt_pl_vip"><i><?php echo ($val['user_nicename']); ?>：</i><?php echo ($val['content']); ?></p><?php endif; endforeach; endif; ?>      
              	
	            </div><?php endif; ?>
            </div>
        </div>
	<?php } endforeach; endif; ?>