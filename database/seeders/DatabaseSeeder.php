<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\AppointmentSchedule;
use App\Models\DentalAppointment;
use App\Models\TeleconsultationRequest;
use App\Models\MedicalCertificateRequest;
use App\Models\HealthInquiry;
use App\Models\InquiryResponse;
use App\Models\HealthArticle;
use App\Models\Announcement;
use App\Models\AppNotification;
use App\Models\ActivityLog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with demonstration data.
     */
    public function run(): void
    {
        // 1. Create Users
        $admin = User::firstOrCreate(
            ['email' => 'admin@adamson.edu.ph'],
            [
                'name' => 'Health Services Admin',
                'password' => Hash::make('Admin@AdU2026'),
                'role' => 'admin',
                'id_number' => 'ADM-2020-001',
                'department_or_course' => 'Health Services Office',
                'contact_number' => '0917-111-2233',
                'emergency_contact_name' => 'AdU Clinic Security',
                'emergency_contact_number' => '02-8524-2011',
                'gender' => 'Prefer not to say',
                'is_active' => true,
            ]
        );

        $student1 = User::firstOrCreate(
            ['email' => 'student@adamson.edu.ph'],
            [
                'name' => 'Juan Dela Cruz',
                'password' => Hash::make('Student@AdU2026'),
                'role' => 'student',
                'id_number' => '2022-10842',
                'department_or_course' => 'BS Computer Engineering',
                'contact_number' => '0918-234-5678',
                'emergency_contact_name' => 'Maria Dela Cruz (Mother)',
                'emergency_contact_number' => '0919-876-5432',
                'gender' => 'Male',
                'birth_date' => '2004-05-15',
                'is_active' => true,
            ]
        );

        $student2 = User::firstOrCreate(
            ['email' => 'maria.clara@adamson.edu.ph'],
            [
                'name' => 'Maria Clara De Leon',
                'password' => Hash::make('Student@AdU2026'),
                'role' => 'student',
                'id_number' => '2023-14521',
                'department_or_course' => 'BS Nursing',
                'contact_number' => '0922-456-7890',
                'emergency_contact_name' => 'Clara De Leon (Mother)',
                'emergency_contact_number' => '0923-987-1234',
                'gender' => 'Female',
                'birth_date' => '2005-09-20',
                'is_active' => true,
            ]
        );

        $faculty = User::firstOrCreate(
            ['email' => 'faculty@adamson.edu.ph'],
            [
                'name' => 'Prof. Roberto Santos',
                'password' => Hash::make('Faculty@AdU2026'),
                'role' => 'faculty_staff',
                'id_number' => 'EMP-2015-084',
                'department_or_course' => 'College of Science & Mathematics',
                'contact_number' => '0920-345-6789',
                'emergency_contact_name' => 'Elena Santos (Spouse)',
                'emergency_contact_number' => '0921-987-6543',
                'gender' => 'Male',
                'birth_date' => '1982-11-04',
                'is_active' => true,
            ]
        );

        // 2. Create Appointment Schedules for the next few days/weeks
        $dates = [
            now()->addDays(1)->toDateString(),
            now()->addDays(2)->toDateString(),
            now()->addDays(3)->toDateString(),
            now()->addDays(5)->toDateString(),
            now()->addDays(7)->toDateString(),
            now()->addDays(8)->toDateString(),
        ];

        $timeSlots = [
            ['start' => '08:00:00', 'end' => '10:00:00'],
            ['start' => '10:00:00', 'end' => '12:00:00'],
            ['start' => '13:00:00', 'end' => '15:00:00'],
            ['start' => '15:00:00', 'end' => '17:00:00'],
            ['start' => '18:00:00', 'end' => '20:00:00'],
        ];

        $createdSchedules = [];
        foreach ($dates as $date) {
            foreach ($timeSlots as $slot) {
                $sched = AppointmentSchedule::firstOrCreate(
                    [
                        'schedule_date' => $date,
                        'start_time' => $slot['start'],
                    ],
                    [
                        'end_time' => $slot['end'],
                        'max_slots' => 4,
                        'is_active' => true,
                        'notes' => 'General Dental Clinic Shift (Room 204)',
                    ]
                );
                $createdSchedules[] = $sched;
            }
        }

        // 3. Seed Dental Appointments
        if (!empty($createdSchedules)) {
            DentalAppointment::firstOrCreate(
                [
                    'user_id' => $student1->id,
                    'appointment_date' => $createdSchedules[0]->schedule_date,
                    'appointment_schedule_id' => $createdSchedules[0]->id,
                ],
                [
                    'appointment_time' => '09:00 AM - 10:00 AM',
                    'reason_for_visit' => 'Oral Prophylaxis (Cleaning)',
                    'specific_concerns' => 'Routine biannual dental cleaning and calculus check.',
                    'status' => 'approved',
                    'admin_notes' => 'Please bring your Adamson University ID and toothbrush.',
                ]
            );

            DentalAppointment::firstOrCreate(
                [
                    'user_id' => $student1->id,
                    'appointment_date' => $createdSchedules[2]->schedule_date,
                    'appointment_schedule_id' => $createdSchedules[2]->id,
                ],
                [
                    'appointment_time' => '01:00 PM - 02:00 PM',
                    'reason_for_visit' => 'Tooth Filling (Composite Restoration)',
                    'specific_concerns' => 'Slight sensitivity in lower left molar when drinking cold water.',
                    'status' => 'pending',
                ]
            );

            DentalAppointment::firstOrCreate(
                [
                    'user_id' => $student2->id,
                    'appointment_date' => $createdSchedules[1]->schedule_date,
                    'appointment_schedule_id' => $createdSchedules[1]->id,
                ],
                [
                    'appointment_time' => '10:00 AM - 11:00 AM',
                    'reason_for_visit' => 'Dental Examination & Consultation',
                    'specific_concerns' => 'Pre-clinical internship dental clearance form completion.',
                    'status' => 'approved',
                    'admin_notes' => 'Verified. Clinical form will be signed upon examination.',
                ]
            );

            DentalAppointment::firstOrCreate(
                [
                    'user_id' => $faculty->id,
                    'appointment_date' => $createdSchedules[4]->schedule_date,
                    'appointment_schedule_id' => $createdSchedules[4]->id,
                ],
                [
                    'appointment_time' => '09:00 AM - 10:00 AM',
                    'reason_for_visit' => 'Toothache Consultation',
                    'specific_concerns' => 'Persistent toothache on upper right premolar since yesterday.',
                    'status' => 'pending',
                ]
            );
        }

        // 4. Seed Teleconsultations
        TeleconsultationRequest::firstOrCreate(
            [
                'user_id' => $student1->id,
                'category' => 'General Health Consultation',
            ],
            [
                'preferred_date' => now()->addDays(2)->toDateString(),
                'preferred_time' => '10:30 AM - 11:30 AM',
                'description' => 'Feeling fatigued and mild dry cough for the past 2 days. Requesting medical advice and recommendations.',
                'preferred_contact_method' => 'Video Meeting Link (Google Meet / Zoom)',
                'contact_details' => 'juan.delacruz@adamson.edu.ph / 0918-234-5678',
                'status' => 'approved',
                'meeting_link' => 'https://meet.google.com/adu-care-med',
                'assigned_personnel' => 'Dr. Maria Ramos, MD (University Physician)',
                'admin_notes' => 'Approved. Please test your camera and microphone 5 minutes before the session.',
                'scheduled_at' => now()->addDays(2)->setTime(10, 30),
            ]
        );

        TeleconsultationRequest::firstOrCreate(
            [
                'user_id' => $student2->id,
                'category' => 'Mental Well-Being Support',
            ],
            [
                'preferred_date' => now()->addDays(3)->toDateString(),
                'preferred_time' => '02:30 PM - 03:30 PM',
                'description' => 'Experiencing high academic stress and difficulty sleeping due to upcoming finals. Would like guidance and coping strategies.',
                'preferred_contact_method' => 'Video Meeting Link (Google Meet / Zoom)',
                'contact_details' => '0922-456-7890',
                'status' => 'scheduled',
                'meeting_link' => 'https://meet.google.com/adu-care-well',
                'assigned_personnel' => 'Counselor Ana Patricia Gomez, RPm',
                'admin_notes' => 'Scheduled with University Guidance and Mental Health Support team.',
                'scheduled_at' => now()->addDays(3)->setTime(14, 30),
            ]
        );

        TeleconsultationRequest::firstOrCreate(
            [
                'user_id' => $faculty->id,
                'category' => 'Follow-up Checkup',
            ],
            [
                'preferred_date' => now()->addDays(4)->toDateString(),
                'preferred_time' => '01:30 PM - 02:30 PM',
                'description' => 'Follow-up check on blood pressure monitor readings and maintenance medication review.',
                'preferred_contact_method' => 'Phone Call',
                'contact_details' => '0920-345-6789',
                'status' => 'pending',
            ]
        );

        // 5. Seed Medical Certificate Requests
        MedicalCertificateRequest::firstOrCreate(
            [
                'user_id' => $student1->id,
                'purpose_of_request' => 'Excuse slip for missed classes due to viral gastroenteritis',
            ],
            [
                'certificate_type' => 'Fit to Return to Class / Work',
                'consultation_date' => now()->subDays(2)->toDateString(),
                'additional_information' => 'Was absent from Oct 20 to Oct 22 due to stomach flu and dehydration. Fully recovered.',
                'status' => 'released',
                'certificate_number' => 'ADU-MC-2026-0089',
                'admin_notes' => 'Validated by Dr. Ramos. Official digital certificate issued and verified.',
                'released_at' => now()->subDay(),
            ]
        );

        MedicalCertificateRequest::firstOrCreate(
            [
                'user_id' => $student2->id,
                'purpose_of_request' => 'Hospital clinical internship duty medical clearance',
            ],
            [
                'certificate_type' => 'OJT / Internship Medical Clearance',
                'consultation_date' => now()->subDays(5)->toDateString(),
                'additional_information' => 'Submitting required chest X-ray and CBC lab results for clearance.',
                'status' => 'under_review',
                'admin_notes' => 'Laboratory findings currently under review by attending clinic physician.',
            ]
        );

        MedicalCertificateRequest::firstOrCreate(
            [
                'user_id' => $faculty->id,
                'purpose_of_request' => 'Excuse slip validation for faculty meeting absence',
            ],
            [
                'certificate_type' => 'Excuse Slip Medical Validation',
                'consultation_date' => now()->subDays(1)->toDateString(),
                'additional_information' => 'Treated for acute migraine at university clinic.',
                'status' => 'approved',
                'admin_notes' => 'Approved. Certificate queued for final digital signature.',
            ]
        );

        // 6. Seed Health Inquiries
        $inquiry1 = HealthInquiry::firstOrCreate(
            [
                'user_id' => $student1->id,
                'subject' => 'Annual Physical Examination (APE) requirements for Engineering students',
            ],
            [
                'category' => 'Clinic Services & Hours',
                'message' => 'Good day, I would like to inquire about the schedule and specific laboratory tests required for 3rd year Engineering students during the Annual Physical Exam. Thank you!',
                'status' => 'resolved',
            ]
        );

        InquiryResponse::firstOrCreate(
            [
                'health_inquiry_id' => $inquiry1->id,
                'message' => 'Good day Juan. The Annual Physical Examination for College of Engineering students is scheduled from Monday to Saturday, 7:00 AM to 9:00 PM at the University Clinic (SV Building). Required tests include Chest X-ray, CBC, and Urinalysis. You may walk in with your valid student ID.',
            ],
            [
                'user_id' => $admin->id,
                'is_staff_reply' => true,
            ]
        );

        InquiryResponse::firstOrCreate(
            [
                'health_inquiry_id' => $inquiry1->id,
                'message' => 'Thank you very much for the clarification! I will visit the clinic this Wednesday.',
            ],
            [
                'user_id' => $student1->id,
                'is_staff_reply' => false,
            ]
        );

        $inquiry2 = HealthInquiry::firstOrCreate(
            [
                'user_id' => $faculty->id,
                'subject' => 'Seasonal Influenza Vaccine Availability for Faculty Members',
            ],
            [
                'category' => 'Vaccination & Immunization',
                'message' => 'Hello Health Services Team. Will there be an upcoming batch of seasonal flu vaccines provided for university faculty and staff members this semester? Thank you.',
                'status' => 'in_progress',
            ]
        );

        InquiryResponse::firstOrCreate(
            [
                'health_inquiry_id' => $inquiry2->id,
                'message' => 'Greetings Prof. Santos. Yes, the University Health Services has procured flu vaccines. The rollout schedule is currently being finalized with HR and will be published via an official announcement shortly.',
            ],
            [
                'user_id' => $admin->id,
                'is_staff_reply' => true,
            ]
        );

        // 7. Seed Health Articles
        $articles = [
            [
                'title' => 'Essential Daily Habits for Mental Well-Being During Exam Seasons',
                'category' => 'Mental Well-Being',
                'summary' => 'Discover practical, science-backed strategies for managing stress, preventing academic burnout, and maintaining mental clarity.',
                'content' => "Midterms and finals can place intense mental demands on students and faculty alike. Research underscores that mental wellness is closely tied to foundational self-care habits.\n\n1. Protect Your Sleep Cycle: Aim for 7 to 8 hours of restorative sleep. Sleep deprivation impairs memory consolidation and emotional regulation.\n\n2. The 50/10 Study Rule: For every 50 minutes of focused academic work, take a 10-minute restorative break. Step away from computer and smartphone screens.\n\n3. Mindfulness & Diaphragmatic Breathing: Taking 3-5 minutes of slow abdominal breathing stimulates the vagus nerve and downregulates the sympathetic fight-or-flight stress response.\n\nIf you find yourself overwhelmed, remember that the University Health Services and Guidance Office provide confidential counseling and wellness consultations.",
                'author_name' => 'University Mental Health Committee',
                'is_published' => true,
                'published_at' => now()->subDays(3),
            ],
            [
                'title' => 'Proper Dental Care & Oral Hygiene: Preventing Tooth Decay & Gingivitis',
                'category' => 'Dental Health',
                'summary' => 'A comprehensive guide to optimal brushing techniques, flossing practices, and when to visit the university dental clinic.',
                'content' => "Oral health is a vital window into your overall physical health. Good oral hygiene habits help prevent tooth decay, enamel erosion, periodontal disease, and systemic infections.\n\nKey Dental Guidelines:\n- Brush at least twice daily for a full two minutes using fluoride toothpaste.\n- Floss daily to remove plaque and food debris between teeth where toothbrushes cannot reach.\n- Replace your toothbrush every 3 months or sooner if bristles are frayed.\n- Schedule routine dental checkups and oral prophylaxis (cleaning) twice a year at the Adamson Dental Clinic.",
                'author_name' => 'AdU Dental Clinic Section',
                'is_published' => true,
                'published_at' => now()->subDays(6),
            ],
            [
                'title' => 'Hydration and Nutrition: Fueling Academic and Workplace Performance',
                'category' => 'Healthy Lifestyle',
                'summary' => 'How balanced nutrition and proper hydration elevate concentration, immune defense, and all-day energy levels.',
                'content' => "Even mild dehydration of 1-2% can result in headaches, fatigue, decreased alertness, and reduced cognitive performance.\n\nTips for Everyday Vitality:\n- Carry a reusable water tumbler and aim for 2 to 2.5 liters of clean water daily.\n- Balance meals with whole grains, lean protein, and colorful vegetables.\n- Limit excessive caffeine and sugary energy drinks which cause energy crashes.\n- Pack healthy snacks like fresh fruit, almonds, or whole wheat sandwiches for campus days.",
                'author_name' => 'University Nutrition & Wellness Advisory',
                'is_published' => true,
                'published_at' => now()->subDays(8),
            ],
            [
                'title' => 'Infection Prevention: Campus Hygiene Guidelines and Respiratory Etiquette',
                'category' => 'Hygiene and Prevention',
                'summary' => 'Preventing the spread of common viral infections, seasonal flu, and respiratory illnesses within classroom environments.',
                'content' => "Campus communities thrive when shared health safety precautions are observed by all members.\n\n- Practice thorough hand hygiene with soap and water for 20 seconds or use 70% ethyl alcohol sanitizer.\n- Observe cough etiquette: cover your mouth and nose with a tissue or bent elbow when coughing or sneezing.\n- Wear a well-fitting face mask if you have mild respiratory symptoms or in crowded indoor lecture halls.\n- Stay home and seek teleconsultation if you experience high fever or persistent contagious symptoms.",
                'author_name' => 'AdU Health Services Preventive Team',
                'is_published' => true,
                'published_at' => now()->subDays(12),
            ],
            [
                'title' => 'Understanding Preventive Health Screenings and Routine Checkups',
                'category' => 'Preventive Healthcare',
                'summary' => 'Why annual physical exams, vision screenings, and blood pressure monitoring matter for lifelong vitality.',
                'content' => "Preventive healthcare focuses on detecting health risk factors before symptoms arise. Regular blood pressure screenings, BMI evaluations, and periodic lab checkups help catch issues early.\n\nAdamson University provides annual health screening facilities for all enrolled students and employed faculty/staff. Take proactive charge of your well-being today.",
                'author_name' => 'AdU Medical Clinic Section',
                'is_published' => true,
                'published_at' => now()->subDays(15),
            ],
            [
                'title' => 'Ergonomics in the Digital Classroom and Office Space',
                'category' => 'General Wellness',
                'summary' => 'Simple postural adjustments and workstation setups to prevent neck strain, carpal tunnel, and chronic lower back pain.',
                'content' => "With extensive hours spent typing code, writing papers, and grading assignments, musculoskeletal strain is a common concern among students and faculty.\n\n- Keep monitor at eye level so your neck remains neutral.\n- Keep feet flat on the floor with knees bent at 90 degrees.\n- Perform gentle neck and shoulder stretches every 45 minutes to relieve muscular tension.",
                'author_name' => 'University Occupational Health Desk',
                'is_published' => true,
                'published_at' => now()->subDays(18),
            ],
        ];

        foreach ($articles as $art) {
            HealthArticle::firstOrCreate(
                ['title' => $art['title']],
                [
                    'author_id' => $admin->id,
                    'slug' => Str::slug($art['title']),
                    'category' => $art['category'],
                    'summary' => $art['summary'],
                    'content' => $art['content'],
                    'author_name' => $art['author_name'],
                    'is_published' => $art['is_published'],
                    'published_at' => $art['published_at'],
                ]
            );
        }

        // 8. Seed Announcements
        $announcements = [
            [
                'title' => 'University Clinic Operating Hours & Dental Booking Advisory for 1st Semester',
                'category' => 'Clinic Schedule Update',
                'priority' => 'high',
                'content' => "The University Health Services Office wishes to inform the Adamson community of our extended clinic operations. The Medical and Dental Clinics are open Monday to Saturday from 7:00 AM to 9:00 PM. Dental appointments and teleconsultations must be booked in advance via the AdU eCare+ portal to ensure safe physical distancing and orderly patient queueing.",
                'is_published' => true,
                'published_at' => now()->subDays(2),
            ],
            [
                'title' => 'Campus-Wide Seasonal Flu Vaccination Campaign Next Week',
                'category' => 'Vaccination Drive',
                'priority' => 'medium',
                'content' => "Protect yourself and loved ones! The University Health Services Office, in coordination with the Manila Health Department, will conduct a voluntary Influenza Vaccination Drive for all eligible students, faculty, and administrative staff at the SV Hall on Oct 28 to Oct 30, 2026. Pre-registration is encouraged via the Health Inquiries desk.",
                'is_published' => true,
                'published_at' => now()->subDays(4),
            ],
            [
                'title' => 'Urgent Advisory: Strict Observance of Health Protocols During Rainy Season',
                'category' => 'Urgent Advisory',
                'priority' => 'urgent',
                'content' => "Due to rising regional incidences of Dengue fever and seasonal viral infections during the rainy season, all university departments are reminded to maintain clean, dry surroundings and eliminate stagnant water reservoirs. Students experiencing fever exceeding 38°C for more than 2 days are advised to visit the clinic immediately.",
                'is_published' => true,
                'published_at' => now()->subDays(1),
            ],
            [
                'title' => 'Digital Medical Certificate Verification Now Live on AdU eCare+',
                'category' => 'General Announcement',
                'priority' => 'medium',
                'content' => "Students and faculty can now submit medical certificate requisitions online. Attending physicians verify documents and issue official university medical clearance slips with digital certificate reference numbers, reducing queue times at the clinic release window.",
                'is_published' => true,
                'published_at' => now()->subDays(7),
            ],
        ];

        foreach ($announcements as $ann) {
            Announcement::firstOrCreate(
                ['title' => $ann['title']],
                [
                    'author_id' => $admin->id,
                    'category' => $ann['category'],
                    'priority' => $ann['priority'],
                    'content' => $ann['content'],
                    'is_published' => $ann['is_published'],
                    'published_at' => $ann['published_at'],
                ]
            );
        }

        // 9. Seed App Notifications for Student
        AppNotification::firstOrCreate(
            [
                'user_id' => $student1->id,
                'title' => 'Dental Appointment Approved',
            ],
            [
                'message' => 'Your dental appointment for Oral Prophylaxis has been approved for tomorrow morning at 09:00 AM.',
                'type' => 'dental',
                'link_url' => route('dental.index'),
                'is_read' => false,
            ]
        );

        AppNotification::firstOrCreate(
            [
                'user_id' => $student1->id,
                'title' => 'Teleconsultation Scheduled',
            ],
            [
                'message' => 'Your teleconsultation session with Dr. Maria Ramos has been scheduled. Google Meet link is now available.',
                'type' => 'teleconsultation',
                'link_url' => route('teleconsultation.index'),
                'is_read' => true,
                'read_at' => now()->subDay(),
            ]
        );

        AppNotification::firstOrCreate(
            [
                'user_id' => $student1->id,
                'title' => 'Medical Certificate Released',
            ],
            [
                'message' => 'Your Fit to Return medical certificate (No: ADU-MC-2026-0089) is ready for download and release.',
                'type' => 'certificate',
                'link_url' => route('certificates.index'),
                'is_read' => false,
            ]
        );

        // 10. Seed Activity Logs
        ActivityLog::firstOrCreate(
            [
                'module' => 'System',
                'action' => 'Database Seeding',
            ],
            [
                'user_id' => $admin->id,
                'description' => 'System initialized with demonstration healthcare data for Adamson University.',
                'ip_address' => '127.0.0.1',
            ]
        );
    }
}
