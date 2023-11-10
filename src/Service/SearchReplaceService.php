<?php declare(strict_types=1);

namespace SearchReplace\Service;

use Shopware\Core\System\SystemConfig\SystemConfigService;

class SearchReplaceService
{
    private SystemConfigService $systemConfigService;

    public function __construct(SystemConfigService $systemConfigService)
    {
        $this->systemConfigService = $systemConfigService;
    }

    public function searchReplace(string $content): string
    {
        $search = $this->getSearchSnippets();
        $replace = $this->getReplaceSnippets();
        return str_replace($search, $replace, $content);
    }

    private function getSearchSnippets(): array
    {
        $searchSnippets = $this->systemConfigService->getString('SearchReplace.config.searchSnippets');
        return array_map('trim', explode(PHP_EOL, $searchSnippets));
    }

    private function getReplaceSnippets(): array
    {
        $replaceSnippets = $this->systemConfigService->getString('SearchReplace.config.replaceSnippets');
        return array_map('trim', explode(PHP_EOL, $replaceSnippets));
    }
}
