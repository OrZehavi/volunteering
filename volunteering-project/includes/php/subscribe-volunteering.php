<?php
    require_once "connectionToDB.php";

    $volunteering_id = $_POST["volunteeringid"];
    session_start();

    $user_id = $_SESSION["user_id"];
    //$user_email = $_SESSION["email"];
    $user_email="amnon.tetra@gmail.com";
    $title = $_SESSION["vol-title"]  ;
    $description = $_SESSION["vol-description"];
    $location= $_SESSION["vol-location"];
    $date= $_SESSION["vol-date"];
    $start_time = $_SESSION["vol-start-time"];
    $duration = $_SESSION["vol-duration"];

    //$temp = strtotime($date."T".$start_time);
    //$datetime=date("Y-m-d H:i", $temp);
    $date = date("Y-m-d",(strtotime($date)));
    $start_time1 = date("H:i",(strtotime($start_time)));
    $temp1="$date";
    $temp2="T$start_time1:00";
    $datetime="$temp1$temp2";

    
    //echo '<script>
    //            alert("user name '.$user_id.' \n email: '.$user_email.' \n tite: '.$title.' \n description: '.$description.' \n location: '.$location.' \n date: '.$date.' \n start time '.$start_time.' \n duration '.$duration.' \n end time:  '.$end_time.' \n start datetime '.$datetime .' ");
    //            </script>';
                
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if ($user_id) {
            $sql = "INSERT INTO volunteerings_to_users(volunteering_id, user_id, is_approved) VALUES ('".$volunteering_id."', '".$user_id."', '0')";
            if (mysqli_query($conn, $sql)) {
                echo '<script>
                alert("You successfully subscribed to this volunteering. \n\n Once you will be approved by the volunteering organizer, you will recieve a notification email. In addition We sent you a calendar remainder.\n \n Thank you for doing good! '.$_SESSION["name"].'!");
                window.location.href = "/volunteering-project/includes/php/get-volunteerings.php"; 
                </script>';
                    require __DIR__ . '/vendor/autoload.php'; //For Google calendar API

                
                /**
                 * Returns an authorized API client.
                 * @return Google_Client the authorized client object
                 */
                function getClient()
                {
                     //echo '<script> alert("create google client");</script>';
                    $client = new Google_Client();
                    $client->setApplicationName('Google Calendar API PHP Quickstart');
                    $client->setScopes(Google_Service_Calendar::CALENDAR);
                    $client->setAuthConfig('credentials.json');
                    $client->setAccessType('offline');
                    $client->setPrompt('select_account consent');
                
                    // Load previously authorized token from a file, if it exists.
                    // The file token.json stores the user's access and refresh tokens, and is
                    // created automatically when the authorization flow completes for the first
                    // time.
                    $tokenPath = 'token.json';
                    if (file_exists($tokenPath)) {
                        $accessToken = json_decode(file_get_contents($tokenPath), true);
                        $client->setAccessToken($accessToken);
                    }
                
                    // If there is no previous token or it's expired.
                    if ($client->isAccessTokenExpired()) {
                        // Refresh the token if possible, else fetch a new one.
                        if ($client->getRefreshToken()) {
                            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                        } else {
                            // Request authorization from the user.
                            $authUrl = $client->createAuthUrl();
                            printf("Open the following link in your browser:\n%s\n", $authUrl);
                            print 'Enter verification code: ';
                            $authCode = trim(fgets(STDIN));
                
                            // Exchange authorization code for an access token.
                            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
                            $client->setAccessToken($accessToken);
                
                            // Check to see if there was an error.
                            if (array_key_exists('error', $accessToken)) {
                                throw new Exception(join(', ', $accessToken));
                            }
                        }
                        // Save the token to a file.
                        if (!file_exists(dirname($tokenPath))) {
                            mkdir(dirname($tokenPath), 0700, true);
                        }
                        file_put_contents($tokenPath, json_encode($client->getAccessToken()));
                    }
                    return $client;
                }
                
                // Get the API client and construct the service object.
                $client = getClient();
                $service = new Google_Service_Calendar($client);
                
                //Create new event
                $event = new Google_Service_Calendar_Event(array(
                  'summary' => 'Volunteering event - '.$title.' ',
                  'location' => $location,
                  'description' => $description,
                  'start' => array(
                    'dateTime' => $datetime,
                    'timeZone' => 'Israel',
                  ),
                  'end' => array(
                    'dateTime' => $datetime,
                    'timeZone' => 'Israel',
                  ),
                  'recurrence' => array(
                    'RRULE:FREQ=DAILY;COUNT=1'
                  ),
                  'attendees' => array(
                    array('email' => $user_email),
                
                
                  ),
                  'reminders' => array(
                    'useDefault' => FALSE,
                    'overrides' => array(
                      array('method' => 'email', 'minutes' => 24 * 60),
                      array('method' => 'popup', 'minutes' => 10),
                    ),
                  ),
                ));
                
                $calendarId = 'primary';
                $event = $service->events->insert($calendarId, $event);
                printf('Event created: %s\n', $event->htmlLink);
                //echo '<script> alert("END");</script>';

                
            } else {
                echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
            }
        }

}
?>

