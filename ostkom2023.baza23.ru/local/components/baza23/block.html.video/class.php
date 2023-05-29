<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Loader;

class PC_BLOCK_HtmlVideo extends CBitrixComponent {
    /**
     * Проверка наличия модулей требуемых для работы компонента
     * @return bool
     * @throws Exception
     */
    private function _checkModules() {
        if (!Loader::includeModule('iblock')) {
            $this->arResult["ERROR"] = array('TYPE' => 'MODULE_IBLOCK_IS_NOT_LOADED');
            return false;
        }
        return true;
    }

    /**
     * Подготовка параметров компонента
     * @param $p_arParams
     * @return mixed
     */
    public function onPrepareComponentParams($p_arParams) {
        if (!isset($p_arParams["CACHE_TYPE"])) $p_arParams["CACHE_TYPE"] = "N";
        if (!isset($p_arParams["CACHE_TIME"])) $p_arParams["CACHE_TIME"] = 0;

        $p_arParams["LAZY_LOAD"] = (trim($p_arParams["LAZY_LOAD"]) == "Y" ? "Y" : "N");

        $p_arParams["CSS_CLASSES"] = trim($p_arParams["CSS_CLASSES"]);
        $p_arParams["CSS_ID"] = trim($p_arParams["CSS_ID"]);

        $p_arParams["ORIGINAL_VIDEO_SIZE"] = (trim($p_arParams["ORIGINAL_VIDEO_SIZE"]) == 'Y' ? 'Y' :'N');
        $p_arParams["AUTOPLAY"] = (trim($p_arParams["AUTOPLAY"]) == 'Y' ? 'Y' :'N');
        $p_arParams["LOOP"] = (trim($p_arParams["LOOP"]) == 'Y' ? 'Y' :'N');
        $p_arParams["MUTED"] = (trim($p_arParams["MUTED"]) == 'Y' ? 'Y' :'N');
        $p_arParams["CONTROLS"] = (trim($p_arParams["CONTROLS"]) == 'N' ? 'N' :'Y');
        $p_arParams["ALLOWFULLSCREEN"] = (trim($p_arParams["ALLOWFULLSCREEN"]) == 'Y' ? 'Y' :'N');
        $p_arParams["USE_AUTOSTOP"] = (trim($p_arParams["USE_AUTOSTOP"]) == 'N' ? 'N' :'Y');
        $p_arParams["USE_AUTOPAUSE"] = (trim($p_arParams["USE_AUTOPAUSE"]) == 'N' ? 'N' :'Y');

        $p_arParams["WIDTH"] = IntVal($p_arParams["WIDTH"]);
        $p_arParams["HEIGHT"] = IntVal($p_arParams["HEIGHT"]);

        $p_arParams["SHOW_NAME"] = (trim($p_arParams["SHOW_NAME"]) == 'Y' ? 'Y' :'N');
        $p_arParams["SHOW_DESCRIPTION"] = (trim($p_arParams["SHOW_DESCRIPTION"]) == 'Y' ? 'Y' :'N');

        $p_arParams["SHOW_MODAL"] = (trim($p_arParams["SHOW_MODAL"]) == 'Y' ? 'Y' :'N');
        $p_arParams["MODAL_ID"] = trim($p_arParams["MODAL_ID"]);
        $p_arParams["MODAL_AJAX_URL"] = trim($p_arParams["MODAL_AJAX_URL"]);
        $p_arParams["MODAL_BUTTON_SHOW"] = trim($p_arParams["MODAL_BUTTON_SHOW"]);

        $p_arParams["SITE_ID"] = trim($p_arParams["SITE_ID"]);
        $p_arParams["IBLOCK_TYPE"] = trim($p_arParams["IBLOCK_TYPE"]);
        $p_arParams["IBLOCK_ID"] = intval($p_arParams["IBLOCK_ID"]);
        $p_arParams["IBLOCK_CODE"] = trim($p_arParams["IBLOCK_CODE"]);

        $p_arParams["ELEMENT_ID"] = intval($p_arParams["ELEMENT_ID"]);
        $p_arParams["ELEMENT_CODE"] = trim($p_arParams["ELEMENT_CODE"]);

        $p_arParams["PROPERTY_VIDEO_FILE"] = trim($p_arParams["PROPERTY_VIDEO_FILE"]);
        $p_arParams["PROPERTY_VIDEO_FILE_OGG"] = trim($p_arParams["PROPERTY_VIDEO_FILE_OGG"]);
        $p_arParams["PROPERTY_VIDEO_FILE_WEBM"] = trim($p_arParams["PROPERTY_VIDEO_FILE_WEBM"]);
        $p_arParams["PROPERTY_VIDEO_YOUTUBE"] = trim($p_arParams["PROPERTY_VIDEO_YOUTUBE"]);
        $p_arParams["PROPERTY_VIDEO_VK"] = trim($p_arParams["PROPERTY_VIDEO_VK"]);
        $p_arParams["PROPERTY_VIDEO_IMAGE"] = trim($p_arParams["PROPERTY_VIDEO_IMAGE"]);
        $p_arParams["FIELD_VIDEO_IMAGE"] = trim($p_arParams["FIELD_VIDEO_IMAGE"]);
        $p_arParams["PROPERTY_VIDEO_NAME"] = trim($p_arParams["PROPERTY_VIDEO_NAME"]);
        $p_arParams["FIELD_VIDEO_NAME"] = trim($p_arParams["FIELD_VIDEO_NAME"]);
        $p_arParams["PROPERTY_VIDEO_DESCRIPTION"] = trim($p_arParams["PROPERTY_VIDEO_DESCRIPTION"]);
        $p_arParams["FIELD_VIDEO_DESCRIPTION"] = trim($p_arParams["FIELD_VIDEO_DESCRIPTION"]);

        if (!empty($p_arParams["VIDEO_FILE"])) {
            if (!is_array($p_arParams["VIDEO_FILE"])) {
                $p_arParams["VIDEO_FILE"] = CFile::GetFileArray($p_arParams["VIDEO_FILE"]);
            } elseif (!isset($p_arParams["VIDEO_FILE"]["SRC"])) {
                unset($p_arParams["VIDEO_FILE"]);
            }
        }
        if (!empty($p_arParams["VIDEO_FILE_OGG"])) {
            if (!is_array($p_arParams["VIDEO_FILE_OGG"])) {
                $p_arParams["VIDEO_FILE_OGG"] = CFile::GetFileArray($p_arParams["VIDEO_FILE_OGG"]);
            } elseif (!isset($p_arParams["VIDEO_FILE_OGG"]["SRC"])) {
                unset($p_arParams["VIDEO_FILE_OGG"]);
            }
        }
        if (!empty($p_arParams["VIDEO_FILE_WEBM"])) {
            if (!is_array($p_arParams["VIDEO_FILE_WEBM"])) {
                $p_arParams["VIDEO_FILE_WEBM"] = CFile::GetFileArray($p_arParams["VIDEO_FILE_WEBM"]);
            } elseif (!isset($p_arParams["VIDEO_FILE_WEBM"]["SRC"])) {
                unset($p_arParams["VIDEO_FILE_WEBM"]);
            }
        }
        if (is_array($p_arParams["VIDEO_YOUTUBE"])) $p_arParams["VIDEO_YOUTUBE"] = trim($p_arParams["VIDEO_YOUTUBE"]["TEXT"]);
        else $p_arParams["VIDEO_YOUTUBE"] = trim($p_arParams["VIDEO_YOUTUBE"]);

        if (is_array($p_arParams["VIDEO_VK"])) $p_arParams["VIDEO_VK"] = trim($p_arParams["VIDEO_VK"]["TEXT"]);
        else $p_arParams["VIDEO_VK"] = trim($p_arParams["VIDEO_VK"]);

        if (!empty($p_arParams["VIDEO_IMAGE"])) {
            if (!is_array($p_arParams["VIDEO_IMAGE"])) {
                $p_arParams["VIDEO_IMAGE"] = CFile::GetFileArray($p_arParams["VIDEO_IMAGE"]);
            } elseif (!isset($p_arParams["VIDEO_IMAGE"]["SRC"])) {
                unset($p_arParams["VIDEO_IMAGE"]);
            }
        }
        if (is_array($p_arParams["VIDEO_NAME"])) $p_arParams["VIDEO_NAME"] = trim($p_arParams["VIDEO_NAME"]["TEXT"]);
        else $p_arParams["VIDEO_NAME"] = trim($p_arParams["VIDEO_NAME"]);
        if (is_array($p_arParams["VIDEO_DESCRIPTION"])) $p_arParams["VIDEO_DESCRIPTION"] = trim($p_arParams["VIDEO_DESCRIPTION"]["TEXT"]);
        else $p_arParams["VIDEO_DESCRIPTION"] = trim($p_arParams["VIDEO_DESCRIPTION"]);
        return $p_arParams;
    }

