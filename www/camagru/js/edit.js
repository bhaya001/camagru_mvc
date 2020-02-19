
var video = document.getElementById('cam'),
    canvas = document.getElementById('canvas'),
    pic = document.getElementById('pic-shot'),
    close = document.getElementsByClassName("close")[0];
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
    vendorUrl = window.URL || window.webkitURL;

navigator.mediaDevices.getUserMedia({video: true, audio: false})
    .then(function(stream){
        video.style.display = "block";
        video.srcObject = stream;
        video.play();
    }).catch(function(err){
        console.log("Error camera");
    });

btn.addEventListener('click', function(){
    context = canvas.getContext('2d')
    context.filter = filter;
    console.log(input.files[0]);
    if(!(input.files && input.files[0]))
        context.drawImage(video,0,0,400,300);
    else
        context.drawImage(file,0,0,400,300);
    var imgUrl = canvas.toDataURL('image/png');
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
    var result = document.createElement("img");
    var ajax = new XMLHttpRequest();
    ajax.open("POST", form.getAttribute('action'), true);
    ajax.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    ajax.onreadystatechange = function() {
        if(this.readyState === 4 && this.status === 200)
        {
            var now = Date.now();
            result.setAttribute('src',this.responseText);
            result.style.display="block";
            document.getElementById("download").setAttribute('href',this.responseText);
            document.getElementById("download").setAttribute('download',"camagru_"+now+".png")
            form.appendChild(result);
        }
    };
    ajax.send(dataString);
    modal.style.display = "block";
    close.addEventListener('click', function(){
        video.style.display="block";
        file.style.display = "none";
        modal.style.display = "none";
        input.value = null;
        context.clearRect(0, 0, canvas.width, canvas.height);
        result.remove();
    });
    window.addEventListener('click', function(){
        if (event.target == modal) {
            video.style.display="block";
            file.style.display = "none";
            modal.style.display = "none";
            input.value = null;
            context.clearRect(0, 0, canvas.width, canvas.height);
            result.remove();
          }
    });
    console.log(input.value);
});

filters.addEventListener('change',function(e){
    filter = e.target.value;
    video.style.filter = filter;
    if(filter != "0" || feeling != "0"|| celebration != "0")
        btn.disabled = false;
    else
        btn.disabled = true;
});

feelings.addEventListener('change', function(e){
    feeling = e.target.value;
    if(filter != "0" || feeling != "0"|| celebration != "0")
    {
        btn.disabled = false;
        if(!(input.value))
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
    if(filter != "0" || feeling != "0"|| celebration != "0")
        btn.disabled = false;
    else
        btn.disabled = true;
});
function readURL(inp) {
    if (inp.files && inp.files[0]) {
      var reader = new FileReader();
      reader.onload = function (e) {
          file.setAttribute('src', e.target.result);
          file.style.display = "block";
          video.style.display = "none";
      };
      reader.readAsDataURL(inp.files[0]);
    }
    else
    {
        video.style.display = "block";
        file.style.display = "none";
    }
  }
