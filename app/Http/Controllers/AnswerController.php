<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentOrAnswerRequest;
use App\Models\Comment;
use App\Models\Answer;
use App\Services\AnswerService;
use ErrorException;

class AnswerController extends Controller
{

    private AnswerService $answerService;

    /**
     * AnswerController constructor.
     *
     * @param AnswerService $answerService
     */
    public function __construct(AnswerService $answerService) {
        $this->answerService = $answerService;
    }

    /**
     * Get a collection of answers to a comment.
     *
     * @param Comment $comment
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Comment $comment)
    {
        return $this->answerService->getAnswers($comment, null);
    }

    /**
     * Store a new answer to a comment.
     *
     * @param StoreCommentOrAnswerRequest $request
     * @param Comment $comment
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreCommentOrAnswerRequest $request, Comment $comment)
    {
        try {
            $data = $request->validated();

            $this->answerService->createAnswer($data, $comment, null);

            return response()->json(['success' => true]);
        } catch (ErrorException $e) {
            return response()->json(['error' => 'Captcha verification failed']);
        }
    }

    /**
     * Get a collection of child answers to an answer.
     *
     * @param Comment $comment
     * @param Answer $answer
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getChildAnswers(Comment $comment, Answer $answer)
    {
        return $this->answerService->getAnswers($comment, $answer);
    }

    /**
     * Store a new child answer to an answer.
     *
     * @param StoreCommentOrAnswerRequest $request
     * @param Comment $comment
     * @param Answer $answer
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeChildAnswer(StoreCommentOrAnswerRequest $request, Comment $comment, Answer $answer)
    {

        try {
            $data = $request->validated();

            $this->answerService->createAnswer($data, $comment, $answer);

            return response()->json(['success' => true]);
        } catch (ErrorException $e) {
            return response()->json(['error' => 'Captcha verification failed']);
        }

    }

}
