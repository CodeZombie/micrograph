	<div class="navbar navbar-default navbar-static-top navbar-inverse" role="navigation">
	  <div class="container">
		<div class="navbar-header">
		  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		  </button>
		</div>
		<div class="navbar-collapse collapse">
		  <ul class="nav navbar-nav">
					<li<?php if($active==0){ ?> class="active" <?php } ?>><a href="<?php $_SERVER['SCRIPT_NAME'] ?>?action=posts"><span class="glyphicon glyphicon-th-large"></span> Posts</a></li>
					<li<?php if($active==1){ ?> class="active" <?php } ?>><a href="<?php $_SERVER['SCRIPT_NAME'] ?>?action=newpost"><span class="glyphicon glyphicon-plus"></span> New Post</a></li>
					<li<?php if($active==2){ ?> class="active" <?php } ?>><a href="<?php $_SERVER['SCRIPT_NAME'] ?>?action=images"><span class="glyphicon glyphicon-paperclip"></span> Images</a></li>
		  </ul>
		  <ul class="nav navbar-nav navbar-right">
			<li<?php if($active==3){ ?> class="active" <?php } ?>><a href="<?php $_SERVER['SCRIPT_NAME'] ?>?action=settings"><span class="glyphicon glyphicon-wrench"></span> Settings</a></li>
			<li><a href="<?php $_SERVER['SCRIPT_NAME'] ?>?action=logout"><span class="glyphicon glyphicon-off"></span> Logout</a></li>
		</ul>
		</div>
	  </div>
	</div>