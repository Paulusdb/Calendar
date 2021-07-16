<?php

declare(strict_types=1);

namespace App\Form\ChangeMonth;

class MonthChangeHandler
{
    public function handle($form): \DateTime|bool
    {
        $currentTime = $form->get('currentDateTime')->getData();
        $time = new \DateTime($currentTime);

        $changeMonth = $form->get('changeMonth')->getData();

        if ($changeMonth == 'back') {
            $time = date_modify($time, '-1 month');
        } elseif ($changeMonth == 'forward') {
            $time = date_modify($time, '+1 month');
        }

        return $time;
    }
}