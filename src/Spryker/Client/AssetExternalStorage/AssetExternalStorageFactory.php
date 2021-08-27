<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AssetExternalStorage;

use Spryker\Client\AssetExternalStorage\Dependency\Client\AssetExternalStorageToStorageClientInterface;
use Spryker\Client\AssetExternalStorage\Dependency\Service\AssetExternalStorageToSynchronizationServiceInterface;
use Spryker\Client\AssetExternalStorage\Mapper\AssetExternalStorageMapper;
use Spryker\Client\AssetExternalStorage\Reader\AssetExternalStorageReader;
use Spryker\Client\AssetExternalStorage\Reader\AssetExternalStorageReaderInterface;
use Spryker\Client\Kernel\AbstractFactory;

class AssetExternalStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\AssetExternalStorage\Reader\AssetExternalStorageReaderInterface
     */
    public function createAssetExternalStorageReader(): AssetExternalStorageReaderInterface
    {
        return new AssetExternalStorageReader(
            $this->getStorageClient(),
            $this->getServiceSynchronization(),
            $this->createAssetExternalStorageMapper()
        );
    }

    /**
     * @return \Spryker\Client\AssetExternalStorage\Mapper\AssetExternalStorageMapper
     */
    public function createAssetExternalStorageMapper()
    {
        return new AssetExternalStorageMapper();
    }

    /**
     * @return \Spryker\Client\AssetExternalStorage\Dependency\Service\AssetExternalStorageToSynchronizationServiceInterface
     */
    public function getServiceSynchronization(): AssetExternalStorageToSynchronizationServiceInterface
    {
        return $this->getProvidedDependency(AssetExternalStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    /**
     * @return \Spryker\Client\AssetExternalStorage\Dependency\Client\AssetExternalStorageToStorageClientInterface
     */
    public function getStorageClient(): AssetExternalStorageToStorageClientInterface
    {
        return $this->getProvidedDependency(AssetExternalStorageDependencyProvider::CLIENT_STORAGE);
    }
}
