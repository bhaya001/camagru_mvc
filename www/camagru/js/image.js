var comment = document.getElementById("comment"),
    list = document.getElementById("commentList"),
    dataComment = "",
    ajax = "",
    postComment = document.getElementById("postBtn"),
    editComment = document.getElementById("editBtn"),
    imgPost = document.getElementById("image"),
    commentElement = document.getElementsByClassName("commentElement"),
    popup = document.getElementsByClassName("popup-action"),
    actionEdit = document.getElementsByClassName("action-left"),
    actionDelete = document.getElementsByClassName("action-right"),
    commentValue = document.getElementsByClassName("comment-value"),
    folder = window.location.pathname.split("/"),
    actions = document.getElementsByClassName("actions"),
    like = document.getElementById("like");

function likesZone() {
    if(like.dataset.liked == "1")
    {
        like.classList.remove('far');
        like.classList.add('fas');
        like.style.color = "red";
    }
    else
    {
        like.classList.remove('fas');
        like.classList.add('far');
        like.style.color = "black";
    }
    if(list.dataset.count == "0")
        document.getElementById('row-').style.display="block";
}

document.onload = likesZone();

window.addEventListener('click', function(e){
    if(e.target !== actions)
    {
        var popup = document.getElementsByClassName("popup-action");
        for(var i = 0; i < popup.length; i++)
        {
            if(popup[i].classList.contains("show"))
                popup[i].classList.remove("show");
        }
    }
});

function openpopup(pos,ev) {
    var popup = document.getElementsByClassName("popup-action");
    for(var i = 0; i < popup.length; i++)
    {
        if(popup[i].classList.contains("show") && (popup[i]!=document.getElementById("action-"+pos)))
            popup[i].classList.toggle("show");
    }
    document.getElementById("action-"+pos).classList.toggle("show");
    ev.stopPropagation();

}

function deleteCmt(idCmt,pos) {
    var dataComment ={commentId:idCmt, csrf:document.getElementById('csrf').value};
    var dataString = "data="+JSON.stringify(dataComment);
    ajax = new XMLHttpRequest();
    ajax.open("POST","/"+folder[1]+"/comments/delete", true);
    ajax.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    ajax.onreadystatechange = function(){
        if((this.readyState === 4 && this.status === 200))
        {
            var response = JSON.parse(this.responseText);
            var message = document.createElement("div");
            message.setAttribute('id',"flash");
            if(response.csrf)
            {
                document.getElementById("row-"+pos).remove();
                var i,j=0;
                for (i = 0; i < commentElement.length; i++) {
                    commentElement[i].setAttribute('id','row-'+i);
                    if(commentElement[i].querySelector('.comment-action'))
                    {
                        popup[j].setAttribute('id','action-'+i);
                        actions[j].setAttribute('data-pos', i);
                        actions[j].setAttribute('onclick', 'openpopup('+i+',event)');
                        actionEdit[j].setAttribute('onclick',"editBtn("+popup[j].dataset.id+",'"+commentValue[i].innerHTML+"',"+i+")");
                        actionDelete[j].setAttribute('onclick',"deleteCmt("+popup[j].dataset.id+","+i+")");
                        j++;
                    }
                    
                }
                list.dataset.count=i;
                if(list.dataset.count == "0")
                    document.getElementById('row-').style.display = "block";
                document.getElementById('csrf').value = response.csrf;
                message.setAttribute('class',"flash success");
            }
            else
            {
                message.setAttribute('class',"flash error");
            }
            message.innerHTML = response.message;
            document.getElementById("main").insertBefore(message,document.getElementById("main").firstChild);
            window.setTimeout(fadeout, 5000);
            
        }
    }
    ajax.send(dataString);
}

function editBtn(idCmt,cmt,pos) {
    comment.value = cmt;
    comment.focus();
    postComment.style.display = 'none';
    editComment.style.display = 'block';
    editComment.style.opacity = '0.5';
    editComment.style.cursor = 'default';
    document.getElementById("cancelEdit").style.display = 'block';
    document.getElementById("cancelEdit").style.opacity = '1';
    document.getElementById("cancelEdit").style.cursor = 'pointer';
    editComment.dataset.id = idCmt;
    editComment.dataset.pos = pos;
    var actions = document.getElementsByClassName("actions");
    for (let index = 0; index < actions.length; index++) {
        actions[index].removeAttribute("onclick");
        actions[index].style.opacity="0.5";
        actions[index].style.cursor="default";
        if(document.getElementsByClassName("popup-action")[index].classList.contains("show"))
            document.getElementsByClassName("popup-action")[index].classList.toggle("show");   
    }
}
function sendNotification(idImg,post) {
    data = {imageId:idImg,post:post};
    dataString = "data="+JSON.stringify(data);
    var najax = new XMLHttpRequest();
    najax.open("POST","/"+folder[1]+"/comments/notification", true);
    najax.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    najax.send(dataString);
}

