<?php
/**
 * Copyright © fr3on. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Fr3on\Lapis\Block\Adminhtml;

use Magento\Backend\Block\Template;
use Fr3on\Lapis\Api\StateReaderInterface;
use Fr3on\Lapis\Model\Generator\RldGenerator;
use Fr3on\Lapis\Model\Generator\YamlSerializer;

class Lapis extends Template
{
    /**
     * @param Template\Context $context
     * @param RldGenerator $generator
     * @param YamlSerializer $serializer
     * @param StateReaderInterface[] $readers
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        private RldGenerator $generator,
        private YamlSerializer $serializer,
        private array $readers = [],
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * @return array
     */
    public function getAllLapisData(): array
    {
        return $this->generator->generateAll();
    }

    /**
     * @param array $data
     * @return string
     */
    public function getYaml(array $data): string
    {
        return $this->serializer->serialize($data);
    }

    /**
     * @return string[]
     */
    public function getResourceIds(): array
    {
        return array_keys($this->readers);
    }
}
