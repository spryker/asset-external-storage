<?php

/**
* Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
* Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
*/

namespace Spryker\Zed\AssetExternalStorage\Business;

use Orm\Zed\AssetExternal\Persistence\SpyAssetExternalQuery;
use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Spryker\Zed\AssetExternalStorage\AssetExternalStorageDependencyProvider;
use Spryker\Zed\AssetExternalStorage\Business\Publisher\AssetExternalStorageWriter;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductDiscontinuedStorage\Dependency\Facade\AssetExternalStorageToAssetExternalInterface;

/**
 * @method \Spryker\Zed\AssetExternalStorage\AssetExternalStorageConfig getConfig()
 * @method \Spryker\Zed\AssetExternalStorage\Persistence\AssetExternalStorageEntityManagerInterface getEntityManager()
 */
class AssetExternalStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\AssetExternalStorage\Business\Publisher\AssetExternalStorageWriter
     */
    public function createAssetExternalStorageWriter()
    {
        return new AssetExternalStorageWriter(
            $this->getStoreQuery(),
            $this->getEntityManager()
        );
    }

    /**
     * @return \Orm\Zed\Store\Persistence\SpyStoreQuery
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function getStoreQuery(): SpyStoreQuery
    {
        return $this->getProvidedDependency(AssetExternalStorageDependencyProvider::PROPEL_QUERY_STORE);
    }
}
