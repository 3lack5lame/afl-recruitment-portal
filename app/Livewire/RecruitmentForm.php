<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Application;
use App\Models\Document;
use App\Models\RecruitmentCycle;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RecruitmentForm extends Component
{
    use WithFileUploads;

    protected $layout = 'layouts.app';

    // Current step (1-4)
    public $currentStep = 1;
    
    // Form fields
    public $date_of_birth;
    public $gender;
    public $county_of_origin;
    public $county_of_residence;
    public $phone_number;
    public $education_level;
    public $preferred_testing_center;
    
    // File uploads
    public $passport_photo;
    public $national_id;
    public $waec_certificate;
    
    // Temporary storage for preview
    public $photo_preview;
    public $id_preview;
    public $cert_preview;
    
    // Success message
    public $successMessage = '';

    public function mount()
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }
    }

    // All 15 Liberian counties
    protected $liberian_counties = [
        'Bomi', 'Bong', 'Gbarpolu', 'Grand Bassa', 'Grand Cape Mount',
        'Grand Gedeh', 'Grand Kru', 'Lofa', 'Margibi', 'Maryland',
        'Montserrado', 'Nimba', 'Rivercess', 'River Gee', 'Sinoe'
    ];

    // Testing centers
    protected $testing_centers = [
        'Monrovia (Montserrado)',
        'Gbarnga (Bong)',
        'Kakata (Margibi)',
        'Buchanan (Grand Bassa)',
        'Zwedru (Grand Gedeh)',
        'Harper (Maryland)',
        'Voinjama (Lofa)',
        'Sanniquellie (Nimba)'
    ];

    // Validation rules for each step
    protected $rules = [
        'date_of_birth' => 'required|date|before:18 years ago|after:35 years ago',
        'gender' => 'required|in:male,female',
        'county_of_origin' => 'required|in:Bomi,Bong,Gbarpolu,Grand Bassa,Grand Cape Mount,Grand Gedeh,Grand Kru,Lofa,Margibi,Maryland,Montserrado,Nimba,Rivercess,River Gee,Sinoe',
    ];

    protected $messages = [
        'passport_photo.max' => 'The passport photo must not be larger than 5MB.',
        'passport_photo.image' => 'The passport photo must be an image file (jpeg, png, jpg).',
        'passport_photo.required' => 'Please upload a passport photo.',
        'national_id.max' => 'The National ID file must not be larger than 5MB.',
        'waec_certificate.max' => 'The WAEC certificate must not be larger than 5MB.',
    ];

    // Step 2 validation rules
    public function step2Rules()
    {
        return [
            'phone_number' => 'required|regex:/^[0-9]{7,12}$/',
            'county_of_residence' => 'required|in:' . implode(',', $this->liberian_counties),
            'education_level' => 'required|in:WASSCE,High School Diploma,Bachelor Degree,Master Degree,Professional Certificate',
        ];
    }

    // Step 3 validation rules
    public function step3Rules()
    {
        return [
            'preferred_testing_center' => 'required|in:' . implode(',', $this->testing_centers),
            'passport_photo' => 'required|file|max:5120|mimes:jpg,jpeg,png',
            'national_id' => 'required|file|max:5120|mimes:jpg,jpeg,png,pdf',
            'waec_certificate' => 'required|file|max:5120|mimes:jpg,jpeg,png,pdf',
        ];
    }

    public function updatedDateOfBirth($value)
    {
        $this->validateOnly('date_of_birth');
    }

    // ==================== IMPROVED FILE UPLOAD HANDLERS ====================
    public function updatedPassportPhoto()
    {
        try {
            $this->validateOnly('passport_photo');

            $this->photo_preview = $this->passport_photo->temporaryUrl();

            Log::info('Passport photo uploaded successfully', [
                'name' => $this->passport_photo->getClientOriginalName(),
                'size' => $this->passport_photo->getSize(),
                'mime' => $this->passport_photo->getMimeType(),
            ]);
        } catch (\Exception $e) {
            Log::error('Passport Photo Upload Failed: ' . $e->getMessage());
            $this->addError('passport_photo', 'Failed to process passport photo. Please try a smaller JPG or PNG file (max 5MB).');
            $this->reset('passport_photo');
            $this->photo_preview = null;
        }
    }

    public function updatedNationalId()
    {
        try {
            $this->validateOnly('national_id');
            $this->id_preview = $this->national_id->temporaryUrl();

            Log::info('National ID uploaded successfully');
        } catch (\Exception $e) {
            Log::error('National ID Upload Failed: ' . $e->getMessage());
            $this->addError('national_id', 'Failed to process National ID.');
            $this->reset('national_id');
        }
    }

    public function updatedWaecCertificate()
    {
        try {
            $this->validateOnly('waec_certificate');
            $this->cert_preview = $this->waec_certificate->temporaryUrl();

            Log::info('WAEC Certificate uploaded successfully');
        } catch (\Exception $e) {
            Log::error('WAEC Certificate Upload Failed: ' . $e->getMessage());
            $this->addError('waec_certificate', 'Failed to process WAEC certificate.');
            $this->reset('waec_certificate');
        }
    }

    // Go to next step
    public function nextStep()
    {
        if ($this->currentStep == 1) {
            $this->validate($this->rules);
        } elseif ($this->currentStep == 2) {
            $this->validate($this->step2Rules());
        } elseif ($this->currentStep == 3) {
            $this->validate($this->step3Rules());
        }
        
        $this->currentStep++;
    }

    // Go to previous step
    public function previousStep()
    {
        $this->currentStep--;
    }

    // Submit the final application
    public function submit()
    {
        try {
            // Final validation
            $this->validate($this->rules);
            $this->validate($this->step2Rules());
            $this->validate($this->step3Rules());

            $age = \Carbon\Carbon::parse($this->date_of_birth)->age;
            
            // Create application
            $application = Application::create([
                'user_id' => Auth::id(),
                'recruitment_cycle_id' => $this->getRecruitmentCycleId(),
                'date_of_birth' => $this->date_of_birth,
                'gender' => $this->gender,
                'county_of_origin' => $this->county_of_origin,
                'county_of_residence' => $this->county_of_residence,
                'phone_number' => $this->phone_number,
                'education_level' => $this->education_level,
                'preferred_testing_center' => $this->preferred_testing_center,
                'status' => 'submitted',
                'notes' => "Applicant age: $age years",
            ]);

            // Save documents
            $documentPaths = [
                'passport_photo' => $this->passport_photo->store('documents/photos', 'public'),
                'national_id'     => $this->national_id->store('documents/ids', 'public'),
                'waec_certificate'=> $this->waec_certificate->store('documents/certificates', 'public'),
            ];

            foreach ($documentPaths as $type => $path) {
                Document::create([
                    'application_id' => $application->id,
                    'type' => $type,
                    'file_path' => $path,
                    'verified' => false,
                ]);
            }

            // Reset form
            $this->reset();
            $this->currentStep = 1;
            $this->successMessage = 'Application submitted successfully! You will receive SMS notification about your test date.';
            
            session()->flash('success', $this->successMessage);

        } catch (\Exception $e) {
            Log::error('Application Submission Error: ' . $e->getMessage());
            $this->addError('form', 'Something went wrong during submission. Please try again.');
        }
    }

    public function getCountiesProperty()
    {
        return $this->liberian_counties;
    }

    public function getCentersProperty()
    {
        return $this->testing_centers;
    }

    protected function getRecruitmentCycleId(): int
    {
        $cycle = RecruitmentCycle::where('is_active', true)
            ->orderBy('start_date', 'desc')
            ->first();

        if (! $cycle) {
            $cycle = RecruitmentCycle::create([
                'title' => 'Default Recruitment Cycle',
                'start_date' => now()->startOfDay(),
                'end_date' => now()->addYear()->endOfDay(),
                'is_active' => true,
            ]);
        }

        return $cycle->id;
    }

    public function render()
    {
        return view('livewire.recruitment-form', [
            'counties' => $this->liberian_counties,
            'centers' => $this->testing_centers,
        ])->layout('layouts.app');
    }
}