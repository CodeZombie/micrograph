<div class="container">
		<div class="col-md-3">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Control</h3>
				</div>
				<div class="panel-body">
					<form method="post" enctype="multipart/form-data" action="<?php $_SERVER['SCRIPT_NAME'] ?>?action=uploadimage">
						<input class="btn btn-default fullwidth" type="file" name="image">
						<input class="btn btn-default fullwidth" name="Submit" type="submit" value="Upload image">
					</form>
				</div>
			</div>
		</div>
		<div class="col-md-9">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Image List</h3>
				</div>
				<div class="panel-body">
				<?php foreach( Images::getImageList(0,800) as $img) { ?>
						<div class="col-xs-6 col-md-3">
							<a href="#" class="thumbnail">
								<img src="<?php echo "content/images/" . $img; ?>" style="height:128px; width:128px;"  alt="...">
							</a>
						</div>
				<?php } ?>
				</div>
			</div>
		</div>
	</div>