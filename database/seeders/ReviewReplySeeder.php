<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\User;
use App\Models\ReviewReply;
use Illuminate\Database\Seeder;

class ReviewReplySeeder extends Seeder
{
    public function run(): void
    {
        $reviews = Review::all();
        $users = User::all();

        foreach ($reviews as $review) {
            $replyCount = rand(1, 3); // mỗi đánh giá có từ 1 đến 3 phản hồi

            for ($i = 0; $i < $replyCount; $i++) {
                ReviewReply::create([
                    'review_id' => $review->id,
                    'user_id' => $users->random()->id,
                    'content' => fake()->sentence(10),
                ]);
            }
        }
    }
}
