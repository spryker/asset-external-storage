<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternalStorage\Business\Publisher;

use Spryker\Zed\AssetExternalStorage\Dependency\Facade\AssetExternalStorageToAssetExternalInterface;
use Spryker\Zed\AssetExternalStorage\Dependency\Facade\AssetExternalStorageToCmsSlotFacadeInterface;
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
     * @var \Spryker\Zed\AssetExternalStorage\Dependency\Facade\AssetExternalStorageToAssetExternalInterface
     */
    protected $assetExternalFacade;

    /**
     * @var \Spryker\Zed\AssetExternalStorage\Dependency\Facade\AssetExternalStorageToCmsSlotFacadeInterface
     */
    protected $cmsSlotFacade;

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
     * @param \Spryker\Zed\AssetExternalStorage\Dependency\Facade\AssetExternalStorageToAssetExternalInterface $assetExternalFacade
     * @param \Spryker\Zed\AssetExternalStorage\Dependency\Facade\AssetExternalStorageToCmsSlotFacadeInterface $cmsSlotFacade
     * @param \Spryker\Zed\AssetExternalStorage\Persistence\AssetExternalStorageEntityManagerInterface $assetExternalStorageEntityManager
     * @param \Spryker\Zed\AssetExternalStorage\Persistence\AssetExternalStorageRepositoryInterface $assetExternalStorageRepository
     */
    public function __construct(
        AssetExternalStorageToStoreFacadeInterface $storeFacade,
        AssetExternalStorageToAssetExternalInterface $assetExternalFacade,
        AssetExternalStorageToCmsSlotFacadeInterface $cmsSlotFacade,
        AssetExternalStorageEntityManagerInterface $assetExternalStorageEntityManager,
        AssetExternalStorageRepositoryInterface $assetExternalStorageRepository
    ) {
        $this->storeFacade = $storeFacade;
        $this->assetExternalFacade = $assetExternalFacade;
        $this->cmsSlotFacade = $cmsSlotFacade;
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
        $assetExternalTransfer = $this->assetExternalFacade->findAssetById($idAssetExternal);

        if (!$assetExternalTransfer) {
            return;
        }

        $assetExternalCmsSlotStorageEntityTransfersByCmsSlotAndStores = $this->assetExternalStorageRepository->findAssetExternalStoragesByFkCmsSlotAndStores($assetExternalTransfer->getIdCmsSlot(), $assetExternalTransfer->getStores());

        if (count($assetExternalCmsSlotStorageEntityTransfersByCmsSlotAndStores)) {
            $this->assetExternalStorageEntityManager->updateAssetExternalStoragesData(
                $assetExternalTransfer,
                $assetExternalCmsSlotStorageEntityTransfersByCmsSlotAndStores
            );

            return;
        }

        $cmsSlotTransfer = $this->cmsSlotFacade->getCmsSlotById($assetExternalTransfer->getIdCmsSlot());
        $cmsSlotKey = $cmsSlotTransfer->getKey();

        foreach ($assetExternalTransfer->getStores() as $storeName) {
            $this->assetExternalStorageEntityManager->createAssetExternalStorage(
                $assetExternalTransfer,
                $storeName,
                $cmsSlotKey
            );
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
        $assetExternalTransfer = $this->assetExternalFacade->findAssetById($idAssetExternal);

        if (!$assetExternalTransfer) {
            return;
        }

        $storeTransfer = $this->storeFacade->getStoreById($idStore);

        $assetExternalCmsSlotStorageEntityTransfersByCmsSlotAndStoreName = $this->assetExternalStorageRepository->findAssetExternalStoragesByFkCmsSlotAndStores(
            $assetExternalTransfer->getIdCmsSlot(),
            [$storeTransfer->getName()]
        );

        $cmsSlotTransfer = $this->cmsSlotFacade->getCmsSlotById($assetExternalTransfer->getIdCmsSlot());
        $cmsSlotKey = $cmsSlotTransfer->getKey();

        if (!count($assetExternalCmsSlotStorageEntityTransfersByCmsSlotAndStoreName)) {
            $this->assetExternalStorageEntityManager->createAssetExternalStorage($assetExternalTransfer, $storeTransfer->getName(), $cmsSlotKey);

            return;
        }

        $this->assetExternalStorageEntityManager->updateAssetExternalStoragesData($assetExternalTransfer, $assetExternalCmsSlotStorageEntityTransfersByCmsSlotAndStoreName);
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
        $assetExternalTransfer = $this->assetExternalFacade->findAssetById($idAssetExternal);

        if (!$assetExternalTransfer) {
            return;
        }

        $storeTransfer = $this->storeFacade->getStoreById($idStore);

        $assetExternalCmsSlotStorageEntityTransfersByCmsSlotAndStoreName = $this->assetExternalStorageRepository->findAssetExternalStoragesByFkCmsSlotAndStores(
            $assetExternalTransfer->getIdCmsSlot(),
            [$storeTransfer->getName()]
        );

        $this->removeAssetExternalsFromStorageData($assetExternalCmsSlotStorageEntityTransfersByCmsSlotAndStoreName, $idAssetExternal);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyAssetExternalCmsSlotStorageEntityTransfer[] $assetExternalCmsSlotsStorageEntityTransfers
     * @param int $idAssetExternal
     *
     * @return void
     */
    protected function removeAssetExternalsFromStorageData(
        array $assetExternalCmsSlotsStorageEntityTransfers,
        int $idAssetExternal
    ): void {
        $this->assetExternalStorageEntityManager->removeAssetFromDatasByAssetExternalUuid(
            $assetExternalCmsSlotsStorageEntityTransfers,
            $idAssetExternal
        );
    }
}
