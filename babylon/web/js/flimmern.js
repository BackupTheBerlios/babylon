function flimmern()
{
  var a = Math.floor (Math.random() * 100);

  if (a < 10 && a != letzter)
    document.images['b' + a].src = '/web/grafik/links1' + a%5 + '.png';
  if (letzter != a && letzter != -1)
    document.images['b' + letzter].src = '/web/grafik/links0' + letzter%5 + '.png';

  if (a < 10)
    letzter = a;
  else
    letzter = -1;
}
