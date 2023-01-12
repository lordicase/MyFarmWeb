<?php
session_start();
if (!isset($_SESSION['logged']))
	{
		header('Location:log_regis/logowanie.php');
		exit();
	}
?>

<!DOCTYPE HTML>
<html lang="pl">



<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Strona główna</title>

	<link rel="Stylesheet" href="style.css">

</head>
<body>

	<?php require_once "menu.html"; //DODAJEMY MENU?>
		<div class="content">
			<h1>Jak używać strony myfarm.pl?</h1>
		<h3>Krok 1 - Dodaj działkę.</h3>
		<p>Aby dodać działkę wybierz zakładkę Twoje działki, następnie użyj przycisku dodaj działkę i wypełnij pojawiający się formularz. Pamiętaj aby nazwy twoich działek nie powtarzały się ponieważ to one będą wykorzystywane do przypisywania zabiegów. Po dodaniu działki możesz określić obszar który obejmuje. Użyj odpowiedniego przycisku a następnie używając myszki dodaj kolejne znaczniki które będą określać granice działki. Po umieszczeniu wszystkich znaczników użyj przycisku zatwierdź. Pamiętaj że granica zostanie wyznaczona w takiej kolejności jak dodawane są znaczniki. Jeśli nie odpowiada Ci obrys działki możesz ponownie dodać nowy obrys. Każdą działkę można w dowolnej chwili edytować oraz usunąć.</p>
		<h3>Krok 2 - Dodaj zabieg agrotechniczny.</h3>
		<p>Podobnie jak w przypadku działek wybierz odpowiednią zakładkę a następnie użyj przycisku dodaj zabieg. Dla lepszej kontroli zabiegów, zabiegi agrotechniczne oraz ochrony roślin zostały oddzielone.</p>
		<h3>Krok 3 - Księga rejestru bydła.</h3>
		<p>Księga rejestru bydła pozwala na elektroniczne zarządzanie historią pogłowia bydła. Dla ułatwienia prowadzenia historii każdy wpis można dowolnie edytować. Obsługa przebiega podobnie jak w przypadku innych działów.</p>
		<h3>Krok 4 - Pracownicy.</h3>
		<p>Tu możesz dodać podstawowe informacje dotyczące twoich pracowników.</p>
		<h3>Krok 5 - Płodozmian.</h3>
		<p>W tym dziale możesz zaplanować płodozmian dla swoich działek. W dowolnym momencie możesz zmienić od którego roku ma być wyświetlony płodozmian. Przechowywane są również wpisy z poprzednich lat dlatego możesz potraktować to jako archiwum. W dolnej części tabeli wyświetlana jest procentowa struktura zasiewów w danym roku.</p>
		<h3>Krok 6 - Alerty szkodników.</h3>
		<p>Aby dodać alert wybierz odpowiednią uprawę oraz szkodnika z formularza następnie kliknij na mapie w odpowiednim miejscu w którym chcesz dodać alert. Pamiętaj jeśli się pomyliłeś możesz usunąć znacznik, kliknij na niego drugi raz. Aby zatwierdzić znacznik użyj przycisku dodaj. Jeśli chcesz zobaczyć alerty występujące tylko na konkretnych uprawach wybierz interesujące Cię uprawy i użyj przycisku zmień</p>
		<h3>Krok 7 - Prognoza pogody.</h3>
		<p>Prognoza pogody przedstawiana jest na 5 kolejnych dni. Uwzględniono temperaturę, opady, wilgotność oraz zachmurzenie. Poniżej prognozy pogody znajduję się mapa burz. Aby zmienić miejsce dla którego podana jest prognoza użyj przycisku zmień a następnie na mapie wybierz interesujące Cię miejsce oraz zatwierdź zmianę.</p>


		</div>

	</div> <!-- ZAKOŃCZENIE <div class="wrapper"> Z MENU -->
		<div class="footer">Myfarm.pl &copy; 2019 Wszelkie prawa zastrzeżone.</div>










</body>
</html>
