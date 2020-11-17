<?php require 'app/views/includes/header.php';

?>
    <div class="modal_content">
        <div class="modal-body">
            <div class="left">
                <img id="image" src="<?=PROOT.$data['image']->path?>">
            </div>
            <div class="right">
                <div class="detailBox">
                    <div class="titleBox">
                        <div class="commenterImage">
                            <?php if($data['image']->profile):?>
                                <img src="<?=PROOT.$data['image']->profile?>"/>
                            <?php else:?>
                                <i class="fas fa-user-circle"></i>
                            <?php endif;?>
                        </div>
                        <div class="commentText">
                            <b><?=$data['image']->name?></b>
                            <span class="date sub-text">On <?=$data['image_on']->format('M j, Y, g:i A')?></span>
                        </div>
                    </div>
                    <div class="actionBox">
                        <ul data-count="<?=($data['comments']) ? count($data['comments']) : 0 ?>" id="commentList" class="commentList">
                            <p id="row-">There is no comments right now</p>
                            <?php if($data['comments']):?>
                                <?php $i=0;foreach($data['comments'] as $value): 
                                    $on = new DateTime($value->updated_at);?>

                                    <li id="row-<?=$i?>" class="commentElement">
                                        <div class="commenterImage">
                                        <?php if($value->profile):?>
                                            <img src="<?=PROOT.$value->profile?>"/>
                                        <?php else:?>
                                            <i class="fas fa-user-circle"></i>
                                        <?php endif;?>
                                        </div>
                                        <div class="commentText">
                                            <b><?=$value->name?></b> <p class="comment-value"><?=$value->comment?></p><span class="date sub-text"><?=($value->created_at != $value->updated_at)? "Edited on" :"On"?> <?=$on->format('M j, Y, g:i A')?></span>
                                        </div>
                                        <?php if ($value->id_user == $_SESSION['user_id']):?>
                                                <p class="comment-action">
                                                    <i class="fas fa-ellipsis-h actions" data-pos="<?=$i?>" onclick="openpopup(<?=$i?>,event)">
                                                        <span id="action-<?=$i?>"class="popup-action" data-id="<?=$value->id_comment?>">
                                                            <a class="action-left" onclick="editBtn(<?=$value->id_comment?>,'<?=$value->comment?>',<?=$i?>)">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <a class="action-right" onclick="deleteCmt(<?=$value->id_comment?>,<?=$i?>)">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </a>
                                                        </span>
                                                    </i>
                                                </p>
                                        <?php elseif ($data['image']->publisher_id == $_SESSION['user_id']) :?>
                                                <p class="comment-action">
                                                    <i class="fas fa-ellipsis-h actions" data-pos="<?=$i?>" onclick="openpopup(<?=$i?>,event)">
                                                        <span id="action-<?=$i?>"class="popup-action" >
                                                            <a class="action" onclick="deleteCmt(<?=$value->id_comment?>,<?=$i?>)">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </a>
                                                        </span>
                                                    </i>
                                                </p>
                                        <?php endif;?>
                                    </li>
                                    <?php $i++;?>
                                <?php endforeach;?>
                            <?php endif;?>
                        </ul>
                    </div>
                    <div class="commentBox">
                        
                        <span class="card__infoicon"><i data-likes = "<?=$data['count_likes']?>" data-liked="<?=$data['is_liked']?>"  id="like" class="fa-heart"></i></span>
                        <div class="taskDescription">
                            <span id="likerString"><?=$data['string_like']?>
                            </span>
                            <div id="liker-card" class="<?=(($data['is_liked']) && $data['count_likes'] >= 1) ? "card-others" : "card-person" ?>" <?=($data['count_likes']) ? "style='display:block'" : ""?>>
                                <ul class="likerList">
                                    <?php if($data['likes']):?>
                                        <?php  $i=0;foreach($data['likes'] as $value): ?>
                                            <?php if($value->liker_id != $_SESSION['user_id']):?>
                                            <li>
                                                <div class="commenterImage">
                                                <?php if($value->profile):?>
                                                    <img src="<?=PROOT.$value->profile?>"/>
                                                <?php else:?>
                                                    <i class="fas fa-user-circle"></i>
                                                <?php endif;?>
                                                </div>
                                                <div class="commentText">
                                                    <b><?=$value->name?></b>
                                                    <span class="sub-text">©<?=$value->login?></span>
                                                </div>
                                                <a class="user-posts" href="<?=PROOT.'images/user/'.$value->id_user?>">See Posts</a>
                                            </li>
                                            <?php endif;?>
                                        <?php endforeach;?>
                                    <?php endif;?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="form-inline">
                        <div class="form-group">
                            <input type="text" id="comment" class="form-control" placeholder="Add your comment" autocomplete="off">
                            <input type="hidden" id="csrf" value="<?=$data['csrf']?>">
                        </div>
                        <div class="form-group">
                            <a id="postBtn">Post</a>
                            <a data-id="" data-pos="" id="editBtn">Edit</a>
                            <a id="cancelEdit">Cancel</a>  
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script src="<?=PROOT?>js/image.js"></script>
<?php require 'app/views/includes/footer.php';?>