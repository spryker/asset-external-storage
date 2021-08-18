<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternalStorage\Business;

use Spryker\Zed\AssetExternalStorage\AssetExternalStorageDependencyProvider;
use Spryker\Zed\AssetExternalStorage\Business\Publisher\AssetExternalStorageWriter;
use Spryker\Zed\AssetExternalStorage\Business\Publisher\AssetExternalStorageWriterInterface;
use Spryker\Zed\AssetExternalStorage\Dependency\Facade\AssetExternalStorageToStoreFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\AssetExternalStorage\AssetExternalStorageConfig getConfig()
 * @method \Spryker\Zed\AssetExternalStorage\Persistence\AssetExternalStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\AssetExternalStorage\Persistence\AssetExternalStorageRepositoryInterface getRepository()
 */
class AssetExternalStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\AssetExternalStorage\Business\Publisher\AssetExternalStorageWriterInterface
     */
    public function createAssetExternalStorageWriter(): AssetExternalStorageWriterInterface
    {
        return new AssetExternalStorageWriter(
            $this->getFacadeStore(),
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\AssetExternalStorage\Dependency\Facade\AssetExternalStorageToStoreFacadeInterface
     */
    public function getFacadeStore(): AssetExternalStorageToStoreFacadeInterface
    {
        return $this->getProvidedDependency(AssetExternalStorageDependencyProvider::FACADE_STORE);
    }
}
