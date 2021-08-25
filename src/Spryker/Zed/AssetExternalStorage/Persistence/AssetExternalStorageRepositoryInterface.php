<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternalStorage\Persistence;

use Orm\Zed\AssetExternal\Persistence\SpyAssetExternal;
use Propel\Runtime\Collection\ObjectCollection;

interface AssetExternalStorageRepositoryInterface
{
    /**
     * @return \Orm\Zed\AssetExternalStorage\Persistence\SpyAssetExternalCmsSlotStorage[]
     */
    public function findAllAssetExternalStorage(): array;

    /**
     * @param int[] $ids
     *
     * @return \Orm\Zed\AssetExternalStorage\Persistence\SpyAssetExternalCmsSlotStorage[]
     */
    public function findAllAssetExternalStorageByAssetExternalIds(array $ids): array;

    /**
     * @param int $idCmsSlot
     *
     * @return \Orm\Zed\AssetExternalStorage\Persistence\SpyAssetExternalCmsSlotStorage[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function findByFkCmsSlot(int $idCmsSlot): ObjectCollection;

    /**
     * @param int $idCmsSlot
     * @param string $storeName
     *
     * @return \Orm\Zed\AssetExternalStorage\Persistence\SpyAssetExternalCmsSlotStorage[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function findByFkCmsSlotAndStore(int $idCmsSlot, string $storeName): ObjectCollection;

    /**
     * @param int $idAssetExternal
     *
     * @return \Orm\Zed\AssetExternal\Persistence\SpyAssetExternal|null
     */
    public function findAssetExternalByIdAssetExternal(int $idAssetExternal): ?SpyAssetExternal;

    /**
     * @param int $idAssetExternal
     *
     * @return \Orm\Zed\AssetExternal\Persistence\SpyAssetExternal[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function findAssetExternalsByIdAssetExternal(int $idAssetExternal): ObjectCollection;
}
