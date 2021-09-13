<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternalStorage\Persistence;

use Orm\Zed\AssetExternalStorage\Persistence\SpyAssetExternalCmsSlotStorage;
use Spryker\Zed\AssetExternalStorage\Persistence\Exception\AssetExternalStorageEntityNotFound;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\AssetExternalStorage\Persistence\AssetExternalStoragePersistenceFactory getFactory()
 */
class AssetExternalStorageRepository extends AbstractRepository implements AssetExternalStorageRepositoryInterface
{
    /**
     * @return \Generated\Shared\Transfer\SpyAssetExternalCmsSlotStorageEntityTransfer[]
     */
    public function findAssetExternalStorages(): array
    {
        $assetExternalCmsSlotStorageEntities = $this->getFactory()
            ->createAssetExternalStorageQuery()
            ->find();

        return $this->getFactory()
            ->createAssetExternalStorageMapper()
            ->mapExternalCmsSlotStorageEntitiesToExternalCmsSlotStorageEntityTransfers($assetExternalCmsSlotStorageEntities);
    }

    /**
     * @param int[] $ids
     *
     * @return \Generated\Shared\Transfer\SpyAssetExternalCmsSlotStorageEntityTransfer[]
     */
    public function findAllAssetExternalStoragesByAssetExternalIds(array $ids): array
    {
        $query = $this->getFactory()->createAssetExternalStorageQuery();

        if ($ids !== []) {
            $query->filterByIdAssetExternalCmsSlotStorage_In($ids);
        }

        $assetExternalCmsSlotStorageEntities = $query->find();

        return $this
            ->getFactory()
            ->createAssetExternalStorageMapper()
            ->mapExternalCmsSlotStorageEntitiesToExternalCmsSlotStorageEntityTransfers($assetExternalCmsSlotStorageEntities);
    }

    /**
     * @param string $cmsSlotKey
     *
     * @return \Generated\Shared\Transfer\SpyAssetExternalCmsSlotStorageEntityTransfer[]
     */
    public function findAssetExternalStoragesByCmsSlotKey(string $cmsSlotKey): array
    {
        $assetExternalCmsSlotStorageEntities = $this->getFactory()
            ->createAssetExternalStorageQuery()
            ->filterByCmsSlotKey($idCmsSlot)
            ->find();

        return $this->getFactory()
            ->createAssetExternalStorageMapper()
            ->mapExternalCmsSlotStorageEntitiesToExternalCmsSlotStorageEntityTransfers($assetExternalCmsSlotStorageEntities);
    }

    /**
     * @param string $cmsSlotKey
     * @param string[] $storeNames
     *
     * @return \Generated\Shared\Transfer\SpyAssetExternalCmsSlotStorageEntityTransfer[]
     */
    public function findAssetExternalStoragesByCmsSlotKeyAndStores(string $cmsSlotKey, array $storeNames): array
    {
        $assetExternalCmsSlotStorageEntities = $this->getFactory()
            ->createAssetExternalStorageQuery()
            ->filterByCmsSlotKey($cmsSlotKey)
            ->filterByStore_In($storeNames)
            ->find();

        return $this->getFactory()
            ->createAssetExternalStorageMapper()
            ->mapExternalCmsSlotStorageEntitiesToExternalCmsSlotStorageEntityTransfers($assetExternalCmsSlotStorageEntities);
    }
}
