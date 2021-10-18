<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternalStorage\Business\Publisher;

use Spryker\Zed\AssetExternalStorage\Dependency\Facade\AssetExternalStorageToAssetExternalInterface;
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
     * @param \Spryker\Zed\AssetExternalStorage\Persistence\AssetExternalStorageEntityManagerInterface $assetExternalStorageEntityManager
     * @param \Spryker\Zed\AssetExternalStorage\Persistence\AssetExternalStorageRepositoryInterface $assetExternalStorageRepository
     */
    public function __construct(
        AssetExternalStorageToStoreFacadeInterface $storeFacade,
        AssetExternalStorageToAssetExternalInterface $assetExternalFacade,
        AssetExternalStorageEntityManagerInterface $assetExternalStorageEntityManager,
        AssetExternalStorageRepositoryInterface $assetExternalStorageRepository
    ) {
        $this->storeFacade = $storeFacade;
        $this->assetExternalFacade = $assetExternalFacade;
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

        $assetExternalCmsSlotStorageToUpdate = $this->assetExternalStorageRepository->findAssetExternalStoragesByCmsSlotKeyAndStores($assetExternalTransfer->getCmsSlotKey(), $assetExternalTransfer->getStores());
        $assetExternalCmsSlotStorageToDelete = $this->assetExternalStorageRepository->findAssetExternalStoragesWithCmsSlotKeyNotEqualAndByStores($assetExternalTransfer->getCmsSlotKey(), $assetExternalTransfer->getStores());

        if (count($assetExternalCmsSlotStorageToUpdate)) {
            $this->assetExternalStorageEntityManager->updateAssetExternalStoragesData(
                $assetExternalTransfer,
                $assetExternalCmsSlotStorageToUpdate,
                $assetExternalCmsSlotStorageToDelete
            );

            return;
        }

        foreach ($assetExternalTransfer->getStores() as $storeName) {
            $this->assetExternalStorageEntityManager->createAssetExternalStorage(
                $assetExternalTransfer,
                $storeName,
                $assetExternalCmsSlotStorageToDelete
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

        $assetExternalCmsSlotStorageEntityTransfers = $this->assetExternalStorageRepository->findAssetExternalStoragesByCmsSlotKeyAndStores(
            $assetExternalTransfer->getCmsSlotKey(),
            [$storeTransfer->getName()]
        );

        if (!count($assetExternalCmsSlotStorageEntityTransfers)) {
            $this->assetExternalStorageEntityManager->createAssetExternalStorage($assetExternalTransfer, $storeTransfer->getName(), []);

            return;
        }

        $this->assetExternalStorageEntityManager->updateAssetExternalStoragesData($assetExternalTransfer, $assetExternalCmsSlotStorageEntityTransfers, []);
    }

    /**
     * @param int $idAssetExternal
     * @param string $cmsSlotKey
     *
     * @return void
     */
    public function unpublish(int $idAssetExternal, string $cmsSlotKey): void
    {
        $assetExternalCmsSlotStorageEntityTransfersByCmsSlot = $this->assetExternalStorageRepository->findAssetExternalStoragesByCmsSlotKey($cmsSlotKey);

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

        $assetExternalCmsSlotStorageEntityTransfers = $this->assetExternalStorageRepository->findAssetExternalStoragesByCmsSlotKeyAndStores(
            $assetExternalTransfer->getCmsSlotKey(),
            [$storeTransfer->getName()]
        );

        $this->removeAssetExternalsFromStorageData($assetExternalCmsSlotStorageEntityTransfers, $idAssetExternal);
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
        $this->assetExternalStorageEntityManager->removeAssetFromDatasByIdAssetExternal(
            $assetExternalCmsSlotsStorageEntityTransfers,
            $idAssetExternal
        );
    }
}
