<?php
	session_start();
	if (!isset($_SESSION['logged']))
		{
			header('Location:../log_regis/logowanie.php');
			exit();
		}
	if (isset($_POST['rok_rozpoczecia'])) {
		$_SESSION['rok']=$_POST['rok_rozpoczecia'];
	}elseif(!isset($_SESSION['rok'])) {
			$_SESSION['rok']=@date(Y,time());
	}

?>

<!DOCTYPE HTML>
<html lang="pl">



<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Płodozmian</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>
	<link rel="Stylesheet" href="../style.css">

</head>
  <body>


				<?php require_once "../menu.html"; //DODAJEMY MENU?>

					<div class="content">
						<div>
							<h1>Płodozmian</h1>
							<form method="post">
							Rok rozpoczęcia:	<input type="text" name="rok_rozpoczecia" value="<?php echo @date(Y,time()) ?>" class='form'>
								<input type='submit' value='Zmień' class='form' />
							</form>
							<br/>

							<?php
								$user_id=$_SESSION['user_id'];
								$rok_rozpoczecia=$_SESSION['rok'];
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
											echo "<tr><td>Numer ewidencyjny działki</td><td>Nazwa własna</td><td>Pow [ha]</td><td>".$rok_rozpoczecia++."</td><td>".$rok_rozpoczecia++."</td><td>".$rok_rozpoczecia++."</td><td>".$rok_rozpoczecia++."</td><td>".$rok_rozpoczecia++."</td><td style='width:60px; border:none;'></td></tr>";

											while($wiersz = $rezultat->fetch_assoc())
											{
												$id_dzialki=$wiersz['id'];
												$nr_e_d=$wiersz['nr_e_d'];
												$nazwa_w=$wiersz['nazwa_w'];
												$pow=$wiersz['pow'];
												$rok_rozpoczecia=$_SESSION['rok'];
												$i=0;
												$polaczenie->query("INSERT INTO plodozmian VALUES (NULL, '$id_dzialki', '$rok_rozpoczecia', '' ,'', '', '$user_id')");
												$rok_rozpoczecia++;
												$polaczenie->query("INSERT INTO plodozmian VALUES (NULL, '$id_dzialki', '$rok_rozpoczecia', '', '', '', '$user_id')");
												$rok_rozpoczecia++;
												$polaczenie->query("INSERT INTO plodozmian VALUES (NULL, '$id_dzialki', '$rok_rozpoczecia', '', '', '', '$user_id')");
												$rok_rozpoczecia++;
												$polaczenie->query("INSERT INTO plodozmian VALUES (NULL, '$id_dzialki', '$rok_rozpoczecia', '', '', '', '$user_id')");
												$rok_rozpoczecia++;
												$polaczenie->query("INSERT INTO plodozmian VALUES (NULL, '$id_dzialki', '$rok_rozpoczecia', '', '', '', '$user_id')");
												$rok_rozpoczecia=$_SESSION['rok'];

												$rezultat1 = $polaczenie->query("SELECT * FROM plodozmian WHERE (user_id='$user_id' AND id_dzialki='$id_dzialki' AND rok>='$rok_rozpoczecia') ORDER BY rok ASC");
												if (!$rezultat1) throw new Exception($polaczenie->error);
												else
												{
													while($wiersz = $rezultat1->fetch_assoc())
													{
														$id[$i]=$wiersz['id'];
														$rok=$wiersz['rok'];
														$przedplon[$i]=$wiersz['przedplon'];
														$uprawa_g[$i]=$wiersz['uprawa_g'];
														$dodatkowa_pra[$i]=$wiersz['dodatkowa_pra'];
														$i++;
													}

													echo "<tr><td rowspan='3'>$nr_e_d</td><td rowspan='3'>$nazwa_w</td><td rowspan='3'>$pow</td><td style='font-size: 11px;'>$przedplon[0]</td><td style='font-size: 11px;'>$przedplon[1]</td><td style='font-size: 11px;'>$przedplon[2]</td><td style='font-size: 11px;'>$przedplon[3]</td><td style='font-size: 11px;'>$przedplon[4]</td><td rowspan='3'><form method='post'><input type='hidden' name='id0' value='$id[0]'><input type='hidden' name='id1' value='$id[1]'><input type='hidden' name='id2' value='$id[2]'><input type='hidden' name='id3' value='$id[3]'><input type='hidden' name='id4' value='$id[4]'><input type='hidden' name='nazwa_w' value='$nazwa_w'><input type='submit' value='Zmień' class='form' /></form></td></tr>
													<tr><td>$uprawa_g[0]</td><td>$uprawa_g[1]</td><td>$uprawa_g[2]</td><td>$uprawa_g[3]</td><td>$uprawa_g[4]</td></tr>
													<tr style='font-size: 11px; background-color:#b0d6df'><td>$dodatkowa_pra[0]</td><td>$dodatkowa_pra[1]</td><td>$dodatkowa_pra[2]</td><td>$dodatkowa_pra[3]</td><td>$dodatkowa_pra[4]</td></tr>";
												}


											}
											echo"</table>";
											echo"<TABLE CELLPADDING=5 BORDER=0>
											<tr>
											<td colspan='3' style='border:none;'>
											<form method='post'>
												 <input type='hidden' name='pokazac' value='tak'>
												<button onclick='pokaz_f_dodaj_d()' class='formpositive'>Dodaj działkę</button>
											</form><br/>
											<form action='plodozmian_pdf.php' method='post'>

												<button onclick='pokaz_f_dodaj_d()' class='formpositive'>Generuj plik PDF</button>
											</form>
											</td>
												<td style='border:none;'>
													<div>
														<canvas id='wyk0' style='width: 100%; height:100%; '></canvas>
													</div>
												</td>
												<td style='border:none;'>
													<div>
														<canvas id='wyk1' style='width: 100%; height:100%; '></canvas>
													</div>
												</td>
												<td style='border:none;'>
													<div>
														<canvas id='wyk2' style='width: 100%; height:100%; '></canvas>
													</div>
												</td>
												<td style='border:none;'>
													<div>
														<canvas id='wyk3' style='width: 100%; height:100%; '></canvas>
													</div>
												</td>
												<td style='border:none;'>
													<div>
														<canvas id='wyk4' style='width: 100%; height:100%; '></canvas>
													</div>
												</td>
												<td style='border:none; width:60px;'>
												</td>
											</tr></table>";
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

			if(isset($_POST['id0']))
			{
				$nazwa_w=$_POST['nazwa_w'];

				$i=0;
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
						$left=1.25;
						echo"<div id='f_edytuj_plodozmian'> <form action='zmienplodozmian.php' method='post'>";
						echo"Działka: $nazwa_w<br />";
						for ($i=0; $i <=5 ; $i++) {
							$id=$_POST["id$i"];

							$rezultat1 = $polaczenie->query("SELECT * FROM plodozmian WHERE user_id='$user_id' AND id='$id'");
							if (!$rezultat1) throw new Exception($polaczenie->error);
							else
							{

								while($wiersz = $rezultat1->fetch_assoc())
								{
									$id_dzialki=$wiersz['id_dzialki'];
									$id=$wiersz['id'];
									$rok=$wiersz['rok'];
									$przedplon=$wiersz['przedplon'];
									$uprawa_g=$wiersz['uprawa_g'];
									$dodatkowa_pra=$wiersz['dodatkowa_pra'];



									echo"<div style='float:left; position:absolute; left:$left%;width:18%;'> $rok<br />

										<input type='hidden' name='id$i' value='$id'>
										Przedplon: <br />  <input list='przedplon' name='przedplon$i' class='form' value='$przedplon'  style='width:150px;'/>
										<datalist id='przedplon'>";
											$rezultat2 = $polaczenie->query("SELECT * FROM uprawy WHERE rodzaj='przedplon'");
											while($wiersz = $rezultat2->fetch_assoc())
											{
												$przedplon=$wiersz['uprawa'];
												echo"<option value='$przedplon'>";
											}

									echo"</datalist><br />

										Uprawa główna: <br /> <input list='uprawa_glowna' name='uprawa_g$i' class='form' value='$uprawa_g'  style='width:150px;'/>
										<datalist id='uprawa_glowna'>";
											$rezultat2 = $polaczenie->query("SELECT * FROM uprawy WHERE rodzaj='Plon glowny'");
											while($wiersz = $rezultat2->fetch_assoc())
											{
												$uprawa_g=$wiersz['uprawa'];
												echo"<option value='$uprawa_g'>";
											}

									echo"</datalist><br />

										Dodatkowa praktyka: <br /> <input list='dodatkowa_praktyka' name='dodatkowa_pra$i' class='form' value='$dodatkowa_pra'  style='width:150px;'/>
										<datalist id='dodatkowa_praktyka'>";
											$rezultat2 = $polaczenie->query("SELECT * FROM uprawy WHERE rodzaj='Dodatkowa praktyka'");
											while($wiersz = $rezultat2->fetch_assoc())
											{
												$dodatkowa_pra=$wiersz['uprawa'];
												echo"<option value='$dodatkowa_pra'>";
											}

									echo"</datalist><br />
									</div>";

									$left+=20;
								}


							}
						}
						echo"<div style='clear:both; width:20%; position:absolute; left:40%; top:140px; padding:5px;'>
						<br /><input type='submit' value='Zmień' class='formpositive' />	<br /><br />
						</form>
						<button onclick='schowaj_f()' class='formnegative'>Anuluj</button> </div></div>";

					$polaczenie->close();
					}

				}
				catch(Exception $e)
				{
					echo '<span style="color:red;">Błąd serwera! Proszę spróbować później</span>';
				///////////////////////////////////////////////////////////////////////////////////////////echo '<br />Informacja developerska: '.$e;
				}



			}
		?>





	</div>

    </div> <!-- ZAKOŃCZENIE <div class="wrapper"> Z MENU -->
		<div class="footer">Myfarm.pl &copy; 2019 Wszelkie prawa zastrzeżone.</div>



