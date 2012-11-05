<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

class Auth extends CI_Controller {


	public function login()
	{
		if ($this->session->userdata('email') !== FALSE) redirect();
		$data = new stdClass();
		$data->jquery = array();
		$this->load->view('auth/login',$data);
	}


	public function logout()
	{
		$data = new stdClass();
		$data->email = $this->session->userdata('email');
		$this->session->sess_destroy();
		$this->load->view('auth/logout',$data);
	}


	public function persona()
	{
		$c = curl_init();
		curl_setopt_array($c,array(
			CURLOPT_SSL_VERIFYPEER => TRUE,
			CURLOPT_URL => 'https://verifier.login.persona.org/verify',
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_POST => 2,
			CURLOPT_POSTFIELDS => 'assertion='.
			$this->input->post('assertion').
			'&audience='.urlencode($this->config->item('audience'))
		));
		$result = curl_exec($c);
		if ($result === FALSE) show_error('Problem connecting to Persona :/');
		curl_close($c);

		$object = json_decode($result);
		if ($object->audience !== $this->config->item('audience'))
			show_error('Wrong audience?!');
		if ($object->status !== 'okay')
			show_error('Persona status is not okay :/');

		$session_data = array(
			'email' => $object->email,
			'range' => $this->config->item('default_range'),
		);
		$this->session->set_userdata($session_data);
	}
}
