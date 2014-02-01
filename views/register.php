	<div class="container">
		<div class="col-md-4 center-block promptbox">
			<?php View::showError(); ?>
			<h2>Register</h2>
			<form class="uk-form" method="post" action="<?php $_SERVER['SCRIPT_NAME'] ?>?action=tryregister" name="registerform" id="registerform">
				<fieldset>
					<h4>Account</h4>
					<div class="form-group">
						<input class="form-control" name="username" type="text" placeholder="Username" value="<?php if(Header::getHeaderPost('username')!=false){ echo preg_replace('/[^a-zA-Z0-9-_]+/i','',Header::getHeaderPost("username"));} ?>">
					</div>
					<div class="form-group">
						<input class="form-control" name="password_one" type="password" placeholder="Password">
					</div>
					<div class="form-group">
						<input class="form-control" name="password_two" type="password" placeholder="Repeat Password">
					</div>
					<div class="form-group">
						<input class="form-control" name="db_timezone" type="text" placeholder="Timezone">
					</div>
					<div class="form-group">
						<input class="btn btn-default floatright" value="Register" type="submit" />
					</div>
				</fieldset>
			</form>
		</div>
	</div>