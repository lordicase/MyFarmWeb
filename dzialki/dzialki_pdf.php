<?php
session_start();
require_once('../TCPDF/examples/tcpdf_include.php');
require_once('../TCPDF/tcpdf.php');

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetMargins(PDF_MARGIN_LEFT+10, 8, PDF_MARGIN_RIGHT+10); //marginesy. drugi - góra
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
if (@file_exists(dirname(__FILE__).'/lang/pol.php')) {
        require_once(dirname(__FILE__).'/lang/pol.php');
        $pdf->setLanguageArray($l);
}
$pdf->setPrintHeader(false); // usunięcie stopki i nagłówka strony header/footer
$pdf->setPrintFooter(false);
$pdf->SetFont('dejavusans', '', 8); //polskie znaki - dejavusans lub freesans
$pdf->AddPage();


  $user_id=$_SESSION['user_id'];
  $user_name=$_SESSION['user'];
  $user_email=$_SESSION['email'];


  require_once "../connect.php";
  mysqli_report(MYSQLI_REPORT_STRICT);
  try
  {
    $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
    if ($polaczenie->connect_errno!=0)
    {
      throw new Exception(mysqli_connect_errno());
    }
    else
    {


      $rezultat = $polaczenie->query("SELECT * FROM dzialki WHERE user_id='$user_id'");

      if (!$rezultat) throw new Exception($polaczenie->error);
      else
      {
        $html='	<h1>Działki</h1><br/>
        <table cellspacing="0" cellpadding="1" border="1" align="center">
        <tr><td>Numer ewidencyjny działki</td><td>Nazwa własna</td><td>Powierzchnia [ha]</td><td>Lokalizacja</td><td>Uprawa</td></tr>';
        while($wiersz = $rezultat->fetch_assoc())
        {
          $id=$wiersz['id'];
          $nr_e_d=$wiersz['nr_e_d'];
          $nazwa_w=$wiersz['nazwa_w'];
          $pow=$wiersz['pow'];
          $adres=$wiersz['adres'];
          $uprawa=$wiersz['uprawa'];
          $typ_tabeli="dzialki";

          $html=$html.'<tr><td>'.$nr_e_d.'</td><td>'.$nazwa_w.'</td><td>'.$pow.'</td><td>'.$adres.'</td><td>'.$uprawa.'</td></tr>';

        }
        $html=$html.'</table>';

      }
    $polaczenie->close();
    }
  }
  catch(Exception $e)
  {
    echo '<span style="color:red;">Błąd serwera! Proszę spróbować później</span>';
  }

$pdf->writeHTML($html, true, false, true, false, '');

$pdf->Output('zabiegi.pdf', 'I');
?>
