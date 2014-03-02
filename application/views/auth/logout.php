<?php $this->load->view('template/top');
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

echo '<p>Thank you for visiting! You are now logged out. ',
	'Whenever you want to log back in, just visit ',anchor(site_url()),
	'.</p>',PHP_EOL;

if ($persona !== FALSE) echo '<p>NOTE: you signed in via ',
	anchor('https://login.persona.org','Persona'),'. ',
	'If you are on a public computer, you may wish to ',
	'log out of that service as well.</p>',
	PHP_EOL; ?>
<script>'use strict';
navigator.id.watch({
	loggedInUser: <?php
echo ($persona === FALSE ? 'null' : '"'.$persona.'"'); ?>,
	onlogin:function(){},
	onlogout:function(){},
	onready:function(){navigator.id.logout();}
});
</script>
<?php $this->load->view('template/bottom');
