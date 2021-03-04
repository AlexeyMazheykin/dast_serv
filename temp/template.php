<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

$l_prefix = "WAS_SHOP_CS_";
?>

<? if (count($arResult["ITEMS"])) { ?>
    <ul class="catalog <?= $arParams["LIST_TYPE"] ?>">
        <? foreach ($arResult["ITEMS"] as $arItem) {
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
            ?>

            <?php

            global $USER;

            $userID = "";

            if (!$USER->GetID()) {

                $arGroups = array("0" => "6");

                $userID = $USER->GetID();

            } else {

                $arGroups = CUser::GetUserGroup($USER->GetID());

                $userID = $USER->GetID();

            }

            $discount = CCatalogDiscount::GetDiscount(
            //$arResult["PRICES"]["BASE"]["ID"],
                $arItem["ID"],
                $arItem["IBLOCK_ID"],
                array(),
                $arGroups,
                "N",
                SITE_ID);

            $price_base = $arItem["PRICES"]["BASE"]["VALUE"];

            $price_normal = number_format($price_base, '2', '.', ' ');

            $price_client = "";

            $price_discount = "";

            foreach ($discount as $key => $item) {

                if ($item["PRIORITY"] == "1") {

                    $DiscountValue = (int)$item["VALUE"];

                    $price_normal = number_format($price_base, '2', '.', ' ');

                    $price_large = number_format($price_base * 1.1, '2', '.', ' ');

                    $price_discount = number_format($price_base - ($price_base * $DiscountValue / 100), '2', '.', ' ');

                } else if ($item["PRIORITY"] == "2") {

                    $DiscountValue = (int)$item["VALUE"];

                    $price_normal = number_format($price_base, '2', '.', ' ');

                    $price_large = number_format($price_base * 1.1, '2', '.', ' ');

                    $price_discount = number_format($price_base - ($price_base * $DiscountValue / 100), '2', '.', ' ');

                } else if ($item["PRIORITY"] == "3" && $userID != "") {

                    $DiscountValue = (int)$item["VALUE"];

                    $price_normal = number_format($price_base, '2', '.', ' ');

                    $price_large = number_format($price_base * 1.1, '2', '.', ' ');

                    $price_discount = number_format($price_base - ($price_base * $DiscountValue / 100), '2', '.', ' ');

                    $price_client = number_format($price_base - ($price_base * $DiscountValue / 100), '2', '.', ' ');

                }

            }

            if ($price_client != "") {

                $price_itogo = $price_client;

            } else if ($price_discount != "") {

                $price_itogo = $price_discount;

            } else {
                $price_itogo = $price_normal;
            }

            if ($_SERVER['REMOTE_ADDR'] == "37.192.113.152") {
                //echo "<pre>";
                //var_dump($price_normal);
                //echo "//////////////////".$DiscountValue." - ".$price_discount." - "." - ".$price_client." - ".(int)str_replace(array(" ","₽"), "", $price_normal)." - norma - ".$price_normal;
                //var_dump($arItem["PROPERTIES"]["CML2_ARTICLE"]["VALUE"]);
                //echo "</pre>";
            }
            ?>


            <li class="item" id="<?= $this->GetEditAreaId($arItem['ID']) ?>">
                <div class="item_body">
                    <p class="vendor__code">Артикул: <strong
                                id="vendor__code"><?php echo $arItem["PROPERTIES"]["CML2_ARTICLE"]["VALUE"]; ?></strong>
                    </p>
                    <? if ($arItem["PREVIEW_PICTURE"]) { ?>
                        <div class="picture">
                            <a href="<?= $arItem["DETAIL_PAGE_URL"] ?>"><img class="lazy"
                                                                             data-src="<?= $arItem["PREVIEW_PICTURE"]["SRC"] ?>"
                                                                             src="<?= $arItem["PREVIEW_PICTURE"]["SRC"] ?>"
                                                                             alt="<?= $arItem["PREVIEW_PICTURE"]["ALT"] ?>"></a>
                        </div>
                    <? } ?>
                    <div class="item_text">
                        <div class="text">
                            <div class="title"><a href="<?= $arItem["DETAIL_PAGE_URL"] ?>"><?= $arItem["NAME"] ?></a>
                            </div>

                            <? if (count($arItem["DISPLAY_PROPERTIES"])) { ?>
                                <ul class="features">
                                    <? $i = 1; ?>
                                    <? foreach ($arItem["DISPLAY_PROPERTIES"] as $arProp) { ?>
                                        <? if ($i++ > 5) break; ?>
                                        <li><?= $arProp["NAME"] ?>: <?= $arProp["DISPLAY_VALUE"] ?></li>
                                    <? } ?>
                                </ul>
                            <? } ?>
                        </div>

                        <div class="extra">
                            <div class="data">
                                <? if ($arItem["MIN_PRICE"]["VALUE"]) { ?>
                                    <div class="price-block">
                                        <?php if ($price_discount != "") { ?>
                                            <div class="old-price regular__price"><?php echo $price_normal; ?><span
                                                        class="symbol"> &#8381;</span></div>
                                        <?php } ?>
                                        <div class="price"><?php echo $price_itogo; ?><span
                                                    class="symbol"> &#8381;</span></div>
                                    </div>
                                <? } ?>

                                <? if (is_array($arItem["PROPERTIES"]["STATUS"]["VALUE"])) { ?>
                                    <div class="availability"><?= $arItem["PROPERTIES"]["STATUS"]["VALUE"]["UF_NAME"] ?></div>
                                <? } ?>
                            </div>

                            <? if ($arItem["MIN_PRICE"]["VALUE"]) { ?>
                                <a href="#" class="buy-btn add_to_basket"
                                   data-id="<?= $arItem["ID"] ?>"><span><?= GetMessage($l_prefix . "BUY_BTN") ?></span></a>
                            <? } ?>
                            <!--
      <? if (isset($arItem["PROPERTIES"]["SPECIAL"]) && is_array($arItem["PROPERTIES"]["SPECIAL"]["VALUE"])) { ?>
      <ul class="tags">
        <? foreach ($arItem["PROPERTIES"]["SPECIAL"]["VALUE"] as $arProp) { ?>
        <li class="tag" style="background-color: #<?= $arProp["UF_COLOR"] ?>; border-color: transparent #<?= $arProp["UF_COLOR"] ?>"><?= $arProp["UF_NAME"] ?></li>
        <? } ?>
      </ul>
      <? } ?>-->
                            <?php if ($arItem["PROPERTIES"]["NEWPRODUCT"]["VALUE"] == "да") { ?>
                                <ul class="tags">
                                    <li class="tag"
                                        style="background: #28a1dc ; border-color: transparent #528dca"><?php echo $arResult["PROPERTIES"]["NEWPRODUCT"]["NAME"]; ?></li>
                                </ul>
                            <?php } ?>
                        </div>
                </div>
            </li>
        <? } ?>
    </ul>
<? } else { ?>
    <div><?= GetMessage($l_prefix . "NO_ELEMENTS") ?></div>
<? } ?>

<? if ($arParams["DISPLAY_BOTTOM_PAGER"]) { ?>
    <?= $arResult["NAV_STRING"] ?>
<? } ?>

<? if (strlen($arResult["DESCRIPTION"]) && $arResult["NAV_RESULT"]->NavPageNomer == 1) { ?>
    <div class="article">
        <?= $arResult["DESCRIPTION"] ?>
    </div>
<? } ?>