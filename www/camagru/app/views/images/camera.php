<?php require 'app/views/includes/header.php';?>
   
    <div class="contents">
        <div class="camera-left">
            <div id="viewer">
                <video id='cam' width="400" height="300" ></video>
                <img id="feelings-viewer" src="#">
                <img id="celebration-viewer" src="#">
                <img id="from-input" src="#">
                <label for="upload">
                    <img id="attach" class="btn" src="<?=PROOT?>fonts/attach.png" title="Upload Photo">
                    <p id="video-error"><i class="fas fa-exclamation-circle"></i> Check your camera, you can only upload photos!</p>
                </label>
                <input type="file" name="pic" id="upload" onchange="readURL(this);" accept="image/png, image/jpeg">
                <div id = "face"></div>
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
                    <option value="0">Stickers</option>
                    <option value="1">Birthday</option>
                    <option value="2">Independence Day</option>
                    <option value="3">Ramadane Kareem</option>
                    <option value="4">Wall Frame</option>
                    <option value="5">Poster Frame</option>
                    
                    
                </select>
            </span>
            
            
            
            <button id="take" class="snapshot" disabled><img class="btn-take" src="<?=PROOT?>fonts/capture.png" title="Take Photo"></button>
            <input type="hidden" id="csrf" value="<?=$data['csrf']?>">
            <canvas id="canvas" width="400" height="300"></canvas>
        </div>
        <div class="camera-right">
            <div id="thumbnail" class="gallery-image">
                <?php if(!$data['images']):?>
                    <p id="no-pic">There is no pictures in your account.</p>
                <?php else:?>
                    <p id="no-pic" class="hide">There is no pictures in your account.</p>
                <?php foreach($data['images'] as $value): ?>
                        <div id="image-<?=$value->id_image?>" class="img-box">
                            <img src="<?=PROOT.$value->path?>" alt="" />
                                <div class="transparent-box" onclick="showImage(<?=$value->id_image?>,event)">
                                    <div class="cam-caption caption">
                                        <p class="opacity-low">
                                            <?=$data['likes'][$value->id_image]?> <i class="far fa-heart"></i>
                                            <?=$data['comments'][$value->id_image]?> <i class="far fa-comment"></i> 
                                            <i class="fas fa-trash-alt" style="margin-left: 30px;" onclick="openpopup(<?=$value->id_image?>,event)">
                                                <span id="action-<?=$value->id_image?>" class="popup-action">
                                                    Confirmation?
                                                    <a class="cam-action" >
                                                        <i class="fas fa-check-circle" onclick="deleteImage(<?=$value->id_image?>,event);"></i>
                                                        <i class="fas fa-times"></i>
                                                    </a>
                                                </span>
                                            </i>
                                        </p>
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
            <form id="generate" action="<?=PROOT?>images/generate" method="post">
            </form>
        </div>
        <div class="modal-footer">
            <h3>Actions</h3>
            <img id="save" class="btn btn-right" src="<?=PROOT?>fonts/send.png" title="save Photo">
            <a id ="download" download="" href=""><img  class="btn btn-right" src="<?=PROOT?>fonts/download.png" title="Download Photo"></a>
            <img id="set-profile" class="btn btn-right" src="<?=PROOT?>fonts/profile.png" title="Make as Profile">
            
        </div>
    </div>
</div>
<script src="<?=PROOT?>js/camera.js"></script>
<?php require 'app/views/includes/footer.php';?>