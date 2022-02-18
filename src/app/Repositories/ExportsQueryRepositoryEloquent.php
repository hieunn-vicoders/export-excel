<?php

namespace VCComponent\Laravel\Export\Repositories;

use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use VCComponent\Laravel\Export\Entities\ExportsQuery;
use VCComponent\Laravel\Export\Repositories\ExportsQueryRepository;
use VCComponent\Laravel\Vicoders\Core\Exceptions\NotFoundException;

/**
 * Class PostRepositoryEloquent.
 *
 * @package namespace VCComponent\Laravel\Post\Repositories;
 */
class ExportsQueryRepositoryEloquent extends BaseRepository implements ExportsQueryRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ExportsQuery::class;
    }

    public function getEntity()
    {
        return $this->model;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * Find data by a slug
     *
     * @param string $type
     * @param int $id
     * @return self
     */
    public function findBySlug($slug)
    {

        try {
            $data = $this->model->where('slug', $slug)->first();
            return $data;
        } catch (Exception $e) {
            throw new NotFoundException('data not found');
        }
    }
    public function getQuery($where)
    {

        try {
            $data = $this->model->where($where)->get();
            return $data;
        } catch (Exception $e) {
            throw new NotFoundException('data not found');
        }

    }

}
