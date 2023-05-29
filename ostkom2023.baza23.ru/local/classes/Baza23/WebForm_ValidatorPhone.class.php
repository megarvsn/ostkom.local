<?
namespace Baza23;

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class WebForm_ValidatorPhone {
    public static function getDescription() {
        return [
            'NAME'            => 'wfv_phone', // идентификатор
            'DESCRIPTION'     => 'Номер телефона', // наименование
            'TYPES'           => ['text'], // типы полей
            'SETTINGS'        => ['\Baza23\WebForm_ValidatorPhone', 'getSettings'], // метод, возвращающий массив настроек
            'CONVERT_TO_DB'   => ['\Baza23\WebForm_ValidatorPhone', 'toDB'], // метод, конвертирующий массив настроек в строку
            'CONVERT_FROM_DB' => ['\Baza23\WebForm_ValidatorPhone', 'fromDB'], // метод, конвертирующий строку настроек в массив
            'HANDLER'         => ['\Baza23\WebForm_ValidatorPhone', 'doValidate'], // валидатор
        ];
    }

    public static function getSettings() {
        return [];
    }

    public static function toDB($arParams) {
        // возвращаем сериализованную строку
        return serialize($arParams);
    }

    public static function fromDB($strParams) {
        // никаких преобразований не требуется, просто вернем десериализованный массив
        return unserialize($strParams);
    }

    public static function doValidate($arParams, $arQuestion, $arAnswers, $arValues) {
        global $APPLICATION;
        foreach ($arValues as $value) {
            $value = preg_replace('/[^0-9]/', '', $value);
            // проверяем на пустоту
            if (strlen($value) < 11 || strlen($value) > 18) {
                // вернем ошибку
                $APPLICATION->ThrowException(Loc::getMessage('ERROR_UNCORRECT_PHONE_NUMBER'));
                return false;
            }
        }

        // все значения прошли валидацию, вернем true
        return true;
    }
}