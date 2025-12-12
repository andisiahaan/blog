<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // About Page
        Page::create([
            'title' => 'About Us',
            'slug' => 'about',
            'content' => '<h2>Welcome to Our Blog</h2>
<p>We are passionate about sharing knowledge and insights with our readers. Our blog covers a wide range of topics including technology, lifestyle, business, and more.</p>
<h3>Our Mission</h3>
<p>To provide high-quality, informative content that helps our readers learn, grow, and stay informed about the latest trends and developments in various fields.</p>
<h3>Our Team</h3>
<p>Our team consists of experienced writers, developers, and industry experts who are dedicated to creating valuable content for our community.</p>',
            'is_active' => true,
            'order' => 1,
        ]);

        // Terms of Service
        Page::create([
            'title' => 'Terms of Service',
            'slug' => 'terms-of-service',
            'content' => '<h2>Terms of Service</h2>
<p>Last updated: ' . date('F d, Y') . '</p>
<h3>1. Acceptance of Terms</h3>
<p>By accessing and using this website, you accept and agree to be bound by the terms and provision of this agreement.</p>
<h3>2. Use License</h3>
<p>Permission is granted to temporarily download one copy of the materials (information or software) on this website for personal, non-commercial transitory viewing only.</p>
<h3>3. User Account</h3>
<p>You are responsible for maintaining the confidentiality of your account and password and for restricting access to your computer.</p>
<h3>4. Disclaimer</h3>
<p>The materials on this website are provided on an \'as is\' basis. We make no warranties, expressed or implied, and hereby disclaim and negate all other warranties.</p>',
            'is_active' => true,
            'order' => 2,
        ]);

        // Privacy Policy
        Page::create([
            'title' => 'Privacy Policy',
            'slug' => 'privacy-policy',
            'content' => '<h2>Privacy Policy</h2>
<p>Last updated: ' . date('F d, Y') . '</p>
<h3>Information We Collect</h3>
<p>We collect information you provide directly to us, such as when you create an account, subscribe to our newsletter, or contact us.</p>
<h3>How We Use Your Information</h3>
<p>We use the information we collect to provide, maintain, and improve our services, and to communicate with you.</p>
<h3>Information Sharing</h3>
<p>We do not share your personal information with third parties except as described in this privacy policy.</p>
<h3>Cookies</h3>
<p>We use cookies and similar tracking technologies to track activity on our website and hold certain information.</p>
<h3>Contact Us</h3>
<p>If you have any questions about this Privacy Policy, please contact us.</p>',
            'is_active' => true,
            'order' => 3,
        ]);

        // Contact Page
        Page::create([
            'title' => 'Contact',
            'slug' => 'contact',
            'content' => '<h2>Contact Us</h2>
<p>We\'d love to hear from you! Whether you have a question, feedback, or just want to say hello, feel free to reach out.</p>
<h3>Email</h3>
<p>contact@blog.test</p>
<h3>Social Media</h3>
<p>Follow us on social media for the latest updates and content.</p>
<h3>Office Hours</h3>
<p>Monday - Friday: 9:00 AM - 5:00 PM</p>',
            'is_active' => true,
            'order' => 4,
        ]);
    }
}
