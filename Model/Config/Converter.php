<?php
/**
 * Copyright © fr3on. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Fr3on\Lapis\Model\Config;

use Magento\Framework\Config\ConverterInterface;

class Converter implements ConverterInterface
{
    public function convert($source)
    {
        $result = [];
        /** @var \DOMNode $resourceNode */
        foreach ($source->getElementsByTagName('resource') as $resourceNode) {
            $resourceId = $resourceNode->attributes->getNamedItem('id')->nodeValue;
            $resourceData = [
                'id' => $resourceId,
                'endpoint_pattern' => $resourceNode->attributes->getNamedItem('endpoint_pattern')?->nodeValue,
                'state_field' => $resourceNode->attributes->getNamedItem('state_field')?->nodeValue,
                'states' => [],
                'transitions' => []
            ];

            foreach ($resourceNode->childNodes as $childNode) {
                if ($childNode->nodeName === 'state') {
                    $resourceData['states'][$childNode->attributes->getNamedItem('id')->nodeValue] = [
                        'id' => $childNode->attributes->getNamedItem('id')->nodeValue,
                        'label' => $childNode->attributes->getNamedItem('label')->nodeValue,
                        'initial' => $childNode->attributes->getNamedItem('initial')?->nodeValue === 'true',
                        'terminal' => $childNode->attributes->getNamedItem('terminal')?->nodeValue === 'true'
                    ];
                } elseif ($childNode->nodeName === 'transition') {
                    $resourceData['transitions'][] = [
                        'from' => $childNode->attributes->getNamedItem('from')->nodeValue,
                        'to' => $childNode->attributes->getNamedItem('to')->nodeValue,
                        'via' => $childNode->attributes->getNamedItem('via')->nodeValue,
                        'actor' => $childNode->attributes->getNamedItem('actor')?->nodeValue ?? 'system',
                        'endpoint' => $childNode->attributes->getNamedItem('endpoint')?->nodeValue
                    ];
                }
            }
            $result[$resourceId] = $resourceData;
        }
        return $result;
    }
}
