<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="black" name="apple-mobile-web-app-status-bar-style">
<meta name="format-detection" content="telephone=no">
<meta http-equiv="Cache-Control" content="no-cache, must-revalidate">
<title><?php echo ($media["title"]); ?></title>
<link rel="stylesheet" type="text/css" href="css/css.css">
<script type="text/javascript" src="js/jquery-1.8.0.min.js"></script>
</head>
<style type="text/css">
  .fx_header {
    position: absolute;
  }
</style>

<body>
  <div class="yingdao">
  <!--#include file="app_share.html"-->
     <div class="yingdao_a">
        <p><img src="images/yingdao_logo.png" alt="<?php echo C("site_title");?>"><?php echo C("site_title");?></p>
        <p>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo C("site_subtitle");?></p>
     </div>
     <div class="yingdao_b">
       <div class="cb"><a href="<?php echo U('reg',array('sex'=>1));?>" class="fl">我是男</a><a href="<?php echo U('reg',array('sex'=>2));?>" class="fr">我是女</a></div>
       <p>已有账号,<a href="<?php echo U('Public/login');?>">直接登录<img src="images/denglujiantou.png" width="9" alt="" class="yingdaojiantou"></a></p>
       <p>我已阅读并同意<?php echo C("site_title");?> <a href="###" onClick="$('.lxzizhu_shop_dc').show();">会员注册协议</a></p>
		


     </div>
