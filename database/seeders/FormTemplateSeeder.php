<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FormTemplate;
use App\Models\Employee;

class FormTemplateSeeder extends Seeder
{
    public function run()
    {
        $admin = Employee::where('employee_id', 'RSCB001')->first();

        $templates = [
            [
                'name' => 'Individual Sports Registration',
                'slug' => 'individual-sports-registration',
                'description' => 'Standard registration form for individual sports events',
                'is_reusable' => true,
                'form_schema' => [
                    'fields' => [
                        [
                            'type' => 'text',
                            'label' => 'Full Name',
                            'name' => 'full_name',
                            'placeholder' => 'Enter your full name',
                            'required' => true,
                        ],
                        [
                            'type' => 'number',
                            'label' => 'Age',
                            'name' => 'age',
                            'placeholder' => 'Enter your age',
                            'required' => true,
                            'min' => 18,
                            'max' => 65,
                        ],
                        [
                            'type' => 'dropdown',
                            'label' => 'Blood Group',
                            'name' => 'blood_group',
                            'placeholder' => 'Select your blood group',
                            'required' => true,
                            'options' => ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'],
                        ],
                        [
                            'type' => 'dropdown',
                            'label' => 'T-Shirt Size',
                            'name' => 'tshirt_size',
                            'placeholder' => 'Select size',
                            'required' => false,
                            'options' => ['S', 'M', 'L', 'XL', 'XXL'],
                        ],
                        [
                            'type' => 'text',
                            'label' => 'Emergency Contact Name',
                            'name' => 'emergency_contact_name',
                            'placeholder' => 'Emergency contact person name',
                            'required' => true,
                        ],
                        [
                            'type' => 'mobile',
                            'label' => 'Emergency Contact Number',
                            'name' => 'emergency_contact_number',
                            'placeholder' => '10-digit mobile number',
                            'required' => true,
                        ],
                        [
                            'type' => 'textarea',
                            'label' => 'Previous Sports Experience',
                            'name' => 'previous_experience',
                            'placeholder' => 'Describe your previous experience in this sport',
                            'required' => false,
                        ],
                        [
                            'type' => 'file',
                            'label' => 'Medical Fitness Certificate',
                            'name' => 'medical_certificate',
                            'placeholder' => 'Upload medical fitness certificate (PDF, JPG, PNG - Max 5MB)',
                            'required' => true,
                            'max_size' => 5120,
                            'allowed_types' => ['pdf', 'jpg', 'jpeg', 'png'],
                        ],
                        [
                            'type' => 'file',
                            'label' => 'ID Card Copy',
                            'name' => 'id_card_copy',
                            'placeholder' => 'Upload scanned copy of ID card',
                            'required' => true,
                            'max_size' => 2048,
                            'allowed_types' => ['pdf', 'jpg', 'jpeg', 'png'],
                        ],
                        [
                            'type' => 'declaration',
                            'label' => 'Declaration',
                            'name' => 'declaration',
                            'placeholder' => 'I declare that all information provided is true and correct. I agree to abide by the rules and regulations of the event.',
                            'required' => true,
                        ],
                    ],
                    'version' => '1.0',
                ],
                'validation_rules' => [
                    'full_name' => 'required|string|max:255',
                    'age' => 'required|numeric|min:18|max:65',
                    'blood_group' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
                    'tshirt_size' => 'nullable|in:S,M,L,XL,XXL',
                    'emergency_contact_name' => 'required|string|max:255',
                    'emergency_contact_number' => 'required|regex:/^[0-9]{10}$/',
                    'previous_experience' => 'nullable|string|max:1000',
                    'medical_certificate' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
                    'id_card_copy' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                    'declaration' => 'accepted',
                ],
                'created_by' => $admin ? $admin->id : 1,
            ],
            [
                'name' => 'Team Sports Registration',
                'slug' => 'team-sports-registration',
                'description' => 'Registration form for team sports events',
                'is_reusable' => true,
                'form_schema' => [
                    'fields' => [
                        [
                            'type' => 'text',
                            'label' => 'Team Name',
                            'name' => 'team_name',
                            'placeholder' => 'Enter your team name',
                            'required' => true,
                        ],
                        [
                            'type' => 'text',
                            'label' => 'Team Captain Name',
                            'name' => 'captain_name',
                            'placeholder' => 'Enter team captain name',
                            'required' => true,
                        ],
                        [
                            'type' => 'mobile',
                            'label' => 'Captain Mobile',
                            'name' => 'captain_mobile',
                            'placeholder' => '10-digit mobile number',
                            'required' => true,
                        ],
                        [
                            'type' => 'number',
                            'label' => 'Number of Players',
                            'name' => 'num_players',
                            'placeholder' => 'Enter total players',
                            'required' => true,
                            'min' => 1,
                            'max' => 30,
                        ],
                        [
                            'type' => 'textarea',
                            'label' => 'Player Names List',
                            'name' => 'player_names',
                            'placeholder' => 'Enter names of all players, one per line',
                            'required' => true,
                        ],
                        [
                            'type' => 'text',
                            'label' => 'Coach Name (if any)',
                            'name' => 'coach_name',
                            'placeholder' => 'Enter coach name',
                            'required' => false,
                        ],
                        [
                            'type' => 'file',
                            'label' => 'Team Approval Letter',
                            'name' => 'team_approval',
                            'placeholder' => 'Upload team approval letter from department head',
                            'required' => true,
                            'max_size' => 5120,
                            'allowed_types' => ['pdf', 'jpg', 'jpeg', 'png'],
                        ],
                        [
                            'type' => 'declaration',
                            'label' => 'Declaration',
                            'name' => 'declaration',
                            'placeholder' => 'I confirm that all team members are eligible and agree to the event rules.',
                            'required' => true,
                        ],
                    ],
                    'version' => '1.0',
                ],
                'validation_rules' => [
                    'team_name' => 'required|string|max:255',
                    'captain_name' => 'required|string|max:255',
                    'captain_mobile' => 'required|regex:/^[0-9]{10}$/',
                    'num_players' => 'required|numeric|min:1|max:30',
                    'player_names' => 'required|string|max:2000',
                    'coach_name' => 'nullable|string|max:255',
                    'team_approval' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
                    'declaration' => 'accepted',
                ],
                'created_by' => $admin ? $admin->id : 1,
            ],
            [
                'name' => 'Athletics Meet Registration',
                'slug' => 'athletics-meet-registration',
                'description' => 'Registration form for athletics and track events',
                'is_reusable' => true,
                'form_schema' => [
                    'fields' => [
                        [
                            'type' => 'checkbox',
                            'label' => 'Select Events',
                            'name' => 'selected_events',
                            'placeholder' => 'Select the events you want to participate in',
                            'required' => true,
                            'options' => [
                                '100m Sprint',
                                '200m Sprint',
                                '400m Run',
                                '800m Run',
                                '1500m Run',
                                '4x100m Relay',
                                'Long Jump',
                                'High Jump',
                                'Shot Put',
                                'Discus Throw',
                                'Javelin Throw',
                            ],
                        ],
                        [
                            'type' => 'text',
                            'label' => 'Personal Best (if any)',
                            'name' => 'personal_best',
                            'placeholder' => 'Enter your personal best timing/distance',
                            'required' => false,
                        ],
                        [
                            'type' => 'file',
                            'label' => 'Medical Certificate',
                            'name' => 'medical_certificate',
                            'placeholder' => 'Upload medical fitness certificate',
                            'required' => true,
                            'max_size' => 5120,
                            'allowed_types' => ['pdf', 'jpg', 'jpeg', 'png'],
                        ],
                        [
                            'type' => 'declaration',
                            'label' => 'Medical Declaration',
                            'name' => 'medical_declaration',
                            'placeholder' => 'I declare that I am medically fit to participate in the selected athletic events.',
                            'required' => true,
                        ],
                    ],
                    'version' => '1.0',
                ],
                'validation_rules' => [
                    'selected_events' => 'required|array|min:1',
                    'personal_best' => 'nullable|string|max:255',
                    'medical_certificate' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
                    'medical_declaration' => 'accepted',
                ],
                'created_by' => $admin ? $admin->id : 1,
            ],
        ];

        foreach ($templates as $template) {
            FormTemplate::create($template);
        }
    }
}
