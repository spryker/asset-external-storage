<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AssetExternalStorage\Reader;

use Generated\Shared\Transfer\AssetExternalStorageCollectionTransfer;
use Generated\Shared\Transfer\AssetExternalStorageCriteriaTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\AssetExternalStorage\Dependency\Client\AssetExternalStorageToStorageClientInterface;
use Spryker\Client\AssetExternalStorage\Dependency\Service\AssetExternalStorageToSynchronizationServiceInterface;
use Spryker\Client\AssetExternalStorage\Mapper\AssetExternalStorageMapperInterface;
use Spryker\Shared\AssetExternalStorage\AssetExternalStorageConfig;

class AssetExternalStorageReader implements AssetExternalStorageReaderInterface
{
    /**
     * @var string
     */
    protected const ASSETS_STORAGE_KEY = 'assets';

    /**
     * @var \Spryker\Client\AssetExternalStorage\Dependency\Client\AssetExternalStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\AssetExternalStorage\Dependency\Service\AssetExternalStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var \Spryker\Client\AssetExternalStorage\Mapper\AssetExternalStorageMapperInterface
     */
    protected $assetExternalStorageMapper;

    /**
     * @param \Spryker\Client\AssetExternalStorage\Dependency\Client\AssetExternalStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\AssetExternalStorage\Dependency\Service\AssetExternalStorageToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\AssetExternalStorage\Mapper\AssetExternalStorageMapperInterface $assetExternalStorageMapper
     */
    public function __construct(
        AssetExternalStorageToStorageClientInterface $storageClient,
        AssetExternalStorageToSynchronizationServiceInterface $synchronizationService,
        AssetExternalStorageMapperInterface $assetExternalStorageMapper
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
        $this->assetExternalStorageMapper = $assetExternalStorageMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\AssetExternalStorageCriteriaTransfer $assetExternalStorageCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\AssetExternalStorageCollectionTransfer
     */
    public function getAssetExternalStorageCollection(
        AssetExternalStorageCriteriaTransfer $assetExternalStorageCriteriaTransfer
    ): AssetExternalStorageCollectionTransfer {
        $assetExternalStorageKey = $this->generateKey(
            $assetExternalStorageCriteriaTransfer->getSlotKeyOrFail(),
            $assetExternalStorageCriteriaTransfer->getStoreNameOrFail()
        );
        $assetExternalStorageTransferData = $this->storageClient->get($assetExternalStorageKey);

        if (!$assetExternalStorageTransferData || empty([$assetExternalStorageTransferData[static::ASSETS_STORAGE_KEY]])) {
            return new AssetExternalStorageCollectionTransfer();
        }

        return $this->assetExternalStorageMapper->mapAssetExternalStorageDataToAssetExternalStorageTransfer($assetExternalStorageTransferData[static::ASSETS_STORAGE_KEY]);
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
