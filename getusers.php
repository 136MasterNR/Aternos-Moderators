<?php

use JetBrains\PhpStorm\ArrayShape;

function dateCompare() : bool {
    $json = file_get_contents('cache.json');
    $decoded = json_decode($json);
    if ($decoded->day == date("d")) {
        echo "<script> console.info('Cache is up to date and being loaded. No further action required.') </script>";
        $result = true;
    } else {
        echo "<script> console.warn('Cache is outdated. Gathering new data and refreshing cache shortly..') </script>";
        $result = false;
    }
    return $result;
}

# If you do not use PhpStorm, please remove the "#[ArrayShape(["name" => "string", "avatar" => "string"])] " part.
#[ArrayShape(["name" => "string", "avatar" => "string"])] function getData(int $id) : array {
    $avatar = "https://aternos.gmbh/img/logo-blue.svg";
    $username = "API Failure";

    if (!file_exists('assets/scripts/keys.php')) {
        echo "<script> console.error('Could not fetch any data as no key file was provided on the server with the necessary discord API key..') </script>";
    } else {
        $key = include 'assets/scripts/keys.php';

        $json_options = [
            "http" => [
                "method" => "GET",
                "header" => "Authorization: Bot $key"
            ]
        ];

        $url = 'https://discordapp.com/api/users/';
        $url .= $id;

        $json_context = stream_context_create($json_options);

        $json_get = file_get_contents($url, false, $json_context);

        $json_decode = json_decode($json_get, true);

        if (!$json_decode['id']) {
            echo "<script> console.error('API callback failed. Key might be expired or not provided correctly.') </script>";
        } else {
            $username = $json_decode['username'];
            # $username .= "#";
            # $username .= $json_decode['discriminator'];
            # Uncomment if you want to add the discriminator

            if ($json_decode['avatar']) {
                $avatar = 'https://cdn.discordapp.com/avatars/'.$id.'/'.$json_decode['avatar'].'?size=2048';
            }
        }
    }
    return array(
        "name" => $username,
        "avatar" => $avatar
    );
}

function upDate() : string {
    if (getUser(682609654504882186, true) == "API failure") {
        $date = "00";
    } else {
        $date = date("d");
    }
    return $date;
}

if (!dateCompare()) {
    $array = array(
        "day" => upDate(),
        "team" => array(
            "josh" => getData(839237573595365406),
            "joa" => getData(117003078502187009),
            "amg" => getData(235428738748121088),
            "jay" => getData(912852147392102421),
            "alex" => getData(435534715831975938),
            "136" => getData(505669157401133076),
            "adr" => getData(603516627023036416),
            "ji" => getData(185604754947178496),
            "hee" => getData(370258256775479307),
            "tig" => getData(674610610662539274)
        )
    );

    $json = json_encode($array);
    file_put_contents("cache.json", json_encode([]));
    file_put_contents('cache.json', $json);
    echo "<script> console.info('Cache has been updated successfully.') </script>";

}

function getUser(string $user, bool $name) {
    $json = file_get_contents('cache.json');
    $decoding = json_decode($json);

    if ($name) {
        $returned = $decoding->team->$user->name;
        echo "<script> console.info('Retrieving user data for $returned') </script>";
    } else {
        $returned = $decoding->team->$user->avatar;
    }
    return $returned;
}