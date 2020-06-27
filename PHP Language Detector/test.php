<?php

require_once ("lib/LangDetector.php");

set_time_limit(-1);

$l = new LangDetector(TRUE);

test_lang("Es muy posible que esto no sea español.","es");
test_lang("It is quite possible that this is not Spanish.","en");
test_lang("Il est fort possible que ce n'est pas l'espagnol.","fr");
test_lang("Es ist durchaus möglich, dass dies nicht Spanisch.","de");
test_lang("È del tutto possibile che questo non è lo spagnolo.","it");
test_lang("És certament possible que això no sigui espanyol.","ca"); // This fails, needs more text to detect "catalan"
test_lang("É bem possível que este não é o espanhol.","pt");

function test_lang($txt,$expected)
{
    global $l;
    $out = $l->get_lang($txt);
    $first = array_shift($out);

    if ($first["lang"]==$expected) {
	echo "OK: $expected [",$first["ratio"],"]\n";
    } else {
	echo "KO: $expected, given: ",$first["lang"], " [", $first["ratio"],"]\n";
    }
}
