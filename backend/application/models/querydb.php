 <?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Querydb extends CI_Model {
	
	//find two ranrom players in the ratings table. TODO: select two random rating's
	function highest_row ()
	{

		$query = $this->db->query('SELECT playerid FROM ratings ORDER BY playerid DESC LIMIT 0, 1');

		if ($query->num_rows() > 0)
		{

		   $row = $query->row();
		   return $row->playerid;

		}
		else
		{
			return 'fail';
		}

		$query->free_result();

	}

	function select_goal_by_id ($playerid)
	{
		$sql = 'SELECT goal FROM ratings WHERE playerid = ?';

		$query = $this->db->query($sql, array($playerid));

		if ($query->num_rows() > 0)
		{

		   $row = $query->row();
		   return $row->goal;

		}
		else
		{
			return 'fail';
		}

		$query->free_result();

	}

	function insert_game_data ($insert_data)
	{


		$this->db->insert('games', $insert_data); 

		// Produces: INSERT INTO mytable (title, name, date) VALUES ('My title', 'My name', 'My date')

/*		$sql = "INSERT INTO games (player1_id,player2_id,key,ip,time) VALUES (?,?,hi,i,k)";

		if($this->db->query($sql, array($rand_playerid_1,$rand_playerid_2))){
			echo "okay";
		}*/

		//$query->free_result();
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
				return 'failtwo';

			}
			else
			{

				$tmp["previousgamedata"]["player1"][] = $player1_id;
				return 'failone';

			}

			$query1->free_result();

			

		}
		else
		{

			$sql1 = 'SELECT id FROM games WHERE player1_id = ? OR player2_id = ? AND ip = ?';

			$query1 = $this->db->query($sql1, array($player2_id,$player2_id,$ip));

			if ($query1->num_rows() > 0)
			{

				return $player2_id;

			}
			else
			{

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
			return $player2_id;

		}
		else
		{

			return 'ultimatefail';

		}

		$query1->free_result();

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
			return $player2_id;

		}
		else
		{

			return 'ultimatefail';

		}

		$query1->free_result();
	}

	function if_user_ip_exists($ip)
	{

		$sql = 'SELECT status FROM active_users WHERE ip = ?';
		
		$query = $this->db->query($sql, array($ip));	

		if ($query->num_rows() > 0)
		{
			$row = $query->row();
			return $row->status;
		}
		else
		{
			return 0;
		}
		$query->free_result();

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
			return $data;

		}
		else
		{

			return 'fail'; //is this fails something is really wrong

		}

		$query->free_result();

	}


}