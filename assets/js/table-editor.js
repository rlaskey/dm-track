/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

'use strict';
(function(){
	var hiddenClass = 'hidden';

	var processResponse = function(){
		document.getElementById('edit-box').classList.remove(hiddenClass);
		document.getElementById('edit').innerHTML = this.responseText;
	}

	var loadInput = function(){
		var request = new XMLHttpRequest();
		request.open('GET',CI.base+'input/'+this.id.replace('-','/'));
		request.setRequestHeader('X-Requested-With','XMLHttpRequest');
		request.send();
		request.onload = processResponse;
	}

	var hideParent = function(){
		this.parentNode.classList.add(hiddenClass);
	}

	var setup = function(){
		window.removeEventListener('load',setup);

		document.getElementById('close-edit-box')
			.addEventListener('click',hideParent);

		var rows = document.getElementById('chart')
			.getElementsByTagName('tr');
		for(var i = 0; i < rows.length; i++){
			rows[i].addEventListener('click',loadInput);

			// replace anchor by its enclosed text node
			var anchors = rows[i].getElementsByTagName('a');
			if(anchors.length !== 1) continue;
			var text = anchors[0].firstChild;
			var parent = anchors[0].parentNode;
			while(parent.firstChild) parent.removeChild(parent.firstChild);
			parent.appendChild(text);
		}
	}
	window.addEventListener('load',setup);
})();
