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
    public function findAllAssetExternalStorages(): array
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
     * @param int $idAssetExternalCmsSlotStorage
     *
     * @throws \Spryker\Zed\AssetExternalStorage\Persistence\Exception\AssetExternalStorageEntityNotFound
     *
     * @return \Orm\Zed\AssetExternalStorage\Persistence\SpyAssetExternalCmsSlotStorage
     */
    public function findOneAssetExternalStorageEntityByAssetExternalId(int $idAssetExternalCmsSlotStorage): SpyAssetExternalCmsSlotStorage
    {
        $assetExternalCmsSlotStorageEntity = $this->getFactory()
            ->createAssetExternalStorageQuery()
            ->findOneByIdAssetExternalCmsSlotStorage($idAssetExternalCmsSlotStorage);

        if (!$assetExternalCmsSlotStorageEntity) {
            throw new AssetExternalStorageEntityNotFound($idAssetExternalCmsSlotStorage);
        }

        return $assetExternalCmsSlotStorageEntity;
    }

    /**
     * @param int $idCmsSlot
     *
     * @return \Generated\Shared\Transfer\SpyAssetExternalCmsSlotStorageEntityTransfer[]
     */
    public function findAssetExternalStoragesByFkCmsSlot(int $idCmsSlot): array
    {
        $assetExternalCmsSlotStorageEntities = $this->getFactory()
            ->createAssetExternalStorageQuery()
            ->filterByFkCmsSlot($idCmsSlot)
            ->find();

        return $this->getFactory()
            ->createAssetExternalStorageMapper()
            ->mapExternalCmsSlotStorageEntitiesToExternalCmsSlotStorageEntityTransfers($assetExternalCmsSlotStorageEntities);
    }

    /**
     * @param int $idCmsSlot
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\SpyAssetExternalCmsSlotStorageEntityTransfer[]
     */
    public function findAssetExternalStoragesByFkCmsSlotAndStore(int $idCmsSlot, string $storeName): array
    {
        $assetExternalCmsSlotStorageEntities = $this->getFactory()
            ->createAssetExternalStorageQuery()
            ->filterByFkCmsSlot($idCmsSlot)
            ->filterByStore($storeName)
            ->find();

        return $this->getFactory()
            ->createAssetExternalStorageMapper()
            ->mapExternalCmsSlotStorageEntitiesToExternalCmsSlotStorageEntityTransfers($assetExternalCmsSlotStorageEntities);
    }
}
