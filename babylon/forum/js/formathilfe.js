function _einfuegen (text, cursor)
{
  var feld = document.getElementsByName("text")[0]

  if (typeof feld.selectionStart != 'undefined' &&
      feld.selectionStart != '0')
  {
    var start = feld.selectionStart;
    var ende = feld.selectionEnd;
    feld.value = feld.value.substring(0, start)
     + text + feld.value.substring(ende, feld.value.length);

    feld.selectionStart = start + cursor;
    feld.selectionEnd = start + cursor;
  }
  else
  {
    feld.value += text;
  }
  
  feld.focus();

  if (typeof feld.scrollTop != 'undefined' &&
      typeof feld.scrollHeight != 'undefined')
    feld.scrollTop = feld.scrollHeight;
}

formate = new Array (2);
formate[0] = new Array ("&gt;", "<b></b>", "<i></i>", "<tt></tt>",
                        "&lt;", "<table border=\"1\"></table>", "<tr></tr>", "<td></td>",
		                  "&amp;", "<ul></ul>", "<ol></ol>", "<li></li>");
formate[1] = new Array ("&gt;", "<b>fett<\/b>", "<i>kursiv<\/i>", "<tt>Maschine<\/tt>",
                        "&lt;", "Tabelle", "Tabellenreihe", "Tabellenzelle",
			               "&amp;", "unsortierte Liste", "sortierte Liste", "Listenelement");
formate[2] = new Array (4, 3, 3, 4,
                        4, 18, 4, 4,
                        5, 4, 4, 4);2

function formate_ausgeben (typ)
{
    _einfuegen (formate[0][typ], formate[2][typ]);
}

function tabelle_formate_erstellen ()
{
  document.writeln ("<\/td><td><img src=\"../grafik/dummy.png\" width=\"24\" height=\"1\"<\/td><td>");
  document.writeln ("<table>");
  for (y = 0; y < 3; y++)
  {
    document.writeln ("<tr>");
    for (x = 0; x < 4; x++)
    {
      document.writeln ("<td><button type=\"button\" onClick=\"formate_ausgeben(", y * 4 + x,
                        ")\">", formate[1][y * 4 + x], "<\/button><\/td>");
    }
    document.writeln ("<\/tr>");
  }
  document.writeln ("<\/table>");
}

smilies = new Array (2);
smilies[0] = new Array (":-)", ";-)", ":-D", ":-(", ":-P", "B-)", ":-\/",
                        ":-|", ":o)", "P-)");

smilies[1] = new Array ("smile", "wink", "biggrin", "frown",
                        "tongue", "glasses", "wrygrin",
                        "neutral", "bignose", "pirate");

function smilie_ausgeben (typ)
{
  _einfuegen (smilies[0][typ], 3);
}

function tabelle_smilies_erstellen ()
{
  document.writeln ("<\/td><td>");
  document.writeln ("<table>");
  for (y = 0; y < 5; y++)
  {
    document.writeln ("<tr>");
    for (x = 0; x < 2; x++)
    {
      document.writeln ("<td><button type=\"button\" onClick=\"smilie_ausgeben(", y * 2 + x,
                        ")\"><img src=\"smileys/", smilies[1][y * 2 + x], ".png\" alt=\"",
	                smilies[0][y * 2 + x], "\"><\/button><\/td>");
    }
    document.writeln ("<\/tr>");
  }
  document.writeln ("<\/table>");
}
