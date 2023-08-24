<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentOrAnswerRequest;
use App\Services\CommentService;
use App\Http\Requests\GetCommentsRequest;
use ErrorException;

class CommentController extends Controller
{

    private CommentService $commentService;

    /**
     * CommentController constructor.
     *
     * @param CommentService $commentService
     */
    public function __construct(CommentService $commentService) {
        $this->commentService = $commentService;
    }

    /**
     * Get a collection of comments with optional sorting.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(GetCommentsRequest $request)
    {
        return $this->commentService->getComments($request->query('sort'));
    }

    /**
     * Store a new comment.
     *
     * @param StoreCommentOrAnswerRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreCommentOrAnswerRequest $request)
    {
        try {
            $data = $request->validated();

            $this->commentService->createComment($data);

            return response()->json(['success' => true]);
        } catch (ErrorException $e) {
            return response()->json(['error' => 'Captcha verification failed']);
        }
   }
}
