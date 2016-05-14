/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function domReady(f){/in/.test(document.readyState)?setTimeout('domReady('+f+')',9):f()}

function include(file) {
    var myScript =  document.createElement("script");
    myScript.src = file;
    myScript.type = "text/javascript";
    document.body.appendChild(myScript);
};


