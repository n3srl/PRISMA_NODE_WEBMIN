/**
*
* @author: N3 S.r.l.
*/

$(setDockerVisibility());
var inafdocker = new DockerModel('V2');
var lastEditId = '';
var indexToShow = null;
$(function(){
	disableForm(inafdocker);

});

function setLastEditId(){
	lastEditId = inafdocker.id;
}

function editObj(id){
	disableForm(inafdocker,true);
	dockerLogic.get(inafdocker,id);
	$('td').removeClass('lastEditedRow');
	lastEditId = '';
}

function allowEditObj(){
	enableForm(inafdocker,false,["name","image","status"]);
}

function saveObj(){
	var f = function(){disableForm(inafdocker);}
	dockerLogic.save(inafdocker,setIndexToShow,setLastEditId, f, reloadAllDatatable);
}

function removeObj(){
	var f = function(){disableForm(inafdocker);}
	dockerLogic.remove(inafdocker,inafdocker.id, safeDelete, f, reloadAllDatatable);
}

function newObj(){
	newForm(inafdocker);
	dockerLogic.get(inafdocker, null);
	$('td').removeClass('lastEditedRow');
	lastEditId = '';
}

function undoObj(){
	var f = function(){
		editObj(inafdocker.id);
	};
	alertConfirm("Conferma", "Sei sicuro di voler annullare le modifiche? Le modifiche non salvate andranno perse", f);
}

function setIndexToShow(){
	indexToShow = inafdocker.id;
}

function restart(value) {
        $.ajax({
               url: "/lib/docker/V2/docker/restart/" + value,
               type: "POST",
               success: function (res) {
                   if(JSON.parse(res).result){
                       defaultSuccess("Container riavviato correttamente");
                       reloadAllDatatable();
                   }else{
                       defaultError();
                   }
               },
               error: function (XMLHttpRequest, textStatus, errorThrown) {
                   defaultError();
               }

           });
}

function stop(value) {
         $.ajax({
               url: "/lib/docker/V2/docker/stop/" + value,
               type: "POST",
               success: function (res) {
                   if(JSON.parse(res).result){
                       defaultSuccess("Container fermato correttamente");
                       reloadAllDatatable();
                   }else{
                       defaultError();
                   }
               },
               error: function (XMLHttpRequest, textStatus, errorThrown) {
                   defaultError();
               }

           });
}

$(document).ready(function () {
	table = $('#DockerList').DataTable({
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
                        
		columnDefs: [{
                            "defaultContent": "-",
                            "targets": "_all"
                        },
                        {
				"targets": [-4],
				"orderable": false
			},
			/*{
				"targets": [3, 4, 5],
				"visible": false
			},*/
                        {
                                "targets": [-3],
                                //"className": 'dt-body-right',
                                render: function (data, type, row, meta) {
                                    var color;
                                    if(data === "Up"){
                                        color = "green";
                                    }else if(data === "Restarting"){
                                        color = "orange";
                                    }else{
                                        color = "black";
                                    }

                                    return '<div style="color:'
                                            + color
                                            + '">' + data
                                            + '</div>';
                                }
                        },
                        {
                                "targets": [-1],
                                //"className": 'dt-body-right',
                                render: function (data, type, row, meta) {  
                                    return "<div>"+
                                    "<button type = 'button' style= 'margin-right: 10px;' value='" + data + "' id= 'btn-restart-" + data + "' onclick= 'restart(this.value)' class='btn btn-success' >Restart</button>" +
                                    "<button type = 'button' style= 'margin-right: 10px;' value='" + data + "' id= 'btn-stop-" + data + "' onclick= 'stop(this.value)' class='btn btn-danger' >Stop</button>" +
                                    "</div>";
                                }
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
			if ($("." +$.md5('image')).is(":visible"))
				aoData.push({"name": "image", "value": $('#F_image').val()});
			if ($("." +$.md5('command')).is(":visible"))
				aoData.push({"name": "command", "value": $('#F_command').val()});
			if ($("." +$.md5('status')).is(":visible"))
				aoData.push({"name": "status", "value": $('#F_status').val()});
			if ($("." +$.md5('created')).is(":visible"))
				aoData.push({"name": "created", "value": $('#F_created').val()});
                        if ($("." +$.md5('actions')).is(":visible"))
				aoData.push({"name": "actions", "value": $('#F_actions').val()});
		},
                
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
		},
		bProcessing: true,
		bServerSide: true,
		bStateSave: true,
		sAjaxSource: '/lib/docker/V2/docker/datatable/list',
                "paging": false,
        "ordering": false,
        "info": false,
        "searching": false
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
				url: '/lib/docker/V2/docker/autocomplete/' + $(this).attr('id').replace('F_',''),
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
				url: '/lib/docker/V2/docker/foreignkey/' + $(this).attr('id').replace('F_',''),
				dataType: 'json'
			},
			minimumInputLength: 0
		});
	});
}

