<div class="joinrpg-api" data-projectId="509" data-locationId="%%loc%%">
  Идет загрузка...<br>
  NB! Некоторые расширения (например friGate у Chrome) могут приводить к ошибке, отключите их.
</div>
<script>
  window.$ = jQuery;
  function loadJoinrpgRoles() {
    jQuery(function() { jQuery('.joinrpg-api').joinrpgroles(); }); 
  };
</script>
<script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="https://joinrpg.ru/external/joinrpg-api.js" defer onload="loadJoinrpgRoles()"></script> 
