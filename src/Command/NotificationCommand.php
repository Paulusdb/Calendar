<?php

declare(strict_types=1);

namespace App\Command;

use App\Repository\AppointmentRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;

#[AsCommand(
    name: 'app:notification',
    description: 'check if appointment is set for today, if so: send notification',
)]
class NotificationCommand extends Command
{

    private AppointmentRepository $appointmentRepository;
    private NotifierInterface $notifier;

    public function __construct(AppointmentRepository $appointmentRepository, NotifierInterface $notifier)
    {
        $this->appointmentRepository = $appointmentRepository;
        $this->notifier = $notifier;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('today_date', InputArgument::OPTIONAL, 'Date of this day', date_create()->format('d-m-Y'))
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $today = $input->getArgument('today_date');

        $appointments = $this->appointmentRepository->findAll();

        $notifyCount = 0;

        foreach ($appointments as $appointment) {
            $begin = $appointment->getBegin()->format('d-m-Y');
            $mustNotify = $appointment->getNotification();

            if ($mustNotify === true and $begin == $today) {
                $this->sendNotification($appointment, $this->notifier);

                $notifyCount++;
            }
        }

        $io->success("$notifyCount appointments received a notification");

        return Command::SUCCESS;
    }

    public function sendNotification($appointment, $notifier)
    {
        $notificationTitle = 'Your appointment ' . $appointment->getTitle() . ' is set for today at ' . $appointment->getBegin()->format('H:i') . '.';

        $notification = new Notification($notificationTitle, ['chat/telegram']);

        return $notifier->send($notification);
    }
}
