<?php

declare(strict_types=1);

namespace App\Controller\Appointment;

use App\Entity\Appointment;
use App\Form\Appointment\Create\AppointmentCreateHandler;
use App\Form\Appointment\AppointmentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppointmentCreateController extends AbstractController
{
    public function __construct(
        private AppointmentCreateHandler $formHandler
    ){
    }

    #[Route('/appointment/new', name: 'appointment_new')]
    public function new(Request $request): Response
    {
        $appointment = new Appointment();
        $form = $this->createForm(AppointmentType::class, $appointment);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->formHandler->handle($appointment);

            $this->addFlash('success', 'Appointment is successfully set!');

            return $this->redirectToRoute('appointment_index');
        }

        return $this->renderForm('appointment/new.html.twig', [
            'appointment' => $appointment,
            'form' => $form,
        ]);
    }
}