/**
*
* @author: N3 S.r.l.
*/

$(setCaptureVisibility());
var inafcapture = new CaptureModel('V2');
var lastEditId = '';
var indexToShow = null;
$(function(){
	disableForm(inafcapture);

});

function setLastEditId(){
	lastEditId = inafcapture.id;
}

function editObj(id){
	disableForm(inafcapture,true);
	captureLogic.get(inafcapture,id);
	$('td').removeClass('lastEditedRow');
	lastEditId = '';
}

function allowEditObj(){
	enableForm(inafcapture,false);
}

function saveObj(){
	var f = function(){disableForm(inafcapture);}
	captureLogic.save(inafcapture,setIndexToShow,setLastEditId, f, reloadAllDatatable);
}

function removeObj(){
	var f = function(){disableForm(inafcapture);}
	captureLogic.remove(inafcapture,inafcapture.id, safeDelete, f, reloadAllDatatable);
}

function newObj(){
	newForm(inafcapture);
	captureLogic.get(inafcapture, null);
	$('td').removeClass('lastEditedRow');
	lastEditId = '';
}

function undoObj(){
	var f = function(){
		editObj(inafcapture.id);
	};
	alertConfirm("Conferma", "Sei sicuro di voler annullare le modifiche? Le modifiche non salvate andranno perse", f);
}

function setIndexToShow(){
	indexToShow = inafcapture.id;
}

function preview(value) {
        $('#capture-preview-modal').modal('show');
        $('#capture-preview-modal-label').html("Calibrazione del " + getFileDate(value) + " (" + getFileHour(value) + ")");
        var body = '<img class="img-responsive" src="/lib/capture/V2/capture/preview/' + value + '"/>';
        $('#capture-preview-modal-body').html(body);
}

function getFileDate(file) {
    var info = file.split("_");
    return info[1].substring(0, 4) + "-" + info[1].substring(4, 6)  + "-" + info[1].substring(6, 8);
  
}

function getFileHour(file) {
    var info = file.split("_");
    return info[1].substring(9, 11) + ":" + info[1].substring(11, 13) + ":" + info[1].substring(13);
}

$(document).ready(function () {
        var groupColumn = 1;
        var collapsedGroups = {};
	table = $('#CaptureList').DataTable({
		"oLanguage": {
			"sZeroRecords": "Nessun risultato",
			"sSearch": "Cerca:",
			"oPaginate": {
				"sPrevious": "Indietro",
				"sNext": "Avanti"
			},
			"sInfo": "Mostra pagina _PAGE_ di _PAGES_",
			"sInfoFiltered": "",
			"sInfoEmpty": "Mostra pagina 0 di 0 elementi",
			"sEmptyTable": "Nessun risultato",
			"sLengthMenu": "Mostra _MENU_ elementi"
			},
                
                        
		columnDefs: [
                        {
				"targets": [-1, -2, -3, -4, -5],
				"orderable": false
			},
			{       "width": "5%",
                                "className": "dt-center",
                                "targets":  [-1, -2]
                        },
                        {
                                "targets": [-2],
                                render: function (data, type, row, meta) {
                                    var info = row[1].split(":");
                                    return "<center>" +
                                    "<button class='btn btn-success' value='" + data +"' onclick= 'preview(this.value)'><i class='fa fa-file'></i></button>" +
                                    "</center>";
                                }
                        },
                        {
                                "targets": [-1],
                                render: function (data, type, row, meta) {                                                                      
                                    return "<center>" + 
                                    "<a href='/lib/capture/V2/capture/download/" + data + "'>"+
                                    "<button class='btn btn-success'><i class='fa fa-download'></i></button>" +
                                    "</a>" +
                                    "</center>";
                                }
                        },
                        {       
                                "visible": false, 
                                "targets": groupColumn 
                        }
                    ],
            
		responsive: true,
		dom: 'lfrt<t>ip',
                
		"fnServerParams": function (aoData) {
			// Show page with passed index
			aoData.push({"name": "searchPageById", "value": indexToShow});
			if ($("." +$.md5('id')).is(":visible"))
				aoData.push({"name": "id", "value": $('#F_id').val()});
			if ($("." +$.md5('name')).is(":visible"))
				aoData.push({"name": "name", "value": $('#F_name').val()});
			if ($("." +$.md5('date')).is(":visible"))
				aoData.push({"name": "date", "value": $('#F_date').val()});
                        if ($("." +$.md5('hour')).is(":visible"))
                            aoData.push({"name": "hour", "value": $('#F_hour').val()});
		},
                rowGroup: {
                    startRender: function (rows, group) {
                        var info = group.split(":");
                        var collapsed = !!collapsedGroups[group];
                        rows.nodes().each(function (r) {
                            r.style.display = collapsed ? 'none' : '';
                        });  
                        return $('<tr class="group" style="background-color:#C6CAD4;">')
                            .append('<td colspan="3">'+ info[0] +'</td>')
                            .append('<td><center>'+ info[1] +'</center></td>')
                            .attr('data-name', group)
                            .toggleClass('collapsed', collapsed);
                    },
                    endRender: null,
                    dataSrc: groupColumn
                },
                
                "order": [[groupColumn, 'desc']],
                "iDisplayLength": 10,
                "iDisplayStart": 0,
                "pageLength": 10,
                "lengthMenu": [10, 25, 50],
		bProcessing: true,
		bServerSide: true,
		bStateSave: true,
		sAjaxSource: '/lib/capture/V2/capture/datatable/list',
                "paging": true,
                "ordering": true,
                "info": true,
                "searching": false
	});
        $('#CaptureList').on('click', 'tr.group', function () {
            var currentOrder = table.order()[0];
            if ( currentOrder[0] === groupColumn && currentOrder[1] === 'asc' ) {
                table.order([groupColumn, 'desc']).draw();
            }
            else {
                table.order([groupColumn, 'asc']).draw();
            }
        });
        $.get("/lib/capture/V2/capture/info/lastcapture", function(data) {
            $('#last-capture-description').html("Calibrazione del " + getFileDate(data) + " (" + getFileHour(data) + ")");
        });
        /*
        $('#CaptureList tbody').on('click', 'tr.group-start', function () {
        var name = $(this).data('name');
        collapsedGroups[name] = !collapsedGroups[name];
        table.draw(false);
    });  */
        
});

 $(function() {
initFilters();
});
	var setData = {
		singleDatePicker: true, opens: 'right',
		calender_style: "picker_2",
		format: 'DD/MM/YYYY'
	};
function initFilters() {
	$(".filter-text").each(function (index) {
		$(this).select2({
			language: 'it',
			maximumSelectionLength: 1,
			multiple: true,
			ajax: {
				url: '/lib/capture/V2/capture/autocomplete/' + $(this).attr('id').replace('F_',''),
				dataType: 'json'
			},
			minimumInputLength: 1
		});
	});
	$(".filter-date, .date").each(function (index) {
		$(this).daterangepicker(setData, function(){reloadAllDatatable();});
	});
	$(".foreign_key").each(function (index) {
		$(this).select2({
			language: 'it',
			maximumSelectionLength: 0,
			multiple: false,
			ajax: {
				url: '/lib/capture/V2/capture/foreignkey/' + $(this).attr('id').replace('F_',''),
				dataType: 'json'
			},
			minimumInputLength: 0
		});
	});
}



