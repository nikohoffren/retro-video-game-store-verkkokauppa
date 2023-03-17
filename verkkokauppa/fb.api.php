<?php
    session_start();

    function makeFacebookApiCall($endpoint, $params) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint . '?'. http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, false);

        $fbResponse = curl_exec($ch);
        $fbResponse = json_decode($fbResponse, true);
        curl_close($ch);

        return array(
            'endpoint' => $endpoint,
            'params' => $params,
            'has_errors' => isset($fbResponse['error']) ? true : false,
            'error_message' => isset($fbResponse['error']) ? $fbResponse['error']['message'] : '',
            'fbResponse' => $fbResponse
        );
    }

    function getFacebookLoginUrl() {
        $endpoint = 'https://www.facebook.com/' . FB_GRAPH_VERSION . '/dialog/oauth';
        $params = array(
            'client_id' => FB_APP_ID,
            'redirect_uri' => FB_REDIRECT_URI,
            'state' => FB_APP_STATE,
            'scope' => 'email',
            'auth_type' => 'rerequest'
        );
        return $endpoint . '?' . http_build_query($params);
    }

    function getAccessTokenWithCode($code) {
        $endpoint = 'https://graph.facebook.com/' . FB_GRAPH_VERSION . '/oauth/access_token';
        $params = array(
            'client_id' => FB_APP_ID,
            'client_secret' => FB_APP_SECRET,
            'redirect_uri' => FB_REDIRECT_URI,
            'code' => $code
        );
        return makeFacebookApiCall($endpoint, $params);
    }

    function getFacebookUserInfo($accessToken) {
        $endpoint = FB_GRAPH_DOMAIN . '/me';
        $params = array(
            'fields' => 'first_name,last_name,email,picture',
            'access_token' => $accessToken
        );
        return makeFacebookApiCall($endpoint, $params);
    }

    function tryAndLoginWithFacebook($get) {
        $status = 'fail';
        $message = '';

        if (isset($get['error'])) {
            $message = $get['error_description'];
        } else {
            $accessTokenInfo = getAccessTokenWithCode($get['code']);

            if ($accessTokenInfo['has_errors']) {
                $message = $accessTokenInfo['error_message'];
            } else {
                $_SESSION['fb_access_token'] = $accessTokenInfo['fbResponse']['access_token'];

                $fbUserInfo = getFacebookUserInfo($_SESSION['fb_access_token']);

                if (!$fbUserInfo['has_errors'] && !empty($fbUserInfo['fbResponse']['id']) && !empty($fbUserInfo['fbResponse']['email'])) {
                    $status = 'ok';
                }

                $_SESSION['fb_user_info'] = $fbUserInfo['fbResponse'];
				$_SESSION['fb_user_id'] = $fbUserInfo['fbResponse']['id'];
				$_SESSION['fb_user_picture'] = $fbUserInfo['fbResponse']['picture']['data']['url'];

                // echo '<pre>';
                // print_r($_SESSION['fb_user_info']['first_name']);
                $_SESSION['fb_logged_in'] = true;
                // header("Location: login");
                // die();
            }
        }
        return array(
            'status' => $status,
            'message' => $message
        );
    }
