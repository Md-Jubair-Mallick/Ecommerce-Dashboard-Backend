<?php
namespace App\Services;

use App\Models\Review;
use App\Repositories\ReviewRepository;

class ReviewService
{
    protected $reviewRepository;

    public function __construct(ReviewRepository $reviewRepository)
    {
        $this->reviewRepository = $reviewRepository;
    }

    public function all($filters = [])
    {
        $reviews = $this->reviewRepository->all($filters);
        $result = [
            'data' => $reviews->items(),
            'meta' => [
                'total' => $reviews->total(),
                'per_page' => $reviews->perPage(),
                'current_page' => $reviews->currentPage(),
                'last_page' => $reviews->lastPage(),
            ],
        ];
        return $result;
    }
    public function update($id, $filters)
    {
        return $this->reviewRepository->update($id, $filters);
    }
    public function delete($id)
    {
        return $this->reviewRepository->delete($id);
    }
}