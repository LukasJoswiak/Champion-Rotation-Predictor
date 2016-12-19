<?php

$path = $_SERVER['DOCUMENT_ROOT'];
include_once($path . '/core/init.php');

$url = explode('/', $_SERVER['REQUEST_URI']);
$champion = str_replace('-', ' ', htmlspecialchars(strip_tags($url[2]), ENT_QUOTES, 'UTF-8'));

$championList = $read->champion_list();

foreach ($championList as $champ) {
	if ($champion == str_replace('\'', '', strtolower($champ['champion_name']))) {
		$id = $champ['champion_id'];
		$name = $champ['champion_name'];
		$key = $champ['champion_key'];
		$title = $champ['title'];
		$tags = unserialize($champ['tags']);
		$date_released = $champ['date_released'];

		$image = 'https://ddragon.leagueoflegends.com/cdn/img/champion/splash/' . $key . '_0.jpg';

		break;
	}
}

if (!isset($id)) {
	require($path . '/404.php');
	exit();
}

if (strlen($champion) > 8) {
	$word1 = "";
	$word2 = "been ";
} else {
	$word1 = " been";
	$word2 = "";
}

$rotation_history = $read->rotation_champion($id);

$last_free = end($rotation_history)['date_free'];

if (strtotime($last_free) > strtotime(date('Y-m-d'))) {
	array_pop($rotation_history);
	$last_free = end($rotation_history)['date_free'];
}

if ($last_free) {
	$lastFreeInWeeks = dateDiffInWeeks($last_free, date('Y-m-d'));

	$lastFreeInWeeks .= ($lastFreeInWeeks == 1) ? ' week ago' : ' weeks ago';
	if ($lastFreeInWeeks == 0) {
		$lastFreeInWeeks = 'free now';
	}
} else {
	$lastFreeInWeeks = 'never free';
}

$times_free = count($rotation_history);

if ($times_free == 1) {
	$times_free .= ' time';
} else {
	$times_free .= ' times';
}

$keys = array();
$weeksAgo = array();

if ($times_free >= 1) {
	$releasedWeeksAgo = dateDiffInWeeks($date_released, date('Y-m-d'));

	$weekDifference = array();

	array_push($rotation_history, array('date_free' => date('Y-m-d'), '0' => date('Y-m-d')));
	$rotation_history = array_reverse($rotation_history);

	foreach ($rotation_history as $key => $row) {
		if ($key > 0) {
			$previous_date = $rotation_history[$key - 1]['date_free'];
			$date = $row['date_free'];

			array_push($keys, $key - 1);
			array_push($weekDifference, dateDiffInWeeks($previous_date, $date));
			array_push($weeksAgo, array_sum($weekDifference));
		}
	}

	array_push($weeksAgo, $releasedWeeksAgo); // add release date of champion

	$count = count($weekDifference) - 1;
	$sum = array_sum($weekDifference) - $weekDifference[0]; // remove different from last time free to current date

	//echo 'Mean: ' . $sum / $count;

	//echo "<br />Variance (lower is better): " . variance($weekDifference);
}

// calculate timeline variables
$weeksAgo = array_reverse($weeksAgo);
if (count($weeksAgo) > 0) {
	$timelineStart = $weeksAgo[0];
} else {
	$timelineStart = 0;
}

if (count($weeksAgo) > 2) {
	$data = $weeksAgo;
	$data = array_splice($data, 1);
	$sumX = array_sum($keys);
	$sumY = array_sum($data);
	$sumXY = 0;
	$sumXSquared = 0;


	foreach ($data as $key => $point) {
		$sumXY += $key * $point;
		$sumXSquared += $key * $key;
	}

	$slope = (count($data) * $sumXY - $sumX * $sumY) / (count($data) * $sumXSquared - $sumX * $sumX);
	// $xBar = array_sum($keys) / count($keys);
	// $yBar = array_sum($data) / count($data);
	// $yIntercept = $yBar - $slope * $xBar;

	$prediction = round(end($weeksAgo) * -1 + abs($slope)); // prediction based on slope added to last week free
	$prediction = ($prediction <= 0) ? 1 : $prediction;

	$predictionText = ($prediction == 1) ? $prediction . " week" : $prediction . " weeks";

	if ($lastFreeInWeeks == 0) {
		$predictionText = "Currently Free";
	}
} else {
	$predictionText = 'Not enough data';
}

$champion_list = $read->champion_list();
$champion_name_list = '';

