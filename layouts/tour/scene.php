<?php
defined('_VR360_EXEC') or die;

/**
 * Layout variables
 * ============================
 * @var   Vr360Scene $scene Scene data
 */
$fileName = explode('.', $scene->file)[0];

$hotspots = $scene->getHotspots();
$defaultView = $scene->getParam('defaultview');
$defaultView = new Vr360Object($defaultView);
?>
<scene
		name="scene_<?php echo $fileName ?>"
		title="<?php echo $scene->name ?>"
		subtitle="<?php echo $scene->description ?>"
		onstart=""
		thumburl="panos/<?php echo $fileName ?>.tiles/thumb.jpg"
		lat=""
		lng=""
		heading=""
>
	<view
			hlookat="<?php echo $defaultView->get('hlookat', VR360_TOUR_SCENE_DEFAULT_HLOOKAT); ?>"
			vlookat="<?php echo $defaultView->get('vlookat', VR360_TOUR_SCENE_DEFAULT_VLOOKAT); ?>"
			fov="<?php echo $defaultView->get('fov', $scene->getParam('fov',VR360_TOUR_SCENE_DEFAULT_FOV)); ?>"

			fovtype="<?php echo $scene->getParam('fovtype', VR360_TOUR_SCENE_DEFAULT_FOVTYPE); ?>"
			maxpixelzoom="<?php echo $scene->getParam('maxpixelzoom', VR360_TOUR_SCENE_DEFAULT_MAXPIXELZOOM); ?>"
			fovmin="<?php echo $scene->getParam('fovmin', VR360_TOUR_SCENE_DEFAULT_FOVMIN); ?>"
			fovmax="<?php echo $scene->getParam('fovmax', VR360_TOUR_SCENE_DEFAULT_FOVMAX); ?>"
			limitview="<?php echo $scene->getParam('limitview', VR360_TOUR_SCENE_DEFAULT_LIMITVIEW); ?>"
	/>
	<preview url="panos/<?php echo $fileName ?>.tiles/preview.jpg"/>
	<image>
		<cube url="panos/<?php echo $fileName ?>.tiles/pano_%s.jpg"/>
	</image>
	<?php if (!empty($hotspots)): ?>
		<?php foreach ($hotspots as $index => $hotspot): ?>
			<?php
			$data = array();

			if (null !== $hotspot->params)
			{
				foreach ($hotspot->params as $key => $value)
				{
					$data[] = $key . '="' . $value . '"';
				}
			}
			?>
			<hotspot name="spot<?php echo $hotspot->code; ?>"
			         dataId="<?php echo $hotspot->code ?>"
			         style="<?php echo $hotspot->style; ?>"
			         hotspot_type="<?php echo $hotspot->type; ?>"
			         ath="<?php echo $hotspot->ath ?>"
			         atv="<?php echo $hotspot->atv ?>" <?php echo implode(' ', $data) ?>
			         onover="jscall(if (typeof isAllowAddHotspot !== 'undefined') isAllowAddHotspot(false))" onout="jscall(if (typeof isAllowAddHotspot !== 'undefined') isAllowAddHotspot(true))"
			         />
		<?php endforeach; ?>
	<?php endif; ?>
</scene>
