<label for="team_select">Select Away Team 1: </label> 
<select id="team1_select"></select>
<label for="team_select">Select Home Team 2: </label>
<select id="team2_select"></select> 
<input type="button" value="GO" onclick="run()">
<div id="results_div"></div>
<div id="standings_results_div"></div>

<script language="javascript">
	/* populate drop down menu */
	var team1_select = document.getElementById( "team1_select" );
	var team2_select = document.getElementById( "team2_select" );
	var results_div = document.getElementById( "results_div" );
	var teams = new Array( " ", "Baltimore Ravens", "Cincinnati Bengals", "Miami Dolphins", "New England Patriots", "New York Jets", "Cleveland Browns", "Pittsburgh Steelers", "Houston Texans", "Indianapolis Colts", "Jacksonville Jaguars", "Tennessee Titans", "Denver Broncos", "Kansas City Chiefs", "Oakland Raiders", "San Diego Chargers", "Dallas Cowboys", "New York Giants", "Philadelphia Eagles", "Washington Redskins", "Chicago Bears", "Detroit Lions", "Green Bay Packers", "Minnesota Vikings", "Atlanta Falcons", "Carolina Panthers", "New Orleans Saints", "Tampa Bay Buccaneers", "Arizona Cardinals", "St Louis Rams", "San Francisco 49ers", "Seattle Seahawks", "Buffalo Bills" );
	teams.sort();
	var team_options;
	for( x = 0; x < teams.length; x++ ){
		team_options += '<option value="' +teams[x]+ '">'+teams[x]+'</option>';
	}//for
	team1_select.innerHTML = team_options;
	team2_select.innerHTML = team_options;
	
	function hMetricTeamX( greater, teamx, metric ){
		switch( true ){
			case ( greater >= 0 && greater < 5 ):
				$( "#"+teamx+metric ).css( "background-color", "yellow" );
				break;
			case ( greater >= 5 && greater < 10 ):
				$( "#"+teamx+metric ).css( "background-color", "orange" );
				break;
			case ( greater >= 10 && greater < 15 ):
				$( "#"+teamx+metric ).css( "background-color", "red" );
				break;
			case ( greater >= 15 && greater < 20 ):
				$( "#"+teamx+metric ).css( "background-color", "purple" );
				break;
			case ( greater >= 20 && greater < 25 ):
				$( "#"+teamx+metric ).css( "background-color", "blue" );
				break;
			case ( greater >= 25 ):
				$( "#"+teamx+metric ).css( "background-color", "green" );
				break;
		}//switch
	}//hMetricTeamX
	
	function hDRPMetrics( greater, teamx, metric ){
		switch( true ){
			case ( greater >= 0 && greater < 100 ):
				$( "#"+teamx+metric ).css( "background-color", "yellow" );
				break;
			case ( greater >= 100 && greater < 200 ):
				$( "#"+teamx+metric ).css( "background-color", "orange" );
				break;
			case ( greater >= 200 && greater < 300 ):
				$( "#"+teamx+metric ).css( "background-color", "red" );
				break;
			case ( greater >= 300 && greater < 400 ):
				$( "#"+teamx+metric ).css( "background-color", "purple" );
				break;
			case ( greater >= 400 && greater < 500 ):
				$( "#"+teamx+metric ).css( "background-color", "blue" );
				break;
			case ( greater >= 500 ):
				$( "#"+teamx+metric ).css( "background-color", "green" );
				break;
		}//switch
	}//hDRPMetrics
	
	function hTDMetric( team1, opp, metric ){
		if( parseInt( team1 ) > parseInt( opp ) ){
			var greater = parseInt( team1 ) - parseInt( opp );
			hMetricTeamX( greater, "team1", metric );
		}else if( parseInt( team1 ) < parseInt( opp ) ){
			var greater =  parseInt( opp ) - parseInt( team1 );
			hMetricTeamX( greater, "team2", metric );
		}//if
	}//hTDMetrics
	
	function hTDAllowedMetric( team1, opp, metric ){
		if( parseInt( team1 ) < parseInt( opp ) ){
			var greater = parseInt( opp ) - parseInt( team1 );
			hMetricTeamX( greater, "team1", metric );
		}else if( parseInt( team1 ) > parseInt( opp ) ){
			var greater =  parseInt( team1 ) - parseInt( opp );
			hMetricTeamX( greater, "team2", metric );
		}//if
	}//hTDAllowedMetric
	
	function hDRPMetricTeam( team1, opp, metric ){
		if( parseInt( team1 ) > parseInt( opp ) ){
			var greater = parseInt( team1 ) - parseInt( opp );
			hDRPMetrics( greater, "team1", metric );
		}else if( parseInt( team1 ) < parseInt( opp ) ){
			var greater =  parseInt( opp ) - parseInt( team1 );
			hDRPMetrics( greater, "team2", metric );
		}//if
	}//hTDAllowedMetric
	
	function manageResult( data ){
		var data_array = data.split( "XAAAAX" );
		results_div.innerHTML = data_array[0];
		var teams_array = data_array[1].split( "XMMMMX" );
		var team1_array = teams_array[0].split( "|" );
		var team2_array = teams_array[1].split( "|" );
		var team1_td_rushing = team1_array[3];
		var team2_td_rushing = team2_array[3];
		var team1_td_passing = team1_array[4];
		var team2_td_passing = team2_array[4];
		var team1_opp_td_rushing = team1_array[5];
		var team2_opp_td_rushing = team2_array[5];
		var team1_opp_td_passing = team1_array[6];
		var team2_opp_td_passing = team2_array[6];
		var team1_td_rushing_diff = team1_array[7];
		var team2_td_rushing_diff = team2_array[7];
		var team1_td_passing_diff = team1_array[8];
		var team2_td_passing_diff = team2_array[8];
		var team1_td_diff = team1_array[9];
		var team2_td_diff = team2_array[9];
		var team1_defense = team1_array[10];
		var team2_defense = team2_array[10];
		var team1_defense_diff = team1_array[12];
		var team2_defense_diff = team2_array[12];
		var team1_rushing_yards = team1_array[13];
		var team2_rushing_yards = team2_array[13];
		var team1_rushing_diff = team1_array[15];
		var team2_rushing_diff = team2_array[15];
		var team1_passing_yards = team1_array[16];
		var team2_passing_yards = team2_array[16];
		var team1_passing_diff = team1_array[18];
		var team2_passing_diff = team2_array[18];
		hTDMetric( team1_td_rushing, team2_td_rushing, "_td_rushing" );
		hTDMetric( team1_td_passing, team2_td_passing, "_td_passing" );
		hTDAllowedMetric( team1_opp_td_rushing, team2_opp_td_rushing, "_allowed_rushing" );
		hTDAllowedMetric( team1_opp_td_passing, team2_opp_td_passing, "_allowed_passing" );
		hTDMetric( team1_td_rushing_diff, team2_td_rushing_diff, "_td_rushing_diff" );
		hTDMetric( team1_td_passing_diff, team2_td_passing_diff, "_td_passing_diff" );
		hTDMetric( team1_td_diff, team2_td_diff, "_td_diff" );
		hDRPMetricTeam( team1_defense, team2_defense, "_defense" );
		hDRPMetricTeam( team1_defense_diff, team2_defense_diff, "_defense_diff" );
		hDRPMetricTeam( team1_rushing_yards, team2_rushing_yards, "_yard_rushing" );
		hDRPMetricTeam( team1_rushing_diff, team2_rushing_diff, "_yard_rushing_diff" );
		hDRPMetricTeam( team1_passing_yards, team2_passing_yards, "_yard_passing" );
		hDRPMetricTeam( team1_passing_diff, team2_passing_diff, "_yard_passing_diff" );
	}//manageResult
	
	function manageStandingsResult( data ){
		$( "#standings_results_div" ).html( data );
	}//manageStandingsResult
	
	/* get stats */
	function getStats(){
		var team1_name = team1_select.options[ team1_select.selectedIndex ].value;
		var team2_name = team2_select.options[ team2_select.selectedIndex ].value;
		$.ajax(
			{
				type: 'POST',
				cache: false,
				data: { team1_name: team1_name, team2_name: team2_name },
				url: 'process_index.php',
				success: function(data, status, xml){
					//console.log( data )
					//elem.value = data;
					manageResult( data );
				}
			}
		);
	}//getStats
	
	function getStandings(){
		var team1_name = team1_select.options[ team1_select.selectedIndex ].value;
		var team2_name = team2_select.options[ team2_select.selectedIndex ].value;
		$.ajax(
			{
				type: 'POST',
				cache: false,
				data: { team_standings_1_name: team1_name, team_standings_2_name: team2_name },
				url: 'process_index.php',
				success: function(data, status, xml){
					//console.log( data )
					//elem.value = data;
					manageStandingsResult( data );
				}
			}
		);
	}//getStandings
	
	function run(){
		getStats();
		getStandings();
	}//run
	
</script>