<?php

namespace App\Doctrine;

use App\Entity\HasMetaTimestampsInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class MetaTimestampsPrePersistEventListener
{
    public function prePersist(LifecycleEventArgs $event): void
    {
        //
    }
}
