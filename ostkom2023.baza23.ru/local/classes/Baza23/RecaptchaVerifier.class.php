<?

namespace Baza23;

class RecaptchaVerifier {
    const SITE_VERIFY_URL = 'https://www.google.com/recaptcha/api/siteverify';

    public $secret;
    public $permissibleScore;
    public $token;

    public function __construct($secret, $permissibleScore = 0) {
        if (empty($secret)) throw new \RuntimeException('No secret provided');

        if (!is_string($secret)) throw new \RuntimeException('The provided secret must be a string');

        $this->secret = $secret;
        $this->permissibleScore = $permissibleScore;
    }

    public function pf_verify($token) {
        $errorObject = array(
                'success'      => false,
                'score'        => false,
                'isSuccess'    => false,
                'hostname'     => null,
                'action'       => null,
                'challenge_ts' => null,
                'error_codes'  => ['Empty token.']
        );
        if (empty($token)) return $errorObject;

        $data = $this->pf_submit($token);
        if (!$data) return $errorObject;

        $success = isset($data['success']) ? $data['success'] : false;
        $score = isset($data['score']) ? $data['score'] : false;
        $isSuccess = $success && $score !== false ? $this->permissibleScore <= $score : false;
        $hostname = isset($data['hostname']) ? $data['hostname'] : null;
        $action = isset($data['action']) ? $data['action'] : null;
        $challenge_ts = isset($data['challenge_ts']) ? $data['challenge_ts'] : null;
        $error_codes = $data['error-codes'];

        return array(
                'success'      => $success,
                'score'        => $score,
                'isSuccess'    => $isSuccess,
                'hostname'     => $hostname,
                'action'       => $action,
                'challenge_ts' => $challenge_ts,
                'error_codes'  => $error_codes
        );
    }

    public function pf_submit($token) {
        $request = self::SITE_VERIFY_URL . '?'
                . 'secret='. $this->secret
                . '&response=' . $token
                . '&remoteip=' . $_SERVER['REMOTE_ADDR'];
        $result = file_get_contents($request);
        if (!$result) {
            $ch = curl_init($request);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $result = curl_exec($ch);

            curl_close($ch);
        }
        return json_decode($result, true);
    }
}