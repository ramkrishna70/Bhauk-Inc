<?php
require_once "../regexpBuilder.php";
/*
Find every word longer than 3 characters:
In der Informatik ist ein Regulärer Ausdruck (engl. regular expression, Abk. RegExp oder Regex) eine Zeichenkette, die der Beschreibung von Mengen beziehungsweise Untermengen von Zeichenketten mit Hilfe bestimmter syntaktischer Regeln dient. Reguläre Ausdrücke finden vor allem in der Softwareentwicklung Verwendung; für fast alle Programmiersprachen existieren Implementierungen.
Reguläre Ausdrücke stellen erstens eine Art Filterkriterium für Texte dar, indem der jeweilige reguläre Ausdruck in Form eines Musters mit dem Text abgeglichen wird. So ist es beispielsweise möglich, alle Wörter, die mit S beginnen und mit D enden, zu matchen (von englisch „to match“ – „auf etwas passen“, „übereinstimmen“, „eine Übereinstimmung finden“), ohne die zwischenliegenden Buchstaben explizit vorgeben zu müssen.
Ein weiteres Beispiel für den Einsatz als Filter ist die Möglichkeit, komplizierte Textersetzungen durchzuführen, indem man die zu suchenden Zeichenketten durch reguläre Ausdrücke beschreibt.
Zweitens lassen sich aus regulären Ausdrücken, als eine Art Schablone, auch Mengen von Wörtern erzeugen, ohne jedes Wort einzeln angeben zu müssen. So lässt sich beispielsweise ein Ausdruck angeben, der alle denkbaren Zeichenkombinationen (Wörter) erzeugt, die mit S beginnen und mit D enden. 

LOGIC:
- start capture
- match every letter char repeated 4 or more times
- end capture and match it one or more times
*/

//Set the case insensitive mode and the Unicode mode,
//beacause in this text there are letters that are not only a-z but also ö,ä
$regexp=new regexpBuilder(CASE_INSENSITIVE.UNICODE_MODE);

$regexp->capture()	//Start a capture
->matchOneOfTheseChars(UNICODE_LETTER_CHAR)->frequency(MORE_THEN_OR_EQUAL_TO,4)	//match every letter char repeated 4 or more times
->closeCapture();	//end capture

$match=$regexp->execOn("In der Informatik ist ein Regulärer Ausdruck (engl. regular expression, Abk. RegExp oder Regex) eine Zeichenkette, die der Beschreibung von Mengen beziehungsweise Untermengen von Zeichenketten mit Hilfe bestimmter syntaktischer Regeln dient. Reguläre Ausdrücke finden vor allem in der Softwareentwicklung Verwendung; für fast alle Programmiersprachen existieren Implementierungen. Reguläre Ausdrücke stellen erstens eine Art Filterkriterium für Texte dar, indem der jeweilige reguläre Ausdruck in Form eines Musters mit dem Text abgeglichen wird. So ist es beispielsweise möglich, alle Wörter, die mit S beginnen und mit D enden, zu matchen (von englisch „to match“ – „auf etwas passen“, „übereinstimmen“, „eine Übereinstimmung finden“), ohne die zwischenliegenden Buchstaben explizit vorgeben zu müssen. Ein weiteres Beispiel für den Einsatz als Filter ist die Möglichkeit, komplizierte Textersetzungen durchzuführen, indem man die zu suchenden Zeichenketten durch reguläre Ausdrücke beschreibt.Zweitens lassen sich aus regulären Ausdrücken, als eine Art Schablone, auch Mengen von Wörtern erzeugen, ohne jedes Wort einzeln angeben zu müssen. So lässt sich beispielsweise ein Ausdruck angeben, der alle denkbaren Zeichenkombinationen (Wörter) erzeugt, die mit S beginnen und mit D enden.");
foreach($match[1] as $result)
	echo $result."<br>";
?>