/**
*
* @author: N3 S.r.l.
*/

$(setFreetureVisibility());
var inaffreeture = new FreetureModel('V2');
var lastEditId = '';
var indexToShow = null;
$(function(){
    disableForm(inaffreeture);
});

function setLastEditId(){
	lastEditId = inaffreeture.id;
}

function editObj(id){
	disableForm(inaffreeture,true);
	freetureLogic.get(inaffreeture,id);
	$('td').removeClass('lastEditedRow');
	lastEditId = '';
}

function allowEditObj(){
	enableForm(inaffreeture,false,["id","value","description","key"]);
}

function saveObj(){
	var f = function(){disableForm(inaffreeture);}
	freetureLogic.save(inaffreeture,setIndexToShow,setLastEditId, f, reloadAllDatatable);
}

function removeObj(){
	var f = function(){disableForm(inaffreeture);}
	freetureLogic.remove(inaffreeture,inaffreeture.id, safeDelete, f, reloadAllDatatable);
}

function newObj(){
	newForm(inaffreeture);
	freetureLogic.get(inaffreeture, null);
	$('td').removeClass('lastEditedRow');
	lastEditId = '';
}

function undoObj(){
	editObj(inaffreeture.id);
}

function setIndexToShow(){
	indexToShow = inaffreeture.id;
}

$(document).ready(function () {
    table = $('#FreetureList').DataTable({
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
        "columnDefs": [{
                "targets": [-2, -3],
                "orderable": false
            },
            {
                "targets": [-1],
                "visible": false
            },
            {
                "targets": [-2],
                //"className": 'dt-body-right',
                render: function(data, type, row, meta) {
                    var reply = data===1 ? "SI":"NO";
                    var color = data===1 ? "green":"red"; 
                    return '<span style="color:' 
                            + color
                            + '">' + reply 
                            + '</span>';
                }
            }],
        responsive: true,
        dom: 'lfrt<t>ip',
        "fnServerParams": function (aoData) {
            // Show page with passed index
            aoData.push({"name": "searchPageById", "value": indexToShow});
            if ($("." + $.md5('id')).is(":visible"))
                aoData.push({"name": "id", "value": $('#F_id').val()});
            if ($("." + $.md5('key')).is(":visible"))
                aoData.push({"name": "key", "value": $('#F_key').val()});
            if ($("." + $.md5('value')).is(":visible"))
                aoData.push({"name": "value", "value": $('#F_value').val()});
            if ($("." + $.md5('description')).is(":visible"))
                aoData.push({"name": "description", "value": $('#F_description').val()});
            if ($("." + $.md5('show')).is(":visible"))
                aoData.push({"name": "show", "value": $('#F_show').val()});
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
        sAjaxSource: '/lib/freeture/V2/freeture/datatable/list',
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
				url: '/lib/freeture/V2/freeture/autocomplete/' + $(this).attr('id').replace('F_',''),
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
				url: '/lib/freeture/V2/freeture/foreignkey/' + $(this).attr('id').replace('F_',''),
				dataType: 'json'
			},
			minimumInputLength: 0
		});
	});
}

