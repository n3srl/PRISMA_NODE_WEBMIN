/**
 *
 * @author: N3 S.r.l.
 */

class Group {
    constructor() {
        this.id = null;
        this.oid = null;
        this.name = null;
        this.type = null;
    }
}

class GroupModel extends Group {
    constructor(version = "v1") {
        super();
        this.version = version;
        if (this.version == "v1" || this.version == 1) {
            this.endpointBase = "/lib/core/" + version + "/group";
    }
    }
    get(id = null, ...callBack) {
        if (id !== null) {
            let endpoint = this.endpointBase + '/' + id;
            getAjax(this, endpoint, ...callBack);
        } else {
            let helper = new GroupModel(this.version);
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
        let helper = new GroupModel(this.version);
        helper.endpoint = this.endpoint;
        helper.id = id;
        let obj = helper.parseToObj;
        let json = JSON.stringify(obj);
        patchAjax(this, endpoint, json, ...callBack);
    }
    delete(id = null, ...callBack) {
        let endpoint = this.endpointBase + '';
        let helper = new GroupModel(this.version);
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
            name: this.name,
            type: this.type,
        };
        return obj;
    }
    parseJsonToObj(context_, json, ...callBack) {
        let obj_full = JSON.parse(json);
        let obj = obj_full.data;
        let context = context_;
        context.id = obj.id;
        context.oid = obj.oid;
        context.name = obj.name;
        context.type = obj.type;
        //callback
        callBack.forEach(s => s.apply());
    }
}

