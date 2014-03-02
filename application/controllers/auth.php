<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

class Auth extends CI_Controller {


	public function login()
	{
		if ($this->session->userdata('user_id') !== FALSE) redirect();
		$this->load->view('auth/login');
	}


	public function logout()
	{
		$data = new stdClass();
		$data->persona = $this->session->userdata('persona');
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

		// TODO: pull current_tz from DB; revert to default if not found
		$session_data = array(
			'persona' => $object->email,
			'range' => $this->config->item('default_range'),
		);

		$query = $this->db
			->where('email',$object->email)
			->get('user');
		if ($query->num_rows() === 1)
		{
			$user = $query->row();
			$session_data['user_id'] = $user->user_id;
		}
		else
		{
			$this->load->helper('reorder');
			$session_data['user_id'] = $next_user_id
				= find_next_pk('user','user_id');
			$this->db
				->set('user_id',$next_user_id)
				->set('email',$object->email)
				->insert('user');
		}
		$query->free_result(); unset($query);

		$this->session->set_userdata($session_data);
	}
}
