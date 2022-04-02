validator.defaults.alerts = true;
validator.message.empty = "Campo obbligatorio";
validator.message.select = "Campo obbligatorio";
validator.message.number_min = "Troppo basso";
validator.message.number_max = "Troppo alto";
validator.message.password_repeat = "Le password non corrispondono"

$('form')
        .on('blur', 'input[required], input.optional, select.required', validator.checkField)
        .on('change', 'select.required', validator.checkField)
        .on('keypress', 'input[required][pattern]', validator.keypress);

$('.multi.required').on('keyup blur', 'input', function () {
    validator.checkField.apply($(this).siblings().last()[0]);
});

$('form').not('.file-upload').submit(function (e) {
    e.preventDefault();
    var submit = true;

    if (!validator.checkAll($(this))) {
        submit = false;
        console.log(validator);
    }
    if (submit) {

        if ($(this).attr("callback") !== undefined) {
            window[$(this).attr("callback")]();
        } else {
            saveObj();
        }


    }



    return false;
});
/*******************************/
/*    Manage previuous page    */
/*******************************/
$(document).ready(function () {
    var currentPage = window.location.href;
    var currentPageNotID = window.location.href;
    if (currentPage.indexOf("edit") >= 0) {
        currentPageNotID = currentPage.replace("/" + ObjID, "");
    }
    if (Cookies.get("current_page") != currentPage && Cookies.get("current_page") != currentPageNotID) {
        Cookies.set("prev_page", Cookies.get("current_page"));
        Cookies.set("current_page", window.location.href);
    }

});

var table = null;
$('table tbody').on('click', '*:not(tr, td)', function (e) {
    checkGoToTarget(this);
    e.stopPropagation();
});
$('table:not(.noclick) tbody ').on('click', 'tr', function (e) {
    e.stopPropagation();
    var tableFind = table;
    var formName = "";
    if (Array.isArray(table)) {
        tableFind = table.find(x => x.tableId == $(this).closest("table").attr("id")).table;
    }

    if ($(this).closest("table").attr("callbackForm") !== undefined) {
        formName = "[name='" + $(this).closest("table").attr("callbackForm") + "'] ";
    }


    if ($(this).hasClass('selected')) {
        if ($(this).closest("table").attr("callback") !== undefined) {
            window[$(this).closest("table").attr("callback")]();
        } else {
            editObj();
        }
        hideAllForm(formName);
        $(this).removeClass('selected');
    } else {
        var d = tableFind.row(this).data();
        if ($(this).closest("table").attr("callback") !== undefined) {
            window[$(this).closest("table").attr("callback")](d[d.length - 1]);
        } else {
            editObj(d[d.length - 1]);
        }

        tableFind.$('tr.selected').removeClass('selected');
        $(this).addClass('selected');

        // aggiungo, clicco e rimuovo href perchè lo scroll direttamente da jquery fa conflitto con la datatable serverside
        $("body").append("<a href='#edit' id='tempA'></a> ");
        $("#tempA").click();
        $("#tempA").remove();
    }
});


