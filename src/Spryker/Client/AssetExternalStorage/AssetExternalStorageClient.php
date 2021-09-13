<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AssetExternalStorage;

use Generated\Shared\Transfer\AssetExternalStorageCollectionTransfer;
use Generated\Shared\Transfer\AssetExternalStorageCriteriaTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\AssetExternalStorage\AssetExternalStorageFactory getFactory()
 */
class AssetExternalStorageClient extends AbstractClient implements AssetExternalStorageClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AssetExternalStorageCriteriaTransfer $assetExternalStorageCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\AssetExternalStorageCollectionTransfer
     */
    public function getAssetExternalCollectionForCmsSlot(
        AssetExternalStorageCriteriaTransfer $assetExternalStorageCriteriaTransfer
    ): AssetExternalStorageCollectionTransfer {
        return $this->getFactory()
            ->createAssetExternalStorageReader()
            ->getAssetExternalStorageCollection($assetExternalStorageCriteriaTransfer);
    }
}
