<?php if(isset($_DISPLAY)) { ?>
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
					<div class="col-md-12">
					<?php if(Images::getImageList(($page-1)*$perpage,$perpage) == null) { ?>
						nothing to show
					<?php } ?>
					<?php foreach( Images::getImageList(($page-1)*$perpage,$perpage) as $img) { ?>
							<div class="col-xs-6 col-md-3">
								<a href="#" class="thumbnail">
									<img src="<?php echo "content/images/" . $img; ?>" style="height:128px; width:128px;"  alt="...">
								</a>
							</div>
					<?php } ?>
					</div>
					<div class="col-md-12">
						<ul class="pagination floatright">
						<?php foreach($pagin as $pag) {
							$out = "<li";
							
							if($pag->active) {
								$out .= ' class="active"';
							}
							
							if($pag->disabled) {
								$out .= ' class="disabled';
								
								if($pag->direction===0) {
									$out .=' pagination_disabled';
								}
								
								$out .= '"';
							}
							$out .= '><a';
							
							if($pag->direction==0 && $pag->disabled!=true) {
								$out .= ' href="?action=images';
								$out .= '&page=';
								
								if($pag->direction==="left") { 
									$out .= $page-1; 
								}
								elseif($pag->direction==="right") {
									$out .= $page+1; 
								}
								else {
								$out .= $pag->pagenumber;
								}
								$out .= '"';
							}
							$out .= '>';
							$out .= $pag->pagenumber;
							$out .= '</a></li>';
							echo $out;
						} ?>
						
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php } else { ?>
403 forbidden
<?php } ?>