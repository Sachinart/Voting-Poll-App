<?php
	/* include necessary functions */
	include_once 'util/functions.php';

	// start session
	sec_session_start();
	unset($_SESSION['redirectLogin']);
	
	$_SESSION['redirectLogin'] = "votes.php";
	
	$pagename = "Votes";
	$candidateVotes = Array();
	$candidateNames = Array();
	
	//getting top three member names with their votes
	$queryVote ="SELECT CONCAT(firstName, ' ', lastName) as name, COUNT(voteFrom) as votes FROM votes JOIN  members ON members.id = votes.voteTo GROUP BY voteTo ORDER BY COUNT(voteFrom) DESC LIMIT 3";
	$stmtVote = $mysqli->prepare($queryVote);
	
	if ($stmtVote) {
        $resultVote = $mysqli->query($queryVote);
    } else {
		$rtnMsg = 'Database error, query: ' . $queryVote . ', errno: ' . $mysqli->errno . ', error: ' . $mysqli->error;
		die($rtnMsg);
    }
	
	while($votes = $resultVote->fetch_assoc()){
		array_push($candidateVotes,$votes['votes']); // Array contains votes of top three members
		array_push($candidateNames,$votes['name']); // Array contains name of top three members
	}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><?php echo SITE_NAME; ?> | <?php echo $pagename; ?></title>
	<?php
		include("includes/library.php");
	?>

</head><!--/head-->

<body>
<?php
	include("includes/headerhtml.php");
?>

	<div class="container top-pane">
		<div class="row">
			<canvas id="pie-chart" width="1100" height="450"></canvas>
		</div>
	</div>
<?php
	include("includes/footerhtml.php");
?>
	
<script>

	var colors = ['#5c93e4','#72d598','#d56a72']; // Array contains colors
	var candidates = <?php echo json_encode($candidateNames); ?>; // Array contains candidate names
	var candidateVotes = <?php echo json_encode($candidateVotes); ?>; // Array contains candidate Votes

/*	Chart.pluginService.register({
	beforeRender: function (chart) {
		if (chart.config.options.showAllTooltips) {
			// create an array of tooltips
			// we can't use the chart tooltip because there is only one tooltip per chart
			chart.pluginTooltips = [];
			chart.config.data.datasets.forEach(function (dataset, i) {
				chart.getDatasetMeta(i).data.forEach(function (sector, j) {
					chart.pluginTooltips.push(new Chart.Tooltip({
						_chart: chart.chart,
						_chartInstance: chart,
						_data: chart.data,
						_options: chart.options.tooltips,
						_active: [sector]
					}, chart));
				});
			});

			// turn off normal tooltips
			chart.options.tooltips.enabled = false;
		}
	},
	afterDraw: function (chart, easing) {
		if (chart.config.options.showAllTooltips) {
			// we don't want the permanent tooltips to animate, so don't do anything till the animation runs atleast once
			if (!chart.allTooltipsOnce) {
				if (easing !== 1)
					return;
				chart.allTooltipsOnce = true;
			}

			// turn on tooltips
			chart.options.tooltips.enabled = true;
			Chart.helpers.each(chart.pluginTooltips, function (tooltip) {
				tooltip.initialize();
				tooltip.update();
				// we don't actually need this since we are not animating tooltips
				tooltip.pivot();
				tooltip.transition(easing).draw();
			});
			chart.options.tooltips.enabled = false;
		}
	}
});*/
		
	new Chart(document.getElementById("pie-chart"), {
	type: 'pie',
	data: {
	  labels: candidates,
	  datasets: [{
		label: "Votes",
		backgroundColor: colors,
		data: candidateVotes,
	  }]
	},
	options: {
		showAllTooltips: true,
    	pieceLabel: {
            render: 'value',
            fontSize: 16,
            fontStyle: 'bold',
            fontColor: '#000',
            fontFamily: '"Lucida Console", Monaco, monospace'
        },
		title: {
			display: true,
			text: 'Votes'
		}
	}
	});

</script>
</body>
</html>