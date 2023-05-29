<?
namespace Baza23;

class Ajax {

    /* Сохраняет параметры компонента в сессии.
     *
     * $p_component обьект компонента
     * $p_templateName название шаблона компонента
     * return уникальный ключ компонента
     */
    public static function psf_addComponent(\CBitrixComponent $p_component, $p_templateName = '') {
        $name = $p_component->__name;
        $params = self::psf_getComponentParams($p_component->arParams);
        $template = (empty($p_templateName) ? $p_component->GetTemplateName() : $p_templateName);

        ksort($params);
        // генерируем уникальный ключ компонента
        $uniqueKey = md5($name . $template . serialize($params) . SITE_ID);

        // записываем в сессию параметры компонента
        // if (!isset($_SESSION[__CLASS__][$uniqueKey])) {
            if (!empty($params["FILTER_NAME"])) {
                $filter = $GLOBALS[$params["FILTER_NAME"]];
            }

            $_SESSION[__CLASS__][$uniqueKey] = array(
                'NAME'     => $name,
                'PARAMS'   => $params,
                'TEMPLATE' => $template,
                'FILTER'   => $filter,
                'SITE_ID'  => SITE_ID
            );
        // }

        // возвращаем ключ
        return $uniqueKey;
    }

    /* Возвращает параметры компонента из сессии.
     *
     * $p_uniqueKey уникальный ключ компонента
     * return параметры компонента
     */
    public static function psf_getComponent($p_uniqueKey) {
        return $_SESSION[__CLASS__][$p_uniqueKey];
    }

    /* Удаляет параметры компонента из сессии.
     *
     * $p_uniqueKey уникальный ключ компонента
     */
    public static function psf_removeComponent($p_uniqueKey) {
        unset($_SESSION[__CLASS__][$p_uniqueKey]);
    }

    /* Вытаскивает из параметров компонента параметры с "~"
     * (неизменённые пользовательские параметры).
     *
     * $p_params параметры, взятые из компонента
     * return пользовательские параметры компонента
     */
    protected static function psf_getComponentParams($p_params) {
        $arRet = array();
        foreach ($p_params as $key => $value) {
            if (strpos($key, '~') === 0) {
                $key1 = substr($key, 1);
                $arRet[$key1] = $value;
            } else {
                $key1 = '~' . $key;
                if (isset($p_params[$key1])) $arRet[$key] = $p_params[$key1];
                else $arRet[$key] = $value;
            }

            unset($p_params[$key], $p_params[$key1]);
        }
        return $arRet;
    }

    /* Сохраняет параметры фильтра в сессии.
     *
     * $p_uniqueKey уникальный ключ компонента
     * $p_arFilter параметры фильтра
     * return true или false
     */
    public static function psf_updateFilter($p_uniqueKey, $p_arFilter) {
        if (!isset($_SESSION[__CLASS__][$p_uniqueKey])) return false;

        // записываем в сессию параметры фильтра
        $_SESSION[__CLASS__][$p_uniqueKey]['FILTER'] = $p_arFilter;
        return true;
    }

    /* Сохраняет обьект в сессии.
     *
     * $p_object обьект
     * return уникальный ключ обьекта
     */
    public static function psf_addObject($p_object) {
        if (!$p_object) return false;

        // генерируем уникальный ключ обьекта
        if (is_array($p_object)) {
            $uniqueKey = md5(serialize($p_object) . SITE_ID);
        } else {
            $uniqueKey = md5($p_object . SITE_ID);
        }

        // записываем в сессию обьект
        // if (!isset($_SESSION[__CLASS__][$uniqueKey])) {
            $_SESSION[__CLASS__][$uniqueKey] = array(
                'OBJECT'   => $p_object,
                'SITE_ID'  => SITE_ID
            );
        // }

        // возвращаем ключ
        return $uniqueKey;
    }

    /* Возвращает обьект из сессии.
     *
     * $p_uniqueKey уникальный ключ обьекта
     * return обьект
     */
    public static function psf_getObject($p_uniqueKey) {
        return $_SESSION[__CLASS__][$p_uniqueKey];
    }

    /* Удаляет обьект из сессии.
     *
     * $p_uniqueKey уникальный ключ обьекта
     */
    public static function psf_removeObject($p_uniqueKey) {
        unset($_SESSION[__CLASS__][$p_uniqueKey]);
    }
}