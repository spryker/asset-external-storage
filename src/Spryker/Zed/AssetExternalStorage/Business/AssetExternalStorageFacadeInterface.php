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
     * - Queries all asset externals with cms slot equals to requested asset external csm slot and with store equals to requested store
     * - Removes asset external from json encoded data
     * - Sends a copy of data to queue based on module config
     *
     * @api
     *
     * @param int $idAssetExternal
     * @param int $idStore
     *
     * @return void
     */
    public function publishStoreRelation(int $idAssetExternal, int $idStore): void;

    /**
     * Specification:
     * - Queries all asset externals with cms slot equals to requested asset external csm slot
     * - Removes asset external from json encoded data
     * - Sends a copy of data to queue based on module config
     *
     * @api
     *
     * @param int $idAssetExternal
     * @param string $cmsSlotKey
     *
     * @return void
     */
    public function unpublish(int $idAssetExternal, string $cmsSlotKey): void;

    /**
     * Specification:
     * - Queries all asset externals with cms slot equals to requested asset external csm slot and with store equals to requested store
     * - Removes asset external from json encoded data
     * - Sends a copy of data to queue based on module config
     *
     * @api
     *
     * @param int $idAssetExternal
     * @param int $idStore
     *
     * @return void
     */
    public function unpublishStoreRelation(int $idAssetExternal, int $idStore): void;
}