    /**
     * Точка входа в компонент
     * Должна содержать только последовательность вызовов вспомогательых ф-ий и минимум логики
     * всю логику стараемся разносить по классам и методам
     */
    public function executeComponent() {
        if (!$this->_checkModules()) return $this->arResult;

        $this->arParams = $this->onPrepareComponentParams($this->arParams);

        if ($this->StartResultCache()) {
            if (!empty($this->arParams["VIDEO_FILE"])
                    || !empty($this->arParams["VIDEO_FILE_OGG"])
                    || !empty($this->arParams["VIDEO_FILE_WEBM"])
                    || !empty($this->arParams["VIDEO_YOUTUBE"])
                    || !empty($this->arParams["VIDEO_VK"])) {
                $this->arResult["ELEMENT_ID"] = $this->arParams["ELEMENT_ID"];
                $this->arResult["ELEMENT_CODE"] = $this->arParams["ELEMENT_CODE"];

                $this->arResult["VIDEO_FILE"] = $this->arParams["VIDEO_FILE"];
                $this->arResult["VIDEO_FILE_OGG"] = $this->arParams["VIDEO_FILE_OGG"];
                $this->arResult["VIDEO_FILE_WEBM"] = $this->arParams["VIDEO_FILE_WEBM"];
                $this->arResult["VIDEO_YOUTUBE"] = self::psf_checkVideoYoutube($this->arParams["VIDEO_YOUTUBE"], $this->arParams);
                $this->arResult["VIDEO_VK"] = self::psf_checkVideoVk($this->arParams["VIDEO_VK"], $this->arParams);
                $this->arResult["VIDEO_IMAGE"] = $this->arParams["VIDEO_IMAGE"];
                $this->arResult["VIDEO_DESCRIPTION"] = $this->arParams["VIDEO_DESCRIPTION"];
                $this->EndResultCache();

            } elseif (($this->arParams["ELEMENT_ID"] > 0 || $this->arParams["ELEMENT_CODE"])
                    && $arIBlock = $this -> pf_checkIBlock($this->arParams)) {
                $this->arParams["IBLOCK_ID"] = $arIBlock["ID"];

                $this->arResult = $this -> pf_loadVideo($this->arParams);
                $this->EndResultCache();

            } else {
                $this->AbortResultCache();
                $this->arResult["ERROR"] = array(
                        'TYPE' => 'UNDEFINED_VIDEO',
                        'PARAMS' => array(
                                "SITE_ID" => $this->arParams["SITE_ID"],
                                "IBLOCK_TYPE" => $this->arParams["IBLOCK_TYPE"],
                                "IBLOCK_CODE" => $this->arParams["IBLOCK_CODE"],
                                "IBLOCK_ID" => $this->arParams["IBLOCK_ID"],
                                "ELEMENT_ID" => $this->arParams["ELEMENT_ID"],
                                "ELEMENT_CODE" => $this->arParams["ELEMENT_CODE"],
                        ),
                );
            }
        }
        $this -> IncludeComponentTemplate();
        return $this;
    }

