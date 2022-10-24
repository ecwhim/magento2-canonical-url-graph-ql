<?php
/**
 * Copyright © Ecwhim. All rights reserved.
 */

declare(strict_types=1);

namespace Ecwhim\CanonicalUrlGraphQl\Model\Resolver\CmsPage;

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
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Cms\Api\PageRepositoryInterface
     */
    protected $pageRepository;

    /**
     * CanonicalUrl constructor.
     *
     * @param \Ecwhim\CanonicalUrl\Model\CanonicalUrlConfigReaderInterface $canonicalUrlConfigReader
     * @param \Ecwhim\CanonicalUrl\Model\CanonicalUrlResolverFactory $canonicalUrlResolverFactory
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Cms\Api\PageRepositoryInterface $pageRepository
     */
    public function __construct(
        \Ecwhim\CanonicalUrl\Model\CanonicalUrlConfigReaderInterface $canonicalUrlConfigReader,
        \Ecwhim\CanonicalUrl\Model\CanonicalUrlResolverFactory $canonicalUrlResolverFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Cms\Api\PageRepositoryInterface $pageRepository
    ) {
        $this->canonicalUrlConfigReader    = $canonicalUrlConfigReader;
        $this->canonicalUrlResolverFactory = $canonicalUrlResolverFactory;
        $this->scopeConfig                 = $scopeConfig;
        $this->pageRepository              = $pageRepository;
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
        if (!isset($value[\Magento\Cms\Api\Data\PageInterface::PAGE_ID])) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('"%1" value should be specified', \Magento\Cms\Api\Data\PageInterface::PAGE_ID)
            );
        }

        /** @var \Magento\Store\Api\Data\StoreInterface $store */
        $store   = $context->getExtensionAttributes()->getStore();
        $storeId = (int)$store->getId();

        if (!$this->canonicalUrlConfigReader->isEnabled($storeId)) {
            return null;
        }

        /* @var \Magento\Cms\Api\Data\PageInterface $page */
        $page = $this->pageRepository->getById((int)$value[\Magento\Cms\Api\Data\PageInterface::PAGE_ID]);
        $type = $this->isHomePage($page, $storeId) ? 'cms_index_index' : 'cms_page_view';

        $canonicalUrlResolver = $this->canonicalUrlResolverFactory->create($type);

        if ($canonicalUrlResolver === null) {
            return null;
        }

        return $canonicalUrlResolver->getCanonicalUrl($page, $storeId);
    }

    /**
     * @param \Magento\Cms\Api\Data\PageInterface $page
     * @param int $storeId
     * @return bool
     */
    protected function isHomePage(\Magento\Cms\Api\Data\PageInterface $page, int $storeId): bool
    {
        $homePageIdentifier = $this->scopeConfig->getValue(
            \Magento\Cms\Helper\Page::XML_PATH_HOME_PAGE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
        $delimiterPosition  = strpos($homePageIdentifier, '|');

        if ($delimiterPosition) {
            $homePageIdentifier = substr($homePageIdentifier, 0, $delimiterPosition);
        }

        return $homePageIdentifier === $page->getIdentifier();
    }
}
