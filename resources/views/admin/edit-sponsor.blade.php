<x-app-layout>
    @section('sponsors','bg-gray-300')
    @section('content')
        <div class="py-3">
            <div class="max-w-7xl mx-auto sm:px-4 lg:px-4">
                <div class="bg-gray-50 overflow-hidden shadow-xl" style="background:white">
                    <h1 class="text-center mb-4" style="font-size:25px">Edit Sponsor</h1>
                    <form method="POST" action="update-sponsor">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="id" value="{{$sponsors->id}}">
                        <div>
                            <?php $i = 1; ?>
                            @foreach($program_sponsors as $program_sponsor)
                                @if($program_sponsor['sponsor_id'] == $sponsors->id)
                                @foreach($programs as $program)
                                    @if($program_sponsor['program_id'] == $program['id'])
                                        <div class="flex">
                                            <div class="mt-2 m-5 w-1/2">
                                                <x-jet-label for="name" value="Program {{ $i }}" />
                                                <select name="program{{ $i }}" data-id = "{{ $i }}" class="select form-select mt-1 block w-full">
                                                    <option value=''>Choose a Program</option>
                                                    <option value="{{ $program->id }}" selected>{{ $program->name }}</option>
                                                </select>
                                            </div>

                                            <div class="mt-2 m-5 w-1/2">
                                                <x-jet-label for="name" value="{{ __('Level') }}" />
                                                <select name="level{{ $i }}"
                                                    class="form-select mt-1 block w-full">
                                                    <option value=''>Choose a level</option>
                                                        @foreach($levels as $level)
                                                            @if($level['program_id'] == $program['id'])
                                                            <option value="{{ $level->id }}" selected>{{ $level->name }}</option>
                                                            @endif
                                                        @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex">
                                            <div class="mt-2 m-5 w-1/2">
                                                <x-jet-label for="name" value="Program {{ $i }}" />
                                                <select name="program{{ $i }}" data-id = "{{ $i }}" class="select form-select mt-1 block w-full">
                                                    <option value=''>Choose a Program</option>
                                                    <option value="{{ $program->id }}">{{ $program->name }}</option>
                                                </select>
                                            </div>

                                            <div class="mt-2 m-5 w-1/2">
                                                <x-jet-label for="name" value="{{ __('Level') }}" />
                                                <select name="level{{ $i }}"
                                                    class="form-select mt-1 block w-full">
                                                    <option value=''>Choose a level</option>
                                                        @foreach($levels as $level)
                                                            @if($level['program_id'] == $program['id'])
                                                            <option value="{{ $level->id }}">{{ $level->name }}</option>
                                                            @endif
                                                        @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @endif
                                <?php $i++; ?>
                                @endforeach
                                @endif
                            @endforeach
                        </div>
                        
                        <div class="mt-2 m-5">
                            <x-jet-label for="name" value="{{ __('Sponsor Name') }}" />
                            <x-jet-input id="name" class="block mt-1 w-full" type="text" :value="old('name')" required name="name" value="{{$sponsors->name}}"/>
                        </div>

                        <div class="mt-2 m-5">
                            <x-jet-label for="address" value="{{ __('Sponsor Address') }}" />
                            
                            <textarea id="address" style="resize:none" rows="3" class="form-textarea border rounded-md shadow-sm mt-1 w-full " name="address" :value="old('address')">{{$sponsors->address}}</textarea>
                        </div>

                        <div class="flex">
                            <div class="mt-2 m-5 w-1/2">
                                <x-jet-label for="mobile_no1" value="{{ __('Sponsor Mobile No 1') }}" />
                                <x-jet-input id="mobile_no1" class="block mt-1 w-full" type="number" name="mobile_no1"  value="{{$sponsors->mobile_no1}}"/>
                            </div>

                            <div class="mt-2 m-5 w-1/2">
                                <x-jet-label for="mobile_no2" value="{{ __('Sponsor Mobile No 2') }}" />
                                <x-jet-input id="mobile_no2" class="block mt-1 w-full" type="number" name="mobile_no2"  value="{{$sponsors->mobile_no2}}"/>
                            </div>
                        </div>

                        <div class="flex">
                            <div class="mt-2 m-5 w-1/2">
                                <x-jet-label for="email" value="{{ __('Sponsor Email') }}" />
                                <x-jet-input id="email" class="block mt-1 w-full" type="email" required name="email_id" value="{{$sponsors->email_id}}"/>
                            </div>

                            <div class="mt-2 m-5 w-1/2">
                                <x-jet-label for="dob" value="{{ __('Sponsor Date of Birth') }}" />
                                <x-jet-input id="dob" class="block mt-1 w-full" type="date" name="dob" value="{{$sponsors->dob}}"/>
                            </div>
                        </div>

                        <div class="mt-2 m-5">
                            <x-jet-label for="reference" value="{{ __('Sponsor Reference') }}" />
                            <x-jet-input id="reference" class="block mt-1 w-full" type="text" name="reference" value="{{$sponsors->reference}}"/>
                        </div>

                        <div class="mt-2 m-5">
                            <x-jet-label for="beneficiary_id" value="{{ __('Beneficiary Name') }}" />
                            <select class="form-select mt-1 block w-full" id="block mt-1 w-full" name="beneficiarie_id[]" id="beneficiarie_id" Multiple row="1">
                                <option value="">--SELECT--</option>
                                @foreach($Beneficiaries_sponsor as $ben_spo)
                                    @if($sponsors['id'] == $ben_spo['sponsor_id'])
                                        @foreach($beneficiaries as $beneficiary)
                                            @if($beneficiary['id'] == $ben_spo['beneficiarie_id'])
                                                <option value="{{$beneficiary['id']}}" selected>{{$beneficiary['name']}}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                                <option disable>--All Beneficiaries--</option>
                                @foreach($beneficiaries as $beneficiary)
                                    <option value="{{ $beneficiary->id }}">{{$beneficiary->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="text-right mt-2 m-5">
                            <x-jet-button class="">
                                {{ __('Edit') }}
                            </x-jet-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endsection
   
</x-app-layout>
