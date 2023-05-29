<?
namespace Baza23;

class Video {

    public static function psf_replaceVideo($p_text, $p_showVideoInModal) {
        if (empty($p_text)) return "";

        $arRes = self::psf_cutVideo($p_text);
        if (!$arRes || empty($arRes["VIDEO"])) return $p_text;

        $ret = $arRes["TEXT"];
        $arVideo = array_reverse($arRes["VIDEO"]);

        $buttonShow = \Baza23\Settings::psf_settings_getText("section--video", "modal-button-show");
        $iconPlay = \Baza23\Settings::psf_icon_getText("icon-video-play");

        global $APPLICATION;
        foreach ($arVideo as $arItem) {
            $position = $arItem["position"];

            $youtube = $arItem["youtube"];
            $vk = $arItem["vk"];

            $width = $arItem["width"];
            $height = $arItem["height"];

            ob_start();
            $APPLICATION->IncludeComponent(
                "baza23:block.html.video",
                '',
                Array(
                    "CSS_CLASSES"              => "item-video",
                    "CSS_ID"                   => "",

                    "LAZY_LOAD"                => ($p_showVideoInModal ? "N" : "Y"),

                    "AUTOPLAY"                 => ($p_showVideoInModal ? "Y" : "N"),
                    "LOOP"                     => "N",
                    "MUTED"                    => "N",
                    "CONTROLS"                 => "Y",
                    "ALLOWFULLSCREEN"          => "Y",
                    "USE_AUTOSTOP"             => "Y",
                    "USE_AUTOPAUSE"            => "Y",

                    "SHOW_NAME"                => "Y",
                    "SHOW_DESCRIPTION"         => "N",

                    "ORIGINAL_VIDEO_SIZE"      => ($youtube || $vk ? "N" : "Y"),
                    "WIDTH"                    => $width,
                    "HEIGHT"                   => $height,

                    "IBLOCK_ID"                => "",
                    "ELEMENT_ID"               => "",
                    "ELEMENT_CODE"             => "",
                    "PROPERTY_VIDEO_FILE_WEBM" => "",
                    "PROPERTY_VIDEO_FILE_OGG"  => "",
                    "PROPERTY_VIDEO_FILE"      => "",
                    "PROPERTY_VIDEO_YOUTUBE"   => "",
                    "PROPERTY_VIDEO_VK"        => "",
                    "FIELD_VIDEO_IMAGE"        => "",
                    "FIELD_VIDEO_DESCRIPTION"  => "",

                    "VIDEO_FILE_WEBM"   => "",
                    "VIDEO_FILE_OGG"    => "",
                    "VIDEO_FILE"        => "",
                    "VIDEO_YOUTUBE"     => $youtube,
                    "VIDEO_VK"          => $vk,

                    "CACHE_TYPE"        => "N",

                    "SHOW_MODAL"        => ($p_showVideoInModal ? "Y" : "N"),
                    "MODAL_ID"          => "",
                    "MODAL_AJAX_URL"    => "",
                    "MODAL_BUTTON_SHOW" => $buttonShow,

                    "ICON_PLAY"         => $iconPlay,
                ), false
            );
            $newVideo = ob_get_contents();
            ob_end_clean();

            $ret = mb_substr($ret, 0, $position) . $newVideo . mb_substr($ret, $position);
        }
        return $ret;
    }

    public static function psf_cutVideo($p_text) {
        if (!$p_text) return false;

        $arVideo = [];

        $pos = 0;
        $text = $p_text;
        while (($pos = mb_strpos($text, '<iframe ', $pos)) !== false) {
            $pos1 = mb_strpos($text, '</iframe>');
            if ($pos1 === false) $pos1 = mb_strlen($text);
            else $pos1 += 9;

            $video = mb_substr($text, $pos, $pos1 - $pos);
            $youtube = self::psf_getYoutubeCode($video);
            $vk = self::psf_getVkCode($video);
            $text = mb_substr($text, 0, $pos) . mb_substr($text, $pos1);

            $arVideo[] = [
                "position" => $pos,
                "video" => $video,
                "width" => \Baza23\Utils::psf_strFind($video, 'width="', '"', false),
                "height" => \Baza23\Utils::psf_strFind($video, 'height="', '"', false),
                "youtube" => $youtube,
                "vk" => $vk,
            ];
        }

        $arRet = [
            "TEXT"  => $text,
            "VIDEO" => $arVideo
        ];
        return $arRet;
    }

    public static function psf_getYoutubeCode($p_video) {
        if (strpos($p_video, 'youtube') === false
                && strpos($p_video, 'youtu.be') === false) return false;

        $ret = \Baza23\Utils::psf_strFind($p_video, '/embed/', '"', false);
        if (($pos = strpos($ret, '?')) !== false) $ret = substr($ret, 0, $pos);
        return $ret;
    }

    public static function psf_getVkCode($p_video) {
        if (strpos($p_video, 'vk.com') === false) return false;
        return true;
    }
}