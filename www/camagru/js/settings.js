var tab = document.getElementsByClassName('tab'),
  editPic = document.getElementById('edit-profile-pic'),
  modal = document.getElementById('snapshot'),
  chk_bx =document.getElementById('chk_bx'),
  tabContent = document.getElementsByClassName('tab-content');

window.addEventListener('load', function(e){
 var act = document.getElementById('tab2show'),
  i;
 if(act.value)
  {
    for(i = 0; i < tab.length; i++)
        tabContent[i].style.display = 'none';
    document.getElementById(act.value).style.display = 'block';
  }
  chk_bx.addEventListener('change',function() {
    if(chk_bx.checked)
      chk_bx.value = "true";
  });
});

editPic.addEventListener('click',function(){

  modal.style.display = "block";
});


window.addEventListener('click', function(){
  if(event.target == modal)
    modal.style.display = "none";
});

document.getElementById('close').addEventListener('click', function(){
  modal.style.display = "none";
});
function changetab(e, showtab) {
  var i;
  for(i = 0; i < tab.length; i++) {
    tabContent[i].style.display = 'none';
    tab[i].classList.remove('tab-active');
  }
  document.getElementById(showtab).style.display = 'block';
  e.currentTarget.classList.add('tab-active');
}


