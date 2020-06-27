<?php
require_once "../regexpBuilder.php";
/*
Replace every word with more than 10 letters followed by a space with the character "�":
Et regul�rt uttrykk brukes innen programmering og er en streng som beskriver et sett av strenger � et m�nster � i forhold til 
gitte syntaksregler. Regul�re uttrykk brukes i mange tekstbehandlere og verkt�y for � s�ke etter og manipulere tekst basert 
p� gitte m�nstre. I tillegg er det en rekke programmeringsspr�k som st�tter regul�re uttrykk; Perl er kanskje det mest kjente 
spr�ket i s� henseende.

LOGIC:
- match every letter char repeated 10 or more times followed by a space character
- replace with "�"
*/

//Set the case insensitive mode and the Unicode mode
$regexp=new regexpBuilder(CASE_INSENSITIVE.UNICODE_MODE);

$regexp->capture()
->matchOneOfTheseChars(UNICODE_LETTER_CHAR)->frequency(MORE_THEN,10)  //match every letter char repeated 1,2 or 3 times
->closeCapture()
->ifItIs(FOLLOWED_BY)->match(GENERAL_SPACE_CHAR)->closeIf();  //followed by a space character

$result=$regexp->replaceWith("�","Et regul�rt uttrykk brukes innen programmering og er en streng som beskriver et sett av strenger � et m�nster � i forhold til gitte syntaksregler. Regul�re uttrykk brukes i mange tekstbehandlere og verkt�y for � s�ke etter og manipulere tekst basert p� gitte m�nstre. I tillegg er det en rekke programmeringsspr�k som st�tter regul�re uttrykk; Perl er kanskje det mest kjente spr�ket i s� henseende.");
echo $result;
?>