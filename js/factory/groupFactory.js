/**
 *
 * @author: N3 S.r.l.
 */

function setVisibility() {

    let core_group_visibility = new Group();

    core_group_visibility.id = true;
    core_group_visibility.oid = true;
    core_group_visibility.name = true;
    core_group_visibility.type = true;
    core_group_visibility.modified_by = true;
    core_group_visibility.created_by = true;
    core_group_visibility.assigned = true;
    core_group_visibility.create_date = true;
    core_group_visibility.valid_from = true;
    core_group_visibility.valid_to = true;
    core_group_visibility.erased = true;
    core_group_visibility.last_update = true;


    $.each(core_group_visibility, function (key, value) {
        if (!value)
            $("." + $.md5(key)).hide();
    });
}
$(setVisibility());

