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
	<title>Księga rejestracji bydła</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
	<link rel="Stylesheet" href="../style.css">
	<script>
		function pokaz_f()
		{
  		$('#f_dodaj_dziennik').fadeIn();
		}
		function schowaj_f()
		{
  		$('#f_dodaj_dziennik').fadeOut();
			$('#f_zmien_dziennik').fadeOut();

		}

	</script>
</head>
  <body>
  <?php require_once "../menu.html"; //DODAJEMY MENU?>

	<?php
		$user_id=$_SESSION['user_id'];
		$user_name=$_SESSION['user'];
		$user_email=$_SESSION['email'];
		require_once "../connect.php";
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
				$rezultat = $polaczenie->query("SELECT * FROM dziennik WHERE user_id='$user_id' ORDER BY data_oznakowania ASC");

				if (!$rezultat) throw new Exception($polaczenie->error);
				else
				{
					echo"<div class='content'>
						<h1>Księga rejestracji bydła</h1>
						<TABLE border='1'>
							<TR align='center' width=100%>
								<TD rowspan='2'>Lp.</TD><TD rowspan='2'>Numer identyfikacyjny zwierzęcia</TD><TD rowspan='2'>Data urodzenia</TD><TD rowspan='2'>Płeć</TD><TD rowspan='2'>Kod rasy</TD><TD rowspan='2'>Data oznakowania</TD><TD>Numer matki</TD><TD>Data przybycia</TD><TD rowspan='2'>Dane przybycia</TD><TD>Data ubycia</TD><TD>Dane ubycia</TD><td style='width:50px; border:none;'></td><td style='width:60px; border:none;'></td>
							</TR>
							<TR align='center'>
								<TD>Numer ojca</TD><TD>Kod zdarzenia</TD><TD>Kod zdarzenia</TD><TD>Dane przewoźnika</TD>
							</TR>";
							$lp=1;
							while($wiersz = $rezultat->fetch_assoc())
							{
								$id=$wiersz['id'];
								$nr_zwierzecia=$wiersz['nr_zwierzecia'];
								$data_urodzenia=$wiersz['data_urodzenia'];
								$plec=$wiersz['plec'];
								$kod_rasy=$wiersz['kod_rasy'];
								$data_oznakowania=$wiersz['data_oznakowania'];
								$nr_matki=$wiersz['nr_matki'];
								$nr_ojca=$wiersz['nr_ojca'];
								$data_przybycia=$wiersz['data_przybycia'];
								$kod_zdarzenia_p=$wiersz['kod_zdarzenia_p'];
								$dane_przybycia=$wiersz['dane_przybycia'];
								$data_ubycia=$wiersz['data_ubycia'];
								$kod_zdarzenia_u=$wiersz['kod_zdarzenia_u'];
								$dane_ubycia=$wiersz['dane_ubycia'];
								$dane_przewoznika=$wiersz['dane_przewoznika'];
								$uwagi=$wiersz['uwagi'];
								$typ_tabeli='dziennik';

								echo "<TR align='center'>
									<TD rowspan='2'>$lp</TD><TD rowspan='2'>$nr_zwierzecia</TD><TD rowspan='2' width='80'>$data_urodzenia</TD><TD rowspan='2'>$plec</TD><TD rowspan='2'>$kod_rasy</TD><TD rowspan='2' width='80'>$data_oznakowania</TD><TD>$nr_matki</TD><TD width='80'>$data_przybycia</TD><TD rowspan='2'>$dane_przybycia</TD><TD width='80'>$data_ubycia</TD><TD>$dane_ubycia</TD>
									<TD rowspan='2'><form action='../usun.php' method='post'>
																	<input type='hidden' name='id' value='$id'>
																	<input type='hidden' name='typ_tabeli' value='$typ_tabeli'>
																	<input type='submit' value='Usuń' class='formnegative'></form></TD>
									<TD rowspan='2'><form method='post'>
																	<input type='hidden' name='id' value='$id'>
																	<input type='hidden' name='nr_zwierzecia' value='$nr_zwierzecia'>
																	<input type='hidden' name='data_urodzenia' value='$data_urodzenia'>
																	<input type='hidden' name='plec' value='$plec'>
																	<input type='hidden' name='kod_rasy' value='$kod_rasy'>
																	<input type='hidden' name='data_oznakowania' value='$data_oznakowania'>
																	<input type='hidden' name='nr_matki' value='$nr_matki'>
																	<input type='hidden' name='nr_ojca' value='$nr_ojca'>
																	<input type='hidden' name='data_przybycia' value='$data_przybycia'>
																	<input type='hidden' name='kod_zdarzenia_p' value='$kod_zdarzenia_p'>
																	<input type='hidden' name='dane_przybycia' value='$dane_przybycia'>
																	<input type='hidden' name='data_ubycia' value='$data_ubycia'>
																	<input type='hidden' name='kod_zdarzenia_u' value='$kod_zdarzenia_u'>
																	<input type='hidden' name='dane_ubycia' value='$dane_ubycia'>
																	<input type='hidden' name='dane_przewoznika' value='$dane_przewoznika'>
																	<input type='hidden' name='uwagi' value='$uwagi'>
																	<input type='hidden' name='typ_tabeli' value='$typ_tabeli'>
																	<input type='submit' value='Zmień' class='form'></form></TD>
								</TR>
								<TR>
									<TD>$nr_ojca</TD><TD>$kod_zdarzenia_p</TD><TD>$kod_zdarzenia_u</TD><TD>$dane_przewoznika</TD>
								</TR>";
								$lp++;
							}
						echo"</TABLE>";
				}
				$polaczenie->close();
			}
		}
		catch(Exception $e)
		{
			echo '<span style="color:red;">Błąd serwera! Proszę spróbować później</span>';
		}
	?>
	<br/>
	<button onclick="pokaz_f()" class='formpositive'>Dodaj wpis</button>
	<br/><br/>
	<a href="ksiega_pdf.php"><button class='formpositive'>Generuj plik PDF</button></a>


		<div id="f_dodaj_dziennik">
			<h2>Dodaj wpis</h2>
			<form action='dodaj_wpis.php' method='post'>
				<div style="float:left; margin:10px;">
					Nr. zwierzęcia:<br /><input type='text' name='nr_zwierzecia'  class='form' maxlength='14'><br />
					Data urodzenia:<br /><input type='date' name='data_urodzenia'  class='form'><br />
					Płeć [K/M]:<br /><input type='text' name='plec'  class='form' maxlength='1'><br />
					Kod rasy:<br /><input type='text' name='kod_rasy'  class='form' maxlength='2'><br />
					Data oznakowania:<br /><input type='date' name='data_oznakowania'  class='form'><br />
					Nr. matki:<br /><input type='text' name='nr_matki'  class='form' maxlength='14'><br />
					Nr. ojca:<br /><input type='text' name='nr_ojca'  class='form' maxlength='14'><br />
				</div>
				<div style="float:left; margin:10px;">
					Data przybycia:<br /><input type='date' name='data_przybycia'  class='form'><br />
					Kod zdarzenia:<br /><input type='text' name='kod_zdarzenia_p'  class='form' maxlength='3'><br />
					Dane przybycia:<br /><input type='text' name='dane_przybycia'  class='form'><br />
					Data ubycia:<br /><input type='date' name='data_ubycia'  class='form'><br />
					Kod zdarzenia:<br /><input type='text' name='kod_zdarzenia_u'  class='form' maxlength='3'><br />
					Dane ubycia:<br /><input type='text' name='dane_ubycia'  class='form'><br />
					Dane przewoźnika:<br /><input type='text' name='dane_przewoznika'  class='form'><br />
				</div>
				<div style="clear:both; margin:10px;">

					<input type='submit' value='Dodaj' class='formpositive'>
				</div>
			</form>
			<button onclick='schowaj_f()' class='formnegative'>Anuluj</button>
		</div>

		<?php
			if (isset($_POST['id']))
			{
				$id=$_POST['id'];
				$nr_zwierzecia=$_POST['nr_zwierzecia'];
				$data_urodzenia=$_POST['data_urodzenia'];
				$plec=$_POST['plec'];
				$kod_rasy=$_POST['kod_rasy'];
				$data_oznakowania=$_POST['data_oznakowania'];
				$nr_matki=$_POST['nr_matki'];
				$nr_ojca=$_POST['nr_ojca'];
				$data_przybycia=$_POST['data_przybycia'];
				$kod_zdarzenia_p=$_POST['kod_zdarzenia_p'];
				$dane_przybycia=$_POST['dane_przybycia'];
				$data_ubycia=$_POST['data_ubycia'];
				$kod_zdarzenia_u=$_POST['kod_zdarzenia_u'];
				$dane_ubycia=$_POST['dane_ubycia'];
				$dane_przewoznika=$_POST['dane_przewoznika'];
				$uwagi=$_POST['uwagi'];

				echo"
				<div id='f_zmien_dziennik'>
					<h2>Zmień wpis</h2>
					<form action='zmien_wpis.php' method='post'>
						<input type='hidden' name='id' value='$id'>
						<div style='float:left; margin:10px;'>
							Nr. zwierzęcia:<br /><input type='text' name='nr_zwierzecia' value='$nr_zwierzecia' class='form' maxlength='14'><br />
							Data urodzenia:<br /><input type='date' name='data_urodzenia' value='$data_urodzenia' class='form'><br />
							Płeć:<br /><input type='text' name='plec' value='$plec' class='form' maxlength='1'><br />
							Kod rasy:<br /><input type='text' name='kod_rasy' value='$kod_rasy' class='form' maxlength='2'><br />
							Data oznakowania:<br /><input type='date' name='data_oznakowania' value='$data_oznakowania' class='form'><br />
							Nr. matki:<br /><input type='text' name='nr_matki' value='$nr_matki' class='form' maxlength='14'><br />
							Nr. ojca:<br /><input type='text' name='nr_ojca' value='$nr_ojca' class='form' maxlength='14'><br />
						</div>
						<div style='float:left; margin:10px;'>
							Data przybycia:<br /><input type='date' name='data_przybycia' value='$data_przybycia' class='form'><br />
							Kod zdarzenia:<br /><input type='text' name='kod_zdarzenia_p' value='$kod_zdarzenia_p' class='form' maxlength='3'><br />
							Dane przybycia:<br /><input type='text' name='dane_przybycia' value='$dane_przybycia' class='form'><br />
							Data ubycia:<br /><input type='date' name='data_ubycia' value='$data_ubycia' class='form'><br />
							Kod zdarzenia:<br /><input type='text' name='kod_zdarzenia_u' value='$kod_zdarzenia_u' class='form' maxlength='3'><br />
							Dane ubycia:<br /><input type='text' name='dane_ubycia' value='$dane_ubycia' class='form'><br />
							Dane przewoźnika:<br /><input type='text' name='dane_przewoznika' value='$dane_przewoznika' class='form'><br />
						</div>
						<div style='clear:both; margin:10px;'>

							<br /><input type='submit' value='Zmień' class='formpositive'>
						</div>
					</form>
					<button onclick='schowaj_f()' class='formnegative'>Anuluj</button>
				</div>";
			}
		?>


		</div>  <!--  content -->

  </div> <!-- ZAKOŃCZENIE <div class="wrapper"> Z MENU -->
    <div class="footer">Myfarm.pl &copy; 2019 Wszelkie prawa zastrzeżone.</div>










  </body>
</html>
