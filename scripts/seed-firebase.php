<?php

declare(strict_types=1);

use App\Services\Firebase\FirestoreRepository;

require __DIR__.'/../vendor/autoload.php';

$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

/** @var FirestoreRepository $repository */
$repository = $app->make(FirestoreRepository::class);

$documents = [
    'admins' => [
        [
            'id' => 'clinic-admin',
            'name' => 'Clinic Admin',
            'email' => 'rashan.pani470@gmail.com',
            'role' => 'superadmin',
        ],
    ],
    'doctors' => [
        [
            'id' => 'dr-neema',
            'name' => 'Dr. Neema Sharma',
            'specialization' => 'Pediatric Dentistry',
            'experience' => '8 years',
            'qualification' => 'BDS, Pediatric Fellowship',
            'bio' => 'Dr. Neema is a compassionate pediatric dentist focused on gentle care for children.',
            'phone' => '+1 (555) 210-8801',
            'languages' => ['English', 'Hindi'],
            'consultation_fee' => 6000,
            'available_days' => ['Monday', 'Tuesday', 'Thursday'],
            'photo' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=900&q=80',
        ],
        [
            'id' => 'dr-aparna',
            'name' => 'Dr. Aparna Mehta',
            'specialization' => 'Cosmetic Dentistry',
            'experience' => '12 years',
            'qualification' => 'DDS, University of Pennsylvania',
            'bio' => 'Expert in smile makeovers and aesthetic dentistry with patient-first care.',
            'phone' => '+1 (555) 210-8802',
            'languages' => ['English'],
            'consultation_fee' => 12000,
            'available_days' => ['Monday', 'Wednesday', 'Friday'],
            'photo' => 'https://images.unsplash.com/photo-1559839734-2b71ea197ec2?auto=format&fit=crop&w=900&q=80',
        ],
        [
            'id' => 'dr-ramesh',
            'name' => 'Dr. Ramesh Patel',
            'specialization' => 'Orthodontics',
            'experience' => '10 years',
            'qualification' => 'MDS Orthodontics',
            'bio' => 'Orthodontist specializing in modern braces and clear aligners.',
            'phone' => '+1 (555) 210-8803',
            'languages' => ['English', 'Gujarati'],
            'consultation_fee' => 9000,
            'available_days' => ['Tuesday', 'Thursday', 'Saturday'],
            'photo' => 'https://images.unsplash.com/photo-1537368910025-700350fe46c7?auto=format&fit=crop&w=900&q=80',
        ],
    ],
    'availability' => [
        [
            'id' => 'dr-neema',
            'doctor_id' => 'dr-neema',
            'available_days' => ['Monday', 'Tuesday', 'Thursday'],
            'available_time_slots' => ['09:00 AM', '10:00 AM', '11:00 AM', '02:00 PM', '03:00 PM', '04:00 PM'],
            'unavailable_dates' => [],
        ],
        [
            'id' => 'dr-aparna',
            'doctor_id' => 'dr-aparna',
            'available_days' => ['Monday', 'Wednesday', 'Friday'],
            'available_time_slots' => ['09:00 AM', '10:00 AM', '11:00 AM', '02:00 PM', '03:00 PM', '04:00 PM'],
            'unavailable_dates' => [],
        ],
        [
            'id' => 'dr-ramesh',
            'doctor_id' => 'dr-ramesh',
            'available_days' => ['Tuesday', 'Thursday', 'Saturday'],
            'available_time_slots' => ['09:00 AM', '10:00 AM', '11:00 AM', '02:00 PM', '03:00 PM', '04:00 PM'],
            'unavailable_dates' => [],
        ],
    ],
    'services' => [
        [
            'id' => 'dental-cleaning',
            'slug' => 'dental-cleaning',
            'name' => 'Dental Cleaning',
            'description' => 'Professional cleaning and preventive care to keep your smile healthy and fresh.',
            'benefits' => ['Plaque removal', 'Gum health', 'Fresh breath'],
            'price' => 6000,
            'currency' => 'INR',
            'duration_minutes' => 30,
            'image' => 'https://images.unsplash.com/photo-1588776814546-ec7b4f3e4d5b?auto=format&fit=crop&w=1200&q=80',
        ],
        [
            'id' => 'braces',
            'slug' => 'braces',
            'name' => 'Braces',
            'description' => 'Discreet and effective orthodontic treatment for perfectly aligned teeth.',
            'benefits' => ['Smile alignment', 'Improved bite', 'Long-term oral health'],
            'price' => 120000,
            'currency' => 'INR',
            'duration_minutes' => 90,
            'image' => 'https://images.unsplash.com/photo-1598256989800-fe5f95da9787?auto=format&fit=crop&w=1200&q=80',
        ],
        [
            'id' => 'root-canal',
            'slug' => 'root-canal',
            'name' => 'Root Canal',
            'description' => 'Relieve pain and save infected teeth with precision endodontic treatment.',
            'benefits' => ['Pain relief', 'Tooth preservation', 'Quick recovery'],
            'price' => 35000,
            'currency' => 'INR',
            'duration_minutes' => 60,
            'image' => 'https://images.unsplash.com/photo-1606811841689-23dfddce3e95?auto=format&fit=crop&w=1200&q=80',
        ],
        [
            'id' => 'teeth-whitening',
            'slug' => 'teeth-whitening',
            'name' => 'Teeth Whitening',
            'description' => 'Lift stains and brighten your smile with safe, professional whitening.',
            'benefits' => ['Brighter smile', 'Fast results', 'Confidence boost'],
            'price' => 15000,
            'currency' => 'INR',
            'duration_minutes' => 45,
            'image' => 'https://images.unsplash.com/photo-1578991624414-276c4378e93d?auto=format&fit=crop&w=1200&q=80',
        ],
        [
            'id' => 'dental-implants',
            'slug' => 'dental-implants',
            'name' => 'Dental Implants',
            'description' => 'Permanent replacement solutions designed for strength and natural aesthetics.',
            'benefits' => ['Natural look', 'Long lifespan', 'Restored function'],
            'price' => 250000,
            'currency' => 'INR',
            'duration_minutes' => 120,
            'image' => 'https://images.unsplash.com/photo-1629909613654-28e377c37c49?auto=format&fit=crop&w=1200&q=80',
        ],
        [
            'id' => 'tooth-extraction',
            'slug' => 'tooth-extraction',
            'name' => 'Tooth Extraction',
            'description' => 'Gentle extraction service with careful post-operative guidance.',
            'benefits' => ['Comfort-first approach', 'Safe healing', 'Expert aftercare'],
            'price' => 9000,
            'currency' => 'INR',
            'duration_minutes' => 30,
            'image' => 'https://images.unsplash.com/photo-1581594693702-fbdc3f5d8d57?auto=format&fit=crop&w=1200&q=80',
        ],
        [
            'id' => 'pediatric-dentistry',
            'slug' => 'pediatric-dentistry',
            'name' => 'Pediatric Dentistry',
            'description' => 'Friendly dental care tailored to children for healthy habits early on.',
            'benefits' => ['Child-friendly', 'Prevention focused', 'Gentle care'],
            'price' => 5000,
            'currency' => 'INR',
            'duration_minutes' => 30,
            'image' => 'https://images.unsplash.com/photo-1537368910025-700350fe46c7?auto=format&fit=crop&w=1200&q=80',
        ],
        [
            'id' => 'cosmetic-dentistry',
            'slug' => 'cosmetic-dentistry',
            'name' => 'Cosmetic Dentistry',
            'description' => 'Customized smile design treatments for a polished, confident result.',
            'benefits' => ['Smile makeovers', 'Aesthetic balance', 'Modern techniques'],
            'price' => 20000,
            'currency' => 'INR',
            'duration_minutes' => 60,
            'image' => 'https://images.unsplash.com/photo-1576058028449-9c7a0f3c8c15?auto=format&fit=crop&w=1200&q=80',
        ],
    ],
    'testimonials' => [
        [
            'id' => 'testimonial-1',
            'name' => 'Samantha K.',
            'role' => 'Patient',
            'message' => 'SmileCare made my treatment comfortable, modern, and reassuring from the first visit.',
            'rating' => 5,
            'avatar' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=300&q=80',
        ],
        [
            'id' => 'testimonial-2',
            'name' => 'Daniel R.',
            'role' => 'Parent',
            'message' => 'The pediatric team was warm and patient. My child actually enjoyed the appointment.',
            'rating' => 5,
            'avatar' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=300&q=80',
        ],
    ],
    'gallery' => [
        [
            'id' => 'gallery-1',
            'title' => 'Reception Lounge',
            'category' => 'Clinic Experience',
            'image' => 'https://images.unsplash.com/photo-1629909615184-74f2f1d07d6c?auto=format&fit=crop&w=1200&q=80',
        ],
        [
            'id' => 'gallery-2',
            'title' => 'Treatment Suite',
            'category' => 'Modern Care',
            'image' => 'https://images.unsplash.com/photo-1606265752439-ef7d0a5f2b1f?auto=format&fit=crop&w=1200&q=80',
        ],
        [
            'id' => 'gallery-3',
            'title' => 'Smile Makeover',
            'category' => 'Cosmetic Dentistry',
            'image' => 'https://images.unsplash.com/photo-1576013551627-0b7dfe71e7c8?auto=format&fit=crop&w=1200&q=80',
        ],
    ],
    'settings' => [
        [
            'id' => 'general',
            'clinic_name' => 'Radhe Dental Clinic',
            'phone' => '+91 98765 43210',
            'email' => 'contact@radhedental.in',
            'address' => '12 MG Road, Mumbai, Maharashtra 400001, India',
            'timezone' => 'Asia/Kolkata',
            'business_hours' => [
                'monday' => ['09:00', '17:00'],
                'tuesday' => ['09:00', '17:00'],
                'wednesday' => ['09:00', '17:00'],
                'thursday' => ['09:00', '17:00'],
                'friday' => ['09:00', '17:00'],
                'saturday' => ['10:00', '14:00'],
            ],
        ],
    ],
];

foreach ($documents as $collection => $items) {
    foreach ($items as $item) {
        try {
            $repository->create($collection, $item, $item['id'] ?? null);
            echo "Seeded {$collection}/{$item['id']}\n";
        } catch (RuntimeException $e) {
            $body = $e->getMessage();
            // If document already exists, attempt an update (idempotent seeding)
            if (stripos($body, 'ALREADY_EXISTS') !== false || stripos($body, 'already exists') !== false) {
                if (! empty($item['id'])) {
                    $repository->update($collection, $item['id'], $item);
                    echo "Updated {$collection}/{$item['id']} (existing)\n";
                    continue;
                }
            }

            // Re-throw unexpected errors to surface them
            throw $e;
        }
    }
}
