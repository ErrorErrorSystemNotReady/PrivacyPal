import requests

# Replace with your own access token
access_token = "YOUR_ACCESS_TOKEN"

# Replace with the Facebook profile URL of the friend you want to check
friend_profile_url = "FRIEND_PROFILE_URL"

# Extract the Facebook user ID from the friend profile URL
friend_profile_url_parts = friend_profile_url.split("/")
friend_id = friend_profile_url_parts[-2]

# Construct the URL for the Graph API endpoint to get the list of friends for the authenticated user
graph_api_url = "https://graph.facebook.com/v13.0/me/friends"

# Include the access token in the headers of the request
headers = {"Authorization": f"Bearer {access_token}"}

# Make the request to the Graph API endpoint to get the list of friends for the authenticated user
response = requests.get(graph_api_url, headers=headers)

# Check if the response from the Graph API endpoint is valid
if response.status_code == 200:
    friends = response.json()["data"]
    is_friend = any(friend_id == f["id"] for f in friends)
    if is_friend:
        print("You are friends with this person!")
    else:
        print("You are not friends with this person.")

    for friend in friends:
        friend_id = friend["id"]
        friend_name = friend["name"]
        print(f"{friend_name}'s friends:")

        # Construct the URL for the Graph API endpoint to get the list of friends for this friend
        friend_graph_api_url = f"https://graph.facebook.com/v13.0/{friend_id}/friends"

        # Make the request to the Graph API endpoint for this friend
        friend_response = requests.get(friend_graph_api_url, headers=headers)

        # Check if the response from the Graph API endpoint is valid
        if friend_response.status_code == 200:
            friend_friends = friend_response.json()["data"]
            for friend_friend in friend_friends:
                friend_friend_name = friend_friend["name"]
                friend_friend_id = friend_friend["id"]
                if friend_friend_id == friend_id:
                    continue  # Skip the friend if it is the authenticated user
                is_mutual = any(friend_friend_id == f["id"] for f in friends)
                if is_mutual:
                    print(f"{friend_friend_name} is a mutual friend!")
                else:
                    print(friend_friend_name)
        else:
            print(f"Error: {friend_response.status_code} - {friend_response.text}")
else:
    print(f"Error: {response.status_code} - {response.text}")
