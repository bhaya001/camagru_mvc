<?php require 'app/views/includes/header.php';?>
<?php (isset($_SESSION['reset_success'])) ? flashMessage('reset_success','success') : '';?>
<div id="thumbnail" class="gallery-image home">
<?php if(!$data['images']):?>
    <p id="no-pic">There is no pictures in your account.</p>
<?php else:?>
	
    <?php foreach($data['images'] as $value): ?>
        <div id="img-box" class="img-box">
            <img src="<?=PROOT.$value->path?>" alt="<?=$value->id_image?>"/>
            <a href="<?=PROOT.'images/show/'.$value->id_image?>">
                <div class="transparent-box">
                    <div class="caption">
                        <p class="opacity-low"><?=$data['likes'][$value->id_image]?> <i class="far fa-heart"></i><?=$data['comments'][$value->id_image]?> <i class="far fa-comment"></i></p>
                    </div>
                </div>
            </a> 
        </div>
    <?php endforeach;?>
<?php endif;?>
</div>
<?php if ($data['num_pages'] > 1): ?>
	<div class="pagination">	
		<ul class="ul-pagination">
			<?php if ($data['curr_page'] > 1): ?>
				<li class="prev"><a href="<?=PROOT.'images/'.$data['to'].'/'.($data['curr_page']-1)?>"><i class="fas fa-chevron-left"></i></a></li>
			<?php endif; ?>

			<?php if ($data['curr_page'] > 3): ?>
				<li class="start"><a href="<?=PROOT.'images/'.$data['to'].'/1'?>">1</a></li>
				<li class="dots">...</li>
			<?php endif; ?>

			<?php if ($data['curr_page']-2 > 0): ?><li class="page"><a href="<?=PROOT.'images/'.$data['to'].'/'.($data['curr_page']-2)?>"><?=$data['curr_page']-2?></a></li><?php endif; ?>
			<?php if ($data['curr_page']-1 > 0): ?><li class="page"><a href="<?=PROOT.'images/'.$data['to'].'/'.($data['curr_page']-1)?>"><?=$data['curr_page']-1?></a></li><?php endif; ?>

			<li class="currentpage"><a href="<?=PROOT.'images/'.$data['to'].'/'.$data['curr_page']?>"><?=$data['curr_page']?></a></li>

			<?php if ($data['curr_page']+1 < $data['num_pages']+1): ?><li class="page"><a href="<?=PROOT.'images/'.$data['to'].'/'.($data['curr_page']+1)?>"><?=$data['curr_page']+1?></a></li><?php endif; ?>
			<?php if ($data['curr_page']+2 < $data['num_pages']+1): ?><li class="page"><a href="<?=PROOT.'images/'.$data['to'].'/'.($data['curr_page']+2)?>"><?=$data['curr_page']+2?></a></li><?php endif; ?>

			<?php if ($data['curr_page'] < $data['num_pages']-2): ?>
				<li class="dots">...</li>
				<li class="end"><a href="<?=PROOT.'images/'.$data['to'].'/'.$data['num_pages']?>"><?=$data['num_pages']?></a></li>
			<?php endif; ?>

			<?php if ($data['curr_page'] < $data['num_pages']): ?>
				<li class="next"><a href="<?=PROOT.'images/'.$data['to'].'/'.($data['curr_page']+1)?>"><i class="fas fa-chevron-right"></i></a></li>
			<?php endif; ?>
		</ul>
	</div>
<?php endif; ?>

<?php require 'app/views/includes/footer.php';?>