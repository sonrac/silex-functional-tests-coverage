<?php
/**
 * @author Donii Sergii <doniysa@gmail.com>.
 */

namespace sonrac\FCoverage\Tests\Seeds;

use sonrac\SimpleSeed\SimpleSeedWithCheckExists;

/**
 * Class UsersSeeds.
 *
 * @author Donii Sergii <doniysa@gmail.com>
 */
class UsersSeeds extends SimpleSeedWithCheckExists
{
    /**
     * {@inheritdoc}
     */
    public function getTable()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return [
            [
                'id'       => 10000,
                'username' => 'user',
                'password' => 'user',
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getWhereForRow($data)
    {
        return ['id' => $data['id']];
    }
}
