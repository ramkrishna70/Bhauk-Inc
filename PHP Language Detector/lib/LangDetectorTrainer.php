<?php

/**
 * Php Lang Detector
 *
 * Copyright 2011 Juan José López Mellado
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
require_once "Ngram.php";

/**
 * Trainer for language models
 *
 * It calculates the model for all the languages in the data/src directory.
 * Every subdirectory in data/src is treated as a language to train, the name
 * of the directory will become the identifier for the language, all the files
 * into the directory will be used for the calculation of the model.
 *
 * @package PhpLangDetector
 * @author Juan José López Mellado
 * @copyright 2011 Juan José López Mellado
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version $Id$
 */
class LangDetectorTrainer {
	/**
	 * Trimming factor for language models
	 */
	const TRIM_SIZE = 900;

	/**
	 * Limit of input bytes for every source file
	 */
	const MAX_BYTES_PER_LANG = 2000000; // 2M

	function __construct()
	{
		$this->data_dir = dirname(__FILE__) . DIRECTORY_SEPARATOR .
			"data" . DIRECTORY_SEPARATOR;

		if (!is_dir($this->data_dir)) {
			throw new Exception($this->data_dir . ": Data directory does not exists.");
		}
		if (!is_dir($this->data_dir . "model")) {
			throw new Exception($this->data_dir . "model: Models directory does not exists.");
		}
		if (!is_writable($this->data_dir . "model")) {
			throw new Exception($this->data_dir . "model: Models directory is not writable.");
		}
		if (!is_dir($this->data_dir . "src")) {
			throw new Exception($this->data_dir . "src: Sources directory does not exists.");
		}
	}

	/**
	 * Train all language models
	 */
	function train()
	{
		$dirs = glob($this->data_dir . "src" . DIRECTORY_SEPARATOR . "*", GLOB_ONLYDIR | GLOB_NOSORT);
		if (is_array($dirs)) {
			foreach ($dirs as $dir) {
				$lang = basename($dir);
				$model = $this->train_language($dir);
				$this->save_model($lang, $model);

				$this->langs_trained[] = $lang;
			}
		}
	}

	/**
	 * Saves the model of a language
	 * 
	 * @param string $lang language identifier
	 * @param array $data model for the language, with bigrams as keys
	 * @access private
	 */
	private function save_model($lang, $data)
	{
		$output_file = $this->data_dir . "model" . DIRECTORY_SEPARATOR . $lang . ".php";
		if (is_file($output_file)) {
			@unlink($output_file);
		}

		$fp = fopen($output_file, "w");
		if (!$fp) {
			throw new Exception($output_file . ": Can not open for writing.");
		}

		fwrite($fp, <<<EOT
<?php
/**
 * Php Lang Detector
 *
 * Copyright 2011 Juan José López Mellado
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Model file for the '{$lang}' language. This file was created automatically
 */

EOT
		);
		fwrite($fp, "\$model['{$lang}']=array(");
		foreach ($data as $bigram => $value) {
			fwrite($fp, "'" . addslashes($bigram) . "'=>{$value},");
		}
		fwrite($fp, ");\n");
		fclose($fp);
	}

	/**
	 * Build model for a language
	 * @param string $lang_sources_dir the directory where the input files are
	 * @return array the model, with bigrams as key
	 */
	private function train_language($lang_sources_dir)
	{
		$data = array();

		// Add .txt files
		$files = glob($lang_sources_dir . DIRECTORY_SEPARATOR . "*.txt", GLOB_NOSORT);
		if (is_array($files)) {
			foreach ($files as $file) {
				echo "- processing $file\n";
				$txt = file_get_contents($file, FALSE, NULL, -1, self::MAX_BYTES_PER_LANG);
				if ($txt) {
					$data = Ngram::get_trigrams($txt, $data);
				}
			}
		}

		// Add .txt.gz files
		$files = glob($lang_sources_dir . DIRECTORY_SEPARATOR . "*.txt.gz", GLOB_NOSORT);
		if (is_array($files)) {
			foreach ($files as $file) {
				echo "- processing $file\n";
				$fp = gzopen($file, "rb");
				$txt = gzread($fp, self::MAX_BYTES_PER_LANG);
				gzclose($fp);

				if ($txt) {
					$data = Ngram::get_trigrams($txt, $data);
				}
			}
		}

		// Keep tue N'th most valuable bigrams, discart the rest
		arsort($data);
		return Ngram::normalize(array_slice($data, 0, self::TRIM_SIZE));
	}

	/**
	 * Get the list of all the trained languages
	 * @return array
	 */
	function get_trained_languages()
	{
		return $this->langs_trained;
	}

	private $data_dir;
	private $langs_trained = array();

}
