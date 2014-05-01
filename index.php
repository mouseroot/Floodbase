<!DOCTYPE html>
<html lang="en-US">
	<head>
		<title>FloodBase</title>
	</head>
	<body>
		<p>Welcome to FloodBase</p><br />
		<a href="/FloodBase">All Categories</a>
		<a href="?cat=anime">Anime</a>
		<a href="?cat=tv">Telivision</a>
		<a href="?cat=xxx">Adult</a>
		<a href="?cat=movies">Movies</a>
		<a href="?cat=music">Music</a>
		<a href="?cat=books">Books</a>
		<a href="?cat=other">Other</a><br />
		<a href="?action=refresh">Update kickass backup</a><br />
		<?php

			//Category switch
			if(isset($_GET['cat']) && !is_null($_GET['cat']))
			{
				$category = strtoupper($_GET['cat']);
			}
			else
			{
				$category = "ALL";
			}

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
						echo "New file downloaded and list refreshed<br />";
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
			foreach($torrents as $t)
			{
				if($category !== "ALL" && strtoupper($t["category"]) === $category)
				{
					echo "<b>$t[category]</b>&nbsp;<a href=$t[kickass_link]>$t[name]</a> -- <a href='$t[torrent]'>Direct link</a><br />";	
				}
				else if($category === "ALL")
				{
					echo "<b>$t[category]</b>&nbsp;<a href=$t[kickass_link]>$t[name]</a><br />";		
				}
			}
/*
			$kickass_backup = "hourlydump.txt.gz";
			$z = gzopen($kickass_backup, "r") or die("Unable to fetch kickass backup");
			$string = "";
			while($line = gzgets($z,1024)) {
				$string .= $line;
			}
			echo "<pre>$string</pre>";
			gzclose($z);
*/
		?>
	</body>
</html>
