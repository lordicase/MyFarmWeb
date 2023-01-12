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
	<title>Zabiegi</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
	<link rel="Stylesheet" href="../style.css">

</head>
<body>
	<script>
		function pokaz_f()
		{
  		$('#f_dodaj_z').fadeIn();
		}
		function schowaj_f()
		{
  		$('#f_dodaj_z').fadeOut();
			$('#f_zmien_z').fadeOut();

		}

	</script>
	<?php require_once "../menu.html"; //DODAJEMY MENU?>
		<div class="content">
		<h1>Zabiegi agrotechniczne</h1>
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
					$rezultat = $polaczenie->query("SELECT * FROM zabiegi WHERE user_id='$user_id' ORDER BY data ASC");

					if (!$rezultat) throw new Exception($polaczenie->error);
					else
					{
						echo"<TABLE CELLPADDING=5 BORDER=1>";
						echo "<TR><TD>Data</TD><TD>Nazwa działki</TD><TD>Typ zabiegu</TD><TD>Opis</TD><td style='width:50px; border:none;'></td><td style='width:60px; border:none;'></td></TR>\n";
						while($wiersz = $rezultat->fetch_assoc())
						{
							$id=$wiersz['id'];
							$data=$wiersz['data'];
							$typ_zabiegu=$wiersz['typ_z'];
							$opis=$wiersz['opis'];
							$nazwa_dzialki=$wiersz['nazwa_w_d'];
							$typ_tabeli="zabiegi";

							echo "<TR><TD>$data</TD><TD>$nazwa_dzialki	</TD><TD>$typ_zabiegu	</TD><TD>	$opis</TD>
												<TD><form action='../usun.php' method='post'>
														<input type='hidden' name='typ_tabeli' value='$typ_tabeli'>
														<input type='hidden' name='id' value='$id'>
														<input type='submit' value='Usuń' class='formnegative'></form></TD>
												<TD><form method='post'>
														<input type='hidden' name='id' value='$id'>
														<input type='hidden' name='data' value='$data'>
														<input type='hidden' name='typ_zabiegu' value='$typ_zabiegu'>
														<input type='hidden' name='opis' value='$opis'>
														<input type='hidden' name='nazwa_d' value='$nazwa_dzialki'>
														<input type='hidden' name='typ_tabeli' value='$typ_tabeli'>
														<input type='submit' value='Zmień' class='form'></form></TD>
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
			}
		?>
		<br />
		<button onclick="pokaz_f()" class='formpositive'>Dodaj zabieg</button>
		<br /><br />
		<a href="zabiegi_a_pdf.php"><button class='formpositive'>Generuj plik PDF</button></a>

		<div id="f_dodaj_z">
			<h2>Dodaj zabieg</h2>
			<form action="dodaj_z.php" method="post">
				Data: <br /> <input type="date" name="data" class="form" required /><br />

				Typ zabiegu: <br /> <input type="radio" name="typ_z" value="Uprawa" class="form" checked />Uprawa<br />
														<input type="radio" name="typ_z" value="Nawożenie" class="form"  />Nawożenie<br />
														<input type="radio" name="typ_z" value="Siew" class="form"  />Siew<br />
														<input type="radio" name="typ_z" value="Zbiory" class="form"  />Zbiory<br />

				Opis: <br /> <input type="text" name="opis" class="form"  /><br />

				Działka: <br />
				<?php
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
						$rezultat = $polaczenie->query("SELECT nazwa_w FROM dzialki WHERE user_id='$user_id'");
						if (!$rezultat) throw new Exception($polaczenie->error);
						else
						{
							while($wiersz = $rezultat->fetch_assoc())
							{
								$nazwa_dzialki=$wiersz['nazwa_w'];
								echo"<input type='radio' name='nazwa_w' value='$nazwa_dzialki' class='form'/>$nazwa_dzialki";
							}
						}
					$polaczenie->close();
					}
				}
				catch(Exception $e)
				{
					echo '<span style="color:red;">Błąd serwera! Proszę spróbować później</span>';
				}
				?>
				<br /><input type="submit" value="Dodaj" class="formpositive" />	<br />
			</form>
			<br />
			<button onclick='schowaj_f()'  class='formnegative'>Anuluj</button>
		</div>
		<?php
			if(isset($_POST['data']))
			{
				$id=$_POST['id'];
				$data=$_POST['data'];
				$typ_z=$_POST['typ_zabiegu'];
				$opis=$_POST['opis'];
				$nazwa_dzialki_wiersz=$_POST['nazwa_d'];

				echo"<div id='f_zmien_z'>
							<h2>Zmień zabieg</h2>
							<form action='zmien_z.php' method='post'>
							<input type='hidden' name='id' value='$id'>
								Data: <br /> <input type='date' name='data' class='form' value='$data' required /><br />

								Typ zabiegu: <br /> <input type='radio' name='typ_z' value='uprawa' class='form'";if($typ_z=="uprawa"){echo"checked";}echo"/>Uprawa<br />";
														 	echo"<input type='radio' name='typ_z' value='nawożenie' class='form'";if($typ_z=="nawożenie"){echo"checked";}echo"/>Nawożenie<br />";
															echo"<input type='radio' name='typ_z' value='siew' class='form'";if($typ_z=="siew"){echo"checked";}echo"/>Siew<br />";
															echo"<input type='radio' name='typ_z' value='siew' class='form'";if($typ_z=="Zbiory"){echo"checked";}echo"/>Zbiory<br />";
								echo"Opis: <br /> <input type='text' name='opis' class='form' value='$opis'  /><br />

								Działka: <br />";

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
										$rezultat = $polaczenie->query("SELECT nazwa_w FROM dzialki WHERE user_id='$user_id'");
										if (!$rezultat) throw new Exception($polaczenie->error);
										else
										{
											while($wiersz = $rezultat->fetch_assoc())
											{
												$nazwa_dzialki=$wiersz['nazwa_w'];
												echo"<input type='radio' name='nazwa_w' value='$nazwa_dzialki' class='form'";if($nazwa_dzialki=="$nazwa_dzialki_wiersz"){echo"checked";}echo"/>$nazwa_dzialki";
											}
										}
									$polaczenie->close();
									}
								}
								catch(Exception $e)
								{
									echo '<span style="color:red;">Błąd serwera! Proszę spróbować później</span>';
								}

				 echo"<br /><input type='submit' value='Zmień' class='formpositive' />	<br />
							</form>
							<br />
							<button onclick='schowaj_f()'  class='formnegative'>Anuluj</button>
						</div>";

			}



		?>










		</div>

	</div> <!-- ZAKOŃCZENIE <div class="wrapper"> Z MENU -->
	<div class="footer">Myfarm.pl &copy; 2019 Wszelkie prawa zastrzeżone.</div>










</body>
</html>
