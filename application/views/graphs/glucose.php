<?php $this->load->view('template/top');
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

echo '<div id="graph" class="wide" style="height:37em"></div>',PHP_EOL,
	'<script>$(function(){',PHP_EOL,
	'var numbers = [';
foreach ($numbers AS $number) echo '[',
	date('G',$number->time),'.',
	100 * number_format(date('i',$number->time) / 60,2),
	',',$number->value,'], ';
echo '];',PHP_EOL,
	'$.plot(',
	'$(\'#graph\'), [',
	'{label:"mg/dL",data:numbers,color:"rgb(42,143,143)",points:{show:true}}',
	'],',PHP_EOL,' {',
	'grid:{hoverable:true},',
	'xaxis:{ticks:24, min:0, max:24}, yaxis:{ticks:20}',
	'}',
	');',PHP_EOL,
	'});</script>',PHP_EOL;

$this->load->view('template/bottom');
