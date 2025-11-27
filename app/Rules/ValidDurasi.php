<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidDurasi implements Rule
{
    public function passes($attribute, $value)
    {
        // Format harus hh.mm
        if (!preg_match('/^\d{1,2}\.\d{1,2}$/', $value)) {
            return false;
        }

        list($jam, $menit) = explode('.', $value);

        // Menit maksimal 59
        if (intval($menit) > 59) {
            return false;
        }

        return true;
    }

    public function message()
    {
        return 'Durasi harus dalam format jam.menit dengan menit maksimal 59.';
    }
}
