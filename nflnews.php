<?php
require('includes/application_top.php');
include('includes/header.php');

$team = $_GET['team'];
if (empty($team)) {
   //get current team
   $team = 'NFL';
}


$teamsids=array("NFL", "ARI", "ATL", "BAL", "BUF", "CAR", "CHI", "CIN", "CLE", "DAL", "DEN", "DET", "GB", "HOU", "IND", "JAX", "KC", "LV", "LAR", "LAC", "MIA", "MIN", "NE", "NO", "NYG", "NYJ", "PHI", "PIT", "SEA", "SF",  "TB", "TEN", "WAS");
$teamsnames=array(" NFL ", " Arizona Cardinals ", " Atlanta Falcons ", " Baltimore Ravens ", " Buffalo Bills ", " Carolina Panthers ", " Chicago Bears ", " Cincinnati Bengals ", " Cleveland Browns ", " Dallas Cowboys ", " Denver Broncos ", " Detroit Lions ", " Green Bay Packers ", " Houston Texans ", " Indianapolis Colts ", " Jacksonville Jaguars ", " Kansas City Chiefs ", " Las Vegas Raiders ", " Los Angeles Rams ", " Los Angeles Chargers ", " Miami Dolphins ", " Minnesota Vikings ", " New England Patriots ", " New Orleans Saints ", " New York Giants ", " New York Jets ", " Philadelphia Eagles ", " Pittsburgh Steelers ", " Seattle Seahawks ", " San Francisco 49ers ", " Tampa Bay Buccaneers ", " Tennessee Titans ", " Washington Football Team ");
?>

<select id="my_selection">
  <option selected="selected">Change to Team News for</option>
  <?php
    foreach($teamsids as $name) {
      $i = array_search($name, $teamsids);?>

      <option value="<?= $name ?>" href="nflnews.php?team=<?= $name ?>"><?= $teamsnames[$i] ?>News</option>
  <?php
    } ?>
</select>

<script>
document.getElementById('my_selection').onchange = function() {
    window.location.href = this.children[this.selectedIndex].getAttribute('href');
}
</script>

<!--Start RSS Feeds-->
<table width="100%">
   <tr><td><div class="navbar"><h1>
  <center>
  <?php
  $searches = array('ARI', 'ATL', 'BAL', 'BUF', 'CAR', 'CHI', 'CIN', 'CLE', 'DAL', 'DEN', 'DET', 'GB', 'HOU', 'IND', 'JAX', 'KC', 'LAR', 'LAC', 'MIA', 'MIN', 'NE', 'NO', 'NYG', 'NYJ', 'LV', 'PHI', 'PIT', 'SEA', 'SF',  'TB', 'TEN', 'WAS');
  $replacements = array('Cardinals', 'Falcons', 'Ravens', 'Bills', 'Panthers', 'Bears', 'Bengals', 'Browns', 'Cowboys', 'Broncos', 'Lions', 'Packers', 'Texans', 'Colts', 'Jaguars', 'Chiefs', 'Rams', 'Chargers', 'Dolphins', 'Vikings', 'Patriots', 'Saints', 'Giants', 'Jets', 'Raiders', 'Eagles', 'Steelers', 'Seahawks', 'Niners', 'Buccaneers', 'Titans', 'Football Team');
  $j = array_search($team, $teamsids);
  echo $teamsnames[$j];
?> News</h1></center></div></td></tr>
</table>

<?php
if($team == 'NFL') {
?>
<!--Call NFL RSS Feed if template is NFL-->
<?php
  $rss = new DOMDocument();
   //$rss->load('http://www.nfl.com/rss/rsslanding?searchString=home');
   $rss->load('https://sports.yahoo.com/nfl/rss/');
   $feed = array();
   foreach ($rss->getElementsByTagName('item') as $node) {
      $item = array (
         'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
         'desc' => $node->getElementsByTagName('description')->item(0)->nodeValue,
         'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,
         'date' => $node->getElementsByTagName('pubDate')->item(0)->nodeValue,
         );
      array_push($feed, $item);
   }
   $limit = 20;
   for($x=0;$x<$limit;$x++) {
      $title = str_replace(' & ', ' &amp; ', $feed[$x]['title']);
      $link = $feed[$x]['link'];
      $description = $feed[$x]['desc'];
      $date = date('l F d, Y', strtotime($feed[$x]['date']));
	  if (trim($title) <> '') {
		echo '<p><strong><a href="'.$link.'" target="_blank" title="'.$title.'">'.$title.'</a></strong><br />';
		echo '<small><em>Posted on '.$date.'</em></small></p>';
		//echo '<p>'.$description.'</p>';
	  }
   }
?>
<!--End NFL RSS Feed-->

<!--Call RSS Feed for teams based on team passed-->
 <?php
} else {
?>

<?php
	$rss = new DOMDocument();
	if ($team == 'GB') $team = 'green-bay';
	if ($team == 'JAX') $team = 'jac';
	if ($team == 'KC') $team = 'kan';
	if ($team == 'LV') $team = 'las-vegas';
	if ($team == 'NE') $team = 'new-england';
	if ($team == 'NO') $team = 'new-orleans';
	if ($team == 'SF') $team = 'san-francisco';
	if ($team == 'TB') $team = 'tam';
   //$rss->load('http://www.nfl.com/rss/rsslanding?searchString=team&abbr='. $team);
   $rss->load('https://sports.yahoo.com/nfl/teams/'.strtolower($team).'/rss/');
   $feed = array();
   foreach ($rss->getElementsByTagName('item') as $node) {
      $item = array (
         'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
         'desc' => $node->getElementsByTagName('description')->item(0)->nodeValue,
         'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,
         'date' => $node->getElementsByTagName('pubDate')->item(0)->nodeValue,
         );
      array_push($feed, $item);
   }
   $limit = 20;
   for($x=0;$x<$limit;$x++) {
      $title = str_replace(' & ', ' &amp; ', $feed[$x]['title']);
      $link = $feed[$x]['link'];
      $description = $feed[$x]['desc'];
      $date = date('l F d, Y', strtotime($feed[$x]['date']));
	  if (trim($title) <> '') {
		echo '<p><strong><a href="'.$link.'" target="_blank" title="'.$title.'">'.$title.'</a></strong><br />';
		echo '<small><em>Posted on '.$date.'</em></small></p>';
		//echo '<p>'.$description.'</p>';
	  }
   }
?>
<!--End RSS Team Feeds-->
<?php
}

include('includes/footer.php');
?>