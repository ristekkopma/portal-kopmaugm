<?php

namespace App\Enums;

enum Religion: string
{
    case Islam = 'islam';
    case Christianity = 'christianity';
    case Hinduism = 'hinduism';
    case Buddhism = 'buddhism';
    case Sikhism = 'sikhism';
    case Jainism = 'jainism';
    case Zoroastrianism = 'zoroastrianism';
    case Taoism = 'taoism';
    case Other = 'other';
}
