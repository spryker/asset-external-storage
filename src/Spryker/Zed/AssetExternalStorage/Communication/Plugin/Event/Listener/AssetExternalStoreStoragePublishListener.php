<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternalStorage\Communication\Plugin\Event\Listener;

use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\AssetExternalStorage\AssetExternalStorageConfig;
use Spryker\Zed\AssetExternalStorage\Communication\Exception\NoForeignKeyException;
use Spryker\Zed\Event\Dependency\Plugin\EventHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\AssetExternalStorage\Business\AssetExternalStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\AssetExternalStorage\AssetExternalStorageConfig getConfig()
 * @method \Spryker\Zed\AssetExternalStorage\Communication\AssetExternalStorageCommunicationFactory getFactory()
 */
class AssetExternalStoreStoragePublishListener extends AbstractPlugin implements EventHandlerInterface
{
    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer $eventEntityTransfer
     * @param string $eventName
     *
     * @throws \Spryker\Zed\AssetExternalStorage\Communication\Exception\NoForeignKeyException
     *
     * @return void
     */
    public function handle(TransferInterface $eventEntityTransfer, string $eventName): void
    {
        $foreignKeys = $eventEntityTransfer->getForeignKeys();

        if (!isset($foreignKeys[AssetExternalStorageConfig::COL_FK_ASSET_EXTERNAL])) {
            throw new NoForeignKeyException(AssetExternalStorageConfig::COL_FK_ASSET_EXTERNAL);
        }
        if (!isset($foreignKeys[AssetExternalStorageConfig::COL_FK_STORE])) {
            throw new NoForeignKeyException(AssetExternalStorageConfig::COL_FK_STORE);
        }

        $idAssetExternal = $foreignKeys[AssetExternalStorageConfig::COL_FK_ASSET_EXTERNAL];
        $idStore = $foreignKeys[AssetExternalStorageConfig::COL_FK_STORE];

        $this->getFacade()->publishStoreRelation($idAssetExternal, $idStore);
    }
}
