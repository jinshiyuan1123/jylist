<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>更换头像</title>
<style>*,html,body{padding:0px;margin:0px;}</style>

<script src="js/avatar/jquery-2.0.3.min.js"></script>
<script src="js/avatar/layer.m.js"></script>
<script src="js/avatar/touch-0.2.14.min.js"></script>
<script src="js/avatar/jquery.crop.js?asdfasdf"></script>
<script>
		$(function(){
			var w = $(window).width();
			var h = $(window).height();
			var id = '<?php echo ($info["photoid"]); ?>';
			var uploadfiles = '<?php echo ($info["uploadfiles"]); ?>';
			$('.cutbox').crop({
				w:w>h?h:w,
				h:h,
				r:(w-30)*0.5,
				res:'<?php echo ($info["uploadfiles"]); ?>',
				callback:function(ret){
					$.post('<?php echo U();?>',{ret:ret,id:id,uploadfiles:uploadfiles},function(data){
					       if(data.status==1){					    	 
					    	   window.location.href=data.url;
					       }
					},'json');									
					localStorage.setItem("new_avatar",ret);
					sessionStorage.setItem('edit_baby_avatar',true);
					
				}
			});
		
		
		
		});
		
</script>
</head>

<body>
<div class="cutbox"></div>


</body>

</html>