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
							{ y: 10584, indexLabel: "espace" },
							{ y: 775, indexLabel: "img" },
							{ y: 748, indexLabel: "alt" },
							{ y: 670, indexLabel: "border0" },
							{ y: 669, indexLabel: "srchttpforumouimathiasestmoche"},
							{ y: 650, indexLabel: "width11" },
							{ y: 650, indexLabel: "height11" },
							{ y: 501, indexLabel: "0px" },
							{ y: 133, indexLabel: "background"},
							{ y: 103, indexLabel: "br"}
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
							{ label: "site(1) - mathias",  y: 801  },
							{ label: "site(2) - chifumi", y: 753  },
							{ label: "site(3) - programmation", y: 854  },
							{ label: "site(4) - php",  y: 986  },
							{ label: "site(5) - clou de girofle",  y: 788  }
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
				Durée de l'opération de crawling : <br>
				Nombre de sites crawlés : <br>
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
				<tr>
					<td>t1</td>
					<td>lien1</td>
				</tr>
				<tr>
					<td>t2</td>
					<td>lien2</td>
				</tr>
			</table>
		</div>
		<h2>Les 10 mots les plus rencontrés</h2>
		<div id="piechart_mots"></div>
		<h2>Le mot le plus rencontré par sites</h2>
		<div id="columnchart_mots"></div>
		<div class="tab_site">
			<h2>Totalité des mots crawlés</h2>
			 <table style="width:100%; border-collapse:collapse;">
				<tr>
					<th>Mot</th>
					<th>Fréquence</th>
				</tr>
				<tr>
					<td>espace</td>
					<td>14584</td>
				</tr>
				<tr>
					<td>img</td>
					<td>775</td>
				</tr>
			</table>
		</div>
	</body>
</html>
