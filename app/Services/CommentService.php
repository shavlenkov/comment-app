<?php

namespace App\Services;

use App\Http\Resources\CommentResource;
use App\Models\Comment;
use ErrorException;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use ReCaptcha\ReCaptcha;

class CommentService
{

    private UserService $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    /**
     * Get comments with optional sorting based on the 'sort' query parameter.
     *
     * @param string $sort
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getComments(?string $sort) {
        $comments =  Comment::sort($sort)->simplePaginate(config('app.paginate'));

        return CommentResource::collection($comments);
    }

    /**
     * Create a new comment and store it in the database.
     *
     * @param array $data
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function createComment(array $data)
    {

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

            }

            // Create a new comment with the processed image file path.
            $comment = Comment::create([
                'user_id' => $user->id,
                'text' => $data['text'],
                'file' => Storage::url($path)
            ]);

        } else {
            // Create a new comment with no image file.
            $comment = Comment::create([
                'user_id' => $user->id,
                'text' => $data['text'],
                'file' => null
            ]);



        }

    }
}
