<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Team
 *
 * @author Mario
 */

class TeamStandings extends Model {
	
	public $title;
	public $whatteam; 
	public $teamdata; 
	public $content; 
	public $standings;
	public $opp_standings;
	public $points_difference;
	public $standings_data;
	public $scores_data;
	public $news;
	public $team_weather;
	
	function __construct(){
		$this->my_view = new ViewStandings();
		$this->standings = array();
		$this->opp_standings = array();
		$this->points_difference = array();
		$this->setWeather();
	}//__construct
	
	public function returnResult(){
		return $this->standings_data;
	}//returnResult
	
	public function setTeam( $whatteam ){
		/* called by process_index.php
		 sets the variable $whatteam to team1 or team2 */
		$this->whatteam = $whatteam;
	}//setTeam
	
	public function addToStandings( $result, $score ){
		$result_array = explode( "-", $score );
		if( $result === "L" || $result === "W" ){
			if( $result === "L" ){
				array_push( $this->standings, (int)$result_array[1] );
				array_push( $this->opp_standings, (int)$result_array[0] );
				array_push( $this->points_difference, ( (int)$result_array[1] - (int)$result_array[0] ) );
			}else{
				array_push( $this->standings, (int)$result_array[0] );
				array_push( $this->opp_standings, (int)$result_array[1] );
				array_push( $this->points_difference, ( (int)$result_array[0] - (int)$result_array[1] ) );
			}//if
		}//if
	}//addToStandings
	
	public function variance( $aValues, $bSample = false ){
		$fMean = array_sum( $aValues ) / count( $aValues );
		$fVariance = 0.0;
		foreach ( $aValues as $i ){
			$fVariance += pow($i - $fMean, 2);
		}//foreach
		$fVariance /= ( $bSample ? count($aValues) - 1 : count($aValues) );
		return $fVariance;
	}//variance
	
	function standard_deviation( $aValues, $bSample = false ){
		$fVariance = $this->variance( $aValues, $bSample );
		return (float) sqrt( $fVariance );
	}//standard_deviation
	
	public function returnBelowStdDevPercentage( $scores_array, $average_team_score_least ){
		$count = 0;
		foreach( $scores_array as $score ) {
			if( round( $average_team_score_least, 0, PHP_ROUND_HALF_DOWN ) >= $score ){
				$count++;
			}//if
		}//foreach
		$dev_per = $count / count( $scores_array );
		$belowStdDevPercentage = $dev_per * 100;
		return $belowStdDevPercentage;
	}//returnBelowStdDevPercentage
	
	public function returnAboveStdDevPercentage( $scores_array, $average_team_score_max ){
		$count = 0;
		foreach( $scores_array as $score ) {
			if( round( $average_team_score_max, 0, PHP_ROUND_HALF_DOWN ) <= $score ){
				$count++;
			}//if
		}//foreach
		$dev_per = $count / count( $scores_array );
		$aboveStdDevPercentage = $dev_per * 100;
		return $aboveStdDevPercentage;
	}//returnAboveStdDevPercentage
	
	public function returnBelowStdPercentage( $scores_array, $average_team_score_min ){
		$count = 0;
		foreach( $scores_array as $score ) {
			if( round( $average_team_score_min, 0, PHP_ROUND_HALF_DOWN ) > $score ){
				$count++;
			}//if
		}//foreach
		$dev_per = $count / count( $scores_array );
		$belowStdPercentage = $dev_per * 100;
		return $belowStdPercentage;
	}//returnBelowStdPercentage
	
	public function returnAboveStdPercentage( $scores_array, $average_team_score_min ){
		$count = 0;
		foreach( $scores_array as $score ) {
			if( round( $average_team_score_min, 0, PHP_ROUND_HALF_DOWN ) < $score ){
				$count++;
			}//if
		}//foreach
		$dev_per = $count / count( $scores_array );
		$aboveStdPercentage = $dev_per * 100;
		return $aboveStdPercentage;
	}//returnAboveStdPercentage
	
	public function returnPointsDiffString( $points_diff_array ){
		$points_diff_string= "";
		foreach( $points_diff_array as $score ) {
			if( $score < 10 && $score >= 0 ){
				$points_diff_string .= "0" . $score . " ";
			}else{
				$points_diff_string .= $score . " ";
			}//if
		}//foreach
		return $points_diff_string;
	}//returnPointsDiffString
	
