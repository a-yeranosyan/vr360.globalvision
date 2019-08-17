<?php

defined('_VR360_EXEC') or die;
$user = Vr360Factory::getUser();
?>
<div class="col-md-12">
	<div class="row">
		<div class="container-fluid">
			<form method="post" id="user-form" class="form-horizontal" enctype="multipart/form-data">
				<div class="col-md-12">
					<div class="row">
						<div class="col-md-4">
							<!-- Form Name -->
							<!-- Text input-->
							<div class="form-group">
								<label class="col-md-4 control-label" for="textinput">Email</label>
								<div class="col-md-8">
									<input
											id="user-email" name="email" placeholder="input your email"
											class="form-control input-sm" type="text"
											data-validation="required email"
											value="<?php echo $user->email; ?>">
								</div>
							</div>
							<!-- Text input-->
							<div class="form-group">
								<label class="col-md-4 control-label" for="textinput">Name</label>
								<div class="col-md-8">
									<input id="user-name" name="name" placeholder="input your name"
										   class="form-control input-sm" data-validation="required" type="text"
										   value="<?php echo $user->name; ?>">

								</div>
							</div>
							<!-- Text input-->
							<div class="form-group">
								<label class="col-md-4 control-label" for="textinput">Current password</label>
								<div class="col-md-8">
									<input
											id="user-current-password"
											name="currentpassword"
											placeholder="Enter your current password"
											class="form-control input-sm"
											type="password"
									>
								</div>
							</div>
							<!-- Text input-->
							<div class="form-group">
								<label class="col-md-4 control-label" for="textinput">Password</label>
								<div class="col-md-8">
									<input id="user-password" name="password" placeholder="Change your password"
										   class="form-control input-sm" type="password">

								</div>
							</div>
							<!-- Text input-->
							<div class="form-group">
								<label class="col-md-4 control-label" for="textinput">Confirm password</label>
								<div class="col-md-8">
									<input id="user-confirm-password" name="confirmpassword" placeholder="Confirm your password"
										   class="form-control input-sm" type="password">

								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label class="col-md-4 control-label" for="textinput">Company name</label>
								<div class="col-md-8">
									<input
											id="user-company" name="company" placeholder="your company name"
											class="form-control input-sm" type="text"
											data-validation=""
											value="">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-4 control-label" for="textinput">Company address</label>
								<div class="col-md-8">
									<input
											id="user-company-address" name="companyaddress" placeholder="your company address"
											class="form-control input-sm" type="text"
											data-validation=""
											value="">
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<!-- File Button -->
							<div class="form-group">
								<label class="col-md-4 control-label" for="uploadPhoto">Upload logo</label>
								<div class="col-md-8">
									<input id="user-logo" name="logo" class="input-file" type="file">
								</div>
							</div>
							<!-- Button (Double) -->
							<div class="form-group">
								<label class="col-md-4 control-label" for="save"></label>
								<div class="col-md-8">
									<button id="user-save-profile" name="saveprofile" class="btn btn-primary btn-sm">
										<i class="fa fa-window-restore" aria-hidden="true"></i> <?php echo \Joomla\Language\Text::_('GENERAL_LABEL_SAVE'); ?>
									</button>
								</div>
							</div>
							<?php if (Vr360HelperFile::exists(VR360_PATH_DATA . '/user/' . $user->id . '/logo.png')): ?>
								<div class="row">
									<div class="col-md-12">
										<img src="<?php echo '_/user/' . $user->id . '/logo.png'; ?>" alt="logo"
											 class="img-thumbnail">
									</div>
								</div>
							<?php endif; ?>
						</div>
					</div>
					<fieldset>
						<input type="hidden" name="view" value="user"/>
						<input type="hidden" name="task" value="ajaxSaveProfile"/>
					</fieldset>
					<script>jQuery.validate();</script>
			</form>
		</div>
	</div>
</div>
