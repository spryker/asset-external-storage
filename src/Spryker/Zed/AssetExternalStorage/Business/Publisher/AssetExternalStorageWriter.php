<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternalStorage\Business\Publisher;

use Orm\Zed\AssetExternal\Persistence\SpyAssetExternal;
use Orm\Zed\AssetExternal\Persistence\SpyAssetExternalQuery;
use Orm\Zed\AssetExternal\Persistence\SpyAssetExternalStoreQuery;
use Orm\Zed\AssetExternalStorage\Persistence\Base\SpyAssetExternalCmsSlotStorage;
use Orm\Zed\AssetExternalStorage\Persistence\SpyAssetExternalCmsSlotStorageQuery;
use Propel\Runtime\Collection\ObjectCollection;
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
     * @param int $idAssetExternal
     *
     * @return void
     */
    public function publish(int $idAssetExternal): void
    {
        $assetExternalEntity = SpyAssetExternalQuery::create()->findOneByIdAssetExternal($idAssetExternal);
        if (!$assetExternalEntity) {
            return;
        }

        $storeTransfers = $this->storeFacade->getAllStores();

        foreach ($storeTransfers as $storeTransfer) {
            $assetExternalStoreEntities = SpyAssetExternalStoreQuery::create()
                ->filterByFkStore($storeTransfer->getIdStore())
                ->filterByFkAssetExternal($idAssetExternal)
                ->find();

            if (!$assetExternalStoreEntities->count()) {
                continue;
            }

            $assetExternalCmsSlotsStoragesByStoreAndCmsSlot = SpyAssetExternalCmsSlotStorageQuery::create()
                ->filterByFkCmsSlot($assetExternalEntity->getFkCmsSlot())
                ->filterByStore($storeTransfer->getName())
                ->find();

            foreach ($assetExternalCmsSlotsStoragesByStoreAndCmsSlot as $assetExternalCmsSlotStorage) {
                $this->updateData($assetExternalEntity, $assetExternalCmsSlotStorage);
            }

            if (!$assetExternalCmsSlotsStoragesByStoreAndCmsSlot->count()) {
                $this->assetExternalStorageEntityManager->createAssetExternalStorage(
                    $assetExternalEntity,
                    $storeTransfer->getName(),
                    $assetExternalEntity->getSpyCmsSlot()->getKey(),
                    $assetExternalEntity->getSpyCmsSlot()->getIdCmsSlot()
                );
            }
        }
    }

    /**
     * @param int $idAssetExternal
     *
     * @return void
     */
    public function unpublish(int $idAssetExternal): void
    {
        $storeTransfers = $this->storeFacade->getAllStores();

        foreach ($storeTransfers as $storeTransfer) {
            $assetExternalStoreEntities = SpyAssetExternalStoreQuery::create()
                ->filterByFkStore($storeTransfer->getIdStore())
                ->filterByFkAssetExternal($idAssetExternal)
                ->find();

            if ($assetExternalStoreEntities->count()) {
                continue;
            }

            $assetExternalCmsSlotsStoragesByStoreAndCmsSlot = SpyAssetExternalCmsSlotStorageQuery::create()
                ->filterByStore($storeTransfer->getName())
                ->find();

            $this->removeAssetExternalsFromStorageData($assetExternalCmsSlotsStoragesByStoreAndCmsSlot, $idAssetExternal);
        }
    }

    /**
     * @param \Orm\Zed\AssetExternal\Persistence\SpyAssetExternal $assetExternalEntity
     * @param \Orm\Zed\AssetExternalStorage\Persistence\SpyAssetExternalCmsSlotStorage $assetExternalCmsSlotStorage
     *
     * @return void
     */
    protected function updateData(SpyAssetExternal $assetExternalEntity, SpyAssetExternalCmsSlotStorage $assetExternalCmsSlotStorage): void
    {
        $isUpdated = $this->assetExternalStorageEntityManager->updateAssetExternalStorageData($assetExternalCmsSlotStorage, $assetExternalEntity);

        if (!$isUpdated) {
            $this->assetExternalStorageEntityManager->createAssetExternalStorageData($assetExternalCmsSlotStorage, $assetExternalEntity);
        }
    }

    /**
     * @param \Orm\Zed\AssetExternalStorage\Persistence\SpyAssetExternalCmsSlotStorage[]|\Propel\Runtime\Collection\ObjectCollection $assetExternalCmsSlotsStoragesByStoreAndCmsSlot
     * @param int $idAssetExternal
     *
     * @return void
     */
    protected function removeAssetExternalsFromStorageData(
        ObjectCollection $assetExternalCmsSlotsStoragesByStoreAndCmsSlot,
        int $idAssetExternal
    ): void {
        foreach ($assetExternalCmsSlotsStoragesByStoreAndCmsSlot as $assetExternalCmsSlotStorage) {
            $this->assetExternalStorageEntityManager->removeAssetFromDataByAssetExternalUuid(
                $assetExternalCmsSlotStorage,
                $idAssetExternal
            );
        }
    }
}
