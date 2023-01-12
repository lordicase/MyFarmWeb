<?php
session_start();

    $user_id=$_POST['user_id'];
    $id=$_POST['id'];
    $nazwa_w_d=$_POST['nazwa_w'];
    $uprawa=$_POST['uprawa'];
    $pow_z=$_POST['pow_z'];
    $data=$_POST['data'];
    $nazwa_s=$_POST['nazwa_s'];
    $dawka_s=$_POST['dawka_s'];
    $uwagi=$_POST['uwagi'];


    $user_id=htmlentities($user_id, ENT_QUOTES, "UTF-8");
    $id=htmlentities($id, ENT_QUOTES, "UTF-8");
    $nazwa_w_d=htmlentities($nazwa_w_d, ENT_QUOTES, "UTF-8");
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

				$polaczenie->query(
				sprintf("UPDATE zabiegi_o_r SET nazwa_w_d='%s', uprawa='%s', pow_z='%s', data='%s', nazwa='%s', dawka='%s', uwagi='%s' WHERE id='%s' AND user_id='%s'",
        mysqli_real_escape_string($polaczenie,$nazwa_w_d),
        mysqli_real_escape_string($polaczenie,$uprawa),
        mysqli_real_escape_string($polaczenie,$pow_z),
        mysqli_real_escape_string($polaczenie,$data),
				mysqli_real_escape_string($polaczenie,$nazwa_s),
				mysqli_real_escape_string($polaczenie,$dawka_s),
				mysqli_real_escape_string($polaczenie,$uwagi),
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
