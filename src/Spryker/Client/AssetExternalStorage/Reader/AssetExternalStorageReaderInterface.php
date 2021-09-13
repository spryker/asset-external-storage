<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AssetExternalStorage\Reader;

use Generated\Shared\Transfer\AssetExternalStorageCriteriaTransfer;
use Generated\Shared\Transfer\AssetExternalStorageCollectionTransfer;

interface AssetExternalStorageReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\AssetExternalStorageCriteriaTransfer $assetExternalStorageCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\AssetExternalStorageCollectionTransfer
     */
    public function getAssetExternalStorageCollection(
        AssetExternalStorageCriteriaTransfer $assetExternalStorageCriteriaTransfer
    ): AssetExternalStorageCollectionTransfer;
}
