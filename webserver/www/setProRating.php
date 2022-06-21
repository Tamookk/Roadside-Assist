<?php
  include "../src/Waitlist.php";

  $waitlist = new Waitlist();
  $waitlist->pull($_GET['caseid']);
  $waitlist->setProRating(intval($_GET['rate']));
  header('Location:./status.php?caseid=' . $_GET['caseid']);
?>
