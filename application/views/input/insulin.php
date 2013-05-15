<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

if ( ! class_exists('Insulin')) $this->load->model('insulin');
if ( ! function_exists('utc')) $this->load->helper('time');
if ( ! isset($object) || empty($object))
{
	unset($object);
	$object = new stdClass();
	$object->injection_id = NULL;
	$object->units = NULL;
	$object->time = gmdate('Y-m-d H:i');
	$object->type = 'lispro';
	$object->notes = NULL;
}
echo form_open('input/insulin/'.$object->injection_id,'id="insulin-form"'),

	form_fieldset(
		'insulin'.
		($object->injection_id === NULL ? '+' : ':#'.$object->injection_id)
	),PHP_EOL,

	'<input type="number" name="units" size="3" min="0" placeholder="0" ',
	'required value="',$object->units,'" />',PHP_EOL,

	form_dropdown(
		'type',array_combine(Insulin::$types,Insulin::$types),$object->type
	),PHP_EOL,

	form_textarea(array(
		'cols' => 8, 'rows' => 2,'name' => 'notes','maxlength' => 83,
		'value' => $object->notes
	)),

	'<input type="datetime" name="time" size="16" required value="',
	utc($object->time,'from'),
	'" />',PHP_EOL,

	form_submit('save','save'),PHP_EOL,

	'</fieldset></form>',PHP_EOL;
