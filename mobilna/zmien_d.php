<?php
session_start();

    $user_id=$_POST['user_id'];
		$id=$_POST['id'];
    $nr_e_d=$_POST['nr_e_d'];
  	$nazwa_w=$_POST['nazwa_w'];
  	$pow=$_POST['pow'];
  	$uprawa=$_POST['uprawa'];
  	$adres=$_POST['adres'];

		$user_id=htmlentities($user_id, ENT_QUOTES, "UTF-8");
		$id=htmlentities($id, ENT_QUOTES, "UTF-8");
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

				$polaczenie->query(
				sprintf("UPDATE dzialki SET nr_e_d='%s', nazwa_w='%s', pow='%s', adres='%s', uprawa = '%s' WHERE id='%s' AND user_id='%s'",
				mysqli_real_escape_string($polaczenie,$nr_e_d),
				mysqli_real_escape_string($polaczenie,$nazwa_w),
				mysqli_real_escape_string($polaczenie,$pow),
				mysqli_real_escape_string($polaczenie,$adres),
				mysqli_real_escape_string($polaczenie,$uprawa),
				mysqli_real_escape_string($polaczenie,$id),
				mysqli_real_escape_string($polaczenie,$user_id)));

				$polaczenie->query(
				sprintf("UPDATE zabiegi SET nazwa_w_d='%s' WHERE id_dzialki='%s' AND user_id='%s'",
				mysqli_real_escape_string($polaczenie,$nazwa_w),
				mysqli_real_escape_string($polaczenie,$id),
				mysqli_real_escape_string($polaczenie,$user_id)));

				$polaczenie->query(
				sprintf("UPDATE zabiegi_o_r SET nazwa_w_d='%s' WHERE id_dzialki='%s' AND user_id='%s'",
				mysqli_real_escape_string($polaczenie,$nazwa_w),
				mysqli_real_escape_string($polaczenie,$id),
				mysqli_real_escape_string($polaczenie,$user_id)));

				echo"A";
				$polaczenie->close;
			}
		}
		catch(Exception $e)
		{
		echo"B";
		///////////////////////////////////////////////////////////////////////////////////////////echo '<br />Informacja developerska: '.$e;
		}


?>
