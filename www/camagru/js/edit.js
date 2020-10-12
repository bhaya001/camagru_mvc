
var video = document.getElementById('cam'),
    canvas = document.getElementById('canvas'),
    pic = document.getElementById('pic-shot'),
    close = document.getElementById('close');
    filter = "0",
    feeling = "0",
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
    upl = document.getElementById('upload'),
    result = document.createElement("img"),
    message = document.createElement("div"),
    camagru = "" , //images rendered
    vflag = 0,// video stream flag
    flag = 0; // upload file flag
    vendorUrl = window.URL || window.webkitURL;

navigator.mediaDevices.getUserMedia({video: true, audio: false})
    .then(function(stream){
        video.style.display = "block";
        video.srcObject = stream;
        video.play();
        vflag = 1;

    }).catch(function(err){
        console.log("Error camera");
        flag = 1;
        document.getElementById('viewer').style.height = "auto";
        document.getElementById('attach').style.position = "static";
    });

btn.addEventListener('click', function(){
    context = canvas.getContext('2d')
    context.filter = filter;
    if(vflag)
        context.drawImage(video,0,0,400,300);
    if(input.files && input.files[0])
        context.drawImage(file,0,0,400,300);
    var imgUrl = canvas.toDataURL('image/png');
    if(!vflag && !(input.files && input.files[0]))
        imgUrl = "";
    var feelingImg = "";
    var celebrateImg = "";
    switch(feeling)
    {
        case "1":
            feelingImg = 'angry'
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
    switch(celebration)
    {
        case "1":
            celebrateImg = 'birthday';
            break;
        case "2":
            celebrateImg = 'celebration1';
            break;
        case "3":
            celebrateImg = 'celebration2';
            break;
        case "4":
            celebrateImg = 'ramadane';
            break;
    }
    var postData = {pic:imgUrl,feeling:feelingImg,celebrate:celebrateImg};
    var dataString = "data="+JSON.stringify(postData);
    var ajax = new XMLHttpRequest();
    ajax.open("POST", form.getAttribute('action'), true);
    ajax.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    ajax.onreadystatechange = function() {
        if((this.readyState === 4 && this.status === 200))
        {
            if((feelingImg == "" && celebrateImg == "") || imgUrl == "")
            {
                message.setAttribute('id',"flash");
                message.setAttribute('class',"flash error");
                message.innerHTML = this.responseText;
                form.appendChild(message);
            }
            else
            {
                var now = Date.now();
                camagru = this.responseText;
                result.setAttribute('src',camagru);
                result.style.display="block";
                document.getElementById("download").setAttribute('href',camagru);
                document.getElementById("download").setAttribute('download',"camagru_"+now+".png")
                form.appendChild(result);
            }
        }
    };
    ajax.send(dataString);
    modal.style.display = "block";
});

document.getElementById("set-profile").addEventListener('click', function(){
    dataProfile = "data="+camagru;
    ajax = new XMLHttpRequest();
    ajax.open("POST","/camagru/users/changeProfile", true);
    ajax.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    ajax.onreadystatechange = function()
    {
        if((this.readyState === 4 && this.status === 200))
        {
            document.getElementById("menu-profile").setAttribute('src',this.responseText);
            fun_close();
        }
    };
    ajax.send(dataProfile);
});

document.getElementById("save").addEventListener('click', function(){
    dataCamagru = "data="+camagru;
    ajax = new XMLHttpRequest();
    ajax.open("POST","/camagru/home/save", true);
    ajax.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    ajax.onreadystatechange = function()
    {
        if((this.readyState === 4 && this.status === 200))
        {
            var inserted = '<div class="img-box"><img src="'+this.responseText+'" alt="" /><div class="transparent-box"><div class="caption"><p class="opacity-low">1 <i class="fa fa-heart"></i></p></div></div></div>'
            var thumbnail = document.getElementById('thumbnail');
            if(document.getElementById('no-pic'))
                document.getElementById('no-pic').style.display = "none";
            thumbnail.insertAdjacentHTML('afterbegin', inserted);
            fun_close();
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
    video.style.filter = filter;
});
close.addEventListener('click', function(){
    fun_close();
});
feelings.addEventListener('change', function(e){
    feeling = e.target.value;
    
        if((feeling != "0"|| celebration != "0") && !flag)
        {
            btn.disabled = false;
            if(feeling != "0" && !(upl.files && upl.files[0]))
                document.getElementById('face').style.display = "block";
            else
                document.getElementById('face').style.display = "none";
        }
        else
        {
            btn.disabled = true;
            document.getElementById('face').style.display = "none";
        }
});

celebrations.addEventListener('change', function(e){
    celebration = e.target.value;
    
        if((feeling != "0"|| celebration != "0") && !flag)
            btn.disabled = false;
        else
            btn.disabled = true;
});
function readURL(inp) {
    if (inp.files && inp.files[0])
    { 
        flag = 0;
        var reader = new FileReader();
        reader.onload = function (e) {
        file.setAttribute('src', e.target.result);
        file.style.display = "block";
        video.style.display = "none";
        document.getElementById('face').style.display = "none";
        if(feeling != "0" || celebration != "0")
            btn.disabled = false;
        else
            btn.disabled = true;
            
      };
      reader.readAsDataURL(inp.files[0]);
    }
    else
    {
        flag = 1;
        file.setAttribute('src', "");
        if(vflag)
            video.style.display = "block";
        file.style.display = "none";
        btn.disabled = true;
    }
  }
  function fun_close()
  {
    file.style.display = "none";
    modal.style.display = "none";
    input.value = null;
    flag = 1;
    context.clearRect(0, 0, canvas.width, canvas.height);
    if(vflag)
        video.style.display = "block";
    else
        btn.disabled = true;
    result.remove();
    message.remove();
  }
