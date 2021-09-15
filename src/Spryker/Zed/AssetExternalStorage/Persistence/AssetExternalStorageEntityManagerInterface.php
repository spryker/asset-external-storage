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
     * @param string $cmsSlotKey
     * @param array $assetExternalCmsSlotStorageEntityTransfersByCmsSlotNotAsCurrentAndStores
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     *
     * @return void
     */
    public function createAssetExternalStorage(
        AssetExternalTransfer $assetExternalTransfer,
        string $storeName,
        string $cmsSlotKey,
        array $assetExternalCmsSlotStorageEntityTransfersByCmsSlotNotAsCurrentAndStores
    ): void;

    /**
     * @param \Generated\Shared\Transfer\AssetExternalTransfer $assetExternalTransfer
     * @param \Generated\Shared\Transfer\SpyAssetExternalCmsSlotStorageEntityTransfer[] $assetExternalCmsSlotStorageEntityTransfersByCmsSlotKeyAndStores
     * @param \Generated\Shared\Transfer\SpyAssetExternalCmsSlotStorageEntityTransfer[] $assetExternalCmsSlotStorageEntityTransfersByCmsSlotNotAsCurrentAndStores
     *
     * @return void
     */
    public function updateAssetExternalStoragesData(
        AssetExternalTransfer $assetExternalTransfer,
        array $assetExternalCmsSlotStorageEntityTransfersByCmsSlotKeyAndStores,
        array $assetExternalCmsSlotStorageEntityTransfersByCmsSlotNotAsCurrentAndStores
    ): void;

    /**
     * @param \Generated\Shared\Transfer\SpyAssetExternalCmsSlotStorageEntityTransfer[] $assetExternalCmsSlotsStorageEntityTransfers
     * @param int $idAssetExternal
     *
     * @return void
     */
    public function removeAssetFromDatasByIdAssetExternal(array $assetExternalCmsSlotsStorageEntityTransfers, int $idAssetExternal): void;
}