    public function pf_loadVideo($p_arParams) {
        $arRet = array();

        $arVideo = $this->pf_getElement($p_arParams);
        if (!empty($arVideo)) {
            $arRet["ELEMENT_ID"] = $arVideo["ID"];
            $arRet["ELEMENT_CODE"] = $arVideo["CODE"];

            if ($p_arParams["PROPERTY_VIDEO_FILE"]) {
                $videoFile = $arVideo["PROPERTY_" . $p_arParams["PROPERTY_VIDEO_FILE"] . "_VALUE"];
                if ($videoFile) $videoFile = CFile::GetFileArray($videoFile);
                if (isset($videoFile["SRC"])) $arRet["VIDEO_FILE"] = $videoFile;
            }
            if ($p_arParams["PROPERTY_VIDEO_FILE_OGG"]) {
                $videoFile = $arVideo["PROPERTY_" . $p_arParams["PROPERTY_VIDEO_FILE_OGG"] . "_VALUE"];
                if ($videoFile) $videoFile = CFile::GetFileArray($videoFile);
                if (isset($videoFile["SRC"])) $arRet["VIDEO_FILE_OGG"] = $videoFile;
            }
            if ($p_arParams["PROPERTY_VIDEO_FILE_WEBM"]) {
                $videoFile = $arVideo["PROPERTY_" . $p_arParams["PROPERTY_VIDEO_FILE_WEBM"] . "_VALUE"];
                if ($videoFile) $videoFile = CFile::GetFileArray($videoFile);
                if (isset($videoFile["SRC"])) $arRet["VIDEO_FILE_WEBM"] = $videoFile;
            }
            if ($p_arParams["PROPERTY_VIDEO_YOUTUBE"]) {
                $arRet["VIDEO_YOUTUBE"] = $arVideo["PROPERTY_" . $p_arParams["PROPERTY_VIDEO_YOUTUBE"] . "_VALUE"];
                if (is_array($arRet["VIDEO_YOUTUBE"])) $arRet["VIDEO_YOUTUBE"] = $arRet["VIDEO_YOUTUBE"]["TEXT"];
                if ($arRet["VIDEO_YOUTUBE"]) {
                    $arRet["VIDEO_YOUTUBE"] = self::psf_checkVideoYoutube($arRet["VIDEO_YOUTUBE"], $p_arParams);
                }
            }
            if ($p_arParams["PROPERTY_VIDEO_VK"]) {
                $arRet["VIDEO_VK"] = $arVideo["PROPERTY_" . $p_arParams["PROPERTY_VIDEO_VK"] . "_VALUE"];
                if (is_array($arRet["VIDEO_VK"])) $arRet["VIDEO_VK"] = $arRet["VIDEO_VK"]["TEXT"];
                if ($arRet["VIDEO_VK"]) {
                    $arRet["VIDEO_VK"] = self::psf_checkVideoVk($arRet["VIDEO_VK"], $p_arParams);
                }
            }

            if ($arRet["VIDEO_FILE"]
                    || $arRet["VIDEO_FILE_OGG"]
                    || $arRet["VIDEO_FILE_WEBM"]
                    || $arRet["VIDEO_YOUTUBE"]
                    || $arRet["VIDEO_VK"]) {

                if ($p_arParams["VIDEO_IMAGE"]) {
                    $arRet["VIDEO_IMAGE"] = $p_arParams["VIDEO_IMAGE"];
                } else {
                    if ($p_arParams["FIELD_VIDEO_IMAGE"]) {
                        $imageFile = $arVideo[$p_arParams["FIELD_VIDEO_IMAGE"]];
                        if ($imageFile) $imageFile = CFile::GetFileArray($imageFile);
                        if (isset($imageFile["SRC"])) $arRet["VIDEO_IMAGE"] = $imageFile;
                    }
                    if (!$arRet["VIDEO_IMAGE"] && $p_arParams["PROPERTY_VIDEO_IMAGE"]) {
                        $imageFile = $arVideo["PROPERTY_" . $p_arParams["PROPERTY_VIDEO_IMAGE"] . "_VALUE"];
                        if ($imageFile) $imageFile = CFile::GetFileArray($imageFile);
                        if (isset($imageFile["SRC"])) $arRet["VIDEO_IMAGE"] = $imageFile;
                    }
                }

                if ($p_arParams["VIDEO_NAME"]) {
                    $arRet["VIDEO_NAME"] = $p_arParams["VIDEO_NAME"];
                } else {
                    if ($p_arParams["FIELD_VIDEO_NAME"]) {
                        $arRet["VIDEO_NAME"] = $arVideo[$p_arParams["FIELD_VIDEO_NAME"]];
                        if (is_array($arRet["VIDEO_NAME"])) $arRet["VIDEO_NAME"] = $arRet["VIDEO_NAME"]["TEXT"];
                    }
                    if (!$arRet["VIDEO_NAME"] && $p_arParams["PROPERTY_VIDEO_NAME"]) {
                        $arRet["VIDEO_NAME"] = $arVideo["PROPERTY_" . $p_arParams["PROPERTY_VIDEO_NAME"] . "_VALUE"];
                        if (is_array($arRet["VIDEO_NAME"])) $arRet["VIDEO_NAME"] = $arRet["VIDEO_NAME"]["TEXT"];
                    }
                }

                if ($p_arParams["VIDEO_DESCRIPTION"]) {
                    $arRet["VIDEO_DESCRIPTION"] = $p_arParams["VIDEO_DESCRIPTION"];
                } else {
                    if ($p_arParams["FIELD_VIDEO_DESCRIPTION"]) {
                        $arRet["VIDEO_DESCRIPTION"] = $arVideo[$p_arParams["FIELD_VIDEO_DESCRIPTION"]];
                        if (is_array($arRet["VIDEO_DESCRIPTION"])) $arRet["VIDEO_DESCRIPTION"] = $arRet["VIDEO_DESCRIPTION"]["TEXT"];
                    }
                    if (!$arRet["VIDEO_DESCRIPTION"] && $p_arParams["PROPERTY_VIDEO_DESCRIPTION"]) {
                        $arRet["VIDEO_DESCRIPTION"] = $arVideo["PROPERTY_" . $p_arParams["PROPERTY_VIDEO_DESCRIPTION"] . "_VALUE"];
                        if (is_array($arRet["VIDEO_DESCRIPTION"])) $arRet["VIDEO_DESCRIPTION"] = $arRet["VIDEO_DESCRIPTION"]["TEXT"];
                    }
                }
            }
        }
        return $arRet;
    }

