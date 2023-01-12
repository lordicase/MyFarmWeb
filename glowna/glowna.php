<?php
session_start();
if (!isset($_SESSION['logged']))
	{
		header('Location:../log_regis/logowanie.php');
		exit();
	}


$user_id=$_SESSION['user_id'];
$user_name=$_SESSION['user'];
$user_email=$_SESSION['email'];

//////////////////////////////////////////////////////////////////////////////////////////////POŁĄCZENIE
require_once "../connect.php";
require_once "simplehtmldom_1_9_1/simple_html_dom.php";
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


      $rezultat = $polaczenie->query("SELECT DISTINCT uprawa FROM dzialki WHERE user_id='$user_id'");

      if (!$rezultat) throw new Exception($polaczenie->error);
      else
      {
        $i=0;
        while($wiersz = $rezultat->fetch_assoc())
        {
          $pow_u=0;
          $uprawa=$wiersz['uprawa'];
          $rezultat1 = $polaczenie->query("SELECT * FROM dzialki WHERE user_id='$user_id' AND uprawa='$uprawa'");
          while($wiersz = $rezultat1->fetch_assoc())
            {

              $pow_u+=$wiersz['pow'];

            }
            $label[$i]=$uprawa;
            $dane[$i]=$pow_u;
            $pow_calkowita[0]+=$pow_u;
            $i++;
        }

      //	print_r($dane);
      //	print_r($label);


      }
			if ($pow_u==0) {
				$label[$i]='brak upraw';
				$dane[$i]=100;
			}
			//pobieranie ilości bydła
			$rezultat = $polaczenie->query("SELECT * FROM dziennik WHERE user_id='$user_id' AND data_ubycia=''");

			if (!$rezultat) throw new Exception($polaczenie->error);
			else
			{
				$ponizej12=0;
				$powyzej12=0;
				$powyzej24=0;
				while($wiersz = $rezultat->fetch_assoc())
        {
          $data_urodzenia=$wiersz['data_urodzenia'];

					if (strtotime($data_urodzenia)>(time ()-31536000)) {
						$ponizej12++;
					}
					if ((strtotime($data_urodzenia)<(time ()-31536000))&&(strtotime($data_urodzenia)>(time ()-63072000))) {
						$powyzej12++;
					}
					if (strtotime($data_urodzenia)<(time ()-63072000)) {
						$powyzej24++;
					}

        }
			}


    $polaczenie->close();
    }

  }
  catch(Exception $e)
  {
    echo '<span style="color:red;">Błąd serwera! Proszę spróbować później</span>';
  ///////////////////////////////////////////////////////////////////////////////////////////echo '<br />Informacja developerska: '.$e;
  }



  /*
          Klasa           .nazwaklasy
          ID              #nazwaid
          tag             img
          klasa + name    .nazwaklasy[name='nazwa']
  */
	$html = file_get_html("https://www.farmer.pl/agroskop/zboza/");
	for ($i=0; $i < 16; $i++) {
		$info[$i]  = $html->find(".srednia td",$i)->innertext;
	}



?>
<!DOCTYPE HTML>
<html lang="pl">



<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Strona główna</title>
	<link rel="Stylesheet" href="../style.css">
	<link rel="shortcut icon" src="../favicon.ico">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>

</head>
<body>


		<?php require_once "../menu.html"; //DODAJEMY MENU?>

		<div class="content">
			<div >
				<div id="srednie_ceny">
					<?php
					echo "Średnie ceny w Polsce:";
					echo"<TABLE CELLPADDING=2 BORDER=none style='border: 0px;'>";
						$html = file_get_html("https://www.farmer.pl/agroskop/zboza/");
						for ($i=1; $i < 16; $i++) {
							echo "<TR><TD style='border: 0px; font-size: 15px;'>".$html->find("tr th",$i)->innertext."</TD>";
							echo "<TD style='border: 0px; font-size: 15px;'>".$html->find(".srednia td",$i)->innertext."</TD></TR>";

						}
					echo"</table>";
					?>
				</div>
		    <div id="wykres">
		      <canvas id="wyk" style='width: 100%; height:300px;'></canvas>
		    </div>

				<div id="wiadomosci">
					<script type="text/javascript" src="https://www.farmer.pl/eksport_rss/0_5_250_1_3.js"></script><noscript><a href="https://www.farmer.pl/">Wiadomości portalu farmer.pl</a></noscript>

				</div>
			</div>
			<div id="podsumowanie">
				<?php
					if ($ponizej12!=0) {
						echo "W stadzie znajduje się $ponizej12 sztuk w wieku ponieżej 12 miesięcy. <br />";

						}
					if ($powyzej12!=0) {
						echo "W stadzie znajduje się $powyzej12 sztuk w wieku powyżej roku. <br />";
						}
					if ($powyzej24!=0) {
					echo "W stadzie znajduje się $powyzej24 sztuk w wieku powyżej 2 lat. <br />";
						}

				?>
			</div>

	</div>

	</div> <!-- ZAKOŃCZENIE <div class="wrapper"> Z MENU -->
<div class="footer">Myfarm.pl &copy; 2019 Wszelkie prawa zastrzeżone.</div>


		<script>
	// WYKRES
	  var czas = <?php echo json_encode($label); ?>;
	  var temp = <?php echo json_encode($dane); ?>;

	      var ctx = document.getElementById('wyk');
	      var myChart = new Chart(ctx, {
	          type: 'doughnut',
	          data: {
	              labels: czas,
	              datasets: [{
	                  label: 'Struktura',
	                  data: temp,
	                  backgroundColor:[
	                    '#3aa47b',
	                    '#0dd720',
	                    '#55d011',
	                    '#9af2f5',
	                    '#43804d',
	                    '#9192b3',
	                    '#00522b',
	                    '#abf600',
	                    '#679d27',
	                    '#2e7e08',
	                    '#2e0482'
	                  ],

	                  fill:false,
	                  borderWidth: 1
	              }]
	          },
	          options:{
	            responsive: true,
							legend:{
								position: 'bottom',
							},
	            title:{
	              display: true,
	              text: 'Struktura upraw ha'
	            },
							animation:{
								animateScale: true,
								animateRotate: true
							}
	          }
	      });
	      </script>







</body>
</html>
