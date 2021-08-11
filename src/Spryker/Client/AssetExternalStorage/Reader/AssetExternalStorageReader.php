<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AssetExternalStorage\Reader;

use Generated\Shared\Transfer\AssetExternalStorageCollectionCriteriaTransfer;
use Generated\Shared\Transfer\AssetExternalStorageCollectionTransfer;
use Generated\Shared\Transfer\AssetExternalStorageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\AssetExternalStorage\Dependency\Client\AssetExternalStorageToStorageClientInterface;
use Spryker\Client\AssetExternalStorage\Dependency\Service\AssetExternalStorageToSynchronizationServiceInterface;
use Spryker\Shared\AssetExternalStorage\AssetExternalStorageConfig;

class AssetExternalStorageReader implements AssetExternalStorageReaderInterface
{
    protected const KEY_PRODUCT_OFFER_REFERENCE = 'product_offer_reference';

    /**
     * @var \Spryker\Client\AssetExternalStorage\Dependency\Client\AssetExternalStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\AssetExternalStorage\Dependency\Service\AssetExternalStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @param \Spryker\Client\AssetExternalStorage\Dependency\Client\AssetExternalStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\AssetExternalStorage\Dependency\Service\AssetExternalStorageToSynchronizationServiceInterface $synchronizationService
     */
    public function __construct(
        AssetExternalStorageToStorageClientInterface $storageClient,
        AssetExternalStorageToSynchronizationServiceInterface $synchronizationService
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
    }

    /**
     * @param \Generated\Shared\Transfer\AssetExternalStorageCollectionCriteriaTransfer $assetExternalStorageCollectionCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\AssetExternalStorageCollectionTransfer
     */
    public function getAssetExternals(
        AssetExternalStorageCollectionCriteriaTransfer $assetExternalStorageCollectionCriteriaTransfer
    ): AssetExternalStorageCollectionTransfer {
        $assetExternalStorageKey = $this->generateKey(
            $assetExternalStorageCollectionCriteriaTransfer->getSlotKeyOrFail(),
            $assetExternalStorageCollectionCriteriaTransfer->getStoreNameOrFail()
        );
        $assetExternalStorageTransferData = $this->storageClient->get($assetExternalStorageKey);

        $assetExternalStorageCollectionTransfer = new AssetExternalStorageCollectionTransfer();

        foreach ($assetExternalStorageTransferData['assets'] as $assetextenal) {
            $assetExternalStorageCollectionTransfer->addAssetExternalStorage(
                (new AssetExternalStorageTransfer())->fromArray($assetextenal, true)
            );
        }

        return $assetExternalStorageCollectionTransfer;
    }

    /**
     * @param string $cmsSlotKey
     * @param string $storeName
     *
     * @return string
     */
    protected function generateKey(string $cmsSlotKey, string $storeName): string
    {
        $synchronizationDataTransfer = (new SynchronizationDataTransfer())
            ->setReference($cmsSlotKey)
            ->setStore($storeName);

        return $this->synchronizationService
            ->getStorageKeyBuilder(AssetExternalStorageConfig::ASSET_EXTERNAL_CMS_SLOT_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }
}
