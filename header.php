<?php
function curPageURL() {
	$pageUrl = $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	$page = explode("/",$pageUrl);
	$pos = strpos($page[2],'index.php');
	if($pos !== false){
		$return = 'index.php';
	} else if($page[3]){
		$return = array($page[2],$page[3]);
	} else {
		$return = $page[2];
	}
	return $return;
}

$script_directory = '';
define('IN_PHPBB', true);
$phpbb_root_path = './board/';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/bbcode.' . $phpEx);
include($phpbb_root_path . 'includes/functions_display.' . $phpEx);
$user->session_begin();
$auth->acl($user->data);
$user->setup('viewforum'); 

require_once 'inc/database_config.php';
require_once 'inc/charfunctions.php';
?>
<!doctype html>
<html>

	<head>
		<meta charset="utf-8"/>
		<title>Wolf Kingdom</title>
		<link rel="stylesheet" media="all" href="/css/style.css"/>
		<link rel="stylesheet" href="/js/fancybox/jquery.fancybox-1.3.4.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css" />
                <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" type="text/javascript"></script>
		<script src="/js/cufon.js" type="text/javascript"></script>
		<script src="/js/Aurulent_Sans.font.js" type="text/javascript"></script>
		<script type="text/javascript" src="/js/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
		<script type="text/javascript" src="/js/flot/jquery.flot.js"></script>
		<script type="text/javascript" src="/js/flot/jquery.flot.pie.js"></script>
                <script src="/js/jquery-1.10.2.js"></script>
                <script src="/js/bootstrap.js"></script>
                <script type="text/javascript">
			function loadContent(user, userhash, id, hc, hsprite, sc, tc, gender, pc, lvl, on, weapon1, weapon2, weapon3, weapon4, weapon5, weapon6) {
				var url = "/js/account.php";
					$.post(url, {username: user, userenc: userhash, owner: id, hair: hc, head: hsprite, skin: sc, top: tc, gen: gender, pants: pc, combat: lvl, online: on, weapon1: weapon1, weapon2: weapon2, weapon3: weapon3, weapon4: weapon4, weapon5: weapon5, weapon6: weapon6} ,function(data) {
						$("#character-details").html(data).show();
						$("a#inline").fancybox({
						'hideOnContentClick': false,
						'hideOnOverlayClick': false,
						'overlayColor': '#000000',
						'padding': 0,
						});
						$("#character-delete-form").bind("submit", function() {
						$("#verification-fails").hide();
						if ($("#verification").val() != 'yes') {
							$("#verification-fails").show();
							$.fancybox.resize();
							return false;
						}
						$.fancybox.showActivity();
						var i = $("#user-i").val();
						var ui = $("#user-ui").val();
						var y = $("#verification").val();
						setTimeout(function(){
						
							$.post("/js/account.php", {id: i, hash: ui, ver: y} ,function(data) {
								$.fancybox.hideActivity();
								$("#character-delete-form").hide();
								window.location.reload()
							});
							
						},5000);
						return false;
						});
					});
			}
			
			$(document).ready(function() {
				$("#toggle:first").addClass('active');
				$('#toggle').click(function(){
					$('#toggle').removeClass('active');
					$(this).toggleClass('active');
				});
				
				$("a#single_image").fancybox();
				
				/* Using custom settings */
				
				$("a#inline").fancybox({
					'hideOnContentClick': false,
					'overlayColor': '#000000',
					'padding': 0,
					'onClosed': function() {
						$("#name-fails").hide();
						$("#pass-fails").hide();
						$("#user-fails").hide();
						$("#user-passes").hide();
						$("#character-creation-form").show();
					} 
				});
				$("#character-creation-form").bind("submit", function() {
					$("#name-fails").hide();
					$("#pass-fails").hide();
					if ($("#name").val().length >= 11 || $("#name").val().length <= 3) {
						$("#name-fails").show();
						$.fancybox.resize();
						return false;
					}
					if ($("#password").val().length <= 4) {
						$("#pass-fails").show();
						$.fancybox.resize();
						return false;
					}
	
					$.fancybox.showActivity();
					var n = $("#name").val();
				    var p = $("#password").val();
					
					setTimeout(function(){
					
						$.post("/js/account.php", {nm: n, pw: p} ,function(data) {
							if(data == 0){
								$("#user-fails").show();
							} else if(data == 1){
								$("#user-passes").show();
								$("#character-creation-form").hide();
								window.location.reload()
							}	
							$.fancybox.hideActivity();
						});
						
					},3000);
					return false;
				});
				
			});
		</script>

		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<!-- Adding "maximum-scale=1" fixes the Mobile Safari auto-zoom bug: http://filamentgroup.com/examples/iosScaleBug/ -->
	</head>
        <body lang="en">

		<header>
			<div class="large">Wolf Kingdom</div>
		</header>
		<div class="body-wrapper">	
			<div class="navigation">
				<div class="navbar">
					<ul>
						<li><a href="/">Home</a></li>
						<li><a href="/board/index.php">Forum</a></li>
						<li><a href="/playnow.php">Play Now</a></li>
						<li><a href="/clans.php">Clans</a></li>
						<li><a href="/highscores.php">Highscores</a></li>
						<li><a href="/media.php">Media</a></li>
					</ul>
				</div>
				<div class="account-panel">
					<div class="avatar-box">
					<?php if($user->data['is_registered']){ ?>
							<a href="/board/ucp.php?i=profile&mode=avatar"><img src="/board/download/file.php?avatar=<?php print $user->data['user_avatar']; ?>" /></a>
					<?php } ?>
					</div>
					<div class="account-text">
					<?php if($user->data['is_registered']){ ?>
						<span class="welcome-message block">Welcome back, <?php print $user->data['username']; ?></span>
						<span class="welcome-text"><a href="/manage.php">Account Management</a></span>
						<span class="welcome-text"> | (<a href="/board/ucp.php?i=pm&folder=inbox"><?php print $user->data['user_unread_privmsg']; ?></a>) | </span>
						<span class="welcome-text">
							<a href='/<?php echo $script_directory; ?>board/ucp.php?mode=logout&amp;sid=<?php print $user->data['session_id'];?>'>Log out</a>
						</span>
					<?php 
                                        } else { 
                                        ?>
                                                <aside>
                                                    <div class="box">
                                                            <div id="data">
								<h4>Login Module</h4>
								<p>Use the form below to login!</p>
                                                                <form method="post" action="/board/ucp.php?mode=login">
								<label for="loginname">Username: </label><input type="text" name="username" class="name" id="loginname"/>
								<label for="loginpass">Password: </label><input type="password" name="password" class="password" id="loginpass"/>
								<input type="hidden" checked="yes" name="autologin" class="autologin"  id="autologin"/>
								<input type="submit" value="Log In" name="login" class="submit"/>
								<input type="hidden" name="redirect" value="/<?php echo $script_directory; ?>index.php" />
								</form> 
								<a class="submit" href="/board/ucp.php?mode=register">Register</a> 
                                                            </div>
                                                    </div>
                                                </aside
                                        <?php         
                                        } 
                                        ?>
                                                </div>
                                        </div>
                                </div>
		<?php
		
			if(curPageURL() != "" && !is_array(curPageURL()) && curPageURL() != 'index.php'){
				if(file_exists("pages/".curPageURL().".php")) {
					include("pages/".curPageURL().".php");
				} else {
					//include("pages/error.php");
                                    exit;
				}
			} else if(is_array(curPageURL()) && curPageURL() != 'index.php' ){
				$page = curPageURL();
				$subpage = $page[1];
				$page = $page[0];
				if(file_exists("pages/".$page.".php")) {
					include("pages/".$page.".php");
				} else {
					//include("pages/error.php");
                                    exit;
				}
			} else {
			}
		
		?>
                        </div>
            </div>
        </body>
</html>
