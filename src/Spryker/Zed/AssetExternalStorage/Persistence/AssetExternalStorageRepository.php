<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternalStorage\Persistence;

use Orm\Zed\AssetExternal\Persistence\SpyAssetExternal;
use Orm\Zed\AssetExternal\Persistence\SpyAssetExternalQuery;
use Orm\Zed\AssetExternalStorage\Persistence\SpyAssetExternalCmsSlotStorageQuery;
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
    public function findAllAssetExternalStorage(): array
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
    public function findAllAssetExternalStorageByAssetExternalIds(array $ids): array
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
    public function findByFkCmsSlot(int $idCmsSlot): ObjectCollection
    {
        return SpyAssetExternalCmsSlotStorageQuery::create()
            ->filterByFkCmsSlot($idCmsSlot)
            ->find();
    }

    /**
     * @param int $idCmsSlot
     * @param string $storeName
     *
     * @return \Orm\Zed\AssetExternalStorage\Persistence\SpyAssetExternalCmsSlotStorage[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function findByFkCmsSlotAndStore(int $idCmsSlot, string $storeName): ObjectCollection
    {
        return SpyAssetExternalCmsSlotStorageQuery::create()
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
        return SpyAssetExternalQuery::create()->findOneByIdAssetExternal($idAssetExternal);
    }

    /**
     * @param int $idAssetExternal
     *
     * @return \Orm\Zed\AssetExternal\Persistence\SpyAssetExternal[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function findAssetExternalsByIdAssetExternal(int $idAssetExternal): ObjectCollection
    {
        return SpyAssetExternalQuery::create()->findByIdAssetExternal($idAssetExternal);
    }
}