    public static function psf_checkVideoYoutube($p_video, $p_arParams) {
        $video = trim($p_video);
        if (empty($video)) return false;

        $ret = $video;
        if (strpos($video, '<iframe') === false) {
            if (($pos = strpos($video, '//youtu.be/')) !== false) {
                $video = substr($video, $pos + 11);
            } elseif (($pos = strpos($video, 'youtube.com/watch?v=')) !== false) {
                $video = substr($video, $pos + 20);
            }

            $ret = '<iframe';
            if ($p_arParams["WIDTH"]) $ret .= ' width="' . $p_arParams["WIDTH"] . '"';
            if ($p_arParams["HEIGHT"]) $ret .= ' height="' . $p_arParams["HEIGHT"] . '"';

            if ($p_arParams["LAZY_LOAD"] == "Y") {
                $ret .= ' src="" data-src="https://www.youtube.com/embed/' . $video . '?';
            } else {
                $ret .= ' src="https://www.youtube.com/embed/' . $video . '?';
            }

            $arParams = [
                'enablejsapi'    => '0',
                'modestbranding' => '1',
                'showinfo'       => '0',
                'rel'            => '0',
            ];
            if ($p_arParams["CONTROLS"] == "N") $arParams['controls'] = '0';
            if ($p_arParams["ALLOWFULLSCREEN"] == "N") $arParams['fs'] = '0';
            if ($p_arParams["AUTOPLAY"] == "Y") $arParams['autoplay'] = '1';
            if ($p_arParams["MUTED"] == "Y") $arParams['mute'] = '1';
            if ($p_arParams["LOOP"] == "Y") {
                $arParams['loop'] = '1';
                $arParams['playlist'] = $video;
            }

            $index = 0;
            foreach ($arParams as $key => $param) {
                if ($index > 0) $ret .= '&amp;';
                $ret .= $key . '=' . $param;
                $index ++;
            }

            $ret .= '" frameborder="0"';
            $ret .= ' allow="accelerometer;';
            if ($p_arParams["AUTOPLAY"] == "Y") $ret .= ' autoplay;';
            $ret .= ' encrypted-media; gyroscope; picture-in-picturerel=0;"';
            if ($p_arParams["ALLOWFULLSCREEN"] == "Y") $ret .= ' allowfullscreen';

            $ret .= '></iframe>';
        }
        return $ret;
    }

