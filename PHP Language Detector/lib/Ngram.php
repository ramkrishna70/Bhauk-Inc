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

/**
 * N-gram processing
 * 
 * @package PhpLangDetector
 * @author Juan José López Mellado
 * @copyright 2011 Juan José López Mellado
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version $Id$
 */
class Ngram {

    static function get_trigrams($txt, $ngrams=array()) {
        $txt = str_replace(array("\n", "\r", "\t"), array("", "", " "), $txt);
        $txt = preg_replace('/[ ]+/', "", mb_strtolower($txt,"UTF-8"));

        $txt = preg_split('//u', $txt);  // this is way faster than mb_substr() or even substr()
        for ($i = 0; $i<count($txt)-2; $i++) {
            $bigram = $txt[$i] . $txt[$i + 1]. $txt[$i + 2];
            if (isset($ngrams[$bigram])) {
                $ngrams[$bigram]++;
            } else {
                $ngrams[$bigram] = 1;
            }
        }

        return $ngrams;
    }

	static function get_bigrams($txt, $ngrams=array()) {
        $txt = str_replace(array("\n", "\r", "\t"), array("", "", " "), $txt);
        $txt = preg_replace('/[ ]+/', "", mb_strtolower($txt,"UTF-8"));

        $txt = preg_split('//u', $txt);  // this is way faster than mb_substr() or even substr()
        for ($i = 0; $i<count($txt)-1; $i++) {
            $bigram = $txt[$i] . $txt[$i + 1];
            if (isset($ngrams[$bigram])) {
                $ngrams[$bigram]++;
            } else {
                $ngrams[$bigram] = 1;
            }
        }

        return $ngrams;
    }

    static function normalize($vector) {
        $sum = 0;
        foreach ($vector as $e => $val) {
            $sum += ( $val * $val);
        }
        $length = sqrt($sum);
        if ($length == 0)
            return $vector;

        foreach ($vector as $e => $val) {
            $vector[$e] = $val / $length;
        }
        return $vector;
    }

    static function scalar_product($a,$b)
    {
        $out = 0;
        foreach ($a as $key => $val) {
            if (isset($b[$key])) {
                $out += ($val * $b[$key]);
            }
        }
        return $out;
    }

}
