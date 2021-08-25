<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternalStorage\Communication\Plugin\Event\Listener;

use Orm\Zed\PayoneConfig\Persistence\Map\SpyAssetExternalTableMap;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\AssetExternalStorage\Communication\AssetExternalStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\AssetExternalStorage\Business\AssetExternalStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\AssetExternalStorage\AssetExternalStorageConfig getConfig()
 */
class AssetExternalStorageUnpublishListener extends AbstractPlugin implements EventHandlerInterface
{
    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer $eventEntityTransfer
     * @param string $eventName
     *
     * @return void
     */
    public function handle(TransferInterface $eventEntityTransfer, $eventName)
    {
        $idCmsSlot = $eventEntityTransfer->getForeignKeys()[SpyAssetExternalTableMap::COL_FK_CMS_SLOT];

        $this->getFacade()->unpublish($eventEntityTransfer->getId(), $idCmsSlot);
    }
}
