<x-app-layout>
    @section('beneficiary','bg-gray-300')
    @section('content')
        <div class="py-3">
            <div class="max-w-7xl mx-auto sm:px-4 lg:px-4">
                <div class="bg-gray-50 overflow-hidden shadow-xl" style="background:white">
                    <h1 class="text-center mb-4" style="font-size:25px">Edit Beneficiary</h1>
                    <form method="POST" action="update-beneficiary">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="id" value="{{$Beneficiarie->id}}">
                        <div class="mt-2 m-5">
                            <x-jet-label for="program_id" value="{{ __('Program Name') }}" />
                            <select class="form-select mt-1 block w-full" id="block mt-1 w-full" name="program_id" id="program_id">
                                <option value="">--SELECT--</option>
                                @foreach($Program as $pro)
                                    @if($Beneficiarie['program_id'] == $pro['id'])
                                        <option value="{{$pro['id']}}" selected>{{$pro['name']}}</option>
                                    @else
                                        <option value="{{$pro['id']}}">{{$pro['name']}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-2 m-5">
                            <x-jet-label for="beneficiary_name" value="{{ __('Beneficiary Name') }}" />
                            <x-jet-input id="beneficiary_name" class="block mt-1 w-full" type="text" name="beneficiary_name" value="{{$Beneficiarie->name}}"/>
                        </div>

                        <div class="mt-2 m-5">
                            <x-jet-label for="beneficiary_address" value="{{ __('Beneficiary Address') }}" />
                            
                            <textarea id="beneficiary_address" style="resize:none" rows="3" class="form-textarea border rounded-md shadow-sm mt-1 w-full " name="beneficiary_address" :value="old('beneficiary_address')">{{$Beneficiarie->address}}</textarea>
                        </div>

                        <div class="mt-2 m-5">
                            <x-jet-label for="beneficiary_mobile_no" value="{{ __('Beneficiary Mobile No') }}" />
                            <x-jet-input id="beneficiary_mobile_no" class="block mt-1 w-full" type="text" name="beneficiary_mobile_no" value="{{$Beneficiarie->mobile_no}}"/>
                        </div>

                        <div class="mt-2 m-5">
                            <x-jet-label for="beneficiary_dob" value="{{ __('Beneficiary Date of Birth') }}" />
                            <x-jet-input id="beneficiary_dob" class="block mt-1 w-full" type="date" name="beneficiary_dob" value="{{$Beneficiarie->dob}}"/>
                        </div>

                        <div class="mt-2 m-5">
                            <x-jet-label for="beneficiary_father_name" value="{{ __('Beneficiary Father Name') }}" />
                            <x-jet-input id="beneficiary_father_name" class="block mt-1 w-full" type="text" name="beneficiary_father_name" value="{{$Beneficiarie->father_name}}"/>
                        </div>

                        <div class="mt-2 m-5">
                            <x-jet-label for="beneficiary_father_occupation" value="{{ __('Beneficiary Father Occupation') }}" />
                            <x-jet-input id="beneficiary_father_occupation" class="block mt-1 w-full" type="text" name="beneficiary_father_occupation" value="{{$Beneficiarie->father_occupation}}"/>
                        </div>

                        <div class="mt-2 m-5">
                            <x-jet-label for="beneficiary_class" value="{{ __('Beneficiary Class') }}" />
                            <x-jet-input id="beneficiary_class" class="block mt-1 w-full" type="text" name="beneficiary_class" value="{{$Beneficiarie->class}}"/>
                        </div>

                        <div class="mt-2 m-5">
                            <x-jet-label for="beneficiary_reference" value="{{ __('Beneficiary Reference') }}" />
                            <x-jet-input id="beneficiary_reference" class="block mt-1 w-full" type="text" name="beneficiary_reference" value="{{$Beneficiarie->reference}}"/>
                        </div>

                        <div class="mt-2 m-5">
                            <x-jet-label for="sponsor_id" value="{{ __('Sponsor Name') }}" />
                            <select class="form-select mt-1 block w-full" id="block mt-1 w-full" name="sponsor_id[]" id="sponsor_id" Multiple row="1">
                                <!--<option disable>--Selected Sponsor--</option>-->
                                @foreach($Beneficiaries_sponsor as $ben_spo)
                                    @if($Beneficiarie['id'] == $ben_spo['beneficiarie_id'])
                                        @foreach($Sponsor as $spo)
                                            @if($spo['id'] == $ben_spo['sponsor_id'])
                                                <option value="{{$spo['id']}}" selected>{{$spo['name']}}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                                <option disable>--All Sponsor--</option>
                                @foreach($Sponsor as $spo)
                                    <option value="{{$spo['id']}}">{{$spo['name']}}</option>
                                @endforeach
                                
                            </select>
                        </div>

                        <div class="text-right mt-2 m-5">
                            <x-jet-button class="">
                                {{ __('update') }}
                            </x-jet-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endsection
</x-app-layout>
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script type="text/javascript">
     $(document).ready(function(){
        $("#sponsor_id").select2();
    });
</script>-->