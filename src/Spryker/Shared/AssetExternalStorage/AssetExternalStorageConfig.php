<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\AssetExternalStorage;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class AssetExternalStorageConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Key generation resource name for asset external messages.
     *
     * @api
     */
    public const ASSET_EXTERNAL_CMS_SLOT_RESOURCE_NAME = 'asset_external';

    /**
     * Specification:
     *  - Queue name as used for processing asset external messages.
     *
     * @api
     */
    public const ASSET_EXTERNAL_SYNC_STORAGE_QUEUE = 'sync.storage.asset_external';
}
