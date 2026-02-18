<!DOCTYPE html><html><head> 
<meta http-equiv="Content-Type" content="text/html;charset=utf-8"> 
<title>Game Info - Newbie - Login to Game - T-Bot Online</title> 
<link href="../../../../images/favicon.ico" rel="shortcut icon">
<link href="../../../../css/main.css" media="screen" rel="stylesheet" type="text/css">
<link href="../../../../css/toolbar.css" media="screen" rel="stylesheet" type="text/css">
<link href="../../../../css/cbt2.css" media="screen" rel="stylesheet" type="text/css"><script type="text/javascript" src="../../../../lib/OG/OG.js"></script> 
<style type="text/css">
<!--
    @import "../../../../lib/dojo/dojo-release-1.5.0/dijit/themes/tundra/tundra.css";
-->
</style>
<script type="text/javascript">
//<![CDATA[
    var djConfig = {"parseOnLoad":"true"};
//]]>
</script>
<script type="text/javascript" src="../../../../lib/dojo/dojo-release-1.5.0/dojo/dojo.js"></script>

<script type="text/javascript">
//<![CDATA[
dojo.require("dojo.fx");
    dojo.require("dojo.NodeList-fx");
//]]>

</script> 
<script type="text/javascript" src="../../../../lib/md5/md5.js"></script>
<script type="text/javascript">
 var _gaq = _gaq || [];
 _gaq.push(['_setAccount', 'UA-20407076-2']);
 _gaq.push(['_trackPageview']);

 (function() {
   var ga = document.createElement('script'); ga.type =
'text/javascript'; ga.async = true;
   ga.src = ('https:' == document.location.protocol ? 'https://ssl' :
'http://www') + '.google-analytics.com/ga.js';
   var s = document.getElementsByTagName('script')[0];
s.parentNode.insertBefore(ga, s);
 })();
</script>
		<script lang="JavaScript">
		function gotoTbot() {
			jsOpenWin('../../../..');
		}
		function moToggler(bid,cls,cannotChange) {
			if(cannotChange!=true) dojo.toggleClass(bid, cls);
		}
		</script>
</head> 
<body class="tundra" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0"> 
<div style="background-image: url(../../../../images/cbt/header_back.jpg); background-repeat: no-repeat; background-position: center top; width: 100%; position:relative;display: table;">
	
	<div id="oToolbarContainer" style="width: 1005px;">
		<div id="oToolbarMainContainer">
			<div id="oToolbarBtnOrange" onclick="jsOpenWin(&quot;http://www.orangegame.co.id&quot;)"></div>
			<div id="oToolbarBtnForum" onclick="jsOpenWin(&quot;http://forum.orangegame.co.id&quot;)"></div>
			<div id="oToolbarBtnTbot" onclick="jsOpenWin(&quot;http://tbot.orangegame.co.id&quot;)"></div>
			<div id="oToolbarBtnMdo" onclick="jsOpenWin(&quot;http://mahadewa.orangegame.co.id&quot;)"></div>
			<div id="oToolbarBtnDK" onclick="jsOpenWin(&quot;http://dk.orangegame.co.id&quot;)"></div>
			<div id="oToolbarBtnCE" onclick="jsOpenWin(&quot;http://crystal.orangegame.co.id&quot;)"></div>
			<div id="oToolbarBtnDS" onclick="jsOpenWin(&quot;http://ds.orangegame.co.id&quot;)"></div>
			<div id="oToolbarBtnRS" onclick="jsOpenWin(&quot;http://rich.orangegame.co.id&quot;)"></div>
			<div id="oToolbarBtnWOT" onclick="jsOpenWin(&quot;http://wot.orangegame.co.id&quot;)"></div>
			<div id="oToolbarBtnPoker" onclick="jsOpenWin(&quot;http://poker.orangegame.co.id&quot;)"></div>
			<div id="oToolbarBtnYouTube" onclick="jsOpenWin(&quot;http://www.youtube.com/user/OGInteractive&quot;)"></div>
			<div id="oToolbarBtnFB" onclick="jsOpenWin(&quot;http://www.facebook.com/orangegame.id&quot;)"></div>
			<div id="oToolbarBtnTwitter" onclick="jsOpenWin(&quot;https://twitter.com/OrangeGameID&quot;)"></div>
			<div id="oToolbarBtnEMail" onclick="jsRedirectTo(&quot;mailto:community@orangegame.co.id&quot;)"></div>
			