<?php
	$user_id=$_SESSION['user_id'];
	$rok_rozpoczecia=$_SESSION['rok'];
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
			for ($y=0; $y <=4 ; $y++) {

				$rok_rozpoczecia=$_SESSION['rok'];
				$pow_calkowita=0;
				$label[0]='Zbożowe';
				$label[1]='Pastewne';
				$label[2]='Okopowe';
				$label[3]='Strączkowe';
				$label[4]='Motylkowe';
				$label[5]='Warzywa';
				$label[6]='Owocowe';


				$struktura[0]=0;
				$struktura[1]=0;
				$struktura[2]=0;
				$struktura[3]=0;
				$struktura[4]=0;
				$struktura[5]=0;
				$struktura[6]=0;
				$struktura[7]=0;
				$rok_rozpoczecia+=$y;
				$rezultat = $polaczenie->query("SELECT * FROM plodozmian WHERE rok='$rok_rozpoczecia' AND user_id='$user_id'");
				if (!$rezultat) throw new Exception($polaczenie->error);
				else
				{

					while($wiersz = $rezultat->fetch_assoc())
					{
						$uprawa_g=$wiersz['uprawa_g'];
						$id_dzialki=$wiersz['id_dzialki'];

						$rezultat1 = $polaczenie->query("SELECT grupa FROM uprawy WHERE uprawa='$uprawa_g' AND rodzaj='plon glowny'");
						if (!$rezultat1) throw new Exception($polaczenie->error);
						else
						{
							$wiersz1 = $rezultat1->fetch_assoc();
							$grupa=$wiersz1['grupa'];

						}

						$rezultat2 = $polaczenie->query("SELECT * FROM dzialki WHERE id='$id_dzialki' AND user_id='$user_id'");
						if (!$rezultat2) throw new Exception($polaczenie->error);
						else
						{
							$wiersz2= $rezultat2->fetch_assoc();
							$pow=$wiersz2['pow'];

						}
						$pow_calkowita+=$pow;




						if ($grupa=='Zbożowe') {
							$struktura[0]+=$pow;
						}
						if ($grupa=='Pastewne') {
							$struktura[1]+=$pow;
						}
						if ($grupa=='Okopowe') {
							$struktura[2]+=$pow;
						}
						if ($grupa=='Strączkowe') {
							$struktura[3]+=$pow;
						}
						if ($grupa=='Motylkowe') {
							$struktura[4]+=$pow;
						}
						if ($grupa=='Warzywa') {

							$struktura[5]+=$pow;
						}
						if ($grupa=='Owocowe') {
							echo "jest";
							$struktura[6]+=$pow;
						}

					}


				}
				if ($pow_calkowita>0) {
					foreach ($struktura as &$key) {
						$key=$key/$pow_calkowita*100;
						$key=round($key,2);
					}
				}
				$dane[$y]=$struktura;
			}
		}

		$polaczenie->close();


	}
	catch(Exception $e)
	{
		echo '<span style="color:red;">Błąd serwera! Proszę spróbować później</span>';
	///////////////////////////////////////////////////////////////////////////////////////////echo '<br />Informacja developerska: '.$e;
	}


?>
			<script>
			function schowaj_f()
			{
				$('#f_edytuj_plodozmian').fadeOut();
				$('#f_dodaj_d').fadeOut();

			}
			function pokaz_f_dodaj_d()
			{
				$('#f_dodaj_d').fadeIn();

			}
			</script>
			<script>
		// WYKRES

			for (var i = 0; i <=4; i++) {


			var label = <?php echo json_encode($label); ?>;
			var temp = <?php echo json_encode($dane); ?>;

					var ctx = document.getElementById('wyk'+i);
					var myChart = new Chart(ctx, {
							type: 'doughnut',
							data: {
									labels: label,
									datasets: [{
											label: 'Struktura',
											data: temp[i],
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
									display: false,
									position: 'bottom',
								},
								title:{
									display: true,
									text: 'Struktura upraw %'
								},
								animation:{
									animateScale: true,
									animateRotate: true
								}
							}
					});
					}
			</script>


  </body>
</html>
