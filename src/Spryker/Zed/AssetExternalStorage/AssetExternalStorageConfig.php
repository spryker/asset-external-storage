<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternalStorage;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class AssetExternalStorageConfig extends AbstractBundleConfig
{
    /**
     * the column name for the fk_cms_slot field
     */
    public const COL_FK_CMS_SLOT = 'spy_asset_external.fk_cms_slot';

    /**
     * the column name for the fk_asset_external field
     */
    public const COL_FK_ASSET_EXTERNAL = 'spy_asset_external_store.fk_asset_external';

    /**
     * the column name for the fk_store field
     */
    public const COL_FK_STORE = 'spy_asset_external_store.fk_store';

    /**
     * @api
     *
     * @return string|null
     */
    public function getEventQueueName(): ?string
    {
        return null;
    }

    /**
     * @api
     *
     * @return string|null
     */
    public function getSynchronizationPoolName(): ?string
    {
        return null;
    }
}
