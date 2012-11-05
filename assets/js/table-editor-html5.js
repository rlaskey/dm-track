/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

/* this version is for modern browsers; it removes the jQuery dependency */
var tableEditor = {hiddenClass:'hidden'};
tableEditor.loadInput = function(){ 
	var edit = document.getElementById('edit');
	var controller = edit.dataset.controller;
	var request = new XMLHttpRequest();

	request.open('GET',
			controller+this.getAttribute('id').replace('-','/'),
			true);
	request.setRequestHeader('X-Requested-With','XMLHttpRequest');
	request.send();
	request.onload = function(e){
		edit.innerHTML = request.responseText;
		tableEditor.editBox.classList.remove(tableEditor.hiddenClass);
	}
}
tableEditor.setup = function(){
	var rows = document.querySelectorAll('#chart tr.linky');
	for (var i = 0; i < rows.length; i++)
	{
		rows[i].addEventListener('click', tableEditor.loadInput, false);
	}
	tableEditor.editBox = document.getElementById('edit-box');
	tableEditor.editBox
		.querySelector('span')
		.addEventListener('click', function(){
				tableEditor.editBox.classList
				.add(tableEditor.hiddenClass);
				}, false);
}

document.addEventListener('DOMContentLoaded', tableEditor.setup, false);
