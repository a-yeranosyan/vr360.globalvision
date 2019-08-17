<?php
defined('_VR360_EXEC') or die;

/**
 * This layout used for display all scenes
 *
 * @var  array $scenes List of scenes
 */
$scenes = $tour->getScenes();
?>
<div id="scenes" class="scenes">
	<?php if (isset($scenes) && !empty($scenes)): ?>
		<?php foreach ($scenes as $scene): ?>
			<div class="panel <?php echo $scene->default ? 'panel-primary' : 'panel-info' ?> scene">
				<div class="panel-heading">
					<div class="container-fluid">
						<div class="col-md-6">
							<div class="panel-title">
								<i class="fas fa-eye"></i> Scene
								<span class="badge">
								<small class="scene-hotspots-count"><?php echo $scene->getHotspots() ? count($scene->getHotspots()) : 0; ?>
									hotspots</small>
							</span>
								<?php if (!$scene->isValid()): ?>
									<span class="label label-danger">
								<small>Invalid</small>
							</span>
								<?php endif; ?>
							</div>
						</div>
						<div class="col-md-6">
							<button type="button" id="removeScene"
							        class="btn btn-danger btn-sm pull-right tour-scene-remove">
								<i class="far fa-minus-square"></i> <?php echo \Joomla\Language\Text::_('TOUR_LABEL_DELETE');?>
							</button>
						</div>
					</div>
				</div>
				<div class="panel-body">
					<div class="container-fluid">
						<div class="col-md-12">
							<div class="sceneWrap form-horizontal">
								<div class="form-group">
									<label class="col-sm-3 control-label"><?php echo \Joomla\Language\Text::_('TOUR_LABEL_SCENE_FILE');?></label>
									<div class="col-sm-9">
										<input
												type="text"
												value="<?php echo $scene->file ?>"
												class="form-control disabled tour-scene-file"
												disabled="disabled" title="Scene File"
										/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">Name *</label>
									<div class="col-sm-9">
										<input
												name="sceneName[<?php echo $scene->id ?>]"
												type="text"
												class="form-control input-sm tour-scene-name"
												placeholder="Scene name"
												value="<?php echo $scene->name ?>"
										/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label"><?php echo \Joomla\Language\Text::_('TOUR_LABEL_NAME');?></label>
									<div class="col-sm-9">
										<input
												name="sceneDescription[<?php echo $scene->id ?>]"
												type="text"
												class="form-control input-sm tour-scene-description"
												size="80"
												placeholder="Scene description"
												value="<?php echo $scene->description ?>"
										/>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-12">
										<div class="col-md-offset-3 col-md-12">
											<div class="checkbox">
												<label>
													<input
															name="sceneDefault"
															type="radio"
															class="tour-scene-default"
															value="<?php echo $scene->id ?>"
														<?php echo $scene->default ? 'checked' : '' ?>
													/>
													Set as default scene
												</label>
											</div>
										</div>
									</div>
								</div>
								<input type="hidden" class="scene-id" name="sceneId[]"
								       value="<?php echo $scene->id ?>"/>
							</div>
						</div>
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-2">
									<div class="form-group">
										<label class="control-label">FOVtype</label>
										<input
												type="text"
												class="form-control tour-scene-options"
												name="sceneParams[<?php echo $scene->id ?>][fovtype]"
												value="<?php echo $scene->getParam('fovtype', 'MFOV'); ?>"
										/>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label class="control-label">FOV</label>
										<input
												type="text"
												class="form-control tour-scene-options"
												name="sceneParams[<?php echo $scene->id ?>][fov]"
												value="<?php echo $scene->getParam('fov', VR360_TOUR_SCENE_DEFAULT_FOV); ?>"
										/>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label class="control-label">Maxpixelzoom</label>
										<input
												type="text"
												class="form-control tour-scene-options"
												name="sceneParams[<?php echo $scene->id ?>][maxpixelzoom]"
												value="<?php echo $scene->getParam('maxpixelzoom', '2.0'); ?>"
										/>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label class="control-label">Fovmin</label>
										<input
												type="text"
												class="form-control tour-scene-options"
												name="sceneParams[<?php echo $scene->id ?>][fovmin]"
												value="<?php echo $scene->getParam('fovmin', '70'); ?>"
										/>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label class="control-label">Fovmax</label>
										<input
												type="text"
												class="form-control tour-scene-options"
												name="sceneParams[<?php echo $scene->id ?>][fovmax]"
												value="<?php echo $scene->getParam('fovmax', '170'); ?>"
										/>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label class="control-label">Limitview</label>
										<input
												type="text"
												class="form-control tour-scene-options"
												name="sceneParams[<?php echo $scene->id ?>][limitview]"
												value="<?php echo $scene->getParam('limitview', 'auto'); ?>"
										/>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	<?php else: ?>
		<div class="panel panel-default scene">
			<div class="panel-heading">
				<div class="container-fluid">
					<div class="col-md-6">
						<div class="panel-title"><?php echo \Joomla\Language\Text::_('TOUR_LABEL_NEW_SCENE');?></div>
					</div>
					<div class="col-md-6">
						<button
								type="button"
								class="btn btn-danger btn-sm pull-right tour-scene-remove"
						>
							<i class="fas fa-minus"></i> <?php echo \Joomla\Language\Text::_('TOUR_LABEL_DELETE');?>
						</button>
					</div>
				</div>
			</div>
			<div class="panel-body">
				<div class="container-fluid">
					<div class="col-md-12">
						<div class="sceneWrap form-horizontal">
							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo \Joomla\Language\Text::_('TOUR_LABEL_SCENE_FILE');?></label>
								<div class="col-sm-9">
									<input
											type="file"
											name="newSceneFile[]"
											class="tour-scene-file"
											data-validation="mime size required"
											data-validation-allowing="jpg, png, jpeg"
											data-validation-max-size="<?php echo ini_get('upload_max_filesize'); ?>"
									/>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo \Joomla\Language\Text::_('TOUR_LABEL_NAME');?></label>
								<div class="col-sm-9">
									<input
											name="newSceneName[]"
											type="text"
											class="form-control input-sm tour-scene-name"
											placeholder="Scene name"
											data-validation="required"
									/>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo \Joomla\Language\Text::_('TOUR_LABEL_DESCRIPTION');?></label>
								<div class="col-sm-9">
									<input
											name="newSceneDescription[]"
											type="text"
											class="form-control input-sm tour-scene-description"
											size="80"
											placeholder="Scene description"
									/>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>
	<div class="form form-inline">
		<div class="col-md-3">
			<div class="form-group">
				<button type="button" id="tour-scene-add" class="btn btn-info btn-sm form-control">
					<i class="fa fa-plus-square"
					   aria-hidden="true"></i> <?php echo \Joomla\Language\Text::_('TOUR_LABEL_ADD_SCENE'); ?>
				</button>
			</div>
		</div>
		<div class="col-md-3">
			<!-- Controls -->
			<div class="form-group">
				<button type="submit" id="tour-save" class="btn btn-primary btn-sm form-control">
					<i class="fa fa-window-restore"
					   aria-hidden="true"></i> <?php echo \Joomla\Language\Text::_('GENERAL_LABEL_SAVE'); ?>
				</button>
			</div>
		</div>
	</div>
</div>