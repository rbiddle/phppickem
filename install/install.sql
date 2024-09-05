-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 20, 2017 at 07:12 PM
-- Server version: 5.7.19-0ubuntu0.16.04.1-log
-- PHP Version: 7.0.22-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nflpickem`
--

-- --------------------------------------------------------

--
-- Table structure for table `nflp_comments`
--

CREATE TABLE `nflp_comments` (
  `commentID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `comment` longtext NOT NULL,
  `postDateTime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `nflp_divisions`
--

CREATE TABLE `nflp_divisions` (
  `divisionID` int(11) NOT NULL,
  `conference` varchar(10) NOT NULL,
  `division` varchar(32) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `nflp_divisions`
--

INSERT INTO `nflp_divisions` (`divisionID`, `conference`, `division`) VALUES
(1, 'AFC', 'North'),
(2, 'AFC', 'South'),
(3, 'AFC', 'East'),
(4, 'AFC', 'West'),
(5, 'NFC', 'North'),
(6, 'NFC', 'South'),
(7, 'NFC', 'East'),
(8, 'NFC', 'West');

-- --------------------------------------------------------

--
-- Table structure for table `nflp_email_templates`
--

CREATE TABLE `nflp_email_templates` (
  `email_template_key` varchar(255) NOT NULL,
  `email_template_title` varchar(255) NOT NULL,
  `default_subject` varchar(255) DEFAULT NULL,
  `default_message` text,
  `subject` varchar(255) DEFAULT NULL,
  `message` text
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `nflp_email_templates`
--

INSERT INTO `nflp_email_templates` (`email_template_key`, `email_template_title`, `default_subject`, `default_message`, `subject`, `message`) VALUES
('WEEKLY_PICKS_REMINDER', 'Weekly Picks Reminder', 'NFL Pick \'Em Week {week} Reminder', 'Hello {player},<br /><br />You are receiving this email because you do not yet have all of your picks in for week {week}.&nbsp; This is your reminder.&nbsp; The first game is {first_game} (Eastern), so to receive credit for that game, you\'ll have to make your pick before then.<br /><br />Links:<br />&nbsp;- NFL Pick \'Em URL: {site_url}<br />&nbsp;- Help/Rules: {rules_url}<br /><br />Good Luck!<br />', 'NFL Pick \'Em Week {week} Reminder', 'Hello {player},<br /><br />You are receiving this email because you do not yet have all of your picks in for week {week}.&nbsp; This is your reminder.&nbsp; The first game is {first_game} (Eastern), so to receive credit for that game, you\'ll have to make your pick before then.<br /><br />Links:<br />&nbsp;- NFL Pick \'Em URL: {site_url}<br />&nbsp;- Help/Rules: {rules_url}<br /><br />Good Luck!<br />'),
('WEEKLY_RESULTS_REMINDER', 'Last Week Results/Reminder', 'NFL Pick \'Em Week {previousWeek} Standings/Reminder', 'Congratulations this week go to {winners} for winning week {previousWeek}.  The winner(s) had {winningScore} out of {possibleScore} picks correct.<br /><br />The current leaders are:<br />{currentLeaders}<br /><br />The most accurate players are:<br />{bestPickRatios}<br /><br />*Reminder* - Please make your picks for week {week} before {first_game} (Eastern).<br /><br />Links:<br />&nbsp;- NFL Pick \'Em URL: {site_url}<br />&nbsp;- Help/Rules: {rules_url}<br /><br />Good Luck!<br />', 'NFL Pick \'Em Week {previousWeek} Standings/Reminder', 'Congratulations this week go to {winners} for winning week {previousWeek}.  The winner(s) had {winningScore} out of {possibleScore} picks correct.<br /><br />The current leaders are:<br />{currentLeaders}<br /><br />The most accurate players are:<br />{bestPickRatios}<br /><br />*Reminder* - Please make your picks for week {week} before {first_game} (Eastern).<br /><br />Links:<br />&nbsp;- NFL Pick \'Em URL: {site_url}<br />&nbsp;- Help/Rules: {rules_url}<br /><br />Good Luck!<br />'),
('FINAL_RESULTS', 'Final Results', 'NFL Pick \'Em 2016 Final Results', 'Congratulations this week go to {winners} for winning week\r\n{previousWeek}. The winner(s) had {winningScore} out of {possibleScore}\r\npicks correct.<br /><br /><span style="font-weight: bold;">Congratulations to {final_winner}</span> for winning NFL Pick \'Em 2016!&nbsp; {final_winner} had {final_winningScore} wins and had a pick ratio of {picks}/{possible} ({pickpercent}%).<br /><br />Top Wins:<br />{currentLeaders}<br /><br />The most accurate players are:<br />{bestPickRatios}<br /><br />Thanks for playing, and I hope to see you all again for NFL Pick \'Em 2017!', 'NFL Pick \'Em 2016 Final Results', 'Congratulations this week go to {winners} for winning week\r\n{previousWeek}. The winner(s) had {winningScore} out of {possibleScore}\r\npicks correct.<br /><br /><span style="font-weight: bold;">Congratulations to {final_winner}</span> for winning NFL Pick \'Em 2016!&nbsp; {final_winner} had {final_winningScore} wins and had a pick ratio of {picks}/{possible} ({pickpercent}%).<br /><br />Top Wins:<br />{currentLeaders}<br /><br />The most accurate players are:<br />{bestPickRatios}<br /><br />Thanks for playing, and I hope to see you all again for NFL Pick \'Em 2017!');

-- --------------------------------------------------------

--
-- Table structure for table `nflp_picks`
--

CREATE TABLE `nflp_picks` (
  `userID` int(11) NOT NULL,
  `gameID` int(11) NOT NULL,
  `pickID` varchar(10) NOT NULL,
  `points` int(11) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `nflp_picksummary`
--

CREATE TABLE `nflp_picksummary` (
  `weekNum` int(11) NOT NULL DEFAULT '0',
  `userID` int(11) NOT NULL DEFAULT '0',
  `tieBreakerPoints` int(11) NOT NULL DEFAULT '0',
  `showPicks` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

-- --------------------------------------------------------

--
-- Table structure for table `nflp_schedule`
--

CREATE TABLE `nflp_schedule` (
  `gameID` int(11) NOT NULL,
  `weekNum` int(11) NOT NULL,
  `gameTimeEastern` datetime DEFAULT NULL,
  `homeID` varchar(10) NOT NULL,
  `homeScore` int(11) DEFAULT NULL,
  `visitorID` varchar(10) NOT NULL,
  `visitorScore` int(11) DEFAULT NULL,
  `overtime` tinyint(1) NOT NULL DEFAULT '0',
  `scores_timeStamp` datetime DEFAULT NULL,
  `final` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;





-- --------------------------------------------------------

--
-- Table structure for table `nflp_teams`
--

CREATE TABLE `nflp_teams` (
  `teamID` varchar(10) NOT NULL,
  `divisionID` int(11) NOT NULL,
  `city` varchar(50) DEFAULT NULL,
  `team` varchar(50) DEFAULT NULL,
  `displayName` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `nflp_teams`
--

INSERT INTO `nflp_teams` (`teamID`, `divisionID`, `city`, `team`, `displayName`) VALUES
('ARI', 8, 'Arizona', 'Cardinals', NULL),
('ATL', 6, 'Atlanta', 'Falcons', NULL),
('BAL', 1, 'Baltimore', 'Ravens', NULL),
('BUF', 3, 'Buffalo', 'Bills', NULL),
('CAR', 6, 'Carolina', 'Panthers', NULL),
('CHI', 5, 'Chicago', 'Bears', NULL),
('CIN', 1, 'Cincinnati', 'Bengals', NULL),
('CLE', 1, 'Cleveland', 'Browns', NULL),
('DAL', 7, 'Dallas', 'Cowboys', NULL),
('DEN', 4, 'Denver', 'Broncos', NULL),
('DET', 5, 'Detroit', 'Lions', NULL),
('GB', 5, 'Green Bay', 'Packers', NULL),
('HOU', 2, 'Houston', 'Texans', NULL),
('IND', 2, 'Indianapolis', 'Colts', NULL),
('JAX', 2, 'Jacksonville', 'Jaguars', NULL),
('KC', 4, 'Kansas City', 'Chiefs', NULL),
('MIA', 3, 'Miami', 'Dolphins', NULL),
('MIN', 5, 'Minnesota', 'Vikings', NULL),
('NE', 3, 'New England', 'Patriots', NULL),
('NO', 6, 'New Orleans', 'Saints', NULL),
('NYG', 7, 'New York', 'Giants', 'NY Giants'),
('NYJ', 3, 'New York', 'Jets', 'NY Jets'),
('LV', 4, 'Las Vegas', 'Raiders', NULL),
('PHI', 7, 'Philadelphia', 'Eagles', NULL),
('PIT', 1, 'Pittsburgh', 'Steelers', NULL),
('LAC', 4, 'Los Angeles', 'Chargers', NULL),
('SEA', 8, 'Seattle', 'Seahawks', NULL),
('SF', 8, 'San Francisco', '49ers', NULL),
('LAR', 8, 'Los Angeles', 'Rams', NULL),
('TB', 6, 'Tampa Bay', 'Buccaneers', NULL),
('TEN', 2, 'Tennessee', 'Titans', NULL),
('WSH', 7, 'Washington', 'Football Team', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `nflp_users`
--

CREATE TABLE `nflp_users` (
  `userID` int(11) NOT NULL,
  `userName` varchar(32) NOT NULL,
  `password` varchar(255) NOT NULL,
  `salt` varchar(255) NOT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `status` tinyint(1) DEFAULT '1',
  `is_admin` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `nflp_users`
--

INSERT INTO `nflp_users` (`userID`, `userName`, `password`, `salt`, `firstname`, `lastname`, `email`, `status`, `is_admin`) VALUES
(1, 'admin', 'jl7LZ1B7ZNUq/RnVqnFmuwRXvMkO/DD5', 'Cb8Jjj0OPy', 'Admin', 'Admin', 'admin@yourdomain.com', 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `nflp_comments`
--
ALTER TABLE `nflp_comments`
  ADD PRIMARY KEY (`commentID`);

--
-- Indexes for table `nflp_divisions`
--
ALTER TABLE `nflp_divisions`
  ADD PRIMARY KEY (`divisionID`);

--
-- Indexes for table `nflp_email_templates`
--
ALTER TABLE `nflp_email_templates`
  ADD PRIMARY KEY (`email_template_key`);

--
-- Indexes for table `nflp_picks`
--
ALTER TABLE `nflp_picks`
  ADD PRIMARY KEY (`userID`,`gameID`);

--
-- Indexes for table `nflp_picksummary`
--
ALTER TABLE `nflp_picksummary`
  ADD PRIMARY KEY (`weekNum`,`userID`);

--
-- Indexes for table `nflp_schedule`
--
ALTER TABLE `nflp_schedule`
  ADD PRIMARY KEY (`gameID`),
  ADD KEY `GameID` (`gameID`),
  ADD KEY `HomeID` (`homeID`),
  ADD KEY `VisitorID` (`visitorID`);

--
-- Indexes for table `nflp_teams`
--
ALTER TABLE `nflp_teams`
  ADD PRIMARY KEY (`teamID`),
  ADD KEY `ID` (`teamID`);

--
-- Indexes for table `nflp_users`
--
ALTER TABLE `nflp_users`
  ADD PRIMARY KEY (`userID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `nflp_comments`
--
ALTER TABLE `nflp_comments`
  MODIFY `commentID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `nflp_divisions`
--
ALTER TABLE `nflp_divisions`
  MODIFY `divisionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `nflp_users`
--
ALTER TABLE `nflp_users`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
