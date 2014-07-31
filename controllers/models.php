<?php namespace Controllers;
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

class Models
{
	public static function handle()
	{
		if ( ! isset(\Core\Router::$uriParts[1]))
			http_response_code(404) && exit;
		$models = explode('-', \Core\Router::$uriParts[1]);

		\Core\Headers::cache('text/javascript; charset=utf-8'); ?>
'use strict';
var Models = Models || {};

<?php
		foreach ($models AS $model) {
			if ( ! file_exists(// emulate default autoloader
				BASEPATH.'models/'.$model.'.php'
			)) continue;
			$name = ucfirst($model);
			$class = '\Models\\'.$name; ?>
Models.<?= $name ?> = Backbone.Model.extend({
	idAttribute: "<?= call_user_func($class.'::PK') ?>",
	urlRoot: "<?= $model ?>",
	defaults: {<?php foreach ($class::$columns AS $column => $attrs): ?>
"<?= $column ?>": null, <?php endforeach; ?>
"<?= call_user_func($class.'::PK'); ?>": null}
});

<?php
		}
	}
}
