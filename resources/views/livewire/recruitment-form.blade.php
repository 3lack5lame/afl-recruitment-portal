<div>
    <!-- Success Message -->
    @if($successMessage)
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Success!</strong>
            <span class="block sm:inline">{{ $successMessage }}</span>
        </div>
    @endif

    @error('form')
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Error:</strong>
            <span class="block sm:inline">{{ $message }}</span>
        </div>
    @enderror

    <!-- Progress Bar -->
    <div class="mb-8">
        <div class="flex justify-between mb-2">
            <span @class(['text-sm', 'font-medium', 'text-blue-600' => $currentStep >= 1])>Step 1: Personal</span>
            <span @class(['text-sm', 'font-medium', 'text-blue-600' => $currentStep >= 2])>Step 2: Contact</span>
            <span @class(['text-sm', 'font-medium', 'text-blue-600' => $currentStep >= 3])>Step 3: Documents</span>
            <span @class(['text-sm', 'font-medium', 'text-blue-600' => $currentStep >= 4])>Step 4: Review</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2.5">
            <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-300"
                 style="width: {{ ($currentStep / 4) * 100 }}%"></div>
        </div>
    </div>

    <!-- Form -->
    <form wire:submit.prevent="submit">
        
        <!-- STEP 1: Personal Information -->
        @if($currentStep == 1)
        <div class="space-y-4">
            <h2 class="text-xl font-bold mb-4">Step 1: Personal Information</h2>
            
            <!-- Date of Birth -->
            <div>
                <label class="block text-sm font-medium mb-1">Date of Birth *</label>
                <input type="date" wire:model="date_of_birth" 
                       class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500">
                @error('date_of_birth') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                <p class="text-xs text-gray-500 mt-1">Must be between 18-35 years old</p>
            </div>
            
            <!-- Gender -->
            <div>
                <label class="block text-sm font-medium mb-1">Gender *</label>
                <select wire:model="gender" class="w-full border rounded-lg px-3 py-2">
                    <option value="">Select Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
                @error('gender') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            
            <!-- County of Origin -->
            <div>
                <label class="block text-sm font-medium mb-1">County of Origin *</label>
                <select wire:model="county_of_origin" class="w-full border rounded-lg px-3 py-2">
                    <option value="">Select County</option>
                    @foreach($counties as $county)
                        <option value="{{ $county }}">{{ $county }}</option>
                    @endforeach
                </select>
                @error('county_of_origin') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            
            <div class="flex justify-end mt-6">
                <button type="button" wire:click="nextStep" 
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                    Next Step →
                </button>
            </div>
        </div>
        @endif

        <!-- STEP 2: Contact Information -->
        @if($currentStep == 2)
        <div class="space-y-4">
            <h2 class="text-xl font-bold mb-4">Step 2: Contact Information</h2>
            
            <!-- Phone Number -->
            <div>
                <label class="block text-sm font-medium mb-1">Phone Number *</label>
                <input type="tel" wire:model="phone_number" placeholder="0881234567"
                       class="w-full border rounded-lg px-3 py-2">
                @error('phone_number') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                <p class="text-xs text-gray-500 mt-1">Enter your Liberian phone number (7-12 digits)</p>
            </div>
            
            <!-- County of Residence -->
            <div>
                <label class="block text-sm font-medium mb-1">County of Residence *</label>
                <select wire:model="county_of_residence" class="w-full border rounded-lg px-3 py-2">
                    <option value="">Select County</option>
                    @foreach($counties as $county)
                        <option value="{{ $county }}">{{ $county }}</option>
                    @endforeach
                </select>
                @error('county_of_residence') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            
            <!-- Education Level -->
            <div>
                <label class="block text-sm font-medium mb-1">Education Level *</label>
                <select wire:model="education_level" class="w-full border rounded-lg px-3 py-2">
                    <option value="">Select Education Level</option>
                    <option value="WASSCE">WASSCE</option>
                    <option value="High School Diploma">High School Diploma</option>
                    <option value="Bachelor Degree">Bachelor Degree</option>
                    <option value="Master Degree">Master Degree</option>
                    <option value="Professional Certificate">Professional Certificate</option>
                </select>
                @error('education_level') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            
            <div class="flex justify-between mt-6">
                <button type="button" wire:click="previousStep" 
                        class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600">
                    ← Back
                </button>
                <button type="button" wire:click="nextStep" 
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                    Next Step →
                </button>
            </div>
        </div>
        @endif

        <!-- STEP 3: Test Center & Documents -->
        @if($currentStep == 3)
        <div class="space-y-4">
            <h2 class="text-xl font-bold mb-4">Step 3: Test Center & Documents</h2>
            
            <!-- Preferred Testing Center -->
            <div>
                <label class="block text-sm font-medium mb-1">Preferred Testing Center *</label>
                <select wire:model="preferred_testing_center" class="w-full border rounded-lg px-3 py-2">
                    <option value="">Select Testing Center</option>
                    @foreach($centers as $center)
                        <option value="{{ $center }}">{{ $center }}</option>
                    @endforeach
                </select>
                @error('preferred_testing_center') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            
            <!-- Passport Photo -->
            <div>
                <label class="block text-sm font-medium mb-1">Passport Photo *</label>
                <input type="file" wire:model="passport_photo" accept="image/*"
                       class="w-full border rounded-lg px-3 py-2">
                @error('passport_photo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                @if($photo_preview)
                    <img src="{{ $photo_preview }}" class="mt-2 h-20 w-20 object-cover rounded">
                @endif
                <p class="text-xs text-gray-500 mt-1">Max 2MB. JPG, PNG only</p>
            </div>
            
            <!-- National ID -->
            <div>
                <label class="block text-sm font-medium mb-1">National ID / Passport *</label>
                <input type="file" wire:model="national_id" accept="image/*,application/pdf"
                       class="w-full border rounded-lg px-3 py-2">
                @error('national_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                @if($id_preview && !str_contains($id_preview, '.pdf'))
                    <img src="{{ $id_preview }}" class="mt-2 h-20 w-20 object-cover rounded">
                @endif
            </div>
            
            <!-- WAEC Certificate -->
            <div>
                <label class="block text-sm font-medium mb-1">WAEC/WASSCE Certificate *</label>
                <input type="file" wire:model="waec_certificate" accept="image/*,application/pdf"
                       class="w-full border rounded-lg px-3 py-2">
                @error('waec_certificate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                @if($cert_preview && !str_contains($cert_preview, '.pdf'))
                    <img src="{{ $cert_preview }}" class="mt-2 h-20 w-20 object-cover rounded">
                @endif
            </div>
            
            <div class="flex justify-between mt-6">
                <button type="button" wire:click="previousStep" 
                        class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600">
                    ← Back
                </button>
                <button type="button" wire:click="nextStep" 
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                    Review Application →
                </button>
            </div>
        </div>
        @endif

        <!-- STEP 4: Review & Submit -->
        @if($currentStep == 4)
        <div class="space-y-4">
            <h2 class="text-xl font-bold mb-4">Step 4: Review Your Application</h2>
            
            <div class="bg-gray-50 p-4 rounded-lg space-y-2">
                <p><strong>Date of Birth:</strong> {{ $date_of_birth }}</p>
                <p><strong>Gender:</strong> {{ ucfirst($gender) }}</p>
                <p><strong>County of Origin:</strong> {{ $county_of_origin }}</p>
                <p><strong>County of Residence:</strong> {{ $county_of_residence }}</p>
                <p><strong>Phone:</strong> {{ $phone_number }}</p>
                <p><strong>Education:</strong> {{ $education_level }}</p>
                <p><strong>Testing Center:</strong> {{ $preferred_testing_center }}</p>
                <p><strong>Documents:</strong> 
                    @if($passport_photo) ✅ Photo @endif
                    @if($national_id) ✅ ID @endif
                    @if($waec_certificate) ✅ Certificate @endif
                </p>
            </div>
            
            <div class="bg-yellow-50 border border-yellow-200 p-3 rounded">
                <p class="text-sm text-yellow-800">
                    ⚠️ By submitting this application, you confirm that all information is true and accurate.
                    False information will lead to disqualification.
                </p>
            </div>
            
            <div class="flex justify-between mt-6">
                <button type="button" wire:click="previousStep" 
                        class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600">
                    ← Back
                </button>
                <button type="submit" 
                        class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">
                    Submit Application ✓
                </button>
            </div>
        </div>
        @endif
        
    </form>
</div>