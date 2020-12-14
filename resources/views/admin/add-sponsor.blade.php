<x-app-layout>
    @section('sponsors','bg-gray-300')
    @section('content')
        <div class="py-3">
            <div class="max-w-7xl mx-auto sm:px-4 lg:px-4">
                <div class="bg-gray-50 overflow-hidden shadow-xl" style="background:white">
                    <div class="flex justify-between items-center">
                        <h1 class="text-center mt-5 m-5" style="font-size:25px">Add Sponsor</h1>
                        <a href="add-multiple-sponsor"><button class="btn-outline-primary add_bank_modal px-4 bg-green-100 py-1 rounded hover:bg-green-200 mt-5 m-5">
                            Add Multiple
                            </button></a>
                    </div>
                    <form method="POST">
                        @if(Session::has('success-message'))
                            <div class="text-green-500 ml-5 mb-2" font-size="20px">{{ session('success-message') }}</div>
                        @endif
                        @csrf
                        <div>
                            <?php $i = 1; ?>
                            @foreach($programs as $program)
                            <div class="flex">
                                <div class="mt-2 m-5 w-1/2">
                                    <x-jet-label for="name" value="Program {{ $i }}" />
                                    <select name="program{{ $i }}" data-id = "{{ $i }}" class="select form-select mt-1 block w-full">
                                        <option value=''>Choose a Program</option>
                                        <option value={{ $program->id }}>{{ $program->name }}</option>
                                    </select>
                                </div>

                                <div class="mt-2 m-5 w-1/2">
                                    <x-jet-label for="name" value="{{ __('Level') }}" />
                                    <select name="level{{ $i }}"
                                        class="form-select mt-1 block w-full">
                                        <option value=''>Choose a level</option>
                                            @foreach($levels as $level)
                                                @if($level['program_id'] == $program['id'])
                                                <option value={{ $level->id }}>{{ $level->name }}</option>
                                                @endif
                                            @endforeach
                                    </select>
                                </div>
                            </div>
                            <?php $i++; ?>
                            @endforeach
                        </div>
                        
                        <div class="mt-2 m-5">
                            <x-jet-label for="name" value="{{ __('Sponsor Name') }}" />
                            <x-jet-input id="name" class="block mt-1 w-full" type="text" :value="old('name')" required name="name"/>
                        </div>

                        <div class="mt-2 m-5">
                            <x-jet-label for="address" value="{{ __('Sponsor Address') }}" />
                            
                            <textarea id="address" style="resize:none" rows="3" class="form-textarea border rounded-md shadow-sm mt-1 w-full " name="address" :value="old('address')"></textarea>
                        </div>

                        <div class="flex">
                            <div class="mt-2 m-5 w-1/2">
                                <x-jet-label for="mobile_no1" value="{{ __('Sponsor Mobile No 1') }}" />
                                <x-jet-input id="mobile_no1" class="block mt-1 w-full" type="number" name="mobile_no1"  />
                            </div>

                            <div class="mt-2 m-5 w-1/2">
                                <x-jet-label for="mobile_no2" value="{{ __('Sponsor Mobile No 2') }}" />
                                <x-jet-input id="mobile_no2" class="block mt-1 w-full" type="number" name="mobile_no2"  />
                            </div>
                        </div>

                        <div class="flex">
                            <div class="mt-2 m-5 w-1/2">
                                <x-jet-label for="email" value="{{ __('Sponsor Email') }}" />
                                <x-jet-input id="email" class="block mt-1 w-full" type="email" required name="email_id"/>
                            </div>

                            <div class="mt-2 m-5 w-1/2">
                                <x-jet-label for="dob" value="{{ __('Sponsor Date of Birth') }}" />
                                <x-jet-input id="dob" class="block mt-1 w-full" type="date" name="dob"/>
                            </div>
                        </div>

                        <div class="mt-2 m-5">
                            <x-jet-label for="reference" value="{{ __('Sponsor Reference') }}" />
                            <x-jet-input id="reference" class="block mt-1 w-full" type="text" name="reference"/>
                        </div>

                        <div class="mt-2 m-5">
                            <x-jet-label for="beneficiary_id" value="{{ __('Beneficiary Name') }}" />
                            <select class="form-select mt-1 block w-full" id="block mt-1 w-full" name="beneficiary_id[]" id="beneficiary_id" Multiple row="1">
                                <option value="">--SELECT--</option>
                                @foreach($beneficiaries as $beneficiary)
                                    <option value="{{ $beneficiary->id }}">{{ $beneficiary->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="text-right mt-2 m-5">
                            <x-jet-button class="">
                                {{ __('Add') }}
                            </x-jet-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endsection
    
</x-app-layout>
