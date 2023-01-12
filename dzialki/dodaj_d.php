<?php
session_start();
if (!isset($_POST['nr_e_d']))
	{
		header('Location:dzialki.php');
		exit();
	}else
	{
		$user_id=$_SESSION['user_id'];
		$nr_e_d=$_POST['nr_e_d'];
		$nazwa_w=$_POST['nazwa_w'];
		$pow=$_POST['pow'];
		$uprawa=$_POST['uprawa'];
		$adres=$_POST['adres'];

		$user_id=htmlentities($user_id, ENT_QUOTES, "UTF-8");
		$nr_e_d=htmlentities($nr_e_d, ENT_QUOTES, "UTF-8");
		$nazwa_w=htmlentities($nazwa_w, ENT_QUOTES, "UTF-8");
		$pow=htmlentities($pow, ENT_QUOTES, "UTF-8");
		$uprawa=htmlentities($uprawa, ENT_QUOTES, "UTF-8");
		$adres=htmlentities($adres, ENT_QUOTES, "UTF-8");

		require_once "../connect.php";

		mysqli_report(MYSQLI_REPORT_STRICT);//////////////////////////////////////////////////////////ZMIANA RAPORTOWANIA BŁĘDÓW
		try
		{
			$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
			if ($polaczenie->connect_errno!=0)
			{
				throw new Exception(mysqli_connect_errno());
			}else
			{
				$rezultat = $polaczenie->query(
				sprintf("SELECT * FROM dzialki WHERE nazwa_w='%s' AND user_id='%s'",
				mysqli_real_escape_string($polaczenie,$nazwa_w),
				mysqli_real_escape_string($polaczenie,$user_id)));
				$ile_nazw = $rezultat->num_rows;
				if($ile_nazw>0)
				{
					$_SESSION['e_nazwa_d']="Istnieje już dzialka o takiej nazwie! Wybierz inną.";
					header('Location:dzialki.php');
				}else {
					$polaczenie->query(
					sprintf("INSERT INTO dzialki VALUES (NULL, '%s', '%s','%s', '%s','%s','%s')",
					mysqli_real_escape_string($polaczenie,$nr_e_d),
					mysqli_real_escape_string($polaczenie,$nazwa_w),
					mysqli_real_escape_string($polaczenie,$pow),
					mysqli_real_escape_string($polaczenie,$adres),
					mysqli_real_escape_string($polaczenie,$uprawa),
					mysqli_real_escape_string($polaczenie,$user_id)));
					$polaczenie->close();
					header('Location:dzialki.php');
				}

			}
		}
		catch(Exception $e)
		{
		echo '<span style="color:red;">Błąd serwera! Proszę spróbować później</span>';
		///////////////////////////////////////////////////////////////////////////////////////////echo '<br />Informacja developerska: '.$e;
		}
	}

?>
