<?php

namespace TestPackage\Support;

enum ChangeFreqType: string
{
    case Hourly = 'hourly';
    case Daily = 'daily';
    case Weekly = 'weekly';
    case Monthly = 'monthly';
}
