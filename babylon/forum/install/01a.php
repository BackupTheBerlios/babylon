<?php;
  if (!isset ($_POST['dbname']))
  {
    echo 'Du mu&szlig;t den Namen der zu erstellenden Datenbank angenben.
    <form action="01.php" method="post">
    <button >Zur&uuml;</button>
  </form>';

  }
  mysql_query ("CREATE DATABASE `babylon`")
    or
    {
      echo "Datenbank $db konnte nicht erstellt werden
       <form action=\"01.php\" method=\"post\">
       <button >Zur&uuml;</button>
     </form>";
    }

  echo '<h2>Datenbank erstellt</h2>

    Die Datenbank wurde erfolgreich erstellt';

  echo '<form action="02.php" method="post">
    <button >Weiter</button>
  </form>';
;?>

