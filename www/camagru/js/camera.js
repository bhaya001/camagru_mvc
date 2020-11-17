
var video = document.getElementById('cam'),
    canvas = document.getElementById('canvas'),
    pic = document.getElementById('pic-shot'),
    close = document.getElementById('close');
    filter = "0",
    feeling = "0",
    feelViewer = document.getElementById('feelings-viewer'),
    celebrateViewer = document.getElementById('celebration-viewer'),
    celebration = "0",
    streaming = false,
    file = document.getElementById("from-input")
    btn = document.getElementById('take'),
    filters = document.getElementById('filters'),
    feelings = document.getElementById('feelings'),
    celebrations = document.getElementById('celebrations'),
    input = document.getElementById("upload");
    form = document.getElementById('generate');
    modal = document.getElementById('snapshot'),
    csrf = document.getElementById('csrf'),
    upl = document.getElementById('upload'),
    result = document.createElement("img"),
    camagru = "" , //images rendered
    vflag = 0,// video stream flag
    flag = 0, // upload file flag
    feelingImg = "",
    folder = window.location.pathname.split("/"),
    celebrateImg = "",
    vendorUrl = window.URL || window.webkitURL;

navigator.mediaDevices.getUserMedia({video: true, audio: false})
    .then(function(stream){
        video.style.display = "block";
        video.srcObject = stream;
        video.play();
        vflag = 1;

    }).catch(function(err){
        document.getElementById('video-error').style.display="block";
        flag = 1
        document.getElementById('viewer').style.height = "auto";
        document.getElementById('attach').style.position = "static";
    });

btn.addEventListener('click', function(){
    context = canvas.getContext('2d')
    context.filter = filter;
    if(input.files && input.files[0])
        context.drawImage(file,0,0,400,300);
    else
        context.drawImage(video,0,0,400,300);
    var imgUrl = canvas.toDataURL('image/png');
    if(!vflag && !(input.files && input.files[0]))
        imgUrl = "";
    
    var postData = {pic:imgUrl,feeling:feelingImg,celebrate:celebrateImg,csrf:csrf.value};
    var dataString = "data="+JSON.stringify(postData);
    var ajax = new XMLHttpRequest();
    ajax.open("POST", form.getAttribute('action'), true);
    ajax.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    ajax.onreadystatechange = function() {
        if((this.readyState === 4 && this.status === 200))
        {
            var response = JSON.parse(this.responseText);
            if((feelingImg == "" && celebrateImg == "") || imgUrl == "" || response.message)
            {
                var message = document.createElement("div");
                message.setAttribute('id',"flash");
                message.setAttribute('class',"flash error");
                message.innerHTML = response.message;
                document.getElementById("main").insertBefore(message,document.getElementById("main").firstChild);
                window.setTimeout(fadeout, 5000);
            }
            else
            {
                var now = Date.now();
                camagru = response.camagru;
                result.setAttribute('src',camagru);
                result.style.display="block";
                document.getElementById("download").setAttribute('href',camagru);
                document.getElementById("download").setAttribute('download',"camagru_"+now+".png");
                csrf.value = response.csrf;
                form.appendChild(result);
            }
        }
    };
    ajax.send(dataString);
    modal.style.display = "block";
});

document.getElementById("set-profile").addEventListener('click', function(){
    dataProfile = {img:camagru,csrf:csrf.value}
    dataString = "data="+JSON.stringify(dataProfile);
    ajax = new XMLHttpRequest();
    ajax.open("POST","/"+folder[1]+"/users/changeProfile", true);
    ajax.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    ajax.onreadystatechange = function()
    {
        if((this.readyState === 4 && this.status === 200))
        {
            var message = document.createElement("div");
            var response = JSON.parse(this.responseText);
            message.setAttribute('id',"flash");
            if(response.image)
            {
                document.getElementById("menu-profile").remove();
                var img = document.createElement("img");
                img.setAttribute("id","menu-profile");
                img.setAttribute("src",response.image);
                csrf.value = response.csrf;
                document.getElementById("profile-content").insertBefore(img,document.getElementById("profile-content").firstChild);
                message.setAttribute('class',"flash success");
            }
            else
                message.setAttribute('class',"flash error");

            message.innerHTML = response.message;
            document.getElementById("main").insertBefore(message,document.getElementById("main").firstChild);
            window.setTimeout(fadeout, 5000);
            fun_close();
        }
    };
    ajax.send(dataString);
});

