var _url = '';
var _map_theme = [
	{ "featureType": "water", "elementType": "geometry", "stylers": [ { "color": "#283593" } ] },
	{ "featureType": "landscape", "elementType": "geometry", "stylers": [ { "color": "#3f51b5" } ] },
	{ "featureType": "road", "elementType": "geometry", "stylers": [ { "color": "#0097a7" } ] },
	{ "featureType": "poi", "stylers": [ { "color": "#0097a7" } ] },
	{ "elementType": "labels.icon", "stylers": [ { "visibility": "off" } ] },
	{ "featureType": "transit", "stylers": [ { "color": "#00838f" } ] },
	{ "featureType": "administrative", "elementType": "geometry", "stylers": [ { "color": "#006064" } ] },
	{ "elementType": "labels.text.fill", "stylers": [ { "color": "#ffffff" } ] },
	{ "elementType": "labels.text.stroke", "stylers": [ { "color": "#263238" } ] },
/*

	{ "featureType": "water", "elementType": "geometry", "stylers": [ { "color": "#00838f" } ] },
	{ "featureType": "landscape", "elementType": "geometry", "stylers": [ { "color": "#00bcd4" } ] },
	{ "featureType": "road", "elementType": "geometry", "stylers": [ { "color": "#0097a7" } ] },
	{ "featureType": "poi", "stylers": [ { "color": "#4dd0e1" } ] },
	{ "elementType": "labels.icon", "stylers": [ { "visibility": "off" } ] },
	{ "featureType": "transit", "stylers": [ { "color": "#00838f" } ] },
	{ "featureType": "administrative", "elementType": "geometry", "stylers": [ { "color": "#006064" } ] },
	{ "elementType": "labels.text.fill", "stylers": [ { "color": "#ffffff" } ] },
	{ "elementType": "labels.text.stroke", "stylers": [ { "color": "#263238" } ] },

	{ "featureType": "water", "elementType": "geometry", "stylers": [ { "color": "#364352" } ] },
	{ "featureType": "landscape", "elementType": "geometry", "stylers": [ { "color": "#445366" } ] },
	{ "featureType": "road", "elementType": "geometry", "stylers": [ { "color": "#53657B" } ] },
	{ "featureType": "poi", "stylers": [ { "color": "#53657B" } ] },
	{ "elementType": "labels.icon", "stylers": [ { "visibility": "off" } ] },
	{ "featureType": "transit", "stylers": [ { "color": "#53657B" } ] },
	{ "featureType": "administrative", "elementType": "geometry", "stylers": [ { "color": "#53657B" } ] },
	{ "elementType": "labels.text.fill", "stylers": [ { "color": "#9AA9BA" } ] },
	{ "elementType": "labels.text.stroke", "stylers": [ { "color": "#364352" } ] },
*/	
];

