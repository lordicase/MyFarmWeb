<?php
session_start();
if (!isset($_SESSION['logged']))
	{
		header('Location:../log_regis/logowanie.php');
		exit();
	}
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Alerty</title>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
	<link rel="Stylesheet" href="../style.css">
</head>

  <body>
  	<?php require_once "menu.html"; //DODAJEMY MENU
			    require_once "znaczniki.js"; //DODAJEMY JS
				  require_once "../connect.php";?>

	    <div class="content">
	      <html>
	        <body>
						<div id='menu_mapy'>
							<div id='dodaj_alert'>
								<h2>Dodaj alert</h2>

								<?php
									$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
								  if ($polaczenie->connect_errno!=0){throw new Exception(mysqli_connect_errno());}
								  else
								  {
										echo"	Uprawa:	 <br />	<select id='uprawa' name='uprawa' class='form'>
																			<option value='0'></option>";
								    $rezultat = $polaczenie->query("SELECT DISTINCT uprawa FROM baza_szkodnikow");
											while($wiersz = $rezultat->fetch_assoc())
											{
												echo"<option value='$wiersz[uprawa]'>$wiersz[uprawa]</option>";
											}
											echo"</select><br />
											Szkodnik:	 <br />	<select id='szkodnik' name='szkodnik' class='form'>
																				<option value='0'></option>";
										$rezultat = $polaczenie->query("SELECT DISTINCT szkodnik FROM baza_szkodnikow");
											while($wiersz = $rezultat->fetch_assoc())
											{
												echo"<option value='$wiersz[szkodnik]'>$wiersz[szkodnik]</option>";
											}
											echo"</select><br />";
										$polaczenie->close();
									}
								?>

					 			<br /><input type="button" name="dodaj" id="zatwierdz" onclick="initMap()" value="Dodaj" class='formpositive'>
								<br /><br />
							</div>
							<div id='legenda'>
								<div style="display: inline-block">

									<label><input type="checkbox" id="Zbożowe"  value="Zbożowe" checked class='form'>Zbożowe<img src="../img/zielony.png"></label><br/>
									<label><input type="checkbox" id="Groch"  value="Groch" checked class='form'>Groch<img src="../img/błękitny.png"></label><br/>
									<label><input type="checkbox" id="Łubin"  value="Łubin" checked class='form'>Łubin<img src="../img/czerwony.png"></label><br/>
									<label><input type="checkbox" id="Bobik"  value="Bobik" checked class='form'>Bobik<img src="../img/fioletowy.png"></label><br/>
								</div>
								<div style="display: inline-block">
									<label><input type="checkbox" id="Kukurydza" value="Kukurydza" checked class='form'>Kukurydza<img src="../img/niebieski.png"></label><br/>
									<label><input type="checkbox" id="Ziemniaki"  value="Ziemniaki" checked class='form'>Ziemniaki<img src="../img/pomarańczowy.png"></label><br/>
									<label><input type="checkbox" id="Buraki"  value="Buraki" checked class='form'>Buraki<img src="../img/żółty.png"></label><br/>
									<label><input type="checkbox" id="Rzepak"  value="Rzepak" checked class='form'>Rzepak<img src="../img/różowy.png"></label><br/>
								</div>
									<div style=" clear:both; "></div>
								<input type="button" name="zmien" id="zmien"  onclick="initMap()" value="Zmien" class='formpositive'>

							</div>
						</div>
	          <div id="map"></div>

	    </div>

  	</div> <!-- ZAKOŃCZENIE <div class="wrapper"> Z MENU -->
   <div class="footer">Myfarm.pl &copy; 2019 Wszelkie prawa zastrzeżone.</div>


<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAWENxre4so2_Sjwehm60c-T7TU-_wK9pg&callback=initMap"></script>
	</body>
</html>
