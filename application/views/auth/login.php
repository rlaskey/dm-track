<?php $this->load->view('template/top');
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

echo '<p>To sign into this site, you will need to login ',
	'<a href="#" id="personaLogin">using Mozilla Persona</a>.</p>',PHP_EOL; ?>
<script>'use strict';
(function(){
var CI = {"base":"<?php echo site_url() ?>"};
var csrf = {
	name:'<?php echo $this->config->item('csrf_token_name'); ?>',
	value:'<?php echo $this->security->get_csrf_hash(); ?>'
};

var signinLink = document.getElementById('personaLogin');
if(signinLink){signinLink.onclick = function(evt){
	// Requests a signed identity assertion from the user.
	navigator.id.request({
		siteName: 'DM.track',
		returnTo: '<?php echo parse_url(site_url())['path']; ?>',
		oncancel: function(){alert(
			'Sorry, that did not work out :/ Please try again.'
		);}
	});
	return false;
};}

var processResponse = function(){
	switch(this.status){
	case 200:
		window.location = CI.base;
		break;
	default:
		alert('login failure :/ -- '+this.responseText);
	}
}

navigator.id.watch({
	loggedInUser: <?php echo (
		$this->session->userdata('persona') === FALSE
		? 'null'
		: '"'.$this->session->userdata('persona').'"'
	); ?>,
	onlogin:function(assertion){
		var SEPARATOR = '&';
		var data = 'assertion='+assertion+SEPARATOR
			+csrf.name+'='+csrf.value;

		var request = new XMLHttpRequest();
		request.open('POST',CI.base+'auth/persona');
		request.setRequestHeader('X-Requested-With','XMLHttpRequest');
		request.setRequestHeader(
			'Content-Type','application/x-www-form-urlencoded'
		);
		request.send(data);
		request.onload = processResponse;
	},
	onlogout:function(){}
});
})();
</script>
<?php $this->load->view('template/bottom');
