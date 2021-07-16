<?php

declare(strict_types=1);

namespace App\Controller\Appointment;


use App\Entity\Appointment;
use App\Form\Appointment\Delete\AppointmentDeleteHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppointmentDeleteController extends AbstractController
{
    public function __construct(private AppointmentDeleteHandler $handler)
    {
    }

    #[Route('/appointment/{id}', name: 'appointment_delete')]
    public function delete(Request $request, Appointment $appointment): Response
    {
        if ($this->isCsrfTokenValid('delete'.$appointment->getId(), $request->request->get('_token'))) {
            $this->handler->handle($appointment);

            $this->addFlash('success', 'Appointment deleted successfully!');
        }

        return $this->redirectToRoute('appointment_index');
    }
}