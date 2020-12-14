
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<x-app-layout>
    @section('beneficiary','bg-gray-300')
    @section('content')
    <div class="w-full p-4">
        <div class="flex flex-col">
            <div class="overflow-x-auto sm:-ml-6 lg:-ml-8">
                <div class="py-2 align-middle inline-block min-w-full sm:pl-6 lg:pl-8 ">
                    <div class="flex items-center inline-grid grid-cols-5 gap-x-10">
                        <input type="text" name="search" id="search" onkeyup="search(this.value, 1)" placeholder="Search.." class="shadow rounded border-0 p-1"/>
                        <select class="shadow rounded border-0 p-1" name="program_id" id="program_id" onchange="search(this.value, 2)">
                            <option value="">Choose Program</option>
                            @foreach($Program as $pro)
                                <option value="{{$pro['id']}}">{{$pro['name']}}</option>
                            @endforeach
                        </select>
                        <select class="shadow rounded border-0 p-1" name="class" id="class" onchange="search(this.value, 3)">
                            <option value="">Choose Class</option>
                            @for($i = 1; $i <=12; $i++)
                                <option value="{{$i}}">{{$i}} Class</option>
                            @endfor
                        </select>
                        <select class="shadow rounded border-0 p-1" name="sponsor_id" id="sponsor_id" onchange="search(this.value, 4)">
                            <option value="">Choose Sponsor</option>
                            @foreach($Sponsor as $Spon)
                                <option value="{{$Spon['id']}}">{{$Spon['name']}}</option>
                            @endforeach
                        </select>
                        <form method="get" action="{{ route('beneficiarie-export') }}" class="mt-2">
                            @csrf
                            <button class="btn-outline-primary add_bank_modal bg-green-100 rounded hover:bg-green-200 p-1 mt-2">
                            Download Beneficiarie
                            </button>
                        </form>
                    </div>
                    <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg"> 
                        <table class="min-w-full divide-y divide-gray-200 text-center">
                            <thead>
                                <tr>
                                    <th class="pl-3 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Program</th>
                                    <th class="pl-3 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="pl-3 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
                                    <th class="pl-3 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mobile No</th>
                                    <th class="pl-3 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date of Birth</th>
                                    <th class="pl-3 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Father Name</th>
                                    <th class="pl-3 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Father Occupation</th>
                                    <th class="pl-3 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class</th>
                                    <th class="pl-3 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference</th>
                                    <th class="pl-3 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sponsor</th>
                                    <th class="pl-3 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="pl-3 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Edit</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($Beneficiarie as $beneficiary)
                                <tr>
                                    @foreach($Program as $pro)
                                        @if($pro['id'] == $beneficiary['program_id'])
                                        <td class="pl-3 py-4 bg-white whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{$pro['name']}}
                                                </div>
                                            </div>
                                        </td>
                                        @endif
                                    @endforeach
                                    <td class="pl-3 py-4 bg-white whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{$beneficiary['name']}}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="pl-3 py-4 bg-white whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{$beneficiary['address']}}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="pl-3 py-4 bg-white whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{$beneficiary['mobile_no']}}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="pl-3 py-4 bg-white whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{$beneficiary['dob']}}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="pl-3 py-4 bg-white whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{$beneficiary['father_name']}}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="pl-3 py-4 bg-white whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{$beneficiary['father_occupation']}}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="pl-3 py-4 bg-white whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{$beneficiary['class']}}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="pl-3 py-4 bg-white whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{$beneficiary['reference']}}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="pl-3 py-4 bg-white whitespace-nowrap">
                                    @foreach($Beneficiaries_sponsor as $ben_spo)
                                        @if($ben_spo['beneficiarie_id'] == $beneficiary['id'])
                                            @foreach($Sponsor as $sp)
                                                @if($sp['id'] == $ben_spo['sponsor_id'])
                                                    <div class="flex items-center">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{$sp['name']}}      
                                                        </div>
                                                    </div>
                                                    <hr>
                                                @endif
                                            @endforeach
                                        @endif
                                    @endforeach
                                    </td>
                                    <td class="pl-3 py-4 bg-white whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="text-sm font-medium text-gray-900">
                                            @if($beneficiary['isactive'] === 1)
                                                <a href='beneficiaryActiveOrDeactive/{{$beneficiary["id"]}}/0'><button class='bg-green-100 text-xs font-bold text-green-900 px-3 py-1 rounded trasition duration-300 ease-in-out hover:bg-green-200 hover:outline-none focus:outline-none' name='active'>Active</button></a>
                                            @else 
                                                <a href='beneficiaryActiveOrDeactive/{{$beneficiary["id"]}}/1'><button class="bg-red-100 text-xs font-bold text-red-900 px-3 py-1 rounded trasition duration-300 ease-in-out hover:bg-red-200 hover:outline-none focus:outline-none" name='deactive'>Deactive</button></a>
                                            @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="pl-3 py-4 bg-white whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="text-sm font-medium text-gray-900">
                                                <form method="post" action="{{ route('edit-beneficiary') }}" >
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $beneficiary->id }}"/>
                                                    <button class="btn-outline-primary transition duration-300 ease-in-out focus:outline-none focus:shadow-outline border border-purple-700 hover:bg-purple-700 text-purple-700 hover:text-white font-normal py-1 px-2 rounded">
                                                        Edit
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                    
                                </tr>
                            @endforeach
                            </tbody>
        
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection
</x-app-layout>
<script type="text/javascript">
    /*$('#search').on('keyup',function(){
        $value=$(this).val();
        $.ajax({
            type : 'get',
            url : '{{URL::to('searchBeneficiarie')}}',
            data:{'search':$value},
            success:function(data){
                $('tbody').html(data);
            }
        });
    })*/
    function search(value, type){
        $value= value;
        $.ajax({
            type : 'get',
            url : '{{URL::to('searchBeneficiarie')}}',
            data:{'search':$value, type},
            success:function(data){
                $('tbody').html(data);
            }
        });
    }
</script>
<script type="text/javascript">
    $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });
</script>