<?php
/*
Template Name: rentman_materiel
*/

require_once("wp-load.php");
include get_template_directory()."/inc/rentman_functions.php";

$current_id = $_GET["id"];

$data  = getEquipmentByID(intval($current_id));
$files = getFilesByID(intval($current_id));
$folders = getEquipmentFolders($current_id);

$files = remove_duplicate_items($files);


//// change title content ... harsh !
ob_start();
get_header();
$header = ob_get_clean();
$header = preg_replace('#<title>(.*?)<\/title>#', "<title>Location $data->displayname</title>", $header);
echo $header;
////////

$breadCrums = breadCrums($current_id);


?>
<div id="materiel_wrapper">
    <?php echo ($breadCrums); ?>
    <h1 class="display_name">
        Location <?php echo $data->displayname ?>
    </h1>


    <div class="box external_remark">
        <strong>Description</strong> : <br><br>

		<?php
        if( $data->external_remark == ""){
            echo "Pas de description pour ce matériel";
        } else{

            echo $data->external_remark; 
        }
        
        ?>
    </div>


    <div id="materiel-images" class="">
        <?php foreach($files as $file){ ?>
        <div class="materiel-image">
            <img src="<?php echo $file->url ?>" alt="<?php echo $file->displayname ?>"/>
            <a class="lightbox-link" href="<?php echo $file->url ?>" data-lightbox="slider-content">

            </a>
        </div>

        <?php } ?>
    </div>

    <?php if(isAKit($current_id)) { ?>
    <div id="kit_content" class="box">

        <?php

        if( isAKit($current_id))
        {

            echo "Ce kit comprend : <br>";

            $kit_content = listKitContent2($current_id);

            echo $kit_content;
        }
        ?>

    </div> <!-- end kit_content -->
    <?php } ?>
</div> <!-- end materiel_wrapper -->


<?php

get_footer();