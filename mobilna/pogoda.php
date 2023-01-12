<?php
session_start();
if ((isset($_GET['lat']))||(isset($_SESSION['user_id'])))
	{
		if(isset($_GET['lat'])){
			$_SESSION['user_id']=$_GET['id'];
			$lat=$_GET['lat'];
			$lng=$_GET['lng'];
		}

		require_once "../connect.php";
		$user_id=$_SESSION['user_id'];
		mysqli_report(MYSQLI_REPORT_STRICT);
		try
		{
			$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
			if ($polaczenie->connect_errno!=0)
			{
				throw new Exception(mysqli_connect_errno());
			}
			else
			{
			//////////////////////////////////////////////////////////////////////////////////////POBIERANIE DANYCH O DŁUGOŚCI I SZEROKOŚCI

				$rezultat = $polaczenie->query("SELECT lat, lng FROM uzytkownicy WHERE id='$user_id'");
				$wiersz = $rezultat->fetch_assoc();
				$lat=$wiersz['lat'];
				$lng=$wiersz['lng'];
				$polaczenie->close();
			}

		}
		catch(Exception $e)
		{
			echo '<span style="color:red;">Błąd serwera! Proszę spróbować później</span>';
		}
		$url="https://api.openweathermap.org/data/2.5/forecast?lat=$lat&lon=$lng&&APPID=4a3f356baf0693f8e64272d327e900fe&units=metric";
		$json = file_get_contents ("$url");
		$details = json_decode ($json);
		for ($i=0; $i <40 ; $i++) {

			$time0=$details->list[$i]->dt;
			$time = date ("H", $time0);

			$timefinal = date ("H", $time0);
			if ($time2>$time) {
				$timefinal = date ("D H", $time0);
			}
			//print_r($details);
			$time2=$time;
			$czas[$i]=array($timefinal);
			$temp[$i]=array($details->list[$i]->main->temp);
			$tempmin[$i]=array($details->list[$i]->main->temp_min);
			$tempmax[$i]=array($details->list[$i]->main->temp_max);
			$wiatr[$i]=array($details->list[$i]->wind->speed);
			$chmury[$i]=array($details->list[$i]->clouds->all);
			$x="3h";
			$deszcz[$i]=array($details->list[$i]->rain->$x);
			$wilgotnosc[$i]=array($details->list[$i]->main->humidity);
			$miasto=$details->city->name;
		}


	}else{
		header('Location:../log_regis/logowanie.php');
		exit();
	}
?>


<!DOCTYPE HTML>
<html lang="pl">



<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Pogoda</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>
	<script src="mapa.js"></script>
	<link rel="Stylesheet" href="../style.css">

