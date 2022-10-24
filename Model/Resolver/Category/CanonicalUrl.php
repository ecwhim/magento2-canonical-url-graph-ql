<?php
/**
 * Copyright © Ecwhim. All rights reserved.
 */

declare(strict_types=1);

namespace Ecwhim\CanonicalUrlGraphQl\Model\Resolver\Category;

class CanonicalUrl implements \Magento\Framework\GraphQl\Query\ResolverInterface
{
    /**
     * @var \Ecwhim\CanonicalUrl\Model\CanonicalUrlConfigReaderInterface
     */
    protected $canonicalUrlConfigReader;

    /**
     * @var \Ecwhim\CanonicalUrl\Model\CanonicalUrlResolverFactory
     */
    protected $canonicalUrlResolverFactory;

    /**
     * CanonicalUrl constructor.
     *
     * @param \Ecwhim\CanonicalUrl\Model\CanonicalUrlConfigReaderInterface $canonicalUrlConfigReader
     * @param \Ecwhim\CanonicalUrl\Model\CanonicalUrlResolverFactory $canonicalUrlResolverFactory
     */
    public function __construct(
        \Ecwhim\CanonicalUrl\Model\CanonicalUrlConfigReaderInterface $canonicalUrlConfigReader,
        \Ecwhim\CanonicalUrl\Model\CanonicalUrlResolverFactory $canonicalUrlResolverFactory
    ) {
        $this->canonicalUrlConfigReader    = $canonicalUrlConfigReader;
        $this->canonicalUrlResolverFactory = $canonicalUrlResolverFactory;
    }

    /**
     * @param \Magento\Framework\GraphQl\Config\Element\Field $field
     * @param \Magento\Framework\GraphQl\Query\Resolver\ContextInterface $context
     * @param \Magento\Framework\GraphQl\Schema\Type\ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return \Magento\Framework\GraphQl\Query\Resolver\Value|mixed|string|null
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function resolve(
        \Magento\Framework\GraphQl\Config\Element\Field $field,
        $context,
        \Magento\Framework\GraphQl\Schema\Type\ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        if (!isset($value['model'])) {
            throw new \Magento\Framework\Exception\LocalizedException(__('"model" value should be specified'));
        }

        /* @var \Magento\Catalog\Model\Category $category */
        $category = $value['model'];
        /** @var \Magento\Store\Api\Data\StoreInterface $store */
        $store   = $context->getExtensionAttributes()->getStore();
        $storeId = (int)$store->getId();

        if (!$this->canonicalUrlConfigReader->isEnabled($storeId)) {
            return null;
        }

        $canonicalUrlResolver = $this->canonicalUrlResolverFactory->create('catalog_category_view');

        if ($canonicalUrlResolver === null) {
            return null;
        }

        return $canonicalUrlResolver->getCanonicalUrl($category, $storeId);
    }
}
