<?php

include "save_rentman_data.php";


function remove_duplicate_files($files_array)
{
    $urls = array();
    foreach($files_array as $file)
    {
        if(in_array($file->url) == false){

            $urls.array_push($file->url);
        }
        print_r($file->url);
    }
}

function getEquipmentByID($id)
{

    $equipment = getEquipementsData();
    // $filtered = array_unique(array_filter($equipment, function($item){
    //     global $id;
    //     return $item->id == intval($id);
    // }), SORT_NUMERIC);

    foreach ($equipment as $key => $value) {
        if( $value->id == intval($id)){

            return $value;
        }
    }

    return null;

}

function getFilesByID($id)
{

    $files = getFilesData();
    $filtered_files = array_filter($files, function($item)use($id){
        return $item->item == $id;
    });
    

    return $filtered_files;
}

function getFolders()
{
    return getFoldersData();
}

function getFolderByID($id)
{
    $folders_data = getFolders();
    
    $result = null;
    foreach ($folders_data as $key => $folder) 
    {
    
        if( $folder->id == $id)
        {
            $result = $folder;
            break;
        }
    }
    return $result;
}

function getParentFolderID($folder_data)
{
	if($folder_data != null){
		if($folder_data->parent != null){
			return intval(explode("/folders/", $folder_data->parent)[1]);		
		}
		
	}
    return -1;
}

function getEquipmentFolders($equipment_id)
{
    
    $equip = getEquipmentByID($equipment_id);

    $equip_folder = explode("/folders/", $equip->folder)[1];

    $result = array();
    $last_folder = null;
    $folder_id = $equip_folder;
    
    $folders_data = getFolders();
    
    foreach ($folders_data as $key => $folder) 
    {
    
        if( $folder->id == intval($folder_id))
        {
            $last_folder = $folder;
            break;
        }
    }

    array_push($result, $last_folder);

    $parent = getFolderByID(getParentFolderID($last_folder));
    array_push($result, $parent);
    while($parent = getFolderByID(getParentFolderID($parent))){
        array_push($result, $parent);
    }
    return $result;

}

function breadCrums($equipment_id)
{
    $folders = getEquipmentFolders($equipment_id);
    $arr = array();

    foreach ($folders as $key => $folder) 
    {
        array_push($arr, $folder->displayname);
    }
    
    $str = "<div id='bread_crums'>";

    for ($i = count($arr)-1; $i >= 0; $i--) 
    {
        if($arr[$i] == "Équipement"){

            $name = "all"; 
        }else{
            
            $name = $arr[$i]; 
        }
        $str .= "<a href='materiels?category=$name'><span>$arr[$i]</span></a>";
        if( $i > 0){
            $str .= "<span> >> </span>";
        }
    }

    $str .= "</div>";
    return $str;
}

function isAKit($equip_id)
{
    $kits_data = getKitsData();

    for($i=0; $i < count($kits_data); $i++){
        
        
        $parent_equipment_id = explode("/equipment/", $kits_data[$i]->parent_equipment)[1];

        if($parent_equipment_id == $equip_id){

            return true;
        }
    }
    

    return false;
}

function listKitContent($equip_id)
{
    $result = array();
    $kits_data = getKitsData();
    for($i=0; $i < count($kits_data); $i++){
        
        
        $parent_equipment_id = explode("/equipment/", $kits_data[$i]->parent_equipment)[1];

        if($parent_equipment_id == $equip_id){
            $cur_id = explode("/equipment/", $kits_data[$i]->equipment)[1];
            array_push($result,  intval($cur_id));
        }
    }

    return $result;
}

function listKitContent2($equip_id)
{
    $html = "";
    $result = array();
    $kits_data = getKitsData();
    for($i=0; $i < count($kits_data); $i++){
        
        
        $parent_equipment_id = explode("/equipment/", $kits_data[$i]->parent_equipment)[1];

        if($parent_equipment_id == $equip_id)
        {
            $cur_id = explode("/equipment/", $kits_data[$i]->equipment)[1];
            $child_equip = getEquipmentByID(intval($cur_id));
            if( $child_equip != null)
            {
                $html .= "<li><a href='materiel?id=$child_equip->id'> $child_equip->displayname</a></li>";
                array_push($result,  intval($cur_id));
            }else{
                // var_dump($kits_data[$i]->displayname);
                $html .= "<li>".$kits_data[$i]->displayname."</li>";
            }
        }
    }

    return $html;
}