<?php

/**
 * CSRF Protection Class
 */
class CSRF_Protection {

    /**
     * Holds CI instance
     *
     * @var CI instance
     */
    private $CI;

    /**
     * Name used to store token on session
     *
     * @var string
     */
    private static $token_name = 'tailortok';

    /**
     * Stores the token
     *
     * @var string
     */
    private static $token;

    // -----------------------------------------------------------------------------------

    public function __construct() {
        $this->CI = & get_instance();
    }

    /**
     * Generates a CSRF token and stores it on session. Only one token per session is generated.
     * This must be tied to a post-controller hook, and before the hook
     * that calls the inject_tokens method().
     *
     * @return void
     * @author Taral Oza
     */
    public function generate_token() {
        // Load session library if not loaded

        if ($this->CI->session->userdata(self::$token_name) === FALSE || $this->CI->session->userdata(self::$token_name) === NULL) {

            // Generate a token and store it on session, since old one appears to have expired.
            $token = md5(uniqid() . microtime() . rand());
            $token = encrypt_decrypt('encrypt', $token);
            self::$token = $token;
            $this->CI->session->set_userdata(self::$token_name, self::$token);
        } else {
            // Set it to local variable for easy access
            if (trim(CSRF_TOKEN_GENERATE_IGNORE_PATH) != "") {
                $arrayIgnore = explode(",", CSRF_TOKEN_GENERATE_IGNORE_PATH);

                $getCurrentPath = $this->CI->router->fetch_class() . "/" . $this->CI->router->fetch_method();
                $pathToIgnore = false;
                if (in_array($getCurrentPath, $arrayIgnore)) {
                    
                } else {
                    $token = md5(uniqid() . microtime() . rand());
                    $token = encrypt_decrypt('encrypt', $token);
                    self::$token = $this->CI->session->userdata(self::$token_name);
                }
            } else {
                $token = md5(uniqid() . microtime() . rand());
                $token = encrypt_decrypt('encrypt', $token);
                self::$token = $this->CI->session->userdata(self::$token_name);
            }
        }
    }

    /**
     * Validates a submitted token when POST request is made.
     *
     * @return void
     * @author Taral Oza
     */
    public function validate_tokens() {
        $pathToIgnore = FALSE;
        if (trim(CSRF_TOKEN_IGNORE_PATH) != "") {
            $arrayIgnore = explode(",", CSRF_TOKEN_IGNORE_PATH);
            $getCurrentPath = $this->CI->router->fetch_class() . "/" . $this->CI->router->fetch_method();
            if (in_array($getCurrentPath, $arrayIgnore)) {
                $pathToIgnore = TRUE;
            }
        }
        // Is this a post request?
        if (!$pathToIgnore && $_SERVER['REQUEST_METHOD'] == 'POST') {
            // Is the token field set and valid?
            $posted_token = $this->CI->input->post(self::$token_name);
            if (!$pathToIgnore && ($posted_token === FALSE || $posted_token != $this->CI->session->userdata(self::$token_name))) {
                // Invalid request, send error 400.
                show_error('Request was invalid. Tokens did not match.', 400);
            }
        }
    }

    /**
     * This injects hidden tags on all POST forms with the csrf token.
     * Also injects meta headers in <head> of output (if exists) for easy access
     * from JS frameworks.
     *
     * @return void
     * @author Ian Murray
     */
    public function inject_tokens() {
        $output = $this->CI->output->get_output();

        //$ssToken = $this->CI->session->userdata(self::$token_name);
        // Inject into form
        $output = preg_replace('/(<(form|FORM)[^>]*(method|METHOD)="(post|POST)"[^>]*>)/', '$0<input type="hidden" name="' . self::$token_name . '" value="' . self::$token . '">', $output);

        // Inject into <head>
        $output = preg_replace('/(<\/head>)/', '<meta name="csrf-name" content="' . self::$token_name . '">' . "\n" . '<meta name="csrf-token" content="' . self::$token . '">' . "\n" . '$0', $output);

        //echo " ==>" . self::$token;
        //exit();
        // Replacing Base URL site 
        $output = str_replace("{BASE_URL_SITE}", base_url() . "index.php/redirect/link/", $output);
        $this->CI->output->_display($output);
    }

}

?>