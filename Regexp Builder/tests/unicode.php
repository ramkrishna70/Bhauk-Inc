<?php
require_once "../regexpBuilder.php";
/*
Find every word longer than 3 characters:
In der Informatik ist ein Regul�rer Ausdruck (engl. regular expression, Abk. RegExp oder Regex) eine Zeichenkette, die der Beschreibung von Mengen beziehungsweise Untermengen von Zeichenketten mit Hilfe bestimmter syntaktischer Regeln dient. Regul�re Ausdr�cke finden vor allem in der Softwareentwicklung Verwendung; f�r fast alle Programmiersprachen existieren Implementierungen.
Regul�re Ausdr�cke stellen erstens eine Art Filterkriterium f�r Texte dar, indem der jeweilige regul�re Ausdruck in Form eines Musters mit dem Text abgeglichen wird. So ist es beispielsweise m�glich, alle W�rter, die mit S beginnen und mit D enden, zu matchen (von englisch �to match� � �auf etwas passen�, ��bereinstimmen�, �eine �bereinstimmung finden�), ohne die zwischenliegenden Buchstaben explizit vorgeben zu m�ssen.
Ein weiteres Beispiel f�r den Einsatz als Filter ist die M�glichkeit, komplizierte Textersetzungen durchzuf�hren, indem man die zu suchenden Zeichenketten durch regul�re Ausdr�cke beschreibt.
Zweitens lassen sich aus regul�ren Ausdr�cken, als eine Art Schablone, auch Mengen von W�rtern erzeugen, ohne jedes Wort einzeln angeben zu m�ssen. So l�sst sich beispielsweise ein Ausdruck angeben, der alle denkbaren Zeichenkombinationen (W�rter) erzeugt, die mit S beginnen und mit D enden. 

LOGIC:
- start capture
- match every letter char repeated 4 or more times
- end capture and match it one or more times
*/

//Set the case insensitive mode and the Unicode mode,
//beacause in this text there are letters that are not only a-z but also �,�
$regexp=new regexpBuilder(CASE_INSENSITIVE.UNICODE_MODE);

$regexp->capture()	//Start a capture
->matchOneOfTheseChars(UNICODE_LETTER_CHAR)->frequency(MORE_THEN_OR_EQUAL_TO,4)	//match every letter char repeated 4 or more times
->closeCapture();	//end capture

$match=$regexp->execOn("In der Informatik ist ein Regul�rer Ausdruck (engl. regular expression, Abk. RegExp oder Regex) eine Zeichenkette, die der Beschreibung von Mengen beziehungsweise Untermengen von Zeichenketten mit Hilfe bestimmter syntaktischer Regeln dient. Regul�re Ausdr�cke finden vor allem in der Softwareentwicklung Verwendung; f�r fast alle Programmiersprachen existieren Implementierungen. Regul�re Ausdr�cke stellen erstens eine Art Filterkriterium f�r Texte dar, indem der jeweilige regul�re Ausdruck in Form eines Musters mit dem Text abgeglichen wird. So ist es beispielsweise m�glich, alle W�rter, die mit S beginnen und mit D enden, zu matchen (von englisch �to match� � �auf etwas passen�, ��bereinstimmen�, �eine �bereinstimmung finden�), ohne die zwischenliegenden Buchstaben explizit vorgeben zu m�ssen. Ein weiteres Beispiel f�r den Einsatz als Filter ist die M�glichkeit, komplizierte Textersetzungen durchzuf�hren, indem man die zu suchenden Zeichenketten durch regul�re Ausdr�cke beschreibt.Zweitens lassen sich aus regul�ren Ausdr�cken, als eine Art Schablone, auch Mengen von W�rtern erzeugen, ohne jedes Wort einzeln angeben zu m�ssen. So l�sst sich beispielsweise ein Ausdruck angeben, der alle denkbaren Zeichenkombinationen (W�rter) erzeugt, die mit S beginnen und mit D enden.");
foreach($match[1] as $result)
	echo $result."<br>";
?>