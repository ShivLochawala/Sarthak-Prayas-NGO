<x-app-layout>
    @section('sponsors','bg-gray-300')
    @section('content')
    
        <div class="w-100 bg-white shadow-md m-4 p-4">
            <h1 class="text-center mb-4" style="font-size:25px">Step 1: Upload Excel Sheet of Sponsor</h1>
            @if(Session::has('success-message'))
                <div class="text-green-500 mb-2" font-size="20px">{{ session('success-message') }}</div>
            @endif
            <img src="storage/Demo-files/EnterSponsorFieldImg.jpg" class="object-contain h-60 w-full" alt="Enter Sponsor Field Img"/>
            <form method="POST" action="{{ route('sponsor-import') }}" enctype="multipart/form-data">
                @csrf
                <div class="flex">
                    <div class="mt-2 m-5 w-1/2">
                        <x-jet-label for="file" value="Upload Excel Sheet" />
                        <x-jet-input id="file" class="block mt-1 w-full" type="file" :value="old('upload')" required name="file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"/>
                    </div>

                    <div class="mt-9 m-15 w-1/2">
                        <x-jet-button class="">
                            {{ __('Upload') }}
                        </x-jet-button>
                    </div>
                </div>
            </form>
        </div>

        <div class="w-100 bg-white shadow-md m-4 p-4">
            <h1 class="text-center mb-4" style="font-size:25px">Step 2: Upload Excel Sheet of Sponsor Program Mapping</h1>
            <img src="storage/Demo-files/EnterProgramSponsorFieldImg.jpg" class="object-contain h-60 w-full" alt="Enter Sponsor Field Img"/>
            <form method="POST" action="{{ route('sponsor-program-import') }}" enctype="multipart/form-data">
                @csrf
                <div class="flex">
                    <div class="mt-2 m-5 w-1/2">
                        <x-jet-label for="file" value="Upload Excel Sheet" />
                        <x-jet-input id="file1" class="block mt-1 w-full" type="file" :value="old('upload')" required name="file1" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"/>
                    </div>

                    <div class="mt-9 m-15 w-1/2">
                        <x-jet-button class="">
                            {{ __('Upload') }}
                        </x-jet-button>
                    </div>
                </div>
            </form>
        </div>

        <div class="w-100 bg-white shadow-md m-4 p-4">
            <h1 class="text-center mb-4" style="font-size:25px">Step 3: Upload Excel Sheet of Sponsors Beneficiaries Mapping</h1>
            <img src="storage/Demo-files/EnterBeneficiarieSponsorFieldImg.jpg" class="object-contain h-60 w-full" alt="Enter Sponsor Field Img"/>
            <form method="POST" action="{{ route('beneficiarie-sponsor-import') }}" enctype="multipart/form-data">
                @csrf
                <div class="flex">
                    <div class="mt-2 m-5 w-1/2">
                        <x-jet-label for="file" value="Upload Excel Sheet" />
                        <x-jet-input id="file2" class="block mt-1 w-full" type="file" :value="old('upload')" required name="file2" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"/>
                    </div>

                    <div class="mt-9 m-15 w-1/2">
                        <x-jet-button class="">
                            {{ __('Upload') }}
                        </x-jet-button>
                    </div>
                </div>
            </form>
        </div>
    @endsection
</x-app-layout>