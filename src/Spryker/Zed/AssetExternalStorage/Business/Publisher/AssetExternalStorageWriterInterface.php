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
     * @param int $idStore
     *
     * @return void
     */
    public function publishStoreRelation(int $idAssetExternal, int $idStore): void;

    /**
     * @param int $idAssetExternal
     * @param string $cmsSlotKey
     *
     * @return void
     */
    public function unpublish(int $idAssetExternal, string $cmsSlotKey): void;

    /**
     * @param int $idAssetExternal
     * @param int $idStore
     *
     * @return void
     */
    public function unpublishStoreRelation(int $idAssetExternal, int $idStore): void;
}