<script>
/*<![CDATA[*/
var stopScrollUp=false;
var curHidden=true;
function doani(out) {
	if(out) {
		var anim1 = dojo.fx.slideTo({ node: "login_panel" ,left:"695px",top:"0",unit:"px" });
		dojo.fx.chain([anim1]).play();
		curHidden=false;
	} else {
		if(! stopScrollUp) {
			var anim1 = dojo.fx.slideTo({ node: "login_panel" ,left:"695px",top:"-100",unit:"px" });
			dojo.fx.chain([anim1]).play();
			curHidden=true;
		}
	}
}
function aniStopScroll(st) {
	stopScrollUp=st;
	if(! st) {
		if(! curHidden) doani(false);
	} else {
		if(curHidden) doani(true);
	}
}
dojo.addOnLoad(function() {
	dojo.style(dojo.byId('passw-clear'), 'display', 'block');
	dojo.style(dojo.byId('passw'), 'display', 'none');
	dojo.connect(dojo.byId('passw-clear'), 'onfocus', function() {
		dojo.style(dojo.byId('passw-clear'), 'display', 'none');
		dojo.style(dojo.byId('passw'), 'display', 'block');
        dojo.byId('passw').focus();
	});
	dojo.connect(dojo.byId('passw'), 'onblur', function() {
		if(document.getElementById('passw').value=='') {
			dojo.style(dojo.byId('passw-clear'), 'display', 'block');
			dojo.style(dojo.byId('passw'), 'display', 'none');
		}
	});
});
/*]]>*/
</script>
<div id="login_panel" onmouseover="doani(true)" onmouseout="doani(false)" onfocus="aniStopScroll(true)" onblur="aniStopScroll(false)" tabindex="0">
    <div id="frmLoginInput">
		<form id="frm_login" name="frm_login" method="post" action="../../../../auth/login.php" onsubmit="return(js2test(this.id))" autocomplete="off">
			<input type="hidden" name="passx" id="passx" value="">
			<input type="hidden" name="service" id="service" value="tbot">
			<input type="text" name="user_id" id="user_id" class="pnltxt_user_id" value="Orange ID" onfocus="aniStopScroll(true)" onblur="if(this.value==&quot;&quot;)this.value=&quot;Orange ID&quot;;aniStopScroll(false)" onclick="if(this.value=='Orange ID')this.value='';">
			<input type="password" name="passw" id="passw" class="pnltxt_passwd" value="" onfocus="aniStopScroll(true)" onblur="aniStopScroll(false)">
			<input type="text" name="passw-clear" id="passw-clear" class="pnltxt_passwd" value="Password" onfocus="aniStopScroll(true)" onblur="aniStopScroll(false)">
			<input type="image" class="pnlBtnLogin" src="http://www.orangegame.co.id/css/toolbar/panel_new/btn_login.png" onfocus="aniStopScroll(true)" onblur="aniStopScroll(false)">
		</form>
	</div>
	<div class="pnlLoginHere"></div>
    <div class="pnlForgetPass" tabindex="0" onfocus="aniStopScroll(true)" onblur="aniStopScroll(false)">
		<a href="index.php" onclick="jsRedirectTo(&quot;http://www.orangegame.co.id/forgetpass&quot;)">Lupa Password?</a>
	</div>
    <div class="pnlRegister" onclick="jsRedirectTo(&quot;../../../../register.php&quot;)" tabindex="0" onfocus="aniStopScroll(true)" onblur="aniStopScroll(false)"></div>
	
	<div class="pnlSocial">
		<div class="title">
			Social Login
		</div>
		<div class="lists">
			<div class="item" onclick="ligSocial('facebook','http://tbot.orangegame.co.id/')"><img src="../../../../css/toolbar/social/fb.png" alt="Facebook" title="Facebook" border="0"></div>
			<div class="item" onclick="ligSocial('twitter','http://tbot.orangegame.co.id/')"><img src="../../../../css/toolbar/social/twitter.png" alt="Twitter" title="Twitter" border="0"></div>
			<div class="item" onclick="ligSocial('google','http://tbot.orangegame.co.id/')"><img src="../../../../css/toolbar/social/google.png" alt="Google Account" title="Google Account" border="0"></div>
			<div class="item" onclick="ligSocial('yahoo','http://tbot.orangegame.co.id/')"><img src="../../../../css/toolbar/social/yahoo.png" alt="Yahoo Account" title="Yahoo Account" border="0"></div>
			<div class="clearer"></div>
		</div>
	</div>

