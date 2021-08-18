<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\AssetExternalStorage;

use Spryker\Shared\Kernel\AbstractBundleConfig;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
class AssetExternalStorageConstants extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Resource name, this will use for key generating
     *
     * @api
     */
    public const ASSET_EXTERNAL_RESOURCE_NAME = 'asset_external';
}
