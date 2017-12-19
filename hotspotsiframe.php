<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'bootstrap.php';

$hotSpotImgUrl     = base64_encode("/assets/images/hotspot.png");
$hotSpotInfoImgUrl = base64_encode("/assets/images/information.png");
$tourId            = Vr360Factory::getInput()->getInt('uId', 0);
$tourUrl           = '//' . $_SERVER['HTTP_HOST'] . '/_/' . $tourId . '/vtour';

$tour   = Vr360ModelTour::getInstance()->getItem($tourId);
$scenes = $tour->id ? $tour->getScenes() : array();
?>
<!DOCTYPE html>
<html>
<head>
	<meta
			name="viewport"
			content="target-densitydpi=device-dpi, width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, minimal-ui"/>
	<meta name="apple-mobile-web-app-capable" content="yes"/>
	<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
	<meta http-equiv="x-ua-compatible" content="IE=edge"/>
	<!-- Globalvision -->
	<link rel="stylesheet" type="text/css" href="./assets/css/tour.min.css">
	<link rel="stylesheet" type="text/css" href="./assets/css/hotspots.min.css">
	<script type="text/javascript" src="./assets/vendor/jquery-2.2.4.min.js"></script>
	<script src='<?php echo $tourUrl . '/tour.js'; ?>'></script>
	<!-- Bootstrap -->
	<script type="text/javascript" src="./assets/vendor/bootstrap/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="./assets/vendor/bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="./assets/vendor/fontawesome-5.0.0/web-fonts-with-css/css/fontawesome-all.css">
</head>
<body>
<div id="button-container">
	<div class="container-fluid">

		<div class="button-group" role="group">
			<button type="button" id="add_hotpost" class="btn btn-primary btn-sm" onclick="addHotspot();">
				<i class="fas fa-plus-square"></i> Add hotspot
			</button>
			<button type="button" id="hotpost_done" class="btn btn-primary btn-sm" onclick="choose_hotSpot_type();">
				<i class="fas fa-indent"></i> Choose type
			</button>

			<button type="button" id="remove_hotpost" class="btn btn-danger btn-sm" onclick="remove_hotspot();">
				<i class="fas fa-minus"></i> Delete hotspot
			</button>

			<button type="button" id="done_remove" class="btn btn-danger btn-sm" onclick="done_remove();">
				<i class="fas fa-save"></i> Removed done
			</button>
			<button type="button" id="moveHotspot" class="btn btn-warning btn-sm" onclick="moveHotspot();">
				<i class="fas fa-arrows-alt"></i> Move hotspots
			</button>
			<button type="button" id="moveHotspotDone" class="btn btn-warning btn-sm" onclick="moveHotspotDone();">
				<i class="fas fa-save"></i> Moved done
			</button>
		</div>

		<div class="button-group" role="group" style="margin-top: 5px">
			<button type="button" id="set_defaultView" class="btn btn-primary btn-sm" onclick="setDefaultView();">
				<i class="fas fa-window-restore"></i> Set default view
			</button>
		</div>

	</div>
</div>
<div id="choose_hotSpot_type_id">
	Choose hotspot type:
	<button type="button" class="btn btn-default btn-sm" onclick="setHotSpotType_Text()">
		<i class="far fa-window-maximize"></i> Modal text
	</button>
	<?php if (!empty($scenes)): ?>
		<button type="button" class="btn btn-default btn-sm" onclick="setHotSpotType_Nomal()">
			<i class="fas fa-link"></i> Scene linking
		</button>
	<?php endif; ?>
</div>

<div id="input_text_dialog" class="form-inline">
	<div class="form-group">
		<input
				id='text_input_hotspot'
				type="text"
				size="30"
				placeholder="Enter your text"
				class="form-control"
		/>
		<button
				type="button"
				class="btn btn-primary"
				onclick="hotspot_add_text_from_input();"
		><i class="fas fa-save"></i> Finish
		</button>
	</div>
