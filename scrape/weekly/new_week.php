<?php

include_once('../keys.php');

if (isset($_POST['data'])) {
    $data = json_decode($_POST['data']);
    $key = $data->key;
    $championList = $data->champion_data;

    // make sure key matches in script
    if ($key === $week_key && $championList) {
        $path = $_SERVER['DOCUMENT_ROOT'];
        include_once($path . '/core/init.php');

        $champions = $data->champions;

        $tuesday = date('Y-m-d', strtotime('tuesday', strtotime('tomorrow')));

        foreach ($champions as $champion) {
            foreach ($championList as $champ) {
                if ($champion == $champ->name) {
                    $id = $champ->key;
                    break;
                }
            }
            
            if ($insert->rotation_champion($id, $tuesday)) {
                echo "Inserted " . $id . " into database for date " . $tuesday . "\n";
            }
        }
    }
}

?>
