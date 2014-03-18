<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Restcontroller extends CI_Controller {

	public function test(){
		$this->load->model('Elologic');

		$this->Elologic->setResult(1);

		echo $this->Elologic->getRating1().", ";
		echo $this->Elologic->getRating2();
	}
}

/* End of file restcontroller.php */
/* Location: ./application/controllers/restcontroller.php */