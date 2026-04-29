<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(
            function () {
                // Insert John Doe into the users table
                DB::table('users')->insert([
                    'id' => 1,
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                    'username' => 'johndoe',
                    'email' => 'john.doe@example.com',
                    'password' => Hash::make('password'),
                    'created_at' => new \DateTime('2026-02-09 10:00:00'),
                    'updated_at' => new \DateTime('2026-02-09 10:00:00'),
                ]);

                // Insert Jane Doe into the users table
                DB::table('users')->insert([
                    'id' => 2,
                    'first_name' => 'Jane',
                    'last_name' => 'Doe',
                    'username' => 'janedoe',
                    'email' => 'jane.doe@example.com',
                    'password' => Hash::make('password'),
                    'created_at' => new \DateTime('2026-02-09 11:00:00'),
                    'updated_at' => new \DateTime('2026-02-09 11:00:00'),
                ]);

                // Insert some posts for John Doe
                DB::table('posts')->insert([
                    [
                        'id' => 1,
                        'user_id' => 1,
                        'title' => "John's First Post",
                        'content' => "This is the content of John's first post.",
                        'created_at' => new \DateTime('2026-02-09 12:00:00'),
                        'updated_at' => new \DateTime('2026-02-09 12:00:00'),
                    ],
                    [
                        'id' => 2,
                        'user_id' => 1,
                        'title' => null,
                        'content' => "This is the content of John's second post.",
                        'created_at' => new \DateTime('2026-02-09 12:05:00'),
                        'updated_at' => new \DateTime('2026-02-09 12:05:00'),
                    ],
                    [
                        'id' => 3,
                        'user_id' => 1,
                        'title' => null,
                        'content' => "This is the content of John's third post.",
                        'created_at' => new \DateTime('2026-02-09 12:10:00'),
                        'updated_at' => new \DateTime('2026-02-09 12:10:00'),
                    ]
                ]);

                // Insert some posts for Jane Doe
                DB::table('posts')->insert([
                    [
                        'id' => 4,
                        'user_id' => 2,
                        'title' => null,
                        'content' => "This is the content of Jane's first post.",
                        'created_at' => new \DateTime('2026-02-09 12:05:00'),
                        'updated_at' => new \DateTime('2026-02-09 12:05:00'),
                    ],
                    [
                        'id' => 5,
                        'user_id' => 2,
                        'title' => "Jane's Second Post",
                        'content' => "This is the content of Jane's second post.",
                        'created_at' => new \DateTime('2026-02-09 12:10:00'),
                        'updated_at' => new \DateTime('2026-02-09 12:10:00'),
                    ],
                    [
                        'id' => 6,
                        'user_id' => 2,
                        'title' => "Jane's Third Post",
                        'content' => "This is the content of Jane's third post.",
                        'created_at' => new \DateTime('2026-02-09 12:15:00'),
                        'updated_at' => new \DateTime('2026-02-09 12:15:00'),
                    ]
                ]);

                // Insert some likes for John's posts
                DB::table('likes')->insert([
                    [
                        'user_id' => 2,
                        'post_id' => 1,
                        'reaction' => 'like',
                        'created_at' => new \DateTime('2026-02-09 12:20:00'),
                        'updated_at' => new \DateTime('2026-02-09 12:20:00'),
                    ],
                    [
                        'user_id' => 1, // John likes his own post
                        'post_id' => 2,
                        'reaction' => 'love',
                        'created_at' => new \DateTime('2026-02-09 12:25:00'),
                        'updated_at' => new \DateTime('2026-02-09 12:25:00'),
                    ],
                ]);

                // Insert some likes for Jane's posts
                DB::table('likes')->insert([
                    [
                        'user_id' => 1,
                        'post_id' => 4,
                        'reaction' => 'like',
                        'created_at' => new \DateTime('2026-02-09 12:30:00'),
                        'updated_at' => new \DateTime('2026-02-09 12:30:00'),
                    ],
                    [
                        'user_id' => 1,
                        'post_id' => 5,
                        'reaction' => 'love',
                        'created_at' => new \DateTime('2026-02-09 12:35:00'),
                        'updated_at' => new \DateTime('2026-02-09 12:35:00'),
                    ],
                    [
                        'user_id' => 2, // Jane likes her own post
                        'post_id' => 5,
                        'reaction' => 'wow',
                        'created_at' => new \DateTime('2026-02-09 12:40:00'),
                        'updated_at' => new \DateTime('2026-02-09 12:40:00'),
                    ]
                ]);

                // Insert some test comments to demonstrate the comment system:
                // - Jane comments on John's post (cross-user)
                // - John comments on his own post (self-comment)
                // - John comments on Jane's post
                // - Jane answers on her own post
                DB::table('comments')->insert([
                    [
                        'user_id' => 2, // Jane comments on John's first post
                        'post_id' => 1,
                        'content' => 'Great first post, John! Really enjoyed reading it.',
                        'created_at' => new \DateTime('2026-02-09 12:45:00'),
                        'updated_at' => new \DateTime('2026-02-09 12:45:00'),
                    ],
                    [
                        'user_id' => 1, // John replies on his own post
                        'post_id' => 1,
                        'content' => 'Thanks Jane, glad you liked it!',
                        'created_at' => new \DateTime('2026-02-09 12:50:00'),
                        'updated_at' => new \DateTime('2026-02-09 12:50:00'),
                    ],
                    [
                        'user_id' => 1, // John comments on Jane's second post
                        'post_id' => 5,
                        'content' => 'Interesting perspective, Jane. I totally agree!',
                        'created_at' => new \DateTime('2026-02-09 13:00:00'),
                        'updated_at' => new \DateTime('2026-02-09 13:00:00'),
                    ],
                    [
                        'user_id' => 2, // Jane replies on her own post
                        'post_id' => 5,
                        'content' => 'Thank you John, I appreciate the kind words.',
                        'created_at' => new \DateTime('2026-02-09 13:05:00'),
                        'updated_at' => new \DateTime('2026-02-09 13:05:00'),
                    ],
                ]);
            }
        );
    }
}
