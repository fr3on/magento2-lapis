<?php
/**
 * Copyright © fr3on. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Fr3on\Lapis\Plugin\Rest;

use Fr3on\Lapis\Api\StateReaderInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Webapi\Controller\Rest;

class AddLapisHeadersPlugin
{
    private const ROUTE_MAP = [
        'orders' => 'order',
        'invoices' => 'invoice',
        'creditmemos' => 'creditmemo',
        'shipments' => 'shipment',
        'carts' => 'cart',
    ];

    /**
     * @param RequestInterface $request
     * @param StateReaderInterface[] $readers
     */
    public function __construct(
        private RequestInterface $request,
        private array $readers = []
    ) {}

    /**
     * @param Rest $subject
     * @param ResponseInterface $response
     * @return ResponseInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterDispatch(Rest $subject, ResponseInterface $response): ResponseInterface
    {
        $pathInfo = $this->request->getPathInfo();
        $parts = explode('/', trim($pathInfo, '/'));
        
        $resourcePlural = $parts[2] ?? null;

        if (!$resourcePlural || !isset(self::ROUTE_MAP[$resourcePlural])) {
            return $response;
        }

        $lapisResourceId = self::ROUTE_MAP[$resourcePlural];
        $reader = $this->readers[$lapisResourceId] ?? null;

        if (!$reader) {
            return $response;
        }

        return $this->injectLapisHeaders($response, $reader, $lapisResourceId);
    }

    /**
     * @param ResponseInterface $response
     * @param StateReaderInterface $reader
     * @param string $resourceId
     * @return ResponseInterface
     */
    private function injectLapisHeaders(
        ResponseInterface $response,
        StateReaderInterface $reader,
        string $resourceId
    ): ResponseInterface {
        $data = json_decode($response->getContent(), true);
        $stateField = $reader->getStateField();
        $currentState = ($stateField && isset($data[$stateField])) ? (string)$data[$stateField] : null;

        if ($currentState) {
            $response->setHeader('X-LAPIS-State', $currentState, true);
            $response->setHeader('X-LAPIS-Resource', $resourceId, true);
            $response->setHeader('X-LAPIS-Inferred', $reader->isInferred() ? 'true' : 'false', true);

            $this->injectTransitionsHeader($response, $reader, $currentState);
        }

        return $response;
    }

    /**
     * @param ResponseInterface $response
     * @param StateReaderInterface $reader
     * @param string $currentState
     * @return void
     */
    private function injectTransitionsHeader(
        ResponseInterface $response,
        StateReaderInterface $reader,
        string $currentState
    ): void {
        $allowed = [];
        foreach ($reader->getTransitions() as $t) {
            if ($t->getFrom() === $currentState) {
                $allowed[] = $t->getTo();
            }
        }
        
        if (!empty($allowed)) {
            $response->setHeader('X-LAPIS-Transitions', implode(',', array_unique($allowed)), true);
        }
    }
}
