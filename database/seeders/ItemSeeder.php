<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\ItemImage;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Review;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\KarmaLog;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $students = User::where('role', 'student')->get();

        $items = [
            ['title' => 'Calculus: Early Transcendentals', 'description' => 'James Stewart 8th Edition. Some highlighting but overall great condition. Perfect for MATH 101.', 'price' => 45.00, 'category' => 'textbooks', 'condition' => 'used'],
            ['title' => 'MacBook Air M1 2020', 'description' => 'Space Gray, 256GB, 8GB RAM. Battery health 92%. Includes charger and case.', 'price' => 650.00, 'category' => 'electronics', 'condition' => 'used'],
            ['title' => 'IKEA Desk Lamp', 'description' => 'Adjustable LED desk lamp. White. Works perfectly. Moving out sale.', 'price' => 15.00, 'category' => 'furniture', 'condition' => 'fair'],
            ['title' => 'Nike Air Force 1 Size 10', 'description' => 'White, worn twice. Too small for me. Almost brand new.', 'price' => 80.00, 'category' => 'clothing', 'condition' => 'new'],
            ['title' => 'TI-84 Plus Calculator', 'description' => 'Graphing calculator in great working condition. Fresh batteries included.', 'price' => 55.00, 'category' => 'electronics', 'condition' => 'used'],
            ['title' => 'Organic Chemistry Textbook', 'description' => 'Clayden et al. 2nd Edition. Minimal wear, no markings inside.', 'price' => 35.00, 'category' => 'textbooks', 'condition' => 'used'],
            ['title' => 'Standing Desk Converter', 'description' => 'Adjustable height desk converter. Great for dorm rooms. Sturdy build.', 'price' => 120.00, 'category' => 'furniture', 'condition' => 'used'],
            ['title' => 'Wilson Tennis Racket', 'description' => 'Pro Staff 97. Grip size 4 3/8. Comes with 3 new tennis balls.', 'price' => 90.00, 'category' => 'sports', 'condition' => 'used'],
            ['title' => 'Concert Tickets x2 - Weekend', 'description' => 'Two GA tickets for the campus music festival. Cannot attend anymore.', 'price' => 50.00, 'category' => 'tickets', 'condition' => 'new'],
            ['title' => 'Notebook Bundle (5-pack)', 'description' => 'Five college-ruled notebooks. Unopened Moleskine pack.', 'price' => 12.00, 'category' => 'supplies', 'condition' => 'new'],
            ['title' => 'Dorm Mini Fridge', 'description' => 'Compact 3.2 cu ft mini fridge. Works great. Pick up only.', 'price' => 75.00, 'category' => 'furniture', 'condition' => 'fair'],
            ['title' => 'Sony WH-1000XM4 Headphones', 'description' => 'Noise-cancelling wireless headphones. Black. Excellent sound quality.', 'price' => 180.00, 'category' => 'electronics', 'condition' => 'used'],
            ['title' => 'Psychology 101 Textbook', 'description' => 'Myers Psychology 12th Edition. Good condition, some notes in margins.', 'price' => 25.00, 'category' => 'textbooks', 'condition' => 'fair'],
            ['title' => 'Yoga Mat - Extra Thick', 'description' => 'Purple 6mm yoga mat with carrying strap. Used for one semester.', 'price' => 18.00, 'category' => 'sports', 'condition' => 'used'],
            ['title' => 'Art Supply Kit', 'description' => 'Complete watercolor set with brushes, palette, and sketchpad. Perfect for ART 200.', 'price' => 35.00, 'category' => 'supplies', 'condition' => 'new'],
        ];

        foreach ($items as $i => $itemData) {
            $seller = $students[$i % $students->count()];
            $item = Item::create(array_merge($itemData, [
                'seller_id' => $seller->id,
                'status' => 'available',
                'views_count' => rand(5, 150),
            ]));
        }

        // Create some completed transactions for sustainability data
        $availableItems = Item::available()->get();
        for ($i = 0; $i < min(6, $availableItems->count()); $i++) {
            $item = $availableItems[$i];
            $buyer = $students->where('id', '!=', $item->seller_id)->random();

            $transaction = Transaction::create([
                'item_id' => $item->id,
                'buyer_id' => $buyer->id,
                'seller_id' => $item->seller_id,
                'amount' => $item->price,
                'status' => 'completed',
            ]);

            $item->update(['status' => 'sold']);

            // Add review
            Review::create([
                'transaction_id' => $transaction->id,
                'reviewer_id' => $buyer->id,
                'reviewed_user_id' => $item->seller_id,
                'item_id' => $item->id,
                'rating' => rand(3, 5),
                'comment' => ['Great seller!', 'Fast response, item as described.', 'Would buy again!', 'Good transaction.', 'Excellent condition, very happy!'][rand(0, 4)],
            ]);

            // Add karma log
            KarmaLog::create([
                'user_id' => $item->seller_id,
                'points' => 10,
                'action' => 'sale_completed',
                'description' => "Completed sale of \"{$item->title}\"",
                'reference_id' => $transaction->id,
                'reference_type' => Transaction::class,
            ]);
        }

        // Create sample conversations
        $activeItems = Item::available()->take(3)->get();
        foreach ($activeItems as $item) {
            $buyer = $students->where('id', '!=', $item->seller_id)->random();
            $convo = Conversation::create([
                'item_id' => $item->id,
                'buyer_id' => $buyer->id,
                'seller_id' => $item->seller_id,
                'last_message_at' => now(),
            ]);

            Message::create(['conversation_id' => $convo->id, 'sender_id' => $buyer->id, 'body' => "Hi! Is {$item->title} still available?"]);
            Message::create(['conversation_id' => $convo->id, 'sender_id' => $item->seller_id, 'body' => 'Yes it is! When can you pick it up?']);
            Message::create(['conversation_id' => $convo->id, 'sender_id' => $buyer->id, 'body' => 'How about tomorrow at the student center?']);
        }
    }
}
