<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AssetExternalStorage;

use Generated\Shared\Transfer\AssetExternalStorageCollectionCriteriaTransfer;
use Generated\Shared\Transfer\AssetExternalStorageCollectionTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * {@inheritDoc}
 *
 * @api
 *
 * @method \Spryker\Client\AssetExternalStorage\AssetExternalStorageFactory getFactory()
 */
class AssetExternalStorageClient extends AbstractClient implements AssetExternalStorageClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AssetExternalStorageCollectionCriteriaTransfer $assetExternalStorageCollectionCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\AssetExternalStorageCollectionTransfer
     */
    public function getAssetExternalCollectionForCmsSlot(
        AssetExternalStorageCollectionCriteriaTransfer $assetExternalStorageCollectionCriteriaTransfer
    ): AssetExternalStorageCollectionTransfer {
        return $this->getFactory()
            ->createAssetExternalStorageReader()
            ->getAssetExternals($assetExternalStorageCollectionCriteriaTransfer);
    }
}
