<?php if (!defined('THINK_PATH')) exit();?><a href="" class="shangchen"><img src="images/shangcenruk.png" alt=""></a>          
	<div class="qingmidu_header" style="height: 80px;">
		<a href="<?php echo U('Home/Ranking/Charmlist',array('type' => 4));?>" <?php if($type == 4): ?>class="hot"<?php endif; ?> >周魅力榜</a>
		<a href="<?php echo U('Home/Ranking/Charmlist',array('type' => 3));?>" <?php if($type == 3): ?>class="hot"<?php endif; ?> >周财富榜</a>
		<a href="<?php echo U('Home/Ranking/Charmlist',array('type' => 2));?>" <?php if($type == 2): ?>class="hot"<?php endif; ?> >魅力榜</a>
		<a href="<?php echo U('Home/Ranking/Charmlist',array('type' => 1));?>" <?php if($type == 1): ?>class="hot"<?php endif; ?> >财富榜</a>
		<a href="<?php echo U('Home/Ranking/qinmibang');?>" <?php if(!$type): ?>class="hot"<?php endif; ?> >亲密榜</a>
		<img src="images/xialajiantou.png" alt="" class="zhangkai daojian">
	</div>	
	
	<script>
		var hanggao = 0;
		$(".zhangkai").click(function(){
			if( hanggao <= 0 ){
				$(".qingmidu_header").css('height',40);
				$(".zhangkai").removeClass('daojian');
				hanggao++
			}else{
      	$(".qingmidu_header").css('height',80);
      	$(".zhangkai").addClass('daojian');
        hanggao--
			}	
		})
	</script>