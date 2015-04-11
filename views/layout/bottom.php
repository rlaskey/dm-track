<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */ ?>
</main>

<footer>
<div>&copy; <?= date('Y') > 2012 ? '2012 - ' : '' ?><?= date('Y') ?>,
<a href="http://rlaskey.org/about">Richard Moss Laskey, III</a>
</div>

</footer>

<script>
'use strict';
var APP = {
	csrf: {
		name: '<?= \Core\CSRF::name() ?>',
		val: function() {
			if ( ! document.cookie) return;
			var cookies = document.cookie.split('; ');
			for (var i = 0; i < cookies.length; i++) {
				var parts = cookies[i].split('=');
				if (parts[0] === APP.csrf.name) return parts[1];
			}
		}
	}
};
</script>

<?php if (isset($js)): ?>
<section id="js" class="hide">
<?php foreach ($js AS $script):
	$gettextPath = BASEPATH.'views/gettext/js/'.$script.'.php';
	if (file_exists($gettextPath)) include($gettextPath); ?>
	<script src="<?= $script ?>"></script>
<?php endforeach; ?>
</section>
<?php endif; ?>

</body>
