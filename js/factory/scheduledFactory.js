/**
*
* @author: N3 S.r.l.
*/

class ScheduledModel extends Scheduled {
	constructor(){
		super();
	}
	get(id = null,...callBack){
		if (id !== null){
			let endpoint = this.endpointBase + '/'+id;
			getAjax(this,endpoint,...callBack);
		}else{
			let helper = new ScheduledModel(this.version);
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
		let helper = new ScheduledModel(this.version);
		helper.endpoint = this.endpoint;
		helper.id = id;
		let obj = helper.parseToObj;
		let json = JSON.stringify(obj);
		patchAjax(this,endpoint,json,...callBack);
	}
	delete(id = null, ...callBack){
		let endpoint = this.endpointBase + '';
		let helper = new ScheduledModel(this.version);
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
function setScheduledVisibility(){

	let inafscheduled_visibility = new Scheduled();

	inafscheduled_visibility.id = false;
        inafscheduled_visibility.name = true;
        inafscheduled_visibility.date = true;
	inafscheduled_visibility.create_date = true;
	inafscheduled_visibility.valid_from = true;
	inafscheduled_visibility.valid_to = true;
	inafscheduled_visibility.erased = true;
	inafscheduled_visibility.last_update = true;


	$.each(inafscheduled_visibility, function(key, value){
		if (!value)
			$("." +$.md5(key)).hide();
	});
}