foreach ($champion_list as $champion) {
	$champion_name_list .= $champion['champion_name'] . ', ';
}

$champion_name_list = substr($champion_name_list, 0, -2);

function dateDiffInWeeks($date1, $date2) {
	if ($date1 > $date2) return dateDiffInWeeks($date2, $date1);
	$first = DateTime::createFromFormat('Y-m-d', $date1);
	$second = DateTime::createFromFormat('Y-m-d', $date2);
	
	$diff = $first->diff($second)->days / 7;
	return ($diff >= 1) ? round($diff) : 0;
}

function variance($arr) {
	$mean = array_sum($arr) / count($arr);

	$sos = 0; // sum of squares
	for ($i = 0; $i < count($arr); $i++) {
		$sos += pow($arr[$i] - $mean, 2);
	}

	return $sos / (count($arr) - 1); // divide by n - 1
}

function standardDeviation($arr) {
	return sqrt(variance($arr));
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, user-scalable=yes" />
	<title><?php echo $name; ?> Prediction</title>

	<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
	<link rel="stylesheet" href="/css/screen.css">
</head>
<body>
	<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

	  ga('create', 'UA-10943451-29', 'auto');
	  ga('send', 'pageview');

	</script>

	<header id="header">
		<h1><a href="/">League of Legends Champion History</a></h1>
		<h2>Free Champion Rotation Predictor</h2>
	</header>

	<main>
		<section id="search">
			<form action="" method="POST" id="search-form" onsubmit="return searchSubmit();">
				<div class="flex-container">
					<div id="search-wrapper" class="flex-item">
						<input type="search" name="champion" placeholder="Champion" id="search-field" class="awesomplete" data-list="<?php echo $champion_name_list; ?>" data-autofirst="true" />
					</div>
					<div id="submit-wrapper" class="flex-item">
						<input type="submit" value="" />
					</div>
				</div>
			</form>
		</section>
		
		<section id="prediction">
			<header>
				<div id="name-wrapper">
					<h1><?php echo $name; ?></h1>
					<h2><?php echo $title; ?></h2>
				</div>

				<div class="outer">
					<div class="inner">
						<img src="<?php echo $image; ?>" />
					</div>
				</div>
			</header>

			<article id="data">
				<div class="flex-container">
					<div class="flex-item">
						<div id="main-display">
							<div id="last-free"><span>Last</span> <span>Free</span></div>
							<time datetime=""><?php echo $lastFreeInWeeks; ?></time>
						</div>
					</div>
					<div class="flex-item">
						<div id="secondary-display">
							<p><span><?php echo $name; ?> has<?php echo $word1; ?></span> <span><?php echo $word2; ?>free <?php echo $times_free; ?></span></p>
						</div>
					</div>
				</div>

				<div class="flex-container">
					<div class="flex-item">
						<div id="timeline">
							<h3>Timeline</h3>

							<span id="line"></span>

							<div id="dots">
							<?php
							foreach ($weeksAgo as $key => $ago) {
								$left = ($timelineStart - $ago) / $timelineStart * 100;

								$itemClass = 'dot';
								$agoText = ($ago == 1) ? $ago . ' week ago' : $ago . ' weeks ago';
								if ($ago == 0) {
									$agoText = ' this week';
								}
								$title = 'Free ' . $agoText;
								if ($key == 0) {
									$itemClass = 'released';
									$title = 'Released ' . $agoText;
									echo '<span class="mark" style="left: ' . $left . '%"></span>';
									echo '<span class="label" style="left: ' . $left . '%">released</span>';
								}

								echo '<span class="' . $itemClass . '" style="left: ' . $left . '%" title="' . $title . '"></span>';
							}
							?>
							</div>

							<div id="key-wrapper">
								<div id="key">
									<span class="dot"></span>&nbsp;&nbsp;&nbsp; = free
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="flex-container">
					<div id="prediction-data" class="flex-item">
						<span id="free-in">Free in</span>
						<span id="prediction-text"><?php echo $predictionText; ?></span>
						<span id="best-prediction">(Best Prediction)</span>
					</div>
				</div>
			</article>
		</section>
	</main>

	<footer>
		<div id="footer-wrapper">
			<a href="https://lukasjoswiak.com">Lukas Joswiak</a> &mdash; <a href="mailto:lukas@lukasjoswiak.com">lukas@lukasjoswiak.com</a>
		</div>
	</footer>
	
	<script src="/js/awesomplete.min.js"></script>
	<script src="/js/main.js"></script>
</body>
</html>