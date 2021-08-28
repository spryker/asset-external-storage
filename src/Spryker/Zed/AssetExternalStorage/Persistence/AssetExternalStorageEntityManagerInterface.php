<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternalStorage\Persistence;

use Orm\Zed\AssetExternal\Persistence\SpyAssetExternal;
use Propel\Runtime\Collection\ObjectCollection;

interface AssetExternalStorageEntityManagerInterface
{
    /**
     * @param \Orm\Zed\AssetExternal\Persistence\SpyAssetExternal $assetExternalEntity
     * @param string $storeName
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     *
     * @return void
     */
    public function createAssetExternalStorage(SpyAssetExternal $assetExternalEntity, string $storeName): void;

    /**
     * @param \Orm\Zed\AssetExternal\Persistence\SpyAssetExternal $assetExternalEntity
     * @param \Generated\Shared\Transfer\SpyAssetExternalCmsSlotStorageEntityTransfer[] $assetExternalCmsSlotStorageEntityTransfers
     *
     * @return void
     */
    public function updateAssetExternalStoragesData(SpyAssetExternal $assetExternalEntity, array $assetExternalCmsSlotStorageEntityTransfers): void;

    /**
     * @param \Orm\Zed\AssetExternalStorage\Persistence\SpyAssetExternalCmsSlotStorage[]|\Propel\Runtime\Collection\ObjectCollection $assetExternalCmsSlotStorageEntities
     * @param int $idAssetExternal
     *
     * @return void
     */
    public function removeAssetFromDatasByAssetExternalUuid(ObjectCollection $assetExternalCmsSlotStorageEntities, int $idAssetExternal): void;
}