</div>

<div id="show_link">
	Linked scene: <select id="selectbox">
		<?php if (!empty($scenes)): ?>
			<?php foreach ($scenes as $scene): ?>
				<option value="scene_<?php echo explode('.', $scene->file)[0] ?>"><?php echo $scene->name ?></option>
			<?php endforeach; ?>
		<?php endif; ?>
	</select>
	<button id="done_link" onclick="get_link()"><i class="fas fa-save"></i> Done</button>
</div>

<div id="pano">
	<script type="text/javascript">
		embedpano({
			swf: '<?php echo $tourUrl . '/tour.swf'; ?>',
			xml: '<?php echo $tourUrl . '/tour.xml?' . time(); ?>',
			target: "pano",
			html5: "prefer",
			passQueryParameters: true
		});

		var krpano = document.getElementById('krpanoSWFObject');

		var add_hotpost = document.getElementById('add_hotpost');
		var hotspot_done = document.getElementById('hotpost_done');
		var selectbox = document.getElementById('selectbox');
		var showlink = document.getElementById('show_link');

		var i = 0;
		var uniqname = '';
		var scene_nums = krpano.get('scene.count');
		var hotspotList = [];
		var current_scene = '';
		var current_vTour_hotspot_counter = 0;
		var current_randome_val = Math.round(Math.random() * 1000000000).toString() + Math.round(Math.random() * 1000000000).toString();

		function disableButton(elements) {
			if (jQuery.isArray(elements)) {
				jQuery.each(elements, function (index, element) {
					jQuery(element).attr('disabled', 'disabled');
					jQuery(element).addClass('hide');
					jQuery(element).hide();
				})
			}
			else {
				jQuery(elements).attr('disabled', 'disabled');
				jQuery(element).addClass('hide');
				jQuery(elements).hide();
			}
		}

		function enableButton(elements) {
			if (jQuery.isArray(elements)) {
				jQuery.each(elements, function (index, element) {
					jQuery(element).removeAttr('disabled');
					jQuery(element).removeClass('hide');
					jQuery(element).show();
				})
			}
			else {
				jQuery(element).removeAttr('disabled');
				jQuery(element).removeClass('hide');
				jQuery(element).show();
			}
		}

		function getHotspotsCount() {
			return krpano.get('hotspot.count');
		}

		function addHotspot(currentHotspotData) {
			disableButton(['#add_hotpost', '#remove_hotpost', '#moveHotspot', '#set_defaultView']);

			i += 1;
			krpano.call("screentosphere(mouse.x,mouse.y,m_ath,m_atv);");

			var scene_num = krpano.get('scene.count');
			current_scene = krpano.get('xml.scene');

			var posX = krpano.get('m_ath');
			var posY = krpano.get('m_atv');

			uniqname = "spot_new_" + i;
			krpano.call("addhotspot(" + uniqname + ");");

			if (typeof currentHotspotData == 'undefined')  // new nomal hotspot added
			{
				currentHotspotData = {};
				currentHotspotData.ath = krpano.get('view.hlookat');
				currentHotspotData.atv = krpano.get('view.vlookat');
				krpano.call("set(hotspot[" + uniqname + "].ondown, draghotspot(););");

				hotspot_done.style.display = 'inline-block';
			}
			else // THIS HOTSPOT HAVE AADITIONAL DATA FROM HOTDPOT LIST
			{
				if (currentHotspotData.hotspot_type == 'normal') {
					krpano.call("set(hotspot[" + uniqname + "].linkedscene, " + currentHotspotData.linkedscene + ");");
				}
				if (currentHotspotData.hotspot_type == 'text') {
					krpano.call("set(hotspot[" + uniqname + "].hotspot_text, " + currentHotspotData.hotspot_text + ");");
				}
			}

			krpano.call("set(hotspot[" + uniqname + "].ath, " + currentHotspotData.ath + ");");
			krpano.call("set(hotspot[" + uniqname + "].sceneName, " + current_scene + ");");
			krpano.call("set(hotspot[" + uniqname + "].atv, " + currentHotspotData.atv + ");");
			krpano.call("set(hotspot[" + uniqname + "].hotspot_type, " + currentHotspotData.hotspot_type + ");");

			// Hotspot arrow image
			krpano.call("set(hotspot[" + uniqname + "].url, assets/images/hotspot.png);");
		}

		function list_scene() {
			krpano.call("set(hotspot[" + uniqname + "].ondown, '');");

			show_link.style.display = 'block';
			hotspot_done.style.display = 'none';
		}

		function get_link() {
			var scene = selectbox.value;
			krpano.call("set(hotspot[" + uniqname + "].linkedscene, " + scene + ");");
			hotspot_add_done();
		}

		var removedHotspot = [];

		function addRemovedHotspot(name) {
			removedHotspot.push(name);
		}

		function remove_hotspot() {

			alert('Click on any hotspot to remove it');

			document.getElementById('done_remove').style.display = 'inline-block';

			disableButton(['#add_hotpost', '#remove_hotpost', '#moveHotspot', '#set_defaultView'])

			var hotspot_count = getHotspotsCount();
			for (i = 0; i < hotspot_count; i++) {
				//krpano.call("set(hotspot[" + i + "].onclick, 'removehotspot(get(name));');");
				krpano.call("set(hotspot[" + i + "].onclick, 'removehotspot(get(name)); js(addRemovedHotspot(get(name)));');");
			}
		}

		function done_remove() {

			enableButton(['#add_hotpost', '#remove_hotpost', '#moveHotspot', '#set_defaultView'])
			document.getElementById('done_remove').style.display = 'none';

			var hotspot_count = getHotspotsCount();
			for (i = 0; i < hotspot_count; i++) {
				krpano.call("set(hotspot[" + i + "].onclick, '');");
			}
		}

		function choose_hotSpot_type() {
			$('#hotpost_done').hide();
			document.getElementById('add_hotpost').style.display = 'inline-block';
			$('#choose_hotSpot_type_id').show();

			//this line make hotspot can't move anymore :)
			krpano.call("set(hotspot[" + uniqname + "].ondown, '');");

		}

		function setHotSpotType_Text() {
			$('#choose_hotSpot_type_id').hide();
			krpano.call("set(hotspot[" + uniqname + "].hotspot_type, text);");

			$('#input_text_dialog').show();
			$('#input_text_dialog #text_input_hotspot').val('');
		}

		function setHotSpotType_Nomal() {
			$('#choose_hotSpot_type_id').hide();
			krpano.call("set(hotspot[" + uniqname + "].hotspot_type, normal);");
			$('#show_link').show();
		}

		function hotspot_add_text_from_input() {
			$('#input_text_dialog').hide();
			krpano.call("set(hotspot[" + uniqname + "].hotspot_text, " + $('#text_input_hotspot').val() + ");");
			hotspot_add_done();
		}

		function hotspot_add_done() {
			$('#input_text_dialog').hide();
			$('#show_link').hide();

			enableButton(['#add_hotpost', '#remove_hotpost', '#moveHotspot', '#set_defaultView']);
		}

		var defaultViewList = {};

		function setDefaultView() {
			var scene = krpano.get('xml.scene');

			defaultViewList[scene] = {};
			defaultViewList[scene].hlookat = krpano.get('view.hlookat');
			defaultViewList[scene].vlookat = krpano.get('view.vlookat');
			defaultViewList[scene].fov = krpano.get('view.fov');

			alert('Applied default view hlookat: ' + defaultViewList[scene].hlookat + ' , vlookat: ' + defaultViewList[scene].vlookat + ' , fov: ' + defaultViewList[scene].fov);
		}

		function rotateToDefaultViewOf(scene) {
			//if current scene have edited default view but not save yet, the xml not have changed, so default view still in xml value,
			// we need to rotate to default view.
			if (typeof defaultViewList[scene] != 'undefined') {
				krpano.set('view.hlookat', defaultViewList[scene].hlookat);
				krpano.set('view.vlookat', defaultViewList[scene].vlookat);
				krpano.set('view.fov', defaultViewList[scene].fov);
			}
		}

		function hmv(currentHotspot, currentScene, i) {

// 				if (typeof currentHotspot !== "object") return false;
// 				var hotspotList = superHotspot.hotspotList;
// 				//var sceneName = this.kr.get('xml.scene');
// 				var sceneName = currentScene;
// 				var currentHotspotData = {};
// 				currentHotspotData.ath = currentHotspot.ath;
// 				currentHotspotData.atv = currentHotspot.atv;
// 				currentHotspotData.hotspot_type = currentHotspot.hotspot_type;
// 				currentHotspotData.sceneName    = sceneName;
// 				currentHotspotData.reRender     = 'true';

// 				if ( typeof currentHotspot.linkedscene != 'undefined')
// 					currentHotspotData.linkedscene = currentHotspot.linkedscene;
// 				else if ( typeof currentHotspot.hotspot_text != 'undefined' )
// 					currentHotspotData.hotspot_text = currentHotspot.hotspot_text;
// 				else
// 					console.error('no hotspot data found: ');

// 				console.info (currentHotspotData);

// 				current_vTour_hotspot_counter++;
// 				hotspotList[sceneName][current_randome_val + current_vTour_hotspot_counter.toString()] = currentHotspotData;

			//if hotspot just live in js var ( not live in xml yet )
			if (currentHotspot.url == 'assets/images/hotspot.png') {
				//hm... do nothing, it's auto re-locate itself
			}
			else // it live in xml, and will auto-reload-by krpano, so we need to
			{
				//1. add it to removed list
				addRemovedHotspot(currentHotspot.name);
				//2. make it render - able
				krpano.call("set(hotspot[" + i + "].xreRender, 'true')");
			}
		}

		function moveHotspot() {
			disableButton(['#add_hotpost', '#remove_hotpost', '#set_defaultView'])

			var hotspot_count = getHotspotsCount();
			for (var i = 0; i < hotspot_count; i++) {
				krpano.call("set(hotspot[" + i + "].ondown, 'draghotspot(); js(hmv(get(hotspot[" + i + "]), get(xml.scene), " + i + ") );')");
			}

			$("#moveHotspot").hide();
			$("#moveHotspotDone").show();
		}

		function moveHotspotDone() {
			enableButton(['#add_hotpost', '#remove_hotpost', '#set_defaultView'])

			var hotspot_count = getHotspotsCount();
			for (var i = 0; i < hotspot_count; i++) {
				krpano.call("set(hotspot[" + i + "].ondown, '');");
			}
			$("#moveHotspot").show();
			$("#moveHotspotDone").hide();
		}

		/**
		 *
		 * @returns {boolean}
		 */
		function isReady() {
			if (
				add_hotpost.disabled == false
				&& document.getElementById('remove_hotpost').disabled == false
				&& document.getElementById('moveHotspot').disabled == false
			) {
				return true;
			}
			return false;
		}

		function superHotspotObj(krpano_Obj) {
			var thisAlias = this;

			this.sceneCount = krpano_Obj.get('scene.count');
			this.hotspotList = {};
			this.kr = krpano_Obj;
			this.firstTimesSave = 0;

			this.saveCurrentHotspotFromCurrentScene = function () {
				// if ( thisAlias.firstTimesSave == 0 ){thisAlias.firstTimesSave = 1;}

				sceneName = this.kr.get('xml.scene');
				// console.info('saveCurrentHotspotFromCurrentScene: ' + sceneName);
				thisAlias.hotspotList[sceneName] = {};
				var hotspot_count = thisAlias.kr.get('hotspot.count');
				for (var i = 0; i < hotspot_count; i++) {
					// console.log(thisAlias.kr.get('hotspot[' + i + '].url'));
					if (/hotspot\.png/.test(thisAlias.kr.get('hotspot[' + i + '].url')) || /vtourskin_hotspot\.png/.test(thisAlias.kr.get('hotspot[' + i + '].url')) || /information\.png/.test(thisAlias.kr.get('hotspot[' + i + '].url'))) {
						// console.log('collecting hotspot: ' + i);
						// console.info(thisAlias.kr.get('hotspot[' + i + ']'));

						thisAlias.hotspotList[sceneName][current_randome_val + current_vTour_hotspot_counter.toString()] = {
							'ath': thisAlias.kr.get('hotspot[' + i + '].ath'),
							'atv': thisAlias.kr.get('hotspot[' + i + '].atv'),
							'sceneName': thisAlias.kr.get('hotspot[' + i + '].sceneName'),
							'hotspot_type': thisAlias.kr.get('hotspot[' + i + '].hotspot_type'),
							'reRender': 'true'
						}
						if (/vtourskin_hotspot\.png/.test(thisAlias.kr.get('hotspot[' + i + '].url')) || /information\.png/.test(thisAlias.kr.get('hotspot[' + i + '].url'))) {
							//hotspot which is aready in xml shouldnt re-render by js anymore, if not, doulicate hotspot will apperent.
							// console.log('superHotspot: xreRender: [' + i + '] ' + thisAlias.kr.get('hotspot[' + i + '].xreRender'));

							if (thisAlias.kr.get('hotspot[' + i + '].xreRender') == 'true') {
								thisAlias.hotspotList[sceneName][current_randome_val + current_vTour_hotspot_counter.toString()].reRender == 'true'
							}
							else
								thisAlias.hotspotList[sceneName][current_randome_val + current_vTour_hotspot_counter.toString()].reRender = 'false';
						}

						if (thisAlias.kr.get('hotspot[' + i + '].hotspot_type') == 'normal') {
							thisAlias.hotspotList[sceneName][current_randome_val + current_vTour_hotspot_counter.toString()].linkedscene = thisAlias.kr.get('hotspot[' + i + '].linkedscene');
						}
						if (thisAlias.kr.get('hotspot[' + i + '].hotspot_type') == 'text') {
							thisAlias.hotspotList[sceneName][current_randome_val + current_vTour_hotspot_counter.toString()].hotspot_text = thisAlias.kr.get('hotspot[' + i + '].hotspot_text');
						}
						current_vTour_hotspot_counter++;
					}
					else {
					}
				}
			};

			/**
			 * Add hotspots into scene
			 */
			this.loadHotspotsToCurrentSceneFromSavedData = function () {
				sceneName = this.kr.get('xml.scene');

				for (var hotspotId in thisAlias.hotspotList[sceneName]) {
					var currentHotspotData = thisAlias.hotspotList[sceneName][hotspotId];

					if (thisAlias.hotspotList[sceneName][hotspotId].reRender == "true") {
						addHotspot(currentHotspotData);
					}
				}

				//this go as sub-job

				for (var i in removedHotspot) {
					if (removedHotspot[i].match(/spot_new_/g) == null)
						krpano.call('removehotspot(' + removedHotspot[i] + ');');
					else
						removedHotspot.splice(i, 1); //remove
				}

				rotateToDefaultViewOf(sceneName);
			};

			this.getData = function () {
				if (true) {
					thisAlias.saveCurrentHotspotFromCurrentScene();
				}
				// if(thisAlias.firstTimesSave == 0){thisAlias.saveCurrentHotspotFromCurrentScene();}
				return thisAlias;
			};

			this.setData = function (data) {
				thisAlias = data;
			}
		}

		var superHotspot = new superHotspotObj(krpano);
	</script>
</div>

</body>
</html>
