<?php if(isset($_DISPLAY)) { //MAKE SURE PEOPLE CAN'T ACCESS THESE FILES FROM OUTSIDE THE ADMIN PANEL ?>
<div class="container">
		<div class="col-md-3">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Filters</h3>
				</div>
				<h5 class="form-subgroup">Results per page</h5>
				<div class="panel-body">
					<ul class="pagination">
						<li <?php if($tgplr->resultsPerPage==2){ ?> class="active" <?php } ?>><a href="?action=drafts<?php echo Header::formatHeaderGetList("tag","order") ?>&perpage=2">2</a></li>
						<li <?php if($tgplr->resultsPerPage==5){ ?> class="active" <?php } ?>><a href="?action=drafts<?php echo Header::formatHeaderGetList("tag","order") ?>&perpage=5">5</a></li>
						<li <?php if($tgplr->resultsPerPage==10){ ?> class="active" <?php } ?>><a href="?action=drafts<?php echo Header::formatHeaderGetList("tag","order") ?>&perpage=10">10</a></li>
						<li <?php if($tgplr->resultsPerPage==15){ ?> class="active" <?php } ?>><a href="?action=drafts<?php echo Header::formatHeaderGetList("tag","order") ?>&perpage=15">15</a></li>
					</ul>
				</div>
				<h5 class="form-subgroup">List order</h5>
				<div class="panel-body">
					<div class="btn-group">
						<a class="btn<?php if($tgplr->order==="asc"){ echo " btn-primary";}else{ echo " btn-default"; } ?>" href="?action=drafts<?php echo Header::formatHeaderGetList("perpage","tag"); ?>">Ascending</a>
						<a class="btn<?php if($tgplr->order==="desc"){ echo " btn-primary";}else{ echo " btn-default"; } ?>" href="?action=drafts<?php echo Header::formatHeaderGetList("perpage","tag"); ?>&order=desc">Descending</a>
					</div>
				</div>
				<?php if(File::fileExists("content/backup.json")) { ?>
				<h5 class="form-subgroup">Messages</h5>
				<div class="panel-body ">
					<div class="panel panel-default">
						<div class="panel-heading">
						Important!
						</div>
						<div class="panel-body">
						You have one unsaved backup </br></br>
						<a href="<?php $_SERVER['SCRIPT_NAME'] ?>?action=recoverbackup" class="btn btn-primary fullwidth" style="height:34px !important;">Recover</a>
						<a href="<?php $_SERVER['SCRIPT_NAME'] ?>?action=deletebackup" class="btn btn-danger fullwidth" style="height:34px !important;">Delete backup</a>
						</div>
						
					</div>
				</div>
				<?php } ?>
			</div>
		</div>
		<div class="col-md-9">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">
					Drafts
					</h3>
				</div>
				<div class="panel-body">
				<?php if($tgplr->maximumPosts == 0) { ?>
					<h3><i>No drafts found</i></h3>
					<p>Why not create one?</p>
				<?php } ?>
					<?php foreach($tgplr->posts as $post) { ?>
						<div class="panel panel-default">
							<div class="panel-body">
								<span class="badge">
								<?php echo $post["id"];?>
								
								</span>
								<h4>
									<a href="?action=editdraft&id=<?php echo $post["id"]; ?>"><?php echo $post["title"]; ?></a>
								</h4>
								<p>
								<?php echo substr(nl2br(strip_tags($post["content"])),0,256); ?>
								<br/>
								<?php if($post["tags"] !== "") { ?>
									<br/>
									<a><?php echo $post["tags"]; ?></a>
								<?php } ?>
								</p>
							</div>
						</div>
					<?php unset($post); } ?>
					<ul class="pagination floatright">
						<?php foreach($tgplr->pagination as $pag) {
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
								$out .= ' href="?action=drafts';
								$out .= Header::formatHeaderGetList("perpage","tag","order");
								$out .= '&page=';
								
								if($pag->direction==="left") { 
									$out .= $tgplr->currentPage-1; 
								}
								elseif($pag->direction==="right") {
									$out .= $tgplr->currentPage+1; 
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
<?php } else { ?>
403 forbidden
<?php } ?>