    public static function psf_checkVideoVk($p_video, $p_arParams) {
        $video = trim($p_video);
        if (empty($video)) return false;

        $ret = $video;
        if (strpos($video, '<iframe') === false) {
            $ret = '<iframe';
            if ($p_arParams["WIDTH"]) $ret .= ' width="' . $p_arParams["WIDTH"] . '"';
            if ($p_arParams["HEIGHT"]) $ret .= ' height="' . $p_arParams["HEIGHT"] . '"';
            if ($p_arParams["LAZY_LOAD"] == "Y") {
                $ret .= ' src="" data-src="https://www.vk.com/video_ext.php?' . $video;
            } else {
                $ret .= ' src="https://www.vk.com/video_ext.php?' . $video;
            }

            $arParams = [
                //'enablejsapi'    => '0',
                //'modestbranding' => '1',
                //'showinfo'       => '0',
                //'rel'            => '0',
            ];
            //if ($p_arParams["CONTROLS"] == "N") $arParams['controls'] = '0';
            //if ($p_arParams["ALLOWFULLSCREEN"] == "N") $arParams['fs'] = '0';
            if ($p_arParams["AUTOPLAY"] == "Y") $arParams['autoplay'] = '1';
            //if ($p_arParams["MUTED"] == "Y") $arParams['mute'] = '1';
            /* if ($p_arParams["LOOP"] == "Y") {
                $arParams['loop'] = '1';
                $arParams['playlist'] = $video;
            } */

            $index = 0;
            foreach ($arParams as $key => $param) {
                $ret .= '&amp;';
                $ret .= $key . '=' . $param;
                $index ++;
            }

            $ret .= ' frameborder="0"';
            $ret .= ' allow="';
            if ($p_arParams["AUTOPLAY"] == "Y") $ret .= ' autoplay;';
            if ($p_arParams["ALLOWFULLSCREEN"] == "Y") $ret .= ' fullscreen;';
            $ret .= ' encrypted-media; picture-in-picturerel;"';
            if ($p_arParams["ALLOWFULLSCREEN"] == "Y") $ret .= ' allowfullscreen';

            $ret .= '></iframe>';
        }
        return $ret;
    }

