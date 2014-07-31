<?php require BASEPATH.'views/layout/top.php';
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */ ?>
<p>
Thank you for using DM.track! You are now logged out.
Whenever you want to log back in, just <a href="">visit the main page</a>.
</p>

<?php if ( ! empty($persona)) { ?>
<p>NOTE: you signed in via
<a href="https://login.persona.org">Persona</a>.
If you are on a public computer,
you may wish to log out of that service as well.</p>
<?php } ?>

<script>
'use strict';
var persona = <?= isset($_SESSION['persona'])
	? '"'.$_SESSION['persona'].'"' : 'null' ?>
</script>
<?php require BASEPATH.'views/layout/bottom.php';
