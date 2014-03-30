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
		//check KEY and ip to make sure it's a legit game 
			//if success

				//calculate elo

				//update ratings table

				//add one to the a counter of total games played

	}


	// This will select two goals from the db, create an encrypted string
	// that can be passed to the browser, and used to verify that the game
	// was sanctioned by us.
	//
/*	public function selectplayers()
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


	}*/

	public function selectplayersrefactor()
	{
		ob_start();   // create a top output buffer 

		$ip_address = $this->input->ip_address();//TODO: use memcached

		$this->load->model('Querydb');

		//find out if their ip address is in our table. status may be outofgames, newuser, and 

		$user_status = $this->Querydb->if_user_ip_exists($ip_address);

		//act on the user_status
		//
		switch ($user_status) {
		    case 0: //user not found; new user

		    	//insert the new user into the active_users table
				//
				$insert_data = array(
				   'ip' 	=> $ip_address,
				   'time'	=> date("Y-m-d H:i:s"),
				   'status' => 1
				);

				$this->Querydb->insert_into_active_users($insert_data);

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
				log_message('debug', 'gs1:'.$goal_string_1);

				$goal_string_2 = 		$this->Querydb->select_goal_by_id($rand_playerid_2);
				log_message('debug', 'gs2:'.$goal_string_2);

				//echo the game data back to the front end
				//
				echo '{"game_data":[{"key": "'.$encrypted_string.'","goal1": "'.$goal_string_1.'","goal2": "'.$goal_string_2.'"}]}';

				ob_end_flush(); // php.net:'send the contents of the topmost output buffer and turn this output buffer off'
    			//ob_flush();     // for an unknown reason, need another flush

    			//add this game to the remaining games table
				$single_remaining_game = array(
				   'ip' =>				$ip_address,
				   'player1_id' =>		$rand_playerid_1,
				   'player2_id'	=>		$rand_playerid_2,
				   'key' =>				$encrypted_string,
				   'time' =>			date("Y-m-d H:i:s")
				);

				$this->Querydb->insert_remaining_games($single_remaining_game);

				//generate all remaining games for the player
				//
				$data = $this->Querydb->select_unplayed_games($rand_playerid_1,$rand_playerid_2);
				//log_message('debug', 'select uploayed games:'.print_r($data));
				//randomize the goals
				//
				if(empty($data["remaininggoals"][1])){
					log_message('error', 'selecting unplayed games failed');
					exit();
				}
			
				shuffle($data["remaininggoals"]);
				
				//insert the remaining goals into remaininggames table
				//
				$l =							1;
				foreach ($data["remaininggoals"] as $goal) 
				{

					if( $l%2 === 0)
					{

						//make encryption string with time and some random numbers
						//
						$t = 					microtime(true);
						$micro = 				sprintf("%06d",($t - floor($t)) * 1000000);
						$d = 					new DateTime( date('Y-m-d H:i:s.'.$micro,$t) );

						$date_string = 			$d->format("Y-m-d H:i:s.u");
						//log_message('debug', 'forloopdate:'.$date_string);

						// create encryption key
						//
						$msg = 					rand(1,9999).'42'.rand(1,9999).$date_string.rand(1,9999).'labsrus';

						$encrypted_string = 	$this->encrypt->encode($msg);

						//log_message('debug', 'encrypted string:'.$encrypted_string);

						$remaining_game = array(
						   'ip' =>				$ip_address,
						   'player1_id' =>		$goal,
						   'player2_id'	=>		$last_goal,
						   'key' =>				$encrypted_string,
						   'time' =>			date("Y-m-d H:i:s")
						);

						$this->Querydb->insert_remaining_games($remaining_game);

					}
					else
					{
						$last_goal =			$goal;
					}
					$l++;
				}

		        break;
		    case 1: //games left to play

		        //check to make sure there is a remaining game, else; look for newly created goals
		    	$game_data = $this->Querydb->select_delete_remaining_game($ip_address);

		        //if there's a remaining game, else echo all games played
		        if(isset($game_data) && $game_data !== 0)
		        {

			        //echo game data
					echo '{"game_data":[{"key": "'.$game_data["currentgamedata"]["key"].'","goal1": "'.$game_data["currentgamedata"]["goal1"].'","goal2": "'.$game_data["currentgamedata"]["goal2"].'"}]}';

		        	ob_end_flush(); // php.net:'send the contents of the topmost output buffer and turn this output buffer off'
    				//ob_flush();     // for an unknown reason, need another flush

		        }
		        elseif($game_data === 0)
		        {

		        	//check if there are new players and re-calculate
					$this->remaining_games_since($ip_address);


		        }
		        else
		        {

		        	echo "something went wrong";

		        	ob_end_flush(); // php.net:'send the contents of the topmost output buffer and turn this output buffer off'
    				//ob_flush();     // for an unknown reason, need another flush

		        }

		        break;
		    case 2:
		    //check if 5 min (PT5S) has elapsed since the last time no new games were calculated

			    $time_now =			new DateTime(date('Y-m-d H:i:s'));

				$time_now->sub(new DateInterval('PT300S'));

				$time_now = $time_now->format('Y-m-d H:i:s');

			    $pass =		$this->Querydb->select_active_user_greater_time($ip_address,$time_now);

			    if($pass === 0)
			    {
			    	//check if there are new players and re-calculate
					$this->remaining_games_since($ip_address);
			    }
			    else
			    {
			    	echo "cool down";

			    	ob_end_flush(); // php.net:'send the contents of the topmost output buffer and turn this output buffer off'
	    			//ob_flush();     // for an unknown reason, need another flush

	    			//update active_user_time to now TODO fix to update a new table value
					$this->Querydb->update_active_users_time($ip_address,date("Y-m-d H:i:s"));


			    }

		        break;
		}


	}

	private function remaining_games_since($ip_address)
	{
		//generate all remaining games for the player
		//
		$this->load->model('Querydb');

		$user_time = 				$this->Querydb->select_active_user_time($ip_address);

		$data = 					$this->Querydb->select_unplayed_games_since($user_time);


		if($data === 'fail')
		{

			echo "all games played";

			ob_end_flush(); // php.net:'send the contents of the topmost output buffer and turn this output buffer off'
    		//ob_flush();     // for an unknown reason, need another flush

    		//TODO update_refresh_time

    		$this->Querydb->update_active_users_status($ip_address,2);

			exit();

		}

		//update active_user_time to now
		$this->Querydb->update_active_users_time($ip_address,date("Y-m-d H:i:s"));

		//randomize the goals
		//
		shuffle($data["newremaininggoals"]);
		
		//insert the remaining goals into remaininggames table
		//
		$l =							1;
		foreach ($data["newremaininggoals"] as $goal) 
		{
			if( $l%2 === 0)
			{
				//make encryption string with time and some random numbers
				//
				$t = 					microtime(true);
				$micro = 				sprintf("%06d",($t - floor($t)) * 1000000);
				$d = 					new DateTime( date('Y-m-d H:i:s.'.$micro,$t) );
				$date_string = 			$d->format("Y-m-d H:i:s.u");
				//log_message('debug', 'forloopdate:'.$date_string)

				// create encryption key
				//
				$msg = 					rand(1,9999).'42'.rand(1,9999).$date_string.rand(1,9999).'labsrus';
				$encrypted_string = 	$this->encrypt->encode($msg);
				//log_message('debug', 'encrypted string:'.$encrypted_string);

				if($l === 2)
				{
					//look up goal text by id
					//
					$goal_text = 		$this->Querydb->select_goal_by_id($goal);

					$last_goal_text = 	$this->Querydb->select_goal_by_id($last_goal);

					//echo a game to be played
					//
					echo '{"game_data":[{"key": "'.$encrypted_string.'","goal1": "'.$last_goal_text.'","goal2": "'.$goal_text.'"}]}';

					ob_end_flush(); // php.net:'send the contents of the topmost output buffer and turn this output buffer off'
    				//ob_flush();     // for an unknown reason, need another flush

				}

				$remaining_game = array(
				   'ip' =>				$ip_address,
				   'player1_id' =>		$goal,
				   'player2_id'	=>		$last_goal,
				   'key' =>				$encrypted_string,
				   'time' =>			date("Y-m-d H:i:s")
				);
				
				$this->Querydb->insert_remaining_games($remaining_game);

			}
			else
			{
				$last_goal =			$goal;
			}
			$l++;
		}

	}

}

/* End of file restcontroller.php */
/* Location: ./application/controllers/restcontroller.php */