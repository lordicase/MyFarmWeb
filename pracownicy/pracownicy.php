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
	<title>Pracownicy</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
	<link rel="Stylesheet" href="../style.css">

</head>
  <body>
    <?php require_once "menu.html"; //DODAJEMY MENU?>
      <div class="content">
        <div>
          <h1>Pracownicy</h1>
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


                $rezultat = $polaczenie->query("SELECT * FROM pracownicy WHERE user_id='$user_id'");

                if (!$rezultat) throw new Exception($polaczenie->error);
                else
                {
                  echo"<TABLE CELLPADDING=5 BORDER=1>";
                  echo "<TR><TD>Imię</TD><TD>Nazwisko</TD><TD>Numer telefonu</TD><TD>Wynagrodzenie zł/h</TD><td style='width:50px; border:none;'></td><td style='width:60px; border:none;'></td></TR>\n";
                  while($wiersz = $rezultat->fetch_assoc())
                  {
                    $id=$wiersz['id'];
                    $imie=$wiersz['imie'];
                    $nazwisko=$wiersz['nazwisko'];
                    $telefon=$wiersz['telefon'];
                    $placa=$wiersz['placa'];
                    $typ_tabeli="pracownicy";


                    echo "<TR><TD>$imie</TD><TD>$nazwisko</TD><TD>$telefon</TD><TD>$placa</TD>
                              <TD><form action='../usun.php' method='post'>
                                  <input type='hidden' name='id' value='$id'>
                                  <input type='hidden' name='typ_tabeli' value='$typ_tabeli'>
                                  <input type='submit' value='Usuń' class='formnegative'></form></TD>
                              <TD><form method='post'>
                                  <input type='hidden' name='id' value='$id'>
                                  <input type='hidden' name='imie' value='$imie'>
                                  <input type='hidden' name='nazwisko' value='$nazwisko'>
                                  <input type='hidden' name='telefon' value='$telefon'>
                                  <input type='hidden' name='placa' value='$placa'>
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
            ///////////////////////////////////////////////////////////////////////////////////////////echo '<br />Informacja developerska: '.$e;
            }
          ?>

        </div>
        <br />
        <form method='post'>
           <input type='hidden' name='pokazac' value='tak'>
          <button onclick="pokaz_f_dodaj_d()" class='formpositive'>Dodaj pracownika</button>
        </form>
				<br />
				<a href="pracownik_pdf.php"><button class='formpositive'>Generuj plik PDF</button></a>


        <?php

        if(isset($_POST['pokazac']))
        {
          echo"<div id='f_dodaj_p'>
            <h2>Dodaj pracownika</h2>
            <form action='dodaj_p.php' method='post'>
              Imię: <br /> <input type='text' name='imie' class='form' required /><br />

              Nazwisko: <br /> <input type='text' name='nazwisko' class='form' required /><br />


              Numer telefonu: <br /> <input type='text' name='telefon' class='form' required /><br />

              Wynagrodzenie: <br /> <input type='text' name='placa' class='form' /><br />

              <br /><input type='submit' value='Dodaj' class='formpositive' />	<br /><br />

              <button onclick='schowaj_f()' class='formnegative'>Anuluj</button>
            </form>
          </div>";
        }
        ?>

        <?php
  			if(isset($_POST['imie']))
  			{
  				$id=$_POST['id'];
          $imie=$_POST['imie'];
  				$nazwisko=$_POST['nazwisko'];
  				$telefon=$_POST['telefon'];
  				$placa=$_POST['placa'];


  					echo"<div id='f_zmien_p'>
  					<h2>Zmień wpis</h2>
  					<form action='zmien_p.php' method='post'>
  						<input type='hidden' name='id' value='$id'>
  						Imię: <br /> <input type='text' name='imie' class='form' value='$imie' required /><br />

  						Nazwisko: <br /> <input type='text' name='nazwisko' class='form' value='$nazwisko' required /><br />

  						Numer telefonu: <br /> <input type='number' name='telefon' class='form' value='$telefon' required /><br />

  						Wynagrodzenie: <br /> <input type='text' name='placa' class='form' value='$placa' /><br />


  						<br /><input type='submit' value='Zmień' class='formpositive' />	<br /><br />
  					</form>
  					<button onclick='schowaj_f()' class='formnegative'>Anuluj</button>
  					</div>";
  					}
  			?>




      </div>

    </div> <!-- ZAKOŃCZENIE <div class="wrapper"> Z MENU -->
			  <div class="footer">Myfarm.pl &copy; 2019 Wszelkie prawa zastrzeżone.</div>
      <script>

      function schowaj_f()
      {
        $('#f_zmien_p').fadeOut();
        $('#f_dodaj_p').fadeOut();
      }
      </script>
  </body>
</html>