</div>
		</div>
	</div>
	<div id="pageWrapper" style="background-image: url(../../../../images/cbt/header_back.jpg); background-repeat: no-repeat; background-position: center top;">
		<div style="padding: 0 0 0 0;">
			<div style="width: 100%; height: 220px; padding: 1px 0 0 0; position:relative; margin-bottom:10px;">
				<div style="width: 943px; height: 61px; position:absolute; top: 161px; left: 31px;">
					<div id="b_home" class="btn_home" onclick="jsRedirectTo('../../../..')" onmouseover="moToggler(&quot;b_home&quot;, &quot;btn_home_over&quot;)" onmouseout="moToggler(&quot;b_home&quot;, &quot;btn_home_over&quot;)"></div>
					<div id="b_news" class="btn_news" onclick="jsOpenWin('http://forum.orangegame.co.id/viewforum.php?f=10')" onmouseover="moToggler(&quot;b_news&quot;, &quot;btn_news_over&quot;)" onmouseout="moToggler(&quot;b_news&quot;, &quot;btn_news_over&quot;)"></div>
					<div id="b_gameinfo" class="btn_gameinfo btn_gameinfo_over" onmouseover="moToggler(&quot;b_gameinfo&quot;, &quot;btn_gameinfo_over&quot;, true)" onmouseout="moToggler(&quot;b_gameinfo&quot;, &quot;btn_gameinfo_over&quot;, true)"></div>
					<div id="b_forum" class="btn_forum" onclick="jsOpenWin('http://forum.orangegame.co.id')" onmouseover="moToggler(&quot;b_forum&quot;, &quot;btn_forum_over&quot;)" onmouseout="moToggler(&quot;b_forum&quot;, &quot;btn_forum_over&quot;)"></div>
					<div id="b_download" class="btn_download" onclick="jsRedirectTo('../../../../downloads')" onmouseover="moToggler(&quot;b_download&quot;, &quot;btn_download_over&quot;)" onmouseout="moToggler(&quot;b_download&quot;, &quot;btn_download_over&quot;)"></div>
					<div id="b_support" class="btn_support" onclick="jsRedirectTo('../../../../contact')" onmouseover="moToggler(&quot;b_support&quot;, &quot;btn_support_over&quot;)" onmouseout="moToggler(&quot;b_support&quot;, &quot;btn_support_over&quot;)"></div>
					<div id="b_rank" class="btn_rank" onclick="jsRedirectTo('../../../../ranks')" onmouseover="moToggler(&quot;b_rank&quot;, &quot;btn_rank_over&quot;)" onmouseout="moToggler(&quot;b_rank&quot;, &quot;btn_rank_over&quot;)"></div>
					<div id="b_itemmall" class="btn_itemmall" onclick="jsRedirectTo('../../../../itemmall')" onmouseover="moToggler(&quot;b_itemmall&quot;, &quot;btn_itemmall_over&quot;)" onmouseout="moToggler(&quot;b_itemmall&quot;, &quot;btn_itemmall_over&quot;)"></div>
				</div>
			</div>
						<div style="width: 100%; display: table; border-collapse:collapse; ">
				<div style="width: 1006px;display: table-row;">
					<div style="width: 258px; float:left; position:relative; display: table-cell; margin: 0 0 0 42px;">
						<div class="submenu_outer">
	<div class="submenu_inner">
		<div id="submenu_item_gi_story" class="submenu_item submenu_item_gi_story " onclick="jsRedirectTo('../../..')" onmouseover="jsOverToggler(&quot;submenu_item_gi_story&quot;, &quot;submenu_item_gi_story_over&quot;)" onmouseout="jsOverToggler(&quot;submenu_item_gi_story&quot;, &quot;submenu_item_gi_story_over&quot;)"></div>
		<div id="submenu_item_gi_dli" class="submenu_item submenu_item_gi_dli " onclick="jsRedirectTo('../../../install')" onmouseover="jsOverToggler(&quot;submenu_item_gi_dli&quot;, &quot;submenu_item_gi_dli_over&quot;)" onmouseout="jsOverToggler(&quot;submenu_item_gi_dli&quot;, &quot;submenu_item_gi_dli_over&quot;)"></div>
		<div id="submenu_item_gi_newbie" class="submenu_item submenu_item_gi_newbie  submenu_item_gi_newbie_over" onmouseover="jsOverToggler(&quot;submenu_item_gi_newbie&quot;, &quot;submenu_item_gi_newbie_over&quot;, true)" onmouseout="jsOverToggler(&quot;submenu_item_gi_newbie&quot;, &quot;submenu_item_gi_newbie_over&quot;, true)"></div>
		<div id="submenu_item_gi_howto" class="submenu_item submenu_item_gi_howto " onclick="jsRedirectTo('../../../howtoplay')" onmouseover="jsOverToggler(&quot;submenu_item_gi_howto&quot;, &quot;submenu_item_gi_howto_over&quot;)" onmouseout="jsOverToggler(&quot;submenu_item_gi_howto&quot;, &quot;submenu_item_gi_howto_over&quot;)"></div>
		<div id="submenu_item_gi_gamemode" class="submenu_item submenu_item_gi_gamemode " onclick="jsRedirectTo('../../../gamemode')" onmouseover="jsOverToggler(&quot;submenu_item_gi_gamemode&quot;, &quot;submenu_item_gi_gamemode_over&quot;)" onmouseout="jsOverToggler(&quot;submenu_item_gi_gamemode&quot;, &quot;submenu_item_gi_gamemode_over&quot;)"></div>
		<div id="submenu_item_gi_character" class="submenu_item submenu_item_gi_character " onclick="jsRedirectTo('../../../character')" onmouseover="jsOverToggler(&quot;submenu_item_gi_character&quot;, &quot;submenu_item_gi_character_over&quot;)" onmouseout="jsOverToggler(&quot;submenu_item_gi_character&quot;, &quot;submenu_item_gi_character_over&quot;)"></div>
		<div id="submenu_item_gi_items" class="submenu_item submenu_item_gi_items " onclick="jsRedirectTo('../../../items')" onmouseover="jsOverToggler(&quot;submenu_item_gi_items&quot;, &quot;submenu_item_gi_items_over&quot;)" onmouseout="jsOverToggler(&quot;submenu_item_gi_items&quot;, &quot;submenu_item_gi_items_over&quot;)"></div>
	</div>
