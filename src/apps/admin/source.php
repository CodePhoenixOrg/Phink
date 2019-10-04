<html>

<body bgcolor="lightgrey">
	<center>
		<?php
		include_once 'puzzle/ipz_source.php';

		use \Puzzle\Source;

		$source = new Source;

		$file = $_GET["file"];

		$script = file_get_contents($file);
		$source = $source->highlightPhp($script, true);

		echo "<h1>Source du script $file</h1><br>\n";
		echo "<div style='text-align:left;width:800px;height:600px;background:white;overflow:scroll'>\n$source\n</div><br>\n";
		?>
	</center>
</body>

</html>