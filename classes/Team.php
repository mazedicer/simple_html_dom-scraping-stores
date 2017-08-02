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

class Team extends Model {
	
	public $title; public $whatteam; public $teamdata; public $teamdataimp; public $td_rushing; public $opp_td_rushing; public  $td_passing; public $opp_td_passing; public $rushing_yards; public $opp_rushing_yards; public $passing_yards; public $opp_passing_yards; public $defense_total; public $opp_defense_total; public $content; public $buffalo_bills; public $miami_dolphins; public $newengland_patriots; public $newyork_jets; public $baltimore_ravens; public $cincinnati_bengals; public $cleveland_browns; public $pittsburgh_steelers; public $houston_texans; public $indianapolis_colts; public $jacksonville_jaguars; public $tennessee_titans; public $denver_broncos; public $kansascity_chiefs; public $oakland_raiders; public $sandiego_chargers; public $dallas_cowboys; public $newyork_giants; public $philadelphia_eagles; public $washington_redskins;public $chicago_bears; public $detroit_lions; public $greenbay_packers; public $minnesota_vikings; public $atlanta_falcons; public $carolina_panthers; public $neworleans_saints; public $tampabay_buccaneers; public $arizona_cardinals; public $stlouis_rams; public $sanfrancisco_49ers; public $seattle_seahawks;
	
	function __construct(){
		$this->my_view = new View();
	}//__construct
	
	public function returnResult(){
		return $this->my_view->returnResult( $this->content ) . "XAAAAX" . $this->teamdataimp;
	}//returnResult
		
	public function buildContent(){
		$td_rushing_diff = $this->returnTDRushingDiff( $this->td_rushing, $this->opp_td_rushing );
		$td_passing_diff = $this->returnTDPassingDiff( $this->td_passing, $this->opp_td_passing );
		$td_diff = $this->returnTDDiff( $td_rushing_diff, $td_passing_diff );
		$defense_diff = $this->returnDefenseDiff( $this->defense_total, $this->opp_defense_total );
		$yards_rushing_diff = $this->returnRushingYardsDiff( $this->rushing_yards, $this->opp_rushing_yards );
		$yards_passing_diff = $this->returnPassingYardsDiff( $this->passing_yards, $this->opp_passing_yards );
		$this->teamdata = array( $this->whatteam, $this->title, " ", $this->td_rushing, $this->td_passing, $this->opp_td_rushing, $this->opp_td_passing, $td_rushing_diff, $td_passing_diff, $td_diff, $this->defense_total, $this->opp_defense_total, $defense_diff, $this->rushing_yards, $this->opp_rushing_yards, $yards_rushing_diff, $this->passing_yards, $this->opp_passing_yards, $yards_passing_diff, " " );
		$this->content .= str_replace( $this->my_view->replace, $this->teamdata, $this->my_view->template );
		$this->teamdataimp .=  implode( "|", $this->teamdata ) . "XMMMMX";
		// clean up memory
		$this->html->clear();
		unset( $this->html );
	}//returnResults
	
	public function setTeam( $whatteam ){
		$this->whatteam = $whatteam;
	}//setTeam
	
	public function setTouchdownDataA( $stat_name, $tr ){
		if( $stat_name === "TOUCHDOWNS (RUSHING-PASSING-RETURNS-DEFENSIVE)" ){
			$touchdowns_rprd = $tr->children( 1 )->plaintext;
			$opp_rprd = $tr->children( 2 )->plaintext;
			$touchdowns_rprd_array = (explode("-",$touchdowns_rprd));
			$opp_rprd_array = (explode("-",$opp_rprd));
			$this->td_rushing = ( $touchdowns_rprd_array[0]*1 );
			$this->opp_td_rushing = ( $opp_rprd_array[0]*1 );
			$this->td_passing = ( $touchdowns_rprd_array[1]*1 );
			$this->opp_td_passing = ( $opp_rprd_array[1]*1 );
		}//if
	}//setTouchdownDataA
	
