<?php

function addServicesToProvider($data, $userId)
{
    $model = new ProviderModel();

    foreach ($data["category_id"] as $key => $value) {
        $ProviderCategoryID = $model->insertProviderCategory($userId, $data, $key);

        $Skills = json_decode($data["skills"][$key]);
        foreach ($Skills as $key1 => $value1) {
            $SkillID = $model->insertSkill($value, $value1);

            $model->insertProviderSkills($ProviderCategoryID, $SkillID);
        }

        $Locations = json_decode($data["locations"][$key]);
        foreach ($Locations as $key2 => $value2) {
            echo $value2;
            $District = "";
            $City = "";

            if ($value2 == 'All Districts') {
                $District = "All";
                $City = "All";
            } else if (strpos($value2, "District") > -1) {
                $District = str_replace(" District", "", $value2);
            } else {
                $City = $value2;
            }

            $LocationID = $model->getLocationID($District, $City)[0]["Location_ID"];

            $model->insertLocation($ProviderCategoryID, $LocationID);
        }
    }

    return true;
}
