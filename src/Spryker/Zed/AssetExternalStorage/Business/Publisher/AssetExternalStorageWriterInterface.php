<?php

namespace Spryker\Zed\AssetExternalStorage\Business\Publisher;

use Generated\Shared\Transfer\AssetExternalStorageTransfer;
use Generated\Shared\Transfer\AssetExternalTransfer;
use Generated\Shared\Transfer\CmsSlotTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Orm\Zed\AssetExternal\Persistence\SpyAssetExternal;
use Orm\Zed\AssetExternal\Persistence\SpyAssetExternalQuery;
use Orm\Zed\AssetExternalStorage\Persistence\SpyAssetExternalCmsSlotStorageQuery;
use Orm\Zed\Store\Persistence\SpyStore;
use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Spryker\Shared\AssetExternalStorage\AssetExternalStorageConfig;
use Spryker\Zed\AssetExternalStorage\Dependency\Facade\AssetExternalStorageToAssetExternalInterface;
use Spryker\Zed\AssetExternalStorage\Persistence\AssetExternalStorageEntityManagerInterface;

interface AssetExternalStorageWriterInterface
{
    /**
     * @param int $assetExternalId
     *
     * @return void
     */
    public function updateAssetExternalSorageData(int $assetExternalId): void;
}
