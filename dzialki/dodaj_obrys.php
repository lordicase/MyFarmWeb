<?php
session_start();
if ((!isset($_SESSION['logged'])&&(isset($_POST['nazwa_w']))))
	{
		header('Location:../log_regis/logowanie.php');
		exit();
	}


  $user_id=$_SESSION['user_id'];
  $id_dzialki=$_POST['id'];
  $nr_e_d=$_POST['nr_e_d'];
  $nazwa_w=$_POST['nazwa_w'];
  $pow=$_POST['pow'];
  $uprawa=$_POST['uprawa'];
  $adres=$_POST['adres'];
	require_once "../connect.php";
	$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
  if ($polaczenie->connect_errno!=0)
  {
    throw new Exception(mysqli_connect_errno());
  }
  else
  {
			//usuwanie dotychczasowych wpisów
		  $polaczenie->query("DELETE FROM mapy_dzialek WHERE user_id='$user_id' AND id_dzialki='$id_dzialki'");
	}
?>

<!DOCTYPE HTML>
<html lang="pl">



<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Dodaj obrys działki</title>

	<link rel="Stylesheet" href="../style.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
	<script src="mapa.js"></script>


</head>
  <body>


  	<?php require_once "../menu.html"; //DODAJEMY MENU?>

  		<div class='content'>
          <h1>Dodaj obrys działki</h1>
            <div id="menu_mapy">
              <?php
							//wyświetlenie danych dotyczących działki
                echo"<input type='hidden' name='user_id' id='user_id' value='$user_id'>
                     <input type='hidden' name='id_dzialki' id='id_dzialki' value='$id_dzialki'>
                     Nr. działki:<br /><input type='text' name='nr_e_d' id='nr_e_d' class='form' value='$nr_e_d' readonly /><br />
                     Nazwa działki:<br /><input type='text' name='nazwa_w' id='nr_e_d' class='form' value='$nazwa_w'readonly /><br />
                     Powierzchnia:<br /><input type='text' name='pow' id='nr_e_d' class='form' value='$pow' readonly /><br />
                     Uprawa:<br /><input type='text' name='uprawa' id='nr_e_d' class='form' value='$uprawa' readonly /><br />
                     Adres:<br /><input type='text' name='adres' id='nr_e_d' class='form' value='$adres' readonly /><br />";
              ?>
							<a href='dzialki.php'><button type="button" name="zatwierdz">Zatwierdź</button></a>
            </div>

            <div id="map_dzialki"></div>

      </div>
  
  	</div> <!-- ZAKOŃCZENIE <div class="wrapper"> Z MENU -->
    <div class="footer">Myfarm.pl &copy; 2019 Wszelkie prawa zastrzeżone.</div>
  <script async defer	src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAWENxre4so2_Sjwehm60c-T7TU-_wK9pg&callback=initMap"></script>

  </body>
</html>
