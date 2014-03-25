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
	public function gameresult()
	{

		echo "hi";
		log_message('debug', 'recordgame logged');

	}


	// This will select two goals from the db, create an encrypted string
	// that can be passed to the browser, and used to verify that the game
	// was sanctioned by us.
	//
	public function selectplayers()//TODO: change to private
	{

		$this->load->model('Querydb');

		$highest_row = 			$this->Querydb->highest_row();

		$rand_playerid_1 = 		rand(1,$highest_row);

		$rand_playerid_2 = 		rand(1,$highest_row);

		if($rand_playerid_1 === $rand_playerid_2)
		{

			while($rand_playerid_1 === $rand_playerid_2){

				$rand_playerid_2 = rand(1,$highest_row);

			}

		}

		// TODO: make sure the ip address hasn't voted for any of the goals previously.
		// i.e. make sure either $rand_playerid_ is NOT already in the games table.
		//
		$ip_address = $this->input->ip_address();

		$redo = $this->Querydb->check_against_previous_games($rand_playerid_1,$rand_playerid_2,$ip_address);
		echo $redo."<br>";

		if($redo !== 'success')
		{

			$this->Querydb->make_played_games_object();

			$player_matches = $this->Querydb->select_while_not_equal($rand_playerid_1,$rand_playerid_2,$ip_address);



		}


		// get time
		//
		$t = 					microtime(true);
		$micro = 				sprintf("%06d",($t - floor($t)) * 1000000);
		$d = 					new DateTime( date('Y-m-d H:i:s.'.$micro,$t) );

		$date_string = $d->format("Y-m-d H:i:s.u");

		// create encryption key
		//
		$this->load->library('encrypt');

		$msg = 					rand(1,9999).'42'.rand(1,9999).$date_string.rand(1,9999).'labsrus';

		$encrypted_string = 	$this->encrypt->encode($msg);

		log_message('debug', 'encrypted string:'.$encrypted_string);

		// select info based on the random numbers (playerid) we generated above
		//
		$goal_string_1 = $this->Querydb->select_goal_by_id($rand_playerid_1);

		$goal_string_2 = $this->Querydb->select_goal_by_id($rand_playerid_2);

		//echo $goal_string_1.",".$goal_string_2;
		echo '{"game_data": [{"key": "'.$encrypted_string.'","goal1": "'.$goal_string_1.'","goal2": "'.$goal_string_2.'"}]}';


		ignore_user_abort(true); //at this point the ajax may disconnect if it has a low enough timeout


		// insert the game info we made above into the games table
		//
		$insert_data = array(
		   'player1_id' => $rand_playerid_1,
		   'player2_id' => $rand_playerid_2,
		   'key' 		=> $encrypted_string,
		   'ip'			=> $ip_address,
		   'time'		=> date("Y-m-d H:i:s")
		);

		$this->Querydb->insert_game_data($insert_data);


	}

	public function selectplayersrefactor()
	{

		$ip_address = $this->input->ip_address();

		$this->load->model('Querydb');

		//find out if their ip address is in our table. status may be outofgames, newuser, and 

		$user_status = $this->Querydb->if_user_ip_exists($ip_address);
echo $user_status.'<br>';
		//act on the user_status
		//
		switch ($user_status) {
		    case 0: //user not found; new user

		    	//select two random players
		    	//
		    	$highest_row = 			$this->Querydb->highest_row();

		    	$rand_playerid_1 = 		rand(1,$highest_row);

				$rand_playerid_2 = 		rand(1,$highest_row);

				if($rand_playerid_1 === $rand_playerid_2)
				{

					while($rand_playerid_1 === $rand_playerid_2){

						$rand_playerid_2 = rand(1,$highest_row);

					}

				}

		    	// get time
				//
				$t = 					microtime(true);
				$micro = 				sprintf("%06d",($t - floor($t)) * 1000000);
				$d = 					new DateTime( date('Y-m-d H:i:s.'.$micro,$t) );

				$date_string = 			$d->format("Y-m-d H:i:s.u");

				// create encryption key
				//
				$this->load->library('encrypt');

				$msg = 					rand(1,9999).'42'.rand(1,9999).$date_string.rand(1,9999).'labsrus';

				$encrypted_string = 	$this->encrypt->encode($msg);

				log_message('debug', 'encrypted string:'.$encrypted_string);

				$goal_string_1 = 		$this->Querydb->select_goal_by_id($rand_playerid_1);

				$goal_string_2 = 		$this->Querydb->select_goal_by_id($rand_playerid_2);

				//echo the game data back to the front end
				//
				echo '{"game_data":[{"key": "'.$encrypted_string.'","goal1": "'.$goal_string_1.'","goal2": "'.$goal_string_2.'"}]}';

				ignore_user_abort(true); //at this point the ajax may disconnect if it has a low enough timeout

				//insert the new user into the active_users table
				//
				$insert_data = array(
				   'ip' 	=> $ip_address,
				   'time'	=> date("Y-m-d H:i:s"),
				   'status' => 1,
				);

				$this->Querydb->insert_into_active_users($insert_data);

				//generate all remaining games for the player
				//
				$data = $this->Querydb->select_unplayed_games($rand_playerid_1,$rand_playerid_2);

				//randomize the goals
				//
				shuffle($data["remaininggoals"]);
				
				//insert the remaining goals into remaininggames table
				foreach ($data["remaininggoals"] as $value) 
				{
					echo $value.',';
					//make encryption string

					$this->Querydb->insert_remaining_games($value);
					
				}

		        break;
		    case 1: //games left to play: lets play
		        echo "i equals 1";

		        //check to make sure there is a remaining games, else; look for newly created players

		        //if remaining game: select a remaining game.

		        //delete the game from the remaining games tables

		        //echo game data

		        break;
		    case 2:
		    	//check if there are any new players, if yes, then create ONLY new remaining games from players.
		        //else, then echo that all games are played.
		        echo "all games played";
		        
		        break;
		}


	}

}

/* End of file restcontroller.php */
/* Location: ./application/controllers/restcontroller.php */