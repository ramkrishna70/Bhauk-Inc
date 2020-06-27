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
 *
 * @package PhpLangDetector
 * @author Juan José López Mellado
 * @copyright 2011 Juan José López Mellado
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version $Id$
 */
class LangDetector {

    /**
     * Trimming factor for text models
     */
    const TRIM_SIZE = 500;

    /**
     * Construct the detector
     *
     * Use the first parameter to instruct the object to cache the language
     * models, useful to speed up the detection of more than one texts.
     *
     * @param boolean $cache_models_in_memory wether cache models in memory or not
     */
    function __construct($cache_models_in_memory=FALSE)
    {
        if (is_null(self::$data_dir)) {
            self::$data_dir = dirname(__FILE__) . DIRECTORY_SEPARATOR .
                "data" . DIRECTORY_SEPARATOR . "model" . DIRECTORY_SEPARATOR;
        }
        $this->cache_models_in_memory = $cache_models_in_memory==TRUE;
    }

    /**
     * Detect the language in wich a text is written
     *
     * For every available language this method calculates a confidence ratio
     * for the given text. Returns an array with all the tested languages, sorted
     * from higher to lower confidence (take the first for the most probable
     * one).
     *
     * Note that you do not need to use all the text for language detection. You
     * can achieve a reasonable accuracy with some hundreds of characters only,
     * making the detection faster.
     *
     * @param string $txt  the text whose language is to be detected, encoded in
     * UTF-8
     * @param array $consider_langs  consider only the listed languages, leave
     * empty for using all available languages
     * @return array  sorted array, every element is a hash with 'lang' and
     * 'ratio' keys
     */
    function get_lang($txt, $consider_langs=array())
    {
        if (empty($consider_langs)) {
            $consider_langs = $this->get_avail_langs();
        } else {
            // make sure all the given langs are available
            $consider_langs = array_intersect(self::$avail_langs,
                $consider_langs);
        }

        $output = array();
        
        // we cache the bigrams for speep purposes
        $this->cache_bigrams = $this->get_text_model($txt);

        foreach ($consider_langs as $lang) {
	    $output[] = array(
		"lang" => $lang,
		"ratio" => $this->get_ratio_for_lang($txt, $lang)
		);
        }
        $this->cache_bigrams = array(); // release cache
        
        usort($output,'__php_lang_detector_sort');
        return $output;
    }

    /**
     * Returns the available languages for detection
     * @return array list of language identifiers
     */
    function get_avail_langs()
    {
        if (is_null(self::$avail_langs)) {
            self::$avail_langs = array();
            
            $files = glob(
                self::$data_dir . "*.php",
                GLOB_NOSORT);
            if (is_array($files)) {
                foreach ($files as $file) {
                    $file = basename($file,".php");
                    self::$avail_langs[] = $file;
                }
            }
        }
        return self::$avail_langs;
    }


    /**
     * Calculate the confidence ratio for a given text and language
     *
     * @param string $txt the text
     * @param string $lang the identifier for the language to test
     * @return FALSE|float  returns FALSE if something happen (unavailable
     * language, for example), or a float between 0.0 and 1.0
     */
    function get_ratio_for_lang($txt, $lang)
    {
        $model = $this->get_language_model($lang);
        if (!$model)
            return FALSE;

        // calculate bigrams if they are not cached
        $bigrams = array();
        if (count($this->cache_bigrams)) {
            $bigrams = $this->cache_bigrams;
        } else {
            $bigrams = $this->get_text_model($txt);
        }

        // Calculate the distance between the two vectors. Because they are
        // normalized, we only need a scalar product
        return Ngram::scalar_product($bigrams, $model);
    }

    /**
     * Gets the model for a given text
     *
     * @param string $txt the text to be converted
     * @return array model for the text, with bigrams as keys
     * @access private
     */
    private function get_text_model($txt)
    {
        $bigrams = Ngram::get_trigrams($txt);
        arsort($bigrams);
        return Ngram::normalize(
                array_slice($bigrams, 0, self::TRIM_SIZE) );
    }

    /**
     * Get the precalculated model for a language
     * 
     * @param string $lang the language identifier
     * @return array the model, consisting in a hash with bigrams as keys
     * @access private
     */
    private function get_language_model($lang)
    {
        if ($this->cache_models_in_memory && isset(self::$cache_models[$lang])) {
            return self::$cache_models[$lang];
        }

        // Get model from model file
        $model = array();
        $model_file = self::$data_dir . $lang . ".php";
        if (!is_readable($model_file))
            return FALSE;
        include $model_file;
        if (!isset($model[$lang]))
            return FALSE;

        if ($this->cache_models_in_memory) {
            self::$cache_models[$lang] = $model[$lang];
        }

        return $model[$lang];
    }

    private $cache_models_in_memory = FALSE;
    private static $cache_models = array();
    private $cache_bigrams = array();
    private static $data_dir = NULL;
    private static $avail_langs = NULL;
}


if ( !function_exists("__php_lang_detector_sort") ) {
    function __php_lang_detector_sort($a,$b)
    {
	if ($a["ratio"]>$b["ratio"])
	    return -1;
	if ($a["ratio"]<$b["ratio"])
	    return 1;
	return 0;
    }
}