<?php

class StartSessionMiddleware
{
  public function fire()
  {
    if (!isset($_SESSION)) {
      session_start();
    }
  }
}
