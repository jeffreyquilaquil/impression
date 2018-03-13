var _url = '';

$(document).ready(function(e) {

	// table sorters ***********************************
	if ($("#grid_table").length){
		if ($("#grid_pager").length){
    		$("#grid_table").tablesorter(_tsOptions).tablesorterPager({container: $("#grid_pager"), positionFixed: false});
    	} else {
    		$("#grid_table").tablesorter(_tsOptions);
    	}
	}

	if ($(".grid_table").length){
   		$(".grid_table").tablesorter(_tsOptions);
	}

	// chosen ***********************************
	if ($(".chosen-select").length){
		$(".chosen-select").chosen();
	}

	// date pickers ***********************************
	if ($(".date-picker").length){
		$(".date-picker").datepicker({});
	}

	if ($(".input-daterange").length){
		$(".input-daterange").datepicker({});
	}


	// attendance ***********************************
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

});

// delete dialog *********************************************

$("#btn-delete-yes").click(function(){
	window.location.href = _url;
});

function confirmDelete(url){
	_url = url;
	$('#delete-modal').modal('show');
}

// confirm dialog *********************************************

$("#btn-confirm-yes").click(function(){
	window.location.href = _url;
});

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

// attendance *********************************************

function checkAllInGrid(){
	if ($('.grid-checkbox').length){
		$('.grid-checkbox').prop('checked', true);
	}
}

function uncheckAllInGrid(){
	if ($('.grid-checkbox').length){
		$('.grid-checkbox').prop('checked', false);
	}
}

function getAllChecked(){
	if (!$('.grid-checkbox').length) return [];

	res = [];
	rows = $('.grid-checkbox');
	len = rows.length;

	for (i = 0; i < len; i++){
		res[i] = rows[i].value;
	}
	return res;
}

function submitAttandanceSelected(){
	if ($('#attendance-form').length){
		$('#attendance-form').submit();
	}
}
