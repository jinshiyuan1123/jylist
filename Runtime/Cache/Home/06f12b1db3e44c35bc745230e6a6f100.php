<?php if (!defined('THINK_PATH')) exit();?>  <?php if(is_array($list)): foreach($list as $key=>$vo): ?><li  class="box"><a href="<?php echo U('saveAvatar',array('photoid'=>$vo['photoid']));?>" class="w_dengyu_h">
	   <img src="<?php echo ($vo["thumbfiles"]); ?>" alt="">
	   <?php if($vo['isavatr'] == 1): ?><i class="dangqian">当前头像</i><?php endif; ?>
	   <?php if(isset($vo['dshPhoto'])&&$vo['dshPhoto'] == 1): ?><i class="dangqian">头像审核中</i><?php endif; ?>
	    <?php if(isset($vo['dshPhoto'])&&$vo['dshPhoto'] == 2): ?><i class="dangqian">审核未通过</i><?php endif; ?>
	   </a></li><?php endforeach; endif; ?>