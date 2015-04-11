/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

$.ajaxSettings.beforeSend = function(xhr, settings) {
	xhr.setRequestHeader('X-CSRF-Token', APP.csrf.val());
};


Backbone.Model.prototype.patch = function() {
	var pick = _.flatten(arguments);
	if (pick.length === 0) pick = _.keys(this.defaults);
	return this.save(_.pick(this.attributes, pick), {patch:true});
};


var My = My || {},
	Views = Views || {};


// Modal
Views.modalBackDrop = document.createElement('div');
Views.modalBackDrop.id = 'modal-backdrop';
Views.modalBackDrop.className = 'hide';
document.querySelector('body').appendChild(Views.modalBackDrop);

Views.modalHolder = document.createElement('div');
Views.modalHolder.id = 'modal-holder';
Views.modalHolder.className = 'hide';
Views.modalHolder.innerHTML = '<div id="modal">'
	+ '<a href="' + window.location.pathname + '#" id="modal-close" '
	+ 'class="float-right">' + $('#js .close').text() + '</a>'
	+ '</div>';
document.querySelector('body').appendChild(Views.modalHolder);

Views.displayModal = function(bool) {
	var func = 'remove';
	if (bool === false) func = 'add';
	$(Views.modalHolder)[func + 'Class']('hide');
	$(Views.modalBackDrop)[func + 'Class']('hide');
}

Views.modalShown = function() {return ! $(Views.modalHolder).hasClass('hide');}

Views.Modal = Backbone.View.extend({
	el: '#modal',
	setView: function(newView) {
		if (this.child) this.child.remove();
		this.child = newView;
		this.el.appendChild(this.child.el);
		Views.displayModal(true);
		return this.child;
	}
});

My.modalView = new Views.Modal;


// Alerts
Views.alerts = document.createElement('ul');
Views.alerts.id = 'alerts';
Views.alerts.className = 'hide pointer';
document.querySelector('body').appendChild(Views.alerts);

$(Views.alerts).on('click', function(event) {
	event.currentTarget.classList.add('hide');
});

Views.hideAlerts = _.debounce(function() {
	$(Views.alerts).addClass('hide').empty();
}, 4200);

Views.addAlert = function(message, className, hideAfter) {
	$(Views.alerts).removeClass('hide');

	if (typeof message === 'undefined' || message === '')
		var message = $('#js .alert-default').text();
	if (typeof className === 'undefined' || className === '')
		var className = 'alert-success';
	if (typeof hideAfter === 'undefined' || hideAfter !== false)
		Views.hideAlerts();

	$(Views.alerts).prepend(
		'<li class="' + className + '">' + message + '</li>'
	);
};
