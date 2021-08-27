<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternalStorage\Persistence;

use Orm\Zed\AssetExternal\Persistence\SpyAssetExternal;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\AssetExternalStorage\Persistence\AssetExternalStoragePersistenceFactory getFactory()
 */
class AssetExternalStorageRepository extends AbstractRepository implements AssetExternalStorageRepositoryInterface
{
    /**
     * @return \Orm\Zed\AssetExternalStorage\Persistence\SpyAssetExternalCmsSlotStorage[]
     */
    public function findAllAssetExternalStorages(): array
    {
        return $this->getFactory()
            ->createAssetExternalStorageQuery()
            ->find()
            ->getArrayCopy();
    }

    /**
     * @param int[] $ids
     *
     * @return \Orm\Zed\AssetExternalStorage\Persistence\SpyAssetExternalCmsSlotStorage[]
     */
    public function findAllAssetExternalStoragesByAssetExternalIds(array $ids): array
    {
        $query = $this->getFactory()->createAssetExternalStorageQuery();

        if ($ids !== []) {
            $query->filterByIdAssetExternalCmsSlotStorage_In($ids);
        }

        return $query->find()->getArrayCopy();
    }

    /**
     * @param int $idCmsSlot
     *
     * @return \Orm\Zed\AssetExternalStorage\Persistence\SpyAssetExternalCmsSlotStorage[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function findAssetExternalStoragesByFkCmsSlot(int $idCmsSlot): ObjectCollection
    {
        return $this->getFactory()
            ->createAssetExternalStorageQuery()
            ->filterByFkCmsSlot($idCmsSlot)
            ->find();
    }

    /**
     * @param int $idCmsSlot
     * @param string $storeName
     *
     * @return \Orm\Zed\AssetExternalStorage\Persistence\SpyAssetExternalCmsSlotStorage[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function findAssetExternalStoragesByFkCmsSlotAndStore(int $idCmsSlot, string $storeName): ObjectCollection
    {
        return $this->getFactory()
            ->createAssetExternalStorageQuery()
            ->filterByFkCmsSlot($idCmsSlot)
            ->filterByStore($storeName)
            ->find();
    }

    /**
     * @param int $idAssetExternal
     *
     * @return \Orm\Zed\AssetExternal\Persistence\SpyAssetExternal|null
     */
    public function findAssetExternalByIdAssetExternal(int $idAssetExternal): ?SpyAssetExternal
    {
        return $this->getFactory()->getAssetExternalQuery()->findOneByIdAssetExternal($idAssetExternal);
    }

    /**
     * @param int $idAssetExternal
     *
     * @return \Orm\Zed\AssetExternal\Persistence\SpyAssetExternal[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function findAssetExternalsByIdAssetExternal(int $idAssetExternal): ObjectCollection
    {
        return $this->getFactory()->getAssetExternalQuery()->findByIdAssetExternal($idAssetExternal);
    }
}
