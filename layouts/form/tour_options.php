<?php
defined('_VR360_EXEC') or die;
// Skins
$skins = Vr360HelperKrpano::getListOfSkins();
?>
<style type="text/css">
	.custom-button {
		width: 161px;
	}
</style>
<div class="tour-options">
	<div class="form-group">
		<span class="col-sm-2 control-label label label-primary">
			<i class="fa fa-cogs" aria-hidden="true"></i> Options
		</span>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Skins</label>
		<div class="col-sm-10">
			<select class="form-control input-sm tour-skins" title="skin" name="params[skin]">
				<?php foreach ($skins as $skin): ?>
					<?php if ($skin != 'base.xml'): ?>
						<option value="<?php echo $skin; ?>"><?php echo $skin; ?></option>
					<?php endif; ?>
				<?php endforeach; ?>
			</select>
		</div>
	</div>
	<div class="well well-sm">
		<div class="container-fluid">
			<div class="form-group">
				<div class="checkbox">
					<label>
						<input
								type="checkbox"
								id="tour-param-rotation"
								class="tour-params"
								name="params[rotation]"
							<?php echo ($tour->params->get('rotation', false)) ? 'checked="checked"' : '' ?>
								value="1"
								size="80"/> <?php echo \Joomla\Language\Text::_('TOUR_LABEL_OPTION_AUTO_ROTATION'); ?>
					</label>
				</div>
			</div>
			<div class="form-group">
				<div class="checkbox">
					<label>
						<input
								type="checkbox"
								id="tour-param-socials"
								class="tour-params"
								name="params[socials]"
								value="1"
							<?php echo ($tour->params->get('socials', false)) ? 'checked="checked"' : '' ?>
								size="80"/> <?php echo \Joomla\Language\Text::_('TOUR_LABEL_OPTION_SHOW_MEDIA_SOCIAL_BUTTONS'); ?>
					</label>
				</div>
			</div>
			<div class="form-group">
				<div class="checkbox">
					<label>
						<input
								type="checkbox"
								id="tour-param-use-logo"
								class="tour-params"
								name="params[userlogo]"
								value="1"
							<?php echo ($tour->params->get('userlogo', false)) ? 'checked="checked"' : '' ?>
								size="80"/> <?php echo \Joomla\Language\Text::_('TOUR_LABEL_OPTION_USE_LOGO'); ?>
					</label>
				</div>
			</div>
			<div class="form-group">
				Color
			</div>
			<div class="form-group">
				<div class="checkbox">
					<label>
						<input
								type="radio"
								class="tour-params"
								name="colorType"
								value="1"
								size="80"/> First Color
					</label>
				</div>
			</div>
			<div class="form-group">
				<div class="checkbox">
					<label>
						<input
								type="radio"
								class="tour-params"
								name="colorType"
								value="1"
								size="80"/> Secondary color
					</label>
				</div>
			</div>
			<div class="form-group">
				<div class="checkbox">
					<label>
						<input
								type="radio"
								class="tour-params"
								name="colorType"
								value="1"
								size="80"/> Thrid Color
					</label>
				</div>
			</div>
			<div class="form-group">
				<div class="checkbox">
					<label>
						<input
								type="checkbox"
								id="tour-param-map"
								class="tour-params"
								name=""
								value="1"
								size="80"/>Map Display
					</label>
				</div>
			</div>
			<div class="form-group">
				<div class="checkbox">
					<label>
						<input
								type="checkbox"
								id="tour-param-vr"
								class="tour-params"
								name=""
								value="1"
								size="80"/>Level Display
					</label>
				</div>
			</div>
			<div class="form-group">
				<div class="checkbox">
					<label>
						<input
								type="checkbox"
								id="tour-param-vr"
								class="tour-params"
								name="params[vr_mode]"
								value="1"
								size="80"/>Vr mode
					</label>
				</div>
			</div>
			<div class="form-group">
				<div class="checkbox">
					<div class="dropdown">
						<button class="btn btn-default dropdown-toggle custom-button" type="button"
						        data-toggle="dropdown">Hotspots Colour
							<span class="caret"></span></button>
						<ul class="dropdown-menu">
							<li><a href="#">Red</a></li>
							<li><a href="#">Green</a></li>
							<li><a href="#">Blue</a></li>
						</ul>
					</div>
				</div>
				<div class="checkbox">
					<div class="dropdown">
						<button class="btn btn-default dropdown-toggle custom-button" type="button"
						        data-toggle="dropdown">Hotspots Type
							<span class="caret"></span></button>
						<ul class="dropdown-menu">
							<li><a href="#">Blinking</a></li>
							<li><a href="#">Still</a></li>
							<li><a href="#">Custom Icon</a></li>
							<li><a href="#">Graphic</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
