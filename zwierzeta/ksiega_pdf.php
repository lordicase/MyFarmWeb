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
$pdf->AddPage(L);


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
    $rezultat = $polaczenie->query("SELECT * FROM dziennik WHERE user_id='$user_id'");

    if (!$rezultat) throw new Exception($polaczenie->error);
    else
    {
      $html='<h1>Księga rejestracji bydła</h1><br/>
      <TABLE cellspacing="0" cellpadding="1" border="1" align="center">

          <tr>
            <td rowspan="2">Lp.</td><td rowspan="2">Numer identyfikacyjny zwierzęcia</td><td rowspan="2"  >Data urodzenia</td><td rowspan="2">Płeć</td><td rowspan="2">Kod rasy</td><td rowspan="2"  >Data oznakowania</td><td>Numer matki</td><td  >Data przybycia</td><td rowspan="2">Dane przybycia</td><td>Data ubycia</td><td  >Dane ubycia</td>
          </tr>
          <tr>
            <td>Numer ojca</td><td>Kod zdarzenia</td><td>Kod zdarzenia</td><td>Dane przewoźnika</td>
          </tr>';
          $lp=1;
          while($wiersz = $rezultat->fetch_assoc())
          {
            $id=$wiersz['id'];
            $nr_zwierzecia=$wiersz['nr_zwierzecia'];
            $data_urodzenia=$wiersz['data_urodzenia'];
            $plec=$wiersz['plec'];
            $kod_rasy=$wiersz['kod_rasy'];
            $data_oznakowania=$wiersz['data_oznakowania'];
            $nr_matki=$wiersz['nr_matki'];
            $nr_ojca=$wiersz['nr_ojca'];
            $data_przybycia=$wiersz['data_przybycia'];
            $kod_zdarzenia_p=$wiersz['kod_zdarzenia_p'];
            $dane_przybycia=$wiersz['dane_przybycia'];
            $data_ubycia=$wiersz['data_ubycia'];
            $kod_zdarzenia_u=$wiersz['kod_zdarzenia_u'];
            $dane_ubycia=$wiersz['dane_ubycia'];
            $dane_przewoznika=$wiersz['dane_przewoznika'];
            $uwagi=$wiersz['uwagi'];
            $typ_tabeli='dziennik';

            $html=$html. '<tr>
              <td rowspan="2">'.$lp.'</td><td rowspan="2">'.$nr_zwierzecia.'</td><td rowspan="2"  >'.$data_urodzenia.'</td><td rowspan="2">'.$plec.'</td><td rowspan="2">'.$kod_rasy.'</td><td rowspan="2"  >'.$data_oznakowania.'</td><td>'.$nr_matki.'</td><td  >'.$data_przybycia.'</td><td rowspan="2">'.$dane_przybycia.'</td><td  >'.$data_ubycia.'</td><td>'.$dane_ubycia.'</td>

            </tr>
            <tr>
              <td>'.$nr_ojca.'</td><td>'.$kod_zdarzenia_p.'</td><td>'.$kod_zdarzenia_u.'</td><td>'.$dane_przewoznika.'</td>
            </tr>';
            $lp++;
          }
        $html=$html."</TABLE>";
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