    // IBLOCK
    public function pf_checkIBlock($p_arParams) {
        $arIBlock = $this -> pf_getIBlock($p_arParams);
        return $arIBlock;
    }

    protected function pf_getIBlock($p_arParams) {
        $arFilter = array();

        if ($p_arParams["IBLOCK_ID"] > 0) {
            return array("ID" => $p_arParams["IBLOCK_ID"]);
        } elseif ($p_arParams["IBLOCK_CODE"]) {
            if ($p_arParams["IBLOCK_TYPE"]) $arFilter["TYPE"] = $p_arParams["IBLOCK_TYPE"];
            $arFilter["CODE"] = $p_arParams["IBLOCK_CODE"];
        } else {
            return false;
        }

        if ($p_arParams["SITE_ID"]) $arFilter["SITE_ID"] = $p_arParams["SITE_ID"];

        $arRet = false;
        $dbIBlock = CIBlock::GetList(array('SORT' => 'ASC', 'ID' => 'ASC'), $arFilter);
        $dbIBlock = new CIBlockResult($dbIBlock);
        if ($arIBlock = $dbIBlock -> fetch()) {
            if ($arIBlock["ACTIVE"] == "Y") {
                $arRet = $arIBlock;
            }
        }
        return $arRet;
    }
    // End of IBLOCK

