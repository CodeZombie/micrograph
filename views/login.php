<?php if(isset($_DISPLAY)) { ?>
	<div class="container">
		<div class="col-md-4 center-block promptbox">
			<?php View::showError(); ?>
			<h2>Login</h2>
			<form role="form" method="post" action="<?php $_SERVER['SCRIPT_NAME'] ?>?action=trylogin" name="loginform" id="loginform">
				<div class="form-group">
					<input class="form-control"  placeholder="Username" name="username" type="text" value="<?php if(Header::getHeaderPost("username")!=false){ echo preg_replace("/[^a-zA-Z0-9-_]+/i",'',Header::getHeaderPost("username"));} ?>" >
					</div>
					<div class="form-group">
					<input class="form-control"  placeholder="Password" name="password" type="password">
					</div>
					<a class="btn btn-default btn-text" onclick="bootbox.alert('To reset your username and/or password, access your web server directly and delete <i>config/login.conf.php</i>. Now navigate to the Admin Panel once again, and you will be prompted to enter a new username and password. Doing so will not delete any posts or information.');">Forgot your password?</a>
					<input class="btn btn-primary floatright" value="Login" type="submit" >
				</fieldset>	
			</form>
		</div>
	</div>
<?php } else { ?>
403 forbidden
<?php } ?>