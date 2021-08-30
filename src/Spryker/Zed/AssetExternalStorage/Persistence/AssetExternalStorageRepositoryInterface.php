<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternalStorage\Persistence;

use Orm\Zed\AssetExternalStorage\Persistence\SpyAssetExternalCmsSlotStorage;

interface AssetExternalStorageRepositoryInterface
{
    /**
     * @return \Generated\Shared\Transfer\SpyAssetExternalCmsSlotStorageEntityTransfer[]
     */
    public function findAllAssetExternalStorages(): array;

    /**
     * @param int[] $ids
     *
     * @return \Generated\Shared\Transfer\SpyAssetExternalCmsSlotStorageEntityTransfer[]
     */
    public function findAllAssetExternalStoragesByAssetExternalIds(array $ids): array;

    /**
     * @param int $idAssetExternalCmsSlotStorage
     *
     * @return \Orm\Zed\AssetExternalStorage\Persistence\SpyAssetExternalCmsSlotStorage
     */
    public function findOneAssetExternalStorageEntityByAssetExternalId(int $idAssetExternalCmsSlotStorage): SpyAssetExternalCmsSlotStorage;

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
}
