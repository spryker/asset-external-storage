<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternalStorage\Business;

interface AssetExternalStorageFacadeInterface
{
    /**
     * Specification:
     * - Queries all asset externals with cms slot equals to requested asset external csm slot
     * - Stores data as json encoded to storage table per cms slot and store
     * - Sends a copy of data to queue based on module config
     *
     * @api
     *
     * @param int $idAssetExternal
     *
     * @return void
     */
    public function publish(int $idAssetExternal): void;

    /**
     * Specification:
     * - Queries all asset externals with cms slot equals to deleted asset external csm slot
     * - Stores data without deleted asset external as json encoded to storage table
     * - Sends delete message to queue based on module config
     *
     * @api
     *
     * @param int $idAssetExternal
     *
     * @return void
     */
    public function unpublish(int $idAssetExternal): void;
}