$(document).ready(function(e) {
	Date.format = 'mm/dd/yyyy';

	// table sorters *************************************************************************************************

	if ($("#grid_table").length){
		if ($("#grid_pager").length){
			$("#grid_table").tablesorter(_tsOptions).tablesorterPager({container: $("#grid_pager"), positionFixed: false, size:20 });
		} else {
			$("#grid_table").tablesorter(_tsOptions);
		}
	}

	if ($(".grid_table").length){
		$(".grid_table").tablesorter(_tsOptions);
	}

	// confirm dialog *********************************************

	if ($("#confirm-btn-yes").length){
		$("#confirm-btn-yes").click(function(){
			window.location.href = _url;
		});
	}

	// popovers ***************************************************

	if ($('[data-toggle="popover"]').length){
		$('[data-toggle="popover"]').popover();


	}

	// chosen *************************************************************************************************
	if ($(".chosen-select").length){
		$(".chosen-select").chosen({search_contains: true});
	}

	// date pickers *************************************************************************************************
	if ($(".date-picker").length){
		$(".date-picker").datepicker({});
	}

	if ($(".datetime-picker").length){
		$(".datetime-picker").datetimepicker({});
	}

	if ($(".input-daterange").length){
		$(".input-daterange").datepicker({});
	}

	if ($(".datetime_range_start").length){
		if ($(".datetime_range_end").length){
			$(".datetime_range_start").datetimepicker({
				format: 'MM/DD/YYYY hh:mm a',
				widgetPositioning : {
					horizontal: 'auto',
					vertical: 'bottom'					
				}
			});

			$(".datetime_range_end").datetimepicker({
				format: 'MM/DD/YYYY hh:mm a',
				widgetPositioning : {
					horizontal: 'auto',
					vertical: 'bottom'					
				},
				useCurrent: false
			});

			$(".datetime_range_start").on('dp.change', function (e){
				$(".datetime_range_end").data('DateTimePicker').minDate(e.date);
			});

			$(".datetime_range_end").on('dp.change', function (e){
				$(".datetime_range_start").data('DateTimePicker').maxDate(e.date);
			});
		}
	}

	// user groups check all
	
	if ($('#chk_all_1').length){
		$('#chk_all_1').click(function (){
			var _checked = this.checked;
			$('.chk1').each(function (){
				this.checked = _checked;
			});
		});

		$('.chk1').click(function (){
			var _checked = this.checked;
			$('.chk1').each(function (){
				_checked &= this.checked;
			});
			$('#chk_all_1').prop('checked', _checked);
		});
	}

	if ($('#chk_all_2').length){
		$('#chk_all_2').click(function (){
			var _checked = this.checked;
			$('.chk2').each(function (){
				this.checked = _checked;
			});
		});

		$('.chk2').click(function (){
			var _checked = this.checked;
			$('.chk2').each(function (){
				_checked &= this.checked;
			});
			$('#chk_all_2').prop('checked', _checked);
		});
	}

	if ($('#chk_all_3').length){
		$('#chk_all_3').click(function (){
			var _checked = this.checked;
			$('.chk3').each(function (){
				this.checked = _checked;
			});
		});

		$('.chk3').click(function (){
			var _checked = this.checked;
			$('.chk3').each(function (){
				_checked &= this.checked;
			});
			$('#chk_all_3').prop('checked', _checked);
		});
	}

	if ($('#chk_all_4').length){
		$('#chk_all_4').click(function (){
			var _checked = this.checked;
			$('.chk4').each(function (){
				this.checked = _checked;
			});
		});

		$('.chk4').click(function (){
			var _checked = this.checked;
			$('.chk4').each(function (){
				_checked &= this.checked;
			});
			$('#chk_all_4').prop('checked', _checked);
		});
	}
	

	// attendance *************************************************************************************************
	if ($('.grid-menuitem-checkall').length){
		$('.grid-menuitem-checkall').click(function (){
			checkAllInGrid();
		});
	}

	if ($('.grid-menuitem-uncheckall').length){
		$('.grid-menuitem-uncheckall').click(function (){
			uncheckAllInGrid();
		});
	}

	// Project Location *************************************************************************************************************

	if ($('.province_select').length){
		$('.province_select').change(function (){
			var _s = 0;
			$(".province_select option:selected").each(function(){
				_s = $(this).val();
			});
			load_cities(_s);
		});
	}

	if ($('.city_select').length){
		$('.city_select').change(function (){
			var _s = 0;
			$(".city_select option:selected").each(function(){
				_s = $(this).val();
			});
			//alert(_s);
			load_barangays(_s);
		});
	}

	// summary graphs *************************************************************************************************************

	if ($('#province-graph').length){
		//alert('called');
		var ctx = $('#province-graph');
		var chart = new Chart(ctx, province_graph);
	}

	if ($('#status-graph').length){
		//alert('called');
		var ctx = $('#status-graph');
		var chart = new Chart(ctx, status_graph);
	}

	if ($('#year-approved-graph').length){
		var ctx = $('#year-approved-graph');
		var chart = new Chart(ctx, year_approved_graph);
	}

	if ($('#sector-graph').length){
		var ctx = $('#sector-graph');
		var chart = new Chart(ctx, sector_graph);
	}

	if ($('#project-type-graph').length){
		var ctx = $('#project-type-graph');
		var chart = new Chart(ctx, project_type_graph);
	}

	if ($('#repayment-province-graph').length){
		var ctx = $('#repayment-province-graph');
		var chart = new Chart(ctx, rep_province_graph);
	}


	// validation *************************************************************************************************************
	/*
	if ($("#edit-form").length){
		$("#edit-form").submit(function() {}).validate(_rules);
	}
	*/

	// datepicker in chuinky *************************************************************************************************
	/*
	if ( ($("#from_date_time").length) && ($("#to_date_time").length)){
		$("#from_date_time, #to_date_time").datePicker({ clickInput:true, startDate:'01/01/1970' });
	}
	*/

	/*
	if ($('.grid-menuitem-markattended').length){
		$('.grid-menuitem-markattended').click(function (){
			res = getAllChecked();
			//if (res.length == 0) return;
			if ($('#edit-modal').length){
				$('#edit-modal').modal('show');
			}
		});
	}


	*/

	// google maps
	if ($('#map').length){

		var pin_rollout = {
			url: 'images/pin_rollout.png',
			size: new google.maps.Size(31, 28),
			origin: new google.maps.Point(0, 0),
			anchor: new google.maps.Point(15, 27)
		};		

		var pin_setup = {
			url: 'images/pin_setup.png',
			size: new google.maps.Size(31, 28),
			origin: new google.maps.Point(0, 0),
			anchor: new google.maps.Point(15, 27)
		};

		var pin_tapi = {
			url: 'images/pin_tapi.png',
			size: new google.maps.Size(31, 28),
			origin: new google.maps.Point(0, 0),
			anchor: new google.maps.Point(15, 27)
		};

		var pin_gia_cbp = {
			url: 'images/pin_gia_cbp.png',
			size: new google.maps.Size(31, 28),
			origin: new google.maps.Point(0, 0),
			anchor: new google.maps.Point(15, 27)
		};		

		var pin_gia_int = {
			url: 'images/pin_gia_int.png',
			size: new google.maps.Size(31, 28),
			origin: new google.maps.Point(0, 0),
			anchor: new google.maps.Point(15, 27)
		};		

		var pin_gia_ext = {
			url: 'images/pin_gia_ext.png',
			size: new google.maps.Size(31, 28),
			origin: new google.maps.Point(0, 0),
			anchor: new google.maps.Point(15, 27)
		};		


		function getMapIcon(itype){

			if (itype == 8) { 
				return pin_rollout ;
			} else if (itype == 6){ 
				return pin_setup;
			} else if (itype == 9){ 
				return pin_tapi;
			} else if (itype == 12){ 
				return pin_gia_cbp;
			} else if (itype == 13){ 
				return pin_gia_ext;
			} else {
				return pin_gia_int;
			}
		}


		var myMapStyler = new google.maps.StyledMapType(_map_theme, {name: 'Grey World'});

		var myMapStylerId = 'Grey World';

		var map = new google.maps.Map(document.getElementById('map'), {
			zoom: 11,
			minZoom: 6,
			center: new google.maps.LatLng(_latitude, _longitude),
			/* mapTypeControlOptions: {
				mapTypeIds: [google.maps.MapTypeId.ROADMAP, myMapStylerId]
			}, */
			mapTypeControl: false,
			/*
			zoomControl: false,
			zoomControlOptions: {
				position: google.maps.ControlPosition.LEFT_CENTER
			},
			*/
			streetViewControl: false,
		});

		/*map.mapTypes.set(myMapStylerId, myMapStyler);
		map.setMapTypeId(myMapStylerId);
		*/

		var infowindow = new google.maps.InfoWindow();
		var marker, i;
		var bounds = new google.maps.LatLngBounds();
		var _position;


		for (i = 0; i < _locations.length; i++) {
			_position = new google.maps.LatLng(_locations[i][1], _locations[i][2]);
			marker = new google.maps.Marker({
				position: _position,
				map: map,
				icon: getMapIcon(_locations[i][4]),
				labelClass: 'map_marker',
			});

			if (_locations[i][1] + _locations[i][2] > 0){
				bounds.extend(_position);
			}
			
			google.maps.event.addListener(marker, 'mouseover', (function(marker, i) {
				return function() {
					infowindow.setContent(_locations[i][0]);
					infowindow.open(map, marker);
				}
			})(marker, i));
			

			google.maps.event.addListener(marker, 'click', (function(marker, i) {
				return function() {
					if (i > _max_points) return;
					loadProjectInfo(i);
				}
			})(marker, i));
		}

		if (_locations.length > 0) {
			//map.fitBounds(bounds);
		}
	}


	if ($("#map-location-picker").length){
		var _latlang = {lat: _latitude, lng: _longitude};

		var map_picker = new google.maps.Map(document.getElementById('map-location-picker') , {
			zoom: 17,
			minZoom: 6,
			center: _latlang,
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			mapTypeControl: false,
			zoomControl: false,
			streetViewControl: false,
		});

		var _marker = new google.maps.Marker({
			position: _latlang,
			map: map_picker,
			icon: 'images/icon_projects.png'
		});

		google.maps.event.addListener(map_picker, 'click', function(event){
			_marker.setPosition(event.latLng);
			$("#longitude").val(event.latLng.lng());
			$("#latitude").val(event.latLng.lat());
		});

		/*
		google.maps.event.addListener(map_picker, 'mousemove', function(event){
			$("#longitude").val(event.latLng.lng());
			$("#latitude").val(event.latLng.lat());
		});
		*/

	}

	if ($("#map-sites").length){
		var _latlang = {lat: _latitude, lng: _longitude};

		var map_picker = new google.maps.Map(document.getElementById('map-sites') , {
			zoom: 17,
			minZoom: 6,
			center: _latlang,
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			mapTypeControl: false,
			streetViewControl: false,

		});

		var infowindow = new google.maps.InfoWindow();
		var marker, i;
		var _position;

		for (i = 0; i < _locations.length; i++) {
			// document.write(i);
			_position = new google.maps.LatLng(_locations[i][2], _locations[i][3]);
			marker = new google.maps.Marker({
				position: _position,
				map: map,
				icon: 'images/icon_projects.png',
				labelAnchor: new google.maps.Point(0, 0),
				labelClass: 'map_marker',

			});

			google.maps.event.addListener(marker, 'mouseover', (function(marker, i) {
				return function() {
					infowindow.setContent(_locations[i][0]);
					infowindow.open(map, marker);
				}
			})(marker, i));

		}

	}

	// delete dialog *********************************************

	if ($("#btn-delete-yes").length){
		$("#btn-delete-yes").click(function(){
			window.location.href = _url;
		});
	}

	if ($("#print-list-btn").length){
		$("#print-list-btn").click(function(){
			if ($("#print-form").length){
				$("#print-form").submit();
			}
		});
	}

	if ($("#download-list-btn").length){
		$("#download-list-btn").click(function(){
			if ($("#download-form").length){
				$("#download-form").submit();
			}
		});
	}

	if ($("#print-page-btn").length){
		$("#print-page-btn").click(function(){
			window.print();
			return false;
		});
	}

	// Filter Panel ************************************************

	if ($(".map-filter-panel").length){
		if ($(".filter-panel-btn-close").length){
			$(".filter-panel-btn-close").click(function (){
				$(".map-filter-panel").width(0);
				$("#page-wrapper").css("margin-left", "0px");
			});
		}

		if ($(".filter-panel-btn-open").length){
			$(".filter-panel-btn-open").click(function (){
				$(".map-filter-panel").width(280);
				$("#page-wrapper").css("margin-left", "280px");
			});
		}
	}
	

// Autorun ********************************

	if ($(".map-filter-panel").length){
		$(".map-filter-panel").width(0);
	}


});

