/**
 *
 * @author: N3 S.r.l.
 */

function setVisibility() {

    let core_grouphasperson_visibility = new GroupHasPerson();

    core_grouphasperson_visibility.id = true;
    core_grouphasperson_visibility.oid = true;
    core_grouphasperson_visibility.person_id = true;
    core_grouphasperson_visibility.group_id = true;
    core_grouphasperson_visibility.modified_by = true;
    core_grouphasperson_visibility.created_by = true;
    core_grouphasperson_visibility.assigned = true;
    core_grouphasperson_visibility.create_date = true;
    core_grouphasperson_visibility.valid_from = true;
    core_grouphasperson_visibility.valid_to = true;
    core_grouphasperson_visibility.erased = true;
    core_grouphasperson_visibility.last_update = true;


    $.each(core_grouphasperson_visibility, function (key, value) {
        if (!value)
            $("." + $.md5(key)).hide();
    });
}
$(setVisibility());

