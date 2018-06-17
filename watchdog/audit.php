<?php

$hashes = array_merge(get_hashes('../app/config'),get_hashes('../src/'));
array_push($hashes, md5_file('../app/AppKernel.php'),md5_file('../web/app.php'));
if (compare_hashes('saved_hashes', $hashes)) {
    file_put_contents('saved_hashes', $hashes);  //rewrite new hashes to file because there was no any differences
}
else {
    echo "Send mail to admin!";
}

function get_hashes($dir){
    $files = scandir($dir);
    static $hashes = array();
    unset($files[array_search('.', $files, true)]);
    unset($files[array_search('..', $files, true)]);
    foreach($files as $file){
        if(is_dir($dir.'/'.$file)) {
            get_hashes($dir . '/' . $file);
        }
        else{
            array_push($hashes, md5_file($dir.'/'.$file));
        }
    }
    return $hashes;
}

function compare_hashes($saved,$current){
    $old_hashes = str_split(file_get_contents($saved),32);
    if (empty(array_diff_assoc($old_hashes, $current))) {
        return true;
    }
    else{
        var_dump(array_diff_assoc($old_hashes, $current));
        return false;
    }
}