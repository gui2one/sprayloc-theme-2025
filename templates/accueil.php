<?php
/*
Template Name: accueil
*/

get_header();

?>

<div id="intro">

    <h1>Location de matériel vidéo tournage à Rennes / Nantes / Brest<br> et tout le Grand-Ouest.</h1>

    <p>SprayLoc propose la location de matériel vidéo pour vos tournage. <br />Lumières, caméras, objectifs, machinerie,
        stabilisateurs, prise de son ...
    </p>
</div>


<div class="separator"></div>

<div id="boxes">
    <a href="materiels?category=Caméras">
        <div class="box">
            <img src="wp-content/uploads/2022/11/red_montee_4541_cropped_small.jpg" alt="Caméra Red" class="bg_image">
            <h2 class="title">Caméras</h2>
            <div class="content">Venez voir nos Caméras Professionelles</div>
        </div>
    </a>
    <a href="materiels?category=Machinerie">
        <div class="box">
            <img src="wp-content/uploads/2022/11/21777_rm4_sprayfilm_691_TiltaSuctionDiscCradleHeadwithV-MountPlate2_OPTIM.jpg"
                alt="Machinerie camera" class="bg_image">
            <h2 class="title">Machinerie</h2>
            <div class="content">pieds, accessoires, gripperie ...</div>
        </div>
    </a>
    <a href="materiels?category=Lumières">
        <div class="box">
            <img src="wp-content/uploads/2023/04/21777_rm4_sprayfilm_431_skypanel120__cropped.jpg" alt="Caméra Red"
                class="bg_image">
            <h2 class="title">Lumières</h2>
            <div class="content">... et que la lumière soit !</div>
        </div>
    </a>
</div>

<?php get_footer(); ?>