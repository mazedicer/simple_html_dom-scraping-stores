<?php
include_once('./simple_html_dom.php');
include_once( "./classes/Model.php" );
include_once( './classes/View.php' );
include_once( "./classes/Team.php" );
include_once( './classes/ViewStandings.php' );
include_once( "./classes/TeamStandings.php" );

if( isset( $_POST['team1_name'] ) && isset( $_POST['team2_name'] ) ){
	$team1 = $_POST['team1_name'];
	$team2 = $_POST['team2_name'];
	$my_teams = new Team();
	$my_teams->setTeam( "team1" );
	$my_teams->getStats( $team1 );
	$my_teams->setTeam( "team2" );
	$my_teams->getStats( $team2 );
	$results = $my_teams->returnResult();
	echo $results;
}//if

if( isset( $_POST['team_standings_1_name'] ) && isset( $_POST['team_standings_2_name'] ) ){
	$team1 = $_POST['team_standings_1_name'];
	$team2 = $_POST['team_standings_2_name'];
	$my_team_standings = new TeamStandings();
	$my_team_standings->setTeam( "team1" );
	$my_team_standings->getStats( $team1 );
	$my_team_standings->setTeam( "team2" );
	$my_team_standings->getStats( $team2 );
	$results = $my_team_standings->returnResult();
	echo $results;
}//if

