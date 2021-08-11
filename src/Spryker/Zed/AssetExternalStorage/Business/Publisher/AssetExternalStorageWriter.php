<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternalStorage\Business\Publisher;

use Orm\Zed\AssetExternal\Persistence\SpyAssetExternal;
use Orm\Zed\AssetExternal\Persistence\SpyAssetExternalQuery;
use Orm\Zed\AssetExternalStorage\Persistence\SpyAssetExternalCmsSlotStorageQuery;
use Orm\Zed\Store\Persistence\SpyStore;
use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Spryker\Zed\AssetExternalStorage\Persistence\AssetExternalStorageEntityManagerInterface;

class AssetExternalStorageWriter implements AssetExternalStorageWriterInterface
{
    /**
     * @var \Orm\Zed\Store\Persistence\SpyStoreQuery $storeQuery
     */
    protected $storeQuery;

    /**
     * @var \Spryker\Zed\AssetExternalStorage\Persistence\AssetExternalStorageEntityManagerInterface $assetExternalStorageEntityManager
     */
    protected $assetExternalStorageEntityManager;

    /**
     * @param \Orm\Zed\Store\Persistence\SpyStoreQuery $storeQuery
     * @param \Spryker\Zed\AssetExternalStorage\Persistence\AssetExternalStorageEntityManagerInterface $assetExternalStorageEntityManager
     */
    public function __construct(
        SpyStoreQuery $storeQuery,
        AssetExternalStorageEntityManagerInterface $assetExternalStorageEntityManager
    ) {
        $this->storeQuery = $storeQuery;
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

        $storeEntities = $this->storeQuery->find();

        foreach ($storeEntities as $storeEntity) {
            $this->saveAssetExternalStorageForStore($assetExternalEntity, $storeEntity);
        }
    }

    /**
     * @param \Orm\Zed\AssetExternal\Persistence\SpyAssetExternal $assetExternalEntity
     * @param \Orm\Zed\Store\Persistence\SpyStore $storeEntity
     *
     * @return void
     */
    protected function saveAssetExternalStorageForStore(SpyAssetExternal $assetExternalEntity, SpyStore $storeEntity): void
    {
        $assetExternalEntities = SpyAssetExternalQuery::create()
            ->filterByFkCmsSlot($assetExternalEntity->getFkCmsSlot())
            ->useSpyAssetExternalStoreQuery()
                ->filterByFkStore($storeEntity->getIdStore())
            ->endUse()
            ->find();

        if (!$assetExternalEntities->count()) {
            return;
        }

        $cmsSlotKey = $assetExternalEntity->getSpyCmsSlot()->getKey();
        $cmsSlotId = $assetExternalEntity->getSpyCmsSlot()->getIdCmsSlot();
        $storeName = $storeEntity->getName();

        $this->assetExternalStorageEntityManager->saveAssetExternalStorage(
            $assetExternalEntities,
            $storeName,
            $cmsSlotKey,
            $cmsSlotId
        );
    }
}
