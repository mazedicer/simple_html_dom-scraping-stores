<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BuffaloBills
 *
 * @author Mario
 */
class BuffaloBills extends Model {
	function __construct(){
		$this->scrape( 'http://www.buffalobills.com/team/team-statistics.html' );
		$this->title = "Buffalo Bills";
		$this->team = new Team();
	}//__construct
	
	public function getData(){
		foreach( $this->html->find( 'tr' ) as $tr ){
			$stat_name = $tr->children( 0 )->plaintext;
			//touchdown data
			if( $stat_name === "TOUCHDOWNS (RUSHING-PASSING-RETURNS-DEFENSIVE)" ){
				$touchdowns_rprd = $tr->children( 1 )->plaintext;
				$opp_rprd = $tr->children( 2 )->plaintext;
				$touchdowns_rprd_array = (explode("-",$touchdowns_rprd));
				$opp_rprd_array = (explode("-",$opp_rprd));
				$this->team->td_rushing = ( $touchdowns_rprd_array[0]*1 );
				$this->team->opp_td_rushing = ( $opp_rprd_array[0]*1 );
				$this->team->td_passing = ( $touchdowns_rprd_array[1]*1 );
				$this->team->opp_td_passing = ( $opp_rprd_array[1]*1 );

			}//if
			//rushing yards data
			if( $stat_name === "TOTAL RUSHING YARDS" ){
				$this->team->rushing_yards = (int)$tr->children( 1 )->plaintext;
				$this->team->opp_rushing_yards = (int)$tr->children( 2 )->plaintext;

			}//if
			//passing yards data
			if( $stat_name === "TOTAL PASSING YARDS" ){
				$this->team->passing_yards = (int)str_replace( ",", "", $tr->children( 1 )->plaintext );
				$this->team->opp_passing_yards = (int)str_replace( ",", "", $tr->children( 2 )->plaintext );
			}//if
		}//foreach

		//get defense data
		foreach( $this->html->find( 'table[class="team-stats-defense"]' ) as $defense_table ){
			foreach( $defense_table->find( 'tr' ) as $tr ){
				$defense_name = $tr->children( 0 )->plaintext;
				if( $defense_name === "TOTAL" ){
					$this->team->defense_total = (int)$tr->children( 1 )->plaintext;
				}//if
				if( $defense_name === "OPPONENTS TOTAL" ){
					$this->team->opp_defense_total = (int)$tr->children( 1 )->plaintext;
				}//if
			}//foreach
		}//foreach
		$this->team->buffalo_bills = $this->team->buildContent();
		$this->team->content .= str_replace( $this->team->my_view->replace, $this->team->buffalo_bills, $this->team->my_view->template );
	}//getData
	
}
