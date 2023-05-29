<?

if ($showSubmenuByClick) {
    if ($link) {
        ?><a class="<?= $navLinkCssClasses ?> submenu-link"<?
             ?> href="<?= $link ?>"<?
             if ($blank) { ?> target="_blank"<? }
             if ($rel) { ?> rel="<?= $rel ?>"<? }
        ?>><span><?= $text ?></span></a><?

        if ($showSymbolSubmenu) {
            ?><input type="checkbox"<?
                     ?> id="<?= $id ?>"<?
                     if ($checked) { ?> checked=""<? }
            ?>><?
            ?><label for="<?= $id ?>"></label><?
        }

    } elseif ($showSymbolSubmenu) {
        ?><span class="<?= $navLinkCssClasses ?> submenu-link"><?= $text ?></span><?
        ?><input type="checkbox"<?
                 ?> id="<?= $id ?>"<?
                 if ($checked) { ?> checked=""<? }
        ?>><?
        ?><label for="<?= $id ?>"></label><?

    } else {
        ?><input type="checkbox"<?
                 ?> id="<?= $id ?>"<?
                 if ($checked) { ?> checked=""<? }
        ?>><?
        ?><label for="<?= $id ?>"<?
                 ?> class="<?= $navLinkCssClasses ?> submenu-link"<?
        ?>><?= $text ?></label><?
    }

} else {

    if ($link) {
        ?><a class="<?= $navLinkCssClasses ?> submenu-link"<?
             ?> href="<?= $link ?>"<?
             if ($blank) { ?> target="_blank"<? }
             if ($rel) { ?> rel="<?= $rel ?>"<? }
        ?>><?
    } else {
        ?><span class="<?= $navLinkCssClasses ?> submenu-link"><?
    }

    ?><span><?= $text ?></span><?
    if ($showSymbol) { ?><span class="submenu-icon"></span><? }

    if ($link) {
        ?></a><?
    } else {
        ?></span><?
    }
}
