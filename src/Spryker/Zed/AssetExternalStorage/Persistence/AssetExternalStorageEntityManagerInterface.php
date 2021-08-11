<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternalStorage\Persistence;

use Propel\Runtime\Collection\ObjectCollection;

interface AssetExternalStorageEntityManagerInterface
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\AssetExternal\Persistence\SpyAssetExternal[] $assetExternalEntities
     * @param string $storeName
     * @param string $cmsSlotKey
     * @param int $cmsSlotId
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     *
     * @return void
     */
    public function saveAssetExternalStorage(ObjectCollection $assetExternalEntities, string $storeName, string $cmsSlotKey, int $cmsSlotId): void;
}
