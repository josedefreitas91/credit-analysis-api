<?php

namespace App\Enums;

enum ResultType: string
{
  case disapproved = 'disapproved';
  case derivative = 'derivative';
  case approved = 'approved';
}
