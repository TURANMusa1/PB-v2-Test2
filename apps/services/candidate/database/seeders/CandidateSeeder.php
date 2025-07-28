<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Candidate;

class CandidateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $candidates = [
            [
                'first_name' => 'Ahmet',
                'last_name' => 'Yılmaz',
                'email' => 'ahmet.yilmaz@example.com',
                'phone' => '+90 555 123 4567',
                'summary' => 'Senior Full Stack Developer with 8+ years of experience in Laravel, React, and Vue.js. Passionate about clean code and user experience.',
                'experience_level' => 'senior',
                'current_position' => 'Senior Developer',
                'current_company' => 'TechCorp',
                'expected_salary' => 45000,
                'location' => 'Istanbul, Turkey',
                'linkedin_url' => 'https://linkedin.com/in/ahmet-yilmaz',
                'github_url' => 'https://github.com/ahmetyilmaz',
                'portfolio_url' => 'https://ahmetyilmaz.dev',
                'skills' => ['Laravel', 'React', 'Vue.js', 'PostgreSQL', 'Redis', 'Docker'],
                'education' => [
                    [
                        'degree' => 'Computer Science',
                        'school' => 'Istanbul Technical University',
                        'year' => '2015'
                    ]
                ],
                'experience' => [
                    [
                        'position' => 'Senior Developer',
                        'company' => 'TechCorp',
                        'duration' => '2020-2024',
                        'description' => 'Led development of multiple web applications'
                    ]
                ],
                'status' => 'active',
                'company_id' => 1,
                'created_by' => 1,
            ],
            [
                'first_name' => 'Ayşe',
                'last_name' => 'Demir',
                'email' => 'ayse.demir@example.com',
                'phone' => '+90 555 987 6543',
                'summary' => 'Frontend Developer specializing in React and TypeScript. Strong focus on accessibility and performance optimization.',
                'experience_level' => 'mid',
                'current_position' => 'Frontend Developer',
                'current_company' => 'WebSolutions',
                'expected_salary' => 35000,
                'location' => 'Ankara, Turkey',
                'linkedin_url' => 'https://linkedin.com/in/ayse-demir',
                'github_url' => 'https://github.com/aysedemir',
                'skills' => ['React', 'TypeScript', 'Tailwind CSS', 'Next.js', 'Jest'],
                'education' => [
                    [
                        'degree' => 'Software Engineering',
                        'school' => 'Middle East Technical University',
                        'year' => '2018'
                    ]
                ],
                'experience' => [
                    [
                        'position' => 'Frontend Developer',
                        'company' => 'WebSolutions',
                        'duration' => '2019-2024',
                        'description' => 'Developed responsive web applications'
                    ]
                ],
                'status' => 'active',
                'company_id' => 1,
                'created_by' => 1,
            ],
            [
                'first_name' => 'Mehmet',
                'last_name' => 'Kaya',
                'email' => 'mehmet.kaya@example.com',
                'phone' => '+90 555 456 7890',
                'summary' => 'DevOps Engineer with expertise in AWS, Docker, and Kubernetes. Passionate about automation and infrastructure as code.',
                'experience_level' => 'expert',
                'current_position' => 'DevOps Engineer',
                'current_company' => 'CloudTech',
                'expected_salary' => 55000,
                'location' => 'Izmir, Turkey',
                'linkedin_url' => 'https://linkedin.com/in/mehmet-kaya',
                'github_url' => 'https://github.com/mehmetkaya',
                'skills' => ['AWS', 'Docker', 'Kubernetes', 'Terraform', 'Jenkins', 'Linux'],
                'education' => [
                    [
                        'degree' => 'Computer Engineering',
                        'school' => 'Ege University',
                        'year' => '2012'
                    ]
                ],
                'experience' => [
                    [
                        'position' => 'DevOps Engineer',
                        'company' => 'CloudTech',
                        'duration' => '2018-2024',
                        'description' => 'Managed cloud infrastructure and CI/CD pipelines'
                    ]
                ],
                'status' => 'active',
                'company_id' => 1,
                'created_by' => 1,
            ],
            [
                'first_name' => 'Zeynep',
                'last_name' => 'Özkan',
                'email' => 'zeynep.ozkan@example.com',
                'phone' => '+90 555 321 0987',
                'summary' => 'Junior Backend Developer with strong foundation in PHP and Laravel. Eager to learn and grow in a dynamic team.',
                'experience_level' => 'entry',
                'current_position' => 'Junior Developer',
                'current_company' => 'StartupXYZ',
                'expected_salary' => 25000,
                'location' => 'Bursa, Turkey',
                'linkedin_url' => 'https://linkedin.com/in/zeynep-ozkan',
                'github_url' => 'https://github.com/zeynepozkan',
                'skills' => ['PHP', 'Laravel', 'MySQL', 'Git', 'REST API'],
                'education' => [
                    [
                        'degree' => 'Information Technology',
                        'school' => 'Uludag University',
                        'year' => '2023'
                    ]
                ],
                'experience' => [
                    [
                        'position' => 'Junior Developer',
                        'company' => 'StartupXYZ',
                        'duration' => '2023-2024',
                        'description' => 'Developed backend APIs and database models'
                    ]
                ],
                'status' => 'active',
                'company_id' => 1,
                'created_by' => 1,
            ],
            [
                'first_name' => 'Can',
                'last_name' => 'Arslan',
                'email' => 'can.arslan@example.com',
                'phone' => '+90 555 789 0123',
                'summary' => 'Mobile Developer with 5+ years of experience in React Native and Flutter. Delivered 10+ apps to app stores.',
                'experience_level' => 'senior',
                'current_position' => 'Mobile Developer',
                'current_company' => 'MobileApps Inc',
                'expected_salary' => 42000,
                'location' => 'Antalya, Turkey',
                'linkedin_url' => 'https://linkedin.com/in/can-arslan',
                'github_url' => 'https://github.com/canarslan',
                'skills' => ['React Native', 'Flutter', 'Firebase', 'Redux', 'TypeScript'],
                'education' => [
                    [
                        'degree' => 'Computer Science',
                        'school' => 'Akdeniz University',
                        'year' => '2017'
                    ]
                ],
                'experience' => [
                    [
                        'position' => 'Mobile Developer',
                        'company' => 'MobileApps Inc',
                        'duration' => '2019-2024',
                        'description' => 'Developed cross-platform mobile applications'
                    ]
                ],
                'status' => 'hired',
                'company_id' => 1,
                'created_by' => 1,
            ]
        ];

        foreach ($candidates as $candidateData) {
            Candidate::create($candidateData);
        }

        $this->command->info('Candidate seeder completed successfully!');
    }
}
