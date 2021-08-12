<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternalStorage\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\AssetExternalStorage\Communication\Plugin\Event\Listener\AssetExternalStoragePublishListener;
use Spryker\Zed\AssetExternalStorage\Communication\Plugin\Event\Listener\AssetExternalStorageUnpublishListener;
use Spryker\Zed\AssetExternalStorage\Communication\Plugin\Event\Listener\AssetExternalStoreStoragePublishListener;
use Spryker\Zed\AssetExternalStorage\Communication\Plugin\Event\Listener\AssetExternalStoreStorageUnpublishListener;
use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\AssetExternalStorage\AssetExternalStorageConfig getConfig()
 * @method \Spryker\Zed\AssetExternalStorage\Communication\AssetExternalStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\AssetExternalStorage\Business\AssetExternalStorageFacadeInterface getFacade()
 */
class AssetExternalStorageEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
{
    /**
     * @api
     *
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    public function getSubscribedEvents(EventCollectionInterface $eventCollection): EventCollectionInterface
    {
        $this->addAssetExternalCreateStorageListener($eventCollection);
        $this->addAssetExternalUpdateStorageListener($eventCollection);
        $this->addAssetExternalDeleteStorageListener($eventCollection);
        $this->addAssetExternalStoreCreateStorageListener($eventCollection);
        $this->addAssetExternalStoreDeleteStorageListener($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    protected function addAssetExternalCreateStorageListener(EventCollectionInterface $eventCollection): EventCollectionInterface
    {
        return $eventCollection->addListenerQueued('Entity.spy_asset_external.create', new AssetExternalStoragePublishListener(), 0, null, $this->getConfig()->getEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    protected function addAssetExternalUpdateStorageListener(EventCollectionInterface $eventCollection): EventCollectionInterface
    {
        return $eventCollection->addListenerQueued('Entity.spy_asset_external.update', new AssetExternalStoragePublishListener(), 0, null, $this->getConfig()->getEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    protected function addAssetExternalDeleteStorageListener(EventCollectionInterface $eventCollection): EventCollectionInterface
    {
        return $eventCollection->addListenerQueued('Entity.spy_asset_external.delete', new AssetExternalStorageUnpublishListener(), 0, null, $this->getConfig()->getEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    protected function addAssetExternalStoreCreateStorageListener(EventCollectionInterface $eventCollection): EventCollectionInterface
    {
        return $eventCollection->addListenerQueued('Entity.spy_asset_external_store.create', new AssetExternalStoreStoragePublishListener(), 0, null, $this->getConfig()->getEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    protected function addAssetExternalStoreDeleteStorageListener(EventCollectionInterface $eventCollection): EventCollectionInterface
    {
        return $eventCollection->addListenerQueued('Entity.spy_asset_external_store.delete', new AssetExternalStoreStorageUnpublishListener(), 0, null, $this->getConfig()->getEventQueueName());
    }
}
