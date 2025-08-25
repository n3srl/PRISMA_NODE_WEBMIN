/**
*
* @author: N3 S.r.l.
*/

class StackModel extends Stack {
	constructor(){
		super();
	}
	get(id = null,...callBack){
		if (id !== null){
			let endpoint = this.endpointBase + '/'+id;
			getAjax(this,endpoint,...callBack);
		}else{
			let helper = new StackModel(this.version);
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
		let helper = new StackModel(this.version);
		helper.endpoint = this.endpoint;
		helper.id = id;
		let obj = helper.parseToObj;
		let json = JSON.stringify(obj);
		patchAjax(this,endpoint,json,...callBack);
	}
	delete(id = null, ...callBack){
		let endpoint = this.endpointBase + '';
		let helper = new StackModel(this.version);
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
function setStackVisibility(){ 

	let inafstack_visibility = new Stack(); 

	inafstack_visibility.id = false;  
        inafstack_visibility.name = true;
        inafstack_visibility.date = true;  
	inafstack_visibility.create_date = true; 
	inafstack_visibility.valid_from = true; 
	inafstack_visibility.valid_to = true; 
	inafstack_visibility.erased = true; 
	inafstack_visibility.last_update = true; 
	

	$.each(inafstack_visibility, function(key, value){
		if (!value)
			$("." +$.md5(key)).hide();
	});
}

