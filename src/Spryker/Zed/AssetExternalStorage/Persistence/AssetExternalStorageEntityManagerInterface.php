<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternalStorage\Persistence;

use Orm\Zed\AssetExternal\Persistence\SpyAssetExternal;
use Orm\Zed\AssetExternalStorage\Persistence\SpyAssetExternalCmsSlotStorage;

interface AssetExternalStorageEntityManagerInterface
{
    /**
     * @param \Orm\Zed\AssetExternal\Persistence\SpyAssetExternal $assetExternalEntity
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     *
     * @return void
     */
    public function createAssetExternalStorage(SpyAssetExternal $assetExternalEntity): void;

    /**
     * @param \Orm\Zed\AssetExternal\Persistence\SpyAssetExternal $assetExternalEntity
     * @param \Orm\Zed\AssetExternalStorage\Persistence\SpyAssetExternalCmsSlotStorage $assetExternalCmsSlotStorage
     *
     * @return void
     */
    public function updateAssetExternalStorageData(SpyAssetExternal $assetExternalEntity, SpyAssetExternalCmsSlotStorage $assetExternalCmsSlotStorage): void;

    /**
     * @param \Orm\Zed\AssetExternalStorage\Persistence\SpyAssetExternalCmsSlotStorage $assetExternalCmsSlotStorageEntity
     * @param int $idAssetExternal
     *
     * @return void
     */
    public function removeAssetFromDataByAssetExternalUuid(SpyAssetExternalCmsSlotStorage $assetExternalCmsSlotStorageEntity, int $idAssetExternal): void;
}
