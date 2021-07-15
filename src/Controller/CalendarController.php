<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\ChangeMonthType;
use App\Repository\AppointmentRepository;
use App\Service\MonthChanger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CalendarController extends AbstractController
{
    #[Route('/', name: 'app_calendar', methods: ['GET', 'POST'])]
    public function calendar(AppointmentRepository $appointmentRepository, Request $request): Response
    {
        $form = $this->createForm(ChangeMonthType::class);
        $form->handleRequest($request);

        $time = new \DateTime();

        if ($form->isSubmitted() && $form->isValid()) {
            $monthChanger = new MonthChanger();
            $time = $monthChanger->changeMonth($form);
        }

        return $this->renderForm('calendar/calendar.html.twig', [
            'time' => $time,
            'appointments' => $appointmentRepository->findAll(),
            'month_back_form' => $form,
            'month_forward_form' => $form,
        ]);
    }
}