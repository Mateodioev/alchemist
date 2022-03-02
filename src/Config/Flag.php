<?php

namespace App\Config;

use \Exception;

/**
 * FLAG MASTER
 * Converts country code to emoji flag.
 *
 * @version 2020-10-20 18:26:00 UTC
 * @author Peter Kahl <https://github.com/peterkahl>
 * @copyright 2016-2020 Peter Kahl
 * @license Apache License, Version 2.0
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * <http://www.apache.org/licenses/LICENSE-2.0>
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
class Flag
{

    /**
     * Converts string of (one) country code to emoji flag (string).
     * Makes correction for codes that have no corresponding flag.
     * Most flags have 2-letter code, but some have more (eg England=gbeng,
     * Scotland=gbsct, Wales=gbwls, etc.).
     * @param string (one or more 2-letter codes)
     * @throws \Exception
     * @return string
     */
    public static function Emoji(string $code)
    {
        if (!is_string($code) || strlen($code) < 2) return '';
        $code = strtolower($code);
        $replacement = array('uk' => 'gb', 'en' => 'gb', 'an' => 'nl', 'ap' => 'un', 'rd' => 'do');
        if (array_key_exists($code, $replacement)) {
            $code = $replacement[$code];
        }
        return self::_code2unicode($code);
    }

    /**
     * Converts country (or region) code to emoji flag. One flag only!
     * @param string (2 or more letter code)
     * @throws \Exception
     * @return string
     */
    private static function _code2unicode(string $code)
    {
        $arr = str_split($code);
        $str = '';
        foreach ($arr as $char) {
            $str .= self::_enclosedUnicode($char);
        }
        return $str;
    }

    /**
     * Converts a character into enclosed unicode.
     * @param string (one character)
     * @throws \Exception
     * @return string
     */
    private static function _enclosedUnicode(string $char)
    {
        $arr = array('a' => '1F1E6', 'b' => '1F1E7', 'c' => '1F1E8', 'd' => '1F1E9', 'e' => '1F1EA', 'f' => '1F1EB', 'g' => '1F1EC', 'h' => '1F1ED', 'i' => '1F1EE', 'j' => '1F1EF', 'k' => '1F1F0', 'l' => '1F1F1', 'm' => '1F1F2', 'n' => '1F1F3', 'o' => '1F1F4', 'p' => '1F1F5', 'q' => '1F1F6', 'r' => '1F1F7', 's' => '1F1F8', 't' => '1F1F9', 'u' => '1F1FA', 'v' => '1F1FB', 'w' => '1F1FC', 'x' => '1F1FD', 'y' => '1F1FE', 'z' => '1F1FF');
        $char = strtolower($char);
        if (array_key_exists($char, $arr)) {
            return mb_convert_encoding('&#x' . $arr[$char] . ';', 'UTF-8', 'HTML-ENTITIES');
        }
        throw new Exception("Illegal value argument char");
    }

}