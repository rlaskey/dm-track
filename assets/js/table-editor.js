/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

$(document).ready(function(){
var controller = $('#edit').data('controller');

$('#chart tr').on('click',function(){
	$('#edit').load(controller+$(this).attr('id').replace('-','/'));
	$('#edit-box').removeClass('hidden');
	});
$('#edit-box span').on('click',function(){
	$(this).parent().addClass('hidden');
	});
});
