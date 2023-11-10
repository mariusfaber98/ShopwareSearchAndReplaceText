<?php declare(strict_types=1);

namespace SearchReplace\Listener;

use SearchReplace\Service\SearchReplaceService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class ResponseListener
{
    private SearchReplaceService $searchReplaceService;

    public function __construct(SearchReplaceService $searchReplaceService)
    {
        $this->searchReplaceService = $searchReplaceService;
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $response = $event->getResponse();

        if ($response instanceof BinaryFileResponse
            || $response instanceof StreamedResponse) {
            return;
        }

        if ($response->getStatusCode() === Response::HTTP_NO_CONTENT) {
            return;
        }

        if (!str_contains($response->headers->get('Content-Type', ''), 'text/html')) {
            return;
        }

        $result = $this->searchReplaceService->searchReplace($response->getContent());

        $response->setContent($result);
    }
}
