<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternalStorage\Business\Publisher;

interface AssetExternalStorageWriterInterface
{
    /**
     * @param int $assetExternalId
     *
     * @return void
     */
    public function updateAssetExternalSorageData(int $assetExternalId): void;
}
