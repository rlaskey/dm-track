<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

class Graphs extends CI_Controller {


	public function __construct()
	{
		parent::__construct();
		$this->load->vars(array('sidebars' => array('input/range')));
	}


	public function glucose()
	{
		$this->load->view('template/top');
		$data = new stdClass;
		$data->js = array('d3','graphs/glucose');
		$this->load->view('template/bottom',$data);
	}


	public function glucose_data()
	{
		$date_fmt = 'Y-m-d H:i';
		$range = $this->session->userdata('range');

		$query = $this->db
			->select('UNIX_TIMESTAMP(time) time,value,level_id')
			->where('time >=',gmdate($date_fmt,strtotime($range['start'])))
			->where('time <=',gmdate($date_fmt,strtotime($range['stop'])))
			->where('user_id',$this->session->userdata('user_id'))
			->get('glucose');
		$numbers = $query->result();
		$query->free_result(); unset($query);

		header('Content-Type: text');
		$tab = "\t";
		echo 'hour',$tab,'glucose',PHP_EOL;
		for ($i = 0, $len = count($numbers); $i < $len; $i++)
		{
			echo date('G',$numbers[$i]->time),'.',
				100 * number_format(date('i',$numbers[$i]->time) / 60,2),
				$tab,
				$numbers[$i]->value;
			if ($i < $len - 1) echo PHP_EOL;
		}
	}
}
