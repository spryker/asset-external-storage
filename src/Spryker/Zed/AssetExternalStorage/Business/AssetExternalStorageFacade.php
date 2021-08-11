<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternalStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\AssetExternalStorage\Business\AssetExternalStorageBusinessFactory getFactory()
 * @method \Spryker\Zed\AssetExternalStorage\Persistence\AssetExternalStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\AssetExternalStorage\Persistence\AssetExternalStorageRepositoryInterface getRepository()
 */
class AssetExternalStorageFacade extends AbstractFacade implements AssetExternalStorageFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
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

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $assetExternalId
     *
     * @return void
     */
    public function unpublish(int $assetExternalId): void
    {
        $this->getFactory()
            ->createAssetExternalStorageWriter()
            ->updateAssetExternalSorageData($assetExternalId);
    }
}
