<?php

namespace App\Services;

use setasign\Fpdi\Fpdi;

class PdfProtection extends Fpdi
{
    protected $encrypted = false;

    public function SetProtection($permissions = [], $user_pass = '', $owner_pass = null)
    {
        $this->encrypted = true;

        if ($owner_pass === null) {
            $owner_pass = uniqid();
        }

        parent::SetProtection($permissions, $user_pass, $owner_pass);
    }
}