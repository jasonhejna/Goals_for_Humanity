<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Errorcontroller extends CI_Controller {

	public function senderror(){
		$this->load->view('404_error.php');
	}

}

/* End of file errorcontroller.php */
/* Location: ./application/controllers/errorcontroller.php */