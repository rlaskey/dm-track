<?php $this->load->view('template/top');
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

function highlight_glucose(&$value)
{
	if ( ! is_numeric($value)) return;
	if ($value < 80) return ' class="low"';
	if ($value > 140) return ' class="high"';
}

echo '<table><thead><tr><th>day</th><th>time</th><th>value</th><th>notes</th>',
	'</tr></thead>',PHP_EOL,'<tbody id="chart">',PHP_EOL;
$currentDay = '';
foreach ($numbers AS $k => $v)
{
	$thisDay = date('M/j',$v->time);
	if ($thisDay === $currentDay && ! empty($currentDay)) $displayDay = '';
	else
	{
		$displayDay = $thisDay;
		$currentDay = $thisDay;
	}

	echo '<tr class="linky" id="',
		(is_numeric($v->value) ? 'glucose-' : 'insulin-'),
		$v->PK,'"><td>',$displayDay,'</td><td>',date('g:ia',$v->time),
		'</td><td',highlight_glucose($v->value),'>',$v->value,
		'</td><td>',htmlentities($v->notes),'</td></tr>',PHP_EOL;
	unset($thisDay,$displayDay);
}
echo '</tbody></table>',PHP_EOL,
	'<div id="edit-box" ',
	'style="margin-top:2em; border-top:2pt solid rgb(222,222,222)" ',
	'class="hidden">',
	'<span class="right linky">close</span>',PHP_EOL,
	'<div data-controller="/dm/input/" id="edit"></div></div>',PHP_EOL;

$this->load->view('template/bottom');
