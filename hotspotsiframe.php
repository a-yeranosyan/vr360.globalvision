<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'bootstrap.php';

$hotSpotImgUrl     = base64_encode("/assets/images/hotspot.png");
$hotSpotInfoImgUrl = base64_encode("/assets/images/information.png");
$tourId            = Vr360Factory::getInput()->getInt('uId', 0);
$tourUrl           = '//' . $_SERVER['HTTP_HOST'] . '/_/' . $tourId . '/vtour';

$tour = new Vr360Tour;
$tour->load(
	array(
		'id'         => $tourId,
		'created_by' => Vr360Factory::getUser()->id
	)
);

$tours = new Vr360ModelTours;
$scenes = !$tour->id ? array() : $tour->getScenes();
?>
<!DOCTYPE html>
<html>
<head>
	<meta name="viewport"
	      content="target-densitydpi=device-dpi, width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, minimal-ui"/>
	<meta name="apple-mobile-web-app-capable" content="yes"/>
	<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
	<meta http-equiv="x-ua-compatible" content="IE=edge"/>
	<!-- Globalvision -->
	<link rel="stylesheet" type="text/css" href="./assets/css/hotspots.min.css">
	<link rel="stylesheet" type="text/css" href="./assets/css/tour.min.css">
	<!-- <link rel="stylesheet" type="text/css" href="./assets/css/hotspots.min.css"> -->
	<script type="text/javascript" src="./assets/vendor/jquery-2.2.4.min.js"></script>
	<script src='<?php echo $tourUrl . '/tour.js'; ?>'></script>
	<!-- Bootstrap -->
	<script type="text/javascript" src="./assets/vendor/bootstrap/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="./assets/vendor/bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="./assets/vendor/font-awesome/css/font-awesome.css">
	<link rel="stylesheet" type="text/css" media="screen" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.7.5/css/bootstrap-select.min.css">
	<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.7.5/js/bootstrap-select.min.js"></script>
</head>
<body>
<div id="button-container">
<div class="alert alert-info show-message-for-click" >
	<strong>Hold click </strong>for adding hotspot
