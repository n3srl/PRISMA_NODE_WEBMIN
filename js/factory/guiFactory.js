/**
 *
 * @author: N3 S.r.l.
 */

function setVisibility() {

    let core_gui_visibility = new Gui();

    core_gui_visibility.id = true;
    core_gui_visibility.oid = true;
    core_gui_visibility.name = true;
    core_gui_visibility.description = true;
    core_gui_visibility.parent_id = true;
    core_gui_visibility.menu_item = true;
    core_gui_visibility.sorting = true;
    core_gui_visibility.create_date = true;
    core_gui_visibility.valid_from = true;
    core_gui_visibility.valid_to = true;
    core_gui_visibility.erased = true;
    core_gui_visibility.last_update = true;


    $.each(core_gui_visibility, function (key, value) {
        if (!value)
            $("." + $.md5(key)).hide();
    });
}
$(setVisibility());

