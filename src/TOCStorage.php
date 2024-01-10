<?php

declare(strict_types=1);

namespace SimpleTOC;

use TOC\MarkupFixer;
use TOC\TocGenerator;

class TOCStorage
{
    private string $fixedContent;
    private string $tableOfContents;
    private int $itemsCount;

    private static ?self $instance = null;

    public function __construct(string $content)
    {
        $fixer = new MarkupFixer();
        $fixedContent = $fixer->fix($content);

        $tocGenerator = new TocGenerator();
        $this->tableOfContents = $tocGenerator->getHtmlMenu($fixedContent);
        $this->itemsCount = substr_count($this->tableOfContents, "</li>");
        $toc = "<br/> <p>" . __("Table of Contents", "simpletableofcontents") . "</p>" . $this->tableOfContents . "<h2";

        $pos = strpos($fixedContent, "<h2");

        if ($pos !== false && $this->itemsCount > 2) {
            $this->fixedContent = substr_replace($fixedContent, $toc, $pos, 3);
        } else {
            $this->fixedContent = $content;
        }

        self::$instance = $this;
    }

    public static function getInstance(): self
    {
        if (null === self::$instance) {
            throw new \RuntimeException("Not initialized yet!");
        }

        return self::$instance;
    }

    public function getTableOfContents(): string
    {
        return $this->tableOfContents;
    }

    public function getFixedContent(): string
    {
        return $this->fixedContent;
    }

    public function getCount(): int
    {
        return $this->itemsCount;
    }
}