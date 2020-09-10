<?php
require('includes/application_top.php');
require('includes/classes/team.php');

if (!$user->is_admin) {
	header('Location: ./');
	exit;
}

if ($_POST['action'] == 'Update') {
	$week = $_POST['week'];
	$pickUserID = $_POST['pickUserID'];

	//update summary table
	$sql = "delete from " . DB_PREFIX . "picksummary where weekNum = " . $_POST['week'] . " and userID = " . $_POST['pickUserID'] . ";";
	$mysqli->query($sql) or die('Error updating picks summary: ' . $mysqli->error);
	$sql = "insert into " . DB_PREFIX . "picksummary (weekNum, userID, showPicks) values (" . $_POST['week'] . ", " . $_POST['pickUserID'] . ", 0);";
	$mysqli->query($sql) or die('Error updating picks summary: ' . $mysqli->error);

	//loop through non-expire weeks and update picks
	$sql = "select * from " . DB_PREFIX . "schedule where weekNum = " . $week . ";";
	$query = $mysqli->query($sql);
	if ($query->num_rows > 0) {
		while ($row = $query->fetch_assoc()) {
			$sql = "delete from " . DB_PREFIX . "picks where userID = " . $pickUserID . " and gameID = " . $row['gameID'];
			$mysqli->query($sql) or die('Error deleting picks: ' . $mysqli->error);

			if (!empty($_POST['game' . $row['gameID']])) {
				$sql = "insert into " . DB_PREFIX . "picks (userID, gameID, pickID) values (" . $pickUserID . ", " . $row['gameID'] . ", '" . $_POST['game' . $row['gameID']] . "')";
				$mysqli->query($sql) or die('Error inserting picks: ' . $mysqli->error);
			}
		}
	}
	$query->free;
	header('Location: results.php?week=' . $_POST['week']);
	exit;
}

$week = (int)$_GET['week'];
if (empty($week)) {
	//get current week
	$week = (int)getCurrentWeek();
}

$pickUserID = (int)$_GET['pickUserID'];
if (empty($pickUserID)) {
	// do anything?
	
}

include('includes/header.php');
?>

	<script type="text/javascript">

	function radioIsChecked(elmName) {
		var elements = document.getElementsByName(elmName);
		for (var i = 0; i < elements.length; i++) {
			if (elements[i].checked) {
				return true;
			}
		}
		return false;
	}
	function checkRadios() {
	  $('input[type=radio]').each(function(){
	   //alert($(this).attr('checked'));
	    var targetLabel = $('label[for="'+$(this).attr('id')+'"]');
	    console.log($(this).attr('id')+': '+$(this).is(':checked'));
	    if ($(this).is(':checked')) {
	      //console.log(targetLabel);
	     targetLabel.addClass('highlight');
	    } else {
	      targetLabel.removeClass('highlight');
	    }
	  });
	}
	$(function() {
		checkRadios();
		$('input[type=radio]').click(function(){
		  checkRadios();
		});
		$('label').click(function(){
		  checkRadios();
		});
	});
	</script>

	<h1>Edit Picks - Week <?php echo $week; ?></h1>
<?php
//display week nav
$sql = "select distinct weekNum from " . DB_PREFIX . "schedule order by weekNum;";
$query = $mysqli->query($sql);
$weekNav = '<div class="navbar3"><b>Go to week:</b> ';
$i = 0;
while ($row = $query->fetch_assoc()) {
	if ($i > 0) $weekNav .= ' | ';
	if ($week !== (int)$row['weekNum']) {
		$weekNav .= '<a href="edit_picks.php?week=' . $row['weekNum'] . '">' . $row['weekNum'] . '</a>';
	} else {
		$weekNav .= $row['weekNum'];
	}
	$i++;
}
$query->free;
$weekNav .= '</div>' . "\n";
echo $weekNav;
?>

