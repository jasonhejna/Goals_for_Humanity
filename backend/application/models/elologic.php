<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Elologic extends CI_Model {

    // Protected properties. Use functions below to get & set.
	protected $rating1;
	protected $rating2;
	protected $score1;
	protected $score2;
	//protected $k = 50;
	
	function __construct($rating1 = 1500, $rating2 = 1500)
    {
        parent::__construct();
        $this->rating1 = $rating1;
		$this->rating2 = $rating2;
    }

/*	public function __construct($rating1 = 1500, $rating2 = 1500) {
		$this->rating1 = $rating1;
		$this->rating2 = $rating2;
	}*/

    // setResult($result)
	// Call when you want to update the ratings (after a game, etc.)
	// $result = ELO_RESULT_WIN or ELO_RESULT_LOSS or ELO_RESULT_TIE
	public function setResult($result) {
		$cscore1 = $this->computeScore($this->rating2, $this->rating1);
		$cscore2 = $this->computeScore($this->rating1, $this->rating2);
		if ($result == 1) {
			$this->rating1 = $this->rating1 + ($this->computeK($this->rating1) * (1 - $cscore1));
			$this->rating2 = $this->rating2 + ($this->computeK($this->rating2) * (0 - $cscore2));
		} elseif ($result == 0) {
			$this->rating1 = $this->rating1 + ($this->computeK($this->rating1) * (0 - $cscore1));
			$this->rating2 = $this->rating2 + ($this->computeK($this->rating2) * (1 - $cscore2));
		} else {
			// Assume tie
			$this->rating1 = $this->rating1 + ($this->computeK($this->rating1) * (0.5 - $cscore1));
			$this->rating2 = $this->rating2 + ($this->computeK($this->rating2) * (0.5 - $cscore2));
		}
	}
	
	protected function computeScore($rating1, $rating2) {
	        return (1 / (1 + pow(10, ($rating1 - $rating2) / 400)));
	}
	
	//computeK($rating)
	// K-value determines the mobility of ratings (the maximum change
	// in rating per game). Feel free to edit this function to return
	// different K-values based on the player's rating.
	// Default K-value is 50
	protected function computeK($rating) {
		return 50;
	}
	
	public function getScore1() {
                $this->score1 = computeScore($this->rating2, $this->rating1);
		return $this->score1;
	}
	
	public function getScore2() {
                $this->score2 = computeScore($this->rating1, $this->rating2);
		return $this->score2;
	}
	
	public function getRating1() {
		return $this->rating1;
	}
	
	public function getRating2() {
		return $this->rating2;
	}
}