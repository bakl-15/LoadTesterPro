<?php
// src/Auth/Application/Service/DomainEventDispatcher.php

namespace App\Auth\Application\Service;

use App\Auth\Domain\User\Event\UserRegistered;
use App\Auth\Domain\User\Event\UserVerified;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final class DomainEventDispatcher
{
    public function __construct(private EventDispatcherInterface $symfonyDispatcher) {}

    public function dispatch(object $event): void
    {
        match (true) {
            $event instanceof UserRegistered => $this->symfonyDispatcher->dispatch($event),
            $event instanceof UserVerified => $this->symfonyDispatcher->dispatch($event),
            default => null, // Ignorer les autres événements
        };
    }
}