    // ELEMENTS
    protected function pf_getElementSelect($p_arParams) {
        $arRet = array("IBLOCK_ID", "ID", "CODE");
        if ($p_arParams["FIELD_VIDEO_IMAGE"]) {
            $arRet[] = $p_arParams["FIELD_VIDEO_IMAGE"];
        }
        if ($p_arParams["FIELD_VIDEO_NAME"]) {
            $arRet[] = $p_arParams["FIELD_VIDEO_NAME"];
        }
        if ($p_arParams["FIELD_VIDEO_DESCRIPTION"]) {
            $arRet[] = $p_arParams["FIELD_VIDEO_DESCRIPTION"];
        }
        if ($p_arParams["PROPERTY_VIDEO_FILE"]) {
            $arRet[] = "PROPERTY_" . $p_arParams["PROPERTY_VIDEO_FILE"];
        }
        if ($p_arParams["PROPERTY_VIDEO_FILE_OGG"]) {
            $arRet[] = "PROPERTY_" . $p_arParams["PROPERTY_VIDEO_FILE_OGG"];
        }
        if ($p_arParams["PROPERTY_VIDEO_FILE_WEBM"]) {
            $arRet[] = "PROPERTY_" . $p_arParams["PROPERTY_VIDEO_FILE_WEBM"];
        }
        if ($p_arParams["PROPERTY_VIDEO_YOUTUBE"]) {
            $arRet[] = "PROPERTY_" . $p_arParams["PROPERTY_VIDEO_YOUTUBE"];
        }
        if ($p_arParams["PROPERTY_VIDEO_IMAGE"]) {
            $arRet[] = "PROPERTY_" . $p_arParams["PROPERTY_VIDEO_IMAGE"];
        }
        if ($p_arParams["PROPERTY_VIDEO_DESCRIPTION"]) {
            $arRet[] = "PROPERTY_" . $p_arParams["PROPERTY_VIDEO_DESCRIPTION"];
        }
        if ($p_arParams["PROPERTY_VIDEO_NAME"]) {
            $arRet[] = "PROPERTY_" . $p_arParams["PROPERTY_VIDEO_NAME"];
        }
        $arRet = array_unique($arRet);
        return $arRet;
    }

    protected function pf_getElementFilter($p_arParams) {
        $arRet = array(
                "IBLOCK_ID" => $p_arParams["IBLOCK_ID"],
                "ACTIVE" => "Y"
        );
        if ($p_arParams["ELEMENT_ID"]) {
            $arRet['ID'] = $p_arParams["ELEMENT_ID"];
        } elseif ($p_arParams["ELEMENT_CODE"]) {
            $arRet['CODE'] = $p_arParams["ELEMENT_CODE"];
        }
        return $arRet;
    }

    protected function pf_getElementOrder($p_arParams) {
        $arRet = array();
        return $arRet;
    }

    protected function pf_getElement($p_arParams) {
        $arOrder = $this -> pf_getElementOrder($p_arParams);
        $arFilter = $this -> pf_getElementFilter($p_arParams);
        $arSelect = $this -> pf_getElementSelect($p_arParams);

        $arRet = array();
        $dbElement = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
        if ($arElement = $dbElement -> Fetch()) {
            $arRet = $arElement;

        }
        return $arRet;
    }
    // End of ELEMENTS
}