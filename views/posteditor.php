<div class="container">
	<div class="col-md-12">
		<div id="errorbox" class="panel panel-danger" style="display:none;">
			<div class="panel-heading">Error</div>
			<div id="errorboxcontent" class="panel-body">
			
			</div>
		</div>
		<div id="messagebox" class="panel panel-primary" style="display:none;">
			<div class="panel-heading">Message </div>
			<div class="panel-body">
				<div id="messageboxcontent" style="float:left;"></div>
				<input onclick="hideMessageBox();" class="btn btn-primary floatright" value="Okay" type="submit" >
			</div>
		</div>
	</div>
</div>
<div class="container">
		<div class="col-md-3">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Panel</h3>
				</div>
				<h5 class="form-subgroup">Meta</h5>
				<div class="panel-body">
					<a class="btn btn-default fullwidth" href="">Choose Thumbnail</a>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Images</h3>
				</div>
				<h5 class="form-subgroup">Upload</h5>
				<div class="panel-body">
					<form id="imgupload" method="post" enctype="multipart/form-data" action="system/ajax/uploadimage.php">
						<input class="btn btn-default fullwidth" type="file" name="image">
						<input class="btn btn-default fullwidth" name="Submit" type="submit" value="Upload image">
					</form>
				</div>
				<h5 class="form-subgroup">List</h5>
				<div class="panel-body">
					<div class="panel panel-default">
						<div class="panel-body img-selector-panel" onclick="insertAtCaret('postcontent','![alt text](views/img/placeholder.png)');">
							<div class="media">
								<div class="pull-left" >
									<img class="media-object" src="views/img/placeholder.png" alt="...">
								</div>
								<em>placeholder.png</em></br>
								<em>12kb</em></br>
								<em>Jan 15 2014</em></br>
							</div>
						</div>
					</div>
					<div class="panel panel-default">
						<div class="panel-body img-selector-panel" onclick="insertAtCaret('postcontent','![alt text](views/img/what.jpg)');">
							<div class="media">
								<div class="pull-left" >
									<img class="media-object" src="views/img/what.jpg" alt="...">
								</div>
								<em>placeholder.png</em></br>
								<em>12kb</em></br>
								<em>Jan 15 2014</em></br>
							</div>
						</div>
					</div>
					<a class="btn btn-default fullwidth" onclick="alert('show a list of images uploaded to the server');">+</a>
				</div>
			</div>
		</div>
		<div class="col-md-9">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Edit Post</h3>
				</div>
				<div class="panel-body">
					<form method="post" action="<?php $_SERVER['SCRIPT_NAME'] ?>?action=savepostedit&id=<?php echo $id; ?>">
						<div class="panel no-border">
							<input name="post_title" id="posttitle" type="text" class="form-control title_field" placeholder="Post Title" <?php echo 'value="' . $post_title_value . '"'; ?>>
						</div>
						<fieldset>	
							<ul class="nav nav-tabs">
								<li class="active" id="markuptab"><a onclick="clickMarkupTab()">markup</a></li>
								<li id="previewtab"><a onclick="clickPreviewTab();" >preview</a></li>
							</ul>
							<div id="previewcontent" class="content_field preview_field" style="display:none;"></div>
							<textarea name="post_content" id="postcontent" type="text" class="form-control no-border-radius content_field" placeholder="Post Content" ><?php echo $post_content_value; ?></textarea>
							<div class="panel panel-default no-border-radius panel-under-content-field">
								<input name="post_tags" type="text" class="form-control" placeholder="Tags, Seperated, By, Commas" <?php echo 'value="' . $post_tags_value . '"'; ?>>
							</div>
							<div class="panel panel-default no-border-top-radius panel-under-tag-field">
								<div class="btn-group">
									<input class="btn btn-primary" style="height:34px;" value="Save Changes" type="submit">
									<a class="btn btn-info" onclick="saveBackup()">Save and Unpublish</a>
									<a class="btn btn-danger" href="<?php $_SERVER['SCRIPT_NAME'] ?>?action=deletepost&id=<?php echo $id; ?>">Delete Post</a>
								</div>
							</div>
						</fieldset>
					</form>
				</div>
			</div>
		</div>
	</div>