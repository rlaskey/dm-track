<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

class Charts extends CI_Controller {


	public function __construct()
	{
		parent::__construct();
		$this->load->vars(array('sidebars' => array(
			'input/glucose',
			'input/insulin',
			'input/range',
		)));
	}


	public function index()
	{
		$date_fmt = 'Y-m-d H:i';
		$range = $this->session->userdata('range');
		$when = 'time >= "'.gmdate($date_fmt,strtotime($range['start'])).
			'" AND time <= "'.gmdate($date_fmt,strtotime($range['stop'])).'"';
		$where = ' WHERE email="'.$this->session->userdata('email').'" AND '.
			$when;

		$query = $this->db->query(
			'(SELECT level_id PK,UNIX_TIMESTAMP(time) time,value,notes '.
			'FROM glucose'.$where.') UNION '.
			'(SELECT injection_id PK,UNIX_TIMESTAMP(time) time,'.
			'CONCAT(type,":",units) value,notes FROM insulin'.$where.
			') ORDER BY time'
		);
		$data = new stdClass();
		$data->numbers = $query->result();
		$query->free_result(); unset($query);


		$data->js = array('table-editor');
		$this->load->view('charts/table',$data);
	}
}
