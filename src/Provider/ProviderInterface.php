<?php declare(strict_types=1);

namespace App\Provider;

use Traversable;

interface ProviderInterface
{
    /**
     * @return Post[]|Traversable - iterable list of Post objects, not expected to be countable
     */
    public function getPosts(Client $client): iterable;
}
