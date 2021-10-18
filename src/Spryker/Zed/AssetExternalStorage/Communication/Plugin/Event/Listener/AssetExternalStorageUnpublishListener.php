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
class AssetExternalStorageUnpublishListener extends AbstractPlugin implements EventHandlerInterface
{
    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer $eventEntityTransfer
     * @param string $eventName
     *
     * @throws \Spryker\Zed\AssetExternalStorage\Communication\Exception\NoForeignKeyException
     *
     * @return void
     */
    public function handle(TransferInterface $eventEntityTransfer, $eventName)
    {
        $foreignKeys = $eventEntityTransfer->getForeignKeys();

        if (!isset($foreignKeys[AssetExternalStorageConfig::COL_CMS_SLOT_KEY])) {
            throw new NoForeignKeyException(AssetExternalStorageConfig::COL_CMS_SLOT_KEY);
        }

        $cmsSlotKey = $foreignKeys[AssetExternalStorageConfig::COL_CMS_SLOT_KEY];

        $this->getFacade()->unpublish($eventEntityTransfer->getId(), $cmsSlotKey);
    }
}
