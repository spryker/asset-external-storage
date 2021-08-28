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

        $assetExternalCmsSlotStorageEntityTransfersByCmsSlot = $this->assetExternalStorageRepository->findAssetExternalStoragesByFkCmsSlot($assetExternalEntity->getFkCmsSlot());

        if (!count($assetExternalCmsSlotStorageEntityTransfersByCmsSlot)) {
            $this->assetExternalStorageEntityManager->updateAssetExternalStoragesData(
                $assetExternalEntity,
                $assetExternalCmsSlotStorageEntityTransfersByCmsSlot
            );

            return;
        }

        foreach ($assetExternalEntity->getSpyAssetExternalStores() as $storeRelationEntity) {
            $this->assetExternalStorageEntityManager->createAssetExternalStorage(
                $assetExternalEntity,
                $storeRelationEntity->getSpyStore()->getName());
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

        $assetExternalCmsSlotStorageEntityTransfersByCmsSlotAndStoreName = $this->assetExternalStorageRepository->findAssetExternalStoragesByFkCmsSlotAndStore(
            $assetExternalEntity->getFkCmsSlot(),
            $storeTransfer->getName()
        );

        if (!count($assetExternalCmsSlotStorageEntityTransfersByCmsSlotAndStoreName)) {
            $this->assetExternalStorageEntityManager->createAssetExternalStorage($assetExternalEntity, $storeTransfer->getName());

            return;
        }

        $this->assetExternalStorageEntityManager->updateAssetExternalStoragesData($assetExternalEntity, $assetExternalCmsSlotStorageEntityTransfersByCmsSlotAndStoreName);
    }

    /**
     * @param int $idAssetExternal
     * @param int $idCmsSlot
     *
     * @return void
     */
    public function unpublish(int $idAssetExternal, int $idCmsSlot): void
    {
        $assetExternalCmsSlotStorageEntityTransfersByCmsSlot = $this->assetExternalStorageRepository->findAssetExternalStoragesByFkCmsSlot($idCmsSlot);

        $this->removeAssetExternalsFromStorageData($assetExternalCmsSlotStorageEntityTransfersByCmsSlot, $idAssetExternal);
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

        $assetExternalCmsSlotStorageEntityTransfersByCmsSlotAndStoreName = $this->assetExternalStorageRepository->findAssetExternalStoragesByFkCmsSlotAndStore(
            $assetExternalEntities->getFirst()->getFkCmsSlot(),
            $storeTransfer->getName()
        );

        $this->removeAssetExternalsFromStorageData($assetExternalCmsSlotStorageEntityTransfersByCmsSlotAndStoreName, $idAssetExternal);
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
        $this->assetExternalStorageEntityManager->removeAssetFromDatasByAssetExternalUuid(
            $assetExternalCmsSlotsStoragesByStoreAndCmsSlot,
            $idAssetExternal
        );
    }
}