/*******************************/
/*        SMOOTH SCROLL        */
/*******************************/
function checkGoToTarget(this_){
    if(!$(this_).prop('a, a *') && !(this_.tagName != undefined && this_.tagName.toLowerCase() == "a") && !$(this_).parent("a").length >= 1 )
        return;
    if (
            location.pathname.replace(/^\//, '') == this_.pathname.replace(/^\//, '')
            &&
            location.hostname == this_.hostname
            ) {
        // Figure out element to scroll to
        var target = $(this_.hash);
        //target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
        goToTarget(target);

    }
    
}
$(document).on("click", 'a[href*="#"]', function (event) {
    checkGoToTarget(this);

});
function goToTarget(target) {
    target = target.length ? target : "";
    // Does a scroll target exist?
    if (target.length) {
        // Only prevent default if animation is actually gonna happen
        event.preventDefault();
        $('html, body').animate({
            scrollTop: target.offset().top
        }, 300, function () {
            // Callback after animation
            // Must change focus!
            var $target = $(target);
            $target.focus();
            if ($target.is(":focus")) { // Checking if the target was focused
                return false;
            } else {
                $target.attr('tabindex', '-1'); // Adding tabindex for elements not focusable
                $target.focus(); // Set focus again
            }
            ;
        });
    }
}

/*******************************/
/*        FORM FILTERS         */
/*******************************/

$(function () {
    showFilters();
    getFilter();
    showHiddenDropdowns();
});
function saveFilter(elem) {
    var filterArray = [];
    var elemType = $(elem).prop('nodeName');
    if (elemType == "SELECT") {
        elem.find("option:selected").each(function (key, value) {
            var valueArray = [];
            valueArray = [$(value).val(), $(value).text()];
            filterArray.push(valueArray);
        });
    }
    if (elemType == "INPUT") {
        filterArray.push($(elem).val());
    }
    // save filter into cookie

    let currentPage = window.location.href;
    let filter = elem.attr('id');
    let value = btoa(JSON.stringify(filterArray));

    Cookies.set(currentPage + filter, value);
}

function getFilter() {
    $(".filter").each(function () {
        var elemType = $(this).prop('nodeName');

        // get filter from cookie
        let currentPage = window.location.href;
        let filter = $(this).attr('id');
        if (Cookies.get(currentPage + filter)) {
            let values = JSON.parse(atob(Cookies.get(currentPage + filter)));
            if (values !== null && values.length > 0) {
                for (const s of values) {

                    if (elemType == "SELECT") {
                        var found = false;
                        $(this).find("option").each(function ()
                        {
                            var optVal = $(this).val();
                            if (optVal == s[0]) {
                                found = true;
                                $(this).prop('selected', true);
                            }
                        });
                        if (!found) {
                            var newState = new Option(s[1], s[0], true, true);
                            $(this).append(newState).trigger("change");
                        }
                    }
                    if (elemType == "INPUT") {
                        $(this).val(s);
                    }
                }
            }
        }
    });
}


$(".applyFilter").click(function () {
    $(".filter").each(function () {
        saveFilter($(this));
    });

    reloadAllDatatable();

});
$(".clearFilter").click(function () {
    $(".filter").each(function () {
        $(this).val("").trigger("change");
        saveFilter($(this));
    });

    reloadAllDatatable();

});
function showFilters() {

    if ($('.filter-content').css("display") == "none") {
        var show = setInterval(function () {
            if ($('.filter-content').is(':visible')) {
                initFilters();
                clearInterval(show);
            }
        }, 100);
    }
}
function showHiddenDropdowns() {

    var show = setInterval(function () {
        if ($('.hidden-dropdown').is(':visible')) {
            initHiddenDropdown();
            clearInterval(show);
        }
    }, 100);
}


/*******************************/
/*        FORM FILTERS         */
/*******************************/

function reloadTable(tableIdentifier) {

    $(tableIdentifier).dataTable().fnDraw(false);

}

function reloadAllDatatable() {
    $('.dataTable').each(function (index) {
        $(this).dataTable().fnDraw(false);
    });


}
function defaultSuccess(message = "Operazione avvenuta correttamente") {
    alertSuccess("Successo", message);
}
function defaultError(message = "Si è verificato un errore imprevisto") {
    alertError("Attenzione", message);
}

function goToPreviousPage() {
    window.location = Cookies.get("prev_page");
    return false;
}

function hideAllForm(formName = "") {

    $(formName + "#savebtn").hide();
    $(formName + "#deletebtn").hide();
    $(formName + "#cleanbtn").hide();
    $(formName + "#modifybtn").hide();
    $(formName + "#undobtn").hide();
}
function newForm(objClass, editButton = false) {
    var formSelector = "";
    if (objClass.form_name != undefined) {
        formSelector = "[name='" + objClass.form_name + "'] ";
    }

    hideAllForm(formSelector);

    $(formSelector + ".custom-enable-on-new").removeClass("input-disabled");
    $(formSelector + ".custom-enable-on-new").addClass("input-enabled");
    $.each(objClass, function (index, value) {
        $(formSelector + "[name=" + index + "]").addClass("input-enabled");
        $(formSelector + "[name=" + index + "]").removeClass("input-disabled");
        $(formSelector + "[name=" + index + "]").prop('disabled', false);
    });
    $(formSelector + "#savebtn").show();
    $(formSelector + "#deletebtn").hide();
    if (editButton) {
        $(formSelector + "#cleanbtn").show();
        $(formSelector + "#modifybtn").hide();
}


}
function enableForm(objClass, editButton = false, exeptions = []) {
    var formSelector = "";
    if (objClass.form_name != undefined) {
        formSelector = "[name='" + objClass.form_name + "'] ";
    }
    hideAllForm(formSelector);
    $.each(objClass, function (index, value) {
        if(!exeptions.includes(index)){
            $(formSelector + "[name=" + index + "]").addClass("input-enabled");
            $(formSelector + "[name=" + index + "]").removeClass("input-disabled");
            $(formSelector + "[name=" + index + "]").prop('disabled', false);
        }
    });
    $(formSelector + "#savebtn").show();
    $(formSelector + "#deletebtn").show();
    $(formSelector + "#cleanbtn").show();
    $(formSelector + "#undobtn").show();
    if (editButton) {
        $(formSelector + "#modifybtn").show();
}
}

function disableForm(objClass, editButton = false) {
    var formSelector = "";
    if (objClass.form_name != undefined) {
        formSelector = "[name='" + objClass.form_name + "'] ";
    }
    hideAllForm(formSelector);
    $(formSelector + ".custom-enable-on-new").removeClass("input-enabled");
    $(formSelector + ".custom-enable-on-new").addClass("input-disabled");
    $.each(objClass, function (index, value) {
        $(formSelector + "[name=" + index + "]").addClass("input-disabled");
        $(formSelector + "[name=" + index + "]").removeClass("input-enabled");
        $(formSelector + "[name=" + index + "]").prop('disabled', true);
        validator.unmark($(formSelector + "[name=" + index + "]")); // Per rimuovere alert precedenti
    });
    $(formSelector + "#savebtn").hide();
    $(formSelector + "#cleanbtn").hide();
    $(formSelector + "#deletebtn").hide();
    if (editButton) {
        $(formSelector + "#modifybtn").show();
}
}

function getClass(objClass, id, ...callBack) {
    if (objClass.form_name != undefined) {
        $("[name='" + objClass.form_name + "']").trigger("reset");
    } else {
        $("form").trigger("reset");
    }


    var f = function () {
        $.each(objClass, function (index, value) {
            //$("input[name=" + index + "]").val(value);
            var elementType = $("[name='" + index + "']").prop('nodeName');
            switch (elementType) {
                case "SELECT":
                    if (value != null) {
                        //if(value instanceof Object && Object.values(value)[1] != null)
                        if ($("[name='" + index + "']").attr("multiple") == "multiple") {

                            var arr = [];
                            if (Array.isArray(value)) {
                                $.each(value, function (key, value) {
                                    if (value instanceof Object) {
                                        if (Object.values(value)[1] != null) {
                                            // oggetti in forma {name:, value:}
                                            var option = new Option(Object.values(value)[0], Object.values(value)[1], true, true);
                                            $("[name='" + index + "']").append(option).trigger("change");
                                            arr.push(Object.values(value)[1]);
                                        }
                                    } else {
                                        if (value != null) {
                                            var option = new Option(value, value, true, true);
                                            $("[name='" + index + "']").append(option).trigger("change");
                                            arr.push(value);
                                        }
                                    }
                                });
                                objClass[index] = arr;
                            } else {
                                var option = new Option(value, value, true, true);
                                $("[name='" + index + "']").append(option).trigger("change");
                            }

                        } else {
                            if (value instanceof Object) {
                                if (Object.values(value)[1] != null) {
                                    // oggetti in forma {name:, value:}
                                    var option = new Option(Object.values(value)[0], Object.values(value)[1], true, true);
                                    $("[name='" + index + "']").append(option).trigger("change");
                                    objClass[index] = Object.values(value)[1];
                                } else {
                                    $("[name='" + index + "']").val(null).trigger("change");
                                }
                            } else {
                                if (value != null) {
                                    var option = new Option(value, value, true, true);
                                    $("[name='" + index + "']").append(option).trigger("change");
                                }
                            }
                        }
                    } else {
                        $("[name='" + index + "']").val(null).trigger("change");
                    }

                    break;
                case "INPUT":
                    if ($("[name='" + index + "']").attr("type") == "checkbox") {
                        $("[name='" + index + "']").val(value);
                        $("[name='" + index + "']").prop("checked", parseInt(value) === 1);
                    } else {
                        if ($("[name='" + index + "']").attr("date") == "date") {
                            if (value == null || value == false) {
                                break;
                            }
                            var date = new Date(value * 1000);
                            var dataIT = ("0" + (date.getDate())).slice(-2) + "/" + ("0" + (date.getMonth() + 1)).slice(-2) + "/" + date.getFullYear();
                            var setData = {singleDatePicker: true, opens: 'right',
                                calender_style: "picker_2",
                                format: 'DD/MM/YYYY',
                                showDropdowns: true,
                                "minYear": 2000,
                                "maxYear": 2100,
                                startDate: dataIT,
                                "locale": {
                                    "daysOfWeek": [
                                        "Do",
                                        "Lu",
                                        "Ma",
                                        "Me",
                                        "Gi",
                                        "Ve",
                                        "Sa"
                                    ],
                                    "monthNames": [
                                        "Gennaio",
                                        "Febbraio",
                                        "Marzo",
                                        "Aprile",
                                        "Maggio",
                                        "Giugno",
                                        "Luglio",
                                        "Agosto",
                                        "Settembre",
                                        "Ottobre",
                                        "Novembre",
                                        "Dicembre"
                                    ],
                                    "firstDay": 1
                                }
                            };

                            $("[name='" + index + "']").daterangepicker(setData, null);
                            $("[name='" + index + "']").val(dataIT);
                        } else {
                            $("[name='" + index + "']").val(value);
                        }
                    }
                    break;
                case "TEXTAREA":
                    $("[name='" + index + "']").html(value);
                    break;
            }
        });
        callBack.forEach(s => s.apply());
    };
    objClass.get(id, f);
}


function saveClass(objClass, ...callBack) {

    setTimeout(() => {
        var formdata = $("form").serializeArray();

        $('form input[type="checkbox"]:not(:checked)').each(function () {
            if ($.inArray(this.name, formdata) === -1) {
                formdata.push({name: this.name, value: '0'});
            }
        });
        
        if (objClass.form_name != undefined) {
            var formdata = $("[name='" + objClass.form_name + "']").serializeArray();
        }


        var data = {};
        $(formdata).each(function (index, obj) {
            if ($("[name='" + obj.name + "']").attr("date") == "date") {
                if (obj.value.length > 0) {
                    var parts = obj.value.split('/');
                    var dataISO = parts[2] + '-' + parts[1] + '-' + parts[0];
                    objClass[obj.name] = dataISO;
                } else {
                    objClass[obj.name] = "";
                }
            } else {
                // controllo se multiselect
                if ($("[name='" + obj.name + "']").attr("multiple") == "multiple") {
                    //valori come array di oggetti
                    objClass[obj.name] = null;
                    var arr = [], $select = $("[name='" + obj.name + "']"), name = $select.attr("name");
                    $select.find("option:selected").each(function () {
                        arr[arr.length] = this.value;
                    });
                    objClass[obj.name] = arr;
                } else {
                    objClass[obj.name] = obj.value;
                }
            }
        });

        let afterSave = function () {
            // clean
            $.each(objClass, function (index, value) {

                var elementType = $("[name='" + index + "']").prop('nodeName');
                $("[name=" + index + "]").val(null).trigger("change");
                $("[name=" + index + "]").prop("checked", false);
            });
            // callback
            callBack.forEach(s => s.apply());
        };
        let safe = false;
        if (objClass.id > 0) {
            safe = true;
        }
        if (safe) {
            objClass.update(afterSave);
        } else {
            objClass.insert(afterSave);
        }
    }, 250);
}

function removeClass(objClass, id, safe, ...callBack) {
    let clean = function () {
        $.each(objClass, function (index, value) {
            $("[name=" + index + "]").val(null).trigger("change");
            $("[name=" + index + "]").prop("checked", false);
        });
    };

    let f = function () {
        if (safe) {
            objClass.erase(id, clean, ...callBack);
        } else {
            objClass.delete(id, clean, ...callBack);
        }
    };
    alertConfirm("Conferma", "Sei sicuro di voler cancellare l'elemento?", f);
}
function alertSuccess(title = "", message = "") {
    PNotify.prototype.options.delay = 8000;
    if ($('.alert-success').length < 1) {
        var notice = new PNotify({
            title: title,
            text: message,
            type: "notice",
            addclass: 'alert-success',
            nonblock: {nonblock: true}
        });
}
}


function alertInfo(title = "", message = "") {
    PNotify.prototype.options.delay = 4000;
    if ($('.alert-info').length < 1) {
        var notice = new PNotify({
            title: title,
            text: message,
            type: "notice",
            addclass: 'alert-info',
            nonblock: {nonblock: true}
        });
}
}
function alertError(title = "", message = "") {
    PNotify.prototype.options.delay = 8000;
    if ($('.alert-error').length < 1) {
        var notice = new PNotify({
            title: title,
            text: message,
            type: "notice",
            addclass: 'alert-error',
            nonblock: {nonblock: true}
        });
}
}


function alertConfirm(title = "", message = "", ...callBack) {
    $.confirm({
        title: title,
        content: message,
        autoClose: 'discard|9000',
        bgOpacity: 0.7,
        theme: 'material',
        buttons: {
            confirm: {
                btnClass: 'btn btn-warning btn-yellow-warning',
                text: 'CONFERMA',
                action: function () {
                    callBack.forEach(s => s.apply());
                }
            },
            discard: {
                btnClass: 'btn btn-success btn-blue-success',
                text: 'ANNULLA'
            }
        }
    });
}
function alertMessage(title = "", message = "", ...callBack) {
    // alert
    $.alert({
        title: title,
        content: message,
        icon: 'fa fa-exclamation-triangle',
        animation: 'scale',
        closeAnimation: 'scale',
        theme: 'material',
        buttons: {
            okay: {
                text: 'Okay',
                btnClass: 'btn-blue',
                action: function () {
                    callBack.forEach(s => s.apply());
                }
            }
        }
    });
}

$(".to-upper").change(function () {
    $(this).val($(this).val().toUpperCase());
});

// disable keyboard input in date field
$(".filter-date, .date, .document_date").on('keydown', function (e)
{
    $(this).inputmask({mask: "9{1,2}/9{1,2}/9{4}"});
});

// gestione ricalcolo automatico percentuali e valori sconto e maggiorazione in base agli input
function manageSurchargePercent() {
    if ($("input[name=price]").val() != null && $("input[name=price]").val().length > 0) {
        var surchargePercent = ($("input[name=surcharge]").val() / $("input[name=price]").val()) * 100;
        surchargePercent = parseFloat(Math.round((surchargePercent + 0.00001) * 100) / 100).toFixed(2);
        $("input[name=surcharge_percent]").val(surchargePercent);
    }
    if ($("input[name=unit_price]").val() != null && $("input[name=unit_price]").val().length > 0) {
        var surchargePercent = ($("input[name=surcharge]").val() / $("input[name=unit_price]").val()) * 100;
        surchargePercent = parseFloat(Math.round((surchargePercent + 0.00001) * 100) / 100).toFixed(2);
        $("input[name=surcharge_percentage]").val(surchargePercent);
    }
}
function manageDiscountPercent() {
    if ($("input[name=price]").val() != null && $("input[name=price]").val().length > 0) {
        var discountPercent = ($("input[name=discount]").val() / $("input[name=price]").val()) * 100;
        discountPercent = parseFloat(Math.round((discountPercent + 0.00001) * 100) / 100).toFixed(2);
        $("input[name=discount_percent]").val(discountPercent);
    }
    if ($("input[name=unit_price]").val() != null && $("input[name=unit_price]").val().length > 0) {
        var discountPercent = ($("input[name=discount]").val() / $("input[name=unit_price]").val()) * 100;
        discountPercent = parseFloat(Math.round((discountPercent + 0.00001) * 100) / 100).toFixed(2);
        $("input[name=discount_percentage]").val(discountPercent);
    }
}
function manageSurcharge() {
    if ($("input[name=price]").val() != null && $("input[name=price]").val().length > 0) {
        var surcharge = (($("input[name=surcharge_percent]").val() * $("input[name=price]").val()) / 100);
        surcharge = parseFloat(Math.round((surcharge + 0.00001) * 100) / 100).toFixed(2);
        $("input[name=surcharge]").val(surcharge);
    }
    if ($("input[name=unit_price]").val() != null && $("input[name=unit_price]").val().length > 0) {
        var surcharge = (($("input[name=surcharge_percentage]").val() * $("input[name=unit_price]").val()) / 100);
        surcharge = parseFloat(Math.round((surcharge + 0.00001) * 100) / 100).toFixed(2);
        $("input[name=surcharge]").val(surcharge);
    }
}
function manageDiscount() {
    if ($("input[name=price]").val() != null && $("input[name=price]").val().length > 0) {
        var discount = (($("input[name=discount_percent]").val() * $("input[name=price]").val()) / 100);
        discount = parseFloat(Math.round((discount + 0.00001) * 100) / 100).toFixed(2);
        $("input[name=discount]").val(discount);
    }
    if ($("input[name=unit_price]").val() != null && $("input[name=unit_price]").val().length > 0) {
        var discount = (($("input[name=discount_percentage]").val() * $("input[name=unit_price]").val()) / 100);
        discount = parseFloat(Math.round((discount + 0.00001) * 100) / 100).toFixed(2);
        $("input[name=discount]").val(discount);
    }
}


function downloadEinv(invoiceId) {
    $.get("/lib/einv/v1/einv/check/" + invoiceId, function (data) {
        var json = JSON.parse(data);
        if (json.result) {
            defaultSuccess();
            var win = window.open("/lib/einv/v1/einv/download/" + invoiceId, '_blank');
            if (win) {
                //Browser has allowed it to be opened
                win.focus();
            } else {
                //Browser has blocked it
                defaultError();
            }
        } else {
            defaultError();
        }
    }).error(function () {
        defaultError();
    });
}

function sleep(milliseconds) {
    const date = Date.now();
    let currentDate = null;
    do {
        currentDate = Date.now();
    } while (currentDate - date < milliseconds);
}
function stripHtml(html)
{
    var tmp = document.createElement("DIV");
    tmp.innerHTML = html;
    return tmp.textContent || tmp.innerText || "";
}
$(".filter-date, .date, .document_date").each(function (index) {
    $(this).inputmask({mask: "9{1,2}/9{1,2}/9{4}"});
});


function downloadExport(endpointBase){
    var obj = {};
    $('.filter').each(function(index) {
        obj[$(this).prop("id").replace('F_', '')] = $(this).val();
    });
    json =  JSON.stringify(obj);
    postExportData(endpointBase, json);
}

function setMeasure(){
    $("[name=width_unit_measure]").prop("disabled", true);
    $("[name=height_unit_measure]").prop("disabled", true);
    let old_width = $("[name=width_unit_measure]").val();
    let old_height = $("[name=height_unit_measure]").val();
    $("[name=width_unit_measure]").val("");
    $("[name=height_unit_measure]").val("");
    
    if($("[name=measure_unit]").val() == "USM"){
        $("[name=width_unit_measure]").prop("disabled", false);
        $("[name=height_unit_measure]").prop("disabled", false);
        $("[name=width_unit_measure]").val(old_width);
        $("[name=height_unit_measure]").val(old_height);
    }
    
    if($("[name=measure_unit]").val() == "ULM"){
        $("[name=width_unit_measure]").prop("disabled", false);
        $("[name=width_unit_measure]").val(old_width);
    }
}
function manageUnitMeasure(parent_id){
    if(parent_id != null && parent_id.length > 0){
        $("[name=measure_unit]").prop('disabled', true);
    }else{
        $("[name=measure_unit]").prop('disabled', false);
    }
}

function reloadTotali() {

    let qu = $("[name=quantity]").val() == null || $("[name=quantity]").val().length <= 0 ? 0 : $("[name=quantity]").val();
    let pu = $("[name=unit_price]").val() == null || $("[name=unit_price]").val().length <= 0 ? 0 : $("[name=unit_price]").val();
    let al = $("[name=sell_tax_rate]").val() == null || $("[name=sell_tax_rate]").val().length <= 0 ? 0 : $("[name=sell_tax_rate]").val();
    let sco = $("[name=discount]").val() == null || $("[name=discount]").val().length <= 0 ? 0 : $("[name=discount]").val();
    let mag = $("[name=surcharge]").val() == null || $("[name=surcharge]").val().length <= 0 ? 0 : $("[name=surcharge]").val();
    let w = $("[name=width_unit_measure]").val() == null || $("[name=width_unit_measure]").val().length <= 0 ? 1 : $("[name=width_unit_measure]").val();
    let h = $("[name=height_unit_measure]").val() == null || $("[name=height_unit_measure]").val().length <= 0 ? 1 : $("[name=height_unit_measure]").val();
    let d = $("[name=depth_unit]").val() == null || $("[name=depth_unit]").val().length <= 0 ? 1 : $("[name=depth_unit]").val();


    var w_float = parseFloat(parseFloat(w)).toFixed(4);
    var h_float = parseFloat(parseFloat(h)).toFixed(4);
    var d_float = parseFloat(parseFloat(d)).toFixed(4);

    pu = parseFloat(parseFloat(pu) * w_float * h_float * d_float);

    var punit_s = parseFloat(parseFloat(pu) - parseFloat(sco)).toFixed(4);
    var punit_sm = parseFloat(parseFloat(punit_s) + parseFloat(mag)).toFixed(4);
    var bas_imp = parseFloat(parseFloat(punit_sm) * parseFloat(qu)).toFixed(2);
    var imposta = parseFloat((parseFloat(bas_imp) * parseFloat(al)) / 100).toFixed(2);
    var tot_tax = parseFloat(parseFloat(bas_imp) + parseFloat(imposta)).toFixed(2);
    var tot = parseFloat(parseFloat(tot_tax)).toFixed(2);

    $("[name=unit_price_discounted]").val(punit_s);
    $("[name=discounted_unit_price_surcharged]").val(punit_sm);
    $("[name=taxable_amount]").val(bas_imp);
    $("[name=tax_amount]").val(imposta);
    $("[name=taxed_amount]").val(tot_tax);
    $("[name=total_price]").val(tot);
}