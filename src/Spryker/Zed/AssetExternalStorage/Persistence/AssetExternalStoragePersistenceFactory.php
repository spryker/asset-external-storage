<?php

/**
* Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
* Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
*/

namespace Spryker\Zed\AssetExternalStorage\Persistence;

use Orm\Zed\AssetExternal\Persistence\SpyAssetExternalQuery;
use Orm\Zed\AssetExternalStorage\Persistence\SpyAssetExternalCmsSlotStorageQuery;
use Orm\Zed\Content\Persistence\SpyContentQuery;
use Spryker\Zed\AssetExternalStorage\AssetExternalStorageDependencyProvider;
use Spryker\Zed\ContentStorage\ContentStorageDependencyProvider;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\AssetExternalStorage\AssetExternalStorageConfig getConfig()
 */
class AssetExternalStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\AssetExternalStorage\Persistence\SpyAssetExternalCmsSlotStorageQuery
     */
    public function getAssetExternalStorageQuery(): SpyAssetExternalCmsSlotStorageQuery
    {
        return $this->getProvidedDependency(AssetExternalStorageDependencyProvider::PROPEL_QUERY_ASSET_EXTERNAL_STORAGE);
    }
}
