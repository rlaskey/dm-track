<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

$range = $this->session->userdata('range');
if ($range === FALSE) $range = $this->config->item('default_range');

echo form_open('input/range'),form_fieldset('range:'),PHP_EOL,

	form_hidden('currently',current_url()),PHP_EOL;

$common = ' size="11" required';
echo form_input('start',$range['start'],'placeholder="1 week ago"'.$common),
	' -&gt; ',PHP_EOL,
	form_input('stop',$range['stop'],'placeholder="today"'.$common),PHP_EOL;
unset($common);

echo form_submit('save','go'),PHP_EOL,

	'</fieldset></form>',PHP_EOL;
