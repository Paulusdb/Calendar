<?php

declare(strict_types=1);

namespace App\Form\Appointment\Update;

use App\Entity\Appointment;
use Doctrine\ORM\EntityManagerInterface;

class AppointmentUpdateHandler
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function handle(Appointment $appointment)
    {
        $this->entityManager->persist($appointment);
        $this->entityManager->flush();
    }
}