<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

echo '</article>',PHP_EOL,
'<aside>',PHP_EOL;

if (isset($sidebars))
{
	foreach ($sidebars AS $sidebar) {$this->load->view($sidebar);}
	unset($sidebars,$sidebar);
}

echo '</aside><!-- #secondary -->',PHP_EOL,

'<footer>',PHP_EOL,
'&copy; ',(date('Y') > 2012 ? '2012 - ' : ''),date('Y'),', ',
'<a href="http://rlaskey.org/about">Richard Moss Laskey, III</a>',
'</footer>',PHP_EOL,
'</body>';
