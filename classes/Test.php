<?php
include_once('./simple_html_dom.php');
class Test {
	
	private $html;
	private $main_schedule_template;
	private $schedule_template;
	private $main_schedule_replace = array( '{content}' );
	private $schedule_replace = array( '{logo}', '{playvs}', '{outcome}', '{score}' );
	
	function __construct(){
		$this->main_schedule_template = file_get_contents( './templates/football/schedule_main_table.php' );
		$this->schedule_template = file_get_contents( './templates/football/schedule_results.php' );
	}//__construct
	
	private function scrape( $url ){
		$curl = curl_init(); 
		curl_setopt( $curl, CURLOPT_URL, $url );  
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );  
		curl_setopt( $curl, CURLOPT_CONNECTTIMEOUT, 10 );  
		$str = curl_exec( $curl );  
		curl_close($curl);  
		// create HTML DOM
		$this->html = str_get_html($str);
	}//scrape
	
	public function getSchedule(){
		$content = "";
		foreach( $this->html->find( 'ul' ) as $ul ){
			$regular_season = $ul->children( 0 )->plaintext;
			if( $regular_season === "Regular Season" ){
				foreach( $ul->find( 'li' ) as $li ){
					$play_vs = $li->children( 0 )->children( 1 )->plaintext;
					$logo = $li->children( 0 )->children( 0 )->children( 0 )->src;
					$result = $li->children( 0 )->children( 2 )->children( 0 )->plaintext;
					$score = $li->children( 0 )->children( 2 )->children( 1 )->plaintext;
					$replace_with = array( $logo, $play_vs, $result, $score );
					$content .= str_replace( $this->schedule_replace, $replace_with, $this->schedule_template );
				}//foreach
			}//if
		}//foreach
		$main_results = str_replace( $this->main_schedule_replace, $content, $this->main_schedule_template );
		echo $main_results;
	}//getSchedule
	
	public function getStandings(){
		foreach( $this->html->find( 'article[data-module="standings"]' ) as $standings ){
			echo $standings;
		}//foreach
	}//getStandings
	
	public function getTopPerformers(){
		foreach( $this->html->find( 'article[data-module="performers"]' ) as $performers ){
			echo $performers;
		}//foreach
	}//getTopPerformers
	
	public function scrapeMiamiDolphins(){
		$this->scrape( 'http://espn.go.com/nfl/team/_/name/ten/tennessee-titans' );
		$title = "Miami Dolphins";
		$this->getSchedule();
		$this->getStandings();
		$this->getTopPerformers();
		 // clean up memory
		$this->html->clear();
		unset($this->html);
	}//scrapeMiamiDolphins
	
	public function getVideos( $query ){
		$this->scrape( "http://search.nfl.com/search?&mc_Video=7145&query=$query&mediatype=Video&mc_Text=10160&mc_Image=4235&sort=date" );
		echo "<ol>";
		foreach( $this->html->find( 'ol' ) as $ol ){
			foreach( $ol->find( 'li' ) as $li ){
				if( $li->children(0)->tag == 'a' ){
					continue;
				}else{
					echo $li;
				}//if
			}//foreach
		}//foreach
		echo "</ol>";
	}//getVideos

}//Test


