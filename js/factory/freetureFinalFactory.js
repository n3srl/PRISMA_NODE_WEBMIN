/**
*
* @author: N3 S.r.l.
*/

class FreetureFinalModel extends FreetureFinal {
	constructor(){
		super();
	}
	get(id = null,...callBack){
		if (id !== null){
			let endpoint = this.endpointBase + '/'+id;
			getAjax(this,endpoint,...callBack);
		}else{
			let helper = new FreetureFinalModel(this.version);
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
		let helper = new FreetureFinalModel(this.version);
		helper.endpoint = this.endpoint;
		helper.id = id;
		let obj = helper.parseToObj;
		let json = JSON.stringify(obj);
		patchAjax(this,endpoint,json,...callBack);
	}
	delete(id = null, ...callBack){
		let endpoint = this.endpointBase + '';
		let helper = new FreetureFinalModel(this.version);
		helper.endpoint = this.endpoint;
		helper.id = id;
		let obj = helper.parseToObj;
		let json = JSON.stringify(obj);
		deleteAjax(this,endpoint,json,...callBack);
	}
	get parseToObj(){
		let obj = {
			id: this.id,
			key: this.key,
			value: this.value,
			description: this.description,
			show: this.show,
		};
		return obj;
	}
	parseJsonToObj(context_,json,...callBack){
		let obj_full = JSON.parse(json);
		let obj = obj_full.data;
		let context = context_;
		context.id = obj.id;
		context.key = obj.key;
		context.value = obj.value;
		context.description = obj.description;
		context.show = obj.show;
		//callback
		callBack.forEach(s => s.apply());
	}
}
function setFreetureFinalVisibility(){ 

	let inaffreeturefinal_visibility = new FreetureFinal(); 

	inaffreeturefinal_visibility.id = false; 
	inaffreeturefinal_visibility.key = true; 
	inaffreeturefinal_visibility.value = true; 
	inaffreeturefinal_visibility.description = true; 
	inaffreeturefinal_visibility.show = false; 
	inaffreeturefinal_visibility.create_date = true; 
	inaffreeturefinal_visibility.valid_from = true; 
	inaffreeturefinal_visibility.valid_to = true; 
	inaffreeturefinal_visibility.erased = true; 
	inaffreeturefinal_visibility.last_update = true; 
	

	$.each(inaffreeturefinal_visibility, function(key, value){
		if (!value)
			$("." +$.md5(key)).hide();
	});
}

