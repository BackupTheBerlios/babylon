<?PHP;
/* Copyright 2003, 2004, 2005 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */


function vorschau ($K_Egl, $BenutzerId, $fid, $tid, $sid, $bid, $zid, $neu, $bemerkung)
{
  if (!$K_Egl)
    fehler (__FILE__, __LINE__, 1, 'Zwichenablage fuer die Vorschau wurde nicht aktualisiert, da nicht eingeloggt');
  
  $comp = trim ($_POST['text']);
  $code = $_POST['codearea'];
  if (strlen($code))
  {
    include_once('../forum/geshi/geshi.php');  

    $sprachen = array ('bash' => 'bash',
                       'C', 'c',
                       'cpp', 'cpp',
                       'HTML' => 'html4strict',
                       'Java' => 'java',
                       'JavaScript' => 'javascript',
                       'lisp' => 'lisp',
                       'PHP' => 'php-brief',
                       'Perl' => 'perl',
                       'Python' => 'python');
    $sprache = 'text';
    if (isset ($_POST['codelang']))
    {
      $sprache = $sprachen[$_POST['codelang']];
      if ($sprache == '')
        $sprache = 'text';
      else
      {
        $t = stripslashes($code);
        $ersatz = array ('&gt;' => '>', '&lt;' => '<', '&quot;' => '"', '&#039;' => '\'', '&amp;' => '&');
        $t = strtr ($t, $ersatz);

        if (isset($_POST['codezz']) and $_POST['codezz'] != "")
        {
          $a = explode (',', $_POST['codezz']);
          if (count ($a) == 2)
          {
            $code_start = intval($a[0]);
            $code_ende = intval($a[1]);
          }
        }
      
        $geshi = new GeSHi($t, $sprache, 'geshi/geshi');
        $geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);
        if (isset ($code_start))
          $geshi->highlight_lines_extra(range($code_start, $code_ende));
        $code =  $geshi->parse_code();
      }
    }
    
    if (isset($_POST['textarea_zeile']) and intval ($_POST['textarea_zeile']) != -1)
    {
      $zeilen = intval ($_POST['textarea_zeile']);
      $vorderteil = implode("\n", array_slice (explode("\n", $comp, $zeilen + 1), 0, $zeilen));
      $hinterteil = ltrim(substr ($comp, strlen ($vorderteil)));
      $vorderteil = rtrim($vorderteil);  
      $comp = $vorderteil . '<div class="code-bereich">' . $code . '</div>' . $hinterteil;
    }
    else
      $comp = $comp . '<div class="code-bereich">' . $code . '</div>'; 
  }

  $comp = addslashes ($comp);

  mysql_query ("UPDATE Benutzer
                SET Ablage = '$comp'
                WHERE BenutzerId = '$BenutzerId'")
    or fehler (__FILE__, __LINE__, 0, 'Zwischenablage fuer die Vorschau konnte nicht aktualisiert werden');
  
  $vorschau = strlen ($comp) ? 'j': 'n'; 
  $bemerkung = rawurlencode ($bemerkung);
  $titel = rawurlencode (substr (strip_tags (trim ($_POST['titel'])), 0, 50));
  include ('gz-beitraege.php');
}
