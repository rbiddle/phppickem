<?php
require('includes/application_top.php');

if ($_GET['execute'] != 1) {
	print "<a href=\"getHtmlScores.php?BATCH_SCORE_UPDATE_KEY=" . BATCH_SCORE_UPDATE_KEY . "&execute=1\">Execute?</a></br></br>";
} else {
	print "<a href=\"results.php\">Results</a></br></br>";
}

$week = (int)$_GET['week'];
if (empty($week)) {
	$week = (int)getCurrentWeek(); //get current week
}

//load source code, depending on the current week, of the website into a variable as a string
$url = "http://static.nfl.com/ajax/scorestrip?season=".SEASON_YEAR."&seasonType=REG&week=".$week;

echo $url . '</br>';

if ($xmlData = file_get_contents($url)) {
	$xml = simplexml_load_string($xmlData);
	$json = json_encode($xml);
	$games = json_decode($json, true);
}


if (empty($week)) {
	//get current week
	$gms = $games['gms']['@attributes'];
	$week = $gms['w'];
}
echo "Week: " . $week . "<br />";

//build scores array, to group teams and scores together in games
$scores = array();
foreach ($games['gms']['g'] as $gameArray) {
	$game = $gameArray['@attributes'];
	if ($game['q'] == 'F' || $game['q'] == 'FO') {
		$overtime = (($game['q'] == 'FO') ? 1 : 0);
		$away_team = $game['v'];
		$home_team = $game['h'];

               if ($home_team == "LA") $home_team="LAR";
               if ($away_team == "LA") $away_team="LAR";

		$away_score = (int)$game['vs'];
		$home_score = (int)$game['hs'];
		$final = 1;

		$winner = ($away_score > $home_score) ? $away_team : $home_team;
		$gameID = getGameIDByTeamID($week, $home_team);
		if (is_numeric(strip_tags($home_score)) && is_numeric(strip_tags($away_score))) {
			if ($away_score > 0 || $home_score > 0) {
				$scores[] = array(
					'gameID' => $gameID,
					'awayteam' => $away_team,
					'visitorScore' => $away_score,
					'hometeam' => $home_team,
					'homeScore' => $home_score,
					'overtime' => $overtime,
					'winner' => $winner,
					'final' => $final
				);
			}
		}
	}
	if ($game['q'] <> 'F' && $game['q'] <> 'FO') {
	$overtime = 0;
	$away_team = $game['v'];
	$home_team = $game['h'];

        if ($home_team == "LA") $home_team="LAR";
        if ($home_team == "LA") $home_team="LAR";

	$away_score = (int)$game['vs'];
	$home_score = (int)$game['hs'];
	$final = 0;

	$winner = ($away_score > $home_score) ? $away_team : $home_team;

    if ($home_team == "LA") $home_team="LAR";
    if ($away_team == "LA") $away_team="LAR";

	$gameID = getGameIDByTeamID($week, $home_team);
		if (is_numeric(strip_tags($home_score)) && is_numeric(strip_tags($away_score))) {
			if ($away_score > 0 || $home_score > 0) {
				$scores[] = array(
					'gameID' => $gameID,
					'awayteam' => $away_team,
					'visitorScore' => $away_score,
					'hometeam' => $home_team,
					'homeScore' => $home_score,
					'overtime' => $overtime,
					'winner' => $winner,
					'final' => $final
				);
			}
		}
	}
}

//game results and winning teams can now be accessed from the scores array
//e.g. $scores[0]['awayteam'] contains the name of the away team (['awayteam'] part) from the first game on the page ([0] part)

if(BATCH_SCORE_UPDATE_ENABLED && !empty($_GET['BATCH_SCORE_UPDATE_KEY']) && $_GET['BATCH_SCORE_UPDATE_KEY'] == BATCH_SCORE_UPDATE_KEY ){
	foreach($scores as $game) {
		$homeScore = ((strlen($game['homeScore']) > 0) ? $game['homeScore'] : 'NULL');
		$visitorScore = ((strlen($game['visitorScore']) > 0) ? $game['visitorScore'] : 'NULL');
		$overtime = ((!empty($game['overtime'])) ? '1' : '0');
		$final = ((!empty($game['final'])) ? '1' : '0');
		$sql = "update " . DB_PREFIX . "schedule ";
		$sql .= "set homeScore = " . $homeScore . ", visitorScore = " . $visitorScore . ", overtime = " . $overtime . ", final = " . $final . " ";
		$sql .= "where gameID = " . $game['gameID'];
		if ($_GET['execute'] == 1) {
			$mysqli->query($sql) or die('Error updating score: ' . $mysqli->error);
		}
		echo $game['hometeam'] . ' hosting ' . $game['awayteam'] . ' => ' . $sql . '<br />';
	}
}
//see how the scores array looks for debugging
echo '<hr /><strong>Scores:</strong><br/><pre>' . print_r($scores, true) . '</pre>';
echo '<hr /><strong>Raw Data:</strong><br/><pre>' . print_r($games, true) . '</pre>';
//echo json_encode($scores);