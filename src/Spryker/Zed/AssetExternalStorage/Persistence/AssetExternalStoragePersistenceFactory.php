<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternalStorage\Persistence;

use Orm\Zed\AssetExternal\Persistence\SpyAssetExternalQuery;
use Orm\Zed\AssetExternalStorage\Persistence\SpyAssetExternalCmsSlotStorageQuery;
use Spryker\Zed\AssetExternalStorage\AssetExternalStorageDependencyProvider;
use Spryker\Zed\AssetExternalStorage\Persistence\Mapper\AssetExternalStorageMapper;
use Spryker\Zed\AssetExternalStorage\Persistence\Mapper\AssetExternalStorageMapperInterface;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\AssetExternalStorage\AssetExternalStorageConfig getConfig()
 * @method \Spryker\Zed\AssetExternalStorage\Persistence\AssetExternalStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\AssetExternalStorage\Persistence\AssetExternalStorageRepositoryInterface getRepository()
 */
class AssetExternalStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\AssetExternalStorage\Persistence\SpyAssetExternalCmsSlotStorageQuery
     */
    public function createAssetExternalStorageQuery(): SpyAssetExternalCmsSlotStorageQuery
    {
        return SpyAssetExternalCmsSlotStorageQuery::create();
    }

    /**
     * @return Spryker\Zed\AssetExternalStorage\Persistence\Mapper\AssetExternalStorageMapperInterface
     */
    public function createAssetExternalStorageMapper(): AssetExternalStorageMapperInterface
    {
        return new AssetExternalStorageMapper();
    }

    /**
     * @return \Orm\Zed\AssetExternal\Persistence\SpyAssetExternalQuery
     */
    public function getAssetExternalQuery(): SpyAssetExternalQuery
    {
        return $this->getProvidedDependency(AssetExternalStorageDependencyProvider::PROPEL_QUERY_ASSET_EXTERNAL);
    }
}
