<?php if (!defined('THINK_PATH')) exit();?> <div class="new-gonggao" style="display: none">
                <img src="images/user_07.png" alt="" class="laba">
                <div id="demo" class="qimo8">
                    <div class="qimo">
                        <div id="demo1"><ul><li><a href="<?php echo U('Public/gongGao',array('id'=>$gg['content_id']));?>"> <?php echo ($gg["title"]); ?></a> </li></ul></div>
                        <div id="demo2"></div>
                    </div>
                </div>
                <img src="images/xx.png" alt="" class="xx_hide">
</div>


<!--
<a href="<?php echo U('Home/User/UpPhoto');?>" class="shangchen shangchen1" style="top: 20%;  display: block;"><img src="images/shangchuang_index.png" alt=""></a>



<a href="<?php echo U('Home/Ranking/Charmlist',array('type' => 4));?>" class="shangchen shangchen1" style="top: 35%;  display: block;"><img src="images/paihang.png" alt=""></a>

 <a href="<?php echo U('Home/Gift/index');?>" class="shangchen" style="top: 35%;"><img src="images/shangcenruk.png" alt=""></a>-->
 
 
      <div class="index_header cb" style="  overflow: hidden;">
         <div class="fl" id="ddd" onclick="getLocation();"><img src="images/fujin.png" alt=""><?php echo ($area); ?></div>
         <span  class="fr shuaxuan">条件刷选<img src="images/shouyejiantou.png" alt=""></sapn>
      </div>

        <div class="dangchuan">
        <div class="heipin"></div>
<form action="/index.php?m=Home&c=Index&a=index" method="post" id="form">
          <div class="shuaxuan_dangchu">
            <p class="kandao">想看到的用户</p>
            <div class="nnnn dedede">    
			<span <?php if($sex < 1): ?>class="hot"<?php endif; ?> >全部</span>          
              <span <?php if($sex == 1): ?>class="hot"<?php endif; ?>>男</span>
			  <span <?php if($sex == 2): ?>class="hot"<?php endif; ?>>女</span>              
            </div>
            <p class="kandao">所在地区筛选</p>
             <form name="dequ">
             <div class="dequ">
			 
              <select id="s1">
			    <?php if(is_array($province)): foreach($province as $key=>$vo): ?><option <?php if($vo['areaid'] == $areaarr['provinceid']): ?>selected="selected"<?php endif; ?> value="<?php echo ($vo["areaid"]); ?>"><?php echo ($vo["areaname"]); ?></option><?php endforeach; endif; ?>
			  </select>
              <select id="s2">
			  	
				 <?php if(is_array($city)): foreach($city as $key=>$vo): ?><option <?php if($vo['areaid'] == $areaarr['cityid']): ?>selected="selected"<?php endif; ?> value="<?php echo ($vo["areaid"]); ?>"><?php echo ($vo["areaname"]); ?></option><?php endforeach; endif; ?>
				
			  </select>
              </div>
             </form>
             <p class="kandao">对方年龄筛选</p>
             <div class="nianli">
               <ul>
                 <li <?php if($age < 1): ?>class="hot"<?php endif; ?>>不限</li>
                 <li <?php if($age == 1): ?>class="hot"<?php endif; ?>>18-25岁</li>
                 <li <?php if($age == 2): ?>class="hot"<?php endif; ?>>26-35岁</li>
                 <li <?php if($age == 3): ?>class="hot"<?php endif; ?>>36-40岁</li>
                 <li <?php if($age == 4): ?>class="hot"<?php endif; ?>>40-50岁</li>
                 <li <?php if($age == 5): ?>class="hot"<?php endif; ?>>50岁以上</li>
               </ul>
             </div>
             <br>
			 <input name="sex" id="sex" value="<?php echo ($sex); ?>" type="hidden"/>
			
			 <input name="provinceid" id="provinceid" value="<?php echo ($areaarr["provinceid"]); ?>" type="hidden"/>
			 <input name="cityid" id="cityid" value="<?php echo ($areaarr["cityid"]); ?>" type="hidden"/>
			 <input name="age" id="age" value="<?php echo ($age); ?>" type="hidden"/>
             <div class="nianli"><a href="javascript:;" class="guangbi_be">取消</a><a href="javascript:$('#form').submit();">确认</a></div>
          </div>
		  </form>
      </div> 
     
 <script type="text/javascript">
      $(".shuaxuan").click(function(){
        $(".shangchen1").hide(100);
        $(".dangchuan,.heipin").show(0)

      })
       $(".guangbi_be").click(function(){
        $(".dangchuan,.heipin").hide(0);
        $(".shangchen1").show(200);

      })

     $(".dedede span").click(function(){
      var index= $(this).index();
      $(".dedede span").removeClass("hot").eq(index).addClass("hot");
	  if(index==0) index--;
	  $("#sex").val(index); 
      })
     $(".nianli ul li").click(function(){
      var index= $(this).index();
      $(".nianli ul li").removeClass("hot").eq(index).addClass("hot"); 
	   if(index==0) index--;
	   $("#age").val(index); 
      })
	  
	  $('#s1').change(function(){
			var url = "<?php echo U('Home/Ajax/ajax_get_city');?>";
		    var provinceid =  $(this).val();
		    if(!provinceid) return false;
			$("#provinceid").val(provinceid);
			$("#cityid").val('0');
			$.post(url,{provinceid:provinceid},function(json){
				html = '<option value="0">请选择</option>';
				if(json){					
					$.each(json, function(idx, item) {
						html += '<option value="'+item.areaid+'">'
								+ item.areaname + '</option>';
					});
	               
					$("#s2").html(html);
				}
		
				
			},'json');
			
			
		})
				
		$('#s2').change(function(){
		 	 var cityid =  $(this).val();
		    if(!cityid) return false;
			$("#cityid").val(cityid);
			
		 })
		 
		   var demo = document.getElementById("demo");
                var demo1 = document.getElementById("demo1");
                var demo2 = document.getElementById("demo2");
                demo2.innerHTML = document.getElementById("demo1").innerHTML;

                function Marquee() {
                    if (demo.scrollLeft - demo2.offsetWidth >= 0) {
                        demo.scrollLeft -= demo1.offsetWidth;
                    } else {
                        demo.scrollLeft++;
                    }
                }
                var myvar = setInterval(Marquee, 30);
				var id = '<?php echo ($gg["content_id"]); ?>';
                $(".xx_hide").click(function(){
                	$(".new-gonggao").hide(200);					
					$.post("<?php echo U('Ajax/ggisread');?>",{id:id},function(data){
						
					},'json')
                })
		var showgg = "<?php echo cookie('gg'); ?>";	
		if(showgg!=id) $(".new-gonggao").show();
      </script>
      <script type="text/javascript">
var ss = 0;
$(window).scroll(function(){
  var sss = ss;  
var s=document.body.scrollTop;
    ss = s;
if(sss>s){
  $(".index_header").attr('id','piaofu');
}else{
    $(".index_header").attr('id','');
}
if(s<200){
  $(".index_header").attr('id','');
}
});
</script>