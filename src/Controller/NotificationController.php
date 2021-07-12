<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Notifier\Notification\Notification;
use App\Repository\AppointmentRepository;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Routing\Annotation\Route;

class NotificationController extends AbstractController
{
    #[Route('/notify', name: 'send_notification')]
    public function sender(AppointmentRepository $appointmentRepository, NotifierInterface $notifier)
    {
        $appointments = $appointmentRepository->findAll();
        $today = new \DateTime();
        $today = $today->format('d-m-Y');

        foreach ($appointments as $appointment) {
            $begin = $appointment->getBegin()->format('d-m-Y');
            $checkNotification = $appointment->getNotification();

            if ($checkNotification === true and $begin == $today) {

                $notificationTitle = 'Your appointment ' . $appointment->getTitle() . ' is set for today';

                $notification = new Notification($notificationTitle, ['chat/telegram']);

                $notifier->send($notification);
            }
        }
        return $this->redirectToRoute('appointment_index');
    }
}