</head>
  <body>
  <?php require_once "../menu.html"; //DODAJEMY MENU?>

    <div class="content">


		<div id="f_zmien_pogode">
			<div id="mapa_pogody"></div>
			<a href='pogoda.php'><button type='button' name='ok' onclick='schowaj_mapa_pogody()' class='formpositive'>Zatwierdź</button></a>
		</div>

			<div id="wykresy">
				<?php echo"<h1>Prognoza pogody dla: $miasto </h1><button type='button' name='zmien' onclick='pokaz_mapa_pogody()' class='form'>Zmień</button>";?>
				<canvas id="temperatura" width="650" height="100"></canvas>
				<canvas id="wiatr" width="650" height="100"></canvas>
				<canvas id="deszcz" width="650" height="100"></canvas>
				<canvas id="chmury" width="650" height="100"></canvas>
			</div>
			<div id="Pogoda_burze">
				<h2>Mapa burz w Polsce</h2>
				<?php
					require_once "../glowna/simplehtmldom_1_9_1/simple_html_dom.php";
					$html = file_get_html("https://burze.dzis.net/?page=mapa");
					$info="https://burze.dzis.net/";
					$info  =$info.$html->find("#content_inner img",0)->src;

					echo "<img src='$info'  width='70%'>";
				?>
				<br/><h4>Oznaczenie czasów, pomiędzy którymi wystąpiło wyładowanie atmosfertczne.</h4><br/>
				<div style="display: inline-block; background-color:#F00; color: #ffffff; border: 1px solid black; padding:10px; margin:5px;">
					0-5 min.
				</div>
				<div style="display: inline-block; background-color:#FFA000; color: #ffffff; border: 1px solid black; padding:10px; margin:5px;">
					5-20 min.
				</div>
				<div style="display: inline-block; background-color:#FF0; color: black; border: 1px solid black; padding:10px; margin:5px;">
					20-35 min.
				</div>
				<div style="display: inline-block; background-color:#0F0; color: #ffffff; border: 1px solid black; padding:10px; margin:5px;">
					35-50 min.
				</div>
				<div style="display: inline-block; background-color:#0FF; color: #ffffff; border: 1px solid black; padding:10px; margin:5px;">
					50-65 min.
				</div>
				<div style="display: inline-block; background-color:#00A0FF; color: #ffffff; border: 1px solid black; padding:10px; margin:5px;">
					65-80 min.
				</div>
				<div style="display: inline-block; background-color:#00F; color: #ffffff; border: 1px solid black; padding:10px; margin:5px;">
					80-95 min.
				</div>
				<div style="display: inline-block; background-color:#A000FF; color: #ffffff; border: 1px solid black; padding:10px; margin:5px;">
					95-110 min.
				</div>
			</div>




    </div>



  </div> <!-- ZAKOŃCZENIE <div class="wrapper"> Z MENU -->
  <div class="footer">Myfarm.pl &copy; 2019 Wszelkie prawa zastrzeżone.</div>
		<script>

		var czas = <?php echo json_encode($czas); ?>;
		var temp = <?php echo json_encode($temp); ?>;
		var tempmin = <?php echo json_encode($tempmin); ?>;
		var tempmax = <?php echo json_encode($tempmax); ?>;
		var wiatr = <?php echo json_encode($wiatr); ?>;
		var deszcz = <?php echo json_encode($deszcz); ?>;
		var chmury = <?php echo json_encode($chmury); ?>;
		var wilgotnosc = <?php echo json_encode($wilgotnosc); ?>;



				var ctx = document.getElementById('temperatura');
				var myChart = new Chart(ctx, {
						type: 'line',
						data: {
								labels: czas,
								datasets: [{
										label: 'Średnia temperatura',
										data: temp,
										backgroundColor:'rgba(66, 255, 0, 1)',
										borderColor:'rgba(66, 255, 0, 1)',
										fill:false,
										borderWidth: 1
								},
								{
										label: 'Temperatura minimalna',
										data: tempmin,
										backgroundColor:'rgba(0, 18, 255, 1)',
										borderColor:'rgba(0, 18, 255, 1)',
										fill:false,
										borderWidth: 1
								},
								{
										label: 'Temperatura maksymalna',
										data: tempmax,
										backgroundColor:'rgba(255, 0, 0, 1)',
										borderColor:'rgba(255, 0, 0, 1)',
										fill:false,
										borderWidth: 1
								}]
						}
				});



				var ctx = document.getElementById('wiatr');
				var myChart = new Chart(ctx, {
						type: 'line',
						data: {
								labels: czas,
								datasets: [{
										label: 'Prędkość wiatru m/s',
										data: wiatr,
										backgroundColor:'rgba(66, 255, 0, 1)',
										borderColor:'rgba(66, 255, 0, 1)',
										fill:false,
										borderWidth: 1
								}]
						}
				});


				var ctx = document.getElementById('deszcz');
				var myChart = new Chart(ctx, {
						type: 'bar',
						data: {
								labels: czas,
								datasets: [{
										label: 'Opady mm/h',
										data: deszcz,
										backgroundColor:'rgba(5, 0, 255, 0.71)',

										borderColor:'rgba(5, 0, 255, 0.71)',

										borderWidth: 1
								}]
						},
						options: {
			 scales: {
					 yAxes: [{
							 ticks: {
									 suggestedMin: 0
							 }
					 }]
			 }
	 }
				});


				var ctx = document.getElementById('chmury');
				var myChart = new Chart(ctx, {
						type: 'line',
						data: {
								labels: czas,
								datasets: [{
										label: 'Zachmurzenie %',
										data: chmury,
										backgroundColor:'rgba(110, 172, 176, 1)',
										borderColor:'rgba(110, 172, 176, 1)',
										fill:false,
										borderWidth: 1
								},
								{
										label: 'Wilgotność %',
										data: wilgotnosc,
										backgroundColor:'rgba(82, 70, 179, 1)',
										borderColor:'rgba(82, 70, 179, 1)',
										fill:false,
										borderWidth: 1
								}]
						}
				});

		</script>
	<?php // print_r($details); ?>


	<script async defer	src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAWENxre4so2_Sjwehm60c-T7TU-_wK9pg&callback=initMap"></script>

  </body>

</html>
