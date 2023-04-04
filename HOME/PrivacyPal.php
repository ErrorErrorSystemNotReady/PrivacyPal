<!DOCTYPE html>
<html>
<head>
    <title>Facebook Friend Checker</title>
    <style>
        /* Add your own custom CSS here */
    </style>
</head>
<body>
    <h1>Facebook Friend Checker</h1>
    <?php
    // Replace with your own access token
    $access_token = "YOUR_ACCESS_TOKEN";

    // Replace with the Facebook profile URL of the friend you want to check
    $friend_profile_url = "FRIEND_PROFILE_URL";

    // Extract the Facebook user ID from the friend profile URL
    $friend_profile_url_parts = explode("/", $friend_profile_url);
    $friend_id = $friend_profile_url_parts[count($friend_profile_url_parts) - 2];

    // Construct the URL for the Graph API endpoint to get the list of friends for the authenticated user
    $graph_api_url = "https://graph.facebook.com/v13.0/me/friends?access_token=" . $access_token;

    // Make the request to the Graph API endpoint to get the list of friends for the authenticated user
    $friends_response = file_get_contents($graph_api_url);
    $friends = json_decode($friends_response)->data;

    // Check if the authenticated user is friends with the person in the given profile URL
    $is_friend = false;
    foreach ($friends as $friend) {
        if ($friend->id == $friend_id) {
            $is_friend = true;
            break;
        }
    }

    if ($is_friend) {
        echo "<p>You are friends with this person!</p>";
    } else {
        echo "<p>You are not friends with this person.</p>";
    }

    // Iterate over the list of friends for the authenticated user and check their lists of friends
    foreach ($friends as $friend) {
        $friend_id = $friend->id;
        $friend_name = $friend->name;
        echo "<h2>{$friend_name}'s Friends:</h2>";

        // Construct the URL for the Graph API endpoint to get the list of friends for this friend
        $friend_graph_api_url = "https://graph.facebook.com/v13.0/{$friend_id}/friends?access_token=" . $access_token;

        // Make the request to the Graph API endpoint for this friend
        $friend_friends_response = file_get_contents($friend_graph_api_url);
        $friend_friends = json_decode($friend_friends_response)->data;

        foreach ($friend_friends as $friend_friend) {
            $friend_friend_name = $friend_friend->name;
            $friend_friend_id = $friend_friend->id;
            if ($friend_friend_id == $friend_id) {
                continue; // Skip the friend if it is the authenticated user
            }
            $is_mutual = false;
            foreach ($friends as $f) {
                if ($f->id == $friend_friend_id) {
                    $is_mutual = true;
                    break;
                }
            }
            if ($is_mutual) {
                echo "<p>{$friend_friend_name} is a mutual friend!</p>";
            } else {
                echo "<p>{$friend_friend_name}</p>";
            }
        }
    }
    ?>
</body>
</html>
