<?php

/**
* Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
* Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
*/

namespace Spryker\Zed\AssetExternalStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\AssetExternalStorage\Business\AssetExternalStorageBusinessFactory getFactory()
 */
class AssetExternalStorageFacade extends AbstractFacade implements AssetExternalStorageFacadeInterface
{
    /**
     * @param int $assetExternalId
     *
     * @return void
     */
    public function publish(int $assetExternalId): void
    {
        $this->getFactory()
            ->createAssetExternalStorageWriter()
            ->updateAssetExternalSorageData($assetExternalId);
    }

    public function unpublish(int $assetExternalId): void
    {
        $this->getFactory()
            ->createAssetExternalStorageWriter()
            ->updateAssetExternalSorageData($assetExternalId);
    }
}
