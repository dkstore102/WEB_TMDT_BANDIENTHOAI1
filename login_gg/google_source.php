<?php

//Google Code
require_once './login_gg/Google/lib_chartraries/Google/autoload.php';
//Insert your cient ID and secret 
//You can get it from : https://console.developers.google.com/
$client_id = '1007906964730-esfllomh58f21r854fsputhipb96llj1.apps.googleusercontent.com';
$client_secret = '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';
$redirect_uri = 'https://dk-store102.tk/login.php';

//incase of logout request, just unset the session var
//if (isset($_GET['logout'])) {
//    unset($_SESSION['access_token']);
//}

/* * **********************************************
  Make an API request on behalf of a user. In
  this case we need to have a valid OAuth 2.0
  token for the user, so we need to send them
  through a login flow. To do this we need some
  information from our API console project.
 * ********************************************** */
$client = new Google_Client();
$client->setClientId($client_id);
$client->setClientSecret($client_secret);
$client->setRedirectUri($redirect_uri);
$client->addScope("email");
$client->addScope("profile");

/* * **********************************************
  When we create the service here, we pass the
  client to it. The client then queries the service
  for the required scopes, and uses that when
  generating the authentication URL later.
 * ********************************************** */
$service = new Google_Service_Oauth2($client);

/* * **********************************************
  If we have a code back from the OAuth 2.0 flow,
  we need to exchange that with the authenticate()
  function. We store the resultant access token
  bundle in the session, and redirect to ourself.
 */

if (isset($_GET['code'])) {
    $client->authenticate($_GET['code']);
    $_SESSION['access_token'] = $client->getAccessToken();
    header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
    exit;
}
/* * **********************************************
  If we have an access token, we can make
  requests, else we generate an authentication URL.
 * ********************************************** */
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    $client->setAccessToken($_SESSION['access_token']);
} else {
    $authUrl = $client->createAuthUrl();
}
if ($client->isAccessTokenExpired()) {
    $authUrl = $client->createAuthUrl();
//            header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
}

if (!isset($authUrl)) {
    $googleUser = $service->userinfo->get(); //get user info 
    if(!empty($googleUser)){
        function loginFromSocialCallBack($socialUser) {
            $con = mysqli_connect("localhost","dkstoreq_dkstore","Dai061220","dkstoreq_dkphone");
            $con->set_charset("utf8");
            if (mysqli_connect_errno()) {
                echo "Connection Fail: " . mysqli_connect_errno();
                exit;
            }
            $result = mysqli_query($con, "Select * from `nguoidung` WHERE `Email` ='" . $socialUser['email'] . "'");
            if ($result->num_rows == 0) {
                $result = mysqli_query($con, "INSERT INTO `nguoidung` (`Ten`,`Email`, `PhanQuyen`) VALUES ('" . $socialUser['name'] . "', '" . $socialUser['email'] . "', 1);");
                if (!$result) {
                    echo mysqli_error($con);
                    exit;
                }
                $result = mysqli_query($con, "Select * from `nguoidung` WHERE `Email` ='" . $socialUser['email'] . "'");
            }
            if ($result->num_rows > 0) {
                $user = mysqli_fetch_assoc($result);
                if (session_status() == PHP_SESSION_NONE) {
        
                    session_start();
        
                }
                $_SESSION['current_user'] = $user;
                $_SESSION['name'] =$socialUser['name'];
                $_SESSION['phanquyen'] = 1;
                $_SESSION['MaND'] = mysqli_query($con, "Select ID_ND from `nguoidung` WHERE `User`='phandai032@gmail.com'");
                header('Location: ./account_management.php');
            }
        }
        
        function validateDateTime($date) {
            //Ki???m tra ?????nh d???ng ng??y th??ng xem ????ng DD/MM/YYYY hay ch??a?
            preg_match('/^[0-9]{1,2}-[0-9]{1,2}-[0-9]{4}$/', $date, $matches);
            if (count($matches) == 0) { //N???u ng??y th??ng nh???p kh??ng ????ng ?????nh d???ng th?? $match = array(); (r???ng)
                return false;
            }
            $separateDate = explode('-', $date);
            $day = (int) $separateDate[0];
            $month = (int) $separateDate[1];
            $year = (int) $separateDate[2];
            //N???u l?? th??ng 2
            if ($month == 2) {
                if ($year % 4 == 0) { //N???u l?? n??m nhu???n
                    if ($day > 29) {
                        return false;
                    }
                } else { //Kh??ng ph???i n??m nhu???n
                    if ($day > 28) {
                        return false;
                    }
                }
            }
            //Check c??c th??ng kh??c
            switch ($month) {
                case 1:
                case 3:
                case 5:
                case 7:
                case 8:
                case 10:
                case 12:
                    if ($day > 31) {
                        return false;
                    }
                    break;
                case 4:
                case 6:
                case 9:
                case 11:
                    if ($day > 30) {
                        return false;
                    }
                    break;
            }
            return true;
        }
        loginFromSocialCallBack($googleUser);
    }
}
//End Google Code
?>