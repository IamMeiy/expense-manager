<?php
/* Payment method constants */
if (!defined('PAYMENT_METHODS')) {
    define('PAYMENT_METHODS', [
        1 => 'CASH',
        2 => 'UPI',
        3 => 'CREDIT CARD',
        4 => 'DEBIT CARD',
        5 => 'BANK TRANSFER',
        6 => 'CHECK',
        7 => 'OTHER'
    ]);
}
