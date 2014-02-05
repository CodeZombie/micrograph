<?php if(isset($_DISPLAY)) { ?>
<div class="container">
	<div class="col-md-12">
		<div id="messagebox" class="panel panel-danger">
			<div class="panel-heading">Error</div>
			<div class="panel-body">
				<div id="messageboxcontent" style="float:left;"><?php echo $GLOBALS["ERROR"]; ?></div>
				<input onclick="hideMessageBox();" class="btn btn-danger floatright" value="Okay" type="submit" >
			</div>
		</div>
	</div>
</div>
<?php } else { ?>
403 forbidden
<?php } ?>