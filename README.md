# MyFarmWeb
MyFarm jest to internetowa aplikacja przeznaczona dla rolników ułatwiająca zarządzanie gospodarstwem, pozwala na przejście z tradycyjnej dokumentacji papierowej na dokumentacje elektroniczną, poza tym znajdują się w niej podstawowe wiadomości rolnicze, prognoza pogody wraz z mapą występowania burz oraz mapa występowania szkodników oparta na zgłoszeniach innych użytkowników.
![image](https://user-images.githubusercontent.com/56389485/212639014-c495424d-e73b-4d09-bb16-463dcc348f80.png)
Panel logowania.
Interfejs logowania składa się z pól pozwalających na wprowadzenie danych przez użytkownika, pola reCAPTCHA zabezpieczającego aplikacje przed logowaniem przez boty oraz przycisku zatwierdzającego formularz. Poniżej znajdują się przyciski przenoszące do formularza rejestracji oraz systemu zmiany hasła.

![image](https://user-images.githubusercontent.com/56389485/212639038-077647c9-b851-4993-b2f4-c03764cc7cfb.png)
Panel rejestracji.
Rejestracja pozwala na założenie nowego konta użytkownika. Podobnie jak strona logowania, składa się z formularza na dane użytkownika, pola reCAPTCHA oraz przycisku zatwierdzającego. Wymagane jest podanie loginu oraz adresu e-mail niewystępującego jeszcze w bazie. Natomiast hasło musi składać się z minimum 10 znaków.

![image](https://user-images.githubusercontent.com/56389485/212639071-656b29fa-cb52-44a5-b91b-f689bb8721ca.png)
Strona główna.
Strona główna aplikacji przedstawia podstawowe informacje dotyczące gospodarstwa takie jak: obecna struktura zasiewów oraz posiadane sztuki bydła. W lewej części panelu znajdują się aktualne średnie ceny plonów zebrane z obszaru Polski, natomiast po prawej stronie znajduje się panel wiadomości rolniczych przekierowujący do zewnętrznego serwisu farmer.pl.

 ![image](https://user-images.githubusercontent.com/56389485/212639087-1f3c9f55-2b86-4e09-a58d-007768d91cfd.png)
Panel działek.
Poszczególne panele prezentujące dane takie jak: występujące w gospodarstwie działki, historia zabiegów agrotechnicznych i ochrony roślin, księga rejestracji bydła czy spis pracowników, zostały zaprojektowane w podobny sposób. Ze względu na to powyżej przedstawiono przykładowy wygląd interfejsu. W pierwszej części znajduje się tabela przedstawiająca dane. Na końcu każdego wiersza znajdują się przyciski pozwalające na usunięcie i edycję wpisu (Rys.18). W przypadku wpisów dotyczących działek znajduje się tam również przycisk przenoszący do ekranu pozwalającego dodać obrys działki na mapie, poprzez wybieranie kolejnych skrajnych punktów działki. Każdy z nich zostaje zapisany w bazie danych wraz ze współrzędnymi oraz numerem działki, co pozwala na późniejsze odtworzenie kształtu na mapie.  Poniżej tabeli znajduje się przycisk pozwalający na dodanie nowego wpisu oraz przycisk generujący plik PDF(Rys.19) ze wszystkimi wpisami. W przypadku spisu działek, poniżej znajduje się dodatkowo mapa zawierająca obrysy działek.

![image](https://user-images.githubusercontent.com/56389485/212639126-7a1f8aff-18ba-4dfb-a0cf-e7613d11208b.png)
Formularz zmiany wpisu.

 ![image](https://user-images.githubusercontent.com/56389485/212639166-c1eafcfa-d8e2-435e-8283-c6e80b84a42d.png)
Wygenerowany plik PDF.

 ![image](https://user-images.githubusercontent.com/56389485/212639193-3b3b2f20-3e57-4ed8-a256-c1d48bf05947.png)
Interfejs planowania płodozmianu.
	Panel płodozmianu pozwala na przechowywanie historii zasiewów oraz planowanie przyszłego płodozmianu na poszczególnych działkach w dowolnym okresie. W górnej części znajduje się pole określające rok rozpoczęcia planowania. Datę rozpoczęcia można dowolnie zmieniać. Panel pozwala na zaplanowanie wszystkich koniecznych działań takich jak: plon główny, przedplon oraz dodatkowa praktyka. Poniżej tabeli znajdują się grafy przedstawiające strukturę zasiewów w danym roku, opcja ta jest szczególnie przydatna w przypadku ubiegania się o dodatkowe płatności. Przyciski znajdujące się poniżej tabeli pozwalają na dodanie nowej działki oraz wygenerowanie pliku PDF z płodozmianem w aktualnie wybranym przedziale czasowym.

![image](https://user-images.githubusercontent.com/56389485/212639223-e99cb7ef-a655-4be1-b60d-f4a632461856.png)
Mapa występowania szkodników.
W panelu alertów znajduje się mapa przedstawiająca miejsca, w których w ciągu ostatnich 30 dni zostało zgłoszone występowanie szkodników upraw. Po wybraniu konkretnego miejsca uzyskujemy szczegółowe informacje w tym zakresie. Panel z lewej strony pozwala na dodanie nowego miejsca występowania szkodnika z zastrzeżeniem 50 aktywnych jednocześnie. Natomiast poniżej znajdują się pola z wyborem grup znaczników, które maja być widoczne na mapie.

![image](https://user-images.githubusercontent.com/56389485/212639271-2570ff1f-5e53-45f6-8117-3e84d0f4b7f0.png)
Panel interfejsu pogody.
	Prognoza pogody przedstawia w sposób graficzny prognozę na najbliższe 5 dni dla dowolnie wybranego miejsca na świecie. Przycisk znajdujący się pod nazwą aktualnie wybranej miejscowości przekierowuje użytkownika do mapy pozwalającej na wybranie oraz zmianę miejscowości. Na kolejnych grafach przedstawiona jest prognoza temperatury, prędkość wiatru oraz ilość opadów. Dodatkowo poniżej znajduje się mapa Polski z aktualnie występującymi burzami (Rys. 23).

![image](https://user-images.githubusercontent.com/56389485/212639299-c40d136a-ca13-4149-98b9-f92ad49c7f36.png)
Mapa występowania burz w Polsce.
