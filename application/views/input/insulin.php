<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

if ( ! class_exists('Insulin')) $this->load->model('insulin');
if ( ! function_exists('utc')) $this->load->helper('time');
if ( ! isset($object) || empty($object))
{
	unset($object);
	$object->injection_id = NULL;
	$object->units = NULL;
	$object->time = gmdate('y-m-d H:i');
	$object->type = 'lispro';
	$object->notes = NULL;
}
echo form_open('input/insulin/'.$object->injection_id,'id="insulin-form"'),
	form_fieldset(
		'insulin'.
		($object->injection_id === NULL ? '+' : ':#'.$object->injection_id)
	),PHP_EOL,
	form_input('units',$object->units,'placeholder="0" size="3"'),PHP_EOL,
	form_dropdown(
		'type',array_combine(Insulin::$types,Insulin::$types),$object->type
	),PHP_EOL,
	form_textarea(array(
		'cols' => 8, 'rows' => 2,'name' => 'notes','maxlength' => 83,
		'value' => $object->notes
	)),
	form_input('time',utc($object->time,'from'),'size="14"'),PHP_EOL,
	form_submit('save','save'),PHP_EOL,
	'</fieldset></form>',PHP_EOL;
