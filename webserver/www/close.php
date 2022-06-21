<?php
  include "../src/Waitlist.php";

  $waitlist = new Waitlist();
  $waitlist->pull($_GET['caseid']);
  $waitlist->setToClosed("$200");
  header('Location:./home.php');
