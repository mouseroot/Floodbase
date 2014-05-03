<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="shortcut icon" href="../../assets/ico/favicon.ico">
	<style>
		body {
			background-color: #F0F0F0;
		}

		.center-text {
			text-align: center;
		}

		li.active > a {
			color: red;
		}

	</style>
	<title>FloodBase</title>

	<!-- Bootstrap core CSS -->
	<link href="css/bootstrap.min.css" rel="stylesheet">

	<!-- Custom styles for this template -->
	<link href="justified-nav.css" rel="stylesheet">

	<!-- Just for debugging purposes. Don't actually copy this line! -->
	<!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>

<body>

<div class="container">

	<div class="masthead">
		<h3 class="text-muted">FloodBase - Mirrored Torrents</h3>
		<?php
		//Category switch
		if(isset($_GET['cat']) && !is_null($_GET['cat']))
		{
			$category = strtoupper($_GET['cat']);
		}
		else
		{
			$category = "All";
		}
		?>
		<ul class="nav nav-justified">
			<?php
				if($category == "All")
				{
					echo '<li class="active"><a href="/temp">All</a></li>';
				}
				else
				{
					echo '<li><a href="/temp">All</a></li>';
				}
			?>

			<?php
				if($category == "ANIME")
				{
					echo '<li class="active"><a href="?cat=anime">Anime</a></li>';
				}
				else
				{
					echo '<li><a href="?cat=anime">Anime</a></li>';
				}
			?>

			<?php
				if($category == "TV")
				{
					echo '<li class="active"><a href="?cat=tv">Television</a></li>';
				}
				else
				{
					echo '<li><a href="?cat=tv">Television</a></li>';
				}
			?>
			
			<?php
				if($category == "XXX")
				{
					echo '<li class="active"><a href="?cat=xxx">Adult</a></li>';
				}
				else
				{
					echo '<li><a href="?cat=xxx">Adult</a></li>';
				}
			?>

			<?php 
				if($category == "MOVIES")
				{
					echo '<li class="active"><a href="?cat=movies">Movies</a></li>';
				}
				else
				{
					echo '<li><a href="?cat=movies">Movies</a></li>';
				}
			?>
			
			<?php
				if($category == "MUSIC")
				{
					echo '<li class="active"><a href="?cat=music">Music</a></li>';
				}
				else
				{
					echo '<li><a href="?cat=music">Music</a></li>';
				}
			?>
			
			<?php
				if($category == "BOOKS")
				{
					echo '<li class="active"><a href="?cat=books">Books</a></li>';
				}
				else
				{
					echo '<li><a href="?cat=books">Books</a></li>';
				}
			?>

			<?php
				if($category == "OTHER")
				{
					echo '<li class="active"><a href="?cat=other">Other</a></li>';
				}
				else
				{
					echo '<li><a href="?cat=other">Other</a></li>';
				}
			?>
			
			<li><a href="?action=refresh">Update Backup</a></li>
		</ul>
	</div>
	
	<p><?php if(isset($_GET['action'])) { echo "Backup Updated"; } else { echo "Kickass Torrents Backup"; } ?></p>
			
	<div class="panel panel-default">
		
		<!-- Default panel contents -->
		<div class="panel-heading"><?php echo "Showing (400) " . strtolower($category) . " torrents"; ?></div>
		
		<!-- Table -->
		<table class="table">
			<tr>
				<th>Category</th>
				<th class='center-text'>Torrent Download Link</th>
			</tr>

			<?php
			//Fetch function
			if(isset($_GET['action']) && !is_null($_GET['action']))
			{
				$action = $_GET['action'];
				switch ($action) {
					case 'refresh':
						if(file_exists("hourlydump.txt.gz"))
						{
							unlink("hourlydump.txt.gz");
						}
						$hourlydump = file_get_contents("http://kickass.to/hourlydump.txt.gz");
						file_put_contents("hourlydump.txt.gz", $hourlydump);
						break;
					
					default:
						//Do nothing
						break;
				}
			}
			function parseKickAss($fstr)
			{
				$kickass_gz = gzopen($fstr, "r");
				$out_str = "";
				$out_obj = array();
				while($line = gzgets($kickass_gz, 4096))
				{
					$items = explode("|", $line);
					$data = [
						"hash" => $items[0],
						"name" => $items[1],
						"category"  => $items[2],
						"kickass_link" => $items[3],
						"torrent" => $items[4]
					];
					array_push($out_obj, $data);
				}
				return $out_obj;
			}
			$torrents = parseKickAss("hourlydump.txt.gz");
			$counter = 0;
			foreach($torrents as $t)
			{
				
				
				if($category !== "All" && strtoupper($t["category"]) === $category)
				{
					$counter++;
					$itemStr = "<tr>";
					$itemStr .= "<td>$t[category]</td>";
					$itemStr .= "<td class='center-text'><a href=$t[torrent]>$t[name]</a></td>";
					$itemStr .= "</tr>";
					echo "$itemStr";	
				}
				else if($category === "All")
				{
					$counter++;
					$itemStr = "<tr>";
					$itemStr .= "<td>$t[category]</td>";
					$itemStr .= "<td class='center-text'><a href=$t[torrent]>$t[name]</a></td>";
					$itemStr .= "</tr>";
					echo "$itemStr";	
				}
				if($counter > 500)
				{ 
					die(""); 
				}
			}
		?>
			<!--
			<tr>
				<td>Table Data</td>
				<td>Table Data</td>
				<td>Table Data</td>
				<td>Table Data</td>
				<td>Table Data</td>
			</tr>
			-->
		</table>
	</div>
	
	<!-- Site footer -->
	<div class="footer">
		<p>&copy; Mousetech.org 2014</p>
	</div>
</div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
  </body>
</html>
