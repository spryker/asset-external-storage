<?php

/**
* Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
* Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
*/

namespace Spryker\Zed\AssetExternalStorage\Business;

interface AssetExternalStorageFacadeInterface
{
    /**
     * @param int $assetExternalId
     *
     * @return void
     */
    public function publish(int $assetExternalId): void;

    /**
     * @param int $assetExternalId
     *
     * @return void
     */
    public function unpublish(int $assetExternalId): void;
}
