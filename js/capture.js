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

function restart(value) {
        console.log(value);
}

function stop(value) {
        console.log(value);
}

$(document).ready(function () {
        var groupColumn = 1;
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
				"targets": [-3, -4],
				"orderable": true
			},
                        {
				"targets": [-1, -2],
				"orderable": false
			},
			{       "width": "5%",
                                "className": "dt-center",
                                "targets":  [-1, -2]
                        },
                        {
                                "targets": [-2],
                                render: function (data, type, row, meta) {
                                    return "<center>" +
                                    "<i class='fa fa-file'></i>" +
                                    "</center>";
                                }
                        },
                        {
                                "targets": [-1],
                                render: function (data, type, row, meta) {                                                                      
                                    return "<center>" +
                                    "<i class='fa fa-download'></i>" +
                                    "</center>";
                                }
                        },
                        {       "visible": false, 
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
				aoData.push({"name": "image", "value": $('#F_image').val()});
		},
                /*
		"fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
			if (aData[aData.length - 1] == lastEditId) {
				$('td', nRow).addClass('lastEditedRow');
			}
		},
		"fnDrawCallback": function (settings, json) {
			// Show page with passed index
                        
			indexToShow = null;
				setTimeout(function () {
				if (settings.json.pageToShow !== null) {
					if ($('.dataTable').DataTable().page.info().page !== settings.json.pageToShow) {
						$('.dataTable').DataTable().page(settings.json.pageToShow).draw('page');
					}
				}
			}, 100);
		},*/
                "drawCallback": function (settings) {
                    var api = this.api();
                    var rows = api.rows({page:'current'}).nodes();
                    var last=null;

                    api.column(groupColumn, {page:'current'}).data().each(function (group, i) {
                        if (last !== group) {
                            $(rows).eq(i).before(
                                '<tr class="group" style="background-color:#C6CAD4;"><td colspan="4">'+group+'</td></tr>'
                            );
                            last = group;
                        }
                    } );
                },
                "order": [[groupColumn, 'asc']],
                "iDisplayLength": 25,
                "iDisplayStart": 0,
                "pageLength": 25,
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
        } );
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