	public function returnScoresString( $scores_array ){
		$scores_string = "";
		foreach( $scores_array as $score ) {
			if( $score < 10 ){
				$scores_string .= "0" . $score . " ";
			}else{
				$scores_string .= $score . " ";
			}//if
		}//foreach
		return $scores_string;
	}//returnScoresString
	
	public function returnSortedScoresString( $scores_array ){
		rsort( $scores_array );
		$scores_string = "";
		$arrlength = count( $scores_array );
		for( $x = 0; $x <  $arrlength; $x++ ) {
			if( $scores_array[$x] < 10 ){
				$scores_string .= "0" . $scores_array[$x] . " ";
			}else{
				$scores_string .= $scores_array[$x] . " ";
			}//if
		}//for
		return $scores_string;
	}//returnSortedScoresString
	
	public function computeScores(){
		//team 1
		$average_team_score_min = array_sum( $this->standings ) / count( $this->standings );
		$standard_dev_team1 = $this->standard_deviation( $this->standings );
		$average_team_score_max = $average_team_score_min + $standard_dev_team1;
		$average_team_score_least = $average_team_score_min - $standard_dev_team1;
		//opponent's
		$average_opp_score_min = array_sum( $this->opp_standings ) / count( $this->opp_standings );
		$standard_dev_opp = $this->standard_deviation( $this->opp_standings );
		$average_opp_score_max = $average_opp_score_min + $standard_dev_opp;
		$average_opp_score_least = $average_opp_score_min - $standard_dev_opp;
		$average_score_min_diff = $average_team_score_min - $average_opp_score_min;
		$average_score_max_diff = $average_team_score_max - $average_opp_score_max;
		$average_score_least_diff = $average_team_score_least - $average_opp_score_least;
		//$average_score = ( $average_score_min_diff + $average_score_max_diff ) / 2;
		$scores_string = $this->returnScoresString( $this->standings );
		$opp_scores_string = $this->returnScoresString( $this->opp_standings );
		$sorted_scores_string = $this->returnSortedScoresString( $this->standings );
		$opp_sorted_scores_string = $this->returnSortedScoresString( $this->opp_standings );
		$belowStdDevPercentage = $this->returnBelowStdDevPercentage( $this->standings, $average_team_score_least );
		$aboveStdDevPercentage = $this->returnAboveStdDevPercentage( $this->standings, $average_team_score_max );
		$belowStdPercentage = $this->returnBelowStdPercentage( $this->standings, $average_team_score_min );
		$aboveStdPercentage = $this->returnAboveStdPercentage( $this->standings, $average_team_score_min );
		$points_diff_string = $this->returnPointsDiffString( $this->points_difference );
		$this->scores_data = "<p>Points difference<br>" . $points_diff_string . "<br>Scores<br>" . $scores_string . "<br>" . $opp_scores_string . "<br>Sorted Scores<br>" . $sorted_scores_string . " <br>". $opp_sorted_scores_string . "<br>" . round( $average_team_score_least, 0, PHP_ROUND_HALF_DOWN ) . "<" . round( $average_team_score_min, 0, PHP_ROUND_HALF_DOWN ) . ">" . round( $average_team_score_max, 0, PHP_ROUND_HALF_DOWN ) . " | " . round( $average_opp_score_least, 0, PHP_ROUND_HALF_DOWN ) . "<" . round( $average_opp_score_min, 0, PHP_ROUND_HALF_DOWN ) . ">" . round( $average_opp_score_max, 0, PHP_ROUND_HALF_DOWN ) . " | " . round( $average_score_least_diff, 0, PHP_ROUND_HALF_DOWN ) . "<" .  round( $average_score_min_diff, 0, PHP_ROUND_HALF_DOWN ) . ">" . round( $average_score_max_diff, 0, PHP_ROUND_HALF_DOWN ) . "<br>" . number_format( $belowStdDevPercentage, 0 ) . "% below stddev, " . number_format( $aboveStdDevPercentage, 0 ) . "% above stddev, " . number_format( $belowStdPercentage, 0 ) . "% below stdrd, " . number_format( $aboveStdPercentage, 0 ) . "% above stdrd" . "</p>";
		
	}//computeScores
	
