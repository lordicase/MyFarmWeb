<?php
session_start();
if (!isset($_SESSION['logged']))
	{
		header('Location:logowanie.php');
		exit();
	}
?>

<!DOCTYPE HTML>
<html lang="pl">



<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Zarządzanie</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
	<link rel="Stylesheet" href="style.css">

</head>
  <body>
    <?php require_once "menu.html"; //DODAJEMY MENU?>
      <div class="content">
        <div class="pudelko_menu">
          <a href="https://myfarm.pl/dzialki/dzialki.php"><img src="../img/dzialki.png" height="70" width="70"><br />Działki</a>
        </div>
        <div class="pudelko_menu">
          <a href="https://myfarm.pl/zabiegi/zabiegi.php"><img src="../img/zabiegi.png" height="70" width="70"><br />Zabiegi agrotechniczne</a>
        </div>
        <div class="pudelko_menu">
          <a href="https://myfarm.pl/zabiegi/zabiegi_o_r.php"><img src="../img/zabiegi_o_r.png" height="70" width="70"><br />Zabiegi ochrony roślin</a>
        </div>
        <div class="pudelko_menu">
          <a href="https://myfarm.pl/zwierzeta/ksiega_rejestracji.php"><img src="../img/rejestr.png" height="70" width="70"><br />Księga rejestracji bydła</a>
        </div>
				<div class="pudelko_menu">
          <a href="https://myfarm.pl/pracownicy/pracownicy.php"><img src="../img/pracownik.png" height="70" width="70"><br />Pracownicy</a>
        </div>
				<div class="pudelko_menu">
					<a href="https://myfarm.pl/plodozmian/plodozmian.php"><img src="../img/plodozmian.png" height="70" width="70"><br />Płodozmian</a>
				</div>

      </div>
    </div> <!-- ZAKOŃCZENIE <div class="wrapper"> Z MENU -->
			<div class="footer">Myfarm.pl &copy; 2019 Wszelkie prawa zastrzeżone.</div>

  </body>
</html>
