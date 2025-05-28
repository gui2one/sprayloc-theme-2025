<?php
$root_dir = preg_replace('/wp-content.*$/', '', __DIR__);
require_once($root_dir . "wp-load.php");
$theme_dir = plugin_dir_path(__FILE__);



$json_files = array(

    "equipment" => $theme_dir . "rentman_data/equipment.json",
    "folders" => $theme_dir . "rentman_data/folders.json",
    "kits" => $theme_dir . "rentman_data/kits.json",
    "files" => $theme_dir . "rentman_data/files.json"
);

function remove_duplicate_items($arr)
{
    $unique_ids = array();

    $duplicates = array();
    $inc = 0;
    foreach ($arr as $value) {

        $id = $value->id;

        if (in_array($id, $unique_ids) == false) {
            array_push($unique_ids, $id);
        } else {
            // echo 'duplicate : '.$inc ."<br>"; 
            array_push($duplicates, $inc);
        }

        $inc++;
    }

    $num_items = count($duplicates);
    for ($i = $num_items - 1; $i >= 0; $i--) {
        $dup = $duplicates[$i];
        unset($arr[$dup]);
    }
    return $arr;
}

function needToDownloadFiles()
{
    global $json_files;

    $result = false;
    foreach ($json_files as $file) {
        if (file_exists($file)) {

            // print_r("file exists !!!!!");
            $file_time = filemtime($file);
            $diff = time() - $file_time;
            $now = date("");
            $file_date = date("", $file_time);
            if ($diff > 60 * 30) {
                // echo "File is too old ".$file."<br>";
                $result = true;
                break;
            }
        } else {
            $result = true;
        }
    }

    return $result;
}

// $handles = array();
function init_curl_request($url)
{
    // global $handles;
    $ch = curl_init();

    curl_setopt_array($ch, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Content-type: application/json',
            'Authorization: Bearer ' . get_option('sprayloc_plugin_admin')["rentman_api_key"],
            'Content-length: 0',
        ),
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_SSL_VERIFYPEER => 1
    ));

    // array_push($handles, $ch);
    return $ch;
}

$full_data["equipment"] = array();
$full_data["files"] = array();
$full_data["folders"] = array();
$full_data["kits"] = array();


function make_all_requests()
{
    global $full_data;

    $files_offset = 0;
    $files_limit = 300;
    $ch_equipment = init_curl_request("https://api.rentman.net/equipment?in_archive[isnull]=true&in_shop[isnull]=false");
    $handles["equipment"] = $ch_equipment;

    $ch_files = init_curl_request("https://api.rentman.net/files?image[isnull]=false&limit=$files_limit");
    $handles["files"] = $ch_files;

    $ch_folders = init_curl_request("https://api.rentman.net/folders");
    $handles["folders"] = $ch_folders;

    $ch_kits = init_curl_request("https://api.rentman.net/equipmentsetscontent");
    $handles["kits"] = $ch_kits;

    $attempts = 0;
    $max_attempts = 15;

    while (count($handles) > 0 && $attempts < $max_attempts) {

        // echo "--> Attempts : ".$attempts."<br>";


        $mh = curl_multi_init();
        foreach ($handles as $key => $handle) {
            curl_multi_add_handle($mh, $handle);
        }



        $messages = -1;
        do {
            $status = curl_multi_exec($mh, $active);
            $infos = curl_multi_info_read($mh, $messages);
            if ($active) {
                curl_multi_select($mh);
            } else {
                // printf("cUrl error (#%d): %s<br>\n",
                // curl_errno($handles["equipment"]),
                // htmlspecialchars(curl_error($handles["equipment"])));
            }
        } while ($active && $status == CURLM_OK);

        $handles_to_remove = [];
        foreach ($handles as $key => $handle) {
            $data = json_decode(curl_multi_getcontent($handle));
            // var_dump($data);
            // die();
            if ($data) {

                $data->data = array_filter($data->data, function ($value) {
                    if (property_exists($value, "in_archive")) {
                        return $value->in_archive == false;
                    } else {
                        return true;
                    }

                    return false;
                });
                $full_data[$key] = array_merge($full_data[$key], $data->data);
                // echo  "Key :".$key."<br>";
                // echo "items count : ".$data->itemCount ."<br>";
                // echo "Limit : ".$data->limit ."<br>";
                if ($data->itemCount < $data->limit) {

                    /// got everything , remove handle"
                    $handles_to_remove[] = $handle;
                    curl_multi_remove_handle($mh, $handle);
                }
            }
        }

        //remove finished handles from $handles array
        foreach ($handles_to_remove as $to_remove) {
            $index = array_search($to_remove, $handles);

            if ($index != false) {
                // remove handle from array
                unset($handles[$index]);
            }
        }

        // redo needed requests

        if (count($handles) > 0) {

            // echo "remake needed requests ...<br>";
            foreach ($handles as $key => $handle) {
                switch ($key) {
                    case "equipment":
                        curl_setopt($handle, CURLOPT_VERBOSE, true);

                        // $streamVerboseHandle = fopen('php://temp', 'w+');
                        // curl_setopt($handle, CURLOPT_STDERR, $streamVerboseHandle);
                        curl_setopt($handle, CURLOPT_URL, "https://api.rentman.net/equipment?in_archive[isnull]=true&in_shop[isnull]=false&offset=300");
                        break;
                    case "files":
                        curl_setopt($handle, CURLOPT_URL, "https://api.rentman.net/files?image[isnull]=false&limit=$files_limit&offset=$files_offset");
                        $files_offset += $files_limit;
                        break;
                    case "folders":
                        curl_setopt($handle, CURLOPT_URL, "https://api.rentman.net/folders?offset=300");
                        break;
                    case "kits":
                        curl_setopt($handle, CURLOPT_URL, "https://api.rentman.net/equipmentsetscontent?offset=300");
                        break;
                    default:
                        break;
                }
            }
        }


        $attempts++;
    }
}


