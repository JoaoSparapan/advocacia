<?php

if (!isset($GLOBALS['AUTH_INIT'])) {

    $GLOBALS['AUTH_INIT'] = true;

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $inactivityLimit = 40 * 60;

    if (
        isset($_SESSION['LAST_ACTIVITY']) &&
        (time() - $_SESSION['LAST_ACTIVITY'] > $inactivityLimit)
    ) {
        session_unset();
        session_destroy();
        header("Location: /advocacia/pages/login.php?session=expired");
        exit;
    }

    $_SESSION['LAST_ACTIVITY'] = time();
}

include_once $_SERVER['DOCUMENT_ROOT'] . "/advocacia/services/Controller/GlobalController.php";
date_default_timezone_set('America/Sao_Paulo');

if (!class_exists('AuthController')) {

    class AuthController extends GlobalController
    {

        function __construct()
        {
            parent::__construct();
        }

        public static function getUser()
        {
            return $_SESSION['user'] ?? null;
        }

        public static function setUser($user)
        {
            $_SESSION['user'] = $user;
        }

        public function verifyUser($email = '', $password = '')
        {
            $encrypt_pass = crypt($password, '$1$rasmusle$');
            $conn = $this->connectDB();

            $query = mysqli_query($conn, "
                SELECT * FROM user
                WHERE email = '$email'
                AND password = '$encrypt_pass'
                LIMIT 1
            ");

            $result = null;
            if ($query && mysqli_num_rows($query) > 0) {
                $result = mysqli_fetch_assoc($query);
            }

            $this->disconnectDB($conn);
            return $result;
        }

        public function createVerificationSession($user_id, $codigo)
        {
            $conn = $this->connectDB();

            mysqli_query($conn, "DELETE FROM auth_sessions WHERE user_id = '$user_id'");

            $query = "
                INSERT INTO auth_sessions
                (user_id, verification_code, expires_at, verified)
                VALUES
                ('$user_id', '$codigo', DATE_ADD(NOW(), INTERVAL 10 MINUTE), 0)
            ";

            $success = mysqli_query($conn, $query);
            $this->disconnectDB($conn);

            return $success;
        }

        public function validateVerificationCode($user_id, $codigo)
        {
            $conn = $this->connectDB();
            $now = date('Y-m-d H:i:s');

            $query = mysqli_query($conn, "
                SELECT * FROM auth_sessions
                WHERE user_id = '$user_id'
                AND verification_code = '$codigo'
                AND expires_at > '$now'
                AND verified = 0
                LIMIT 1
            ");

            $session = mysqli_fetch_assoc($query);

            if ($session) {
                mysqli_query($conn, "
                    UPDATE auth_sessions SET verified = 1 WHERE id = {$session['id']}
                ");
            }

            $this->disconnectDB($conn);
            return $session ? true : false;
        }

        public function cleanupExpiredSessions()
        {
            $conn = $this->connectDB();
            $now = date('Y-m-d H:i:s');

            mysqli_query($conn, "
                DELETE FROM auth_sessions WHERE expires_at < '$now'
            ");

            $this->disconnectDB($conn);
        }

        public function getUserById($user_id)
        {
            $conn = $this->connectDB();

            $query = mysqli_query($conn, "
                SELECT * FROM user
                WHERE idUser = '$user_id'
                LIMIT 1
            ");

            $user = ($query && mysqli_num_rows($query) > 0)
                ? mysqli_fetch_assoc($query)
                : null;

            $this->disconnectDB($conn);
            return $user;
        }
    }
}

?>
