<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Restcontroller extends CI_Controller {

	// Testing the elologic model
	//
/*	public function test()
	{
		$this->load->model('Elologic');

		$this->Elologic->setResult(0,1700,1100);//0 is tie, 1 is player one, 2 is player two

		echo $this->Elologic->getRating1() . ", ";
		echo $this->Elologic->getRating2();
	}*/

	public function newgoal()
	{
		$goal =			$this->input->post('goal');

		$this->load->helper('url');

		$this->load->helper('captcha');

		$vals = array(
		    'word'	 => 'Randomstr',
		    'img_path'	 => './captcha/',
		    'img_url'	 => base_url().'captcha/',
		    'font_path'	 => './system/fonts/texb.ttf',
		    'img_width'	 => '150',
		    'img_height' => 30,
		    'expiration' => 7200
		    );

		$cap = create_captcha($vals);

		echo $cap['image'];

	}

	public function captcharesponse()
	{
		//$captcha_response =			$this->input->post('captcharesponse');
		//echo $captcha_response;
		echo "asjd";
	}


	// Get encryption, and selected answer strings as two http requests.
	// And calculate the player's scores, then update the db with those.
	//
	public function gameresult()
	{
		ob_start();   // create a top output buffer 

		$key =			$this->input->post('key');

		$player_won =	$this->input->post('player_won');

		$ip_address = $this->input->ip_address();

		if ( ! $this->input->valid_ip($ip_address))
		{
		    
		    echo 'Your ip address is not valid. Sorry about that.';//todo: use header

			ob_end_flush(); // php.net:'send the contents of the topmost output buffer and turn this output buffer off'
    		//ob_flush();     // for an unknown reason, need another flush

		}

		//$player_won =			(int)$player_won;

		if($player_won != "0" && $player_won != "1" && $player_won != "2")
		{
			header("HTTP/1.0 555 Improper post value");

			ob_end_flush(); // php.net:'send the contents of the topmost output buffer and turn this output buffer off'
    		//ob_flush();     // for an unknown reason, need another flush

			exit();
		}

		$this->load->model('Querydb');

		//check KEY and ip to make sure it's a legit game
		$data =				$this->Querydb->check_remaining_game($ip_address,$key);

		if($data === 0)
		{

			header("HTTP/1.0 555 Couldn't find game. Please refresh.");

			ob_end_flush(); // php.net:'send the contents of the topmost output buffer and turn this output buffer off'
    		//ob_flush();     // for an unknown reason, need another flush

    		exit();

		}

		$this->Querydb->delete_game($data["remaininggame"][1]);

		echo 'success';

		ob_end_flush(); // php.net:'send the contents of the topmost output buffer and turn this output buffer off'
    	//ob_flush();     // for an unknown reason, need another flush

		//get rating by player id
		$player_data =			$this->Querydb->get_ratings_by_playerid($data["playerid"][1],$data["playerid"][2]);

		if($player_data === 0)
		{
			exit();
		}

		$this->load->model('Elologic');

		$this->Elologic->setResult($player_won,$player_data["rating"][1],$player_data["rating"][2]);//0 is tie, 1 is player one, 2 is player two

		$rating1 =				$this->Elologic->getRating1();
		$rating2 =				$this->Elologic->getRating2();

    	//update ratings with new scores
    	$update_data =			array(
    								'rating'	=>	$rating1
    							);

    	$this->Querydb->update_ratings($player_data["playerid"][1],$update_data);

    	$update_data2 =			array(
    								'rating'	=>	$rating2
    							);

    	$this->Querydb->update_ratings($player_data["playerid"][2],$update_data2);


    	//TODO add data to game log
    	//date("Y-m-d H:i:s")

	}

	public function selectplayers()
	{
		ob_start();   // create a top output buffer 

		$ip_address = $this->input->ip_address();

		if ( ! $this->input->valid_ip($ip_address))
		{
		    
		    header("HTTP/1.0 555 Your ip address is not valid. Sorry about that.");

			ob_end_flush(); // php.net:'send the contents of the topmost output buffer and turn this output buffer off'
    		//ob_flush();     // for an unknown reason, need another flush



		}

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
				   'ip' 			=> $ip_address,
				   'time'			=> date("Y-m-d H:i:s"),
				   'status' 		=> 1,
				   'lockout_time'	=> date("Y-m-d H:i:s")
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

				$encrypted_string = 	$this->encrypt->sha1($msg);

				//$encrypted_string =		stripslashes($encrypted_string);

				//$encrypted_string = 	urlencode($encrypted_string);
				//log_message('debug', 'encrypted string:'.$encrypted_string);

				$goal_string_1 = 		$this->Querydb->select_goal_by_id($rand_playerid_1);
				//log_message('debug', 'gs1:'.$goal_string_1);

				$goal_string_2 = 		$this->Querydb->select_goal_by_id($rand_playerid_2);
				//log_message('debug', 'gs2:'.$goal_string_2);

				//echo the game data back to the front end
				//
				echo '{"key": "'.$encrypted_string.'","goal1": "'.$goal_string_1.'","goal2": "'.$goal_string_2.'"}';

				ob_end_flush(); // php.net:'send the contents of the topmost output buffer and turn this output buffer off'
    			//ob_flush();     // for an unknown reason, need another flush

    			//add this game to the remaining games table
				$single_remaining_game = array(
				   'ip' =>				$ip_address,
				   'player1_id' =>		$rand_playerid_1,
				   'player2_id'	=>		$rand_playerid_2,
				   'vkey' =>			$encrypted_string,
				   'time' =>			date("Y-m-d H:i:s")
				);

				$this->Querydb->insert_remaining_games($single_remaining_game);

				//generate all remaining games for the player
				//
				$data = $this->Querydb->select_unplayed_games($rand_playerid_1,$rand_playerid_2);
				//log_message('debug', 'select uploayed games:'.print_r($data));
				//randomize the goals
				//
				if(empty($data["remaininggoals"][1]))
				{
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

						$encrypted_string = 	$this->encrypt->sha1($msg);

						//$encrypted_string =		stripslashes($encrypted_string);

						//$encrypted_string = 	urlencode($encrypted_string);

						//log_message('debug', 'encrypted string:'.$encrypted_string);

						$remaining_game = array(
						   'ip' =>				$ip_address,
						   'player1_id' =>		$goal,
						   'player2_id'	=>		$last_goal,
						   'vkey' =>				$encrypted_string,
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
		    	$game_data = $this->Querydb->select_remaining_game($ip_address);

		        //if there's a remaining game, else echo all games played
		        if(isset($game_data) && $game_data !== 0)
		        {

			        //echo game data
			        header("Content-Type: text/html; charset=UTF-8");
					echo '{"key": "'.$game_data["currentgamedata"]["key"][1].'","goal1": "'.$game_data["currentgamedata"]["goal1"].'","goal2": "'.$game_data["currentgamedata"]["goal2"].'"}';

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

		        	header("HTTP/1.0 555 something went wrong");

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

			    if($pass === 1)
			    {
			    	//check if there are new players and re-calculate
					$this->remaining_games_since($ip_address);
			    }
			    else
			    {

			    	header("HTTP/1.0 555 all games played");//cool down time

			    	ob_end_flush(); // php.net:'send the contents of the topmost output buffer and turn this output buffer off'
	    			//ob_flush();     // for an unknown reason, need another flush

	    			//update active_user_lockout_time to now
					$this->Querydb->active_user_lockout_time($ip_address,date("Y-m-d H:i:s"));

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

			header("HTTP/1.0 555 all games played");

			ob_end_flush(); // php.net:'send the contents of the topmost output buffer and turn this output buffer off'
    		//ob_flush();     // for an unknown reason, need another flush

    		//update_refresh_time
    		$this->Querydb->active_user_lockout_time($ip_address,date("Y-m-d H:i:s"));

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
				$encrypted_string = 	$this->encrypt->sha1($msg);

				//$encrypted_string =		stripslashes($encrypted_string);

				//$encrypted_string = 	urlencode($encrypted_string);
				//log_message('debug', 'encrypted string:'.$encrypted_string);

				if($l === 2)
				{
					//look up goal text by id
					//
					$goal_text = 		$this->Querydb->select_goal_by_id($goal);

					$last_goal_text = 	$this->Querydb->select_goal_by_id($last_goal);

					//echo a game to be played
					//
					echo '{"key" : "'.$encrypted_string.'", "goal1" : "'.$last_goal_text.'", "goal2" : "'.$goal_text.'"}';

					ob_end_flush(); // php.net:'send the contents of the topmost output buffer and turn this output buffer off'
    				//ob_flush();     // for an unknown reason, need another flush

				}

				$remaining_game = array(
				   'ip' =>				$ip_address,
				   'player1_id' =>		$goal,
				   'player2_id'	=>		$last_goal,
				   'vkey' =>			$encrypted_string,
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