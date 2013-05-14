<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

if ( ! function_exists('utc')) $this->load->helper('time');
if ( ! isset($object) || empty($object))
{
	unset($object);
	$object = new stdClass();
	$object->level_id = NULL;
	$object->value = NULL;
	$object->time = gmdate('Y-m-d H:i');
	$object->notes = NULL;
}
echo form_open('input/glucose/'.$object->level_id,'id="glucose-form"'),

	form_fieldset(
		'glucose'.($object->level_id === NULL ? '+' : ':#'.$object->level_id)
	),PHP_EOL,

	'<input type="number" name="value" size="3" min="0" placeholder="83" ',
	'required value="',$object->value,'" />',PHP_EOL,

	'<input type="datetime" name="time" size="14" required value="',
	utc($object->time,'from'),
	'" />',PHP_EOL,

	form_textarea(array(
		'cols' => 8, 'rows' => 2,'name' => 'notes','maxlength' => 83,
		'value' => $object->notes
	)),PHP_EOL,

	form_submit('save','save'),PHP_EOL,

	'</fieldset></form>',PHP_EOL;
