<?php
//modify vars below
// Database
define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', 'phppicks');
define('DB_PASSWORD', 'phppickemuser');
define('DB_DATABASE', 'phppicks');
define('DB_PREFIX', 'nflp_');

define('SITE_URL', '/');   //use either http or https
//define('SITE_URL', 'https://ourfootballpicks.com/');
define('ALLOW_SIGNUP', true);
define('SHOW_SIGNUP_LINK', false);
define('USER_NAMES_DISPLAY', 1); // 1 = real names, 2 = usernames, 3 = usernames w/ real names on hover
define('COMMENTS_SYSTEM', 'basic'); // basic, disqus, or disabled
define('DISQUS_SHORTNAME', ''); // only needed if using Disqus for comments

define('ALWAYS_HIDE_PICKS', true); // Set to true to hide picks until games are locked out

define('SITE_NAME', "Henderson/Biddle NFL Pick 'Em");
define('SEASON_YEAR', '2025');
define('SERVER_TIMEZONE', 'America/Chicago'); // Your SERVER's timezone. NOTE: Game times will always be displayed in Eastern time, as they are on NFL.com. This setting makes sure cutoff times work properly.

// Show donation request in footer
define('ENABLE_DONATE_FOOTER', false);

//define a batch update "key" that a cronjob can pass to update scores automatically
//can be anything you want, as long as it can be sent as a get parameter on the URL
//NOTE: make your life easier and just use alpha-numerics w/out any special chars...
//NOTE: THE PAGE NAME IS CASE SENSEITVE TO BYPASS LOG-IN, IF THE CASE NOT MATCH, IT WILL REDIRECT TO LOGIN W/OUT UPDATING SCORES!
//example:
// curl -O 'http://www.yourdomain.com/getHtmlScores.php?BATCH_SCORE_UPDATE_KEY=yourRandomDefinedValueHere'
// wget 'http://www.yourdomain.com/getHtmlScores.php?BATCH_SCORE_UPDATE_KEY=yourRandomDefinedValueHere'
define('BATCH_SCORE_UPDATE_KEY', 'NOTREALLYINUSE');
//enable or disable batch updates here
define('BATCH_SCORE_UPDATE_ENABLED', true);

// ***DO NOT EDIT ANYTHING BELOW THIS LINE***
error_reporting(E_ALL ^ E_NOTICE ^ E_STRICT);

//automatically set timezone offset (hours difference between your server's timezone and eastern time)
date_default_timezone_set(SERVER_TIMEZONE);
/*$timeZoneCurrent = @date_default_timezone_get();
if (ini_get('date.timezone')) {
	$timeZoneCurrent = ini_get('date.timezone');
}*/
$dateTimeZoneCurrent = new DateTimeZone(SERVER_TIMEZONE);
$dateTimeZoneEastern = new DateTimeZone("America/New_York");
$dateTimeCurrent = new DateTime("now", $dateTimeZoneCurrent);
$dateTimeEastern = new DateTime("now", $dateTimeZoneEastern);
$offsetCurrent = $dateTimeCurrent->getOffset();
$offsetEastern = $dateTimeEastern->getOffset();
$offsetHours = ($offsetEastern - $offsetCurrent) / 3600;

// hack for 2021-2022 b/c I loaded game times as CST instead of EST
//define('SERVER_TIME`ZONE_OFFSET', $offsetHours);
define('SERVER_TIMEZONE_OFFSET', $offsetHours - 1);

$theTime = time();
//$theTime = 1705877492;
$transition = $dateTimeZoneCurrent->getTransitions($theTime,$theTime);
define('SERVER_TIMEZONE_ABBR', $transition[0]['abbr']);
