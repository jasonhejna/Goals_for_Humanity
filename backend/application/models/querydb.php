 <?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Querydb extends CI_Model {
	
	//find two ranrom players in the ratings table. TODO: select two random rating's
	function highest_row ()
	{

		$query = $this->db->query('SELECT playerid FROM ratings ORDER BY playerid DESC LIMIT 0, 1');

		if ($query->num_rows() > 0)
		{

		   $row = $query->row();
		   $query->free_result();
		   return $row->playerid;

		}
		else
		{
			$query->free_result();
			return 'fail';
		}

	}

	function select_goal_by_id ($playerid)
	{
		$sql = 'SELECT goal FROM ratings WHERE playerid = ?';

		$query = $this->db->query($sql, array($playerid));

		if ($query->num_rows() > 0)
		{

		   $row = $query->row();
		   $query->free_result();
		   return $row->goal;

		}
		else
		{
			$query->free_result();
			return 'fail';
		}

	}

	function insert_game_data ($insert_data)
	{


		$this->db->insert('games', $insert_data); 


	}

	//for a matching ip address; make sure neither of the two games has been played before by checking their ids
	//this is also looped through until we find a game
	function check_against_previous_games ($player1_id,$player2_id,$ip)
	{

		$sql = 'SELECT id FROM games WHERE player1_id = ? OR player2_id = ? AND ip = ?';

		$query = $this->db->query($sql, array($player1_id,$player1_id,$ip));

		if ($query->num_rows() > 0)
		{
			$sql1 = 'SELECT id FROM games WHERE player1_id = ? OR player2_id = ? AND ip = ?';

			$query1 = $this->db->query($sql1, array($player2_id,$player2_id,$ip));

			if ($query1->num_rows() > 0)
			{

				$tmp["previousgamedata"]["player1"][] = $player1_id;
				$tmp["previousgamedata"]["player2"][] = $player2_id;
				$query1->free_result();
				return 'failtwo';

			}
			else
			{

				$tmp["previousgamedata"]["player1"][] = $player1_id;
				$query1->free_result();
				return 'failone';

			}

		}
		else
		{

			$sql1 = 'SELECT id FROM games WHERE player1_id = ? OR player2_id = ? AND ip = ?';

			$query1 = $this->db->query($sql1, array($player2_id,$player2_id,$ip));

			if ($query1->num_rows() > 0)
			{

				$query1->free_result();
				return $player2_id;

			}
			else
			{

				$query1->free_result();
				return 'pass';

			}

			$query1->free_result();

		}

		$query->free_result();

	}


	function select_while_not_equal($player1_id,$player2_id,$ip)
	{


		$sql = 'SELECT player1_id,player2_id FROM games WHERE player1_id != ? AND player2_id != ? AND player1_id != ? AND player2_id != ? AND ip = ?';
		
		$query = $this->db->query($sql, array($player1_id,$player2_id,$player2_id,$player1_id,$ip));
		
		if ($query->num_rows() > 0)
		{

			foreach ($query->result() as $row)
			{
				//check which games the user has played by looping through 
				$tmp["unmatchesgames"]["player1"][] = $row->player1_id;
				$tmp["unmatchesgames"]["player2"][] = $row->player2_id;
			}
			$query->free_result();
			return $player2_id;

		}
		else
		{
			$query->free_result();
			return 'ultimatefail';

		}

	}

	function make_played_games_object()
	{
		$sql = 'SELECT ';
		
		$query = $this->db->query($sql, array($player1_id,$player2_id,$player2_id,$player1_id,$ip));
		
		if ($query->num_rows() > 0)
		{

			foreach ($query->result() as $row)
			{
				//check which games the user has played by looping through 
				$tmp["remaininggames"]["player1"][] = $row->player1_id;
				$tmp["remaininggames"]["player2"][] = $row->player2_id;
			}
			$query->free_result();
			return $player2_id;

		}
		else
		{

			$query->free_result();
			return 'ultimatefail';

		}

	}

	function if_user_ip_exists($ip)
	{

		$sql = 'SELECT status FROM active_users WHERE ip = ?';
		
		$query = $this->db->query($sql, array($ip));	

		if ($query->num_rows() > 0)
		{
			$row = $query->row();
			$query->free_result();
			return $row->status;
		}
		else
		{
			$query->free_result();
			return 0;
		}

	}

	function insert_into_active_users($insert_data)
	{

		$this->db->insert('active_users', $insert_data); 

	}

	function select_unplayed_games($player1_id,$player2_id)
	{

		$sql = 'SELECT playerid FROM ratings WHERE playerid != ? AND playerid != ?';

		$query = $this->db->query($sql, array($player1_id,$player2_id));

		if ($query->num_rows() > 0)
		{

			foreach ($query->result() as $row)
			{
				//$row = $query->row();
				$data["remaininggoals"][] = $row->playerid;
			}
			$query->free_result();
			return $data;

		}
		else
		{

			$query->free_result();
			return 'fail'; //is this fails something is really wrong

		}

	}

	function insert_remaining_games($insert_data)
	{

		$this->db->insert('remaining_games', $insert_data);

	}

	function select_delete_remaining_game($ip)
	{

		$sql = 'SELECT * FROM remaining_games WHERE ip = ? LIMIT 0,1';
		
		$query 										= $this->db->query($sql, array($ip));	

		if ($query->num_rows() > 0)
		{
			$row 									= $query->row();

			$query->free_result();

			//delete the remaining_game
			$this->db->delete('remaining_games', array('id' => $row->id));

			$data["currentgamedata"]["key"]		 	= $row->key;


			$sql1 									= 'SELECT goal FROM ratings WHERE playerid = ?';
		
			$query1 								= $this->db->query($sql1, array($row->player1_id));

			$row1 									= $query1->row();

			$query1->free_result();

			$data["currentgamedata"]["goal1"]		= $row1->goal;


			$sql2 									= 'SELECT goal FROM ratings WHERE playerid = ?';
		
			$query2 								= $this->db->query($sql2, array($row->player2_id));

			$row2 									= $query2->row();

			$query2->free_result();

			$data["currentgamedata"]["goal2"]		= $row2->goal;

			return $data;

		}
		else
		{
			$query->free_result();

			return 0;
		}

	}

	function update_active_users_status($ip,$status)
	{

		$data 										= array(
												        'status' => $status,
												    );

		$this->db->where('ip', $ip);

		$this->db->update('active_users', $data);

	}

	function select_unplayed_games_since($time)
	{
		
		$sql = 'SELECT playerid FROM ratings WHERE time > ?';

		$query = $this->db->query($sql, array($time));

		if ($query->num_rows() > 1)
		{

			foreach ($query->result() as $row)
			{
				//$row = $query->row();
				$data["newremaininggoals"][] = $row->playerid;
			}
			$query->free_result();
			return $data;

		}
		else
		{

			$query->free_result();
			return "fail";

		}

	}

	function update_active_users_time($ip,$time)
	{
		$data 				= array(
								'time' => $time
							);

		$this->db->where('ip', $ip);

		$this->db->update('active_users', $data);
	}

	function select_active_user_time($ip)
	{

		$sql 									= 'SELECT time FROM active_users WHERE ip = ?';

		$query 									= $this->db->query($sql, array($ip));

		$row 									= $query->row();

		$query->free_result();

		return $row->time;

	}

	function select_active_user_greater_time($ip,$time_now)
	{
		$sql 									= 'SELECT id FROM active_users WHERE ip = ? AND time < ?';

		$query 									= $this->db->query($sql, array($ip,$time_now));

		if ($query->num_rows() > 0)
		{
			$query->free_result();
			return 0;//success
		}
		else
		{
			$query->free_result();
			return 1;
		}
	}

}