var tab = document.getElementsByClassName('tab'),
  modal = document.getElementById('snapshot'),
  chk_bx =document.getElementById('chk_bx'),
  folder = window.location.pathname.split("/"),
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

function changetab(e, showtab) {
  var i;
  for(i = 0; i < tab.length; i++) {
    tabContent[i].style.display = 'none';
    tab[i].classList.remove('tab-active');
  }
  document.getElementById(showtab).style.display = 'block';
  e.currentTarget.classList.add('tab-active');
}
function openpopup(ev)
{
    document.getElementById("action-profile").classList.toggle("show");
    ev.stopPropagation();
}

window.addEventListener('click', function(e){
    if(document.getElementById("action-profile").classList.contains("show"))
        document.getElementById("action-profile").classList.remove("show");
});
function editProfile(e){
  window.location.href = location.origin+"/"+folder[1]+"/images/camera";
  e.stopPropagation();
}
function deleteProfile(e){
    var dataString = "data="+document.getElementById('csrf').value;
    ajax = new XMLHttpRequest();
    ajax.open("POST","/"+folder[1]+"/users/deleteProfile/", true);
    ajax.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    ajax.onreadystatechange = function(){
        if((this.readyState === 4 && this.status === 200))
        {
            var response = JSON.parse(this.responseText);
            var message = document.createElement("div");
            message.setAttribute('id',"flash");
            if(response.csrf)
            {
                csrf.value = response.csrf;
                document.getElementById("menu-profile").remove();
                document.getElementById("img-box").remove();
                var i = document.createElement("i");
                var span = document.createElement("span");
                span.setAttribute('class',"profile-setting");
                span.innerHTML = `<i onclick="openpopup(event)" class="icon fas fa-user-circle">
                                    <span id="action-profile" class="popup-action">
                                      <i id="edit-profile" class="fas fa-edit" onclick="editProfile(event)"></i>
                                    </span>
                                  </i>`;
                document.getElementById("head").insertBefore(span,document.getElementById("head").firstChild);
                i.setAttribute('id','menuprofile');
                i.classList.add("fas","i-active","fa-user-circle");
                document.getElementById("profile-content").insertBefore(i,document.getElementById("profile-content").firstChild);
                message.setAttribute('class',"flash success");
                
            }
            else
                message.setAttribute('class',"flash error");
            message.innerHTML = response.message;
            document.getElementById("main").insertBefore(message,document.getElementById("main").firstChild);
            window.setTimeout(fadeout, 5000);
        }
    }
    ajax.send(dataString);
  
}

