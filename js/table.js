'use strict';
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

var My = My || {},
	Models = Models || {},
	Collections = Collections || {},
	Views = Views || {},
	Routers = Routers || {};


Models.Row = Backbone.Model.extend({
	dayTime: function(priorDay){
		var date = new Date(this.get('time').replace(' ', 'T') + 'Z');
		var dateParts = date.toString().split(' ');

		var day = dateParts[1] + '/' + date.getDate();
		if(day !== priorDay) this.set('day', day);
		else this.set('day', '');

		var H = date.getHours(), l = H, P = 'am';
		if(H === '00') l = '12';
		if(H >= 12) P = 'pm';
		if(H > 12) l = H - 12;
		this.set('hm', l + ':' + ('0' + date.getMinutes()).substr(-2) + P);

		return day;
	}
});

Models.Range = Backbone.Model.extend({url: 'range'});


Collections.Row = Backbone.Collection.extend({
	url: 'table/data',
	model: Models.Row
});


Views.localDateTime = function(datetime){
	if( ! datetime) var date = new Date;
	else var date = new Date(datetime.replace(' ', 'T') + 'Z');
	return date.getFullYear() + '-'
		+ ('0' + (1 + date.getMonth())).substr(-2) + '-'
		+ ('0' + date.getDate()).substr(-2) + 'T'
		+ ('0' + date.getHours()).substr(-2) + ':'
		+ ('0' + date.getMinutes()).substr(-2);
};


Views.glucoseStyle = function(mgDL){
	var color = 0;
	if(mgDL < 180 && mgDL > 80){
		var percentage = (mgDL - 80) / 100;
		color = 240 - parseInt(240 * percentage);
	}
	else if(mgDL <= 80) color = 240;
	return 'background:hsl(' + color + ', 64%, 83%);';
};


Views.Simple = Backbone.View.extend({
	tagName: 'form',
	events: {
		'change input, textarea, select': 'changeInput',
		'submit': 'save',
	},
	initialize: function(){this.listenTo(this.model, 'sync', this.render);},
	render: function(){
		this.$el.html(this.template(this.model.attributes));
		if(this.selectFields) _.each(this.selectFields, this.setSelect, this);
		if( ! _.isUndefined(this.model.get('time'))) this.model
			.set('time', Views.localDateTime(this.model.get('time')));
		return this;
	},
	save: function(event){
		event.preventDefault();

		if(this.model.get('time')){
			var asUTC = new Date(this.model.get('time') + 'Z'),
				corrected = asUTC.getTime()
					+ (60000 * (new Date).getTimezoneOffset());
			this.model.set('time', new Date(corrected).toISOString());
		}

		var xhr = this.model.patch();

		if(xhr === false){
			var message = 'There was a problem with what you tried to do :/';
			if(typeof this.model !== 'undefined')
				message = this.model.validationError;
			return alert(message);
		}

		$(xhr).one('load', function(event){
			var message = '', className = '', hideAfter = true;
			if(event.target.status >= 400){
				message = event.target.responseText;
				className = 'alert-danger';
				hideAfter = false;
			}
			Views.addAlert(message, className, hideAfter);
			if(hideAfter) document.getElementById('modal-close').click();
		});
	},
	changeInput: function(event){
		var element = event.currentTarget;
		this.model.set(element.name, element.value);
	},
});

Views.Glucose = Views.Simple.extend({
	template: _.template($('#glucose-template').html()),
});

Views.Insulin = Views.Simple.extend({
	template: _.template($('#insulin-template').html()),
	selectFields: ['type'],
	setSelect: function(field){
		var input = this.el.querySelector('[name=' + field + ']');
		if(input === null) return;
		var currentVal = this.model.get(field);
		if(currentVal === null) return this.model.set(field, input.value);
		input.value = this.model.get(field);
	}
});

Views.Range = Views.Simple.extend({
	template: _.template($('#range-template').html()),
});


Views.Row = Backbone.View.extend({
	tagName: 'tr',
	className: 'pointer',
	events: {'click': 'edit'},
	render: function(){
		this.$el.html(this.template(this.model.attributes));
		return this;
	},
	edit: function(){
		My.RouterTable.navigate(
			this.model.get('model').substr(0, 1).toLowerCase() + '/'
			+ this.model.get('PK'),
			{trigger: true}
		);
	}
});

Views.RowGlucose = Views.Row
	.extend({template: _.template($('#row-glucose-template').html())});

Views.RowInsulin = Views.Row
	.extend({template: _.template($('#row-insulin-template').html())});


Views.Table = Backbone.View.extend({
	el: '#data',
	rows: [],
	day: '',
	initialize: function(){
		this.collection = new Collections.Row;
		this.listenTo(this.collection, 'request', this.clear);
		this.listenTo(this.collection, 'sync', this.render);
	},
	clear: function(){
		for(var i = 0, len = this.rows.length; i < len; i++)
			this.rows.pop().remove();
	},
	render: function(){
		this.collection.each(this.renderRow, this);
		return this;
	},
	renderRow: function(row){
		this.day = row.dayTime(this.day);
		var type = 'Row' + row.get('model');
		var rowView = new Views[type]({model: row});
		this.rows.push(rowView);
		$('#tbody').append(rowView.render().el);
	},
});



Routers.Table = Backbone.Router.extend({
	routes: {
		'': 'home',
		'i/:PK': 'editInsulin',
		'i': 'newInsulin',
		'g/:PK': 'editGlucose',
		'g': 'newGlucose',
		'r': 'editRange'
	},
	home: function(){
		if( ! My.ViewTable) My.ViewTable = new Views.Table;
		My.ViewTable.collection.fetch();
		Views.displayModal(false);
	},
	editInsulin: function(PK){
		My.modalView.setView(new Views.Insulin({
			model: new Models.Insulin({insulin_id: PK})
		})).model.fetch();
	},
	newInsulin: function(){
		My.modalView.setView(new Views.Insulin({model: new Models.Insulin}))
			.render();
	},
	editGlucose: function(PK){
		My.modalView.setView(new Views.Glucose({
			model: new Models.Glucose({glucose_id: PK})
		})).model.fetch();
	},
	newGlucose: function(){
		My.modalView.setView(new Views.Glucose({model: new Models.Glucose}))
			.render();
	},
	editRange: function(){
		My.modalView.setView(new Views.Range({model: new Models.Range}))
			.model.fetch();
	}
});
My.RouterTable = new Routers.Table;
Backbone.history.start();
