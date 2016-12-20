<?php

$path = $_SERVER['DOCUMENT_ROOT'];
include_once($path . '/core/init.php');

$tuesday = date('Y-m-d', strtotime('last tuesday', strtotime('tomorrow')));
$next_tuesday = date('Y-m-d', strtotime('tuesday'));

$champions = $read->rotation_week($tuesday);

// get array of next week's champions
// will only be populated when new list is up, usually on Monday
$next_week_champions = $read->rotation_week($next_tuesday);

$next_week_exists = false;
if (count($next_week_champions) > 0 && strtotime($tuesday) - strtotime($next_tuesday) < 0) {
	$next_week_exists = true;
}

$champion_rotation_list = array();

foreach ($champions as $champion) {
	$id = $champion['champion_id'];

	$champ = $read->champion($id);
	$name = $champ['champion_name'];
	$key = $champ['champion_key'];

	$squareImage = 'https://ddragon.leagueoflegends.com/cdn/5.15.1/img/champion/' . $key . '.png';
	$tallImage = 'https://ddragon.leagueoflegends.com/cdn/img/champion/loading/' . $key . '_0.jpg';
	$champion_rotation_list[] = array('id' => $id, 'name' => $name, 'key' => $key, 'squareImage' => $squareImage, 'tallImage' => $tallImage);
}

$next_champion_rotation_list = array();
if ($next_week_exists) {
	foreach ($next_week_champions as $champion) {
		$id = $champion['champion_id'];
		
		$champ = $read->champion($id);
		$name = $champ['champion_name'];
		$key = $champ['champion_key'];
		
		$squareImage = 'https://ddragon.leagueoflegends.com/cdn/5.15.1/img/champion/' . $key . '.png';
		$tallImage = 'https://ddragon.leagueoflegends.com/cdn/img/champion/loading/' . $key . '_0.jpg';
		
		$next_champion_rotation_list[] = array('id' => $id, 'name' => $name, 'key' => $key, 'squareImage' => $squareImage, 'tallImage' => $tallImage);
	}
}

$champion_list = $read->champion_list();
$champion_name_list = '';

foreach ($champion_list as $champion) {
	$champion_name_list .= $champion['champion_name'] . ', ';
}

$champion_name_list = substr($champion_name_list, 0, -2);

function url($name) {
	return strtolower(str_replace('\'', '', str_replace(' ', '-', $name)));
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, user-scalable=yes" />
	<title>Champion Rotation Predictor</title>

	<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
	<link rel="stylesheet" href="/css/screen.css" />
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
		<div id="ad">
			<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
			<!-- Champion Rotation Predictor -->
			<ins class="adsbygoogle"
				 style="display:block"
				 data-ad-client="ca-pub-5514616542575292"
				 data-ad-slot="6477755013"
				 data-ad-format="auto"></ins>
			<script>
				(adsbygoogle = window.adsbygoogle || []).push({});
			</script>
		</div>

		<section id="search">
			<p>
				Enter a champion in the search box to view statistics about their rotation history and get a prediction on when they will be free next.
			</p>

			<form action="" method="POST" id="search-form" onsubmit="return searchSubmit();">
				<div class="flex-container">
					<div id="search-wrapper" class="flex-item">
						<input type="search" name="champion" placeholder="Champion" id="search-field" class="awesomplete" data-list="<?php echo $champion_name_list; ?>" data-autofirst="true" autofocus />
					</div>
					<div id="submit-wrapper" class="flex-item">
						<input type="submit" value="" />
					</div>
				</div>
			</form>
		</section>

		<article class="rotation">
			<h1>Current Rotation</h1>

			<ul>
				<?php
				foreach ($champion_rotation_list as $champion) {
					echo "<li><a href='/champion/" . url($champion['name']) . "'><img src='" . $champion['tallImage'] . "'' /><span class='champion_name'>" . $champion['name'] . "</span></a></li>";
				}					
				?>
			</ul>
		</article>
		
		<?php if ($next_week_exists) { ?>
		<article class="rotation">
			<h1>Next Week's Rotation</h1>
			
			<ul>
				<?php
				foreach ($next_champion_rotation_list as $champion) {
					echo "<li><a href='/champion/" . url($champion['name']) . "'><img src='" . $champion['tallImage'] . "'' /><span class='champion_name'>" . $champion['name'] . "</span></a></li>";
				}					
				?>
			</ul>
		</article>
		<?php } ?>
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
