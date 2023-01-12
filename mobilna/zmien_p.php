<?php
session_start();

	
    $user_id=$_POST['user_id'];
		$id=$_POST['id'];
    $imie=$_POST['imie'];
    $nazwisko=$_POST['nazwisko'];
  	$telefon=$_POST['telefon'];
  	$placa=$_POST['placa'];

		$user_id=htmlentities($user_id, ENT_QUOTES, "UTF-8");
		$id=htmlentities($id, ENT_QUOTES, "UTF-8");
		$imie=htmlentities($imie, ENT_QUOTES, "UTF-8");
		$nazwisko=htmlentities($nazwisko, ENT_QUOTES, "UTF-8");
		$telefon=htmlentities($telefon, ENT_QUOTES, "UTF-8");
		$placa=htmlentities($placa, ENT_QUOTES, "UTF-8");


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
				sprintf("UPDATE pracownicy SET imie='%s', nazwisko='%s', telefon='%s', placa='%s' WHERE id='%s' AND user_id='%s'",
				mysqli_real_escape_string($polaczenie,$imie),
				mysqli_real_escape_string($polaczenie,$nazwisko),
				mysqli_real_escape_string($polaczenie,$telefon),
				mysqli_real_escape_string($polaczenie,$placa),
				mysqli_real_escape_string($polaczenie,$id),
				mysqli_real_escape_string($polaczenie,$user_id)));
				$polaczenie->close();
				echo 'A';

			}
		}
		catch(Exception $e)
		{
		echo 'B';
		///////////////////////////////////////////////////////////////////////////////////////////echo '<br />Informacja developerska: '.$e;
		}

?>
