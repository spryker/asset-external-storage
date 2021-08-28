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
     * @return \Generated\Shared\Transfer\AssetExternalStorageTransfer[]
     */
    public function findAllAssetExternalStorages(): array;

    /**
     * @param int[] $ids
     *
     * @return \Generated\Shared\Transfer\AssetExternalStorageTransfer[]
     */
    public function findAllAssetExternalStoragesByAssetExternalIds(array $ids): array;

    /**
     * @param int $idCmsSlot
     *
     * @return \Generated\Shared\Transfer\SpyAssetExternalCmsSlotStorageEntityTransfer[]
     */
    public function findAssetExternalStoragesByFkCmsSlot(int $idCmsSlot): array;

    /**
     * @param int $idCmsSlot
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\SpyAssetExternalCmsSlotStorageEntityTransfer[]
     */
    public function findAssetExternalStoragesByFkCmsSlotAndStore(int $idCmsSlot, string $storeName): array;

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
