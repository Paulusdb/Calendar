<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Form\AppointmentType;
use App\Form\ChangeMonthType;
use App\Repository\AppointmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppointmentController extends AbstractController
{
    #[Route('/', name: 'app_calendar', methods: ['GET', 'POST'])]
    public function calendar(AppointmentRepository $appointmentRepository, Request $request): Response
    {
        $form = $this->createForm(ChangeMonthType::class);
        $form->handleRequest($request);

        $time = new \DateTime();

        if ($form->isSubmitted() && $form->isValid()) {
            $currentTime = $form->get('currentDateTime')->getData();
            $time = new \DateTime($currentTime);

            $changeMonth = $form->get('changeMonth')->getData();

            if ($changeMonth == 'back') {
                $time = date_modify($time, '-1 month');
            }
            elseif ($changeMonth == 'forward') {
                $time = date_modify($time, '+1 month');
            }
        }

        return $this->render('calendar/calendar.html.twig', [
            'time' => $time,
            'appointments' => $appointmentRepository->findAll(),
            'month_back_form' => $form->createView(),
            'month_forward_form' => $form->createView(),
        ]);
    }

    #[Route('/list', name: 'appointment_index', methods: ['GET'])]
    public function index(AppointmentRepository $appointmentRepository): Response
    {
        return $this->render('appointment/index.html.twig', [
            'appointments' => $appointmentRepository->findAll(),
        ]);
    }

    #[Route('/appointment/new', name: 'appointment_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $appointment = new Appointment();
        $form = $this->createForm(AppointmentType::class, $appointment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($appointment);
            $entityManager->flush();

            return $this->redirectToRoute('appointment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('appointment/new.html.twig', [
            'appointment' => $appointment,
            'form' => $form,
        ]);
    }

    #[Route('/appointment/{id}', name: 'appointment_show', methods: ['GET'])]
    public function show(Appointment $appointment): Response
    {
        return $this->render('appointment/show.html.twig', [
            'appointment' => $appointment,
        ]);
    }

    #[Route('/appointment/{id}/edit', name: 'appointment_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Appointment $appointment): Response
    {
        $form = $this->createForm(AppointmentType::class, $appointment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('appointment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('appointment/edit.html.twig', [
            'appointment' => $appointment,
            'form' => $form,
        ]);
    }

    #[Route('/appointment/{id}', name: 'appointment_delete', methods: ['POST'])]
    public function delete(Request $request, Appointment $appointment): Response
    {
        if ($this->isCsrfTokenValid('delete'.$appointment->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($appointment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('appointment_index', [], Response::HTTP_SEE_OTHER);
    }
}
