<?php

/**
* Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
* Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
*/

namespace Spryker\Zed\AssetExternalStorage;

use Orm\Zed\AssetExternal\Persistence\SpyAssetExternalQuery;
use Orm\Zed\AssetExternalStorage\Persistence\SpyAssetExternalCmsSlotStorageQuery;
use Orm\Zed\Content\Persistence\SpyContentQuery;
use Orm\Zed\Payment\Persistence\SpyPaymentMethodQuery;
use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Spryker\Zed\AssetExternalStorage\Dependency\Facade\AssetExternalStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\AssetExternalStorage\Dependency\Facade\AssetExternalStorageToEventBehaviorFacadeBridge;
use Spryker\Zed\AssetExternalStorage\Dependency\Service\AssetExternalStorageToSynchronizationServiceBridge;
use Spryker\Zed\ContentStorage\ContentStorageDependencyProvider;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\AssetExternalStorage\Dependency\Facade\AssetExternalStorageToAssetExternalBridge;
use Spryker\Zed\AssetExternalStorage\Dependency\Facade\ProductDiscontinuedStorageToLocaleFacadeBridge;

/**
 * @method \Spryker\Zed\AssetExternalStorage\AssetExternalStorageConfig getConfig()
 */
class AssetExternalStorageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PROPEL_QUERY_STORE = 'PROPEL_QUERY_STORE';
    public const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';
    public const PROPEL_QUERY_ASSET_EXTERNAL_STORAGE = 'PROPEL_QUERY_ASSET_EXTERNAL_STORAGE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        parent::provideBusinessLayerDependencies($container);

        $this->addStoreQuery($container);
        $this->addEventBehaviorFacade($container);

        return $container;
    }

    public function providePersistenceLayerDependencies(Container $container)
    {
        parent::providePersistenceLayerDependencies($container);

        $this->addAssetExternalStorageQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_STORE, $container->factory(function () {
            return SpyStoreQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function addEventBehaviorFacade(Container $container): Container
    {
        $container->set(static::FACADE_EVENT_BEHAVIOR, function (Container $container) {
            return new AssetExternalStorageToEventBehaviorFacadeBridge($container->getLocator()->eventBehavior()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addAssetExternalStorageQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_ASSET_EXTERNAL_STORAGE, $container->factory(function () {
            return SpyAssetExternalCmsSlotStorageQuery::create();
        }));

        return $container;
    }
}
