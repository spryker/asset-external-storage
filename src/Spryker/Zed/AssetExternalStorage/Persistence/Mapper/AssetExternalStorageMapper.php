<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternalStorage\Persistence\Mapper;

use Generated\Shared\Transfer\SpyAssetExternalCmsSlotStorageEntityTransfer;
use Propel\Runtime\Collection\ObjectCollection;

class AssetExternalStorageMapper implements AssetExternalStorageMapperInterface
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\AssetExternalStorage\Persistence\SpyAssetExternalCmsSlotStorage[] $assetExternalCmsSlotStorageEntities
     *
     * @return array<\Generated\Shared\Transfer\SpyAssetExternalCmsSlotStorageEntityTransfer>
     */
    public function mapExternalCmsSlotStorageEntitiesToExternalCmsSlotStorageEntityTransfers(ObjectCollection $assetExternalCmsSlotStorageEntities): array
    {
        $assetExternalCmsSlotStorageEntityTransfers = [];
        foreach ($assetExternalCmsSlotStorageEntities as $assetExternalCmsSlotStorageEntity) {
            $assetExternalCmsSlotStorageEntityTransfers[] = (new SpyAssetExternalCmsSlotStorageEntityTransfer())->fromArray($assetExternalCmsSlotStorageEntity->toArray(), true);
        }

        return $assetExternalCmsSlotStorageEntityTransfers;
    }
}
