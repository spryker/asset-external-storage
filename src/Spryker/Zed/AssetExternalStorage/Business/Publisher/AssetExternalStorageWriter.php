<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternalStorage\Business\Publisher;

use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\AssetExternal\Persistence\SpyAssetExternal;
use Orm\Zed\AssetExternal\Persistence\SpyAssetExternalQuery;
use Orm\Zed\AssetExternalStorage\Persistence\SpyAssetExternalCmsSlotStorageQuery;
use Spryker\Zed\AssetExternalStorage\Dependency\Facade\AssetExternalStorageToStoreFacadeInterface;
use Spryker\Zed\AssetExternalStorage\Persistence\AssetExternalStorageEntityManagerInterface;

class AssetExternalStorageWriter implements AssetExternalStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\AssetExternalStorage\Dependency\Facade\AssetExternalStorageToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\AssetExternalStorage\Persistence\AssetExternalStorageEntityManagerInterface
     */
    protected $assetExternalStorageEntityManager;

    /**
     * @param \Spryker\Zed\AssetExternalStorage\Dependency\Facade\AssetExternalStorageToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\AssetExternalStorage\Persistence\AssetExternalStorageEntityManagerInterface $assetExternalStorageEntityManager
     */
    public function __construct(
        AssetExternalStorageToStoreFacadeInterface $storeFacade,
        AssetExternalStorageEntityManagerInterface $assetExternalStorageEntityManager
    ) {
        $this->storeFacade = $storeFacade;
        $this->assetExternalStorageEntityManager = $assetExternalStorageEntityManager;
    }

    /**
     * @param int $assetExternalId
     *
     * @return void
     */
    public function updateAssetExternalSorageData(int $assetExternalId): void
    {
        $assetExternalEntity = SpyAssetExternalQuery::create()->findOneByIdAssetExternal($assetExternalId);
        if (!$assetExternalEntity) {
            return;
        }

        SpyAssetExternalCmsSlotStorageQuery::create()
            ->filterByFkCmsSlot($assetExternalEntity->getFkCmsSlot())
            ->deleteAll();

        $storeTransfers = $this->storeFacade->getAllStores();

        foreach ($storeTransfers as $storeTransfer) {
            $this->saveAssetExternalStorageForStore($assetExternalEntity, $storeTransfer);
        }
    }

    /**
     * @param \Orm\Zed\AssetExternal\Persistence\SpyAssetExternal $assetExternalEntity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return void
     */
    protected function saveAssetExternalStorageForStore(SpyAssetExternal $assetExternalEntity, StoreTransfer $storeTransfer): void
    {
        $assetExternalEntities = SpyAssetExternalQuery::create()
            ->filterByFkCmsSlot($assetExternalEntity->getFkCmsSlot())
            ->useSpyAssetExternalStoreQuery()
                ->filterByFkStore($storeTransfer->getIdStore())
            ->endUse()
            ->find();

        if (!$assetExternalEntities->count()) {
            return;
        }

        $cmsSlotKey = $assetExternalEntity->getSpyCmsSlot()->getKey();
        $cmsSlotId = $assetExternalEntity->getSpyCmsSlot()->getIdCmsSlot();
        $storeName = $storeTransfer->getName();

        $this->assetExternalStorageEntityManager->saveAssetExternalStorage(
            $assetExternalEntities,
            $storeName,
            $cmsSlotKey,
            $cmsSlotId
        );
    }
}
