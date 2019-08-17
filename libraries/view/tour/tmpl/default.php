<?php defined('_VR360_EXEC') or die; ?>
<!DOCTYPE html>
<html>
<head>
	<?php /* @var Vr360Tour $tour */ ?>
	<?php $tour = $this->tour; ?>
	<?php $thumbnail = $tour->getThumbnail(); ?>
	<title><?php echo Vr360Configuration::getConfig('siteName'); ?> - <?php echo $tour->getName(); ?></title>

	<link rel="shortcut icon" type="image/x-icon" href="./assets/images/globalvision.webp">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0"/>

	<meta name="apple-mobile-web-app-capable" content="yes"/>
	<meta name="apple-mobile-web-app-status-bar-style" content="black"/>

	<!-- Charset -->
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
	<meta http-equiv="x-ua-compatible" content="IE=edge"/>

	<meta name="description" content="<?php echo $tour->getDescription(); ?>">
	<meta name="keywords" content="<?php echo $tour->getKeyword(); ?>">

	<!-- Globalvision -->
	<link rel="stylesheet" type="text/css" href="./assets/css/tour.min.css">

	<!-- Krpano -->
	<script src="<?php echo $tour->getKrpanoJsUrl(); ?>"></script>

	<script src="./assets/js/site/tour.min.js"></script>

	<!-- SEO Metadata -->
	<meta name="robots" content="index, follow"/>

	<?php require_once __DIR__ . '/default_socials.php'; ?>
</head>
<body>
<?php if (!$this->tour->isValid() || !$this->tour->isValidForRender()): ?>
	<span class="label label-danger"><?php echo \Joomla\Language\Text::_('GENERAL_LABEL_INVALID_TOUR'); ?>Invalid tour or data broken</span>
<?php else: ?>
	<div id="pano" style="">
		<noscript>
			<table style="">
				<tr style="vertical-align:middle;">
					<td>
						<div style="text-align:center;">ERROR:<br/><br/>Javascript not activated<br/><br/></div>
					</td>
				</tr>
			</table>
		</noscript>
		<script type="text/javascript">
			window.onload = function () {
				<?php echo $tour->getKrpanoEmbedPano(); ?>
			}
		</script>
	</div>
<?php endif; ?>
</body>
</html>