document.getElementById("cancelEdit").addEventListener('click',function(){
    postComment.style.display = 'block';
    postComment.style.opacity = '0.5';
    postComment.style.cursor = 'default';
    editComment.style.display = 'none';
    document.getElementById("cancelEdit").style.display = 'none';
    comment.value = "";
    var actions = document.getElementsByClassName("actions");
    for (let index = 0; index < actions.length; index++) {
        actions[index].setAttribute("onclick","openpopup("+index+",event)");
        actions[index].style.opacity="1";
        actions[index].style.cursor="pointer";
        
    }
});

postComment.addEventListener('click', function(){

    if (comment.value) {
        var img = window.location.href.substring(window.location.href.lastIndexOf('/') + 1);
        var pos = parseInt(list.dataset.count);
        var dataComment ={imageId:img,comment:comment.value, csrf:document.getElementById('csrf').value};
        var dataString = "data="+JSON.stringify(dataComment);
        ajax = new XMLHttpRequest();
        ajax.open("POST","/"+folder[1]+"/comments/add", true);
        ajax.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        ajax.onreadystatechange = function(){
            if((this.readyState === 4 && this.status === 200))
            {
                var response = JSON.parse(this.responseText);
                var message = document.createElement("div");
                if(!(response.message))
                {
                    
                    if(document.getElementById('row-'))
                        document.getElementById('row-').style.display = "none";
                    var response = JSON.parse(this.responseText);
                    var on = new Date(response.time.date);
                    var node = document.createElement("LI");
                    node.setAttribute('id','row-'+pos);
                    node.setAttribute('class','commentElement');
                    var profile = (response.profile) ? "<img src='"+response.profile+"'/>" : "<i class='fas fa-user-circle'></i>"
                    node.innerHTML = "<div class='commenterImage'>"+profile+"</div><div class='commentText'><b>"+response.user+"</b> <p class='comment-value'>"+response.comment+"</p>\
                        <span class='date sub-text'>On "+on.toLocaleString('en-US',{dateStyle:'medium',timeStyle:'short',timeZoneName:'short',hour12:'false'})+"\
                        </span></div><p class='comment-action'><i class='fas fa-ellipsis-h actions' onclick='openpopup("+pos+",event)'><span id='action-"+pos+"' class='popup-action' data-id='"+response.id_comment+"'>\
                        <a class='action-left' onclick='editBtn("+response.id_comment+",\""+response.comment+"\","+pos+")'><i class='fas fa-edit'></i> </a><a class='action-right' onclick='deleteCmt("+response.id_comment+","+pos+")'>\
                        <i class='fas fa-trash-alt'></i></a></span></i></p>";
                    list.dataset.count = pos+1;
                    comment.value = "";
                    list.appendChild(node);
                    postComment.style.cursor = "default";
                    postComment.style.opacity = "0.5";
                    node.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    document.getElementById('csrf').value = response.csrf;
                    sendNotification(img,1);
                }
                else
                {
                    message.setAttribute('id',"flash");
                    message.setAttribute('class',"flash error");
                    message.innerHTML = response.message;
                    document.getElementById("main").insertBefore(message,document.getElementById("main").firstChild);
                    window.setTimeout(fadeout, 5000);
                }
            }
        }
        ajax.send(dataString);
    }
});

