<?PHP;
echo "<html>
<head>
  <link href=\"/web/css/web.css\" rel=\"stylesheet\" type=\"text/css\">
  <script src=\"/web/js/flimmern.js\" type=\"text/javascript\"></script>
  <title>Babylon</title>
  <script type=\"text/javascript\">
  <!--
  var letzter = -1;
  -->
  </script>
</head>
<body>";
  include ("web/leiste.php");
  echo "  <img src=\"/web/grafik/babylon.png\">

  <img src=\"/web/grafik/dummy.png\" alt=\"\" onLoad=\"window.setInterval('flimmern()', 100)\">

  <h2> Ein Webforum</h2>
  <a name=\"ueber\"><img Name=\"b5\" src=\"/web/grafik/links00.png\"></a>
  
  <h3>Aus Sicht des Anwenders</h3>
  bietet es viele M&ouml;glichkeiten der Konfiguration.
  <ul>
    <li>Stile - verschiedenen Ansichten in denen sich das Forum pr&auml;sentiert</li>
    <li>Signaturen - eine Fusszeile die an jeden Beitrag geh&auml;ngt wird</li>
    <li>Themen / Beitr&auml;ge je Seite - wieviel wird auf jeder Seite angezeigt</li>
    <li>Sprungverhalten - wohin wird nach dem Absenden eines Beitrags verzweigt</li>
    <li>Atavare - Verzierung der eigenen Beitr&auml;ge mit Atavaren</li>
  </ul>
  <h3>Dem Foren-Betreiber</h3>
  gibt es die M&ouml;glichkeit mit geringem Aufwand das Forum einzurichten und zu betreiben.<p>";


  include ("web/runterladen.php");
  include ("web/installation.php");
  include ("web/mitarbeit.php");
  include ("web/bugs.php");
  
echo "</body>
</html>";

;?>
