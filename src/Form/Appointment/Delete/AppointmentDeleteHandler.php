<?php

declare(strict_types=1);

namespace App\Form\Appointment\Delete;

use App\Entity\Appointment;
use Doctrine\ORM\EntityManagerInterface;

class AppointmentDeleteHandler
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function handle(Appointment $appointment)
    {
        $this->entityManager->remove($appointment);
        $this->entityManager->flush();
    }
}