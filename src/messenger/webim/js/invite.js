var style=document.createElement("style");document.getElementsByTagName("head")[0].appendChild(style);window.createPopup||(style.appendChild(document.createTextNode("")),style.setAttribute("type","text/css"));var sheet=document.styleSheets[document.styleSheets.length-1];if(window.createPopup)sheet.cssText=mibewInviteStyle;else{var node=document.createTextNode(mibewInviteStyle);style.appendChild(node)}
function mibewInviteOnResponse(a){var c=a.invitation.message,b=a.invitation.operator,d=a.invitation.avatar,a='<div id="mibewinvitationpopup"><div id="mibewinvitationclose"><a href="javascript:void(0);" onclick="mibewHideInvitation();">&times;</a></div>';b&&(a+='<h1 onclick="mibewOpenAgent();">'+b+"</h1>");d&&(a+='<img id="mibewinvitationavatar" src="'+d+'" title="'+b+'" alt="'+b+'" onclick="mibewOpenAgent();" />');a=a+('<p onclick="mibewOpenAgent();">'+c+"</p>")+'<div style="clear: both;"></div></div>'; if(c=document.getElementById("mibewinvitation"))c.innerHTML=a}
function mibewHideInvitation(){if(document.getElementById("mibewinvitationpopup"))document.getElementById("mibewinvitationpopup").style.display="none"}
function mibewOpenAgent(){document.getElementById("mibewAgentButton")&&(document.getElementById("mibewAgentButton").onclick(),mibewHideInvitation())};