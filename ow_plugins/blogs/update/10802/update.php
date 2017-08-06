<?php

$languageService = Updater::getLanguageService();

$languages = $languageService->getLanguages();
$langFaId = null;
$langEnId = null;
foreach ($languages as $lang) {
    if ($lang->tag == 'fa-IR') {
        $langFaId = $lang->id;
    }
    if ($lang->tag == 'en') {
        $langEnId = $lang->id;
    }
}


if ($langFaId != null) {
    $languageService->addOrUpdateValue($langFaId, 'blogs', 'show_all_blogs', '&lt;a href="{$url}"&gt;نمایش تمامی بلاگ‌های {$user_name}&lt;/a&gt;');
    $languageService->addOrUpdateValue($langFaId, 'blogs', 'view_page_heading', '{$postTitle}');
}

if ($langEnId != null) {
    $languageService->addOrUpdateValue($langEnId, 'blogs', 'show_all_blogs', '&lt;a href="{$url}"&gt;show all {$user_name}\'s blogs&lt;/a&gt;');
    $languageService->addOrUpdateValue($langEnId, 'blogs', 'view_page_heading', '{$postTitle}');

}