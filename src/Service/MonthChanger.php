<?php


namespace App\Service;


class MonthChanger
{
    public function changeMonth($form)
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