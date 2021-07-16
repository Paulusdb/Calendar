<?php

declare(strict_types=1);

namespace App\Controller\Appointment;

use App\Entity\Appointment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppointmentShowController extends AbstractController
{
    #[Route('/appointment/{id}', name: 'appointment_show')]
    public function show(Appointment $appointment): Response
    {
        return $this->render('appointment/show.html.twig', [
            'appointment' => $appointment,
        ]);
    }
}