<?php
require_once "../regexpBuilder.php";
/*
Replace every word with more than 10 letters followed by a space with the character "ø":
Et regulært uttrykk brukes innen programmering og er en streng som beskriver et sett av strenger – et mønster – i forhold til 
gitte syntaksregler. Regulære uttrykk brukes i mange tekstbehandlere og verktøy for å søke etter og manipulere tekst basert 
på gitte mønstre. I tillegg er det en rekke programmeringsspråk som støtter regulære uttrykk; Perl er kanskje det mest kjente 
språket i så henseende.

LOGIC:
- match every letter char repeated 10 or more times followed by a space character
- replace with "ø"
*/

//Set the case insensitive mode and the Unicode mode
$regexp=new regexpBuilder(CASE_INSENSITIVE.UNICODE_MODE);

$regexp->capture()
->matchOneOfTheseChars(UNICODE_LETTER_CHAR)->frequency(MORE_THEN,10)  //match every letter char repeated 1,2 or 3 times
->closeCapture()
->ifItIs(FOLLOWED_BY)->match(GENERAL_SPACE_CHAR)->closeIf();  //followed by a space character

$result=$regexp->replaceWith("ø","Et regulært uttrykk brukes innen programmering og er en streng som beskriver et sett av strenger – et mønster – i forhold til gitte syntaksregler. Regulære uttrykk brukes i mange tekstbehandlere og verktøy for å søke etter og manipulere tekst basert på gitte mønstre. I tillegg er det en rekke programmeringsspråk som støtter regulære uttrykk; Perl er kanskje det mest kjente språket i så henseende.");
echo $result;
?>