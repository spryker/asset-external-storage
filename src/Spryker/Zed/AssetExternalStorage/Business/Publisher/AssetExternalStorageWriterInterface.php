<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternalStorage\Business\Publisher;

interface AssetExternalStorageWriterInterface
{
    /**
     * @param int $idAssetExternal
     *
     * @return void
     */
    public function publish(int $idAssetExternal): void;

    /**
     * @param int $idAssetExternal
     *
     * @return void
     */
    public function unpublish(int $idAssetExternal): void;
}
