<?PHP
function msie()
{
  return (strstr($_SERVER["HTTP_USER_AGENT"], "MSIE"));
}

function msie_png()
{
  return (msie() ? ".ie" : "");
}
;?>
