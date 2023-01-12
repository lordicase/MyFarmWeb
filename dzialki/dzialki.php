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
	<title>Twoje działki</title>

	<link rel="Stylesheet" href="../style.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>
	<script src="mapa.js"></script>


</head>
<body>


	<?php require_once "../menu.html"; //DODAJEMY MENU?>

		<div class="content">
			<div>
				<h1>Działki</h1>
				<?php
					$user_id=$_SESSION['user_id'];
					$user_name=$_SESSION['user'];
					$user_email=$_SESSION['email'];

					//////////////////////////////////////////////////////////////////////////////////////////////POŁĄCZENIE
					require_once "../connect.php";

					mysqli_report(MYSQLI_REPORT_STRICT);//////////////////////////////////////////////////////////ZMIANA RAPORTOWANIA BŁĘDÓW
					try
					{
						$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
						if ($polaczenie->connect_errno!=0)
						{
							throw new Exception(mysqli_connect_errno());
						}
						else
						{


							$rezultat = $polaczenie->query("SELECT * FROM dzialki WHERE user_id='$user_id'");

							if (!$rezultat) throw new Exception($polaczenie->error);
							else
							{
								echo"<TABLE CELLPADDING=5 BORDER=1>";
								echo "<TR><TD>Numer ewidencyjny działki</TD><TD>Nazwa własna</TD><TD>Powierzchnia [ha]</TD><TD>Lokalizacja</TD><TD>Uprawa</TD><TD style='width:50px; border:none;'></TD><TD style='width:60px; border:none;'></TD><TD style='width:90px; border:none;'></TD></TR>\n";
								while($wiersz = $rezultat->fetch_assoc())
								{
									$id=$wiersz['id'];
									$nr_e_d=$wiersz['nr_e_d'];
									$nazwa_w=$wiersz['nazwa_w'];
									$pow=$wiersz['pow'];
									$adres=$wiersz['adres'];
									$uprawa=$wiersz['uprawa'];
									$typ_tabeli="dzialki";

									echo "<TR><TD>$nr_e_d</TD><TD>$nazwa_w</TD><TD>$pow</TD><TD>$adres</TD><TD>$uprawa</TD>
														<TD><form action='../usun.php' method='post'>
																<input type='hidden' name='id' value='$id'>
															  <input type='hidden' name='typ_tabeli' value='$typ_tabeli'>
																<input type='submit' value='Usuń' class='formnegative'></form></TD>
														<TD><form method='post'>
														    <input type='hidden' name='id' value='$id'>
														    <input type='hidden' name='nr_e_d' value='$nr_e_d'>
														    <input type='hidden' name='nazwa_w' value='$nazwa_w'>
														    <input type='hidden' name='pow' value='$pow'>
														    <input type='hidden' name='adres' value='$adres'>
														    <input type='hidden' name='uprawa' value='$uprawa'>
														    <input type='hidden' name='typ_tabeli' value='$typ_tabeli'>
														    <input type='submit' value='Zmień' class='form'></form></TD>
														<TD><form action='dodaj_obrys.php' method='post'>
																<input type='hidden' name='id' value='$id'>
																<input type='hidden' name='nr_e_d' value='$nr_e_d'>
																<input type='hidden' name='nazwa_w' value='$nazwa_w'>
																<input type='hidden' name='pow' value='$pow'>
																<input type='hidden' name='adres' value='$adres'>
																<input type='hidden' name='uprawa' value='$uprawa'>
														    <input type='submit' value='Dodaj obrys' class='form'></form></TD>
												</TR>\n";

								}
								echo"</table>";

							}
						$polaczenie->close();
						}

					}
					catch(Exception $e)
					{
						echo '<span style="color:red;">Błąd serwera! Proszę spróbować później</span>';
					///////////////////////////////////////////////////////////////////////////////////////////echo '<br />Informacja developerska: '.$e;
					}
				?>

			</div>
			<br />
			<form method='post'>
				 <input type='hidden' name='pokazac' value='tak'>
				<button onclick="pokaz_f_dodaj_d()" class='formpositive'>Dodaj działkę</button>
			</form>
			<br />
			<a href="dzialki_pdf.php"><button class='formpositive'>Generuj plik PDF</button></a>
			<br />
			<br />
			<?php

			if(isset($_SESSION['e_nazwa_d']) || isset($_POST['pokazac']))
			{
				echo"<div id='f_dodaj_d'>
					<h2>Dodaj działkę</h2>
					<form action='dodaj_d.php' method='post'>
						Numer ewidencyjny działki: <br /> <input type='text' name='nr_e_d' class='form' required /><br />

						Nazwa własna działki: <br /> <input type='text' name='nazwa_w' class='form' required /><br />";
																										//WYŚWIETLANIE BŁĘDU
						if (isset($_SESSION['e_nazwa_d']))
						{
							echo '<div class="error">'.$_SESSION['e_nazwa_d'].'</div>';

						}


				echo"Powierzchnia: <br /> <input type='number' step='0.001' name='pow' class='form' required /><br />

						Uprawa: <br /> <input type='text' name='uprawa' class='form' /><br />

						Lokalizacja: <br /> <input type='text' name='adres' class='form' required /><br />

						<br /><input type='submit' value='Dodaj' class='formpositive' />	<br /><br />

						<button onclick='schowaj_f()' class='formnegative'>Anuluj</button>
					</form>
				</div>";
				unset($_SESSION['e_nazwa_d']);
			}
			?>
			<?php
			if(isset($_POST['nr_e_d']))
			{
				$id=$_POST['id'];
				$nr_e_d=$_POST['nr_e_d'];
				$nazwa_w=$_POST['nazwa_w'];
				$pow=$_POST['pow'];
				$uprawa=$_POST['uprawa'];
				$adres=$_POST['adres'];


					echo"<div id='f_zmien_d'>
					<h2>Zmień wpis</h2>
					<form action='zmien_d.php' method='post'>
						<input type='hidden' name='id' value='$id'>
						Numer ewidencyjny działki: <br /> <input type='text' name='nr_e_d' class='form' value='$nr_e_d' required /><br />

						Nazwa własna działki: <br /> <input type='text' name='nazwa_w' class='form' value='$nazwa_w' required /><br />

						Powierzchnia: <br /> <input type='number' step='0.001' name='pow' class='form' value='$pow' required /><br />

						Uprawa: <br /> <input type='text' name='uprawa' class='form' value='$uprawa' /><br />

						Lokalizacja: <br /> <input type='text' name='adres' class='form' value='$adres' required /><br />

						<br /><input type='submit' value='Zmień' class='formpositive' />	<br /><br />
					</form>
					<button onclick='schowaj_f()' class='formnegative'>Anuluj</button>
					</div>";
					}
			?>



			<div id="map_dzialki"></div>

		</div>


	</div> <!-- ZAKOŃCZENIE <div class="wrapper"> Z MENU -->
		<div class="footer">Myfarm.pl &copy; 2019 Wszelkie prawa zastrzeżone.</div>








<script async defer	src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAWENxre4so2_Sjwehm60c-T7TU-_wK9pg&callback=initMap"></script>

</body>
</html>
