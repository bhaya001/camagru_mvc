window.onload = function() {
  window.setTimeout(fadeout, 5000);
}

function responsiveMenu() {
    var x = document.getElementById("myTopnav");
    var y = document.getElementById("menu");
    if(y.className === "navbar-right")
    {
        y.className += " responsive-menu animate-top";
    }else
      y.className = "navbar-right";

    if (x.className === "topnav") {
      x.className += " responsive";
    } else {
      x.className = "topnav";
    }
  }

function fadeout() {
  var flash = document.getElementById("flash");
  if(flash)
    flash.style.opacity = '0';
}