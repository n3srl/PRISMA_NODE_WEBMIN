/**
*
* @author: N3 S.r.l.
*/

class CaptureModel extends Capture {
	constructor(){
		super();
	}
	get(id = null,...callBack){
		if (id !== null){
			let endpoint = this.endpointBase + '/'+id;
			getAjax(this,endpoint,...callBack);
		}else{
			let helper = new CaptureModel(this.version);
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
		let helper = new CaptureModel(this.version);
		helper.endpoint = this.endpoint;
		helper.id = id;
		let obj = helper.parseToObj;
		let json = JSON.stringify(obj);
		patchAjax(this,endpoint,json,...callBack);
	}
	delete(id = null, ...callBack){
		let endpoint = this.endpointBase + '';
		let helper = new CaptureModel(this.version);
		helper.endpoint = this.endpoint;
		helper.id = id;
		let obj = helper.parseToObj;
		let json = JSON.stringify(obj);
		deleteAjax(this,endpoint,json,...callBack);
	}
	get parseToObj(){
		let obj = {
			id: this.id
		};
		return obj;
	}
	parseJsonToObj(context_,json,...callBack){
		let obj_full = JSON.parse(json);
		let obj = obj_full.data;
		let context = context_;
		context.id = obj.id;
		//callback
		callBack.forEach(s => s.apply());
	}
}
function setCaptureVisibility(){ 

	let inafcapture_visibility = new Capture(); 

	inafcapture_visibility.id = false;  
        inafcapture_visibility.name = true;
        inafcapture_visibility.date = true;  
	inafcapture_visibility.create_date = true; 
	inafcapture_visibility.valid_from = true; 
	inafcapture_visibility.valid_to = true; 
	inafcapture_visibility.erased = true; 
	inafcapture_visibility.last_update = true; 
	

	$.each(inafcapture_visibility, function(key, value){
		if (!value)
			$("." +$.md5(key)).hide();
	});
}

