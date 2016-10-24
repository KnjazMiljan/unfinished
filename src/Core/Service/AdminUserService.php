<?php
declare(strict_types = 1);
namespace Core\Service;

use Core\Mapper\AdminUsersMapper;
use Zend\Crypt\Password\Bcrypt;

/**
 * Class AdminUserService.
 *
 * @package Core\Service
 */
class AdminUserService
{
    /**
     * @var AdminUsersMapper
     */
    private $adminUsersMapper;

    /**
     * @var Bcrypt
     */
    private $crypt;

    /**
     * AdminUserService constructor.
     *
     * @param Bcrypt           $crypt            bcrypt password encryption method
     * @param AdminUsersMapper $adminUsersMapper mapper for admin us
     */
    public function __construct(Bcrypt $crypt, AdminUsersMapper $adminUsersMapper)
    {
        $this->crypt            = $crypt;
        $this->adminUsersMapper = $adminUsersMapper;
    }

    /**
     * Performs user login or throws exception if credentials are not valid.
     *
     * @param string $email user email
     * @param string $password user password
     *
     * @return array|\ArrayObject|null
     *
     * @throws \Exception if user does not exist or password is not valid
     */
    public function loginUser($email, $password)
    {
        $user = $this->adminUsersMapper->getByEmail($email);

        if (!$user) {
            throw new \Exception('User does not exist.');
        }

        if (!$this->crypt->verify($password, $user->password)) {
            throw new \Exception('Password does not match.');
        }

        $this->adminUsersMapper->updateLogin($user->admin_user_uuid);

        return $user;
    }
}
