<?php

class Model {
	
	public $html;
	public $videos;
	public $weather;
	public $xml;
	
	public function returnTDRushingDiff( $td_rushing, $opp_td_rushing ){
		return $td_rushing - $opp_td_rushing;
	}//returnTDRushingDiff
	
	public function returnTDPassingDiff( $td_passing, $opp_td_passing ){
		return $td_passing - $opp_td_passing;
	}//returnTDPassingDiff
	
	public function returnTDDiff( $td_rushing_diff, $td_passing_diff ){
		return $td_rushing_diff + $td_passing_diff;
	}//returnTDDiff
	
	public function returnDefenseDiff( $defense, $opp_defense ){
		return $defense - $opp_defense;
	}//returnDefenseDiff
	
	public function returnRushingYardsDiff( $rushing_yards, $opp_rushing_yards ){
		return $rushing_yards - $opp_rushing_yards;
	}//returnRushingYardsDiff
	
	public function returnPassingYardsDiff( $passing_yards, $opp_passing_yards ){
		return $passing_yards - $opp_passing_yards;
	}//returnPassingYardsDiff
	
	public function scrape( $url ){
		$curl = curl_init(); 
		curl_setopt( $curl, CURLOPT_URL, $url );  
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );  
		curl_setopt( $curl, CURLOPT_CONNECTTIMEOUT, 10 );  
		$str = curl_exec( $curl );  
		curl_close($curl);  
		return $str;	
	}//scrape
	
	public function setHTML( $url ){
		$str = $this->scrape( $url );
		// create HTML DOM
		$this->html = str_get_html( $str );
	}//setHTML
	
	public function setVideos( $url ){
		$str = $this->scrape( $url );
		// create HTML DOM
		$this->videos = str_get_html( $str );
	}//setVideos
	
	public function setWeather(){
		$str = $this->scrape( "http://www.wunderground.com/sports/NFL/" );
		// create weather
		$this->weather = str_get_html( $str );
	}//setWeather
	
	public function setXML( $url ){
		$str = $this->scrape( $url );
		$this->xml = simplexml_load_string( $str, 'SimpleXMLElement', LIBXML_NOCDATA );
	}//setXML

}//Toysrus


