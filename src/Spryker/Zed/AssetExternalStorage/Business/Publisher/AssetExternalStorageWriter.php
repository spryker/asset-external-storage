<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternalStorage\Business\Publisher;

use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\AssetExternalStorage\Dependency\Facade\AssetExternalStorageToStoreFacadeInterface;
use Spryker\Zed\AssetExternalStorage\Persistence\AssetExternalStorageEntityManagerInterface;
use Spryker\Zed\AssetExternalStorage\Persistence\AssetExternalStorageRepositoryInterface;

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
     * @var \Spryker\Zed\AssetExternalStorage\Persistence\AssetExternalStorageRepositoryInterface
     */
    protected $assetExternalStorageRepository;

    /**
     * @param \Spryker\Zed\AssetExternalStorage\Dependency\Facade\AssetExternalStorageToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\AssetExternalStorage\Persistence\AssetExternalStorageEntityManagerInterface $assetExternalStorageEntityManager
     * @param \Spryker\Zed\AssetExternalStorage\Persistence\AssetExternalStorageRepositoryInterface $assetExternalStorageRepository
     */
    public function __construct(
        AssetExternalStorageToStoreFacadeInterface $storeFacade,
        AssetExternalStorageEntityManagerInterface $assetExternalStorageEntityManager,
        AssetExternalStorageRepositoryInterface $assetExternalStorageRepository
    ) {
        $this->storeFacade = $storeFacade;
        $this->assetExternalStorageEntityManager = $assetExternalStorageEntityManager;
        $this->assetExternalStorageRepository = $assetExternalStorageRepository;
    }

    /**
     * @param int $idAssetExternal
     *
     * @return void
     */
    public function publish(int $idAssetExternal): void
    {
        $assetExternalEntity = $this->assetExternalStorageRepository->findAssetExternalByIdAssetExternal($idAssetExternal);

        if (!$assetExternalEntity) {
            return;
        }

        $assetExternalCmsSlotsStoragesByCmsSlot = $this->assetExternalStorageRepository->findByFkCmsSlot($assetExternalEntity->getFkCmsSlot());

        if (!$assetExternalCmsSlotsStoragesByCmsSlot->count()) {
            $this->assetExternalStorageEntityManager->createAssetExternalStorage($assetExternalEntity);

            return;
        }

        foreach ($assetExternalCmsSlotsStoragesByCmsSlot as $assetExternalCmsSlotStorage) {
            $this->assetExternalStorageEntityManager->updateAssetExternalStorageData($assetExternalEntity, $assetExternalCmsSlotStorage);
        }
    }

    /**
     * @param int $idAssetExternal
     * @param int $idStore
     *
     * @return void
     */
    public function publishStoreRelation(int $idAssetExternal, int $idStore): void
    {
        $assetExternalEntity = $this->assetExternalStorageRepository->findAssetExternalByIdAssetExternal($idAssetExternal);

        if (!$assetExternalEntity) {
            return;
        }

        $storeTransfer = $this->storeFacade->getStoreById($idStore);

        $assetExternalCmsSlotsStoragesByStoreAndCmsSlot = $this->assetExternalStorageRepository->findByFkCmsSlotAndStore(
            $assetExternalEntity->getFkCmsSlot(),
            $storeTransfer->getName()
        );

        if (!$assetExternalCmsSlotsStoragesByStoreAndCmsSlot->count()) {
            $this->assetExternalStorageEntityManager->createAssetExternalStorage($assetExternalEntity);

            return;
        }

        foreach ($assetExternalCmsSlotsStoragesByStoreAndCmsSlot as $assetExternalCmsSlotStorage) {
            $this->assetExternalStorageEntityManager->updateAssetExternalStorageData($assetExternalEntity, $assetExternalCmsSlotStorage);
        }
    }

    /**
     * @param int $idAssetExternal
     * @param int $idCmsSlot
     *
     * @return void
     */
    public function unpublish(int $idAssetExternal, int $idCmsSlot): void
    {
        $assetExternalCmsSlotsStoragesByCmsSlot = $this->assetExternalStorageRepository->findByFkCmsSlot($idCmsSlot);

        $this->removeAssetExternalsFromStorageData($assetExternalCmsSlotsStoragesByCmsSlot, $idAssetExternal);
    }

    /**
     * @param int $idAssetExternal
     * @param int $idStore
     *
     * @return void
     */
    public function unpublishStoreRelation(int $idAssetExternal, int $idStore): void
    {
        $assetExternalEntities = $this->assetExternalStorageRepository->findAssetExternalsByIdAssetExternal($idAssetExternal);

        if (!$assetExternalEntities->count()) {
            return;
        }

        $storeTransfer = $this->storeFacade->getStoreById($idStore);

        $assetExternalCmsSlotsStoragesByStoreAndCmsSlot = $this->assetExternalStorageRepository->findByFkCmsSlotAndStore(
            $assetExternalEntities->getFirst()->getFkCmsSlot(),
            $storeTransfer->getName()
        );

        $this->removeAssetExternalsFromStorageData($assetExternalCmsSlotsStoragesByStoreAndCmsSlot, $idAssetExternal);
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
