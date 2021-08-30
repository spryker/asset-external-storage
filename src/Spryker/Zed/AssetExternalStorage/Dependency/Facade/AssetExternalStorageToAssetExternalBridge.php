<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternalStorage\Dependency\Facade;

use Generated\Shared\Transfer\AssetExternalTransfer;

class AssetExternalStorageToAssetExternalBridge implements AssetExternalStorageToAssetExternalInterface
{
    /**
     * @var \Spryker\Zed\AssetExternal\Business\AssetExternalFacadeInterface
     */
    protected $assetExternalFacade;

    /**
     * @param \Spryker\Zed\AssetExternal\Business\AssetExternalFacadeInterface $assetExternalFacade
     */
    public function __construct($assetExternalFacade)
    {
        $this->assetExternalFacade = $assetExternalFacade;
    }

    /**
     * @param int $idAssetExternal
     *
     * @return \Generated\Shared\Transfer\AssetExternalTransfer|null
     */
    public function findAssetById(int $idAssetExternal): ?AssetExternalTransfer
    {
        return $this->assetExternalFacade->findAssetById($idAssetExternal);
    }
}
