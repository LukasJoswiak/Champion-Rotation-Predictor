<?php

include_once('../keys.php');

if (isset($_POST['data'])) {
    $data = json_decode($_POST['data']);
    $key = $data->key;

    // make sure key matches in script
    if ($key === $week_key) {
        $path = $_SERVER['DOCUMENT_ROOT'];
        include_once($path . '/core/init.php');

        $champions = $data->champions;

        $championList = json_decode(file_get_contents("https://ddragon.leagueoflegends.com/cdn/6.24.1/data/en_US/champion.json"))->data;

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
