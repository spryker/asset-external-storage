<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternalStorage\Dependency\Facade;

use Generated\Shared\Transfer\AssetExternalTransfer;

interface AssetExternalStorageToAssetExternalInterface
{
    /**
     * @param int $idAssetExternal
     *
     * @return \Generated\Shared\Transfer\AssetExternalTransfer|null
     */
    public function findAssetById(int $idAssetExternal): ?AssetExternalTransfer;
}
