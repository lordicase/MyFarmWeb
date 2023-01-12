<?php
  session_start();
  $user_id=$_SESSION['user_id'];
  // pobieranie danych z bazy
  function parseToXML($htmlStr)
  {
    $xmlStr=str_replace('<','&lt;',$htmlStr);
    $xmlStr=str_replace('>','&gt;',$xmlStr);
    $xmlStr=str_replace('"','&quot;',$xmlStr);
    $xmlStr=str_replace("'",'&#39;',$xmlStr);
    $xmlStr=str_replace("&",'&amp;',$xmlStr);
    return $xmlStr;
  }
  require_once "../connect.php";
  // Opens a connection to a MySQL server
  $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
  if ($polaczenie->connect_errno!=0)
  {
    throw new Exception(mysqli_connect_errno());
  }
  else
  {
    // POBIERZ NAZWY KOLEJNYCH DZIALEK
    $query = "SELECT DISTINCT id_dzialki FROM mapy_dzialek WHERE user_id='$user_id'";
    $result0 = $polaczenie->query($query);
    if (!$result0)
    {
      die('Invalid query: ' . mysqli_error());
    }else {
      header("Content-type: text/xml");

      // Start XML file, echo parent node
      echo "<?xml version='1.0' ?>";
      echo '<markers>';

      //DOPUKI BEDZIE MIAŁ JAKIEŚ DZIAŁKI
      while ($wiersz0 = @mysqli_fetch_assoc($result0))
      {
        //POBIERAJ KOLEJNE WIERSZE
        $id_dzialki=$wiersz0['id_dzialki'];

        $result = $polaczenie->query("SELECT id, lat, lng, user_id, id_dzialki FROM mapy_dzialek WHERE user_id='$user_id' AND id_dzialki='$id_dzialki'");
        if (!$result)
        {
          die('Invalid query: ' . mysqli_error());
        }
        $ind=0;

        echo '<marker ';

        // DOPUKI BĘDZIESZ MIAŁ WIERSZE OD JEDNEJ DZIAŁKI BOBIERAJ ICH NAMIARY
        while ($wiersz = @mysqli_fetch_assoc($result)){
          echo 'lat'.$ind.'="' . $wiersz['lat'] . '" ';
          echo 'lng'.$ind.'="' . $wiersz['lng'] . '" ';
          $user_id=$wiersz['user_id'];
          $id_dzialki=$wiersz['id_dzialki'];
          $ind = $ind + 1;
        }
        //pobieranie nazwy i uprawy dzialki
        $result = $polaczenie->query("SELECT nazwa_w, uprawa FROM dzialki WHERE user_id='$user_id' AND id='$id_dzialki'");
        $wiersz = @mysqli_fetch_assoc($result);
        $nazwa_w=$wiersz['nazwa_w'];
        $uprawa=$wiersz['uprawa'];

        echo 'user_id="' . $user_id . '" ';
        echo 'id_dzialki="' . $id_dzialki . '" ';
        echo 'ile_znacznikow="' . $ind . '" ';
        echo 'nazwa_w="' . $nazwa_w . '" ';
        echo 'uprawa="' . $uprawa . '" ';
        echo '/>';
      }
    }


    // End XML file
    echo '</markers>';
    $polaczenie->close;
  }
?>
