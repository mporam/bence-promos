<div id="carousel" class="carousel slide" data-ride="carousel">

<?php /*    <div class="caption"><?php echo $data['sliderPromo']['title']; ?></div> */ ?>

    <!-- Wrapper for slides -->
    <div class="carousel-inner" role="listbox">

        <?php $first = true; ?>
        <?php foreach ($data['sliderPromos'] as $promo) { ?>
            <div class="item<?php echo($first ? ' active' : ''); ?>">
                <a href="/account/promotions/<?php echo $promo['id']; ?>/">
                    <img src="<?php echo $promo['thumb']; ?>" alt="<?php echo $promo['title']; ?>">
                    <div class="carousel-caption">
                        <?php echo $promo['title']; ?>
                    </div>
                </a>
            </div>
            <?php
            $first = false;
        } ?>
<?php /*
        <div class="item active">
            <a href="/account/promotions/18/"><img src="<?php echo $data['sliderPromo']['image1']; ?>"
                                                   alt="<?php echo $data['sliderPromo']['title']; ?>"></a>
        </div>
        <div class="item">
            <a href="/account/promotions/18/"><img src="<?php echo $data['sliderPromo']['image2']; ?>"
                                                   alt="<?php echo $data['sliderPromo']['title']; ?>"></a>
        </div>
        <div class="item">
            <a href="/account/promotions/18/"><img src="<?php echo $data['sliderPromo']['image3']; ?>"
                                                   alt="<?php echo $data['sliderPromo']['title']; ?>"></a>
        </div>
        <div class="item">
            <a href="/account/promotions/18/"><img src="<?php echo $data['sliderPromo']['image4']; ?>"
                                                   alt="<?php echo $data['sliderPromo']['title']; ?>"></a>
        </div>
        <div class="item">
            <a href="/account/promotions/18/"><img src="<?php echo $data['sliderPromo']['image5']; ?>"
                                                   alt="<?php echo $data['sliderPromo']['title']; ?>"></a>
        </div>
        <div class="item">
            <a href="/account/promotions/18/"><img src="<?php echo $data['sliderPromo']['image6']; ?>"
                                                   alt="<?php echo $data['sliderPromo']['title']; ?>"></a>
        </div>
 */ ?>
    </div>

    <!-- Controls -->
    <a class="left carousel-control" href="#carousel" role="button" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#carousel" role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>