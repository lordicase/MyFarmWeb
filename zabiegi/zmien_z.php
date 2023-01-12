<?php
session_start();
if (!isset($_POST['id']))
	{
		header('Location:zabiegi.php');
		exit();
	}else
	{
    $user_id=$_SESSION['user_id'];
		$id=$_POST['id'];
    $data=$_POST['data'];
    $typ_z=$_POST['typ_z'];
  	$opis=$_POST['opis'];
  	$nazwa_dzialki=$_POST['nazwa_w'];

		$user_id=htmlentities($user_id, ENT_QUOTES, "UTF-8");
		$id=htmlentities($id, ENT_QUOTES, "UTF-8");
		$data=htmlentities($data, ENT_QUOTES, "UTF-8");
		$typ_z=htmlentities($typ_z, ENT_QUOTES, "UTF-8");
		$opis=htmlentities($opis, ENT_QUOTES, "UTF-8");
		$nazwa_dzialki=htmlentities($nazwa_dzialki, ENT_QUOTES, "UTF-8");


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
				sprintf("UPDATE zabiegi SET data='%s', typ_z='%s', opis='%s', nazwa_w_d='%s' WHERE id='%s' AND user_id='%s'",
				mysqli_real_escape_string($polaczenie,$data),
				mysqli_real_escape_string($polaczenie,$typ_z),
				mysqli_real_escape_string($polaczenie,$opis),
				mysqli_real_escape_string($polaczenie,$nazwa_dzialki),
				mysqli_real_escape_string($polaczenie,$id),
				mysqli_real_escape_string($polaczenie,$user_id)));
				header('Location:zabiegi.php');

			}
		}
		catch(Exception $e)
		{
		echo '<span style="color:red;">Błąd serwera! Proszę spróbować później</span>';
		///////////////////////////////////////////////////////////////////////////////////////////echo '<br />Informacja developerska: '.$e;
		}
	}

?>
