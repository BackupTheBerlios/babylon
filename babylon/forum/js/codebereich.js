/* Copyright 2005 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

function codebereich_init (sprachen)
{
  document.write ("<button type=\"button\" id=\"codebutton\" onClick=\"codebereich_erstellen('");
  document.write (sprachen);
  document.writeln ("')\">Code &gt;<\/button>");
  document.writeln ("<div id=\"codediv\" class=\"code-eingabe\"'><\/div>");
}

function codebereich_erstellen (sprachen)
{
  button = document.getElementById("codebutton").firstChild;
    // element syntax    type        id              elternid     attribute....                   abschluss
    // text syntax       textnode    elternid        text
  elements = new Array ('table',    'codetable',    'codediv',   'width', '100%', 'cellpadding', '6', 'border', '0', '',
                        'tr',       'codetr0',      'codetable', 'width', '100%', '',
                        'td',       'codetd0',      'codetr0',   '',
                        'td',       'codetd1',      'codetr0',   'width', '100%', '',
                        'textarea', 'codearea',     'codetd0',   'name', 'codearea', 'cols', '80', 'rows', '5', 'onSelect', 'code_selektion_aktualisieren()', '',
                        'textnode', 'codetd1',      'Sprache', 
                        'br',       'codebr0',      'codetd1',   '',
                        'select',   'codelang',     'codetd1',   'name', 'codelang', 'size', '1', '',
                        'p',        'codep0',       'codetd1',   '',
                        'button',   'submitbutton', 'codetd1',   '',
                        'textnode', 'submitbutton', 'einfuegen!',
                        'input',    'codezz',       'codetd1',   'type', 'hidden', 'name', 'codezz', 'value', '0,0', '');
  
  if (button.nodeValue == 'Code >')
  {
    var i = 0;
    var e;
    while (i < elements.length)
    {
      if (elements[i] == 'textnode')
      {
        e = document.createTextNode (elements[i+2]);
        document.getElementById(elements[i+1]).appendChild(e);
        i+=3;
      }
      else
      {
        e = document.createElement (elements[i++]);
        e.setAttribute('id', elements[i++])
        document.getElementById(elements[i++]).appendChild(e);
        while (elements[i] != '')
        {
          e.setAttribute(elements[i], elements[i+1]);
          i+=2;
        }
        i++;
      }
    }

    var s = sprachen.split(',');
    var l = s.length;
    for (var x = 0; x < l; x++)
    {
      e = document.createElement ('option');
      e.setAttribute('value', s[x]);
      var t = document.createTextNode (s[x]);
      e.appendChild(t);
      document.getElementById('codelang').appendChild(e);
    }
   
    button.nodeValue = "Code v";
  }
  else
  {
    var kind;
    while (kind = document.getElementById('codediv').firstChild)
      document.getElementById('codediv').removeChild(kind);
    button.nodeValue = "Code >";
  }
}

function code_selektion_aktualisieren()
{
  var codearea = document.getElementById("codearea");
  if (typeof codearea.selectionStart != 'undefined')
  {
    var start = codearea.value.slice(0, codearea.selectionStart).match(/\n/gi);
    var ende = codearea.value.slice(0, codearea.selectionEnd).match(/\n/gi);

    start = (!start) ? 1 : start.length + 1
    ende = (!ende) ? 1 : ende.length + 1

    document.getElementById("codezz").value = start + ',' + ende;
  } 
}

function textarea_zeile_aktualisieren()
{
  var textarea = document.getElementById("text");
  if (typeof textarea.selectionStart != 'undefined')
  {
    var num = textarea.value.slice(0, textarea.selectionStart).match(/\n/gi);
    num = (!num) ? 0 : num.length;
    document.getElementById("textarea_zeile").value = num;
  }
}
