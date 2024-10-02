<?php
$weekStats = array();
$playerTotals = array();
$possibleScoreTotal = 0;
calculateStats();

$tmpScore = 0;
$i = 1;
if (is_array($playerTotals) && sizeof($playerTotals) > 0) {
	//show top 3 pick ratios
	echo '		<div class="bg-success">' . "\n";
	echo '			<b>Current Leaders (pick %):</b><br />' . "\n";
	$playerTotals = sort2d($playerTotals, 'score', 'desc');
	foreach($playerTotals as $playerID => $stats) {
		if ($tmpScore < $stats['score']) $tmpScore = $stats['score']; //set initial top score
		//if next lowest score is reached, increase counter
		if ($stats['score'] < $tmpScore ) $i++;
		//if score is zero or counter is 3 or higher, break
		if ($stats['score'] == 0 || $i > 3) break;
		$pickRatio = $stats['score'] . '/' . $possibleScoreTotal;
		$pickPercentage = number_format((($stats['score'] / $possibleScoreTotal) * 100), 2) . '%';
		echo '			' . $i . '. ' . $stats['name'] . ' - ' . $pickRatio . ' (' . $pickPercentage . ')<br />';
		$tmpScore = $stats['score']; //set last # wins
	}
	echo '		</div>' . "\n";
}

?>