</div>
						<div>
	<div class="btn_newbie_guide" onclick="jsRedirectTo('../..')">
	</div>
	<div class="btn_cash" onclick="jsRedirectTo('http://orangegame.co.id/cash')">
	</div>
	<div class="btn_download_game" onclick="jsRedirectTo('../../../../downloads')">
	</div>
	<div class="btn_voucher_new" onclick="jsOpenWin('http://www.unopay.co.id/')">
	</div>
	<div class="btn_itemmall_new" onclick="jsRedirectTo('../../../../itemmall')">
	</div>
</div>
<div>
	<div class="bnr_banner_obt">
	</div>
</div>					</div>
					<div style="width: 664px; float:left; position:relative; display: table-cell;">
						<div style="vertical-align: top; ">
							<div class="c_box_big_outer">
	<div class="c_box_big_header_no_title">
	</div>
	<div class="c_box_big_content_outer">
		<div class="c_box_big_content_inner">
			<div class="c_box_big_left"></div>
			<div class="c_box_big_content_wrapper">

				<div style="margin: 0 0 25px 0;">
					<div style="display: table;">
						<div style="display: table-row;">
							<div id="submenu_01" style="display: table-cell;" class="submenu_sub_gi_newbie_01 " onclick="jsRedirectTo('../1')" onmouseover="jsOverToggler(&quot;submenu_01&quot;, &quot;submenu_sub_gi_newbie_01_over&quot;)" onmouseout="jsOverToggler(&quot;submenu_01&quot;, &quot;submenu_sub_gi_newbie_01_over&quot;)"></div>
							<div style="width: 5px; height: 100%;"></div>
							<div id="submenu_02" style="display: table-cell;" class="submenu_sub_gi_newbie_02  submenu_sub_gi_newbie_02_over" onmouseover="jsOverToggler(&quot;submenu_02&quot;, &quot;submenu_sub_gi_newbie_02_over&quot;, true)" onmouseout="jsOverToggler(&quot;submenu_02&quot;, &quot;submenu_sub_gi_newbie_02_over&quot;, true)"></div>
							<div style="width: 5px; height: 100%;"></div>
							<div id="submenu_03" style="display: table-cell;" class="submenu_sub_gi_newbie_03 " onclick="jsRedirectTo('../3')" onmouseover="jsOverToggler(&quot;submenu_03&quot;, &quot;submenu_sub_gi_newbie_03_over&quot;)" onmouseout="jsOverToggler(&quot;submenu_03&quot;, &quot;submenu_sub_gi_newbie_03_over&quot;)"></div>
							<div style="width: 5px; height: 100%;"></div>
							<div id="submenu_04" style="display: table-cell;" class="submenu_sub_gi_newbie_04 " onclick="jsRedirectTo('../4')" onmouseover="jsOverToggler(&quot;submenu_04&quot;, &quot;submenu_sub_gi_newbie_04_over&quot;)" onmouseout="jsOverToggler(&quot;submenu_04&quot;, &quot;submenu_sub_gi_newbie_04_over&quot;)"></div>
							<div style="width: 5px; height: 100%;"></div>
							<div id="submenu_05" style="display: table-cell;" class="submenu_sub_gi_newbie_05 " onclick="jsRedirectTo('../5')" onmouseover="jsOverToggler(&quot;submenu_05&quot;, &quot;submenu_sub_gi_newbie_05_over&quot;)" onmouseout="jsOverToggler(&quot;submenu_05&quot;, &quot;submenu_sub_gi_newbie_05_over&quot;)"></div>
						</div>
					</div>
				</div>

				<div class="c_newbie_title c_gi_newbie_02_title"></div>
				
				<!-- content here -->
				<div class="img_gi_newbie_02" style="position: relative;">
									<div style="position: absolute; top: 32px; left: 274px; width:38px; height: 20px; cursor: pointer;" onclick="jsOpenWin(&quot;../../../../register.php&quot;);"></div>
								</div>
			</div>
			<div class="c_box_big_right"></div>
		</div>
	</div>
	<div class="c_box_big_bottom">
	</div>