	public function getSchedule(){
		unset( $this->standings, $this->opp_standings, $this->points_difference );
		$this->standings = array();
		$this->opp_standings = array();
		$this->points_difference = array();
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
					$content .= str_replace( $this->my_view->schedule_replace, $replace_with, $this->my_view->schedule_template );
					$this->addToStandings( $result, $score );
				}//foreach
			}//if
		}//foreach
		$this->computeScores();
		$this->content .= $this->scores_data;
		$main_results = str_replace( $this->my_view->main_schedule_replace, $content, $this->my_view->main_schedule_template );
		$this->content .= $main_results;
		unset( $this->scores_data );
	}//getSchedule
	
	public function getStandings(){
		foreach( $this->html->find( 'article[data-module="standings"]' ) as $standings ){
			$this->content .= $standings;
		}//foreach
	}//getStandings
	
	public function getTopPerformers(){
		foreach( $this->html->find( 'article[data-module="performers"]' ) as $performers ){
			$this->content .= $performers;
		}//foreach
	}//getTopPerformers
	
	public function getNews(){
		$news = "<article style=\"clear:both\"><ul style=\"list-style-type:none\">";
		foreach( $this->xml->entry as $entry ){
			$publish = strtotime( $entry->published );
			$published = date( "m-d-Y", $publish );
			$news .= "<li>". $published ." <strong>" . $entry->title . "</strong> " . $entry->summary . "</li>";
		}//foreach
		$news .= "</ul></article>";
		$this->content .= $news;
	}//getNews
	
	public function getVideos(){
		$videos = "<article style=\"clear:both\"><ol style=\"list-style-type:none\">";
		foreach( $this->videos->find( 'ol' ) as $ol ){
			foreach( $ol->find( 'li' ) as $li ){
				if( $li->children(0)->tag == 'a' ){
					continue;
				}else{
					$videos .= $li;
				}//if
			}//foreach
		}//foreach
		$videos .= "</ol></article>";
		$this->content .= $videos;
	}//getVideos
	
	public function getTeamWeather(){
		$this->content .= $this->team_weather;
	}//getTeamWeather
	
	public function buildContent(){
		$this->getSchedule();
		$this->getTeamWeather();
		$this->getStandings();
		$this->getTopPerformers();
		$this->getNews();
		$this->getVideos();
		$this->standings_data .= $this->my_view->returnResult( $this->content );
		$this->content = "";
		// clean up memory
		$this->html->clear();
		$this->videos->clear();
		unset( $this->html, $this->xml, $this->videos );
	}//buildContent
	
	public function getTeamStandings( $url, $team ){
		$this->setHTML( $url );
		$this->title = $team;
	}//getTeamStandings
	
	public function getTeamNews( $url ){
		$this->setXML( $url );
	}//getTeamNews
	
	public function getTeamVideos( $query ){
		$url = "http://search.nfl.com/search?&mc_Video=7145&query=$query&mediatype=Video&mc_Text=10160&mc_Image=4235&sort=date";
		$this->setVideos( $url );
	}//getTeamVideos
	
	public function returnMatch( $words_array, $row ){
		$match = true;
		foreach( $words_array as $word ) {
			if( stripos( $row, $word ) != false && stripos( $row, $word ) >= 0  ){
				continue;
			}else{
				$match = false;	
			}//if
		}//foreach
		return $match;
	}//returnMatch
	
	public function setTeamWeather( $team ){
		$words_array = explode( "+", $team );
		foreach( $this->weather->find( 'table[class=contentTable]' ) as $table ){
			foreach( $table->find( 'tr' ) as $tr ) {
				$match = $this->returnMatch( $words_array, $tr->plaintext );
				if( $match === true ){
					$this->team_weather = $tr->children(4)->children(0)->children(0);
					break;
				}//if
			}//foreach
		}//foreach
	}//setTeamWeather
	
	/* AMERICAN */
	
	public function buffaloBills() {
		$this->getTeamStandings( 'http://espn.go.com/nfl/team/_/name/buf/buffalo-bills', 'Buffalo Bills' );
		$this->getTeamNews( "http://www.nfl.com/rss/rsslanding?searchString=team&abbr=BUF" );
		$this->getTeamVideos( "buffalo+bills" );
		$this->setTeamWeather( "buffalo+bills" );
		$this->buildContent();
	}//buffaloBills
	
	public function miamiDolphins() {
		$this->getTeamStandings( 'http://espn.go.com/nfl/team/_/name/mia/miami-dolphins', 'Miami Dolphins' );
		$this->getTeamNews( "http://www.nfl.com/rss/rsslanding?searchString=team&abbr=MIA" );
		$this->getTeamVideos( "miami+dolphins" );
		$this->setTeamWeather( "miami+dolphins" );
		$this->buildContent();
	}//miamiDolphins
	
	public function newEnglandPatriots() {
		$this->getTeamStandings( 'http://espn.go.com/nfl/team/_/name/ne/new-england-patriots', 'New England Patriots' );
		$this->getTeamNews( "http://www.nfl.com/rss/rsslanding?searchString=team&abbr=NE" );
		$this->getTeamVideos( "new+england+patriots" );
		$this->setTeamWeather( "new+england+patriots" );
		$this->buildContent();
	}//newEnglandPatriots
	
	public function newYorkJets() {
		$this->getTeamStandings( 'http://espn.go.com/nfl/team/_/name/nyj/newyork-jets', 'New York Jets' );
		$this->getTeamNews( "http://www.nfl.com/rss/rsslanding?searchString=team&abbr=NYJ" );
		$this->getTeamVideos( "new-york+jets" );
		$this->setTeamWeather( "new-york+jets" );
		$this->buildContent();
	}//newYorkJets
	
	public function baltimoreRavens() {
		$this->getTeamStandings( 'http://espn.go.com/nfl/team/_/name/bal/baltimore-ravens', 'Baltimore Ravens' );
		$this->getTeamNews( "http://www.nfl.com/rss/rsslanding?searchString=team&abbr=BAL" );
		$this->getTeamVideos( "baltimore+ravens" );
		$this->setTeamWeather( "baltimore+ravens" );
		$this->buildContent();
	}//baltimoreRavens
	
	public function cincinnatiBengals() {
		$this->getTeamStandings( 'http://espn.go.com/nfl/team/_/name/cin/cincinnati-bengals', 'Cincinnati Bengals' );
		$this->getTeamNews( "http://www.nfl.com/rss/rsslanding?searchString=team&abbr=CIN" );
		$this->getTeamVideos( "cincinnati+bengals" );
		$this->setTeamWeather( "cincinnati+bengals" );
		$this->buildContent();
	}//cincinnatiBengals
	
	public function clevelandBrowns() {
		$this->getTeamStandings( 'http://espn.go.com/nfl/team/_/name/cle/cleveland-browns', 'Cleveland Browns' );
		$this->getTeamNews( "http://www.nfl.com/rss/rsslanding?searchString=team&abbr=CLV" );
		$this->getTeamVideos( "cleveland+browns" );
		$this->setTeamWeather( "cleveland+browns" );
		$this->buildContent();
	}//clevelandBrowns
	
	public function pittsburghSteelers() {
		$this->getTeamStandings( 'http://espn.go.com/nfl/team/_/name/pit/pittsburgh-steelers', 'Pittsburgh Steelers' );
		$this->getTeamNews( "http://www.nfl.com/rss/rsslanding?searchString=team&abbr=PIT" );
		$this->getTeamVideos( "pittsburgh+steelers" );
		$this->setTeamWeather( "pittsburgh+steelers" );
		$this->buildContent();
	}//pittsburghSteelers
	
	public function houstonTexans() {
		$this->getTeamStandings( 'http://espn.go.com/nfl/team/_/name/hou/houston-texans', 'Houston Texans' );
		$this->getTeamNews( "http://www.nfl.com/rss/rsslanding?searchString=team&abbr=HOU" );
		$this->getTeamVideos( "houston+texans" );
		$this->setTeamWeather( "houston+texans" );
		$this->buildContent();
	}//houstonTexans
	
	public function indianapolisColts() {
		$this->getTeamStandings( 'http://espn.go.com/nfl/team/_/name/ind/indianapolis-colts', 'Indianapolis Colts' );
		$this->getTeamNews( "http://www.nfl.com/rss/rsslanding?searchString=team&abbr=IND" );
		$this->getTeamVideos( "indianapolis+colts" );
		$this->setTeamWeather( "indianapolis+colts" );
		$this->buildContent();
	}//indianapolisColts
	
	public function jacksonvilleJaguars() {
		$this->getTeamStandings( 'http://espn.go.com/nfl/team/_/name/jax/jacksonville-jaguars', 'Jacksonville Jaguars' );
		$this->getTeamNews( "http://www.nfl.com/rss/rsslanding?searchString=team&abbr=JAX" );
		$this->getTeamVideos( "jacksonville+jaguars" );
		$this->setTeamWeather( "jacksonville+jaguars" );
		$this->buildContent();
	}//jacksonvilleJaguars
	
	public function tennesseeTitans() {
		$this->getTeamStandings( 'http://espn.go.com/nfl/team/_/name/ten/tennessee-titans', 'Tennessee Titans' );
		$this->getTeamNews( "http://www.nfl.com/rss/rsslanding?searchString=team&abbr=TEN" );
		$this->getTeamVideos( "tennessee+titans" );
		$this->setTeamWeather( "tennessee+titans" );
		$this->buildContent();
	}//tennesseeTitans
	
	public function denverBroncos() {
		$this->getTeamStandings( 'http://espn.go.com/nfl/team/_/name/den/denver-broncos', 'Denver Broncos' );
		$this->getTeamNews( "http://www.nfl.com/rss/rsslanding?searchString=team&abbr=DEN" );
		$this->getTeamVideos( "denver+broncos" );
		$this->setTeamWeather( "denver+broncos" );
		$this->buildContent();
	}//denverBroncos
	
	public function kansascityChiefs() {
		$this->getTeamStandings( 'http://espn.go.com/nfl/team/_/name/kc/kansas-city-chiefs', 'Kansas City Chiefs' );
		$this->getTeamNews( "http://www.nfl.com/rss/rsslanding?searchString=team&abbr=KC" );
		$this->getTeamVideos( "kansas+city+chiefs" );
		$this->setTeamWeather( "kansas+city+chiefs" );
		$this->buildContent();
	}//kansascityChiefs
	
	public function oaklandRaiders() {
		$this->getTeamStandings( 'http://espn.go.com/nfl/team/_/name/oak/oakland-raiders', 'Oakland Raiders' );
		$this->getTeamNews( "http://www.nfl.com/rss/rsslanding?searchString=team&abbr=OAK" );
		$this->getTeamVideos( "oakland+raiders" );
		$this->setTeamWeather( "oakland+raiders" );
		$this->buildContent();
	}//oaklandRaiders
	
	public function sandiegoChargers() {
		$this->getTeamStandings( 'http://espn.go.com/nfl/team/_/name/sd/san-diego-chargers', 'San Diego Chargers' );
		$this->getTeamNews( "http://www.nfl.com/rss/rsslanding?searchString=team&abbr=SD" );
		$this->getTeamVideos( "san+diego+chargers" );
		$this->setTeamWeather( "san+diego+chargers" );
		$this->buildContent();
	}//sandiegoChargers
	
	/* NATIONAL */
	
	public function dallasCowboys() {
		$this->getTeamStandings( 'http://espn.go.com/nfl/team/_/name/dal/dallas-cowboys', 'Dallas Cowboys' );
		$this->getTeamNews( "http://www.nfl.com/rss/rsslanding?searchString=team&abbr=DAL" );
		$this->getTeamVideos( "dallas+cowboys" );
		$this->setTeamWeather( "dallas+cowboys" );
		$this->buildContent();
	}//dallasCowboys
	
	public function newyorkGiants() {
		$this->getTeamStandings( 'http://espn.go.com/nfl/team/_/name/nyg/newyork-giants', 'New York Giants' );
		$this->getTeamNews( "http://www.nfl.com/rss/rsslanding?searchString=team&abbr=NYG" );
		$this->getTeamVideos( "new+york+giants" );
		$this->setTeamWeather( "new+york+giants" );
		$this->buildContent();
	}//newyorkGiants
	
	public function philadelphiaEagles() {
		$this->getTeamStandings( 'http://espn.go.com/nfl/team/_/name/phi/philadelphia-eagles', 'Philadelphia Eagles' );
		$this->getTeamNews( "http://www.nfl.com/rss/rsslanding?searchString=team&abbr=PHI" );
		$this->getTeamVideos( "philadelphia+eagles" );
		$this->setTeamWeather( "philadelphia+eagles" );
		$this->buildContent();
	}//philadelphiaEagles
	
	public function washingtonRedskins() {
		$this->getTeamStandings( 'http://espn.go.com/nfl/team/_/name/wsh/washington-redskins', 'Washington Redskins' );
		$this->getTeamNews( "http://www.nfl.com/rss/rsslanding?searchString=team&abbr=WAS" );
		$this->getTeamVideos( "washington+redskins" );
		$this->setTeamWeather( "washington+redskins" );
		$this->buildContent();
	}//washingtonRedskins
	
	public function chicagoBears() {
		$this->getTeamStandings( 'http://espn.go.com/nfl/team/_/name/chi/chicago-bears', 'Chicago Bears' );
		$this->getTeamNews( "http://www.nfl.com/rss/rsslanding?searchString=team&abbr=CHI" );
		$this->getTeamVideos( "chicago+bears" );
		$this->setTeamWeather( "chicago+bears" );
		$this->buildContent();
	}//chicagoBears
	
	public function detroitLions() {
		$this->getTeamStandings( 'http://espn.go.com/nfl/team/_/name/det/detroit-lions', 'Detroit Lions' );
		$this->getTeamNews( "http://www.nfl.com/rss/rsslanding?searchString=team&abbr=DET" );
		$this->getTeamVideos( "detroit+lions" );
		$this->setTeamWeather( "detroit+lions" );
		$this->buildContent();
	}//detroitLions
	
	public function greenbayPackers() {
		$this->getTeamStandings( 'http://espn.go.com/nfl/team/_/name/gb/green-bay-packers', 'Green Bay Packers' );
		$this->getTeamNews( "http://www.nfl.com/rss/rsslanding?searchString=team&abbr=GB" );
		$this->getTeamVideos( "green+bay+packers" );
		$this->setTeamWeather( "green+bay+packers" );
		$this->buildContent();
	}//greenbayPackers
	
	public function minnesotaVikings() {
		$this->getTeamStandings( 'http://espn.go.com/nfl/team/_/name/min/minnesota-vikings', 'Minnesota Vikings' );
		$this->getTeamNews( "http://www.nfl.com/rss/rsslanding?searchString=team&abbr=MIN" );
		$this->getTeamVideos( "minnesota+vikings" );
		$this->setTeamWeather( "minnesota+vikings" );
		$this->buildContent();
	}//minnesotaVikings
	
	public function atlantaFalcons() {
		$this->getTeamStandings( 'http://espn.go.com/nfl/team/_/name/atl/atlanta-falcons', 'Atlanta Falcons' );
		$this->getTeamNews( "http://www.nfl.com/rss/rsslanding?searchString=team&abbr=ATL" );
		$this->getTeamVideos( "atlanta+falcons" );
		$this->setTeamWeather( "atlanta+falcons" );
		$this->buildContent();
	}//atlantaFalcons
	
	public function carolinaPanthers() {
		$this->getTeamStandings( 'http://espn.go.com/nfl/team/_/name/car/carolina-panthers', 'Carolina Panthers' );
		$this->getTeamNews( "http://www.nfl.com/rss/rsslanding?searchString=team&abbr=CAR" );
		$this->getTeamVideos( "carolina+panthers" );
		$this->setTeamWeather( "carolina+panthers" );
		$this->buildContent();
	}//carolinaPanthers
	
	public function neworleansSaints() {
		$this->getTeamStandings( 'http://espn.go.com/nfl/team/_/name/no/new-orleans-saints', 'New Orleans Saints' );
		$this->getTeamNews( "http://www.nfl.com/rss/rsslanding?searchString=team&abbr=NO" );
		$this->getTeamVideos( "new+orleans+saints" );
		$this->setTeamWeather( "new+orleans+saints" );
		$this->buildContent();
	}//neworleansSaints
	
	public function tampabayBuccaneers() {
		$this->getTeamStandings( 'http://espn.go.com/nfl/team/_/name/tb/tampa-bay-buccaneers', 'Tampa Bay Buccaneers' );
		$this->getTeamNews( "http://www.nfl.com/rss/rsslanding?searchString=team&abbr=TB" );
		$this->getTeamVideos( "tampa+bay+buccaneers" );
		$this->setTeamWeather( "tampa+bay+buccaneers" );
		$this->buildContent();
	}//tampabayBuccaneers
	
	public function arizonaCardinals() {
		$this->getTeamStandings( 'http://espn.go.com/nfl/team/_/name/ari/arizona-cardinals', 'Arizona Cardinals' );
		$this->getTeamNews( "http://www.nfl.com/rss/rsslanding?searchString=team&abbr=ARZ" );
		$this->getTeamVideos( "arizona+cardinals" );
		$this->setTeamWeather( "arizona+cardinals" );
		$this->buildContent();
	}//arizonaCardinals
	
	public function stlouisRams() {
		$this->getTeamStandings( 'http://espn.go.com/nfl/team/_/name/stl/st-louis-rams', 'St. Louis Rams' );
		$this->getTeamNews( "http://www.nfl.com/rss/rsslanding?searchString=team&abbr=STL" );
		$this->getTeamVideos( "st+louis+rams" );
		$this->setTeamWeather( "st+louis+rams" );
		$this->buildContent();
	}//stlouisRams
	
	public function sanfrancisco49ers() {
		$this->getTeamStandings( 'http://espn.go.com/nfl/team/_/name/sf/san-francisco-49ers', 'San Francisco 49ers' );
		$this->getTeamNews( "http://www.nfl.com/rss/rsslanding?searchString=team&abbr=SF" );
		$this->getTeamVideos( "san+francisco+49ers" );
		$this->setTeamWeather( "san+francisco+49ers" );
		$this->buildContent();
	}//sanfrancisco49ers
	
	public function seattleSeahawks() {
		$this->getTeamStandings( 'http://espn.go.com/nfl/team/_/name/sea/seattle-seahawks', 'Seattle Seahawks' );
		$this->getTeamNews( "http://www.nfl.com/rss/rsslanding?searchString=team&abbr=SEA" );
		$this->getTeamVideos( "seattle+seahawks" );
		$this->setTeamWeather( "seattle+seahawks" );
		$this->buildContent();
	}//seattleSeahawks
	
	public function getStats( $team_name ){
		/* called by process_index.php
		 runs function with the given team name argument e.g. "Buffalo Bills" will execute buffaloBills() function */
		$functions = array( 'Buffalo Bills' => 'buffaloBills',
						'Miami Dolphins' => 'miamiDolphins',
						'New England Patriots' => 'newEnglandPatriots',
						'New York Jets' => 'newYorkJets',
						'Baltimore Ravens' => 'baltimoreRavens',
						'Cincinnati Bengals' => 'cincinnatiBengals',
						'Cleveland Browns' => 'clevelandBrowns',
						'Pittsburgh Steelers' => 'pittsburghSteelers',
						'Houston Texans' => 'houstonTexans',
						'Indianapolis Colts' => 'indianapolisColts',
						'Jacksonville Jaguars' => 'jacksonvilleJaguars',
						'Tennessee Titans' => 'tennesseeTitans',
						'Denver Broncos' => 'denverBroncos',
						'Kansas City Chiefs' => 'kansascityChiefs',
						'Oakland Raiders' => 'oaklandRaiders',
						'San Diego Chargers' => 'sandiegoChargers',
						'Dallas Cowboys' => 'dallasCowboys',
						'New York Giants' => 'newyorkGiants',
						'Philadelphia Eagles' => 'philadelphiaEagles',
						'Washington Redskins' => 'washingtonRedskins',
						'Chicago Bears' => 'chicagoBears',
						'Detroit Lions' => 'detroitLions',
						'Green Bay Packers' => 'greenbayPackers',
						'Minnesota Vikings' => 'minnesotaVikings',
						'Atlanta Falcons' => 'atlantaFalcons',
						'Carolina Panthers' => 'carolinaPanthers',
						'New Orleans Saints' => 'neworleansSaints',
						'Tampa Bay Buccaneers' => 'tampabayBuccaneers',
						'Arizona Cardinals' => 'arizonaCardinals',
						'St Louis Rams' => 'stlouisRams',
						'San Francisco 49ers' => 'sanfrancisco49ers',
						'Seattle Seahawks' => 'seattleSeahawks'
					);
		foreach( $functions as $key => $value ) {
			if( $team_name === $key ){
				$this->$value();
				break;
			}//if
		}//foreach
	}//getStats
	
}
