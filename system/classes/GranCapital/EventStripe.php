<?php

namespace GranCapital;

use HCStudio\Orm;

class EventStripe extends Orm {
  protected $tblName  = 'event_stripe';

  public function __construct() {
    parent::__construct();
  }
}