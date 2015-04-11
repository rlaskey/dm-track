'use strict';
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

(function() {

var signinLink = document.getElementById('personaLogin');
if (signinLink) {signinLink.onclick = function(evt) {
	navigator.id.request({
		siteName: 'DM.track',
		returnTo: '',
		oncancel: function() {alert(
			'Sorry, that did not work out :/ Please try again.'
		);}
	});
	return false;
};}

var processResponse = function() {
	switch(this.status) {
	case 200:
		window.location = '';
		break;
	default:
		alert('login failure :/ -- '+this.responseText);
	}
}

navigator.id.watch({
	loggedInUser: APP.email,
	onlogin: function(assertion) {
		var data = 'assertion='+assertion;

		var request = new XMLHttpRequest();
		request.open('POST', 'auth/persona');
		request.setRequestHeader(
			'Content-Type', 'application/x-www-form-urlencoded'
		);
		request.setRequestHeader('X-CSRF-Token', APP.csrf.val());
		request.send(data);
		request.onload = processResponse;
	},
	onlogout: function() {}
});
})();
