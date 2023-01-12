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
	<title>Zabiegi ochrony roślin</title>
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
		<h1>Zabiegi ochrony roślin</h1>
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
					$rezultat = $polaczenie->query("SELECT * FROM zabiegi_o_r WHERE user_id='$user_id' ORDER BY data ASC");

					if (!$rezultat) throw new Exception($polaczenie->error);
					else
					{
						echo"<TABLE CELLPADDING=5 BORDER=1>";
						echo "<TR><TD>Nazwa działki</TD><TD>Uprawa</TD><TD>Obszar wykonania zabiegu</TD><TD>Data</TD><TD>Nazwa środka</TD><TD>Dawka</TD><TD>Uwagi</TD><td style='width:50px; border:none;'></td><td style='width:60px; border:none;'></td></TR>\n";
						while($wiersz = $rezultat->fetch_assoc())
						{
							$id=$wiersz['id'];
              $nazwa_w_d=$wiersz['nazwa_w_d'];
							$uprawa=$wiersz['uprawa'];
							$pow_z=$wiersz['pow_z'];
              $data=$wiersz['data'];
							$nazwa=$wiersz['nazwa'];
							$dawka=$wiersz['dawka'];
              $uwagi=$wiersz['uwagi'];
							$typ_tabeli="zabiegi_o_r";

							echo "<TR><TD>$nazwa_w_d</TD><TD>$uprawa</TD><TD>$pow_z</TD><TD>$data</TD><TD>$nazwa</TD><TD>$dawka</TD><TD>$uwagi</TD>
												<TD><form action='../usun.php' method='post'>
														<input type='hidden' name='typ_tabeli' value='$typ_tabeli'>
														<input type='hidden' name='id' value='$id'>
														<input type='submit' value='Usuń' class='formnegative'></form></TD>
												<TD><form method='post'>
														<input type='hidden' name='id' value='$id'>
                            <input type='hidden' name='nazwa_w_d' value='$nazwa_w_d'>
														<input type='hidden' name='uprawa' value='$uprawa'>
														<input type='hidden' name='pow_z' value='$pow_z'>
														<input type='hidden' name='data' value='$data'>
														<input type='hidden' name='nazwa_s' value='$nazwa'>
                            <input type='hidden' name='dawka_s' value='$dawka'>
                            <input type='hidden' name='uwagi' value='$uwagi'>
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
		<a href="zabiegi_o_r_pdf.php"><button class='formpositive'>Generuj plik PDF</button></a>

		<div id="f_dodaj_z">
			<h2>Dodaj zabieg</h2>
			<form action="dodaj_z_o_r.php" method="post">
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
              echo"<BR />";
						}
					$polaczenie->close();
					}
				}
				catch(Exception $e)
				{
					echo '<span style="color:red;">Błąd serwera! Proszę spróbować później</span>';
				}
				?>
        Uprawa:<br /> <input type="text" name="uprawa" class="form" /><br />

        Powierzchnia wykonania zabiegu:<br /> <input type="text" name="pow_z" class="form" /><br />

				Data: <br /> <input type="date" name="data" class="form" required /><br />

        Nazwa środka: <br /> <input type="text" name="nazwa_s" class="form" required /><br />

        Dawka: <br /> <input type="text" name="dawka_s" class="form" required /><br />

				Uwagi: <br /> <input type="text" name="uwagi" class="form"  /><br />

				<br /><input type="submit" value="Dodaj"  class='formpositive'/>	<br />
			</form>
			<br />
			<button onclick='schowaj_f()'  class='formnegative'>Anuluj</button>
		</div>


		<?php
			if(isset($_POST['data']))
			{
				$id=$_POST['id'];
        $nazwa_dzialki_wiersz=$_POST['nazwa_w_d'];
        $uprawa=$_POST['uprawa'];
        $pow_z=$_POST['pow_z'];
				$data=$_POST['data'];
				$nazwa=$_POST['nazwa_s'];
				$dawka=$_POST['dawka_s'];
				$uwagi=$_POST['uwagi'];
        $typ_tabeli=$_POST['typ_tabeli'];


        echo"<div id='f_zmien_z'><form action='zmien_z_o_r.php' method='post'>
        <input type='hidden' name='id' value='$id'>
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
              echo"<br />";
            }
          $polaczenie->close();
          }
        }
        catch(Exception $e)
        {
          echo '<span style="color:red;">Błąd serwera! Proszę spróbować później</span>';
        }

        echo"Uprawa:<br /> <input type='text' name='uprawa' class='form' value='$uprawa' /><br />

        Powierzchnia wykonania zabiegu:<br /> <input type='text' name='pow_z' class='form' value='$pow_z'/><br />

        Data: <br /> <input type='date' name='data' class='form' required value='$data'/><br />

        Nazwa środka: <br /> <input type='text' name='nazwa_s' class='form' required value='$nazwa'/><br />

        Dawka: <br /> <input type='text' name='dawka_s' class='form' required value='$dawka'/><br />

        Uwagi: <br /> <input type='text' name='uwagi' class='form'  value='$uwagi'/><br />

        <br /><input type='submit' value='Zmień' class='formpositive' />	<br />
      </form>
      <br />
      <button onclick='schowaj_f()' class='formnegative'>Anuluj</button>
    </div>";

			}



		?>










		</div>

	</div> <!-- ZAKOŃCZENIE <div class="wrapper"> Z MENU -->
		<div class="footer">Myfarm.pl &copy; 2019 Wszelkie prawa zastrzeżone.</div>










</body>
</html>
