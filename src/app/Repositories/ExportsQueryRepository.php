<?php

namespace VCComponent\Laravel\Export\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;
interface ExportsQueryRepository extends RepositoryInterface
{
    public function findBySlug($slug);
    public function getQuery($where);
    public function getEntity();

}
