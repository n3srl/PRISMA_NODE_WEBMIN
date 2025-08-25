/**
 *
 * @author: N3 S.r.l.
 */

function setVisibility() {

    let core_permission_visibility = new Permission();

    core_permission_visibility.id = true;
    core_permission_visibility.oid = true;
    core_permission_visibility.ext_oid = true;
    core_permission_visibility.person_id = true;
    core_permission_visibility.group_id = true;
    core_permission_visibility.execute = true;
    core_permission_visibility.read = true;
    core_permission_visibility.write = true;
    core_permission_visibility.active = true;
    core_permission_visibility.username = true;
    core_permission_visibility.secret_token = true;
    core_permission_visibility.modified_by = true;
    core_permission_visibility.created_by = true;
    core_permission_visibility.assigned = true;
    core_permission_visibility.create_date = true;
    core_permission_visibility.valid_from = true;
    core_permission_visibility.valid_to = true;
    core_permission_visibility.erased = true;
    core_permission_visibility.last_update = true;


    $.each(core_permission_visibility, function (key, value) {
        if (!value)
            $("." + $.md5(key)).hide();
    });
}
$(setVisibility());

