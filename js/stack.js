/**
*
* @author: N3 S.r.l.
*/

$(setStackVisibility());
var inafstack = new StackModel('V2');
var lastEditId = '';
var indexToShow = null;
$(function(){
	disableForm(inafstack);

});

function setLastEditId(){
	lastEditId = inafstack.id;
}

function editObj(id){
	disableForm(inafstack,true);
	stackLogic.get(inafstack,id);
	$('td').removeClass('lastEditedRow');
	lastEditId = '';
}

function allowEditObj(){
	enableForm(inafstack,false);
}

function saveObj(){
	var f = function(){disableForm(inafstack);}
	stackLogic.save(inafstack,setIndexToShow,setLastEditId, f, reloadAllDatatable);
}

function removeObj(){
	var f = function(){disableForm(inafstack);}
	stackLogic.remove(inafstack,inafstack.id, safeDelete, f, reloadAllDatatable);
}

function newObj(){
	newForm(inafstack);
	stackLogic.get(inafstack, null);
	$('td').removeClass('lastEditedRow');
	lastEditId = '';
}

function undoObj(){
	var f = function(){
		editObj(inafstack.id);
	};
	alertConfirm("Conferma", "Sei sicuro di voler annullare le modifiche? Le modifiche non salvate andranno perse", f);
}

function setIndexToShow(){
	indexToShow = inafstack.id;
}

function preview(value) {
        $('#stack-preview-modal').modal('show');
        $('#stack-preview-modal-label').html("Stack del " + getFileDate(value) + " (" + getFileHour(value) + ")");
        var body = '<img class="img-responsive" src="/lib/stack/V2/stack/preview/' + value + '"/>';
        $('#stack-preview-modal-body').html(body);
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
	table = $('#StackList').DataTable({
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
                                    return "<center>" +
                                    "<button class='btn btn-success' id='stack-preview-" + data + "' value='" + data + "' onclick= 'preview(this.value)'><i class='fa fa-file'></i></button>" +
                                    "</center>";
                                }
                        },
                        {
                                "targets": [-1],
                                render: function (data, type, row, meta) {                                                                      
                                    return "<center>" + 
                                    "<a href='/lib/stack/V2/stack/download/" + data + "'>"+
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
                        return $('<tr class="group" style="background-color:#C6CAD4;">')
                            .append( '<td colspan="3">'+ info[0] +'</td>' )
                            .append( '<td><center>'+ info[1] +'</center></td>' )
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
		sAjaxSource: '/lib/stack/V2/stack/datatable/list',
                "paging": true,
                "ordering": true,
                "info": true,
                "searching": false
	});
        $('#StackList').on('click', 'tr.group', function () {
            var currentOrder = table.order()[0];
            if ( currentOrder[0] === groupColumn && currentOrder[1] === 'asc' ) {
                table.order([groupColumn, 'desc']).draw();
            }
            else {
                table.order([groupColumn, 'asc']).draw();
            }
        });
        
        $.get("/lib/stack/V2/stack/info/laststack", function(data) {
            $('#last-stack-description').html("Stack del " + getFileDate(data) + " (" + getFileHour(data) + ")");
        });
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
				url: '/lib/stack/V2/stack/autocomplete/' + $(this).attr('id').replace('F_',''),
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
				url: '/lib/stack/V2/stack/foreignkey/' + $(this).attr('id').replace('F_',''),
				dataType: 'json'
			},
			minimumInputLength: 0
		});
	});
}



