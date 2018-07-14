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
    protected function getTable()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    protected function getData()
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