function getEquipementsData()
{
    global $json_files;


    return json_decode(file_get_contents($json_files["equipment"]));
}
function getFoldersData()
{
    global $json_files;
    // if( needToDownloadFiles())
    // {
    //     downloadAllData();
    // }

    return json_decode(file_get_contents($json_files["folders"]));
}
function getFilesData()
{
    global $json_files;

    $arr = json_decode(file_get_contents($json_files["files"]));
    // return remove_duplicate_items_2($arr);
    return $arr;
}

function getKitsData()
{
    global $json_files;

    return json_decode(file_get_contents($json_files["kits"]));
}

function downloadAllData()
{
    global $full_data;
    global $json_files;
    make_all_requests();
    $filtered_equipments = array_values(array_unique(array_filter($full_data["equipment"], function ($item) {
        return $item->in_archive == false;
    }), SORT_REGULAR));

    /* filter folders that are not equipment type */
    $filtered_folders = array_values(array_unique(array_filter($full_data["folders"], function ($item) {
        return $item->itemtype == 'equipment';
    }), SORT_REGULAR));
    /* filter files that are not image type */
    $filtered_files = array_values(array_unique(array_filter($full_data["files"], function ($item) {
        return $item->image != false;
    }), SORT_REGULAR));

    $filtered_files = array_values(remove_duplicate_items($filtered_files));
    if (file_exists($json_files["files"])) {

        unlink($json_files["files"]);
    }
    file_put_contents($json_files["files"], json_encode($filtered_files, JSON_PRETTY_PRINT));

    if (file_exists($json_files["equipment"])) {

        unlink($json_files["equipment"]);
    }
    file_put_contents($json_files["equipment"], json_encode($filtered_equipments, JSON_PRETTY_PRINT));

    if (file_exists($json_files["folders"])) {

        unlink($json_files["folders"]);
    }
    file_put_contents($json_files["folders"], json_encode($filtered_folders, JSON_PRETTY_PRINT));

    if (file_exists($json_files["kits"])) {

        unlink($json_files["kits"]);
    }
    file_put_contents($json_files["kits"], json_encode($full_data["kits"], JSON_PRETTY_PRINT));
}
if (needToDownloadFiles()) {
    downloadAllData();
    // echo "Downloaded fresh data ...";

}
