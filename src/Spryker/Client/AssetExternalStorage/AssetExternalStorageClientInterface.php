<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AssetExternalStorage;

use Generated\Shared\Transfer\AssetExternalStorageCollectionCriteriaTransfer;
use Generated\Shared\Transfer\AssetExternalStorageCollectionTransfer;

interface AssetExternalStorageClientInterface
{
    /**
     * Specification:
     * - Gets asset external collection transfer object from storage for the specified cms slot key and store name combination.
     * - Requires AssetExternalStorageCollectionCriteriaTransfer.slotKey transfer field to be set.
     * - Requires AssetExternalStorageCollectionCriteriaTransfer.storeName transfer field to be set.
     * - Gets data from storage by key equals asset_external:{storeName}:{slotKey}.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AssetExternalStorageCollectionCriteriaTransfer $assetExternalStorageCollectionCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\AssetExternalStorageCollectionTransfer
     */
    public function getAssetExternalCollectionForCmsSlot(
        AssetExternalStorageCollectionCriteriaTransfer $assetExternalStorageCollectionCriteriaTransfer
    ): AssetExternalStorageCollectionTransfer;
}
