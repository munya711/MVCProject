<?php
class session {
    public static function start($timeout =  10800) {
        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'domain' => '',
            'secure' => false,  // Set to true when using HTTPS
            'httponly' => true,
            'samesite' => 'Strict',
        ]);
        session_start();
        // Check for session hijacking
        if (!isset($_SESSION['IP_ADDRESS'])) {
            $_SESSION['IP_ADDRESS'] = $_SERVER['REMOTE_ADDR'];
        }
        if ($_SESSION['IP_ADDRESS'] !== $_SERVER['REMOTE_ADDR']) {
            self::destroy();
        }
        if (!isset($_SESSION['USER_AGENT'])) {
            $_SESSION['USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];
        }
        if ($_SESSION['USER_AGENT'] !== $_SERVER['HTTP_USER_AGENT']) {
            self::destroy();
        }
        // Handle session timeout
        if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $timeout)) {
            self::destroy();
        }
        $_SESSION['LAST_ACTIVITY'] = time();
    }

    public static function destroy() {
        session_unset();
        session_destroy();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
    }

    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    public static function get($key) {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    public static function remove($key) {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    public static function clearall() {
        session_unset();
    }

    public static function regenerate() {
        session_regenerate_id(true);
    }

    public static function isLoggedIN($key) {
        return isset($_SESSION[$key]);
    }

    public static function exists($key) {
        return isset($_SESSION[$key]);
    }
}
?>
