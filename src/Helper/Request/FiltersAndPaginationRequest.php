<?php

namespace App\Helper\Request;

use Symfony\Component\HttpFoundation\Request;

class FiltersAndPaginationRequest
{
    const PAGE = 'page';
    const PER_PAGE = 'perPage';
    const SORT = 'sort';

    /**
     * @param Request $request
     * @return mixed
     */
    public function getOrderParams(Request $request)
    {
        return $request->query->get(self::SORT);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function getFiltersParams(Request $request): array
    {
        $filters = $request->query->all();

        unset($filters[self::SORT]);
        unset($filters[self::PAGE]);
        unset($filters[self::PER_PAGE]);

        return $filters;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function getPaginationParams(Request $request): array
    {
        $page = $request->query->get(self::PAGE, 1);
        $perPage = $request->query->get(self::PER_PAGE, 10);

        return [
            self::PAGE => $page,
            self::PER_PAGE => $perPage,
            'offset' => $this->getOffsetPaginationParam($page, $perPage)
        ];
    }

    /**
     * @param int $page
     * @param int $perPage
     * @return int
     */
    private function getOffsetPaginationParam(int $page, int $perPage): int
    {
        return ($page - 1) * $perPage;
    }
}