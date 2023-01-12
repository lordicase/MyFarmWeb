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
  if (isset($_POST['rok_rozpoczecia'])) {
    $_SESSION['rok']=$_POST['rok_rozpoczecia'];
  }elseif(!isset($_SESSION['rok'])) {
      $_SESSION['rok']=date(Y,time());
  }


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
        $rok_rozpoczecia=$_SESSION['rok'];

        $html='<h1>Plan płodozmianu</h1><br/>
        <table cellspacing="0" cellpadding="1" border="1" align="center">';
        $html=$html.'<tr><td>Numer ewidencyjny działki</td><td>Nazwa własna</td><td>Pow [ha]</td><td>'.$rok_rozpoczecia++.'</td><td>'.$rok_rozpoczecia++.'</td><td>'.$rok_rozpoczecia++.'</td><td>'.$rok_rozpoczecia++.'</td><td>'.$rok_rozpoczecia++.'</td></tr>';

        while($wiersz = $rezultat->fetch_assoc())
        {
          $id_dzialki=$wiersz['id'];
          $nr_e_d=$wiersz['nr_e_d'];
          $nazwa_w=$wiersz['nazwa_w'];
          $pow=$wiersz['pow'];
          $rok_rozpoczecia=$_SESSION['rok'];

          $i=0;
          $rezultat1 = $polaczenie->query("SELECT * FROM plodozmian WHERE (user_id='$user_id' AND id_dzialki='$id_dzialki' AND rok>='$rok_rozpoczecia') ORDER BY rok ASC");
          if (!$rezultat1) throw new Exception($polaczenie->error);
          else
          {
            while($wiersz = $rezultat1->fetch_assoc())
            {
              $id[$i]=$wiersz['id'];
              $rok=$wiersz['rok'];
              $przedplon[$i]=$wiersz['przedplon'];
              $uprawa_g[$i]=$wiersz['uprawa_g'];
              $dodatkowa_pra[$i]=$wiersz['dodatkowa_pra'];
              $i++;
            }

            $html=$html.'<tr><td rowspan="3">'.$nr_e_d.'</td><td rowspan="3">'.$nazwa_w.'</td><td rowspan="3">'.$pow.'</td><td style="font-size: 11px;">'.$przedplon[0].'</td><td style="font-size: 11px;">'.$przedplon[1].'</td><td style="font-size: 11px;">'.$przedplon[2].'</td><td style="font-size: 11px;">'.$przedplon[3].'</td><td style="font-size: 11px;">'.$przedplon[4].'</td></tr>
            <tr><td>'.$uprawa_g[0].'</td><td>'.$uprawa_g[1].'</td><td>'.$uprawa_g[2].'</td><td>'.$uprawa_g[3].'</td><td>'.$uprawa_g[4].'</td></tr>
            <tr style="font-size: 11px; background-color:#b0d6df"><td>'.$dodatkowa_pra[0].'</td><td>'.$dodatkowa_pra[1].'</td><td>'.$dodatkowa_pra[2].'</td><td>'.$dodatkowa_pra[3].'</td><td>'.$dodatkowa_pra[4].'</td></tr>';
          }


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
