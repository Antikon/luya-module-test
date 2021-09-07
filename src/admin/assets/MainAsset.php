<?php
namespace antikon\luyamoduletest\admin\assets;

use luya\web\Asset;

/**
 * Main Asset for Conference Module
 *
 * @author Anton Ikonnikov <antikon2@yandex.ru>
 * @since 1.0.0
 */
class MainAsset extends Asset
{
    /**
     * @inheritDoc
     */
    public $sourcePath = "@luyamoduletest/resources";

    /**
     * @inheritDoc
     */
    public $js = [
        'js/directives.js',

    ];

    /**
     * @inheritDoc
     */
    public $css = [
    ];

    /**
     * @inheritDoc
     */
    public $depends = [
    ];
}