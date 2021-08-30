<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternalStorage\Dependency\Facade;

use Generated\Shared\Transfer\CmsSlotTransfer;

interface AssetExternalStorageToCmsSlotInterface
{
    /**
     * @param int $idCmsSlot
     *
     * @return \Generated\Shared\Transfer\CmsSlotTransfer
     */
    public function getCmsSlotById(int $idCmsSlot): CmsSlotTransfer;
}
