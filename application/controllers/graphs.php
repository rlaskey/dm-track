<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

class Graphs extends CI_Controller {


	public function __construct()
	{
		parent::__construct();
		$this->load->vars(array(
			'jquery' => array('flot'),
			'sidebars' => array('input/range'),
		));
	}


	public function glucose()
	{
		$date_fmt = 'Y-m-d H:i';
		$range = $this->session->userdata('range');

		$query = $this->db
			->select('UNIX_TIMESTAMP(time) time,value,level_id')
			->where('time >=',gmdate($date_fmt,strtotime($range['start'])))
			->where('time <=',gmdate($date_fmt,strtotime($range['stop'])))
			->where('email',$this->session->userdata('email'))
			->get('glucose');
		$data = new stdClass();
		$data->numbers = $query->result();
		$query->free_result(); unset($query);

		$this->load->view('graphs/glucose',$data);
	}
}
