<?php

namespace app\components;

/**
 * A class to validate email domains
 */
class VerifyEmailDomain
{
    public function check($email): bool
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            list(, $domain) = explode('@', $email);
            if (dns_check_record($domain, "MX")) {
                return true;
            }
        }

        return false;
    }
}
