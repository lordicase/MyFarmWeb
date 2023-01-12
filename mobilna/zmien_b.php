<?php
session_start();

    $user_id=$_POST['user_id'];
    $id=$_POST['id'];
    $nr_zwierzecia=$_POST['nr_zwierzecia'];
    $data_urodzenia=$_POST['data_urodzenia'];
    $plec=$_POST['plec'];
    $kod_rasy=$_POST['kod_rasy'];
    $data_oznakowania=$_POST['data_oznakowania'];
    $nr_matki=$_POST['nr_matki'];
    $nr_ojca=$_POST['nr_ojca'];
    $data_przybycia=$_POST['data_przybycia'];
    $kod_zdarzenia_p=$_POST['kod_zdarzenia_p'];
    $dane_przybycia=$_POST['dane_przybycia'];
    $data_ubycia=$_POST['data_ubycia'];
    $kod_zdarzenia_u=$_POST['kod_zdarzenia_u'];
    $dane_ubycia=$_POST['dane_ubycia'];
    $dane_przewoznika=$_POST['dane_przewoznika'];
    $uwagi=$_POST['uwagi'];

    $id=htmlentities($id, ENT_QUOTES, "UTF-8");
    $user_id=htmlentities($user_id, ENT_QUOTES, "UTF-8");
    $nr_zwierzecia=htmlentities($nr_zwierzecia, ENT_QUOTES, "UTF-8");
    $data_urodzenia=htmlentities($data_urodzenia, ENT_QUOTES, "UTF-8");
    $plec=htmlentities($plec, ENT_QUOTES, "UTF-8");
    $kod_rasy=htmlentities($kod_rasy, ENT_QUOTES, "UTF-8");
    $data_oznakowania=htmlentities($data_oznakowania, ENT_QUOTES, "UTF-8");
    $nr_matki=htmlentities($nr_matki, ENT_QUOTES, "UTF-8");
    $nr_ojca=htmlentities($nr_ojca, ENT_QUOTES, "UTF-8");
    $data_przybycia=htmlentities($data_przybycia, ENT_QUOTES, "UTF-8");
    $kod_zdarzenia_p=htmlentities($kod_zdarzenia_p, ENT_QUOTES, "UTF-8");
    $dane_przybycia=htmlentities($dane_przybycia, ENT_QUOTES, "UTF-8");
    $data_ubycia=htmlentities($data_ubycia, ENT_QUOTES, "UTF-8");
    $kod_zdarzenia_u=htmlentities($kod_zdarzenia_u, ENT_QUOTES, "UTF-8");
    $dane_ubycia=htmlentities($dane_ubycia, ENT_QUOTES, "UTF-8");
    $dane_przewoznika=htmlentities($dane_przewoznika, ENT_QUOTES, "UTF-8");
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
        sprintf("UPDATE dziennik SET nr_zwierzecia='%s', data_urodzenia='%s', plec='%s', kod_rasy='%s', data_oznakowania='%s', nr_matki='%s', nr_ojca='%s', data_przybycia='%s', kod_zdarzenia_p='%s', dane_przybycia='%s', data_ubycia='%s', kod_zdarzenia_u='%s', dane_ubycia='%s', dane_przewoznika='%s', uwagi='%s' WHERE id='%s' AND user_id='%s'",
        mysqli_real_escape_string($polaczenie,$nr_zwierzecia),
        mysqli_real_escape_string($polaczenie,$data_urodzenia),
        mysqli_real_escape_string($polaczenie,$plec),
        mysqli_real_escape_string($polaczenie,$kod_rasy),
        mysqli_real_escape_string($polaczenie,$data_oznakowania),
        mysqli_real_escape_string($polaczenie,$nr_matki),
        mysqli_real_escape_string($polaczenie,$nr_ojca),
        mysqli_real_escape_string($polaczenie,$data_przybycia),
        mysqli_real_escape_string($polaczenie,$kod_zdarzenia_p),
        mysqli_real_escape_string($polaczenie,$dane_przybycia),
        mysqli_real_escape_string($polaczenie,$data_ubycia),
        mysqli_real_escape_string($polaczenie,$kod_zdarzenia_u),
        mysqli_real_escape_string($polaczenie,$dane_ubycia),
        mysqli_real_escape_string($polaczenie,$dane_przewoznika),
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