document.getElementById("save").addEventListener('click', function(){
    dataCamagru = "data="+camagru;
    ajax = new XMLHttpRequest();
    ajax.open("POST","/"+folder[1]+"/images/save", true);
    ajax.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    ajax.onreadystatechange = function()
    {
        if((this.readyState === 4 && this.status === 200))
        {
            var response = JSON.parse(this.responseText);
            if(!response.message)
            {
                var inserted = '<div id="image-'+response.id_image+'" class="img-box"><img src="'+response.path+'" alt="" />\
                                    <div class="transparent-box" onclick="showImage('+response.id_image+',event)">\
                                        <div class="cam-caption caption">\
                                            <p class="opacity-low">\
                                                0 <i class="far fa-heart"></i>\
                                                0 <i class="far fa-comment"></i>\
                                                <i class="fas fa-trash-alt" style="margin-left: 30px;" onclick="openpopup('+response.id_image+',event)">\
                                                    <span id="action-'+response.id_image+'" class="popup-action">\
                                                        Confirmation?\
                                                        <a class="cam-action" >\
                                                            <i class="fas fa-check-circle" onclick="deleteImage('+response.id_image+',event);"></i>\
                                                            <i class="fas fa-times"></i>\
                                                        </a>\
                                                    </span>\
                                                </i>\
                                            </p>\
                                        </div>\
                                    </div>\
                                </div>'
                var thumbnail = document.getElementById('thumbnail');
                if(document.getElementById('no-pic'))
                    document.getElementById('no-pic').style.display = "none";
                thumbnail.insertAdjacentHTML('afterbegin', inserted);
                fun_close();
            }
            else
            {
                var message = document.createElement("div");
                message.setAttribute('id',"flash");
                message.setAttribute('class',"flash error");
                message.innerHTML = response.message;
                document.getElementById("main").insertBefore(message,document.getElementById("main").firstChild);
                window.setTimeout(fadeout, 5000);
            }
        }
    };
    ajax.send(dataCamagru);
});
window.addEventListener('click', function(){
    if (event.target == modal) {
        fun_close();
      }
});
filters.addEventListener('change',function(e){
    filter = e.target.value;
    if(vflag)
        video.style.filter = filter;
    if(file)
        file.style.filter = filter;
});
close.addEventListener('click', function(){
    fun_close();
});

feelings.addEventListener('change', function(e){
    feeling = e.target.value;
    switch(feeling)
    {
        case "0":
            feelingImg = '';
            feelViewer.style.display = "none";
            break;
        case "1":
            feelingImg = 'angry';
            break;
        case "2":
            feelingImg = 'crazy';
            break;
        case "3":
            feelingImg = 'crying';
            break;
        case "4":
            feelingImg = 'happy';
            break;
        case "5":
            feelingImg = 'sad';
            break;
        case "6":
            feelingImg = 'sunglasses';
            break;
        case "7":
            feelingImg = 'surprised';
            break;
    }
    if((feeling != "0" || celebration != "0") && !flag)
    {
        btn.disabled = false;
        if(feeling != "0")
        {
            feelViewer.style.display = "block";
            feelViewer.src = location.origin+"/"+folder[1]+"/filters/"+feelingImg+".png";
        }
    }
    else if((feeling != "0" || celebration != "0") && flag)
        btn.disabled = true;
    else
    {
        feelViewer.style.display = "none";
        feelingImg = "";
        btn.disabled = true;
        feelViewer.src = "#";
    }
});