function confirmDelete(url){
	_url = url;
	$('#delete-modal').modal('show');
}


function confirmDialog(msg, title, url){
	_url = url;

	if ($('#confirm-modal-title').length){
		$('#confirm-modal-title').html(title);
	}

	if ($('#confirm-modal-message').length){
		$('#confirm-modal-message').html(msg);
	}

	if ($('#confirm-modal').length){
		$('#confirm-modal').modal('show');
	}
}

function showProjectInfo(title, url){
	_url = url;
	msg = '<img src="images/progress.gif" al="Loading...">';
	if ($('#info-modal-title').length){
		$('#info-modal-title').html(title);
	}

	if ($('#info-modal-message').length){
		$('#info-modal-message').html(msg);
	}

	if ($('#info-modal').length){
		$('#info-modal').modal('show');
	}

	$.get(url, function(data){
		if ($('#info-modal-message').length){
			$('#info-modal-message').html(data);
		}
	});
}

function load_cities(province){
	if ($('.city_select').length == 0) return;
	var _items = $('.city_select');
	_items.empty();

	var _cid = 0;
	$.each(_cities, function (index, item){
		if (item.pid == province){
			if (_cid == 0) _cid = item.cid;
			_items.append(
				$('<option>',{
					value: item.cid,
					text : item.name},
					'</option>'
					))
		}

	});
	load_barangays(_cid);
}

function load_barangays(city){
	if ($('.barangay_select').length == 0) return;
	var _items = $('.barangay_select');
	_items.empty();

	$.each(_barangays, function (index, item){
		if ((item.cid == 0) || (item.cid == city)){
			_items.append(
				$('<option>',{
					value: item.bid,
					text : item.name},
					'</option>'
					))
		}

	});

}



