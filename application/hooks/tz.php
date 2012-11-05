<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

function handle_tz()
{
	/* expand to use defaults; use CI timezone_menu, session, etc. */
	date_default_timezone_set('America/New_York');
}
