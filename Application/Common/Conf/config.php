<?php
return array(
	//'配置项'=>'配置值'
    'TMPL_TEMPLATE_SUFFIX'=>'.html',
    'LOAD_EXT_CONFIG' => 'performance,shield,db',
    'VAR_SESSION_ID' => 'session_id',
	'SITE_URL' => '',
	'SITE_HASH_KEY' => 'pass003',//加密字符串，不可修改
	'URL_CASE_INSENSITIVE' =>false,
	'PWD_SALA'=>'www.yueai.me',//开发者官网，请勿修改，修改后无法使用后果自负。
	'LBS_DB'=>'143360',//百度lbs
	'ak'=>'SNuHPQWBE7FFRCxGnImjqWDf',//百度lbs ak
	'ak2'=>'0699a15d2182f5558ee42882d553bcc8',//百度lbs ak
	'open_lxzizhu_app'=>0,//如果有app 开启
	'homeview'=>'30',//个人主页被访问通知间隔，后台需开启开关才有效。单位分钟
	//星座配置
	//	1#白羊座|2#金牛座|3#双子座|4#巨蟹座|5#狮子座|6#处女座| 7#天秤座|8#天蝎座|9#射手座|10#摩羯座|11#水瓶座|12#双鱼座|
	'Constellation' =>array(
			1=>'白羊座',
			2=>'金牛座',
			3=>'双子座',
			4=>'巨蟹座',
			5=>'狮子座',
			6=>'处女座',
			7=>'天秤座',
			8=>'天蝎座',
			9=>'射手座',
			10=>'摩羯座',
			11=>'水瓶座',
			12=>'双鱼座'	
	  ),
	  
	  //魅力值、财富值
		'rank' =>array(
				1=>array(
						array('icon'=>'','name'=>'懵懂无知','value'=>0),
						array('icon'=>'','name'=>'懵懂无知','value'=>1000),
						array('icon'=>'','name'=>'懵懂无知','value'=>2000),
						array('icon'=>'','name'=>'情窦初开','value'=>3000),
						array('icon'=>'','name'=>'情窦初开','value'=>4000),
						array('icon'=>'','name'=>'情窦初开','value'=>5000),
						array('icon'=>'','name'=>'此最相思','value'=>6000),
						array('icon'=>'','name'=>'此最相思','value'=>7000),
						array('icon'=>'','name'=>'此最相思','value'=>8000),
						array('icon'=>'','name'=>'依依不舍','value'=>9000),
						array('icon'=>'','name'=>'依依不舍','value'=>10000),
						array('icon'=>'','name'=>'依依不舍','value'=>11000),
						array('icon'=>'','name'=>'魂牵梦萦','value'=>12000),
						array('icon'=>'','name'=>'魂牵梦萦','value'=>13000),
						array('icon'=>'','name'=>'魂牵梦萦','value'=>14000),
						array('icon'=>'','name'=>'花开并蒂','value'=>15000),
						array('icon'=>'','name'=>'花开并蒂','value'=>16000),
						array('icon'=>'','name'=>'花开并蒂','value'=>17000),
						array('icon'=>'','name'=>'你侬我侬','value'=>18000),
						array('icon'=>'','name'=>'你侬我侬','value'=>19000),
						array('icon'=>'','name'=>'你侬我侬','value'=>20000),
						array('icon'=>'','name'=>'相濡以沫','value'=>21000),
						array('icon'=>'','name'=>'相濡以沫','value'=>22000),
						array('icon'=>'','name'=>'相濡以沫','value'=>23000),
						array('icon'=>'','name'=>'刻骨铭心','value'=>104000),
						array('icon'=>'','name'=>'刻骨铭心','value'=>105000),
						array('icon'=>'','name'=>'刻骨铭心','value'=>106000),
						array('icon'=>'','name'=>'矢志不渝','value'=>107000),
						array('icon'=>'','name'=>'矢志不渝','value'=>108000),
						array('icon'=>'','name'=>'矢志不渝','value'=>109000),
						array('icon'=>'','name'=>'执子之手','value'=>110000),
						array('icon'=>'','name'=>'执子之手','value'=>111000),
						array('icon'=>'','name'=>'执子之手','value'=>112000),
						array('icon'=>'','name'=>'与子偕老','value'=>113000),
						array('icon'=>'','name'=>'与子偕老','value'=>114000),
						array('icon'=>'','name'=>'与子偕老','value'=>115000),
						array('icon'=>'','name'=>'生死契阔','value'=>116000),
						array('icon'=>'','name'=>'生死契阔','value'=>117000),
						array('icon'=>'','name'=>'生死契阔','value'=>118000),
						array('icon'=>'','name'=>'真爱不灭 ','value'=>119000),
						array('icon'=>'','name'=>'真爱不灭 ','value'=>120000),
						array('icon'=>'','name'=>'真爱不灭 ','value'=>121000),
						array('icon'=>'','name'=>'真情永恒','value'=>122000),
						array('icon'=>'','name'=>'真情永恒','value'=>123000),
						array('icon'=>'','name'=>'荡气回肠','value'=>124000),
						array('icon'=>'','name'=>'荡气回肠','value'=>125000),
						array('icon'=>'','name'=>'天若有情','value'=>126000),
						array('icon'=>'','name'=>'天若有情','value'=>127000),
						array('icon'=>'','name'=>'白首不离','value'=>128000),
						array('icon'=>'','name'=>'白首不离','value'=>129000),
					
				),
				2=>array(
					
						array('icon'=>'','name'=>'懵懂无知','value'=>0,'ltfl'=>1),//ltfl 聊天返利多%
						array('icon'=>'','name'=>'懵懂无知','value'=>1000,'ltfl'=>1),
						array('icon'=>'','name'=>'懵懂无知','value'=>2000,'ltfl'=>1),
						array('icon'=>'','name'=>'情窦初开','value'=>3000,'ltfl'=>2),
						array('icon'=>'','name'=>'情窦初开','value'=>4000,'ltfl'=>2),
						array('icon'=>'','name'=>'情窦初开','value'=>5000,'ltfl'=>2),
						array('icon'=>'','name'=>'此最相思','value'=>6000,'ltfl'=>3),
						array('icon'=>'','name'=>'此最相思','value'=>7000,'ltfl'=>3),
						array('icon'=>'','name'=>'此最相思','value'=>8000,'ltfl'=>3),
						array('icon'=>'','name'=>'依依不舍','value'=>9000,'ltfl'=>4),
						array('icon'=>'','name'=>'依依不舍','value'=>10000,'ltfl'=>4),
						array('icon'=>'','name'=>'依依不舍','value'=>11000,'ltfl'=>4),
						array('icon'=>'','name'=>'魂牵梦萦','value'=>12000,'ltfl'=>5),
						array('icon'=>'','name'=>'魂牵梦萦','value'=>13000,'ltfl'=>5),
						array('icon'=>'','name'=>'魂牵梦萦','value'=>14000,'ltfl'=>5),
						array('icon'=>'','name'=>'花开并蒂','value'=>15000,'ltfl'=>6),
						array('icon'=>'','name'=>'花开并蒂','value'=>16000,'ltfl'=>6),
						array('icon'=>'','name'=>'花开并蒂','value'=>17000,'ltfl'=>6),
						array('icon'=>'','name'=>'你侬我侬','value'=>18000,'ltfl'=>7),
						array('icon'=>'','name'=>'你侬我侬','value'=>19000,'ltfl'=>7),
						array('icon'=>'','name'=>'你侬我侬','value'=>100000,'ltfl'=>7),
						array('icon'=>'','name'=>'相濡以沫','value'=>101000,'ltfl'=>8),
						array('icon'=>'','name'=>'相濡以沫','value'=>102000,'ltfl'=>8),
						array('icon'=>'','name'=>'相濡以沫','value'=>103000,'ltfl'=>8),
						array('icon'=>'','name'=>'刻骨铭心','value'=>104000,'ltfl'=>9),
						array('icon'=>'','name'=>'刻骨铭心','value'=>105000,'ltfl'=>9),
						array('icon'=>'','name'=>'刻骨铭心','value'=>106000,'ltfl'=>9),
						array('icon'=>'','name'=>'矢志不渝','value'=>107000,'ltfl'=>10),
						array('icon'=>'','name'=>'矢志不渝','value'=>108000,'ltfl'=>10),
						array('icon'=>'','name'=>'矢志不渝','value'=>109000,'ltfl'=>10),
						array('icon'=>'','name'=>'执子之手','value'=>110000,'ltfl'=>11),
						array('icon'=>'','name'=>'执子之手','value'=>111000,'ltfl'=>11),
						array('icon'=>'','name'=>'执子之手','value'=>112000,'ltfl'=>11),
						array('icon'=>'','name'=>'与子偕老','value'=>113000,'ltfl'=>12),
						array('icon'=>'','name'=>'与子偕老','value'=>114000,'ltfl'=>12),
						array('icon'=>'','name'=>'与子偕老','value'=>115000,'ltfl'=>12),
						array('icon'=>'','name'=>'生死契阔','value'=>116000,'ltfl'=>13),
						array('icon'=>'','name'=>'生死契阔','value'=>117000,'ltfl'=>13),
						array('icon'=>'','name'=>'生死契阔','value'=>118000,'ltfl'=>13),
						array('icon'=>'','name'=>'真爱不灭 ','value'=>119000,'ltfl'=>14),
						array('icon'=>'','name'=>'真爱不灭 ','value'=>120000,'ltfl'=>14),
						array('icon'=>'','name'=>'真爱不灭 ','value'=>121000,'ltfl'=>14),
						array('icon'=>'','name'=>'真情永恒','value'=>122000,'ltfl'=>15),
						array('icon'=>'','name'=>'真情永恒','value'=>123000,'ltfl'=>15),
						array('icon'=>'','name'=>'荡气回肠','value'=>124000,'ltfl'=>16),
						array('icon'=>'','name'=>'荡气回肠','value'=>125000,'ltfl'=>16),
						array('icon'=>'','name'=>'天若有情','value'=>126000,'ltfl'=>17),
						array('icon'=>'','name'=>'天若有情','value'=>127000,'ltfl'=>17),
						array('icon'=>'','name'=>'白首不离','value'=>128000,'ltfl'=>18),
						array('icon'=>'','name'=>'白首不离','value'=>129000,'ltfl'=>20),
						
				),
			
		),

	//系统消息type
	  'Systemmsgtype' =>array(
			1=>'送礼',
			2=>'邀请',
			3=>'礼物返利',
			4=>'粉丝买VIP',
			5=>'邀请好友',			
			6=>'粉丝充值',
			7=>'私密照支出',		
			8=>'私密照收入',		
			9=>'上传照片',		
			10=>'后台添加',
	  		11=>'昵称审核',
	  		12=>'头像审核',
	  		13=>'内心独白审核',
	  		14=>'相册审核',
	  		15=>'评论审核',
	  	16=>'系统消息',
			101=>'提现',
			201=>'魅力值/财富值',
			301=>'消费点',
			3011=>'签到',
			401=>'充值',
			501=>'亲密度',
				
	  ),	
		
		//举报类型
		'Reporttypes' =>array(
			1=>'色情',
			2=>'政治敏感',
			3=>'违法(暴力恐怖，违禁品等)',
			4=>'广告骚扰',				
	  	),	
		

		//个人资料设置
		'SetProfile' =>array(
		    'code1'=>array(
		    	'name'=>'交友目的',
		    	'info'=>array(
		    		1=>'结婚',
		    		2=>'交男女朋友',
		    		3=>'排解寂寞',
		    		4=>'纯友谊',
		    		5=>'一夜情',
		    		6=>'短期恋爱',
		    		7=>'聊友',		
	    			8=>'长期约饭友',
	    			9=>'寻找刺激',
	    			10=>'找合适的人做合适的事',		
		    	)
		    		
		    ),
			'code2'=>array(
					'name'=>'对情感的态度',
					'info'=>array(
			    		1=>'经历过许多，现在只想简单',
			    		2=>'顺其自然',
			    		3=>'可接受开放式关系',
			    		4=>'希望找一个成熟男性或女性',
			    		5=>'不想认真，随便谈谈',
			    		6=>'只接受以结婚为目的的感情',
			    		7=>'先约，活好不介意进一步发展',		
		    			8=>'只想多尝试多经历',
		    			9=>'换个人重新开始才能忘记过去',
					)),
			'code3'=>array(
					'name'=>'对性爱的态度',
					'info'=>array(
			    		1=>'顺其自然随感觉',
			    		2=>'颜值控',
			    		3=>'身材控',
			    		4=>'要求对方活好',
			    		5=>'只接受已婚男性',
			    		6=>'只接受未婚男性',
						7=>'只接受已婚女性',
						8=>'只接受未婚女性',
			    		9=>'大叔控',		
		    			10=>'少妇控',
		    			11=>'熟女控',
						12=>'萝莉控',
						13=>'是异性都可以',
						14=>'只能一对一',
						15=>'可接受多人',
						16=>'聊得来才愿意',
						17=>'S/M人格',
						18=>'喜欢角色扮演',	
					)),
			'code4'=>array(
					'name'=>'婚姻状况',
					'info'=>
					array(
							1=>'未婚',
							2=>'已婚',
							3=>'离异'
							
					)),				
		),

	);