function myFunction() {
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