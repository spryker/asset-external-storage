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
     * The column name for the fk_cms_slot field.
     *
     * @var string
     */
    public const COL_CMS_SLOT_KEY = 'spy_asset_external.cms_slot_key';

    /**
     * The column name for the fk_asset_external field.
     *
     * @var string
     */
    public const COL_FK_ASSET_EXTERNAL = 'spy_asset_external_store.fk_asset_external';

    /**
     * The column name for the fk_store field.
     *
     * @var string
     */
    public const COL_FK_STORE = 'spy_asset_external_store.fk_store';

    /**
     * @api
     *
     * @return string|null
     */
    public function findEventQueueName(): ?string
    {
        return null;
    }

    /**
     * @api
     *
     * @return string|null
     */
    public function findSynchronizationPoolName(): ?string
    {
        return null;
    }
}
