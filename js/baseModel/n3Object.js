/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class N3Obj {
    constructor(version = "v1", module = "name", className = "company", method = "") {
        this.version = version;
        this.endpointBase = "/lib/" + module + "/" + version + "/" + className;
        this.id = null;
        this.oid = null;
    }
}