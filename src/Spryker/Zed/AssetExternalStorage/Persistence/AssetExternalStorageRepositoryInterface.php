<?php

/**
* Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
* Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
*/

namespace Spryker\Zed\AssetExternalStorage\Persistence;

interface AssetExternalStorageRepositoryInterface
{
    /**
     * @return \Orm\Zed\AssetExternalStorage\Persistence\SpyAssetExternalCmsSlotStorage[]
     */
    public function findAllAssetExternalStorage(): array;

    /**
     * @param int[] $ids
     *
     * @return \Orm\Zed\AssetExternalStorage\Persistence\SpyAssetExternalCmsSlotStorage[]
     */
    public function findAllAssetExternalStorageByAssetExternalId(array $ids): array;
}
