<?php
/*
 * douggr/zf-rest
 *
 * @link https://github.com/douggr/zf-rest for the canonical source repository
 * @version 1.0.0
 *
 * For the full copyright and license information, please view the LICENSE
 * file distributed with this source code.
 */

namespace ZfRest;

use ZfRest\Auth\Exception;
use ZfRest\Util\String;
use ZfRest\Model\User;

/**
 * {@inheritdoc}
 */
class Auth
{
    const FAILURE                       = \Zend_Auth_Result::FAILURE;
    const FAILURE_IDENTITY_NOT_FOUND    = \Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND;
    const FAILURE_IDENTITY_AMBIGUOUS    = \Zend_Auth_Result::FAILURE_IDENTITY_AMBIGUOUS;
    const FAILURE_CREDENTIAL_INVALID    = \Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID;
    const FAILURE_UNCATEGORIZED         = \Zend_Auth_Result::FAILURE_UNCATEGORIZED;

    /**
     * @var ZfRest\Auth
     */
    protected static $instance = null;

    /**
     * Returns an instance of ZfRest\Auth
     *
     * @return ZfRest\Auth
     */
    final public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    /**
     * Authenticates against the supplied adapter
     *
     * @param string username
     * @param string password in row format
     * @return ZfRest\Auth
     */
    public static function authenticate($username, $password)
    {
        if ('' === trim($username) || '' === trim($password)) {
            throw new Exception('ERR.IDENTITY_AMBIGUOUS', self::FAILURE_IDENTITY_AMBIGUOUS);
        }

        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            $usedColumn = 'email';
        } else {
            $usedColumn = 'username';
        }

        if (null === $user = User::locate($usedColumn, $username)) {
            throw new Exception('ERR.IDENTITY_NOT_FOUND', self::FAILURE_IDENTITY_NOT_FOUND);
        }

        if (!String::verifyPassword($password, $user->password)) {
            throw new Exception('ERR.CREDENTIAL_INVALID', self::FAILURE_CREDENTIAL_INVALID);
        }

        $token       = String::password(static::getAccessToken($user));
        $user->token = $token;
        $user->save();

        return [
            'token_type'    => 'bearer',
            'access_token'  => $token
        ];
    }

    /**
     * @param ZfRest\Model\User
     * @return string
     */
    public static function getAccessToken($user)
    {
        return base64_encode("{$user->api_key}:{$user->api_secret}");
    }

    /**
     * Singleton pattern implementation makes "new" unavailable
     *
     * @return void
     */
    private function __construct()
    {
    }

    /**
     * Singleton pattern implementation makes "clone" unavailable
     *
     * @return void
     */
    private function __clone()
    {
    }
}
