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
  var tab = document.getElementsByClassName('tab'),
  tabContent = document.getElementsByClassName('tab-content');

/* Set first tab visible */
tab[0].classList.add('tab-active');
tabContent[0].style.display = 'block';

/* Change visible tab */
function changetab(e, showtab) {
  var i;
  for (i = 0; i < tab.length; i++) {
      tabContent[i].style.display = 'none';
      tab[i].classList.remove('tab-active');
  }
  document.getElementById(showtab).style.display = 'block';
  e.currentTarget.classList.add('tab-active');
}