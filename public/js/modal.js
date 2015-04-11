'use strict';
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

var My = My || {},
	Views = Views || {};

Views.modalBackDrop = document.createElement('div');
Views.modalBackDrop.id = 'modal-backdrop';
Views.modalBackDrop.className = 'hide';
document.querySelector('body').appendChild(Views.modalBackDrop);

Views.modalHolder = document.createElement('div');
Views.modalHolder.id = 'modal-holder';
Views.modalHolder.className = 'hide';
Views.modalHolder.innerHTML = '<div id="modal">'
	+ '<a href="' + window.location.pathname + '#" id="modal-close" '
	+ 'class="float-right">close</a>'
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
