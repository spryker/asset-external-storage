<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternalStorage\Business;

use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Spryker\Zed\AssetExternalStorage\AssetExternalStorageDependencyProvider;
use Spryker\Zed\AssetExternalStorage\Business\Publisher\AssetExternalStorageWriter;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\AssetExternalStorage\AssetExternalStorageConfig getConfig()
 * @method \Spryker\Zed\AssetExternalStorage\Persistence\AssetExternalStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\AssetExternalStorage\Persistence\AssetExternalStorageRepositoryInterface getRepository()
 */
class AssetExternalStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\AssetExternalStorage\Business\Publisher\AssetExternalStorageWriter
     */
    public function createAssetExternalStorageWriter(): AssetExternalStorageWriter
    {
        return new AssetExternalStorageWriter(
            $this->getStoreQuery(),
            $this->getEntityManager()
        );
    }

    /**
     * @return \Orm\Zed\Store\Persistence\SpyStoreQuery
     */
    public function getStoreQuery(): SpyStoreQuery
    {
        return $this->getProvidedDependency(AssetExternalStorageDependencyProvider::PROPEL_QUERY_STORE);
    }
}