<?php
if (empty($pickUserID)) {
	echo '<div>Select User:</div>';

	$sql = "select * from " . DB_PREFIX . "users where userID > 1 order by lastname, firstname";
	$query = $mysqli->query($sql);
	if ($query->num_rows > 0) {
		echo '<table class="table table-striped">' . "\n";
		echo '	<tr><th align="left">Username</th><th align="left">Name</th></tr>' . "\n";
		$i = 0;
		while ($row = $query->fetch_assoc()) {
			$rowclass = (($i % 2 == 0) ? ' class="altrow"' : '');
			echo '		<tr' . $rowclass . '>' . "\n";
			echo '			<td><a href="' . $_SERVER['PHP_SELF'] . '?week=' . $week . '&pickUserID=' . $row['userID'] . '">' . $row['userName'] . '</a></td>' . "\n";
			echo '			<td>' . $row['lastname'] . ', ' . $row['firstname'] . '</td>' . "\n";
			echo '		</tr>' . "\n";
			$i++;
		}
		echo '</table>' . "\n";
	}
} else {
	echo '<form id="picksForm" name="picksForm" action="edit_picks.php" method="post">';
	echo '<input type="hidden" name="week" value="' . $week . '" />';
	echo '<input type="hidden" name="pickUserID" value="' . $pickUserID . '" />';
	
	$sql = "select * from " . DB_PREFIX . "users where userID = " . $pickUserID;
	$query = $mysqli->query($sql);
	if ($query->num_rows > 0) {
		echo '<div>Editting Picks for: ';
		$row = $query->fetch_assoc();
		echo $row['lastname'] . ', ' . $row['firstname'] . ' (' . $row['userName'] . ', ' . $row['email'] . ')';
		echo '</div>' . "\n";
		echo '</br>';
	}

	//get existing picks
	$picks = getUserPicks($week, $pickUserID);
	
	//display schedule for week
	$sql = "select s.*, (DATE_ADD(NOW(), INTERVAL " . SERVER_TIMEZONE_OFFSET . " HOUR) > gameTimeEastern or DATE_ADD(NOW(), INTERVAL " . SERVER_TIMEZONE_OFFSET . " HOUR) > '" . $cutoffDateTime . "')  as expired ";
	$sql .= "from " . DB_PREFIX . "schedule s ";
	$sql .= "inner join " . DB_PREFIX . "teams ht on s.homeID = ht.teamID ";
	$sql .= "inner join " . DB_PREFIX . "teams vt on s.visitorID = vt.teamID ";
	$sql .= "where s.weekNum = " . $week . " ";
	$sql .= "order by s.gameTimeEastern, s.gameID";
	$query = $mysqli->query($sql) or die($mysqli->error);
	
	if ($query->num_rows > 0) {
		echo '		<div class="row">'."\n";
		echo '			<div class="col-xs-12">'."\n";
		$i = 0;
		while ($row = $query->fetch_assoc()) {
			$homeTeam = new team($row['homeID']);
			$visitorTeam = new team($row['visitorID']);
			echo '				<div class="matchup">' . "\n";
			echo '					<div class="row bg-row1">'."\n";
			echo '					<div class="col-xs-12 center">' . date('D n/j g:i a', strtotime($row['gameTimeEastern'])) . ' ET</div>' . "\n";
			echo '					</div>'."\n";
			echo '					<div class="row versus">' . "\n";
			echo '						<div class="col-xs-1"></div>' . "\n";
			echo '						<div class="col-xs-4">'."\n";
			echo '							<label for="' . $row['gameID'] . $visitorTeam->teamID . '" class="label-for-check"><div class="team-logo"><img src="images/logos/'.$visitorTeam->teamID.'.svg" onclick="document.entryForm.game'.$row['gameID'].'[0].checked=true;" /></div></label>' . "\n";
			echo '						</div>'."\n";
			echo '						<div class="col-xs-2">@</div>' . "\n";
			echo '						<div class="col-xs-4">'."\n";
			echo '							<label for="' . $row['gameID'] . $homeTeam->teamID . '" class="label-for-check"><div class="team-logo"><img src="images/logos/'.$homeTeam->teamID.'.svg" onclick="document.entryForm.game' . $row['gameID'] . '[1].checked=true;" /></div></label>'."\n";
			echo '						</div>' . "\n";
			echo '						<div class="col-xs-1"></div>' . "\n";
			echo '					</div>' . "\n";
			echo '					<div class="row bg-row2">'."\n";
			echo '						<div class="col-xs-1"></div>' . "\n";
			echo '						<div class="col-xs-4 center">'."\n";
			echo '							<input type="radio" class="check-with-label" name="game' . $row['gameID'] . '" value="' . $visitorTeam->teamID . '" id="' . $row['gameID'] . $visitorTeam->teamID . '"' . (($picks[$row['gameID']]['pickID'] == $visitorTeam->teamID) ? ' checked' : '') . ' />'."\n";
			echo '						</div>'."\n";
			echo '						<div class="col-xs-2"></div>' . "\n";
			echo '						<div class="col-xs-4 center">'."\n";
			echo '							<input type="radio" class="check-with-label" name="game' . $row['gameID'] . '" value="' . $homeTeam->teamID . '" id="' . $row['gameID'] . $homeTeam->teamID . '"' . (($picks[$row['gameID']]['pickID'] == $homeTeam->teamID) ? ' checked' : '') . ' />' . "\n";
			echo '						</div>' . "\n";
			echo '						<div class="col-xs-1"></div>' . "\n";
			echo '					</div>' . "\n";
			echo '					<div class="row bg-row3">'."\n";
			echo '						<div class="col-xs-6 center">'."\n";
			echo '							<div class="team">' . $visitorTeam->city . ' ' . $visitorTeam->team . '</div>'."\n";
			echo '						</div>'."\n";
			echo '						<div class="col-xs-6 center">' . "\n";
			echo '							<div class="team">' . $homeTeam->city . ' ' . $homeTeam->team . '</div>'."\n";
			echo '						</div>' . "\n";
			echo '					</div>'."\n";
			echo '				</div>'."\n";
		}
		echo '			</div>' . "\n";
		echo '		</div>' . "\n";	
	}
	
	echo '<input type="submit" name="action" value="Update" class="btn btn-info" />';
	echo '</form>';
}	

include('includes/footer.php');
