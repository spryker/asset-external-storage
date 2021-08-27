<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AssetExternalStorage\Mapper;

use Generated\Shared\Transfer\AssetExternalStorageCollectionTransfer;
use Generated\Shared\Transfer\AssetExternalStorageTransfer;

class AssetExternalStorageMapper implements AssetExternalStorageMapperInterface
{
    /**
     * @param array $assetExternalStorageTransferData
     *
     * @return \Generated\Shared\Transfer\AssetExternalStorageCollectionTransfer
     */
    public function mapAssetExternalStorageDataToAssetExternalStorageTransfer(
        array $assetExternalStorageTransferData
    ): AssetExternalStorageCollectionTransfer {
        $assetExternalStorageCollectionTransfer = new AssetExternalStorageCollectionTransfer();

        foreach ($assetExternalStorageTransferData as $assetExtenal) {
            $assetExternalStorageCollectionTransfer->addAssetExternalStorage(
                (new AssetExternalStorageTransfer())->fromArray($assetExtenal, true)
            );
        }

        return $assetExternalStorageCollectionTransfer;
    }
}
