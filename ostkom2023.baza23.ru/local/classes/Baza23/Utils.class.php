<?

namespace Baza23;

class Utils {
    public static function psf_parseDir($p_dir) {
        If (! $p_dir) return array();

        $dir = (strpos($p_dir, SITE_DIR) === 0 ? substr($p_dir, strlen(SITE_DIR)) : $p_dir);
        $arPath = explode('/', $dir);
        if (!$arPath[0]) array_shift($arPath);
        if (!$arPath[count($arPath) - 1]) array_pop($arPath);
        return $arPath;
    }

    public static function psf_defineWordEnd($p_number, $ps_word_1, $ps_word_2_4, $ps_word_5_0) {
        $l_num = IntVal($p_number);
        if ($l_num < 0) $l_num = 0 - $l_num;

        $l_ret = ($l_num % 10 == 1 && $l_num % 100 != 11 ? $ps_word_1 :
                ($l_num % 10 >= 2 && $l_num % 10 <= 4 && ($l_num % 100 < 10 || $l_num % 100 >= 20) ? $ps_word_2_4 : $ps_word_5_0));
        return $l_ret;
    }

    public static function psf_array_merge_recursive($dest, $new) {
        if (!is_array($dest) && is_array($new)) return $new;
        if (is_array($dest) && !is_array($new)) return $dest;
        if (!is_array($dest) && !is_array($new)) return array();
        foreach ($new as $k => $v) {
            if (is_array($v) && isset($dest[$k]) && !is_numeric($k)) {
                $dest[$k] = self::psf_array_merge_recursive($dest[$k], $v);
            } else if (!is_numeric($k)) {
                $dest[$k] = $new[$k];
            } else {
                $dest[] = $new[$k];
            }
        }
        return $dest;
    }

    public static $ss_translitParams = array("replace_space" => "-", "replace_other" => "-");

    public static function psf_translit($p_string) {
        $ret = \CUtil::translit($p_string, "ru", self::$ss_translitParams);
        return $ret;
    }

    public static function psf_checkEmail($p_email) {
        return filter_var($p_email, FILTER_VALIDATE_EMAIL);
    }

    public static function psf_clearPhone($p_phone, $p_onlyDigit = false) {
        if ($p_onlyDigit) {
            $ret = preg_replace("/[^0-9]/", '', $p_phone);
            if ($ret) $ret = '+' . $ret;
        } else {
            $ret = filter_var($p_phone, FILTER_SANITIZE_NUMBER_INT);
        }
        return $ret;
    }

    public static function psf_cutDate($p_date) {
        $l_ret = false;
        if ($p_date) {
            $timestamp = MakeTimeStamp($p_date, FORMAT_DATETIME);
            $l_ret = FormatDate(
                    array("" => 'j F Y'),
                    $timestamp,
                    time()
            );
        }
        return $l_ret;
    }

    public static function psf_convertDateTime($p_date) {
        $l_ret = false;
        if ($p_date) {
            $timestamp = MakeTimeStamp($p_date, FORMAT_DATETIME);
            $l_ret = FormatDate(
                    array(
                        "tommorow" => "tommorow, H:i",
                        "today" => "today, H:i",
                        "yesterday" => "yesterday, H:i",
                        "" => 'j F Y'
                    ),
                    $timestamp,
                    time()
            );
        }
        return $l_ret;
    }

    public static function psf_convertDate($p_date) {
        $l_ret = false;
        if ($p_date) {
            $timestamp = MakeTimeStamp($p_date, FORMAT_DATETIME);
            $l_ret = FormatDate(
                    array(
                        "tommorow" => "tommorow",
                        "today" => "today",
                        "yesterday" => "yesterday",
                        "" => 'j F Y'
                    ),
                    $timestamp,
                    time()
            );
        }
        return $l_ret;
    }

    public static function psf_getMonthDays($p_month, $p_year) {
        return cal_days_in_month(CAL_GREGORIAN, $p_month, $p_year);
    }

    public static function psf_getMonthNames() {
        $arRet = Array();
        for ($index = 1; $index <= 12; $index++) {
            $arRet[$index] = FormatDate("f", mktime(0, 0, 0, $index, 1, 2000));
        }
        return $arRet;
    }

    public static function psf_clearText($p_text) {
        if ($p_text) $ret = strip_tags($p_text, '<b><a>');
        return $ret;
    }

    public static function psf_strReplace($p_text, $p_arReplaces) {
        if (empty($p_arReplaces)) return $p_text;

        $ret = $p_text;

        foreach ($p_arReplaces as $old => $new) {
            $cur = '';

            while (($pos = mb_strpos($ret, $old)) !== false) {
                $cur .= mb_substr($ret, 0, $pos) . $new;
                $ret = mb_substr($ret, $pos + mb_strlen($old));
            }

            $ret = $cur . $ret;
        }
        return $ret;
    }

    public static function psf_strFind($p_text, $p_startStr, $p_endStr, $p_includeStr = false) {
        if (!$p_text || !$p_startStr || !$p_endStr) return false;

        $ret = false;
        $pos = mb_strpos($p_text, $p_startStr);
        if ($pos !== false) {
            if (!$p_includeStr) $pos += mb_strlen($p_startStr);

            $pos1 = mb_strpos($p_text, $p_endStr, $pos);
            if ($pos1 > 0) {
                $ret = mb_substr($p_text, $pos, $pos1 - $pos);
                if ($p_includeStr) $ret .= $p_endStr;
            }
        }
        return $ret;
    }

    public static function psf_implodeWithKeys($p_arItems, $p_separator, $p_separator2 = '=') {
        if (empty($p_arItems)) return '';

        $ret = '';
        foreach ($p_arItems as $key => $value) {
            if (!empty($ret)) $ret .= $p_separator;
            $ret .= $key . $p_separator2 . $value;
        }
        return $ret;
    }
}