	public function setRushingDataA( $stat_name, $tr ){
		if( $stat_name === "TOTAL RUSHING YARDS" ){
			$this->rushing_yards = (int)str_replace( ",", "", $tr->children( 1 )->plaintext );
			$this->opp_rushing_yards = (int)str_replace( ",", "", $tr->children( 2 )->plaintext );

		}//if
	}//setRushingDataA
	
	public function setPassingDataA( $stat_name, $tr ){
		if( $stat_name === "TOTAL PASSING YARDS" ){
			$this->passing_yards = (int)str_replace( ",", "", $tr->children( 1 )->plaintext );
			$this->opp_passing_yards = (int)str_replace( ",", "", $tr->children( 2 )->plaintext );
		}//if
	}//setPassingDataA
	
	public function setDefenseDataA(){
		foreach( $this->html->find( 'table[class="team-stats-defense"]' ) as $defense_table ){
			foreach( $defense_table->find( 'tr' ) as $tr ){
				$defense_name = $tr->children( 0 )->plaintext;
				if( $defense_name === "TOTAL" ){
					$this->defense_total = (int)$tr->children( 1 )->plaintext;
				}//if
				if( $defense_name === "OPPONENTS TOTAL" ){
					$this->opp_defense_total = (int)$tr->children( 1 )->plaintext;
				}//if
			}//foreach
		}//foreach
	}//setDefenseDataA
	
	public function getTeamDataA( $url, $team ){
		$this->setHTML( $url );
		$this->title = $team;
		foreach( $this->html->find( 'tr' ) as $tr ){
			$stat_name = $tr->children( 0 )->plaintext;
			//touchdown data
			$this->setTouchdownDataA( $stat_name, $tr );
			//rushing yards data
			$this->setRushingDataA( $stat_name, $tr );
			//passing yards data
			$this->setPassingDataA( $stat_name, $tr );
		}//foreach
		//get defense data
		$this->setDefenseDataA();
	}//getTeamDataA
	
	public function setTouchdownDataB( $stat_name, $tr ){
		if( $stat_name === "Touchdowns (Rushing - Passing - Returns - Defensive)" ){
			$touchdowns_rprd = $tr->children( 1 )->plaintext;
			$opp_rprd = $tr->children( 2 )->plaintext;
			$touchdowns_rprd_array = (explode("-",$touchdowns_rprd));
			$opp_rprd_array = (explode("-",$opp_rprd));
			$this->td_rushing = ( $touchdowns_rprd_array[0]*1 );
			$this->opp_td_rushing = ( $opp_rprd_array[0]*1 );
			$this->td_passing = ( $touchdowns_rprd_array[1]*1 );
			$this->opp_td_passing = ( $opp_rprd_array[1]*1 );

		}//if
	}//setTouchdownDataB
	
	public function setRushingDataB( $stat_name, $tr ){
		if( $stat_name === "Total Rushing Yards" ){
			$this->rushing_yards = (int)str_replace( ",", "", $tr->children( 1 )->plaintext );
			$this->opp_rushing_yards = (int)str_replace( ",", "", $tr->children( 2 )->plaintext );
		}//if
	}//setRushingDataB
	
	public function setPassingDataB( $stat_name, $tr ){
		if( $stat_name === "Total Passing Yards" ){
			$this->passing_yards = (int)str_replace( ",", "", $tr->children( 1 )->plaintext );
			$this->opp_passing_yards = (int)str_replace( ",", "", $tr->children( 2 )->plaintext );
		}//if
	}//setPassingDataB
	
	public function setDefenseDataB(){
		foreach( $this->html->find( 'div[class="team-stats-wrapper"]' ) as $div ){
			$defense_header = $div->children( 0 )->plaintext;
			if( $defense_header === "Defense" ){
				foreach( $div->find( 'tr' ) as $tr ){
					$defense_name = $tr->children( 0 )->plaintext;
					if( $defense_name === "total" ){
						$this->defense_total = (int)$tr->children( 1 )->plaintext;
					}//if
					if( $defense_name === "opponents total" ){
						$this->opp_defense_total = (int)$tr->children( 1 )->plaintext;
					}//if
				}//foreach
			}//if
		}//foreach
	}//setDefenseDataB
	
