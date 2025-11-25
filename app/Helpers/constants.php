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

/* Expense Types */
if (!defined('EXPENSE_TYPES')) {
    define('EXPENSE_TYPES', [
        1 => [
            'title' => 'FOOD',
            'description' => 'Eating outside such as restaurants, cafes, snacks, tea/coffee, and food delivery apps.'
        ],
        2 => [
            'title' => 'GROCERY',
            'description' => 'Daily household essentials including vegetables, milk, rice, oil, and supermarket items.'
        ],
        3 => [
            'title' => 'FUEL',
            'description' => 'Petrol, diesel, CNG, or any refueling costs for personal vehicles.'
        ],
        4 => [
            'title' => 'SAVINGS',
            'description' => 'Money transferred to savings accounts, RD/FD, emergency funds, or piggy bank.'
        ],
        5 => [
            'title' => 'SHOPPING',
            'description' => 'Buying items like clothes, electronics, accessories, gadgets, or home items.'
        ],
        6 => [
            'title' => 'BILLS',
            'description' => 'Monthly recurring bills such as electricity, water, mobile, internet, and gas.'
        ],
        7 => [
            'title' => 'ENTERTAINMENT',
            'description' => 'Fun activities like movies, games, events, concerts, and outings.'
        ],
        8 => [
            'title' => 'HEALTH',
            'description' => 'Medical-related spending such as medicines, doctor fees, tests, and fitness supplements.'
        ],
        9 => [
            'title' => 'TRAVEL',
            'description' => 'Travel or transport expenses including cabs, buses, trains, flights, hotels, tolls, and parking.'
        ],
        10 => [
            'title' => 'RENT',
            'description' => 'Monthly rent payments for house, PG, or office.'
        ],
        11 => [
            'title' => 'EDUCATION',
            'description' => 'Courses, books, tuition fees, certifications, or learning materials.'
        ],
        12 => [
            'title' => 'INVESTMENT',
            'description' => 'Money invested in stocks, mutual funds, SIPs, crypto, gold, etc.'
        ],
        13 => [
            'title' => 'PERSONAL_CARE',
            'description' => 'Personal grooming and care such as haircuts, salon, skincare, grooming products, and spa.'
        ],
        14 => [
            'title' => 'SUBSCRIPTIONS',
            'description' => 'Digital subscriptions like Netflix, Prime Video, Spotify, SaaS tools, and hosting.'
        ],
        15 => [
            'title' => 'VEHICLE_MAINTENANCE',
            'description' => 'Vehicle repair, servicing, parts replacement, tyres, lubrication, and insurance.'
        ],
        16 => [
            'title' => 'GIFTS',
            'description' => 'Gifts for family and friends, festival gifts, charity, and donations.'
        ],
        17 => [
            'title' => 'EMI',
            'description' => 'Loan EMI payments including personal loan, bike/car EMI, or device EMI.'
        ],
        18 => [
            'title' => 'OTHER',
            'description' => 'Miscellaneous expenses that do not fit into any other category.'
        ],
    ]);
}

/* Bank Account Types */
if (!defined('BANK_ACCOUNT_TYPES')) {
    define('BANK_ACCOUNT_TYPES', [
        1 => 'SAVINGS',
        2 => 'CURRENT',
        3 => 'FIXED DEPOSIT',
        4 => 'RECURRING DEPOSIT',
        5 => 'NRI ACCOUNT',
        6 => 'SALARY ACCOUNT',
        7 => 'OTHER'
    ]);
}
