<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

class Input extends CI_Controller {


	public function insulin($injection_id = '')
	{
		if ($this->input->post('save'))
		{
			foreach (array('units','type','notes') AS $field) $this->db
				->set($field,$this->input->post($field));
			$this->load->helper('time');
			$this->db
				->set('time',utc($this->input->post('time')))
				->set('email',$this->session->userdata('email'));
			if (empty($injection_id)) $this->db->insert('insulin');
			else $this->db
				->where('injection_id',$injection_id)
				->where('email',$this->session->userdata('email'))
				->update('insulin');
			redirect();
		}

		$query = $this->db
			->where('injection_id',$injection_id)
			->where('email',$this->session->userdata('email'))
			->get('insulin');
		$data = new stdClass();
		$data->object = $query->row();
		$query->free_result(); unset($query);

		if ( ! $this->input->is_ajax_request())
		{
			$this->load->view('template/top');
			$this->load->view('input/insulin',$data);
			$this->load->view('template/bottom');
		}
		else {$this->load->view('input/insulin',$data);}
	}


	public function glucose($level_id = '')
	{
		if ($this->input->post('save'))
		{
			foreach (array('value','notes') AS $field) $this->db
				->set($field,$this->input->post($field));
			$this->load->helper('time');
			$this->db
				->set('time',utc($this->input->post('time')))
				->set('email',$this->session->userdata('email'));
			if (empty($level_id)) $this->db->insert('glucose');
			else $this->db
				->where('level_id',$level_id)
				->where('email',$this->session->userdata('email'))
				->update('glucose');
			redirect();
		}

		$query = $this->db
			->where('level_id',$level_id)
			->where('email',$this->session->userdata('email'))
			->get('glucose');
		$data = new stdClass();
		$data->object = $query->row();
		$query->free_result(); unset($query);

		if ( ! $this->input->is_ajax_request())
		{
			$this->load->view('template/top');
			$this->load->view('input/glucose',$data);
			$this->load->view('template/bottom');
		}
		else {$this->load->view('input/glucose',$data);}
	}


	public function range()
	{
		// just go to the front page if navigated to directly
		if ( ! $this->input->post()) redirect();

		foreach (array_keys($this->config->item('default_range')) AS $i)
		{
			$$i = $this->input->post($i);
			if ($$i === FALSE) $$i = $this->config->item('default_range',$i);
			$$i = trim(strtolower($$i));

			if (strtotime($$i) === FALSE)
				$$i = $this->config->item('default_range',$i);
		}
		$this->session->set_userdata('range',array(
			'start' => $start,'stop' => $stop
		));
		self::handleRedirect();
	}


	public function tz()
	{
		if ( ! $this->input->post()) redirect();
		// if no change, return self::handleRedirect();

		// update DB; save setting
		$this->session->set_userdata('tz','TZ');
		self::handleRedirect();
	}


	private static function handleRedirect()
	{
		$CI =& get_instance();
		if ($CI->input->post('currently'))
			redirect($CI->input->post('currently'));
		else redirect();
	}
}
