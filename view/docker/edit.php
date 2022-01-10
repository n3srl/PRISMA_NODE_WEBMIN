<?php /* @var $Docker Docker */ ?> 
<div class='right_col' role='main'>
	<div class=''>
		<div class='page-title'>
			<div class='title_left'>
				<h2><?= _('Docker Container') ?></h2>
			</div>
		</div>
			<div class='clearfix'></div>
				<div class='row'>
					<div class='col-md-12 col-sm-12 col-xs-12'>
					<div id='list' class='x_panel'>
						<div class='x_title no-padding-lr'>
							<div class='clearfix'>
								<div class='col-md-6 no-padding-l'>
									<h2><?= _('Elenco') ?></h2>
								</div>
									
								</div>
							</div>
							<!-- <div class='x_panel filter-container'>
								<div class='x_title filter-title-container collapse-link'>
									<div class='filter-title'>
										<h2 class='font-15'>Filtra per...</h2>
										<ul class='nav navbar-right panel_toolbox'>
											<li><a class='black'><i class='fa fa-chevron-down'></i></a>
											</li>
										</ul>
									</div>
								<div class='clearfix'></div>
							</div>
							<div class='x_content filter-content' hidden>
								<div class='form-group col-md-3 col-sm-6 col-xs-12 <?= md5(id) ?>'>
									<div class='form-group'>
										<div class='col-xs-12'>
											<small class='text-muted'><?php echo (_('id')) ?></small>
											<select class='form-control filter filter-text' id='F_id' multiple='multiple' title='<?php echo (_('Filtra per id')) ?>'>
											</select>
										</div>
									</div>
								</div>
								<div class='form-group col-md-3 col-sm-6 col-xs-12 <?= md5(name) ?>'>
									<div class='form-group'>
										<div class='col-xs-12'>
											<small class='text-muted'><?php echo (_('name')) ?></small>
											<select class='form-control filter filter-text' id='F_name' multiple='multiple' title='<?php echo (_('Filtra per name')) ?>'>
											</select>
										</div>
									</div>
								</div>
								<div class='form-group col-md-3 col-sm-6 col-xs-12 <?= md5(image) ?>'>
									<div class='form-group'>
										<div class='col-xs-12'>
											<small class='text-muted'><?php echo (_('image')) ?></small>
											<select class='form-control filter filter-text' id='F_image' multiple='multiple' title='<?php echo (_('Filtra per image')) ?>'>
											</select>
										</div>
									</div>
								</div>
								<div class='form-group col-md-3 col-sm-6 col-xs-12 <?= md5(command) ?>'>
									<div class='form-group'>
										<div class='col-xs-12'>
											<small class='text-muted'><?php echo (_('command')) ?></small>
											<select class='form-control filter filter-text' id='F_command' multiple='multiple' title='<?php echo (_('Filtra per command')) ?>'>
											</select>
										</div>
									</div>
								</div>
								<div class='form-group col-md-3 col-sm-6 col-xs-12 <?= md5(status) ?>'>
									<div class='form-group'>
										<div class='col-xs-12'>
											<small class='text-muted'><?php echo (_('status')) ?></small>
											<select class='form-control filter filter-text' id='F_status' multiple='multiple' title='<?php echo (_('Filtra per status')) ?>'>
											</select>
										</div>
									</div>
								</div>
								<div class='form-group col-md-3 col-sm-6 col-xs-12 <?= md5(created) ?>'>
									<div class='form-group'>
										<div class='col-xs-12'>
											<small class='text-muted'><?php echo (_('created')) ?></small>
											<select class='form-control filter filter-text' id='F_created' multiple='multiple' title='<?php echo (_('Filtra per created')) ?>'>
											</select>
										</div>
									</div>
								</div>
								<div class='form-group col-md-12 col-sm-12 col-xs-12'>
									<div class='form-group'>
										<div class='col-xs-12'>
											<button class="pull-right btn btn-success applyFilter" ><?= _("Applica filtri") ?></button>
										</div>
									</div>
								</div>
							</div>
						</div> -->
							<div class='x_content'>
								<table id='DockerList' class='table table-striped table-bordered' style='width: 100%; '>
									<thead>
									<tr>
										<th><?php echo (_('nome')) ?></th>
										<th><?php echo (_('immagine')) ?></th>
										<th><?php echo (_('comando')) ?></th>
										<th><?php echo (_('stato')) ?></th>
										<th><?php echo (_('creato')) ?></th>
										<th></th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class='col-md-12 col-sm-12 col-xs-12'>
					<form id='CompanyForm' method='POST' action='/service/docker/save/<?php echo $Docker->id;?>' class='form-horizontal form-label-left' novalidate>
						<div id='edit' class='x_panel'>
							<div class='x_title no-padding-lr'>
								<div class='clearfix'>
									<div class='col-md-8 no-padding'>
										<h2><?= _('Gestisci') ?></h2>
									</div>
									<div class='col-md-4 no-padding'>
										
										<button type = 'submit' style= 'display: none; margin-right: 10px;' id= 'savebtn' class='btn btn-success pull-right' ><?= _('RIAVVIA') ?></button>
										<button type = 'button' style= 'display: none; margin-right: 10px;' id= 'modifybtn' onclick= 'allowEditObj();' class='btn btn-success btn-blue-success pull-right' ><?= _('MODIFICA') ?></button>
										<button type = 'button' style= 'display: none; margin-right: 10px;' id= 'undobtn' onclick= 'undoObj();' class='btn btn-warning btn-yellow-warning pull-right' ><?= _('ANNULLA') ?></button>
										
									</div>
								</div>
							</div>
							<div class='x_content'>
								<div class='col-md-3 col-sm-6 col-xs-12 <?= md5(id) ?>'>
									<div class='item form-group'>
										<div class='col-xs-12'>
											<small class='text-muted'><?php echo ( _('id')) ?> </small>
											<input type = 'text' name='id' class='form-control col-md-7 col-xs-12 input-disabled' placeholder=' <?php echo ( _('id')) ?> '  title=' <?php echo ( _('id')) ?> '/>
										</div>
									</div>
								</div>
								<div class='col-md-3 col-sm-6 col-xs-12 <?= md5(name) ?>'>
									<div class='item form-group'>
										<div class='col-xs-12'>
											<small class='text-muted'><?php echo ( _('nome')) ?> </small>
											<input type = 'text' name='name' class='form-control col-md-7 col-xs-12 input-disabled' placeholder=' <?php echo ( _(' ')) ?> '  title=' <?php echo ( _('name')) ?>' maxlength = '64' />
										</div>
									</div>
								</div>
								<div class='col-md-3 col-sm-6 col-xs-12 <?= md5(image) ?>'>
									<div class='item form-group'>
										<div class='col-xs-12'>
											<small class='text-muted'><?php echo ( _('immagine')) ?> </small>
											<input type = 'text' name='image' class='form-control col-md-7 col-xs-12 input-disabled' placeholder=' <?php echo ( _(' ')) ?> '  title=' <?php echo ( _('image')) ?>' maxlength = '64' />
										</div>
									</div>
								</div>
								<div class='col-md-3 col-sm-6 col-xs-12 <?= md5(command) ?>'>
									<div class='item form-group'>
										<div class='col-xs-12'>
											<small class='text-muted'><?php echo ( _('comando')) ?> </small>
											<input type = 'text' name='command' class='form-control col-md-7 col-xs-12 input-disabled' placeholder=' <?php echo ( _(' ')) ?> '  title=' <?php echo ( _('command')) ?>' maxlength = '256' />
										</div>
									</div>
								</div>
								<div class='col-md-3 col-sm-6 col-xs-12 <?= md5(status) ?>'>
									<div class='item form-group'>
										<div class='col-xs-12'>
											<small class='text-muted'><?php echo ( _('stato')) ?> </small>
											<input type = 'text' name='status' class='form-control col-md-7 col-xs-12 input-disabled' placeholder=' <?php echo ( _(' ')) ?> '  title=' <?php echo ( _('status')) ?>' maxlength = '64' />
										</div>
									</div>
								</div>
								<div class='col-md-3 col-sm-6 col-xs-12 <?= md5(created) ?>'>
									<div class='item form-group'>
										<div class='col-xs-12'>
											<small class='text-muted'><?php echo ( _('creato')) ?> </small>
											<input type = 'text' name='created' class='form-control col-md-7 col-xs-12 input-disabled' placeholder=' <?php echo ( _(' ')) ?> '  title=' <?php echo ( _('created')) ?>' maxlength = '64' />
										</div>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<?php include './view/template/foot.php'; ?>
	<script src='<?php echo $_SERVER['PATH_WEBROOT'] ?>/js/docker.js<?= _VERSION_ ?>'></script>

