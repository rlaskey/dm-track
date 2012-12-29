/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

$(document).ready(function(){
	$('#chart a').replaceWith(function(){return $(this).text();});
	$('#chart tr').on('click',function(){
		$('#edit').load(CI.base+'input/'+$(this).attr('id').replace('-','/'));
		$('#edit-box').removeClass('hidden');
	});
	$('#edit-box span').on('click',function(){$(this).parent().addClass('hidden');});
});
