<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Home Page</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />

    <script>
        function sendFriendRequest(username, element) {
            $.ajax({
                type: "POST",
                url: "send_friend_request.php",
                data: {
                    username: username
                },
                success: function(response) {
                    var jsonData = JSON.parse(response);
                    if (jsonData.success) {
                        $(element).replaceWith("<p>Request Sent</p>");
                    } else {
                        alert("Failed to send friend request: " + jsonData.error);
                    }
                }
            });
        }

        function acceptFriendRequest(username) {
            $.ajax({
                type: "POST",
                url: "accept_friend_request.php",
                data: {
                    username: username
                },
                success: function(response) {
                    var jsonData = JSON.parse(response);
                    if (jsonData.success) {
                        loadFriendRequests(); // Reload friend requests to update the list
                    } else {
                        alert('Error accepting friend request: ' + jsonData.error);
                    }
                }
            });
        }

        function declineFriendRequest(username) {
            $.ajax({
                type: "POST",
                url: "decline_friend_request.php",
                data: {
                    username: username
                },
                success: function(response) {
                    var jsonData = JSON.parse(response);
                    if (jsonData.success) {
                        loadFriendRequests(); // Reload friend requests to update the list
                    } else {
                        alert('Error declining friend request: ' + jsonData.error);
                    }
                }
            });
        }

        function loadFriendsList() {
            $.get("get_friends.php", function(data) {
                var friends = JSON.parse(data);
                $("#friendList").empty();

                friends.forEach((friend) => {
                    var friendDiv = $(`
                <div class="containClass rounded-lg border shadow friend list-group-item list-group-item-action d-flex justify-content-center align-items-center mb-2 cursor-pointer">${friend.username}</div>
            `);
                    friendDiv.click(function() {
                        $(".friend").removeClass('activeFriend'); // Remove active class from all friends
                        $(this).addClass('activeFriend'); // Add active class to the clicked friend
                        $("#selectedFriend").text(friend.username); // Update the displayed selected friend

                        // Save the selected friend's username to the session
                        $.post("set_selected_friend.php", {
                            selectedFriend: friend.username,
                            selectedFriendID: friend.id,
                        }, function(response) {
                            var result = JSON.parse(response);
                            if (!result.success) {
                                alert('Failed to set selected friend in session');
                            }
                        });

                        loadChat(friend.username, friend.id); // Load chat history with the selected friend
                    });
                    $("#friendList").append(friendDiv);
                });
            });
        }



        function loadFriendRequests() {
            $.get("get_friend_requests.php", function(data) {
                var requests = JSON.parse(data);
                $("#friendRequests").empty();
                requests.forEach((request) => {
                    var requestRow = $(`
                                <div class="d-flex justify-content-between align-items-center bg-white shadow rounded-lg border border-light p-2">
                                    <div class="d-flex align-items-center">
                                        <span class="ml-4">${request.username}</span>
                                    </div>
                                    <div>
                                        <button class="btn btn-success btn-sm me-1" onclick="acceptFriendRequest('${request.username}')">Accept</button>
                                        <button class="btn btn-danger btn-sm" onclick="declineFriendRequest('${request.username}')">Decline</button>
                                    </div>
                                </div>
                            `);
                    $("#friendRequests").append(requestRow);
                });
            });
        }

        function loadChat() {
            $.get("get_selected_friend.php", function(data) {
                var response = JSON.parse(data);
                if (response.success) {
                    var friendUsername = response.username; // Get the username from the session via PHP
                    $.get("get_chats.php", {
                    }, function(data) {
                        var chats = JSON.parse(data);
                        $("#messagesSection").empty();
                        $("#selectedFriend").text(friendUsername); // Set the friend's username in the chat header
                        chats.forEach((chat) => {
                            var chatClass = chat.sender === friendUsername ? "chatYou" : "chatMe";
                            $("#messagesSection").append(`<div class="${chatClass}">${chat.message}</div>`);
                        });
                    });
                } else {
                    console.error("Error loading chats: " + response.error);
                }
            });
        }


        function sendChat() {
            var messageContent = $("#chatMessage").val().trim();
            if (messageContent !== "") {
                $.post("send_chat.php", {
                    message: messageContent
                }, function(response) {
                    var jsonData = JSON.parse(response);
                    if (jsonData.success) {
                        $("#chatMessage").val(""); // Clear the input field
                        loadChat(); // Refresh the chat area, function might need adjustments to use session friend ID
                    } else {
                        alert("Failed to send message: " + jsonData.error);
                    }
                });
            }
        }


        $(document).ready(function() {
            // Check if user is logged in
            $.get("check_login.php", function(data) {
                var jsonData = JSON.parse(data);
                if (!jsonData.logged_in) {
                    window.location.href = "login.php";
                } else {
                    $("#welcomeMessage").text(`Welcome, ${jsonData.username}`);
                    loadFriendsList(); // Load friends on initial load
                }
            });


            $("#sendChatBtn").click(function() {
                sendChat();
            });

            $("#chatBtn").click(function() {
                $("#chatSection").show();
                $("#addFriendSection").hide();
                $("#sectionTitle").text("Chat");
            });

            $("#addFriendBtn").click(function() {
                loadFriendRequests();
                $("#chatSection").hide();
                $("#addFriendSection").show();
                $("#sectionTitle").text("Add Friend");
            });

            $("#logoutBtn").click(function() {
                $.post("logout.php", function() {
                    window.location.href = "login.php";
                });
            });

            $("#searchBtn").click(function() {
                $.post("search.php", function() {
                    window.location.href = "search.php";
                });
            });

            $("#searchFriendBtn").click(function() {
                var searchUsername = $("#searchUsernameInput").val().trim();
                if (searchUsername !== "") {
                    $.ajax({
                        type: "POST",
                        url: "search_users.php",
                        data: {
                            searchUsername: searchUsername
                        },
                        success: function(response) {
                            var jsonData = JSON.parse(response);
                            $("#searchResults").empty();
                            if (jsonData.length > 0) {
                                jsonData.forEach(function(user) {
                                    var resultCard = $(`
                            <div class="containClass d-flex flex-column align-items-center bg-white shadow rounded-lg border border-light p-3 mr-4">
                                <img src="${user.profile_picture}" class="rounded-circle" style="width: 100px; height: 100px;">
                                <p class="mt-2">${user.username}</p>
                                ${user.actionButton}
                            </div>
                        `);
                                    $("#searchResults").append(resultCard);
                                });
                            } else {
                                $("#searchResults").append(`<p>No users found with that username.</p>`);
                            }
                        }
                    });
                }
            });
        });
    </script>

    <style>
        .containClass {
            border: 1px solid black;
            padding: 2px;
            display: flex;
            min-height: 30px;
            text-align: center;
        }

        .chatMe {
            align-self: flex-end;
            border-radius: 10px 0 10px 10px;
            background-color: green;
            padding: 10px;
            margin: 5px;
        }

        .chatYou {
            align-self: flex-start;
            border-radius: 0 10px 10px 10px;
            background-color: gray;
            padding: 10px;
            margin: 5px;
        }

        .activeFriend {
            color: white;
            background-color: red;
        }
    </style>
