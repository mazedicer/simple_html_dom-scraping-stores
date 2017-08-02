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
class ViewStandings {
	
	public $main_schedule_template;
	public $schedule_template;
	public $standings_template;
	public $standings_replace = array( '{content}' );
	public $main_schedule_replace = array( '{content}' );
	public $schedule_replace = array( '{logo}', '{playvs}', '{outcome}', '{score}' );
	
	function __construct(){
		$this->main_schedule_template = file_get_contents( './templates/football/schedule_main_table.php' );
		$this->schedule_template = file_get_contents( './templates/football/schedule_results.php' );
		$this->standings_template = file_get_contents( './templates/football/standings_main.php' );
	}//__construct
	
	public function returnResult( $content ) {
		$main_result = str_replace( $this->standings_replace, $content, $this->standings_template );
		return $main_result;
	}//returnResult
	
}
