<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comment;
use Carbon\Carbon;

class FixCommentDatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all comments
        $comments = Comment::all();

        foreach ($comments as $comment) {
            // Set created_at to a random time in the past 30 days
            $createdAt = Carbon::now()->subDays(rand(1, 30))->subHours(rand(1, 24));
            
            // Set updated_at to a time after created_at but still in the past
            $updatedAt = (clone $createdAt)->addHours(rand(0, 48));

            $comment->update([
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ]);
        }
    }
}
