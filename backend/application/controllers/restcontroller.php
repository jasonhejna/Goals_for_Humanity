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

	private $data = array();

	function __construct()
	{
		parent::__construct();
		$ip_address = $this->input->ip_address();
		if ( ! $this->input->valid_ip($ip_address))
		{
		    
		    header("HTTP/1.1 555 Your ip address is not valid. Sorry about that.");

			ob_end_flush();

			exit();

		}
		//block too many requests
		//ip lookup for a potential ban, or captcha


		//also make a random number like 555 to use in our headers. In order to fuck
		//with script kiddies

	}


	// echo goals in order of highest rank to lowest rank. The frontend specifies the
	// number of returned items. 0 to 14 would return to top 15 goals. Also, there's a max
	// of 24 goals sent per request.
	public function echogoals()
	{
		ob_start();

		$start			= $this->input->post('start');

		$num_results	= $this->input->post('num_results');

		if(!is_numeric($start) && !is_numeric($num_results))
		{
			header("HTTP/1.1 555 Both post values must be integer numbers");

			ob_end_flush();

			exit();
		}

		//make sure we aren't selecting more than 24 goals
		if($num_results > 36){
			header("HTTP/1.1 555 You can't request more than 36 goals");

			ob_end_flush();

			exit();
		}

		if($start < 0)
		{
			header("HTTP/1.1 555 the start value must be zero or greater");

			ob_end_flush();

			exit();
		}

		$this->load->model('Querydb');

		log_message('debug', '!controller!start:'.$start.',num_results:'.$num_results);

		//Select our goals
		$data				= $this->Querydb->select_goals_orderby_rating($start,$num_results);

		if($data === 0)
		{
			header("HTTP/1.1 555 something went wrong. Check back later");

			ob_end_flush();

			exit();
		}
		if($data === 'limiterror')
		{
			header("HTTP/1.1 555 one or more of the post values was larger than the number of goals");

			ob_end_flush();

			exit();
		}

		//parse the data into json
		$found_goals_json	= '';

		$ie					= 0;
		foreach ($data['rating'] as $key => $value) {
			$found_goals_json .= '{"rank":"'.$data['rating'][$ie].'","goal":"'.$data['goal'][$ie].'","date":"'.$data['time'][$ie].'"},';
			$ie++;
		}

		$found_goals_json 	= rtrim($found_goals_json, ',');

		//$maxGoals			= $data['max'] - 1; //subtract one since we start our goal count at zero

		echo '{"success":1,"maxgoal":"'.$data['max'].'","num_results":'.$ie.',"goals":['.$found_goals_json.']}';

		ob_end_flush();

	}

	public function newgoal()
	{

		ob_start();   // create a top output buffer 

		$ip_address = $this->input->ip_address();

		if ( ! $this->input->valid_ip($ip_address))
		{
		    
		    header("HTTP/1.1 555 Your ip address is not valid. Sorry about that.");

			ob_end_flush();

			exit();

		}

		$goal =			$this->input->post('goal');

		//check goal for 256 chars, has words, and those words don't exceed 30 characters
		$goal_char_count =		strlen($goal);

		if($goal_char_count < 5)
		{
			header("HTTP/1.1 555 Goal must be greater than five characters");

			ob_end_flush();

			exit();
		}

		if($goal_char_count > 256)
		{
			header("HTTP/1.1 555 Goal must be less than 256 characters");

			ob_end_flush();

			exit();
		}

		$goal					= preg_replace('!\s+!', ' ', $goal);//replace multiple space with single space

		$goal_words				= explode(" ", $goal);

		foreach ($goal_words as $key => $word) {

			log_message('debug', 'goal_words:'.$word);

			$word_char_count =		strlen($word);

			if($word_char_count > 22){
				header("HTTP/1.1 555 Goal can't have words with more than 22 characters");

				ob_end_flush();

				exit();
			}

		}

		$this->load->model('Querydb');

		$goal_alpha_numeric = preg_replace('/[^a-z0-9]+/i', '%', $goal);

		$data				= $this->Querydb->select_exact_goal('ratings',$goal_alpha_numeric);

		if(isset($data["matching_goal"][0]))
		{
			log_message('debug', 'quotes_match:'.$data["matching_goal"][0]);
			
			header("HTTP/1.1 555 We already have your goal");

			ob_end_flush();

			exit();
		}

		$data				= $this->Querydb->select_exact_goal('new_goal',$goal_alpha_numeric);

		if(isset($data["matching_goal"][0]))
		{

			//log_message('debug', 'quotes_match2:'.$data["matching_goal"][0]);

			header("HTTP/1.1 555 We already have your goal");

			ob_end_flush();

			exit();
		}

		//generate a verify_code
		//make encryption string with time and some random numbers
		//
		$t 						= microtime(true);
		$micro					= sprintf("%06d",($t - floor($t)) * 1000000);
		$d						= new DateTime( date('Y-m-d H:i:s.'.$micro,$t) );
		$date_string			= $d->format("Y-m-d H:i:s.u");

		$random_string 			= '96canhaz'.rand(1,9999).rand(1,9999).'che6se'.$date_string.rand(1,9999);

		$this->load->library('encrypt');

		$verify_code			= $this->encrypt->sha1($random_string);

		//generate a captcha
		$captcha_code			= substr(uniqid('', true), -5);
		$captcha_url			= $this->make_captch_image($captcha_code);

		$data =			$this->Querydb->select_goals_boolean("'".$goal."'");

		if($data !== 0)
		{
			$found_goals_json = '';
			$ie=0;
			foreach($data["matching_goal"] as $key => $matching_goal) {
				$found_goals_json .= '{"'.$ie.'":"'.$matching_goal.'"},';
				$ie++;
			}

			$found_goals_json = 		rtrim($found_goals_json, ',');

			echo '{"verify_code":"'.$verify_code.'","captchaUrl":"'.$captcha_url.'","similarGoals":['.$found_goals_json.']}';
		}
		else
		{
			echo '{"verify_code":"'.$verify_code.'","captchaUrl":"'.$captcha_url.'","similarGoals":"null"}';
		}

		ob_end_flush();
			
		//insert data into the db
		$new_goal = array(
			'verify_code'	=>	$verify_code,
			'captcha_code'	=>	$captcha_code,
			'ip_address'	=>	$ip_address,
			'goal'			=>	$goal,
			'status'		=>	1
			);

		$this->Querydb->insert_new_goal($new_goal);

	}

	public function verifycaptcha()
	{
		ob_start();

		$ip_address = $this->input->ip_address();

		if ( ! $this->input->valid_ip($ip_address))
		{
			header("HTTP/1.1 555 Your ip address is not valid. Sorry about that.");

			ob_end_flush();

			exit();
		}

		$captcharesponse			= $this->input->post('userDefinedCaptcha');

		$verify_code				= $this->input->post('verify_code');

		if(!isset($verify_code))
		{
			header("HTTP/1.1 555 The verify_code was not set in your post.");

			ob_end_flush();

			exit();
		}

		//verify it's in the new_goal table
		$this->load->model('Querydb');

		$works 						= $this->Querydb->update_new_goal_status_confirmed($verify_code,$captcharesponse,$ip_address);

		//goal found from captcha code response, and ip_address
		if($works === 1)
		{
			echo '{"success":"1"}';

			ob_end_flush();

			exit();
		}
		//goal not found

		$captcha_code			= substr(uniqid('', true), -5);
		$captcha_url			= $this->make_captch_image($captcha_code);

		echo '{"success":"0","verify_code":"'.$verify_code.'","captchaUrl":"'.$captcha_url.'"}';

		ob_end_flush();

		//update new_goal table with new captcha code
		//TODO: add a verify code
		$this->Querydb->update_new_goal_captcha_code($verify_code,$captcha_code,$ip_address);

	}

	// Get encryption, and selected answer strings as two http requests.
	// And calculate the player's scores, then update the db with those.
	//
	public function gameresult()
	{
		ob_start();   // create a top output buffer 

		$ip_address = $this->input->ip_address();

		if ( ! $this->input->valid_ip($ip_address))
		{
		    
		    header("HTTP/1.1 555 Your ip address is not valid. Sorry about that.");

			ob_end_flush();

			exit();

		}

		$key =			$this->input->post('key');

		$game_result =	$this->input->post('game_result');

		//$game_result =			(int)$game_result;

		if($game_result != "goal1" && $game_result != "goal2" && $game_result != "tiegame" && $game_result != "skipgame")
		{
			header("HTTP/1.1 555 Improper post value");

			ob_end_flush(); // php.net:'send the contents of the topmost output buffer and turn this output buffer off'
    		//ob_flush();     // for an unknown reason, need another flush

			exit();
		}

		$this->load->model('Querydb');

		//check KEY and ip to make sure it's a legit game
		$data =				$this->Querydb->check_remaining_game($ip_address,$key);

		if($data === 0)
		{

			header("HTTP/1.1 555 Couldn't find game. Please refresh.");

			ob_end_flush(); // php.net:'send the contents of the topmost output buffer and turn this output buffer off'
    		//ob_flush();     // for an unknown reason, need another flush

    		exit();

		}

		$this->Querydb->delete_game($data["remaininggame"][1]);

		echo 'success';

		ob_end_flush(); // php.net:'send the contents of the topmost output buffer and turn this output buffer off'
    	//ob_flush();     // for an unknown reason, need another flush

    	if($game_result === "skipgame")
		{
			exit();
		}

		//get rating by player id
		$player_data =			$this->Querydb->get_ratings_by_playerid($data["playerid"][1],$data["playerid"][2]);

		if($player_data === 0)
		{
			exit();
		}

		$this->load->model('Elologic');

		$this->Elologic->setResult($game_result,$player_data["rating"][1],$player_data["rating"][2]);//0 is tie, 1 is player one, 2 is player two

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
		    
		    header("HTTP/1.1 555 Your ip address is not valid. Sorry about that.");

			ob_end_flush();

			exit();

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

				$msg = 					'r0'.rand(1,9999).'42'.rand(1,9999).$date_string.rand(1,9999).'labsr1us';

				$encrypted_string = 	$this->encrypt->sha1($msg);

				//add salt


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

		        break;
		    case 1: //games left to play

		        //check to make sure there is a remaining game, else; look for newly created goals
		    	$game_data = $this->Querydb->select_remaining_game($ip_address);

		        //if there's a remaining game, else echo all games played
		        if(isset($game_data) && $game_data !== 0)
		        {

			        //echo game data
			        //header("Content-Type: text/html; charset=UTF-8");
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

		        	header("HTTP/1.1 555 something went wrong");

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
			    	if($ip_address == ''){

			    	}
			    	$this->demo_delete_users($ip_address);
			    	//check if there are new players and re-calculate
					$this->remaining_games_since($ip_address);
			    }
			    else
			    {

			    	header("HTTP/1.1 555 all games played");//cool down time

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

			header("HTTP/1.1 555 all games played");

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

					ob_end_flush();

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

	private function make_captch_image($rand_chars)
	{
		$this->load->helper('url');

		$this->load->helper('captcha');

		$vals = array(
		    'word'	 => $rand_chars,
		    'img_path'	 => './captcha/',
		    'img_url'	 => base_url().'captcha/',
		    'font_path'	 => './system/fonts/texb.ttf',
		    'img_width'	 => '160',
		    'img_height' => 30,
		    'expiration' => 7200
		    );

		$cap = create_captcha($vals);

		$subject =			$cap['image'];
		$pattern =			'~["](.+?)["]~';
		preg_match($pattern, $subject, $matches, PREG_OFFSET_CAPTURE, 3);
		$captcha_url		= $matches[1][0];

		if($this->config->item('environment') === 'production')
		{
			$captcha_url		= substr_replace($captcha_url, 'https', 0, 4);
		}

		return $captcha_url;
	}

}

/* End of file restcontroller.php */
/* Location: ./application/controllers/restcontroller.php */