	public function getTeamDataB( $url, $team ){
		$this->setHTML( $url );
		$this->title = $team;
		foreach( $this->html->find( 'tr' ) as $tr ){
			$stat_name = $tr->children( 0 )->plaintext;
			//touchdown data
			$this->setTouchdownDataB( $stat_name, $tr );
			//rushing yards data
			$this->setRushingDataB( $stat_name, $tr );
			//passing yards data
			$this->setPassingDataB( $stat_name, $tr );
		}//foreach
		//get defense data
		$this->setDefenseDataB();
	}//getTeamDataB
	
	/* AMERICAN */
	
	public function buffaloBills() {
		$this->getTeamDataA( 'http://www.buffalobills.com/team/team-statistics.html', 'Buffalo Bills' );
		$this->buildContent();
	}//buffaloBills
	
	public function miamiDolphins() {
		$this->getTeamDataA( 'http://www.miamidolphins.com/team/statistics.html', 'Miami Dolphins' );
		$this->buildContent();
	}//miamiDolphins
	
	public function newEnglandPatriots() {
		$this->getTeamDataB( 'http://www.patriots.com/schedule-and-stats/statistics', 'New England Patriots' );
		$this->buildContent();
	}//newEnglandPatriots
	
	public function newYorkJets() {
		$this->getTeamDataA( 'http://www.newyorkjets.com/team/statistics.html', 'New York Jets' );
		$this->buildContent();
	}//newYorkJets
	
	public function baltimoreRavens() {
		$this->getTeamDataA( 'http://www.baltimoreravens.com/team/team-statistics.html', 'Baltimore Ravens' );
		$this->buildContent();
	}//baltimoreRavens
	
	public function cincinnatiBengals() {
		$this->getTeamDataA( 'http://www.bengals.com/team/stats.html', 'Cincinnati Bengals' );
		$this->buildContent();
	}//cincinnatiBengals
	
	public function clevelandBrowns() {
		$this->getTeamDataA( 'http://www.clevelandbrowns.com/team/statistics.html', 'Cleveland Browns' );
		$this->buildContent();
	}//clevelandBrowns
	
	public function pittsburghSteelers() {
		$this->getTeamDataA( 'http://www.steelers.com/team/statistics.html', 'Pittsburgh Steelers' );
		$this->buildContent();
	}//pittsburghSteelers
	
	public function houstonTexans() {
		$this->getTeamDataA( 'http://www.houstontexans.com/team/statistics.html', 'Houston Texans' );
		$this->buildContent();
	}//houstonTexans
	
	public function indianapolisColts() {
		$this->getTeamDataA( 'http://www.colts.com/team/statistics.html', 'Indianapolis Colts' );
		$this->buildContent();
	}//indianapolisColts
	
	public function jacksonvilleJaguars() {
		$this->getTeamDataA( 'http://www.jaguars.com/team/season-stats.html', 'Jacksonville Jaguars' );
		$this->buildContent();
	}//jacksonvilleJaguars
	
	public function tennesseeTitans() {
		$this->getTeamDataA( 'http://www.titansonline.com/team/statistics.html', 'Tennessee Titans' );
		$this->buildContent();
	}//tennesseeTitans
	
	public function denverBroncos() {
		$this->getTeamDataA( 'http://www.denverbroncos.com/team/statistics.html', 'Denver Broncos' );
		$this->buildContent();
	}//denverBroncos
	
	public function kansascityChiefs() {
		$this->getTeamDataA( 'http://www.kcchiefs.com/team/statistics.html', 'Kansas City Chiefs' );
		$this->buildContent();
	}//kansascityChiefs
	
