 <?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Querydb extends CI_Model {
	
	//find two ranrom players in the ratings table. TODO: select two random rating's
	public function highest_row()
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

	public function select_goal_by_id($playerid)
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

}