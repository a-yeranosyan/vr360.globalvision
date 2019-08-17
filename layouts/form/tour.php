<?php

defined('_VR360_EXEC') or die;

/** @var Vr360Tour $tour */
?>
<!-- Hidden scene form -->
<?php require_once __DIR__ . '/tour_scene.php'; ?>
<!-- Create new tour form -->
<form method="post" id="tour-form" class="form-horizontal tour-form" enctype="multipart/form-data">
	<?php require_once __DIR__ . '/tour_information.php'; ?>
	<div class="col-md-6 form-horizontal">
		<!-- Name -->
		<div class="form-group">
			<label for="name" class="col-sm-2 control-label"><?php echo \Joomla\Language\Text::_('TOUR_LABEL_NAME');?></label>
			<div class="col-sm-10">
				<input
						type="text"
						class="form-control input-sm"
						id="name"
						name="name"
						placeholder="<?php echo \Joomla\Language\Text::_('TOUR_LABEL_NAME_DESCRIPTION');?>"
						value="<?php echo $tour->get('name'); ?>"
						data-validation="required"/>
			</div>
		</div>
		<!-- Alias -->
		<div class="form-group">
			<label for="alias" class="col-sm-2 control-label"><?php echo \Joomla\Language\Text::_('TOUR_LABEL_ALIAS');?></label>
			<div class="col-sm-10">
				<input
						type="text"
						class="form-control input-sm"
						id="alias"
						name="alias"
						placeholder="<?php echo \Joomla\Language\Text::_('TOUR_LABEL_ALIAS_DESCRIPTION');?>"
						value="<?php echo $tour->get('alias'); ?>"
						data-validation="required"
				/>
			</div>
		</div>
		<!-- Description -->
		<div class="form-group">
			<label for="description" class="col-sm-2 control-label"><?php echo \Joomla\Language\Text::_('TOUR_LABEL_DESCRIPTION');?></label>
			<div class="col-sm-10">
				<input
						type="text"
						class="form-control input-sm"
						id="description"
						name="description"
						placeholder="<?php echo \Joomla\Language\Text::_('TOUR_LABEL_DESCRIPTION_DESCRIPTION');?>"
						value="<?php echo $tour->get('description'); ?>"
				/>
			</div>
		</div>
		<!-- Keyword -->
		<div class="form-group">
			<label for="description" class="col-sm-2 control-label"><?php echo \Joomla\Language\Text::_('TOUR_LABEL_KEYWORD');?></label>
			<div class="col-sm-10">
				<input
						type="text"
						class="form-control input-sm"
						id="keyword"
						name="keyword"
						placeholder="<?php echo \Joomla\Language\Text::_('TOUR_LABEL_KEYWORD_DESCRIPTION');?>"
						value="<?php echo $tour->get('keyword'); ?>"
				/>
			</div>
		</div>
		<hr/>
		<!-- Options -->
		<?php require_once __DIR__ . '/tour_options.php'; ?>
	</div>
	<div class="col-md-6">
		<?php require_once __DIR__ . '/tour_scenes.php'; ?>
	</div>
	<fieldset>
		<?php if ($tour->id): ?>
			<input type="hidden" name="id" value="<?php echo $tour->id; ?>"/>
		<?php endif; ?>
		<input type="hidden" name="view" value="tour"/>
		<input type="hidden" name="task" value="ajaxSaveTour"/>
	</fieldset>
	<script>jQuery.validate({modules: "file"});</script>
</form>


