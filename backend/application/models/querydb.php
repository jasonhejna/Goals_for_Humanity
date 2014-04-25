<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Querydb extends CI_Model {

	function insert_new_goal($new_goal_data)
	{
		$this->db->insert('new_goal', $new_goal_data);
	}

	function check_remaining_game($ip,$key)
	{

		$sql 									= 'SELECT id, player1_id, player2_id FROM remaining_games WHERE ip = ? AND vkey = ? ';

		$query 									= $this->db->query($sql, array($ip,$key));

		if ($query->num_rows() > 0)
		{
			$row = $query->row();
			$query->free_result();

			$data["playerid"][1] =			$row->player1_id;
			$data["playerid"][2] =			$row->player2_id;
			$data["remaininggame"][1] =		$row->id;

			return $data;
		}
		else
		{
			$query->free_result();
			return 0;
		}
		
	}

	function select_goals_boolean($goal)
	{
		//SELECT goal FROM ratings WHERE MATCH (goal) AGAINST ('Explore the Universe.' IN BOOLEAN MODE);
		$sql 									= 'SELECT goal FROM ratings WHERE MATCH (goal) AGAINST (? IN BOOLEAN MODE)';

		$query 									= $this->db->query($sql, array($goal));

		if ($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{
				//$row = $query->row();
				$data["matching_goal"][] = $row->goal;
			}
			$query->free_result();

			return $data;
		}
		else
		{
			$query->free_result();

			return 0;
		}
	}

	function get_ratings_by_playerid($player1_id,$player2_id)
	{

		$sql 									= 'SELECT playerid, rating FROM ratings WHERE playerid = ?';

		$query 									= $this->db->query($sql, array($player1_id));

		if ($query->num_rows() > 0)
		{
			$row = $query->row();
			$query->free_result();

			$player_data["playerid"][1] = $row->playerid;

			$player_data["rating"][1] = $row->rating;
		}
		else
		{
			$query->free_result();
			return 0;
		}

		$sql1 									= 'SELECT playerid, rating FROM ratings WHERE playerid = ?';

		$query1 									= $this->db->query($sql1, array($player2_id));

		if ($query1->num_rows() > 0)
		{
			$row1 = $query1->row();
			$query1->free_result();

			$player_data["playerid"][2] = $row1->playerid;

			$player_data["rating"][2] = $row1->rating;

			return $player_data;
		}
		else
		{
			$query1->free_result();
			return 0;
		}

	}

	function update_ratings($playerid,$data)
	{

		$this->db->where('playerid', $playerid);

		$this->db->update('ratings', $data);

	}

	function delete_game($id)
	{
		//delete the remaining_game
		$this->db->delete('remaining_games', array('id' => $id));
	}
	
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

	function select_remaining_game($ip)
	{

		$sql = 'SELECT * FROM remaining_games WHERE ip = ? LIMIT 0,1';
		
		$query 										= $this->db->query($sql, array($ip));	

		if ($query->num_rows() > 0)
		{
			$row 									= $query->row();

			$query->free_result();

			//delete the remaining_game
			//$this->db->delete('remaining_games', array('id' => $row->id));

			$data["currentgamedata"]["key"][1]		 	= $row->vkey;


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
								'time' 			=> $time
							);

		$this->db->where('ip', $ip);

		$this->db->update('active_users', $data);
	}

	function active_user_lockout_time($ip,$time)
	{
		$data 				= array(
								'lockout_time' => $time
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
		$sql 									= 'SELECT id FROM active_users WHERE ip = ? AND time < ? AND lockout_time < ?';

		$query 									= $this->db->query($sql, array($ip,$time_now,$time_now));

		if ($query->num_rows() > 0)
		{
			$query->free_result();
			return 1;//success
		}
		else
		{
			$query->free_result();
			return 0;
		}

	}

}