<?php defined('_VR360_EXEC') or die; ?>
<!DOCTYPE html>
<html>
<head>
	<link rel="shortcut icon" type="image/x-icon" href="./assets/images/globalvision.webp">
	<title><?php echo Vr360Configuration::getConfig('siteName'); ?></title>
	<meta name="description" content="<?php echo Vr360Configuration::getConfig('siteDescription'); ?>"/>

	<meta property="og:title" content="<?php echo Vr360Configuration::getConfig('siteName'); ?>"/>
	<meta property="og:description" content="<?php echo Vr360Configuration::getConfig('siteDescription'); ?>"/>
	<meta property="og:type" content="website"/>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- VENDOR -->
	<!-- jQuery -->
	<script type="text/javascript" src="./assets/vendor/jquery-2.2.4.min.js"></script>
	<!-- jQuery UI -->
	<script type="text/javascript" src="./assets/vendor/jquery-ui/jquery-ui.min.js"></script>
	<link rel="stylesheet" href="./assets/vendor/jquery-ui/jquery-ui.min.css">
	<link rel="stylesheet" href="./assets/vendor/jquery-ui/jquery-ui.theme.min.css">
	<!-- Bootstrap -->
	<script type="text/javascript" src="./assets/vendor/bootstrap/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="./assets/vendor/bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="./assets/vendor/fontawesome-5.0.0/web-fonts-with-css/css/fontawesome-all.min.css">
	<!-- Validator -->
	<script
			type="text/javascript"
			src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js">
	</script>

	<!-- Globalvision -->
	<link rel="stylesheet" type="text/css" href="./assets/css/globalvision.min.css">
	<?php if (Vr360HelperAuthorize::isAuthorized()): ?>
		<script type="text/javascript" src="./assets/js/admin.js"></script>
		<script type="text/javascript" src="./assets/js/admin/modal.min.js"></script>
		<script type="text/javascript" src="./assets/js/admin/waiting.min.js"></script>
		<script type="text/javascript" src="./assets/js/log.min.js"></script>
	<?php endif; ?>
</head>
<body>
<div class="container-fluid">
	<div class="row mainWrapper">
		<!-- Waiting layer -->
		<div class="row">
			<div id="overlay-waiting" class="waiting">
				<div class="col-md-6 col-md-offset-3 messages">
				</div>
				<div class="col-md-2 col-md-offset-3">
					<button class="btn btn-primary btn-block btn-log-close ajax-close">
						<i class="fa fa-close"></i> <?php echo \Joomla\Language\Text::_('GENERAL_LABEL_CLOSE'); ?>
					</button>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="header">
					<a href="http://globalvision.ch" target="_blank" class="logo">
						<!--<img id="logo" src="./assets/images/logo.png"/>-->
					</a>
				</div>
			</div>
			<div class="col-md-6">
				<div class="text-center center-block">
					<a
							class="social-facebook"
							href="<?php echo Vr360Configuration::getConfig('socials')['facebook']; ?>"
							target="_blank"
					>
						<i class="fab fa-facebook-square fa-3x"></i></i>
					</a>
					<a
							class="social-twitter"
							href="<?php echo Vr360Configuration::getConfig('socials')['twitter']; ?>"
							target="_blank"
					>
						<i class="fab fa-twitter-square fa-3x"></i>
					</a>
					<a
							class="social-googleplus"
							href="<?php echo Vr360Configuration::getConfig('socials')['google+']; ?>"
							target="_blank"
					>
						<i class="fab fa-google-plus-square fa-3x"></i></a>
					<a
							class="social-email"
							href="<?php echo Vr360Configuration::getConfig('socials')['mail']; ?>"
					>
						<i class="fas fa-envelope-square fa-3x"></i>
					</a>
				</div>
			</div>
		</div>
		<div class="col-md-12">
			<div class="system-messages">
				<?php $messages = Vr360Session::getInstance()->getMessages(); ?>
				<?php if (!empty($messages)): ?>
					<?php foreach ($messages as $key => $type): ?>
						<?php foreach ($type as $message): ?>
							<div class="alert alert-<?php echo $key; ?>"><?php echo $message; ?></div>
						<?php endforeach; ?>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
			<hr/>
		</div>
		<div class="col-md-12">
			{content}
		</div>
	</div>
</div>
</body>
</html>