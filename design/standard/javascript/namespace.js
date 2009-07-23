// Namespacing using technique from http://weblogs.asp.net/mschwarz/archive/2005/08/26/423699.aspx
function registerNS(ns)
{
 var nsParts = ns.split(".");
 var root = window;

 for(var i=0; i<nsParts.length; i++)
 {
  if(typeof root[nsParts[i]] == "undefined")
   root[nsParts[i]] = new Object();

  root = root[nsParts[i]];
 }
}
