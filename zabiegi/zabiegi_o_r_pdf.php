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
    $rezultat = $polaczenie->query("SELECT * FROM zabiegi_o_r WHERE user_id='$user_id' ORDER BY data ASC");

    if (!$rezultat) throw new Exception($polaczenie->error);
    else
    {
      $html='<h1>Zabiegi ochrony roślin</h1><br/>
      <TABLE cellspacing="0" cellpadding="1" border="1" align="center">';
      $html=$html."<TR><TD>Nazwa działki</TD><TD>Uprawa</TD><TD>Obszar wykonania zabiegu</TD><TD>Data</TD><TD>Nazwa środka</TD><TD>Dawka</TD><TD>Uwagi</TD></TR>";
      while($wiersz = $rezultat->fetch_assoc())
      {
        $id=$wiersz['id'];
        $nazwa_w_d=$wiersz['nazwa_w_d'];
        $uprawa=$wiersz['uprawa'];
        $pow_z=$wiersz['pow_z'];
        $data=$wiersz['data'];
        $nazwa=$wiersz['nazwa'];
        $dawka=$wiersz['dawka'];
        $uwagi=$wiersz['uwagi'];
        $typ_tabeli="zabiegi_o_r";

        $html=$html."<TR><TD>$nazwa_w_d</TD><TD>$uprawa</TD><TD>$pow_z</TD><TD>$data</TD><TD>$nazwa</TD><TD>$dawka</TD><TD>$uwagi</TD></TR>";

      }
      $html=$html."</table>";
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
