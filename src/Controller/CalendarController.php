<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\ChangeMonth\ChangeMonthType;
use App\Repository\AppointmentRepository;
use App\Form\ChangeMonth\MonthChangeHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CalendarController extends AbstractController
{
    public function __construct(private MonthChangeHandler $monthHandler)
    {
    }

    #[Route('/', name: 'app_calendar')]
    public function calendar(AppointmentRepository $appointmentRepository, Request $request): Response
    {
        $form = $this->createForm(ChangeMonthType::class);

        $time = new \DateTime();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $time = $this->monthHandler->handle($form);
        }

        return $this->renderForm('calendar/calendar.html.twig', [
            'time' => $time,
            'appointments' => $appointmentRepository->findAll(),
            'month_back_form' => $form,
            'month_forward_form' => $form,
        ]);
    }
}