</head>

<body class="bg-light">
    <div class="container mt-5">
        <h2 id="welcomeMessage">Welcome,</h2>
        <h2 id="sectionTitle">Chat</h2>

        <div class="btn-group" role="group" aria-label="Basic example">
            <button type="button" id="chatBtn" class="btn btn-primary">Chat</button>
            <button type="button" id="addFriendBtn" class="btn btn-secondary">Add Friend</button>
            <button type="button" id="searchBtn" class="btn btn-secondary">Search</button>
            <a href="bookmark.php" class="btn btn-secondary">Bookmark</a>
            <button type="button" id="logoutBtn" class="btn btn-danger">Logout</button>
        </div>

        <div id="chatSection" class=" my-4">
            <div class="container d-flex justify-content-start mt-3">
                <div class="row" style="width: 100%">
                    <div class="col-3" id="friendList">
                        <!-- Friends List -->
                    </div>
                    <div class="col-9 border border-light bg-white rounded-lg shadow " style="flex: 70%; margin-left: 2px">
                        <div id="selectedFriend" class="d-flex justify-content-center align-items-center mt-2 font-weight-bold">
                            <!-- Selected Friend -->
                        </div>
                        <div id="messagesSection" class="mt-2 d-flex flex-column" style="height: 500px; overflow-y: auto;">
                            <!-- Chat messages -->
                        </div>
                        <div class="mt-2 d-flex justify-content-between align-items-center" style="padding-left: 10px; padding-right: 10px">
                            <textarea id="chatMessage" class="form-control mb-2" rows="3" cols="50"></textarea>
                            <button id="sendChatBtn" class="btn btn-success ml-2">Send</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="addFriendSection" style="display: none" class="mt-3">
            <h4>Search Friend</h4>
            <div class="d-flex">
                <p class="mt-2 mr-2">Username:</p>
                <input type="text" id="searchUsernameInput" class="form-control mr-2" />
                <button id="searchFriendBtn" class="btn btn-info">Search</button>
            </div>
            <div class="d-flex mt-3">
                <div id="searchResults" class="d-flex align-items-center">
                    <!-- Search results will be populated here -->
                </div>
            </div>
            <h3>Friend Requests</h3>
            <div id="friendRequests">
                <!-- Friend requests will be populated here -->
            </div>
        </div>
    </div>
</body>

</html>