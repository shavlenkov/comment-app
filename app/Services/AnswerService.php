<?php

namespace App\Services;

use App\Http\Resources\AnswerResource;
use App\Models\Answer;
use App\Models\Comment;
use ErrorException;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use ReCaptcha\ReCaptcha;

use App\Events\CommentReplied;

use App\Services\UserService;

class AnswerService
{

    private UserService $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    /**
     * Get answers to a comment.
     *
     * @param Comment $comment
     * @param Answer|null $answer
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getAnswers(Comment $comment, ?Answer $answer)
    {
        return AnswerResource::collection(
            $answer === null ? $comment->answers : $answer->childAnswers
        );
    }

    /**
     * Create a new answer to a comment and store it in the database.
     *
     * @param array $data
     * @param Comment $comment
     * @param Answer|null $answer3
     * @return \Illuminate\Http\JsonResponse
     */
    public function createAnswer(array $data, Comment $comment, ?Answer $answer) {

        $user = $this->userService->createUser($data);

        // If a file is provided, then upload it
        if (!empty($data['file'])) {

            $path = $data['file']->store('uploads', 'public');

            $fileExtension = $data['file']->getClientOriginalExtension();

            if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])) {
                $image = Image::make(storage_path('app/public/' . $path));

                // Resize the image
                $image->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                });

                // Check image size after resizing
                $width = $image->getWidth();
                $height = $image->getHeight();

                if ($width > 320 || $height > 240) {
                    $image->fit(320, 240);
                }

                // Save image
                $image->save(storage_path('app/public/' . $path));

                // Create a new answer with the processed image file path.
                $currentAnswer = Answer::create([
                    'user_id' => $user->id,
                    'text' => $data['text'],
                    'comment_id' => $comment->id,
                    'parent_id' => $answer?->id,
                    'file' => Storage::url($path)
                ]);
            } else {
                $currentAnswer = Answer::create([
                    'user_id' => $user->id,
                    'text' => $data['text'],
                    'comment_id' => $comment->id,
                    'parent_id' => $answer?->id,
                    'file' => Storage::url($path)
                ]);
            }

        } else {
            // Create a new answer with no image file.
            $currentAnswer = Answer::create([
                'user_id' => $user->id,
                'text' => $data['text'],
                'comment_id' => $comment->id,
                'parent_id' => $answer?->id,
                'file' => null
            ]);
        }

        event(new CommentReplied($currentAnswer));

    }

}
