<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternalStorage\Persistence\Mapper;

use Generated\Shared\Transfer\AssetExternalStorageTransfer;
use Orm\Zed\AssetExternal\Persistence\SpyAssetExternalStore;
use Propel\Runtime\Collection\ObjectCollection;

interface AssetExternalStorageMapperInterface
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\AssetExternalStorage\Persistence\SpyAssetExternalCmsSlotStorage[] $assetExternalCmsSlotStorageEntities
     *
     * @return \Generated\Shared\Transfer\SpyAssetExternalCmsSlotStorageEntityTransfer[]
     */
    public function mapExternalCmsSlotStorageEntitiesToExternalCmsSlotStorageEntityTransfers(ObjectCollection $assetExternalCmsSlotStorageEntities): array;
}
