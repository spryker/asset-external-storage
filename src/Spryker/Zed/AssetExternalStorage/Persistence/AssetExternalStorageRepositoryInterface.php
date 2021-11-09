<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternalStorage\Persistence;

interface AssetExternalStorageRepositoryInterface
{
    /**
     * @return array<\Generated\Shared\Transfer\SpyAssetExternalCmsSlotStorageEntityTransfer>
     */
    public function findAssetExternalStorages(): array;

    /**
     * @param array<int> $ids
     *
     * @return array<\Generated\Shared\Transfer\SpyAssetExternalCmsSlotStorageEntityTransfer>
     */
    public function findAssetExternalStoragesByAssetExternalIds(array $ids): array;

    /**
     * @param string $cmsSlotKey
     *
     * @return array<\Generated\Shared\Transfer\SpyAssetExternalCmsSlotStorageEntityTransfer>
     */
    public function findAssetExternalStoragesByCmsSlotKey(string $cmsSlotKey): array;

    /**
     * @param string $cmsSlotKey
     * @param array<string> $storeNames
     *
     * @return array<\Generated\Shared\Transfer\SpyAssetExternalCmsSlotStorageEntityTransfer>
     */
    public function findAssetExternalStoragesWithCmsSlotKeyNotEqualAndByStores(string $cmsSlotKey, array $storeNames): array;

    /**
     * @param string $cmsSlotKey
     * @param array<string> $storeNames
     *
     * @return array<\Generated\Shared\Transfer\SpyAssetExternalCmsSlotStorageEntityTransfer>
     */
    public function findAssetExternalStoragesByCmsSlotKeyAndStores(string $cmsSlotKey, array $storeNames): array;
}
