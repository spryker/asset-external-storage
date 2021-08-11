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
     * - Get asset external collection transfer object from storage for the specified cms slot key and store name combination.
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
