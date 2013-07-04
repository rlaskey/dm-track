<?php $this->load->view('template/top');
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */
?>
<div id="graph"></div>
<script>'use strict';
(function(){
var numbers = [<?php foreach ($numbers AS $number) echo '[',
	date('G',$number->time),'.',
	100 * number_format(date('i',$number->time) / 60,2),
	',',$number->value,
	'], '; ?>];
var loadPlot = function(){
	window.removeEventListener('load',loadPlot);
	$.plot(
		document.getElementById('graph'),
		[{label:"mg/dL",
			data:numbers,
			color:"rgb(42,143,143)",
			points:{show:true}
		}],
		{
			grid:{hoverable:true},
			xaxis:{ticks:24, min:0, max:24},
			yaxis:{ticks:20}
		}
	);
}
window.addEventListener('load',loadPlot);
})();</script>

<?php $this->load->view('template/bottom');
