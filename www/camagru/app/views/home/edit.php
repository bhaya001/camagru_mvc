<?php require 'app/views/includes/header.php';?>
<div class="main">
    <div class="box left">
        <div id = "face"></div>
        <div id="viewer">
            <video id='cam' width="400" height="300" src=""></video>
            <img id="from-input" src="#">
            <label for="upload"><img id="attach" class="btn" src="<?=PROOT?>fonts/attach.png" title="Upload Picture"></label>
            <input type="file" name="pic" id="upload" onchange="readURL(this);" accept="image/png, image/jpeg">
        </div>
        <span class="custom-dropdown">
            <select id="filters">
                <option value="0">Filters Style </option>
                <option value="none">Normal</option>
                <option value="grayscale(100%)">Grayscale</option>
                <option value="sepia(100%)">Sepia</option>
                <option value="invert(100%)">Invert</option>
                <option value="hue-rotate(90deg)">Hue</option>
                <option value="blur(5px)">Blur</option>
                <option value="contrast(200%)">Contrast</option>
            </select>
        </span>
        <span class="custom-dropdown">
            <select id="feelings">
                <option value="0">Feelings</option>
                <option value="1">Angry</option>
                <option value="2">Crazy</option>
                <option value="3">Cry</option>
                <option value="4">Happy</option>
                <option value="5">Sad</option>
                <option value="6">cool</option>
                <option value="7">Shock</option>
            </select>
        </span>
        <span class="custom-dropdown">
            <select id="celebrations">
                <option value="0">Celebrations</option>
                <option value="1">Birthday</option>
                <option value="2">Independence Day</option>
                <option value="3">Special Day</option>
                <option value="4">Ramadane Kareem</option>
            </select>
        </span>
        
        
        
        <button id="take" class="snapshot" disabled><img class="btn-take" src="<?=PROOT?>fonts/capture.png" title="Take Picture"></button>
        <canvas id="canvas" width="400" height="300"></canvas>
    </div>
    <div class="right">
        <div id="thumbnail" class="gallery-image">
            <?php if(!$data['images']):?>
                    <p id="no-pic">There is no pictures in your account.</p>
            <?php else:?>
            <?php foreach($data['images'] as $value): 
                ?>
                
                
                    <div class="img-box">
                        <img loading="lazy" src="<?=PROOT.$value->path?>" alt="" />
                            <div class="transparent-box">
                                <div class="caption">
                                    <p class="opacity-low">1 <i class="far fa-heart"></i></p>
                                </div>
                            </div> 
                    </div>
                
            <?php endforeach;?>
            <?php endif;?>
        </div>   
    </div>
    
</div>
<div id="snapshot" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span id="close">&times;</span>
            <h2>Snapshot</h2>
        </div>
        <div id="pic-shot" class="modal-body">
            <form id="generate" action="<?=PROOT?>/home/generate" method="post">
            </form>
        </div>
        <div class="modal-footer">
            <h3>Actions</h3>
            <img id="save" class="btn btn-right" src="<?=PROOT?>fonts/send.png" title="save picture">
            <a id ="download" download="" href=""><img  class="btn btn-right" src="<?=PROOT?>fonts/download.png" title="Download Picture"></a>
            <img id="set-profile" class="btn btn-right" src="<?=PROOT?>fonts/profile.png" title="Make as Profile">
            
        </div>
    </div>
</div>
<script src="<?=PROOT?>js/edit.js"></script>
<?php require 'app/views/includes/footer.php';?>