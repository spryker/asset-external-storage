<?php

/**
* Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
* Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
*/

namespace Spryker\Zed\AssetExternalStorage\Persistence;

use Generated\Shared\Transfer\AssetExternalTransfer;
use Generated\Shared\Transfer\CmsSlotTransfer;
use Orm\Zed\AssetExternalStorage\Persistence\SpyAssetExternalCmsSlotStorage;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Shared\AssetExternalStorage\AssetExternalStorageConfig;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\AssetExternalStorage\Persistence\AssetExternalStoragePersistenceFactory getFactory()
 */
class AssetExternalStorageEntityManager extends AbstractEntityManager implements AssetExternalStorageEntityManagerInterface
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\AssetExternal\Persistence\SpyAssetExternal[] $assetExternalEntities
     * @param string $storeName
     * @param string $cmsSlotKey
     * @param int $cmsSlotId
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     *
     * @return void
     */
    public function saveAssetExternalStorage(ObjectCollection $assetExternalEntities, string $storeName, string $cmsSlotKey, int $cmsSlotId): void
    {
        $data['cmsSlotKey'] = $cmsSlotKey;

        foreach ($assetExternalEntities as $assetExternalEntity) {
            $data['assets'][] = [
                'assetUuid' => $assetExternalEntity->getAssetUuid(),
                'assetContent' => $assetExternalEntity->getAssetContent(),
            ];
        }
        $assetExternalCmsSlotStorage = new SpyAssetExternalCmsSlotStorage();

        $assetExternalCmsSlotStorage
            ->setStore($storeName)
            ->setFkCmsSlot($cmsSlotId)
            ->setCmsSlotKey($cmsSlotKey)
            ->setData($data);

        $assetExternalCmsSlotStorage->save();
    }
}
