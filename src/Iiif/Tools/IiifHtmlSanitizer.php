<?php

namespace Ubl\Iiif\Tools;

use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;

class IiifHtmlSanitizer
{
    protected HtmlSanitizer $sanitizer;

    protected static ?IiifHtmlSanitizer $instance = null;

    public function __construct()
    {
        $config = (new HtmlSanitizerConfig())
            ->allowElement("a", ["href"])
            ->allowElement("b")
            ->allowElement("br")
            ->allowElement("i")
            ->allowElement("img", ["alt", "src"])
            ->allowElement("p")
            ->allowElement("small")
            ->allowElement("span")
            ->allowElement("sub")
            ->allowElement("sup")
            ->allowLinkSchemes(["https", "http", "mailto"])
            ->allowMediaSchemes(["https", "http"]);
        $this->sanitizer = new HtmlSanitizer($config);
    }

    public static function sanitizeHtml(?string $input): ?string
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        try {
            return self::$instance->sanitizer->sanitize($input);
        } catch (\Exception $exception) {
            echo $exception->getMessage();
            return null;
        }
    }
}