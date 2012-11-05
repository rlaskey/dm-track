<?php $this->load->view('template/top');
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

echo form_open(),'</form>',PHP_EOL,// plop down the CSRF
	'<p>To sign into this site, you will need to login ',
	'<a href="#" id="personaLogin">using Mozilla Persona</a>.</p>',PHP_EOL; ?>
<script>
var csrf = {name:'<?php echo $this->config->item('csrf_token_name'); ?>'};
csrf.value = $('form').first().find('input[name='+csrf.name+']').val();

var signinLink = document.getElementById('personaLogin');
if(signinLink){signinLink.onclick = function(evt){
	// Requests a signed identity assertion from the user.
	navigator.id.request({
		siteName: 'DM.track',
		/* siteLogo: '<?php $parseURL = parse_url(site_url('assets/image/logo.png'));
			echo $parseURL['path'];
		unset($parseURL); ?>', */
		returnTo: '<?php $parseURL = parse_url(site_url());
			echo $parseURL['path'];
			unset($parseURL); ?>',
		oncancel: function(){alert('Sorry, that did not work out :/ Please try again.');}
	});
	return false;
};}

navigator.id.watch({
	loggedInUser: <?php echo (
		$this->session->userdata('email') === FALSE
		? 'null'
		: '"'.$this->session->userdata('email').'"'
	); ?>,
	onlogin:function(assertion){
		var data = {assertion: assertion};
		data[csrf.name] = csrf.value;
		jQuery.ajax({
			type: 'POST',
			url: CI.base+'auth/persona',
			data: data,
			success: function(res, status, xhr){window.location = CI.base;},
			error: function(res, status, xhr){alert('login failure :/ -- '+res);}
		});
	},
	onlogout:function(){}
});
</script>
<?php $this->load->view('template/bottom');
