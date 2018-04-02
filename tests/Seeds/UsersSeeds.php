<?php
/**
 * @author Donii Sergii <s.doniy@infomir.com>.
 */

namespace sonrac\FCoverage\Tests\Seeds;

use sonrac\SimpleSeed\SimpleSeedWithCheckExists;

/**
 * Class UsersSeeds
 *
 * @author Donii Sergii <s.donii@infomir.com>
 */
class UsersSeeds extends SimpleSeedWithCheckExists
{
    /**
     * @inheritDoc
     */
    protected function getTable()
    {
        return 'users';
    }

    /**
     * @inheritDoc
     */
    protected function getData()
    {
        return [
            [
                'id'       => 10000,
                'username' => 'user',
                'password' => 'user'
            ]
        ];
    }

    /**
     * @inheritDoc
     */
    protected function getWhereForRow($data)
    {
        return ['id' => $data['id']];
    }

}