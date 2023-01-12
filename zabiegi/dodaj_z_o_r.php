<?php
session_start();
if (!isset($_POST['data']))
	{
		header('Location:zabiegi_o_r.php');
		exit();
	}else
	{
		$user_id=$_SESSION['user_id'];
    $nazwa_w=$_POST['nazwa_w'];
    $uprawa=$_POST['uprawa'];
    $pow_z=$_POST['pow_z'];
    $data=$_POST['data'];
    $nazwa_s=$_POST['nazwa_s'];
		$dawka_s=$_POST['dawka_s'];
		$uwagi=$_POST['uwagi'];

		$user_id=htmlentities($user_id, ENT_QUOTES, "UTF-8");
    $nazwa_w=htmlentities($nazwa_w, ENT_QUOTES, "UTF-8");
    $uprawa=htmlentities($uprawa, ENT_QUOTES, "UTF-8");
    $pow_z=htmlentities($pow_z, ENT_QUOTES, "UTF-8");
		$data=htmlentities($data, ENT_QUOTES, "UTF-8");
		$nazwa_s=htmlentities($nazwa_s, ENT_QUOTES, "UTF-8");
		$dawka_s=htmlentities($dawka_s, ENT_QUOTES, "UTF-8");
		$uwagi=htmlentities($uwagi, ENT_QUOTES, "UTF-8");
		

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
				$rezultat=$polaczenie->query("SELECT id FROM dzialki WHERE user_id='$user_id' AND nazwa_w='$nazwa_w'");
				$wiersz = $rezultat->fetch_assoc();
	      $id_dzialki=$wiersz['id'];
				$polaczenie->query(
				sprintf("INSERT INTO zabiegi_o_r VALUES (NULL, '%s', '%s','%s', '%s','%s','%s','%s','%s','%s')",
        mysqli_real_escape_string($polaczenie,$nazwa_w),
        mysqli_real_escape_string($polaczenie,$uprawa),
				mysqli_real_escape_string($polaczenie,$pow_z),
				mysqli_real_escape_string($polaczenie,$data),
				mysqli_real_escape_string($polaczenie,$nazwa_s),
        mysqli_real_escape_string($polaczenie,$dawka_s),
        mysqli_real_escape_string($polaczenie,$uwagi),
				mysqli_real_escape_string($polaczenie,$user_id),
			mysqli_real_escape_string($polaczenie,$id_dzialki)));
				$polaczenie->close();
				header('Location:zabiegi_o_r.php');

			}
		}
		catch(Exception $e)
		{
		echo '<span style="color:red;">Błąd serwera! Proszę spróbować później</span>';
		///////////////////////////////////////////////////////////////////////////////////////////echo '<br />Informacja developerska: '.$e;
		}
	}

?>
