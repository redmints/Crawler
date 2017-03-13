<!doctype html>
<html lang="fr">
	<head>
		<meta charset="UTF-8">
		<link href="style_crawler.css" rel="stylesheet" type="text/css">
		<title>Crawler - Statistiques</title>
		<!--Font style-->
		<link href="https://fonts.googleapis.com/css?family=Titillium+Web" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Gloria+Hallelujah" rel="stylesheet">
		<!--Scripts-->
		<script src="http://canvasjs.com/assets/script/canvasjs.min.js"></script>
		<script type="text/javascript">
			window.onload = function () {
				var piechart = new CanvasJS.Chart("piechart_mots", {
					backgroundColor: "#f9f9f9",
					legend: {
						maxWidth: 200,
						markerMargin: 8,
						horizontalAlign: "right",
						verticalAlign: "center"
					},
					data: [
					{
						type: "pie",
						showInLegend: true,
						explodeOnClick: true,
						legendText: "{indexLabel}",
						toolTipContent: "{indexLabel} - {y} (#percent %)",
						dataPoints: [
							@foreach ($keywords as $keyword)
								{ y: {{ $keyword["frequency"] }}, indexLabel: "{{ $keyword["text"] }}" },
							@endforeach
						]
					}
					]
				});
				//second chart
				var columnchart = new CanvasJS.Chart("columnchart_mots", {
					data: [
					{
						type: "column",
						toolTipContent: "{label} - {y}",
						dataPoints: [
							@foreach ($links as $link)
								{ label: "{{ $link["title"] }} - {{ $link["text"] }}",  y: {{ $link["frequency"] }}  },
							@endforeach
						]
					}
					]
				});
				piechart.render();
				columnchart.render();
			}
		</script>
	</head>
	<body>
		<a href="./"><h1>WOBLE</h1></a>
		<div class="bloc">
			<h2>Statistiques de l'opération de crawling</h2>
			<p>
				Nombre de sites crawlés : {{ $nbsites }}<br>
			</p>
			<p>
				Vous trouverez ci-dessous le résultat de l'opération de crawling
				sous forme de graphiques et de tableaux.
			</p>
		</div>
		<br>
		<div class="tab_site">
			<h2>Tableaux des sites crawlés</h2>
			 <table style="width:100%; border-collapse:collapse;">
				<tr>
					<th>Titre</th>
					<th>Liens</th>
				</tr>
				@foreach ($sites as $site)
					<tr>
						<td>{{ $site->title }}</td>
						<td>{{ $site->url }}</td>
					</tr>
				@endforeach
			</table>
		</div>
		<h2>Les 10 mots les plus rencontrés</h2>
		<div id="piechart_mots"></div>
		<h2>Le mot le plus rencontré par sites</h2>
		<div id="columnchart_mots"></div>
	</body>
</html>
