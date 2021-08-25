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
     * @param int $idAssetExternal
     *
     * @return void
     */
    public function publish(int $idAssetExternal): void
    {
        $this->getFactory()
            ->createAssetExternalStorageWriter()
            ->publish($idAssetExternal);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idAssetExternal
     * @param int $idStore
     *
     * @return void
     */
    public function publishStoreRelation(int $idAssetExternal, int $idStore): void
    {
        $this->getFactory()
            ->createAssetExternalStorageWriter()
            ->publishStoreRelation($idAssetExternal, $idStore);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idAssetExternal
     * @param int $idCmsSlot
     *
     * @return void
     */
    public function unpublish(int $idAssetExternal, int $idCmsSlot): void
    {
        $this->getFactory()
            ->createAssetExternalStorageWriter()
            ->unpublish($idAssetExternal, $idCmsSlot);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idAssetExternal
     * @param int $idStore
     *
     * @return void
     */
    public function unpublishStoreRelation(int $idAssetExternal, int $idStore): void
    {
        $this->getFactory()
            ->createAssetExternalStorageWriter()
            ->unpublishStoreRelation($idAssetExternal, $idStore);
    }
}
