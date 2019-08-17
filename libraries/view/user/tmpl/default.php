<?php defined('_VR360_EXEC') or die; ?>

<form name="user-form" action="index.php" method="post">
	<div class="input-group">
		<span class="input-group-addon" id="login-username"><i class="fa fa-user-circle" aria-hidden="true"></i></span>
		<input
				type="text"
				name="username"
				class="form-control"
				placeholder="Username"
				aria-describedby="login-username"
		>
	</div>
	<br/>
	<div class="input-group">
		<span class="input-group-addon" id="login-password"><i class="fa fa-key" aria-hidden="true"></i></span>
		<input
				type="password"
				name="password"
				class="form-control"
				placeholder="Password"
				aria-describedby="login-password"
		>
	</div>
	<br/>
	<button
			type="submit"
			class="btn btn-primary"
			name="user-login"
			id="user-login"
	>
		<i class="fas fa-sign-in-alt"></i> <?php echo \Joomla\Language\Text::_('USER_LABEL_USER_LOGIN'); ?>
	</button>
	<button
			type="submit"
			class="btn btn-danger"
			name="user-reset-password"
			id="user-reset-password"
	>
		<i class="fas fa-exchange-alt"></i> <?php echo \Joomla\Language\Text::_('USER_LABEL_RESET_PASSWORD'); ?>
	</button>
	<fieldset>
		<input type="hidden" name="view" value="user"/>
		<input type="hidden" name="task" value="login"/>
	</fieldset>
</form>
