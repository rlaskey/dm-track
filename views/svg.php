<!DOCTYPE html>
<head>
<meta name="viewport" content="width=device-width, user-scalable=no" />
<base href="<?= \Config::$base ?>" />
<link rel="stylesheet" href="public/css/style.css" />
<link rel="stylesheet" href="public/css/svg.css" />
</head>
<body>
<!-- This Source Code Form is subject to the terms of the Mozilla Public
	- License, v. 2.0. If a copy of the MPL was not distributed with this
	- file, You can obtain one at http://mozilla.org/MPL/2.0/. -->

<main class="no-asides"></main>

<?php if (isset($js)) foreach ($js AS $script): ?>
	<script src="<?= $script ?>"></script>
<?php endforeach; ?>
</body>
