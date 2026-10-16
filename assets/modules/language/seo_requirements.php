<?php
    /**
     * Created by PhpStorm.
     * User: user
     * Date: 10.08.18
     * Time: 17:42
     */

    $modx->documentObject['microdata'] = '';
    $shop                              = new Shop($modx);
    if (isset($_GET['p'])) {
        $modx->documentObject['seo_canonical'] = $modx->makeUrl($modx->documentObject['id']);
    }

    // генерация мета-тегов
    if ($_SESSION['country'] != 'RU') {
        switch ($modx->documentObject['template']) {
            // категория
            case $modx->config['shop_tpl_category']:
                $amountFilters = $shop->countFilters();
                switch (true) {
                    case $amountFilters > 1 || $modx->getPlaceholder('issetPrice'):
                        $modx->documentObject['seo_robots']    = 'noindex, nofollow';
                        $modx->documentObject['seo_canonical'] = $modx->makeUrl($modx->documentObject['id']);
                    case $amountFilters > 0:
                        $modx->documentObject['seo_content'] = '';
                        break;
                }
                if ($modx->getPlaceholder('brnd_first_letter')) {
                    $modx->documentObject['seo_canonical'] = '';
                }
                $filters = $shop->getFiltersNames('brand');

                if ($shop->countFilters() == 1 && $modx->getPlaceholder("brnd_first_letter")) {
                    $modx->documentObject['seo_title']       = 'Купить '
                        . $modx->documentObject['pagetitle'] . ' '
                        . $filters . ' в интернет-магазине MakeUpGrim.com';
                    $modx->documentObject['seo_description'] = $modx->documentObject['pagetitle'] . ' '
                        . $filters . ' по выгодным ценам ✔ 100% оригинальная продукция ✔ Доставка по Украине ✈ Киев ✈ Одесса ✈ Днепр ✈ Харьков ✔Интернет-магазин профессиональной косметики MakeUpGrim.com';
                } else {
                    $modx->documentObject['seo_title']       = isset($modx->documentObject['seo_title'][1]) && $modx->documentObject['seo_title'][1] ? $modx->documentObject['seo_title'][1] : 'Купить '
                        . $modx->documentObject['pagetitle'] . ' '
                        . $filters . ' в интернет-магазине MakeUpGrim.com';
                    $modx->documentObject['seo_description'] = isset($modx->documentObject['seo_description'][1]) && $modx->documentObject['seo_description'][1] ? $modx->documentObject['seo_description'][1] : $modx->documentObject['pagetitle'] . ' '
                        . $filters . ' по выгодным ценам ✔ 100% оригинальная продукция ✔ Доставка по Украине ✈ Киев ✈ Одесса ✈ Днепр ✈ Харьков ✔Интернет-магазин профессиональной косметики MakeUpGrim.com';
                }

                if ($shop->countFilters() == 1 && !$modx->getPlaceholder("brnd_first_letter")) {
                    $modx->documentObject['seo_robots'] = 'noindex, nofollow';
                }

                $modx->documentObject['pagetitle'] = $modx->documentObject['pagetitle'] . ' ' . $filters;

                break;

            // бренд
            case $modx->config['shop_brand_single_template']:
                $modx->documentObject['seo_title']       = isset($modx->documentObject['seo_title'][1]) && $modx->documentObject['seo_title'][1] ? $modx->documentObject['seo_title'][1] : 'Оригинальная косметика '
                    . $modx->documentObject['pagetitle'] . ' в интернет-магазине MakeUpGrim.com';
                $modx->documentObject['seo_description'] = isset($modx->documentObject['seo_description'][1]) && $modx->documentObject['seo_description'][1] ? $modx->documentObject['seo_description'][1] : 'Косметика '
                    . $modx->documentObject['pagetitle']
                    . ' по выгодным ценам ✔ 100% оригинальная продукция ✔ Доставка по Украине ✈ Киев ✈ Одесса ✈ Днепр ✈ Харьков ✔Интернет-магазин профессиональной косметики MakeUpGrim.com';
                break;

            // продукт
            case $modx->config['shop_item_template']:
                $modx->documentObject['seo_title']       = isset($modx->documentObject['product_seo_title']) && $modx->documentObject['product_seo_title'] ? $modx->documentObject['product_seo_title'] : 'Оригинальная косметика '
                    . $modx->documentObject['pagetitle'] . ' ' . $modx->documentObject['product_brand'] . ' купить в интернет-магазине MakeUpGrim.com';
                $modx->documentObject['seo_description'] = isset($modx->documentObject['product_seo_description']) && $modx->documentObject['product_seo_description'] ? $modx->documentObject['product_seo_description'] : $modx->documentObject['pagetitle']
                    . ' ' . $modx->documentObject['product_brand'] . ' по выгодным ценам ✔ 100% оригинальная продукция ✔ Доставка по Украине ✈ Киев ✈ Одесса ✈ Днепр ✈ Харьков ✔Интернет-магазин профессиональной косметики MakeUpGrim.com';
                break;
        }
    }

    switch ($modx->documentObject['template']) {
        case (int)$modx->config['shop_home_template']:
            // контакты
        case 12:
            // О компании
        case 15:
            // Контентный шаблон
        case 24:
            $data         = [
                "@context" => "http://schema.org",
                "@type"    => 'Organization',
            ];
            $data['name'] = $modx->config['site_name'];
            $data['url']  = $modx->config['site_url'];
            $data['logo'] = $modx->config['site_url'] . 'assets/images/logo_white.svg';
            if ($modx->config['as_fb']) {
                $data['sameAs'][] = $modx->config['as_fb'];
            }
            if ($modx->config['as_insta']) {
                $data['sameAs'][] = $modx->config['as_insta'];
            }
            if ($modx->config['as_insta']) {
                $data['telephone'] = explode(',', $modx->config['as_microdata_phones']);
            }
            $modx->documentObject['microdata'] = $modx->parseChunk('tpl_microdata', [
                'data' => json_encode($data, JSON_UNESCAPED_UNICODE),
            ]);
            if ($modx->documentObject['seo_content'] && $modx->documentObject['seo_content'][1]) {
                $modx->documentObject['seo_content'] = $modx->parseChunk('tpl_product_seo', [
                    'product_content' => $modx->documentObject['seo_content'][1],
                ]);
                $modx->documentObject['h_pagetitle'] = $modx->documentObject['pagetitle'];
            } else {
                $modx->documentObject['h_pagetitle'] = "<h1>" . $modx->documentObject['pagetitle'] . "</h1>";
            }
            break;
        case $modx->config['shop_item_template']:
            $modx->documentObject['seo_canonical'] = $modx->makeUrl($modx->config['shop_page_item'], '', '', 'full') . $modx->documentObject['product_url'] . '/';
        case $modx->config['shop_brand_template']:
            if ($modx->documentObject['seo_content'] && $modx->documentObject['seo_content'][1]) {
                $modx->documentObject['seo_content'] = $modx->parseChunk('tpl_product_seo', [
                    'product_content' => $modx->documentObject['seo_content'][1],
                ]);
                $modx->documentObject['h_pagetitle'] = $modx->documentObject['pagetitle'];
            } else {
                $modx->documentObject['h_pagetitle'] = "<h1>" . $modx->documentObject['pagetitle'] . "</h1>";
            }

            break;
//        case (int)$modx->config['shop_tpl_category']:
//        case (int)$modx->config['shop_brand_single_template']:
        // страница поиска
//        case 26:
        case (int)$modx->config['shop_tpl_category']:
            $prices = json_decode($modx->runSnippet("Shop", [
                "get"           => 'getFilterPrice',
                'for_microdata' => true,
            ]), true);

            $data                              = [
                "@context" => "http://schema.org",
                "@type"    => 'product',
                'name'     => $modx->documentObject['pagetitle'],
                'offers'   => [
                    "@type"         => "AggregateOffer",
                    "lowPrice"      => $prices['minimum'],
                    "highPrice"     => $prices['maximum'],
                    "priceCurrency" => $_SESSION['currency'],
                ],
            ];
            $modx->documentObject['microdata'] = $modx->parseChunk('tpl_microdata', [
                'data' => json_encode($data, JSON_UNESCAPED_UNICODE),
            ]);
        default:

            if ($modx->documentObject['seo_content'] && $modx->documentObject['seo_content'][1]) {
                $modx->documentObject['seo_content'] = $modx->parseChunk('tpl_product_seo', [
                    'product_content' => $modx->documentObject['seo_content'][1],
                ]);
                $modx->documentObject['h_pagetitle'] = $modx->documentObject['pagetitle'];
            } else {
                $modx->documentObject['h_pagetitle'] = "<h1>" . $modx->documentObject['pagetitle'] . "</h1>";
            }
            break;
    }
