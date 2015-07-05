<?php
class IEEventSinker {
    var $terminated = false;

   function ProgressChange($progress, $progressmax) {
      echo "Download progress: $progress / $progressmax\n";
    }

    function DocumentComplete(&$dom, $url) {
      echo "Document $url complete\n";
    }

    function OnQuit() {
      echo "Quit!\n";
      $this->terminated = true;
    }
}
$ie = new COM("InternetExplorer.Application");
// note that you don't need the & for PHP 5!
$sink = new IEEventSinker();
com_event_sink($ie, $sink, "DWebBrowserEvents2");
$ie->Visible = true;
$ie->Navigate("http://www.example.org");
while(!$sink->terminated) {
  com_message_pump(4000);
}
$ie = null;
?>