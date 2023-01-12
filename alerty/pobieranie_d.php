<?php
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
    // Select all the rows in the markers table
    $czas1 = time()-2678400;

    $query = "SELECT id, name, address, lat, lng, type FROM markers WHERE data>'$czas1'";
    $result = $polaczenie->query($query);
    if (!$result)
    {
      die('Invalid query: ' . mysqli_error());
    }

    header("Content-type: text/xml");

  // Start XML file, echo parent node
    echo "<?xml version='1.0' ?>";
    echo '<markers>';
    $ind=0;
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

    // End XML file
    echo '</markers>';
    $polaczenie->close;
  }
?>
