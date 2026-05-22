<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\{Role, User, Category, Size, Product, Blog, BlogDetail, Review};
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Roles ──────────────────────────────────────────────────
        $roles = [
            ['role_id' => 1, 'role_name' => 'Admin'],
            ['role_id' => 2, 'role_name' => 'Staff'],
            ['role_id' => 3, 'role_name' => 'Customer'],
        ];
        foreach ($roles as $r) {
            Role::updateOrCreate(['role_id' => $r['role_id']], $r);
        }

        // ── Users ──────────────────────────────────────────────────
        $admin = User::updateOrCreate(['email' => 'admin@luxestore.com'], [
            'username'   => 'admin',
            'first_name' => 'Admin',
            'last_name'  => 'User',
            'password'   => Hash::make('password'),
            'role_id'    => 1,
        ]);

        $staff = User::updateOrCreate(['email' => 'staff@luxestore.com'], [
            'username'   => 'staff_jane',
            'first_name' => 'Jane',
            'last_name'  => 'Smith',
            'password'   => Hash::make('password'),
            'role_id'    => 2,
        ]);

        $customers = [
            ['username' => 'john_doe',    'first_name' => 'John',    'last_name' => 'Doe',     'email' => 'john@example.com'],
            ['username' => 'sara_lee',    'first_name' => 'Sara',    'last_name' => 'Lee',     'email' => 'sara@example.com'],
            ['username' => 'mike_jones',  'first_name' => 'Mike',    'last_name' => 'Jones',   'email' => 'mike@example.com'],
            ['username' => 'emily_clark', 'first_name' => 'Emily',   'last_name' => 'Clark',   'email' => 'emily@example.com'],
            ['username' => 'alex_wong',   'first_name' => 'Alex',    'last_name' => 'Wong',    'email' => 'alex@example.com'],
        ];
        $customerUsers = [];
        foreach ($customers as $c) {
            $customerUsers[] = User::updateOrCreate(['email' => $c['email']], array_merge($c, [
                'password' => Hash::make('password'),
                'role_id'  => 3,
            ]));
        }

        // ── Categories ─────────────────────────────────────────────
        $categories = [
            ['category_name' => 'Men\'s Clothing',   'slug' => 'mens-clothing',    'description' => 'Premium menswear for every occasion'],
            ['category_name' => 'Women\'s Clothing',  'slug' => 'womens-clothing',  'description' => 'Elegant womenswear curated with care'],
            ['category_name' => 'Accessories',        'slug' => 'accessories',      'description' => 'Complete your look with our accessories'],
            ['category_name' => 'Footwear',           'slug' => 'footwear',         'description' => 'Step up your style with premium footwear'],
            ['category_name' => 'Bags & Wallets',     'slug' => 'bags-wallets',     'description' => 'Carry what matters in style'],
            ['category_name' => 'Sportswear',         'slug' => 'sportswear',       'description' => 'Performance meets style'],
        ];
        $catModels = [];
        foreach ($categories as $c) {
            $catModels[] = Category::updateOrCreate(['slug' => $c['slug']], $c);
        }

        // ── Sizes ──────────────────────────────────────────────────
        $sizeNames = ['XS', 'S', 'M', 'L', 'XL', 'XXL', '38', '39', '40', '41', '42', 'One Size'];
        $sizeModels = [];
        foreach ($sizeNames as $sn) {
            $sizeModels[] = Size::updateOrCreate(['size_name' => $sn]);
        }
        // Map size name => model
        $sizeMap = collect($sizeModels)->keyBy('size_name');

        // ── Products ───────────────────────────────────────────────
        $products = [
            // Men's Clothing
            [
                'product_name' => 'Classic Oxford Shirt',
                'description'  => 'A timeless Oxford shirt crafted from premium 100% cotton. Perfect for business or casual wear. Features a button-down collar and tailored fit.',
                'category'     => 'mens-clothing',
                'is_featured'  => true,
                'sizes'        => [['S', 89.99, 20], ['M', 89.99, 35], ['L', 89.99, 28], ['XL', 94.99, 15]],
            ],
            [
                'product_name' => 'Slim Fit Chinos',
                'description'  => 'Modern slim-fit chinos made from stretch cotton blend. Comfortable enough for all-day wear with a polished silhouette.',
                'category'     => 'mens-clothing',
                'is_featured'  => true,
                'sizes'        => [['S', 79.99, 12], ['M', 79.99, 30], ['L', 79.99, 22], ['XL', 84.99, 10]],
            ],
            [
                'product_name' => 'Merino Wool Crew Neck',
                'description'  => 'Ultra-soft merino wool sweater that regulates temperature naturally. Ideal for layering during cooler months.',
                'category'     => 'mens-clothing',
                'is_featured'  => false,
                'sizes'        => [['S', 129.99, 8], ['M', 129.99, 18], ['L', 129.99, 14]],
            ],
            [
                'product_name' => 'Tailored Blazer',
                'description'  => 'A sharp tailored blazer with a modern cut. Crafted from a premium wool blend for a sophisticated finish.',
                'category'     => 'mens-clothing',
                'is_featured'  => true,
                'sizes'        => [['S', 299.99, 5], ['M', 299.99, 8], ['L', 299.99, 6], ['XL', 319.99, 3]],
            ],
            // Women's Clothing
            [
                'product_name' => 'Silk Wrap Dress',
                'description'  => 'An effortlessly elegant wrap dress in lightweight silk. Flattering for all body types with a flowing silhouette.',
                'category'     => 'womens-clothing',
                'is_featured'  => true,
                'sizes'        => [['XS', 189.99, 6], ['S', 189.99, 14], ['M', 189.99, 20], ['L', 189.99, 10]],
            ],
            [
                'product_name' => 'High-Waist Trousers',
                'description'  => 'Structured high-waist trousers that elongate the silhouette. Features a wide-leg cut in premium crepe fabric.',
                'category'     => 'womens-clothing',
                'is_featured'  => false,
                'sizes'        => [['XS', 119.99, 8], ['S', 119.99, 16], ['M', 119.99, 14], ['L', 124.99, 7]],
            ],
            [
                'product_name' => 'Cashmere Turtleneck',
                'description'  => 'Pure cashmere turtleneck for ultimate luxury comfort. Incredibly soft with beautiful drape.',
                'category'     => 'womens-clothing',
                'is_featured'  => true,
                'sizes'        => [['XS', 219.99, 4], ['S', 219.99, 10], ['M', 219.99, 12], ['L', 219.99, 6]],
            ],
            [
                'product_name' => 'Linen Summer Blouse',
                'description'  => 'Breezy 100% linen blouse perfect for warm weather. Relaxed fit with delicate detailing.',
                'category'     => 'womens-clothing',
                'is_featured'  => false,
                'sizes'        => [['XS', 79.99, 15], ['S', 79.99, 22], ['M', 79.99, 18], ['L', 84.99, 9]],
            ],
            // Accessories
            [
                'product_name' => 'Italian Leather Belt',
                'description'  => 'Full-grain Italian leather belt with a brushed gold buckle. A wardrobe essential that only gets better with age.',
                'category'     => 'accessories',
                'is_featured'  => false,
                'sizes'        => [['S', 89.99, 25], ['M', 89.99, 30], ['L', 89.99, 20]],
            ],
            [
                'product_name' => 'Silk Pocket Square',
                'description'  => 'Hand-rolled silk pocket square with vibrant pattern. The perfect finishing touch for any formal outfit.',
                'category'     => 'accessories',
                'is_featured'  => false,
                'sizes'        => [['One Size', 49.99, 50]],
            ],
            [
                'product_name' => 'Minimalist Watch',
                'description'  => 'Swiss-movement minimalist watch with sapphire crystal glass. Slim 38mm case in stainless steel.',
                'category'     => 'accessories',
                'is_featured'  => true,
                'sizes'        => [['One Size', 349.99, 12]],
            ],
            // Footwear
            [
                'product_name' => 'Premium Leather Oxford',
                'description'  => 'Handcrafted leather Oxford shoes made in Italy. Goodyear welted construction for longevity and resolability.',
                'category'     => 'footwear',
                'is_featured'  => true,
                'sizes'        => [['39', 289.99, 5], ['40', 289.99, 8], ['41', 289.99, 10], ['42', 289.99, 7]],
            ],
            [
                'product_name' => 'Suede Chelsea Boots',
                'description'  => 'Premium suede Chelsea boots with elastic side panels. Versatile and stylish for any smart-casual occasion.',
                'category'     => 'footwear',
                'is_featured'  => false,
                'sizes'        => [['39', 199.99, 6], ['40', 199.99, 10], ['41', 199.99, 8], ['42', 199.99, 5]],
            ],
            // Bags
            [
                'product_name' => 'Structured Tote Bag',
                'description'  => 'A sophisticated structured tote in full-grain leather. Spacious interior with multiple compartments.',
                'category'     => 'bags-wallets',
                'is_featured'  => true,
                'sizes'        => [['One Size', 459.99, 8]],
            ],
            [
                'product_name' => 'Slim Bifold Wallet',
                'description'  => 'Ultra-slim bifold wallet in vegetable-tanned leather. Holds cards and bills without the bulk.',
                'category'     => 'bags-wallets',
                'is_featured'  => false,
                'sizes'        => [['One Size', 89.99, 35]],
            ],
            // Sportswear
            [
                'product_name' => 'Performance Running Tee',
                'description'  => 'Technical running tee with moisture-wicking fabric and four-way stretch. Reflective details for low-light visibility.',
                'category'     => 'sportswear',
                'is_featured'  => false,
                'sizes'        => [['S', 59.99, 20], ['M', 59.99, 30], ['L', 59.99, 25], ['XL', 59.99, 15]],
            ],
            [
                'product_name' => 'Yoga Leggings',
                'description'  => 'High-performance yoga leggings with compression support and squat-proof fabric.',
                'category'     => 'sportswear',
                'is_featured'  => true,
                'sizes'        => [['XS', 89.99, 12], ['S', 89.99, 18], ['M', 89.99, 22], ['L', 89.99, 10]],
            ],
        ];

        $catMap = Category::all()->keyBy('slug');
        $productModels = [];

        foreach ($products as $p) {
            $cat = $catMap[$p['category']] ?? $catModels[0];
            $product = Product::updateOrCreate(
                ['product_name' => $p['product_name']],
                [
                    'description' => $p['description'],
                    'slug'        => Str::slug($p['product_name']) . '-' . Str::random(6),
                    'category_id' => $cat->category_id,
                    'is_featured' => $p['is_featured'],
                    'is_active'   => true,
                ]
            );
            // Sync sizes
            $syncData = [];
            foreach ($p['sizes'] as [$sizeName, $price, $stock]) {
                if (isset($sizeMap[$sizeName])) {
                    $syncData[$sizeMap[$sizeName]->size_id] = ['price' => $price, 'stock_qty' => $stock];
                }
            }
            $product->sizes()->sync($syncData);
            $productModels[] = $product;
        }

        // ── Blog ───────────────────────────────────────────────────
        $blogPosts = [
            [
                'title'       => 'The Art of Dressing Well: A Guide to Building a Capsule Wardrobe',
                'subtitle'    => 'How fewer, better pieces can transform your style and simplify your life.',
                'description' => "Building a capsule wardrobe is one of the most transformative things you can do for your personal style. The concept is simple: a small collection of versatile, high-quality pieces that work together seamlessly.\n\nStart with neutral foundations — think navy, black, white, and grey. These colours form the backbone of any great wardrobe and mix effortlessly. Invest in quality basics: a well-fitting white shirt, a tailored blazer, dark slim trousers, and comfortable leather shoes.\n\nThe key is quality over quantity. Ten well-chosen pieces will serve you better than thirty mediocre ones. Look for natural fibres like cotton, wool, and linen — they breathe better, last longer, and look more luxurious.\n\nAccessories are where you can express personality. A beautiful watch, a statement bag, or a silk scarf can transform even the simplest outfit into something memorable.\n\nRemember: the best wardrobe is one that makes you feel confident every single day.",
                'tags'        => 'style, fashion, capsule wardrobe',
            ],
            [
                'title'       => 'How to Care for Leather: Extending the Life of Your Investment Pieces',
                'subtitle'    => 'Simple maintenance routines that keep your leather goods looking pristine for years.',
                'description' => "Leather is one of nature's most remarkable materials — it improves with age when properly cared for. Whether it's a structured handbag, Chelsea boots, or a slim wallet, the right maintenance routine makes all the difference.\n\nFirst, always condition your leather regularly. Use a high-quality leather conditioner every 3–6 months to keep the material supple and prevent cracking. Apply with a soft cloth in circular motions and allow it to absorb before buffing.\n\nFor shoes, use a good shoe tree when not in use — this maintains shape and absorbs moisture. Polish regularly with a colour-matched cream to maintain lustre and protect the surface.\n\nStorage matters too. Keep leather goods in their dust bags when not in use and store in a cool, dry place away from direct sunlight. Never store leather in plastic, which can cause mildew.\n\nWith proper care, quality leather pieces last decades — making them the ultimate sustainable fashion choice.",
                'tags'        => 'leather care, accessories, sustainability',
            ],
            [
                'title'       => 'The Return of Tailoring: Why Custom Fit is Having a Moment',
                'subtitle'    => 'From boardroom to weekend wear, the perfectly fitted garment is back in focus.',
                'description' => "After years of oversized silhouettes dominating fashion runways, tailoring is making a triumphant comeback. And not just for formal occasions — the principles of great fit are now being applied to everything from casual trousers to weekend knitwear.\n\nThe resurgence is driven partly by a growing appreciation for quality over quantity. When you own fewer, better pieces, each one must earn its place. A perfectly fitted Oxford shirt doesn't need much else — it simply looks right.\n\nBespoke tailoring remains the ultimate expression of this movement. Working with a skilled tailor allows you to choose every detail: the lapel width, button stance, pocket style, and lining. The result is a garment that fits your unique body perfectly.\n\nBut excellent fit doesn't require bespoke prices. Many ready-to-wear brands now offer extended size ranges and use sophisticated pattern-making to achieve a near-perfect fit off the rack. The key is knowing your measurements and being willing to try different cuts.\n\nInvest in alterations. Even a modest off-the-rack suit transformed by a skilled tailor looks significantly better than an expensive one worn as purchased.",
                'tags'        => 'tailoring, menswear, fashion trends',
            ],
        ];

        foreach ($blogPosts as $bp) {
            $blog = Blog::updateOrCreate(['author_name' => 'LuxeStore Editorial'], [
                'author_name' => 'LuxeStore Editorial',
                'user_id'     => $admin->user_id,
            ]);
            BlogDetail::updateOrCreate(['title' => $bp['title']], array_merge($bp, [
                'blog_id'      => $blog->blog_id,
                'slug'         => Str::slug($bp['title']) . '-' . Str::random(6),
                'is_published' => true,
            ]));
        }

        // ── Reviews ────────────────────────────────────────────────
        $reviewData = [
            ['title' => 'Absolutely love this!',       'desc' => 'The quality is outstanding. I\'ve washed it multiple times and it still looks brand new. Highly recommend to everyone.', 'rating' => 5],
            ['title' => 'Great value for money',       'desc' => 'I was hesitant at first but this exceeded my expectations. The fabric is premium and the fit is perfect.', 'rating' => 5],
            ['title' => 'Good quality, fast shipping', 'desc' => 'Arrived in two days and the packaging was beautiful. The product is exactly as described.', 'rating' => 4],
            ['title' => 'Very satisfied',              'desc' => 'This is my third purchase from LuxeStore. Consistently excellent quality and service.', 'rating' => 5],
            ['title' => 'Perfect gift',                'desc' => 'Bought this as a gift and my partner absolutely loved it. Will definitely be shopping here again.', 'rating' => 5],
            ['title' => 'Exceeded expectations',       'desc' => 'I didn\'t expect this level of quality at this price point. LuxeStore has earned a loyal customer.', 'rating' => 5],
        ];

        foreach ($reviewData as $i => $rv) {
            $user    = $customerUsers[$i % count($customerUsers)];
            $product = $productModels[$i % count($productModels)];
            Review::updateOrCreate(
                ['user_id' => $user->user_id, 'review_title' => $rv['title']],
                [
                    'description' => $rv['desc'],
                    'rating'      => $rv['rating'],
                    'product_id'  => $product->product_id,
                    'is_approved' => true,
                ]
            );
        }

        $this->command->info('✅  Database seeded successfully!');
        $this->command->info('   Admin:  admin@luxestore.com  / password');
        $this->command->info('   Staff:  staff@luxestore.com  / password');
        $this->command->info('   User:   john@example.com     / password');
    }
}
