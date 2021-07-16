<?php

declare(strict_types=1);

namespace App\Controller\Appointment;

use App\Form\Appointment\AppointmentType;
use App\Entity\Appointment;
use App\Form\Appointment\Update\AppointmentUpdateHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppointmentUpdateController extends AbstractController
{
    public function __construct(private AppointmentUpdateHandler $formHandler)
    {
    }

    #[Route('/appointment/{id}/edit', name: 'appointment_edit')]
    public function edit(Request $request, Appointment $appointment): Response
    {
        $form = $this->createForm(AppointmentType::class, $appointment);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->formHandler->handle($appointment);

            $this->addFlash('success', 'Appointment edited successfully!');

            return $this->redirectToRoute('appointment_index');
        }

        return $this->renderForm('appointment/edit.html.twig', [
            'appointment' => $appointment,
            'form' => $form,
        ]);
    }
}