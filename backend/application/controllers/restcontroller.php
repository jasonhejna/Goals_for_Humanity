<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Restcontroller extends CI_Controller {

	// Testing the elologic model
	//
	public function test()
	{
		$this->load->model('Elologic');

		$this->Elologic->setResult(1);

		echo $this->Elologic->getRating1() . ", ";
		echo $this->Elologic->getRating2();
	}


	// Get encryption, and selected answer strings as two http requests.
	// And calculate the player's scores, then update the db with those.
	//
	public function updateresult()
	{

		
		log_message('debug', 'recordgame logged');

	}


	// This will select two goals from the db, create an encrypted string
	// that can be passed to the browser, and used to verify that the game
	// was sanctioned by us.
	//
	public function selectplayers()//TODO: change to private
	{

		$this->load->model('Querydb');

		$highest_row = $this->Querydb->highest_row();

		$rand_playerid_1 = rand(1,$highest_row);

		$rand_playerid_2 = rand(1,$highest_row);

		if($rand_playerid_1 === $rand_playerid_2)
		{

			while($rand_playerid_1 === $rand_playerid_2){

				$rand_playerid_2 = rand(1,$highest_row);

			}

		}

		//select info based on the random numbers (playerid) we generated above
		$goal_string_1 = $this->Querydb->select_goal_by_id($rand_playerid_1);

		$goal_string_2 = $this->Querydb->select_goal_by_id($rand_playerid_2);

		echo $goal_string_1.",".$goal_string_2;

	}

}

/* End of file restcontroller.php */
/* Location: ./application/controllers/restcontroller.php */