</div>
						</div>
					</div>
				</div>
			</div>
			<!--div style='width: 100%; margin: 20px 0 10px 0;'>
				<div style='background-image: url(/images/cbt/robot_fother.png); background-repeat: no-repeat; width: 914px; height: 161px;margin-left: auto; margin-right: auto;'>
				</div>
			</div-->
		</div>
		<!--div style="display: table; height: 90px; position: absolute; overflow: hidden; width: 100%;bottom: 0;">
			<div style=" #position: absolute; #top: 50%;display: table-cell; vertical-align: middle;width: 100%;">
				<div style=" #position: relative; #top: -50%; text-align: center;width: 100%; height: 90px; ">
					<div style='background-image: url(/images/orange_logo.png); background-repeat: no-repeat; width: 160px; height: 26px; top: 3px; left: 660px; margin: 0 auto 5px auto;'></div>
					<span style="font-family: Arial; font-size: 9pt; color: #f68122;">T-Bot Online Indonesia &copy; 2010 Orange Game Fun Factory. All Rights Reserved. </span>      
				</div>
			</div>
		</div-->
	</div>
</div>
<div style="position: relative;width:100%;height:65px;left: 0;bottom: 0;background-image: url(../../../../images/cbt2/image/footer_spacer.gif); background-repeat: repeat-x;">
	<div style="width: 990px; padding-top: 10px;margin-left: auto; margin-right: auto;">
		<div style="display: table; border-collapse:collapse; width: 100%;padding-left: 5px;">
			<div style="width: 450px;height: 100%; display: table-cell;">
				<div style="float: left;">
					<div style="background-image: url(../../../../images/orange_logo.png); background-repeat: no-repeat; width: 160px; height: 26px;vertical-align: middle; margin-bottom: 5px;"></div>
					<div style="font-family: Arial; font-size: 9pt; color: #000000;">T-Bot Online Indonesia © 2010 Orange Game Fun Factory. All Rights Reserved. </div>
				</div>
			</div>
			<div style="height: 100%; display: table-cell;">
				<div style="float: right; text-align: right; vertical-align: middle;">
					<a href="http://forum.orangegame.co.id/viewforum.php?f=15" target="_blank">FaQ</a> - <a href="../../../index.php">Game Info</a> - <a href="../../../../contact/index.php">Support</a>
				</div>
			</div>
		</div>
	</div>
</div>

 

</body></html>