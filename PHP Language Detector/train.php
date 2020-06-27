<?php

require_once ("lib/LangDetectorTrainer.php");

echo "Creating language models\n";
echo "be patient, this will take some time\n";

set_time_limit(-1);

$l = new LangDetectorTrainer();
$l->train();

echo "Models created for the following languages: ", implode(", ",$l->get_trained_languages()),"\n";

echo "done.\n";
