<?php

namespace App;

enum ConnectionType: string
{
    //phone or email
    case PHONE = 'phone';
    case EMAIL = 'email';
}