</div>
<div class="popup-inner" id="edit-remove-move" style="display:none;">
		<button type="button" id="edit_hotpost" class="btn btn-primary btn-sm button-custom-th" onclick="editHotspot();">
			Edit
		</button>
		<button type="button" id="move_hotspot" class="btn btn-primary btn-sm button-custom-th" onclick="moveHotspot()" ;">
			Move
		</button>
		<button type="button" id="delete_hotpost" class="btn btn-primary btn-sm button-custom-th" onclick="deleteHotspot();">
			Delete
		</button>
		<a class="popup-close" data-popup-close="popup-1" href="#">x</a>
		<div id="text_div_edit" class="form-group" style="display: none;">
			<div class="form-group">
				<input
				type="text"
				size="29"
				maxlength="255"
				placeholder="Edit"
				class="form-control"
				/>
			</div>
			<div class="form-group">
				<textarea
				class="form-control"
				placeholder="Edit Description"
				maxlength="255"
				style="
				resize: none;
				width:259px;
				overflow:hidden;
				margin-top:2px;
				margin-bottom:2px;
				height: 155px;
				"
				></textarea>
			</div>
			<button
				type="button"
				class="btn btn-default"
				onclick="saveEdit()">Save
			</button>
		</div>
	</div>
	<div class="popup" data-popup="popup-1">
	    <div class="popup-inner" id="popup">
			<form >
				<input type="hidden" id="user_id" value="<?php echo $tour->created_by?>">
				<input type="hidden" id="tour_id" value="<?php echo $tour->id?>">
					<button type="button" id="add_hotpost" class="btn btn-primary btn-sm button-custom" onclick="addHotspot();">
						Add hotspot here
					</button>
					<button type="button" id="set_defaultView" class="btn btn-primary btn-sm button-custom" onclick="setDefaultView();">
						Set default view
					</button>
			<div id="open-add-hot" class="form-inline" style="display: none;">
				<div class="form-group">
					<div class="button-group" role="group">
						<button type="button" id="add_text" class="btn btn-primary btn-sm button-custom" onclick="addText();">
							Add Text
						</button>
						<button type="button" id="add_Tooltip" class="btn btn-primary btn-sm button-custom" onclick="addTooltip();">
							Add Tooltip
						</button>
					</div>
					<div class="button-group" role="group">
						<button type="button" id="add_Modal" class="btn btn-primary btn-sm button-custom" onclick="addModal();">
							Add  Modal
						</button>
						<button type="button" id="add_image" class="btn btn-primary btn-sm button-custom" onclick="addImage();">
							Add  Image
						</button>
					</div>
					<div class="button-group" role="group">
						<button type="button" id="add_video" class="btn btn-primary btn-sm button-custom" onclick="addVideo();">
							Add  Video
						</button>
						<button type="button" id="add_link" class="btn btn-primary btn-sm button-custom" onclick="addLink();">
							Add  Link a scene
						</button>
					</div>
					<!-- TeXt-->
					<div id="text_div" class="form-group" style="display: none;">
						<div class="form-group">
							<div class="form-group">
								<input
								id='text_t'
								maxlength="255"
								type="text"
								size="29"
								placeholder="Input Text Title"
								class="form-control"
								/>
							</div>
							<textarea
								class="form-control"
								placeholder="Input Description"
								id="text_text"
								maxlength="255"
								style="
								resize: none;
								width:259px;
								overflow:hidden;
								margin-top:2px;
								margin-bottom:2px;
								height: 155px;
								"
							></textarea>
						</div>
						<button
								type="button"
								id="savehotspots"
								class="btn btn-default "
								onclick="SaveHot('text')">Save
						</button>
						<button
							type="button"
							class="btn btn-default"
							onclick="onclickCancel()">Back
						</button>
					</div>
					<!-- ToooltIp-->
					<div id="Tooltip_div" class="form-group" style="display: none;">
						<div class="form-group">
							<input
								id='tooltip_t'
								type="text"
								maxlength="255"
								size="29"
								placeholder="Input Tooltip Title"
								class="form-control"
							/>
						</div>
						<div class="form-group">
							<textarea
								class="form-control"
								placeholder="Input Description"
								id="tooltip_d"
								maxlength="255"
								style="
								resize: none;
								width:259px;
								overflow:hidden;
								margin-top:2px;
								margin-bottom:2px;
								height: 155px;
								"
							></textarea>
						</div>
						<br>
						<button
								type="button"
								id="savehotspots"
								class="btn btn-default "
								onclick="SaveHot('tooltip')">Save
						</button>
						<button
							type="button"
							class="btn btn-default"
							onclick="onclickCancel()">Back
						</button>
					</div>
					<!-- Modal-->
					<div id="modal_div" class="form-group" style="display: none;">
						<div class="form-group">
							<input
								id='modal_t'
								type="text"
								size="29"
								placeholder="Input Modal Title"
								class="form-control"
							/>
						</div>
						<div class="form-group">
							<textarea
								class="form-control"
								placeholder="Input Description"
								id="modal_d"
								maxlength="255"
								style="
								resize: none;
								width:259px;
								overflow:hidden;
								margin-top:2px;
								margin-bottom:2px;
								height: 155px;
								"
							></textarea>
						</div>
						<br>
						<button
								type="button"
								id="savehotspots"
								class="btn btn-default "
								onclick="SaveHot('modal')">Save
						</button>
						<button
							type="button"
							class="btn btn-default"
							onclick="onclickCancel()">Back
						</button>
					</div>
					<!-- Image-->
					<div id="image_div" class="form-group" style="display: none;">
						<div class="form-group">
							<input
								id='image_url'
								maxlength="255"
								type="text"
								size="29"
								placeholder="Image Url"
								class="form-control"
								style="margin-bottom: 2px "
							/>
						</div>
						<button
								type="button"
								id="savehotspots"
								class="btn btn-default "
								onclick="SaveHot('image')">Save
						</button>
						<button
							type="button"
							class="btn btn-default"
							onclick="onclickCancel()">Back
						</button>
					</div>
					<!-- Video-->
					<div id="video_div" class="form-group" style="display: none;">
						<div class="form-group">
							<input
								id='video_url'
								maxlength="255"
								type="text"
								size="29"
								placeholder="Video Url"
								class="form-control"
								style="margin-bottom: 5px; margin-top: 5px;"
							/>
						</div>
						<button
								type="button"
								id="savehotspots"
								class="btn btn-default "
								onclick="SaveHot('video')">Save
						</button>
						<button
							type="button"
							class="btn btn-default"
							onclick="onclickCancel()">Back
						</button>
					</div>
					<!-- addLinkScene-->
					<div id="link_div" class="form-group" style="display: none;">
						<select
							class="selectpicker"
							data-width="261px"
							id="selectbox"
						>
						<?php if (!empty($scenes)): ?>
						<?php foreach ($scenes as $scene): ?>
							<option value="scene_<?php echo explode('.', $scene->file)[0] ?>"><?php echo $scene->name ?></option>
						<?php endforeach ?>
						<?php endif; ?>
						</select>
						<button
								type="button"
								id="savehotspots"
								class="btn btn-default "
								onclick="SaveHot('linkscene')">Save
						</button>
						<button
							type="button"
							class="btn btn-default button-custom-save-cancel"
							onclick="onclickCancel()">Back
						</button>
					</div>
				</div>
			</div>
	        <a class="popup-close" data-popup-close="popup-1" href="#">x</a>
		</form>
	</div>
	</div>
