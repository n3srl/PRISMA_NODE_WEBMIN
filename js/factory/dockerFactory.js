/**
*
* @author: N3 S.r.l.
*/

class DockerModel extends Docker {
	constructor(){
		super();
	}
	get(id = null,...callBack){
		if (id !== null){
			let endpoint = this.endpointBase + '/'+id;
			getAjax(this,endpoint,...callBack);
		}else{
			let helper = new DockerModel(this.version);
			let obj = this;
			$.each(helper, function (index, value) {
				obj[index] = value;
			});
			callBack.forEach(s => s.apply());
		}
	}
	insert(...callBack){
		let endpoint = this.endpointBase + '';
		let obj = this.parseToObj;
		let json = JSON.stringify(obj);
		postAjax(this,endpoint,json,...callBack);
	}
	update(...callBack){
		let endpoint = this.endpointBase + '';
		let obj = this.parseToObj;
		let json = JSON.stringify(obj);
		putAjax(this,endpoint,json,...callBack);
	}
	erase(id = null, ...callBack){
		let endpoint = this.endpointBase + '';
		let helper = new DockerModel(this.version);
		helper.endpoint = this.endpoint;
		helper.id = id;
		let obj = helper.parseToObj;
		let json = JSON.stringify(obj);
		patchAjax(this,endpoint,json,...callBack);
	}
	delete(id = null, ...callBack){
		let endpoint = this.endpointBase + '';
		let helper = new DockerModel(this.version);
		helper.endpoint = this.endpoint;
		helper.id = id;
		let obj = helper.parseToObj;
		let json = JSON.stringify(obj);
		deleteAjax(this,endpoint,json,...callBack);
	}
	get parseToObj(){
		let obj = {
			id: this.id,
			name: this.name,
			image: this.image,
			command: this.command,
			status: this.status,
			created: this.created,
		};
		return obj;
	}
	parseJsonToObj(context_,json,...callBack){
		let obj_full = JSON.parse(json);
		let obj = obj_full.data;
		let context = context_;
		context.id = obj.id;
		context.name = obj.name;
		context.image = obj.image;
		context.command = obj.command;
		context.status = obj.status;
		context.created = obj.created;
		//callback
		callBack.forEach(s => s.apply());
	}
}
function setDockerVisibility(){ 

	let inafdocker_visibility = new Docker(); 

	inafdocker_visibility.id = false; 
	inafdocker_visibility.name = true; 
	inafdocker_visibility.image = true; 
	inafdocker_visibility.command = false; 
	inafdocker_visibility.status = true; 
	inafdocker_visibility.created = false; 
	inafdocker_visibility.create_date = true; 
	inafdocker_visibility.valid_from = true; 
	inafdocker_visibility.valid_to = true; 
	inafdocker_visibility.erased = true; 
	inafdocker_visibility.last_update = true; 
	

	$.each(inafdocker_visibility, function(key, value){
		if (!value)
			$("." +$.md5(key)).hide();
	});
}

