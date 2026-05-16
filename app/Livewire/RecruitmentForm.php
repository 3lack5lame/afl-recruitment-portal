<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Application;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

    // All 15 Liberian counties
    protected $liberian_counties = [
        'Bomi', 'Bong', 'Gbarpolu', 'Grand Bassa', 'Grand Cape Mount',
        'Grand Gedeh', 'Grand Kru', 'Lofa', 'Margibi', 'Maryland',
        'Montserrado', 'Nimba', 'Rivercess', 'River Gee', 'Sinoe'
    ];

    // Testing centers (based on major cities)
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
            'passport_photo' => 'required|image|max:2048', // 2MB max
            'national_id' => 'required|file|max:2048|mimes:jpg,jpeg,png,pdf',
            'waec_certificate' => 'required|file|max:2048|mimes:jpg,jpeg,png,pdf',
        ];
    }

    // Real-time validation for date of birth (shows age error immediately)
    public function updatedDateOfBirth($value)
    {
        $this->validateOnly('date_of_birth');
    }

    // When photo is uploaded, create a preview
    public function updatedPassportPhoto()
    {
        $this->validateOnly('passport_photo');
        $this->photo_preview = $this->passport_photo->temporaryUrl();
    }

    public function updatedNationalId()
    {
        $this->validateOnly('national_id');
        $this->id_preview = $this->national_id->temporaryUrl();
    }

    public function updatedWaecCertificate()
    {
        $this->validateOnly('waec_certificate');
        $this->cert_preview = $this->waec_certificate->temporaryUrl();
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
        // Final validation of all steps
        $this->validate($this->rules);
        $this->validate($this->step2Rules());
        $this->validate($this->step3Rules());

        // Calculate age from date of birth
        $age = \Carbon\Carbon::parse($this->date_of_birth)->age;
        
        // Create the application record
        $application = Application::create([
            'user_id' => Auth::id(),
            'recruitment_cycle_id' => null, // We'll add active cycle later
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
            'national_id' => $this->national_id->store('documents/ids', 'public'),
            'waec_certificate' => $this->waec_certificate->store('documents/certificates', 'public'),
        ];

        foreach ($documentPaths as $type => $path) {
            Document::create([
                'application_id' => $application->id,
                'type' => $type,
                'file_path' => $path,
                'verified' => false,
            ]);
        }

        // Clear form
        $this->reset();
        
        // Set success message
        $this->successMessage = 'Application submitted successfully! You will receive SMS notification about your test date.';
        
        // Reset to step 1
        $this->currentStep = 1;
    }

    // Pass counties and centers to the view
    public function getCountiesProperty()
    {
        return $this->liberian_counties;
    }

    public function getCentersProperty()
    {
        return $this->testing_centers;
    }

    public function render()
    {
        return view('livewire.recruitment-form', [
            'counties' => $this->liberian_counties,
            'centers' => $this->testing_centers,
        ])->layout('layouts.app');
    }
}