</div>
<div id="pano" >
	<script type="text/javascript">
		var allow = true;
		function isAllowAddHotspot(isAllowAddHotspot){
			if (isAllowAddHotspot == 'false') isAllowAddHotspot = false;
			allow = isAllowAddHotspot;
		}
		$(function() {
			var krpano = document.getElementById('krpanoSWFObject');
			var timeout, clicker = $("#pano");
			var oldX, oldY;
			//----- OPEN
			clicker.mousedown(function(e){
				oldX = e.pageX;
				oldY = e.pageY;
				if (allow) {
					timeout = setInterval(function(){
						var targeted_popup_class = jQuery(this).attr('data-popup-open');
						$('[data-popup=popup-1]').fadeIn(350);
						var x = e.pageX ;
						var y = e.pageY;
						krpano.call("screentosphere(mouse.x,mouse.y,m_ath,m_atv);");
						$(".popup-inner#popup").css({left: x, top: y});
						$(".show-message-for-click").hide();
						e.preventDefault();
					}, 500);

					clicker.mousemove(function(event){
						if (event.pageX != oldX || event.pageY != oldY) {
						clearInterval(timeout);
						}
					});
				}
				return false;
			});

			$(document).mouseup(function(){
				if (allow) {
					clearInterval(timeout);
				}
				return false;
			});

		    //----- CLOSE
			$('[data-popup-close]').on('click', function(e)  {

			$('#text_div').hide();
			$('#Tooltip_div').hide();
			$('#modal_div').hide();
			$('#video_div').hide();
			$('#image_div').hide();
			$('#link_div').hide();
			$('#open-add-hot').hide();
			$(".show-message-for-click").show();
			$('#edit-remove-move').hide();
			$('#text_div_edit').hide();
			enableButton(['#edit_hotpost', '#move_hotspot', '#delete_hotpost'])
			disableButton(['#edit_text','#edit_Tooltip','#edit_modal','#edit_image','#edit_video','#edit_link'])
			enableButton(['#set_defaultView', '#add_hotpost'])
			enableButton(['#add_text','#add_Tooltip', '#add_Modal', '#add_image', '#add_video' ,'#add_link']);

			var targeted_popup_class = jQuery(this).attr('data-popup-close');
			$('[data-popup="' + targeted_popup_class + '"]').fadeOut(0);
			e.preventDefault();
			});
		});

		embedpano({
			swf: '<?php echo $tourUrl . '/tour.swf'; ?>',
			xml: '<?php echo $tourUrl . '/tour.xml?' . time(); ?>',
			target: "pano",
			html5: "prefer",
			passQueryParameters: true
		});

		var krpano = document.getElementById('krpanoSWFObject');
		krpano.call("autorotate.stop()")

		var add_hotpost = document.getElementById('add_hotpost');
		var hotspot_done = document.getElementById('add_text');
		var selectbox = document.getElementById('selectbox');
		var showlink = document.getElementById('show_link');

		var i = 0;
		var hotspots = {};
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
					jQuery(element).hide();
				})
			}
			else {
				jQuery(elements).attr('disabled', 'disabled');
				jQuery(elements).hide();
			}
		}

		function enableButton(elements) {
			if (jQuery.isArray(elements)) {
				jQuery.each(elements, function (index, element) {
					jQuery(element).removeAttr('disabled');
					jQuery(element).show();
				})
			}
			else {
				jQuery(element).removeAttr('disabled');
				jQuery(element).show();
			}
		}

		function getHotspotsCount() {
			return krpano.get('hotspot.count');
		}

		function addHotspot(currentHotspotData) {
			disableButton([ '#set_defaultView', '#add_hotpost']);
			$('#open-add-hot').show();
		}

		function addText(){
		// alert(1);
		// alert(uniqname);
		disableButton(['#add_Tooltip', '#add_Modal', '#add_image', '#add_video' ,'#add_link' ]);
			$("#text_div").show();

		}

		function addModal(){
		disableButton(['#add_Tooltip', '#add_text', '#add_image', '#add_video' ,'#add_link' ,'#savehotspots' ]);
			$("#modal_div").show();
		}

		function addTooltip(){
		disableButton(['#add_Modal', '#add_text', '#add_image', '#add_video' ,'#add_link' ,'#savehotspots' ]);
			$("#Tooltip_div").show();
		}

		function addImage(){
			disableButton([ '#add_text' , '#add_Modal', '#add_Tooltip', '#add_video' ,'#add_link' ,'#savehotspots' ]);
			$("#image_div").show();
		}

		function addVideo(){
			disableButton([ '#add_text' , '#add_Modal', '#add_Tooltip', '#add_image' ,'#add_link' ,'#savehotspots' ]);
			$("#video_div").show();
		}

		function addLink(){
			disableButton([ '#add_text' , '#add_Modal', '#add_Tooltip', '#add_image' ,'#add_video' ,'#savehotspots' ]);
			$("#selectbox option[value="+krpano.get("xml.scene")+"]").attr('disabled',true);
			$('#selectbox').selectpicker('render');
			$("#selectbox option[value="+krpano.get("xml.scene")+"]").attr('disabled',false);
			$("#link_div").show();
		}

		////edit

		function editText(){
			disableButton(['#edit_Tooltip', '#edit_modal', '#edit_image', '#edit_video' ,'#edit_link' ,'#saveEdit' ]);
			$("#text_div_edit").show();

		}

		function editTooltip(){
			disableButton(['#edit_text' , '#edit_modal',  '#edit_image', '#edit_video' ,'#edit_link' ,'#saveEdit' ]);
			$("#tooltip_div_edit").show();
		}

		function editModal(){
			disableButton(['#edit_text','#edit_Tooltip',  '#edit_image', '#edit_video' ,'#edit_link' ,'#saveEdit' ]);
			$("#modal_div_edit").show();
		}


		function editImage(){
			disableButton([ '#edit_text' , '#edit_modal', '#edit_Tooltip', '#edit_video' ,'#edit_link' ,'#saveEdit' ]);
			$("#image_div_edit").show();
		}

		function editVideo(){
			disableButton([ '#edit_text' , '#edit_modal', '#edit_Tooltip', '#edit_image' ,'#edit_link' ,'#saveEdit' ]);
			$("#video_div_edit").show();
		}

		function editScene(){
			disableButton([ '#edit_text' , '#edit_modal', '#edit_Tooltip', '#edit_image' ,'#edit_video' ,'#saveEdit' ]);
			$("#scene_div_edit").show();
		}


		function onclickCancel(){
			$("#text_div").hide();
			$("#modal_div").hide();
			$("#Tooltip_div").hide();
			$("#image_div").hide();
			$("#video_div").hide();
			$("#link_div").hide();
			enableButton(['#add_text','#add_Tooltip', '#add_Modal', '#add_image', '#add_video' ,'#add_link','#savehotspots']);
		}
		function saveEdit(){
			$( "[data-popup-close]" ).trigger( "click" );
		}

		function SaveHot(type){
			i += 1;
			uniqname = "spot_new_" + i;
			// krpano.call("screentosphere(mouse.x,mouse.y,m_ath,m_atv);");
			var scene_num = krpano.get('scene.count');
			var current_scene = krpano.get('xml.scene');
			var posX = krpano.get('m_ath');
			var posY = krpano.get('m_atv');
			// hotspots[uniqname] = r; //JSON.parse(r);
			krpano.call("addhotspot(" + uniqname + ");");
			if (typeof currentHotspotData == 'undefined'){
				currentHotspotData = {};
				currentHotspotData.ath = krpano.get('view.hlookat');
				currentHotspotData.atv = krpano.get('view.vlookat');
			}
			else // THIS HOTSPOT HAVE AADITIONAL DATA FROM HOTDPOT LIST
			{
				// if (currentHotspotData.hotspot_type == 'normal') {
				// 	krpano.call("set(hotspot[" + uniqname + "].linkedscene, " + currentHotspotData.linkedscene + ");");
				// }
				// if (currentHotspotData.hotspot_type == 'text') {
				// 	krpano.call("set(hotspot[" + uniqname + "].hotspot_text, " + currentHotspotData.hotspot_text + ");");
				// }
			}
			if(type == 'text'){
				var text_t = $("#text_t").val();
				var text_text = $("#text_text").val();
				krpano.call("set(hotspot[" + uniqname + "].hotspot_type, text);");
				krpano.call("set(hotspot[" + uniqname + "].hotspot_title, "+text_t+" ");
				krpano.call("set(hotspot[" + uniqname + "].hotspot_content, "+text_text+" ");
				krpano.call("set(hotspot[" + uniqname + "].url, assets/images/image.png);");
			}
			if(type == 'modal'){
				var modal_t = $("#modal_t").val();
				var modal_d = $("#modal_d").val();
				krpano.call("set(hotspot[" + uniqname + "].hotspot_type, modal);");
				krpano.call("set(hotspot[" + uniqname + "].modal_title, "+modal_t+" ");
				krpano.call("set(hotspot[" + uniqname + "].modal_content, "+modal_d+" ");
				krpano.call("set(hotspot[" + uniqname + "].url, assets/images/modal.png);");
			}
			if(type == 'tooltip'){
				var tooltip_t = $("#tooltip_t").val();
				var tooltip_d = $("#tooltip_d").val();
				krpano.call("set(hotspot[" + uniqname + "].hotspot_type, tooltip);");
				krpano.call("set(hotspot[" + uniqname + "].tooltip_title, "+tooltip_t+" ");
				krpano.call("set(hotspot[" + uniqname + "].tooltip_content, "+tooltip_d+" ");
				krpano.call("set(hotspot[" + uniqname + "].url, assets/images/tooltip.png);");
			}
			if(type == 'video'){
				var videourl = $("#video_url").val();
				krpano.call("set(hotspot[" + uniqname + "].hotspot_type, video);");
				krpano.call("set(hotspot[" + uniqname + "].video_url, "+videourl+" ");
				krpano.call("set(hotspot[" + uniqname + "].url, assets/images/video.png);");
			}

			if(type == 'image'){
				var imageurl = $("#image_url").val();
				krpano.call("set(hotspot[" + uniqname + "].hotspot_type, image);");
				krpano.call("set(hotspot[" + uniqname + "].image_url, "+imageurl+" ");
				krpano.call("set(hotspot[" + uniqname + "].url, assets/images/image.png);");
			}

			if(type == 'linkscene'){
				var scene = $("#selectbox").val();
				krpano.call("set(hotspot[" + uniqname + "].hotspot_type, link);");
				krpano.call("set(hotspot[" + uniqname + "].linkedscene, " + scene + ");");
				krpano.call("set(hotspot[" + uniqname + "].url, assets/images/hotspot.png);");
			}
			krpano.call("set(hotspot[" + uniqname + "].onclick,  js(showPopup(" + uniqname + ")););");
			krpano.call("set(hotspot[" + uniqname + "].onover,  js(isAllowAddHotspot(false)););");
			krpano.call("set(hotspot[" + uniqname + "].onout,  js(isAllowAddHotspot(true)););");
			krpano.call("set(hotspot[" + uniqname + "].ath, " + posX + ");");
			krpano.call("set(hotspot[" + uniqname + "].sceneName, " + current_scene + ");");
			krpano.call("set(hotspot[" + uniqname + "].atv, " + posY + ");");
			$("[data-popup-close]").trigger("click");
		}

		function editHotspot(){
		disableButton(['#move_hotspot', '#delete_hotpost','#edit_hotpost']);
			$('#text_div_edit').show();
			$('#tooltip_div_edit ').hide();
			$('#modal_div_edit ').hide();
			$('#video_div_edit').hide();
			$('#image_div_edit').hide();
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

		function deleteHotspot() {
		if (confirm("Are you  Sure? ") == true) {
				//krpano.call("set(hotspot[" + i + "].onclick, 'removehotspot(get(name));');");
				// krpano.call("removehotspot("+uniqname+"); js(hmv(get(hotspot[" + i + "]), get(xml.scene), " + i + ") );')");
				var current_scene = krpano.get('xml.scene');
			var hotspot_count = getHotspotsCount();
			for (var i = 0; i < hotspot_count; i++) {
				krpano.call("set(hotspot[" + i + "].ondown, 'draghotspot(); js(hmv(get(hotspot[" + i + "]), get(xml.scene), " + i + ") );')");
			}
				$("[data-popup-close]").trigger("click");
		}else{
				return false;
			}
		}

		function done_remove() {

			enableButton(['add_hotpost', '#remove_hotpost', '#moveHotspot', '#set_defaultView'])
			document.getElementById('done_remove').style.display = 'none';

			add_hotpost.disabled = false;
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
			// krpano.call("set(hotspot[" + uniqname + "].hotspot_type, text);");

			$('#input_text_dialog').show();
			$('#input_text_dialog #text_input_hotspot').val('');
		}

		function setHotSpotType_Nomal() {
			$('#choose_hotSpot_type_id').hide();
			// krpano.call("set(hotspot[" + uniqname + "].hotspot_type, normal);");
			$('#show_link').show();
		}

		function hotspot_add_text_from_input() {
			$('#input_text_dialog').hide();
		//	krpano.call("set(hotspot[" + uniqname + "].hotspot_text, " + $('#text_input_hotspot').val() + ");");
			hotspot_add_done();
		}

		function hotspot_add_done() {
			$('#input_text_dialog').hide();
			$('#show_link').hide();
			add_hotpost.disabled = false;

			enableButton(['#remove_hotpost', '#moveHotspot', '#set_defaultView']);
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

		function editHotspots() {
			krpano.call("screentosphere(mouse.x,mouse.y,m_ath,m_atv);");
			krpano.call("set(hotspot[" + uniqname + "].onclick,  js(showPopup()););");
		}

		function moveHotspot() {
			var current_scene = krpano.get('xml.scene');
			var hotspot_count = getHotspotsCount();
			for (var i = 0; i < hotspot_count; i++) {
				krpano.call("set(hotspot[" + i + "].ondown, 'draghotspot(); js(hmv(get(hotspot[" + i + "]), get(xml.scene), " + i + ") );')");
			}

			// krpano.call("screentosphere(mouse.x,mouse.y,m_ath,m_atv);");
			// krpano.call("set(hotspot[" + uniqname + "].ondown, draghotspot(););");
			disableButton(['#add_hotpost', '#remove_hotpost', '#set_defaultView'])
		}

		function showPopup( uniqn ) {
			// uniqname = uniqn;
			enableButton(['add_hotpost', '#remove_hotpost', '#set_defaultView'])
			$("#edit-remove-move").show();
			$('#text_div_edit').hide();
			$('#tooltip_div_edit ').hide();
			$('#modal_div_edit ').hide();
			$('#video_div_edit').hide();
			$('#image_div_edit').hide();
			$('#scene_div_edit').hide();

			// $('#edit-remove-move').css('position', 'fixed');
			// var hotspot_count = getHotspotsCount();
			// for (var i = 0; i < hotspot_count; i++) {
			// 	krpano.call("set(hotspot[" + i + "].ondown, '');");
		}

		// function moveHotspotDone() {
		// 	enableButton(['add_hotpost', '#remove_hotpost', '#set_defaultView'])
		// 	// var hotspot_count = getHotspotsCount();
		// 	// for (var i = 0; i < hotspot_count; i++) {
		// 	// 	krpano.call("set(hotspot[" + i + "].ondown, '');");
		// 	// }
		// 	$("#moveHotspot").show();
		// 	$("#moveHotspotDone").hide();
		// }

		/**
		 *
		 * @returns {boolean}
		 */
		function isReady() {
			if (
				add_hotpost.disabled == false
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
				thisAlias.hotspotList[sceneName] = {};
				var hotspot_count = thisAlias.kr.get('hotspot.count');

				for (var i = 0; i < hotspot_count; i++) {
					// if (/hotspot\.png/.test(thisAlias.kr.get('hotspot[' + i + '].url')) || /vtourskin_hotspot\.png/.test(thisAlias.kr.get('hotspot[' + i + '].url')) || /information\.png/.test(thisAlias.kr.get('hotspot[' + i + '].url'))) {
						thisAlias.hotspotList[sceneName][current_randome_val + current_vTour_hotspot_counter.toString()] = {
							'ath': thisAlias.kr.get('hotspot[' + i + '].ath'),
							'atv': thisAlias.kr.get('hotspot[' + i + '].atv'),
							'sceneName': thisAlias.kr.get('hotspot[' + i + '].sceneName'),
							'hotspot_type': thisAlias.kr.get('hotspot[' + i + '].hotspot_type'),
							'reRender': 'true'
						}
						// if (/vtourskin_hotspot\.png/.test(thisAlias.kr.get('hotspot[' + i + '].url')) || /information\.png/.test(thisAlias.kr.get('hotspot[' + i + '].url'))) {
							//hotspot which is aready in xml shouldnt re-render by js anymore, if not, doulicate hotspot will apperent.
							if (thisAlias.kr.get('hotspot[' + i + '].xreRender') == 'true') {
								thisAlias.hotspotList[sceneName][current_randome_val + current_vTour_hotspot_counter.toString()].reRender == 'true'
							}
							else{
								thisAlias.hotspotList[sceneName][current_randome_val + current_vTour_hotspot_counter.toString()].reRender = 'false';
							}
						// }

						if (thisAlias.kr.get('hotspot[' + i + '].hotspot_type') == 'text') {
							thisAlias.hotspotList[sceneName][current_randome_val + current_vTour_hotspot_counter.toString()].title = thisAlias.kr.get('hotspot[' + i + '].hotspot_title');
							thisAlias.hotspotList[sceneName][current_randome_val + current_vTour_hotspot_counter.toString()].content = thisAlias.kr.get('hotspot[' + i + '].hotspot_content');
						}
						if (thisAlias.kr.get('hotspot[' + i + '].hotspot_type') == 'modal') {
							thisAlias.hotspotList[sceneName][current_randome_val + current_vTour_hotspot_counter.toString()].title = thisAlias.kr.get('hotspot[' + i + '].modal_title');
							thisAlias.hotspotList[sceneName][current_randome_val + current_vTour_hotspot_counter.toString()].content = thisAlias.kr.get('hotspot[' + i + '].modal_content');
						}
						if (thisAlias.kr.get('hotspot[' + i + '].hotspot_type') == 'tooltip') {
							thisAlias.hotspotList[sceneName][current_randome_val + current_vTour_hotspot_counter.toString()].title = thisAlias.kr.get('hotspot[' + i + '].tooltip_title');
							thisAlias.hotspotList[sceneName][current_randome_val + current_vTour_hotspot_counter.toString()].content = thisAlias.kr.get('hotspot[' + i + '].tooltip_content');
						}
						if (thisAlias.kr.get('hotspot[' + i + '].hotspot_type') == 'video') {
							thisAlias.hotspotList[sceneName][current_randome_val + current_vTour_hotspot_counter.toString()].video_url = thisAlias.kr.get('hotspot[' + i + '].video_url');
						}
						if (thisAlias.kr.get('hotspot[' + i + '].hotspot_type') == 'image') {
							thisAlias.hotspotList[sceneName][current_randome_val + current_vTour_hotspot_counter.toString()].image_url = thisAlias.kr.get('hotspot[' + i + '].image_url');
						}
						if (thisAlias.kr.get('hotspot[' + i + '].hotspot_type') == 'link') {
							thisAlias.hotspotList[sceneName][current_randome_val + current_vTour_hotspot_counter.toString()].linkedscene = thisAlias.kr.get('hotspot[' + i + '].linkedscene');
						}
						current_vTour_hotspot_counter++;
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
<script type="text/javascript">
	$(document).ready(function() {
	$('.selectpicker').selectpicker();
	});
</script>
</body>
</html>