	public function oaklandRaiders() {
		$this->getTeamDataA( 'http://www.raiders.com/team/statistics.html', 'Oakland Raiders' );
		$this->buildContent();
	}//oaklandRaiders
	
	public function sandiegoChargers() {
		$this->getTeamDataB( 'http://www.chargers.com/team/team-stats', 'San Diego Chargers' );
		$this->buildContent();
	}//sandiegoChargers
	
	/* NATIONAL */
	
	public function dallasCowboys() {
		$this->getTeamDataB( 'http://www.dallascowboys.com/team/statistics', 'Dallas Cowboys' );
		$this->buildContent();
	}//dallasCowboys
	
	public function newyorkGiants() {
		$this->getTeamDataA( 'http://www.giants.com/team/statistics.html', 'New York Giants' );
		$this->buildContent();
	}//newyorkGiants
	
	public function philadelphiaEagles() {
		$this->getTeamDataA( 'http://www.philadelphiaeagles.com/team/statistics.html', 'Philadelphia Eagles' );
		$this->buildContent();
	}//philadelphiaEagles
	
	public function washingtonRedskins() {
		$this->getTeamDataA( 'http://www.redskins.com/team/statistics.html', 'Washington Redskins' );
		$this->buildContent();
	}//washingtonRedskins
	
	public function chicagoBears() {
		$this->getTeamDataA( 'http://www.chicagobears.com/team/statistics.html', 'Chicago Bears' );
		$this->buildContent();
	}//chicagoBears
	
	public function detroitLions() {
		$this->getTeamDataA( 'http://www.detroitlions.com/team/statistics.html', 'Detroit Lions' );
		$this->buildContent();
	}//detroitLions
	
	public function greenbayPackers() {
		$this->getTeamDataA( 'http://www.packers.com/team/statistics.html', 'Green Bay Packers' );
		$this->buildContent();
	}//greenbayPackers
	
	public function minnesotaVikings() {
		$this->getTeamDataA( 'http://www.vikings.com/team/statistics.html', 'Minnesota Vikings' );
		$this->buildContent();
	}//minnesotaVikings
	
	public function atlantaFalcons() {
		$this->getTeamDataA( 'http://www.atlantafalcons.com/team/statistics.html', 'Atlanta Falcons' );
		$this->buildContent();
	}//atlantaFalcons
	
	public function carolinaPanthers() {
		$this->getTeamDataA( 'http://www.panthers.com/team/statistics.html', 'Carolina Panthers' );
		$this->buildContent();
	}//carolinaPanthers
	
	public function neworleansSaints() {
		$this->getTeamDataA( 'http://www.neworleanssaints.com/team/statistics.html', 'New Orleans Saints' );
		$this->buildContent();
	}//neworleansSaints
	
	public function tampabayBuccaneers() {
		$this->getTeamDataA( 'http://www.buccaneers.com/team-and-stats/statistics.html', 'Tampa Bay Buccaneers' );
		$this->buildContent();
	}//tampabayBuccaneers
	
	public function arizonaCardinals() {
		$this->getTeamDataA( 'http://www.azcardinals.com/gameday/statistics.html', 'Arizona Cardinals' );
		$this->buildContent();
	}//arizonaCardinals
	
	public function stlouisRams() {
		$this->getTeamDataA( 'http://www.stlouisrams.com/team/statistics.html', 'St. Louis Rams' );
		$this->buildContent();
	}//stlouisRams
	
	public function sanfrancisco49ers() {
		$this->getTeamDataA( 'http://www.49ers.com/team/statistics.html', 'San Francisco 49ers' );
		$this->buildContent();
	}//sanfrancisco49ers
	
	public function seattleSeahawks() {
		$this->getTeamDataB( 'http://www.seahawks.com/team/statistics', 'Seattle Seahawks' );
		$this->buildContent();
	}//seattleSeahawks
	
	public function getStats( $team_name ){
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
