<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternalStorage\Communication\Plugin\Event\Listener;

use Orm\Zed\AssetExternal\Persistence\Map\SpyAssetExternalStoreTableMap;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

/**
 * @method \Spryker\Zed\AssetExternalStorage\Communication\AssetExternalStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\AssetExternalStorage\Business\AssetExternalStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\AssetExternalStorage\AssetExternalStorageConfig getConfig()
 */
class AssetExternalStoreStorageUnpublishListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventEntityTransfers, $eventName)
    {
        $this->preventTransaction();
        $assetExternalIds = $this->getFactory()
            ->getEventBehaviorFacade()
            ->getEventTransferForeignKeys($eventEntityTransfers, SpyAssetExternalStoreTableMap::COL_FK_ASSET_EXTERNAL);

        foreach ($assetExternalIds as $assetExternalId) {
            $this->getFacade()->unpublish($assetExternalId);
        }
    }
}
