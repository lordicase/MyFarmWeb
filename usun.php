<?php
session_start();
if (!isset($_POST['id']))
	{
		header('Location:glowna.php');
		exit();
	}else
	{

		$id=$_POST['id'];
		$typ_tabeli=$_POST['typ_tabeli'];
		$user_id=$_SESSION['user_id'];
		$id=htmlentities($id, ENT_QUOTES, "UTF-8");
		$typ_tabeli=htmlentities($typ_tabeli, ENT_QUOTES, "UTF-8");
		$user_id=htmlentities($user_id, ENT_QUOTES, "UTF-8");



		require_once "connect.php";

		mysqli_report(MYSQLI_REPORT_STRICT);//////////////////////////////////////////////////////////ZMIANA RAPORTOWANIA BŁĘDÓW
		try
		{
			$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
			if ($polaczenie->connect_errno!=0)
			{
				throw new Exception(mysqli_connect_errno());
			}else
			{
				if($typ_tabeli=="dzialki")
				{
					$polaczenie->query(
					sprintf("DELETE FROM dzialki WHERE id = '%s' AND user_id='%s' ",
					mysqli_real_escape_string($polaczenie,$id),
					mysqli_real_escape_string($polaczenie,$user_id)));
					$polaczenie->close();
				header('Location:dzialki/dzialki.php');
				}

				if($typ_tabeli=="zabiegi")
				{
					$polaczenie->query(
					sprintf("DELETE FROM zabiegi WHERE id = '%s' AND user_id='%s' ",
					mysqli_real_escape_string($polaczenie,$id),
					mysqli_real_escape_string($polaczenie,$user_id)));
					$polaczenie->close();
				header('Location:zabiegi/zabiegi.php');
				}

				if($typ_tabeli=="zabiegi_o_r")
				{
					$polaczenie->query(
					sprintf("DELETE FROM zabiegi_o_r WHERE id = '%s' AND user_id='%s' ",
					mysqli_real_escape_string($polaczenie,$id),
					mysqli_real_escape_string($polaczenie,$user_id)));
					$polaczenie->close();
				header('Location:zabiegi/zabiegi_o_r.php');
				}

				if($typ_tabeli=="dziennik")
				{
					$polaczenie->query(
					sprintf("DELETE FROM dziennik WHERE id = '%s' AND user_id='%s' ",
					mysqli_real_escape_string($polaczenie,$id),
					mysqli_real_escape_string($polaczenie,$user_id)));
					$polaczenie->close();
				header('Location:zwierzeta/ksiega_rejestracji.php');
				}

				if($typ_tabeli=="pracownicy")
				{
					$polaczenie->query(
					sprintf("DELETE FROM pracownicy WHERE id = '%s' AND user_id='%s' ",
					mysqli_real_escape_string($polaczenie,$id),
					mysqli_real_escape_string($polaczenie,$user_id)));
					$polaczenie->close();
				header('Location:pracownicy/pracownicy.php');
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