editComment.addEventListener('click', function(){
    if(editComment.style.cursor == "pointer")
    {
        var idCmt = parseInt(editComment.dataset.id);
        var pos = parseInt(editComment.dataset.pos);
        if (comment.value) {
            var img = window.location.href.substring(window.location.href.lastIndexOf('/') + 1);
            var dataComment ={commentId:idCmt,comment:comment.value,imageId:img,csrf:document.getElementById('csrf').value};
            var dataString = "data="+JSON.stringify(dataComment);
            ajax = new XMLHttpRequest();
            ajax.open("POST","/"+folder[1]+"/comments/update/"+idCmt, true);
            ajax.setRequestHeader("Content-type","application/x-www-form-urlencoded");
            ajax.onreadystatechange = function(){
            if((this.readyState === 4 && this.status === 200))
                {
                    var response = JSON.parse(this.responseText);
                    var message = document.createElement("div");
                    if(!(response.message))
                    {
                        var on = new Date(response.time.date);
                        var newnode = document.createElement("LI");
                        var profile = (response.profile) ? "<img src='"+response.profile+"'/>" : "<i class='fas fa-user-circle'></i>"
                        newnode.innerHTML = "<div class='commenterImage'>"+profile+"</div><div class='commentText'><b>"+response.user+"</b> <p class='comment-value'>"+response.comment+"</p>\
                            <span class='date sub-text'>Edited on "+on.toLocaleString('en-US',{dateStyle:'medium',timeStyle:'short',timeZoneName:'short',hour12:'false'})+"\
                            </span></div><p class='comment-action'><i class='fas fa-ellipsis-h actions' data-pos='"+pos+"'><span id='action-"+pos+"' class='popup-action' data-id='"+idCmt+"'>\
                            <a class='action-left' onclick='editBtn("+idCmt+",\""+response.comment+"\","+pos+")'><i class='fas fa-edit'></i> </a><a class='action-right' onclick='deleteCmt("+idCmt+","+pos+")'>\
                            <i class='fas fa-trash-alt'></i></a></span></i></p>";
                        list.replaceChild(newnode,document.getElementsByClassName('commentElement')[pos]);
                        newnode.setAttribute('id','row-'+pos);
                        newnode.setAttribute('class','commentElement');
                        newnode.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        document.getElementById('csrf').value = response.csrf;
                        sendNotification(img,0); 
                    }
                    else
                    {
                        message.setAttribute('id',"flash");
                        message.setAttribute('class',"flash error");
                        message.innerHTML = response.message;
                        document.getElementById("main").insertBefore(message,document.getElementById("main").firstChild);
                        window.setTimeout(fadeout, 5000);
                    }
                    document.getElementById("cancelEdit").click();
                }
            }
            ajax.send(dataString);
        }
    }
});

comment.addEventListener("keyup", function(e){
        if(this.value.length > 0 && this.value.trim() != 0)
        {
            postComment.style.cursor = "pointer";
            postComment.style.opacity = "1";
            editComment.style.cursor = "pointer";
            editComment.style.opacity = "1";
            
        }
        else
        {
            postComment.style.cursor = "default";
            postComment.style.opacity = "0.5";
            editComment.style.cursor = "default";
            editComment.style.opacity = "0.5";
            

        }
        if(e.keyCode === 13)
        {
            if(postComment.style.display == "none")
                editComment.click();
            else
                postComment.click();
        }
        if(e.keyCode === 27)
        {
            if(postComment.style.display == "none")
                document.getElementById('cancelEdit').click();
        }
            
            
});

imgPost.addEventListener("dblclick", function() {
    like.click(); 
});

like.addEventListener('click', function(){
     var img = window.location.href.substring(window.location.href.lastIndexOf('/') + 1);

    var dataLike ={imageId:img};
    var dataString = "data="+JSON.stringify(dataLike);
    ajax = new XMLHttpRequest();
    if(this.dataset.liked == "0"){
        ajax.open("POST","/"+folder[1]+"/likes/add", true);
        ajax.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        ajax.onreadystatechange = function(){
            if((this.readyState === 4 && this.status === 200))
            {
                if(this.responseText)
                {
                    like.classList.remove('far');
                    like.classList.add('fas');
                    like.style.color = "red";
                    like.dataset.liked = "1";
                    if(like.dataset.likes== "0")
                        document.getElementById("likerString").innerHTML = "Liked by You";
                    else
                    {
                        document.getElementById("likerString").innerHTML = "liked by You And <span style='border-bottom:1px solid'>"+like.dataset.likes+" Others</span>";
                        document.getElementById('liker-card').removeAttribute('class');
                        document.getElementById('liker-card').classList.add("card-others");
                        
                    }
                }
                else
                {
                    var message = document.createElement("div");
                    message.setAttribute('id',"flash");
                    message.setAttribute('class',"flash error");
                    message.innerHTML = "There is an error please try later";
                    document.getElementById("main").insertBefore(message,document.getElementById("main").firstChild);
                    window.setTimeout(fadeout, 5000);
                }
            }
        }
    }
    else{
        ajax.open("POST","/"+folder[1]+"/likes/remove", true);
        ajax.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        ajax.onreadystatechange = function(){
            if((this.readyState === 4 && this.status === 200))
            {
                if(this.responseText)
                {
                    like.classList.remove('fas');
                    like.classList.add('far');
                    like.style.color = "black";
                    like.dataset.liked = "0";
                    if(like.dataset.likes=="0")
                        document.getElementById("likerString").innerHTML = "Liked by No one";
                    else
                    {
                        document.getElementById("likerString").innerHTML = "liked by <span style='border-bottom:1px solid'>"+like.dataset.likes+" Person</span>";
                        document.getElementById('liker-card').removeAttribute('class');
                        document.getElementById('liker-card').classList.add("card-person");
                    }
                }
            }
        }
        
    }

    ajax.send(dataString);
   
    

});