celebrations.addEventListener('change', function(e){
    celebration = e.target.value;
    
    switch(celebration)
    {
        case "0":
            celebrateImg = '';
            celebrateViewer.style.display = "none";
            break;
        case "1":
            celebrateImg = 'birthday';
            break;
        case "2":
            celebrateImg = 'celebration';
            break;
        case "3":
            celebrateImg = 'ramadane';
            break;
        case "4":
            celebrateImg = 'frame2';
            break;
        case "5":
            celebrateImg = 'frame3';
            break;
    }
    if((feeling != "0" || celebration != "0") && !flag)
    {
        btn.disabled = false;
        if(celebration != "0")
        {
            celebrateViewer.style.display = "block";
            celebrateViewer.src = location.origin+"/"+folder[1]+"/filters/"+celebrateImg+".png";
        }

    }
    else if((feeling != "0" || celebration != "0") && flag)
        btn.disabled = true;
    else
    {
        celebrateViewer.style.display = "none";
        celebrateImg = "";
        celebrateViewer.src = "#";
        btn.disabled = true;
    }
});
function readURL(inp) {
    if (inp.files && inp.files[0])
    { 
        var reader = new FileReader();
        reader.onload = function (e) {
            var ext = inp.value.substring(inp.value.lastIndexOf('.') + 1).toLowerCase();
            video.style.display = "none";
            if (ext != "gif" && ext != "png" && ext != "bmp" && ext != "jpeg" && ext != "jpg")
            {
                if(vflag)
                    video.style.display = "block";
                else
                    flag = 1;
                file.style.display = "none";
                var message = document.createElement("div");
                message.setAttribute('id',"flash");
                message.setAttribute('class',"flash error");
                message.innerHTML = "choose an image of type \"png\", \"jpg\" or \"jpeg\"";
                document.getElementById("main").insertBefore(message,document.getElementById("main").firstChild);
                window.setTimeout(fadeout, 5000);
                input.value = null;
            }
            else
            {
                var message = document.createElement("div");
                message.setAttribute('id',"flash");
                message.setAttribute('class',"flash error");
                var imgTemp = new Image();
                imgTemp.onload = function() {
                    file.setAttribute('src', e.target.result);
                    if(parseInt(imgTemp.width) >= 400)
                    {

                        file.style.display = "block";
                        console.log(celebration);
                        flag = 0;
                        if((feeling != "0" || celebration != "0") && !flag)
                        {
                            btn.disabled = false;
                            if(feeling != "0")
                            {
                                feelViewer.style.display = "block";
                                feelViewer.src = location.origin+"/"+folder[1]+"/filters/"+feelingImg+".png";
                            }
                            if(celebration != "0")
                            {
                                celebrateViewer.style.display = "block";
                                celebrateViewer.src = location.origin+"/"+folder[1]+"/filters/"+celebrateImg+".png";
                            }
                        }
                    }
                    else
                        imgTemp.onerror();
                };
                imgTemp.onerror = function() {
                    if(vflag)
                        video.style.display = "block";
                    else
                        flag = 1;
                    file.style.display = "none";
                    message.innerHTML = "Image too small please choose another one";
                    document.getElementById("main").insertBefore(message,document.getElementById("main").firstChild);
                    window.setTimeout(fadeout, 5000);
                    input.value = null;
                    file.setAttribute('src', '#');
                };
                imgTemp.src = URL.createObjectURL(inp.files[0]);
            }
                
        };
      reader.readAsDataURL(inp.files[0]);
      if(!vflag)
        {
            document.getElementById('attach').style.position = "absolute";
            document.getElementById('video-error').style.display = "none";
        }
    }
    else
    {
        file.setAttribute('src', "");
        if(vflag)
            video.style.display = "block";
        else
        {
            document.getElementById('attach').style.position = "static";
            document.getElementById('video-error').style.display = "block";
            if((feeling != "0" || celebration != "0"))
            {
                if(feeling != "0")
                {
                    feelViewer.style.display = "none";
                    feelViewer.src = "#";
                }
                if(celebration != "0")
                {
                    celebrateViewer.style.display = "none";
                    celebrateViewer.src = "#";
                }
            }
        }
        flag = 1;
        file.style.display = "none";
        btn.disabled = true;
    }
  }

  function fun_close()
  {
    file.style.display = "none";
    modal.style.display = "none";
    input.value = null;
    context.clearRect(0, 0, canvas.width, canvas.height);
    if(vflag)
        video.style.display = "block";
    else
    {
        document.getElementById("attach").style.position = "static";
        document.getElementById('video-error').style.display="block";
        flag = 1;
    }
    video.style.filter = "none";
    btn.disabled = true;
    result.remove();
    feelViewer.style.display = "none";
    celebrateViewer.style.display = "none";
    feelViewer.src = "#";
    celebrateViewer.src = "#";
    feelings.selectedIndex = 0;
    celebrations.selectedIndex = 0;
    filters.selectedIndex = 0;
    feeling = "0";
    celebration = "0";
    filter = "none";
    feelingImg = "";
    celebrateImg = "";
  }

function deleteImage(id,ev) {
    var dataImage ={id:id, csrf:csrf.value};
    var dataString = "data="+JSON.stringify(dataImage);
    ajax = new XMLHttpRequest();
    ajax.open("POST","/"+folder[1]+"/images/delete/"+id, true);
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
                document.getElementById("image-"+id).remove();
                if(!document.getElementsByClassName("img-box").length)
                document.getElementById('no-pic').style.display = "block";
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
    ev.stopPropagation();
  }
  function showImage(id,ev) {
    var popup = document.getElementsByClassName("popup-action");
    var f = 0;
    for(var i = 0; i < popup.length; i++)
    {
        if(popup[i].classList.contains("show"))
        {
            popup[i].classList.remove("show");
            f = 1;
            break;
        }
    }
    if(!f)
        window.location.href = location.origin+"/"+folder[1]+"/images/show/"+id;
    ev.stopPropagation();
  }
  function openpopup(id,ev) {
    var popup = document.getElementsByClassName("popup-action");
    for(var i = 0; i < popup.length; i++)
    {
        if(popup[i].classList.contains("show") && (popup[i]!=document.getElementById("action-"+id)))
            popup[i].classList.toggle("show");
    }
    document.getElementById("action-"+id).classList.toggle("show");
    ev.stopPropagation();
}
window.addEventListener('click', function(e){
    var del = document.getElementsByClassName('fa-trash-alt');
    if(e.target !== del)
    {
        var popup = document.getElementsByClassName("popup-action");
        for(var i = 0; i < popup.length; i++)
        {
            if(popup[i].classList.contains("show"))
                popup[i].classList.remove("show");
        }
    }
});