<div class="lxzizhu_shop_dc" style="display: none">
    <div class="lxzizhu_shop_hei"></div>
    <div class="huiyuanzhuc">
      <div class=" cb zhuchetiaokuang"><?php echo C("site_title");?>注册条款<a class="qiandao_guanbi"><img src="images/xx.png"></a></div>
      <div class="zhuchetiaokuang_b" >
	  <?php  $tiaokuan = trim(C("site_tiaokuan")); if(empty($tiaokuan)){ ?><p>　本系统紫竹交友V3.0(以下简称&quot;紫竹交友&quot;)。紫竹交友是本身所有，专为具有中华人民共和国(&quot;中国&quot;)国籍的成年单身人士通过互联网提供微信交友服务和转发文章的一个交友软件，用户（以下也称“会员”）在此注册为征友会员并在之后进行的征友活动中应遵守以下会员注册条款：
　　1、注册条款的接受
　　一旦会员注册即表示会员已经阅读并且同意与紫竹交友达成协议，并接受所有的注册条款。
　　2、会员注册条件
　　1) 申请注册成为紫竹交友的会员应同时满足下列全部条件：
　　在注册之日以及此后使用紫竹交友服务期间您必须以恋爱或者婚姻为目的；
　　在注册之日以及此后使用紫竹交友服务期间您必须是单身状态，包括未婚、离异或是丧偶；
　　在注册之日您必须年满18周岁以上。
　　注册成为紫竹交友之会员或使用紫竹交友相关服务，表示您陈述并保证您已具备单独签定本协议，并遵守本协议所有条款之民事权利能力及民事行为能力。
　　敬请注意，如您不具备独立缔结本协议之民事权利能力或民事行为能力，或者您本人尚未年满18周岁，或者您并非单身人士，又或者您非中华人民共和国公民，则即使您通过注册获得紫竹交友之会员身份，该身份之取得也应视为自始无效。您同意在上述情况下，我们有权在任何时间注销您的会员身份并免于赔偿您因此受到的任何损失。
　　敬请注意，如您不具备独立缔结本协议之民事权利能力或民事行为能力，或者您本人尚未年满18周岁，或者您并非单身人士，又或者您非中华人民共和国公民，则您无权使用紫竹交友，在此情况下，如您因为使用紫竹交友而造成任何损失，则您同意我们无须对该等损失承担任何责任。
　　敬请注意，如您不具备独立缔结本协议之民事权利能力或民事行为能力，或者您本人尚未年满18周岁，又或者您非中华人民共和国公民，或者您并非单身人士，或者您提供虚假个人信息而在紫竹交友注册或使用紫竹交友，因此给我们造成的任何损失或导致任何第三方对我们提出任何性质的权利请求的，您应赔偿我们遭受的所有损失并保证我们免于承担该等第三方的权利请求（包括适当的时候发表声明进行澄清）。如果我们先行对第三方承担责任的，我们有权对您进行追偿，您有义务在收到我们的追偿通知时全额赔偿我们的损失（包括我们支出的调查费、律师费、诉讼费以及其他合理支出等，下同）。
　　为免歧义，此处“单身人士”系指尚未与他人缔结婚姻关系的任何具有中国国籍的公民。
　　2) 为更好的享有紫竹交友提供的服务，会员应： 向紫竹交友提供本人真实、正确、最新及完整的资料； 随时更新登记资料，保持其真实性及有效性； 提供真实有效的联系方式（包括手机号码、QQ号、微信号、陌陌号等）； 征友过程中，务必保持征友帐号的唯一性。
　　3) 若会员提供任何错误、不实或不完整的资料，或紫竹交友有理由怀疑资料为错误、不实或不完整及违反会员注册条款的，或紫竹交友有理由怀疑其会员资料、言行等有悖于“严肃纯净的交友”主题的，紫竹交友有权修改会员的注册昵称、独白等，或暂停或终止该会员的帐号，或暂停或终止提供紫竹交友提供的全部或部分服务。
　　3、服务说明
　　1) 紫竹交友在提供网络服务时，可能会对部分网络服务收取一定的费用，在此情况下，会在相关页面上做明确的提示。如会员拒绝支付该项费用，则不能使用与之相关的网络服务。付费业务将在本注册条款的基础上另行规定服务条款，以规范付费业务的内容和双方的权利义务，会员应认真阅读，如会员购买付费业务，则视为接受付费业务的服务条款。
　　2) 无论是付费业务还是紫竹交友免费提供服务，上述服务均有有效期，有效期结束后服务将自动终止，且有效期不可中断或延期。除非本注册条款另有规定，所有付费业务均不退费。
　　3) 对于利用紫竹交友服务进行非法活动，或其言行（无论线上或者线下的）背离紫竹交友严肃交友目的的，紫竹交友将严肃处理，包括将其列入黑名单、将其被投诉的情形公之于众、删除会员帐号等处罚措施。
　　4) 紫竹交友有权向其会员发送广告信，或为组织线下活动等目的，向其会员发送电子邮件、短信或电话通知。由于手机网络的特殊性，紫竹交友有权获取会员的手机信息，如手机号码或会员的基站位置等。
　　5) 为提高紫竹交友会员的交友的成功率和效率的目的，紫竹交友有权将紫竹交友会员的交友信息在紫竹交友的合作网站上进行展示或其他类似行为。
　　
　　4、免责条款
　　1) 紫竹交友不保证其提供的服务一定能满足会员的要求和期望，也不保证服务不会中断，对服务的及时性、安全性、准确性都不作保证。
　　2) 对于会员通过紫竹交友提供的服务传送的内容，紫竹交友会尽合理努力按照国家有关规定严格审查，但无法完全控制经由网站服务传送的内容，不保证内容的正确性、完整性或品质。因此会员在使用紫竹交友服务时，可能会接触到令人不快、不适当或令人厌恶的内容。在任何情况下，紫竹交友均不为会员经由网站服务以张贴、发送电子邮件或其它方式传送的任何内容负责。但紫竹交友有权依法停止传输任何前述内容并采取相应行动，包括但不限于暂停会员使用网站服务的全部或部分，保存有关记录，并根据国家法律法规、相关政策在必要时向有关机关报告并配合有关机关的行动。
　　3) 对于紫竹交友网站提供的各种广告信息、链接、资讯等，紫竹交友会对广告内容进行初步审核，但是紫竹交友难以确保对方产品真实性、合法性或可靠性，由于产品购买导致的相关责任主要由广告商承担；敬告用户理性看待，如需购买或者交易，请谨慎考虑。并且，对于会员经由紫竹交友服务与广告商进行联系或商业往来，完全属于会员和广告商之间的行为，与紫竹交友无关。对于前述商业往来所产生的任何损害或损失，紫竹交友不承担任何责任。
　　4) 对于用户上传的照片、资料、证件等，紫竹交友已采用相关措施并已尽合理努力进行审核，但不保证其内容的正确性、合法性或可靠性，相关责任由上传上述内容的会员负责。
　　5) 会员以自己的独立判断从事与交友相关的行为，并独立承担可能产生的不利后果和责任，紫竹交友不承担任何法律责任。
　　6)依据有关法律法规的规定或依据行政机关、司法机关、检察机关的要求，向其提供会员的基本信息或站内聊天信息，上述行为侵犯会员隐私权的，紫竹交友不承担任何法律责任。
　　5、会员应遵守以下法律法规：
　　1) 紫竹交友提醒会员在使用紫竹交友服务时，遵守《中华人民共和国合同法》、《中华人民共和国著作权法》、《全国人民代表大会常务委员会关于维护互联网安全的决定》、《中华人民共和国保守国家秘密法》、《中华人民共和国电信条例》、《中华人民共和国计算机信息系统安全保护条例》、《中华人民共和国计算机信息网络国际联网管理暂行规定》及其实施办法、《计算机信息系统国际联网保密管理规定》、《互联网信息服务管理办法》、《计算机信息网络国际联网安全保护管理办法》、《互联网电子公告服务管理规定》等相关中国法律法规的规定。
　　2) 在任何情况下，如果紫竹交友有理由认为会员使用紫竹交友服务过程中的任何行为，包括但不限于会员的任何言论和其它行为违反或可能违反上述法律和法规的任何规定，紫竹交友可在任何时候不经任何事先通知终止向该会员提供服务。
　　6、禁止会员从事下列行为:
　　1)发布信息或者利用紫竹交友的服务时在紫竹交友的网页上、微信上或者利用紫竹交友的服务制作、复制、发布、传播以下信息：反对宪法所确定的基本原则的；危害国家安全，泄露国家秘密，颠覆国家政权，破坏国家统一的；损害国家荣誉和利益的；煽动民族仇恨、民族歧视、破坏民族团结的；破坏国家宗教政策，宣扬邪教和封建迷信的；散布谣言，扰乱社会秩序，破坏社会稳定的；散布淫秽、色情、赌博、暴力、凶杀、恐怖或者教唆犯罪的；侮辱或者诽谤他人，侵害他人合法权利的；含有虚假、有害、胁迫、侵害他人隐私、骚扰、侵害、中伤、粗俗、猥亵、或其它有悖道德、令人反感的内容；含有中国法律、法规、规章、条例以及任何具有法律效力的规范所限制或禁止的其它内容的。
　　2) 使用紫竹交友服务的过程中，以任何方式危害未成年人的利益的。
　　3) 冒充任何人或机构，包含但不限于冒充紫竹交友工作人员、管理员、客服，或以虚伪不实的方式陈述或谎称与任何人或机构有关的。
　　4) 将侵犯任何人的肖像权、名誉权、隐私权、专利权、商标权、著作权、商业秘密或其它专属权利的内容上载、张贴、发送电子邮件或以其它方式传送的。
　　5) 将病毒或其它计算机代码、档案和程序，加以上载、张贴、发送电子邮件或以其它方式传送的。
　　6) 跟踪或以其它方式骚扰其他会员的。
　　7) 未经合法授权而截获、篡改、收集、储存或删除他人个人信息、电子邮件或其它数据资料，或将获知的此类资料用于任何非法或不正当目的。
　　8) 以任何方式干扰紫竹交友服务的。
　　7、关于会员在紫竹交友的上传或张贴的内容
　　1) 会员在紫竹交友上传或张贴的内容（包括照片、文字、交友成功会员的成功故事等），视为会员授予紫竹交友免费、非独家的使用权，紫竹交友有权为展示、传播及推广前述张贴内容的目的，对上述内容进行复制、修改、出版等。该使用权持续至会员书面通知紫竹交友不得继续使用，且紫竹交友实际收到该等书面通知时止。紫竹交友合作伙伴使用或在现场活动中使用，紫竹交友将事先征得会员的同意，但在紫竹交友内使用不受此限。
　　2) 因会员进行上述上传或张贴，而导致任何第三方提出侵权或索赔要求的，会员承担全部责任。
　　3) 任何第三方对于会员在紫竹交友的公开使用区域张贴的内容进行复制、修改、编辑、传播等行为的，该行为产生的法律后果和责任均由行为人承担，与紫竹交友无关。
　　8、会员注册条款的变更和修改
　　紫竹交友有权随时对本注册条款进行变更和修改。一旦发生注册条款的变动，紫竹交友将在页面上提示修改的内容，或将最新版本的会员注册条款以邮件的形式发送给会员。会员如果不同意会员注册条款的修改，可以主动取消会员资格（如注销账号），如对部分服务支付了额外的费用，可以申请将费用全额或部分退回。如果会员继续使用会员帐号，则视为会员已经接受会员注册条款的修改。
　　紫竹交友对本交友软件有唯一的升级修改权限，并有权终止一部分版本的使用。请及时升级最新版本。
　　紫竹交友付费用户协议
　　为规范紫竹交友付费业务的内容以及付费用户的权利和义务，特制定本条款。本服务条款仅针对付费用户且必须在完全接受《紫竹交友注册协议》基础的补充协议。
　　1.协议条款的接受
　　1）付费用户是指成功注册紫竹交友用户账号，并充值付费成为VIP会员以使用或实际已使用紫竹交友VIP相关服务的个人。
　　2）特别提醒：本协议是用户注册账号并充值付费及实际使用紫竹交友提供的相关VIP服务时适用的通用条款。因此，请您在注册紫竹交友用户账号并充值时，或接受使用紫竹交友针对付费用户提供的相关VIP服务之前，详细地阅读本协议的所有内容。
　　3）用户了解并同意：无论您事实上是否认真阅读，只要您完成账号注册并充值，或者实际接受了紫竹交友的相关VIP服务，就表示您已接受了本协议及紫竹交友公布的各相关VIP服务规则（包括但不限于付费用户使用规则、充值规则等），并愿意受其约束。如果发生纠纷，您不能以未仔细阅读为由进行抗辩。
　　4）只要您完成账号注册并充值，即表示您已接受相关服务的价格及期限等规则，完成充值时紫竹交友所公布的相应服务价格与服务期限即视为双方协议约定并接受。紫竹交友有可能随时更改本协议与相关服务规则（包括相关服务的价格与期限等），不论修改后相关服务的价格提高与降低或同等价值服务期限的延长或减少，在修改前完成充值的付费用户与紫竹交友的双方约定不变。而本协议或服务规则（包括服务的价格与期限等）修改后完成充值的用户，则视为双方接受修改后的本协议与相关服务规则之约定。
　　5）本协议与服务规则的修改将于相关页面公告修改的事实，而不另行对付费用户进行个别通知，敬请定期查询。如果您不同意本协议、各服务规则，或紫竹交友对本协议、各服务规则的修改，您可以主动停止使用紫竹交友服务。如果您仍继续使用紫竹交友服务的，则视为您同意紫竹交友对本协议及各服务规则的修改，修改后的用户协议、服务规则自公布之日起生效。
　　6）一旦充值成功，用户将无法取消充值，且用户不得以任何理由要求紫竹交友退还全部或部分已充值款项，紫竹交友存在过错的除外。 注意：为完善用户体验，紫竹交友本着集思广益的原则，作以下这规定：若您能为紫竹交友提供好的建议或意见；或您在完成充值成为付费用户3个工作日觉得体验不佳，并且您有非常好的提升相关体验之建议或意见；可以联系紫竹交友客服申请退款，紫竹交友客服将在收到您的反馈信息后3个工作日内给您回复并告之最终处理结果，本条款的相应解释权及决断权归紫竹交友所有。
　　2. 相关服务使用规则
　　1）用户在紫竹交友注册账号并充值付费后，可以享受紫竹交友根据实际情况针对付费用户提供的相关服务（简称“付费用户服务”）。内容可能包括：聊天畅通不受限制；找人搜索排名靠前；首页大图推荐；相关联系方式和定位；自定义招呼内容；向附近用户一键批量发送招呼信息；享有尊贵VIP图标；个人帐号消费记录查询；以及其他紫竹交友提供的，标明为付费用户所享有的服务。
　　2）付费用户服务的具体内容、用户对象、费用、参与方式、参与条件等，紫竹交友会在相关页面上做明确的提示，如果用户拒绝支付相应费用或不同意相关内容，则无法使用相应服务。紫竹交友将有权决定并随时修改所提供的付费用户服务的内容、条件、资费标准、期限和收费方式等（包括免费到收费或收费到免费的修改，不同阶段不同收费，不同用户级别不同收费，服务提供或取消等）。
　　3）付费用户在注册账号时应提供完整、详尽、真实的个人资料，如个人资料有变更，应及时登录账号进行更新。付费用户了解并同意：账号一旦设定，就不可再为变更；密码则可以在紫竹交友上进行更改；如因付费用户不及时更新或提供不准确资料造成无法使用付费用户服务的，紫竹交友不承担任何责任。
　　4）付费用户注册成功账号后，有义务妥善保管其账号和密码，应对其账号进行的活动和行为负法律责任。如因付费用户自身过错（包括但不限于转让账号、与他人共用、自己泄露等）或付费用户手机或其他移动设备感染病毒或木马，导致账号或密码泄漏、遗失的，付费用户应自行承担由此造成的损失，紫竹交友将保留对处理付费用户账号或密码遗失等问题所提供的服务索取服务费用的权利。
　　5）若付费用户发现其账号或密码遭他人非法使用或有异常使用的情形，应立即通知紫竹交友，并提交该账号为其本人所有的有关证明，以便申请该账号的暂停使用，因此而造成的损失，紫竹交友不承担赔偿责任。
　　6）付费用户对其所浏览、查阅的用户联系资料、联系方式等内容，以及与其他会员的聊天记录等，应当承担不泄露、不复制、不传播、不转发、不利用付费用户资格进行任何损害其用户或紫竹交友利益行为的义务。
　　7）付费用户应按紫竹交友制定并公布的服务规则、内容等，使用相关服务。
　　8）付费用户在使用紫竹交友网络服务(包括但不限于付费用户服务)过程中，必须遵循以下原则：
　　a、遵守中华人民共和国有关的法律和法规；
　　b、不得为任何非法目的而使用网络服务系统；
　　c、遵守所有与网络服务有关的网络协议、规定和程序；
　　d、不得进行任何可能损害、攻击、使服务器过度负荷或其他可能影响、破坏紫竹交友所提供服务的行为；
　　e、不得利用紫竹交友网络服务系统进行任何可能对互联网的正常运转造成不利影响或可能干扰他人以正常方式使用紫竹交友所提供的服务的行为；
　　f、不得利用紫竹交友网络服务系统传输任何骚扰性、中伤他人、辱骂性、恐吓性、庸俗淫秽、侵犯他人知识产权或公众/私人权利的其他任何非法的信息资料；
　　g、不得利用紫竹交友网络服务系统进行其他不利于紫竹交友的行为；
　　h、就紫竹交友及合作商业伙伴的服务、产品、业务咨询应采取相应机构提供的沟通渠道，不得在公众场合发布有关紫竹交友及相关服务的负面宣传；
　　i、如发现任何非法使用用户账号或账号出现异常使用的情况，应立即通告紫竹交友。
　　3.隐私保护
　　1）保护用户（特别是未成年人）的隐私是紫竹交友的一项基本政策。紫竹交友禁止任何未满18周岁的未成年人注册成为紫竹交友用户或紫竹交友付费用户。
　　2）紫竹交友将采取商业上合理的方式来保护付费用户个人资料的安全，即使用通常可以获得的安全的技术和程序来保护付费用户的个人资料，保证付费用户的个人资料在未经授权的情况下不被访问、使用或泄漏。对于非因紫竹交友的过错而造成付费用户账号的丢失或付费用户个人资料的泄密，紫竹交友将不承担任何责任。
　　3）紫竹交友承诺不会公开付费用户注册资料中的真实姓名、身份证号码、家庭住址、通讯地址、电子邮箱、密码、银行卡或其他第三方支付帐号等信息有关的任何个人资料。但如果出现下列情况将不在此承诺范围内：
　　a、付费用户允许紫竹交友披露这些个人资料；
　　b、有关法律法规或行政规章要求紫竹交友披露付费用户的个人资料；
　　c、司法机关或行政机关基于法定程序要求紫竹交友披露付费用户的个人资料；
　　d、为保护紫竹交友的知识产权和其他财产权益，需要披露付费用户的个人资料；
　　e、在紧急情况下为保护其他用户和社会大众的人身安全，需要披露付费用户的个人资料；
　　f、本公司合理怀疑有危害国家安全事情发生时，本公司主动将相关资料供公安机关调查处理；
　　g、紫竹交友可能会与第三方合作向用户提供相关的网络服务，在此情况下，如该第三方同意承担与紫竹交友同等的保护付费用户隐私的责任，则紫竹交友可将付费用户的注册资料等提供给该第三方。
　　4）在不透露付费用户隐私资料的前提下，紫竹交友有权对整个用户数据库进行技术分析并对分析、整理后的用户数据库进行商业上的利用。该商业利用包括但不限于：紫竹交友及其关系企业或合作对象对用户资料进行搜集、处理、保存、传递及使用，以做成会计资料、进行网络行为调查及研究、提供用户其它信息及服务或进行其它各种方式的合法使用。
　　5)紫竹交友付费业务的在线付费、充值等付费技术与接口均由合作的支付宝、财付通、易宝、易联等第三方支付平台提供相关之服务，相应的技术支持与帐号与密码安全保障等均由相应的第三方支付平台提供保护，紫竹交友承诺不会收集任何付费用户的付款帐号、银行卡号、密码等信息。
　　4. 服务中断、终止
　　1）发生下列情形之一时，即使未经通知，紫竹交友有权停止或中断所提供的服务（包括但不限于付费用户服务）：
　　a、定期或不定期地对紫竹交友的网络设备进行必要的保养及施工；
　　b、因紫竹交友、紫竹交友的合作方或电信网络系统软硬件设备的故障、失灵或人为操作的疏失；
　　c、他人侵入紫竹交友的网络，篡改、删改或伪造、编造网站数据；
　　d、灾害或其他不可抗力原因；
　　<p>　　e、由于相关机构基于法律或法定程序的要求；
　　f、其他基于法律或国家政策的规定。
　　2）除前款外，付费用户或紫竹交友可随时根据实际情况终止全部或部分服务。
　　5. 取消账号和终止、暂停服务
　　付费用户有如下任意一种或多种行为，紫竹交友有权随时取消其账号或终止或暂停（包括但不限于封停该用户账号或限期禁止登录）对该用户的全部或部分服务（包括但不限于付费用户服务），紫竹交友不承担任何责任，且付费用户账号已充值部分不予返还：
　　1）违反本协议或相关服务规则的行为；
　　2）滥用其付费用户权利；
　　3）提供虚假注册信息；
　　4）通过不正当手段使用紫竹交友网络服务；
　　5）有损紫竹交友及其权利人、关系企业或合作对象的权益和其他用户合法权益的行为；
　　6）恶意传播、转发、泄露紫竹交友其他紫竹交友用户隐私等，或其他侵犯紫竹交友、紫竹交友用户或其他第三人合法权利的行为，给紫竹交友、紫竹交友用户或其他第三人造成损失的；
　　7）因自身过错导致账号遗失给紫竹交友或其他第三人造成损失的；
　　8）违反中国的法律、法规；
　　9）违背社会风俗和社会道德的行为；
　　10）其他违反紫竹交友相关规定的行为。
　　6．免责声明
　　1）在适用法律允许的最大范围内，紫竹交友明确表示不提供任何其他类型的保证：包括但不限于适销性、适用性、可靠性、准确性、完整性、无病毒、无错误、满足用户要求、及时性、安全性等的任何明示、默示保证和责任。
　　2）在适用法律允许的最大范围内，紫竹交友不就因付费用户使用紫竹交友的服务引起的，或在任何方面与紫竹交友的服务有关的任何意外的、非直接的、特殊的、或间接的损害或请求（包括但不限于因人身伤害、因隐私泄漏、因未能履行包括诚信或合理谨慎在内的任何责任、因过失和因任何其他金钱上的损失或其他损失而造成的损害赔偿）承担任何责任。
　　3）付费用户若反对任何本协议的条款或对后来的协议的修改有异议，或对紫竹交友的服务不满，付费用户有以下的追索权：
　　a、不再使用紫竹交友付费用户服务或所有服务；
　　b、请求紫竹交友注销其账号；
　　c、通告紫竹交友停止其接受紫竹交友付费用户服务的资格。
　　7．违约责任
　　1）付费用户同意保障和维护紫竹交友及其他用户的利益，如因付费用户违反有关法律、法规或本协议项下的任何条款而给紫竹交友或任何其他第三人造成损失，付费用户同意承担由此造成的损害赔偿责任。
　　2）用户利用紫竹交友平台或者紫竹交友漏洞非法获取收益的，紫竹交友有权扣除用户账户内非法所得部分，并保留对用户追究法律责任的权利。
　　8.法律管辖
　　本协议相关规范的解释及适用，以及付费用户因使用紫竹交友相关服务而与紫竹交友之间所产生的权利义务关系，应适用中华人民共和国法律（不含涉外民事法律适用法或其他类似法规）。因此所产生的争议，均应以锦尚中国源码论坛提供所在地法院为第一审管辖法院。此外，如果本协议的任何内容与法律相抵触，应以法律规定为准，而本协议的其他部分保持对付费用户的法律效力。
　　9．其他规定
　　如本协议中的任何条款无论因何种原因完全或部分无效或不具有执行力，本协议的其余条款仍应有效并且对协议各方有约束力。
　　本协议中的标题仅为方便而设，不具法律或契约效果。
　　紫竹交友制定并在网站上公布的各种服务规则、帮助条款、用户提示等，为本协议有效组成部分，与本协议具同等法律效力。请用户在使用相关服务时，仔细阅读有关条款。
　　紫竹交友</p>
		<?php }else{echo $tiaokuan;}?>
      </div>
      <input type="button" class="input_button"  value="同意">
    </div>
  </div>
</div>

   <script type="text/javascript">
     $(".qiandao_guanbi,.input_button").click(function(){
       $(".lxzizhu_shop_dc").hide(500);

     })
   var apcs =$(window).height();
   var apcs2 = apcs-40-65;
   $(".huiyuanzhuc").css('height',apcs2)
   var dangchuangao = $(".huiyuanzhuc").height();
   var dangchuangao2 = dangchuangao-84;
   $(".zhuchetiaokuang_b").css('height',dangchuangao2)
   </script>

</body>

</html>