<?php

namespace App\Helpers;

use Illuminate\Contracts\Validation\Rule;

class PESELRule implements Rule
{
    private bool $not_numeric;
    private bool $not_eleven_characters;
    private bool $person_under_18;

    public function passes($attribute, $value)
    {
        if (!is_numeric($value)) {
            $this->not_numeric = true;
            return false;
        }
        if (strlen((string)$value) !== 11) {
            $this->not_eleven_characters = true;
            return false;
        }

        $weights = [1, 3, 7, 9, 1, 3, 7, 9, 1, 3];
        $sum = 0;
        for ($i = 0; $i < 10; $i++) {
            $sum += $weights[$i] * (int) substr((string)$value, $i, 1);//$sum += $weights[$i] * (int) $value[$i];
        }
        $checkSum = 10 - ($sum % 10);
        if ($checkSum === 10) {
            $checkSum = 0;
        }

        $year = (int)substr((string)$value, 0, 2);
        $month = (int)substr((string)$value, 2, 2);
        $day = (int)substr((string)$value, 4, 2);
        if ($month > 80) {
            $month -= 80;
            $year += 1800;
        } elseif ($month > 60) {
            $month -= 60;
            $year += 2200;
        } elseif ($month > 40) {
            $month -= 40;
            $year += 2100;
        } elseif ($month > 20) {
            $month -= 20;
            $year += 2000;
        } else {
            $year += 1900;
        }
        if (!checkdate($month, $day, $year)) {
            return false;
        }

        if ($checkSum != (int)substr((string) $value, 10, 1)) {//if ($value[10] != $checkSum) {
            return false;
        }

        $age = date("Y") - $year;
        if ($age < 18) {
            $this->person_under_18 = true;
            return false;
        }
        return true;
    }

    public function message(): string
    {
        if (isset($this->not_numeric) && $this->not_numeric) {
            return 'not numeric values';
        }
        if (isset($this->not_eleven_characters) && $this->not_eleven_characters) {
            return 'eleven characters required';
        }
        if (isset($this->person_under_18) && $this->person_under_18) {
            return 'Not 18 years old';
        }
        return 'Podaj poprawny :attribute';
    }
}
