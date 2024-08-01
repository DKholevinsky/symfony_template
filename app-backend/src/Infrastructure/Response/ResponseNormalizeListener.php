<?php declare(strict_types=1);

namespace App\Infrastructure\Response;

use App\Infrastructure\Response\ResourceReference\ResponseComposer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;

readonly class ResponseNormalizeListener
{
    public function __construct(
        private ResponseComposer $responseComposer,
    ) {}

    public function onKernelView(ViewEvent $event): void
    {
        $value = $event->getControllerResult();
        if ($value instanceof Response) {
            return;
        }

        $response = new JsonResponse($this->responseComposer->process($value));
        $response->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        $event->setResponse($response);
    }
}
