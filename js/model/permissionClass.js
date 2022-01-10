/**
 *
 * @author: N3 S.r.l.
 */

class Permission {
    constructor() {
        this.id = null;
        this.oid = null;
        this.ext_oid = null;
        this.person_id = null;
        this.group_id = null;
        this.execute = null;
        this.read = null;
        this.write = null;
        this.active = null;
        this.username = null;
        this.secret_token = null;
    }
}

class PermissionModel extends Permission {
    constructor(version = "v1") {
        super();
        this.version = version;
        if (this.version == "v1" || this.version == 1) {
            this.endpointBase = "/lib/core/" + version + "/permission";
    }
    }
    get(id = null, ...callBack) {
        if (id !== null) {
            let endpoint = this.endpointBase + '/' + id;
            getAjax(this, endpoint, ...callBack);
        } else {
            let helper = new PermissionModel(this.version);
            let obj = this;
            $.each(helper, function (index, value) {
                obj[index] = value;
            });
            callBack.forEach(s => s.apply());
    }
    }
    insert(...callBack) {
        let endpoint = this.endpointBase + '';
        let obj = this.parseToObj;
        let json = JSON.stringify(obj);
        postAjax(this, endpoint, json, ...callBack);
    }
    update(...callBack) {
        let endpoint = this.endpointBase + '';
        let obj = this.parseToObj;
        let json = JSON.stringify(obj);
        putAjax(this, endpoint, json, ...callBack);
    }
    erase(id = null, ...callBack) {
        let endpoint = this.endpointBase + '';
        let helper = new PermissionModel(this.version);
        helper.endpoint = this.endpoint;
        helper.id = id;
        let obj = helper.parseToObj;
        let json = JSON.stringify(obj);
        patchAjax(this, endpoint, json, ...callBack);
    }
    delete(id = null, ...callBack) {
        let endpoint = this.endpointBase + '';
        let helper = new PermissionModel(this.version);
        helper.endpoint = this.endpoint;
        helper.id = id;
        let obj = helper.parseToObj;
        let json = JSON.stringify(obj);
        deleteAjax(this, endpoint, json, ...callBack);
    }
    get parseToObj() {
        let obj = {
            id: this.id,
            oid: this.oid,
            ext_oid: this.ext_oid,
            person_id: this.person_id,
            group_id: this.group_id,
            execute: this.execute,
            read: this.read,
            write: this.write,
            active: this.active,
            username: this.username,
            secret_token: this.secret_token,
        };
        return obj;
    }
    parseJsonToObj(context_, json, ...callBack) {
        let obj_full = JSON.parse(json);
        let obj = obj_full.data;
        let context = context_;
        context.id = obj.id;
        context.oid = obj.oid;
        context.ext_oid = obj.ext_oid;
        context.person_id = obj.person_id;
        context.group_id = obj.group_id;
        context.execute = obj.execute;
        context.read = obj.read;
        context.write = obj.write;
        context.active = obj.active;
        context.username = obj.username;
        context.secret_token = obj.secret_token;
        //callback
        callBack.forEach(s => s.apply());
    }
}

