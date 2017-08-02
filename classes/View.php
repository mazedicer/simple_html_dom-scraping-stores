<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of View
 *
 * @author Mario
 */
class View{
	
	public $main_template;
	public $template;
	public $main_replace = array( '{content}' );
	public $replace = array( '{whatteam}', '{name}', '{points}', '{td_rushing}', '{td_passing}', '{allowed_rushing}', '{allowed_passing}', '{td_rushing_diff}', '{td_passing_diff}', '{td_diff}', '{defense}', '{opp_defense}', '{defense_diff}', '{yards_rushing}', '{opp_yards_rushing}', '{yards_rushing_diff}', '{yards_passing}', '{opp_yards_passing}', '{yards_passing_diff}', '{ats}' );
	
	function __construct(){
		$this->main_template = file_get_contents( './templates/football/main_table.php' );
		$this->template = file_get_contents( './templates/football/team_results.php' );
	}//__construct
	
	public function returnResult( $content ) {
		$main_result = str_replace( $this->main_replace, $content, $this->main_template );
		return $main_result;
	}//returnResult
	
}
