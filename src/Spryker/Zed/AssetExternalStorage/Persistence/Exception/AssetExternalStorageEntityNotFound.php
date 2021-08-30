<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternalStorage\Persistence\Exception;

use Exception;

class AssetExternalStorageEntityNotFound extends Exception
{
    /**
     * @param int $idAssetExternalCmsSlotStorage
     */
    public function __construct(int $idAssetExternalCmsSlotStorage)
    {
        $message = sprintf('No asset external storage entity with id %s', $idAssetExternalCmsSlotStorage);

        parent::__construct($message);
    }
}
