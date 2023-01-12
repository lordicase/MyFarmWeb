<?php

  require_once "simplehtmldom_1_9_1/simple_html_dom.php";
  require_once "../connect.php";
  // pobieranie danych z bazy
  $user_id=$_GET['id'];
  $user_id=htmlentities($user_id, ENT_QUOTES, "UTF-8");
  function parseToXML($htmlStr)
  {
    $xmlStr=str_replace('<','&lt;',$htmlStr);
    $xmlStr=str_replace('>','&gt;',$xmlStr);
    $xmlStr=str_replace('"','&quot;',$xmlStr);
    $xmlStr=str_replace("'",'&#39;',$xmlStr);
    $xmlStr=str_replace("&",'&amp;',$xmlStr);
    return $xmlStr;
  }




  // Opens a connection to a MySQL server
  $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
  if ($polaczenie->connect_errno!=0)
  {
    throw new Exception(mysqli_connect_errno());
  }
  else
  {



    //INFORMACJE O ZABIEGACH

    $result = $polaczenie->query(sprintf("SELECT * FROM zabiegi WHERE user_id='%s' ORDER BY data ASC",
    mysqli_real_escape_string($polaczenie,$user_id)));
    if (!$result)
    {
      die('Invalid query: ' . mysqli_error());
    }

    header("Content-type: text/xml");

  // Start XML file, echo parent node
    echo "<?xml version='1.0' ?>";
    echo '<dane>';
    $ind=0;
    // POBIERANIE ZABIEGOW
    echo '<zabiegi>';
    while ($row = @mysqli_fetch_assoc($result)){
      // Add to XML document node
      echo '<zabieg ';
      echo 'id="' . $row['id'] . '" ';
      echo 'data="' . parseToXML($row['data']) . '" ';
      echo 'typ_z="' . parseToXML($row['typ_z']) . '" ';
      echo 'opis="' . parseToXML($row['opis']) . '" ';
      echo 'nazwa_w_d="' . parseToXML($row['nazwa_w_d']) . '" ';
      echo '/>';
      $ind = $ind + 1;
    }
  echo '</zabiegi>';






    // POBIERANIE ALERTOW
    $czas1 = time()-2678400;

    $czas1=htmlentities($czas1, ENT_QUOTES, "UTF-8");
    $result = $polaczenie->query(sprintf("SELECT id, name, address, lat, lng, type FROM markers WHERE data>'%s'",
    mysqli_real_escape_string($polaczenie,$czas1)));
    if (!$result)
    {
      die('Invalid query: ' . mysqli_error());
    }


    $ind=0;

    echo '<alerty>';
    // Iterate through the rows, printing XML nodes for each
    while ($row = @mysqli_fetch_assoc($result)){
      // Add to XML document node
      echo '<marker ';
      echo 'id="' . $row['id'] . '" ';
      echo 'name="' . parseToXML($row['name']) . '" ';
      echo 'address="' . parseToXML($row['address']) . '" ';
      echo 'lat="' . $row['lat'] . '" ';
      echo 'lng="' . $row['lng'] . '" ';
      echo 'type="' . $row['type'] . '" ';
      echo '/>';
      $ind = $ind + 1;
    }

    echo '</alerty>';




    echo '<mapy_dzialek>';
    // POBIERZ NAZWY KOLEJNYCH DZIALEK

    $result0 = $polaczenie->query(sprintf("SELECT DISTINCT id_dzialki FROM mapy_dzialek WHERE user_id='%s'",
    mysqli_real_escape_string($polaczenie,$user_id)));
    if (!$result0)
    {
      die('Invalid query: ' . mysqli_error());
    }else {


      //DOPUKI BEDZIE MIAŁ JAKIEŚ DZIAŁKI
      while ($wiersz0 = @mysqli_fetch_assoc($result0))
      {
        //POBIERAJ KOLEJNE WIERSZE
        $id_dzialki=$wiersz0['id_dzialki'];
        $id_dzialki=htmlentities($id_dzialki, ENT_QUOTES, "UTF-8");
        $result = $polaczenie->query(sprintf("SELECT id, lat, lng, user_id, id_dzialki FROM mapy_dzialek WHERE user_id='%s' AND id_dzialki='%s'",
        mysqli_real_escape_string($polaczenie,$user_id),
        mysqli_real_escape_string($polaczenie,$id_dzialki)));
        if (!$result)
        {
          die('Invalid query: ' . mysqli_error());
        }
        $ind=0;

        echo '<markerd ';

        // DOPUKI BĘDZIESZ MIAŁ WIERSZE OD JEDNEJ DZIAŁKI BOBIERAJ ICH NAMIARY
        while ($wiersz = @mysqli_fetch_assoc($result)){
          echo 'lat'.$ind.'="' . $wiersz['lat'] . '" ';
          echo 'lng'.$ind.'="' . $wiersz['lng'] . '" ';
          $user_id=$wiersz['user_id'];
          $id_dzialki=$wiersz['id_dzialki'];
          $ind = $ind + 1;
        }
        //pobieranie nazwy i uprawy dzialki
        $result = $polaczenie->query(sprintf("SELECT nazwa_w, uprawa FROM dzialki WHERE user_id='%s' AND id='%s'",
        mysqli_real_escape_string($polaczenie,$user_id),
        mysqli_real_escape_string($polaczenie,$id_dzialki)));
        @mysqli_fetch_assoc($result);
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
    echo '</mapy_dzialek>';





    echo '<ceny>';
    //POBIERANIE DANYCH CEN
      $html = file_get_html("https://www.farmer.pl/agroskop/zboza/");
      for ($i=1; $i <16 ; $i++) {
        echo '<info ' ;
        echo'nazwa="'.$html->find("tr th",$i)->innertext. '" ';
        echo'cena="'.$html->find(".srednia td",$i)->innertext. '" ';
        echo '/>';
      }
    echo '</ceny>';


    echo '<uprawy>';
//DANE O UPRAWACH
$rezultat = $polaczenie->query(sprintf("SELECT DISTINCT uprawa FROM dzialki WHERE user_id='%s'",
mysqli_real_escape_string($polaczenie,$user_id)));

if (!$rezultat) throw new Exception($polaczenie->error);
else
{
      $i=0;
      while($wiersz = $rezultat->fetch_assoc())
      {
        $pow_u=0;
        $uprawa=$wiersz['uprawa'];
        $uprawa=htmlentities($uprawa, ENT_QUOTES, "UTF-8");
        $rezultat1 = $polaczenie->query(sprintf("SELECT * FROM dzialki WHERE user_id='%s' AND uprawa='%s'",
        mysqli_real_escape_string($polaczenie,$user_id),
        mysqli_real_escape_string($polaczenie,$uprawa)));
        while($wiersz = $rezultat1->fetch_assoc())
          {

            $pow_u+=$wiersz['pow'];

          }
          $label[$i]=$uprawa;
          $dane[$i]=$pow_u;
          $pow_calkowita[0]+=$pow_u;
          $i++;
      }
    $x=0;
      foreach ($label as $key) {
        echo '<uprawa ' ;
        echo'nazwa="'.$key. '" ';
        echo'pow="'.$dane[$x]. '" ';
        echo '/>';
        $x++;
      }



    if ($pow_u==0) {
      $label[$i]='brak upraw';
      $dane[$i]=100;
    }
  }
    echo '</uprawy>';




    $result = $polaczenie->query(sprintf("SELECT * FROM dzialki WHERE user_id=%s",
    mysqli_real_escape_string($polaczenie,$user_id)));
    if (!$result)
    {
      die('Invalid query: ' . mysqli_error());
    }

    echo '<dzialki>';
    // Iterate through the rows, printing XML nodes for each
    while ($row = @mysqli_fetch_assoc($result)){
      // Add to XML document node
      echo '<dzialka ';
      echo 'id="' . $row['id'] . '" ';
      echo 'nr_e_d="' . parseToXML($row['nr_e_d']) . '" ';
      echo 'nazwa_w="' . parseToXML($row['nazwa_w']) . '" ';
      echo 'pow="' . $row['pow'] . '" ';
      echo 'adres="' . $row['adres'] . '" ';
      echo 'uprawa="' . $row['uprawa'] . '" ';
      echo '/>';

    }

    echo '</dzialki>';






  //INFORMACJE O ZABIEGACH OCHRONY ROŚLIN
  $result = $polaczenie->query(sprintf("SELECT * FROM zabiegi_o_r WHERE user_id='%s' ORDER BY data ASC",
  mysqli_real_escape_string($polaczenie,$user_id)));
  echo '<zabiegi_o_r>';
  // Iterate through the rows, printing XML nodes for each
  while ($row = @mysqli_fetch_assoc($result)){
    // Add to XML document node
    echo '<zabieg_o ';
    echo 'id="' .  parseToXML($row['id']) . '" ';
    echo 'nazwa_w_d="' . parseToXML($row['nazwa_w_d']) . '" ';
    echo 'uprawa="' . parseToXML($row['uprawa']) . '" ';
    echo 'pow_z="' .  parseToXML($row['pow_z']) . '" ';
    echo 'data="' .  parseToXML($row['data']) . '" ';
    echo 'nazwa_s="' .  parseToXML($row['nazwa']) . '" ';
    echo 'dawka="' .  parseToXML($row['dawka']) . '" ';
    echo 'uwagi="' .  parseToXML($row['uwagi']) . '" ';

    echo '/>';

  }

  echo '</zabiegi_o_r>';


  //INFORMACJE O PRACOWNIKACH
  $result = $polaczenie->query(sprintf("SELECT * FROM pracownicy WHERE user_id='%s'",
  mysqli_real_escape_string($polaczenie,$user_id)));
  echo '<pracownicy>';
  // Iterate through the rows, printing XML nodes for each
  while ($row = @mysqli_fetch_assoc($result)){
    // Add to XML document node
    echo '<pracownik ';
    echo 'id="' .  parseToXML($row['id']) . '" ';
    echo 'imie="' . parseToXML($row['imie']) . '" ';
    echo 'nazwisko="' . parseToXML($row['nazwisko']) . '" ';
    echo 'telefon="' . parseToXML($row['telefon']) . '" ';
    echo 'placa="' . parseToXML($row['placa']) . '" ';
    echo '/>';

  }

  echo '</pracownicy>';

  $rezultat = $polaczenie->query(sprintf("SELECT * FROM dziennik WHERE user_id='%s' ORDER BY data_oznakowania ASC",
  mysqli_real_escape_string($polaczenie,$user_id)));
  echo '<sztuki>';

  while($wiersz = $rezultat->fetch_assoc())
  {
    echo'<sztuka ';
    echo 'id="' . parseToXML($wiersz['id']) . '" ';
    echo 'nr_zwierzecia="' . parseToXML($wiersz['nr_zwierzecia']) . '" ';
    echo 'data_urodzenia="' . parseToXML($wiersz['data_urodzenia']) . '" ';
    echo 'plec="' . parseToXML($wiersz['plec']) . '" ';
    echo 'kod_rasy="' . parseToXML($wiersz['kod_rasy']) . '" ';
    echo 'data_oznakowania="' . parseToXML($wiersz['data_oznakowania']) . '" ';
    echo 'nr_matki="' . parseToXML($wiersz['nr_matki']) . '" ';
    echo 'nr_ojca="' . parseToXML($wiersz['nr_ojca']) . '" ';
    echo 'data_przybycia="' . parseToXML($wiersz['data_przybycia']) . '" ';
    echo 'kod_zdarzenia_p="' . parseToXML($wiersz['kod_zdarzenia_p']) . '" ';
    echo 'dane_przybycia="' . parseToXML($wiersz['dane_przybycia']) . '" ';
    echo 'data_ubycia="' . parseToXML($wiersz['data_ubycia']) . '" ';
    echo 'kod_zdarzenia_u="' . parseToXML($wiersz['kod_zdarzenia_u']) . '" ';
    echo 'dane_ubycia="' . parseToXML($wiersz['dane_ubycia']) . '" ';
    echo 'dane_przewoznika="' . parseToXML($wiersz['dane_przewoznika']) . '" ';
    echo 'uwagi="' . parseToXML($wiersz['uwagi']) . '" ';
    echo '/>';

}
  echo '</sztuki>';

  //INFORMACJE O GOSPODARSTWIE
  $result = $polaczenie->query(sprintf("SELECT * FROM uzytkownicy WHERE id='%s'",
  mysqli_real_escape_string($polaczenie,$user_id)));
  $wiersz = @mysqli_fetch_assoc($result);
  $email=$wiersz['email'];
  $lat=$wiersz['lat'];
  $lat=$wiersz['lng'];
  echo '<gospodarstwo ' ;
  echo'user_id="'.$user_id. '" ';
  echo'email="'.$email. '" ';
  echo'lat="'.$lat. '" ';
  echo'lng="'.$lat. '" ';
  echo '/>';




    // End XML file
    echo '</dane>';
    $polaczenie->close;
  }
?>
