<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternalStorage\Persistence;

use Generated\Shared\Transfer\AssetExternalTransfer;

interface AssetExternalStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\AssetExternalTransfer $assetExternalTransfer
     * @param string $storeName
     * @param array $assetExternalCmsSlotStorageToDelete
     *
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return void
     */
    public function createAssetExternalStorage(
        AssetExternalTransfer $assetExternalTransfer,
        string $storeName,
        array $assetExternalCmsSlotStorageToDelete
    ): void;

    /**
     * @param \Generated\Shared\Transfer\AssetExternalTransfer $assetExternalTransfer
     * @param array<\Generated\Shared\Transfer\SpyAssetExternalCmsSlotStorageEntityTransfer> $assetExternalCmsSlotStorageToUpdate
     * @param array<\Generated\Shared\Transfer\SpyAssetExternalCmsSlotStorageEntityTransfer> $assetExternalCmsSlotStorageToDelete
     *
     * @return void
     */
    public function updateAssetExternalStoragesData(
        AssetExternalTransfer $assetExternalTransfer,
        array $assetExternalCmsSlotStorageToUpdate,
        array $assetExternalCmsSlotStorageToDelete
    ): void;

    /**
     * @param array<\Generated\Shared\Transfer\SpyAssetExternalCmsSlotStorageEntityTransfer> $assetExternalCmsSlotsStorageEntityTransfers
     * @param int $idAssetExternal
     *
     * @return void
     */
    public function removeAssetFromDatasByIdAssetExternal(array $assetExternalCmsSlotsStorageEntityTransfers, int $idAssetExternal): void;
}
