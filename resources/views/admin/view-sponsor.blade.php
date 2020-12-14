<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<x-app-layout>
    @section('sponsors','bg-gray-300')

    @section('content')
    <div class="w-full p-4">
        <div class="flex flex-col">
            <div class="overflow-x-auto sm:-ml-6 lg:-ml-8">
                <div class="py-2 align-middle inline-block min-w-full sm:pl-6 lg:pl-8 ">
                    <div class="flex items-center inline-grid grid-cols-5 gap-x-10">
                        <input type="text" name="search" id="search" onkeyup="search(this.value, 1)" placeholder="Search.." class="shadow rounded border-0 p-1"/>
                        <select class="shadow rounded border-0 p-1" name="program_id" id="program_id" onchange="search(this.value, 2)">
                            <option value="">Choose Program</option>
                            @foreach($programs as $pro)
                                <option value="{{$pro['id']}}">{{$pro['name']}}</option>
                            @endforeach
                        </select>
                        <select class="shadow rounded border-0 p-1" name="class" id="class" onchange="search(this.value, 3)">
                            <option value="">Choose Month</option>
                                <option value="01">January</option>
                                <option value="02">February</option>
                                <option value="03">March</option>
                                <option value="04">April</option>
                                <option value="05">May</option>
                                <option value="06">June</option>
                                <option value="07">July</option>
                                <option value="08">August</option>
                                <option value="09">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                        </select>
                        <select class="shadow rounded border-0 p-1" name="sponsor_id" id="sponsor_id" onchange="search(this.value, 4)">
                            <option value="">Choose Beneficiary</option>
                            @foreach($beneficiaries as $ben)
                                <option value="{{$ben['id']}}">{{$ben['name']}}</option>
                            @endforeach
                        </select>
                        <form method="get" action="{{ route('sponsor-export') }}" class="mt-2">
                            @csrf
                            <button class="btn-outline-primary add_bank_modal bg-green-100 rounded hover:bg-green-200 p-1 mt-2">
                            Download Sponsor
                            </button>
                        </form>
                    </div>
                    <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg"> 
                        <table class="min-w-full divide-y divide-gray-200 text-center">
                            <thead>
                                <tr>
                                    <th class="pl-3 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="pl-3 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
                                    <th class="pl-3 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mobile 1</th>
                                    <th class="pl-3 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mobile 2</th>
                                    <th class="pl-3 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="pl-3 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DOB</th>
                                    <th class="pl-3 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Program </th>
                                    <th class="pl-3 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Level</th>
                                    <th class="pl-3 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Beneficiaries</th>
                                    <th class="pl-3 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference</th>
                                    <th class="pl-3 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="pl-3 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Edit</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($sponsors as $sponsor)
                                <tr>
                                    <td class="pl-3 py-4 bg-white whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $sponsor->name }}
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="pl-3 py-4 bg-white whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $sponsor->address }}
                                            </div>
                                        </div>
                                    </td>

                                    <td class="pl-3 py-4 bg-white whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $sponsor->mobile_no1 }}
                                            </div>
                                        </div>
                                    </td>

                                    <td class="pl-3 py-4 bg-white whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $sponsor->mobile_no2 }}
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="pl-3 py-4 bg-white whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $sponsor->email_id }}
                                            </div>
                                        </div>
                                    </td>

                                    <td class="pl-3 py-4 bg-white whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $sponsor->dob }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="pl-3 py-4 bg-white whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="text-sm font-medium text-gray-900">
                                                @foreach($program_sponsors as $program_sponsor)
                                                    @if($program_sponsor['sponsor_id'] == $sponsor->id)
                                                        @foreach($programs as $program)
                                                            @if($program_sponsor['program_id'] == $program['id'])
                                                                {{ $program->name }}<br><hr>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </td>
                                    <td class="pl-3 py-4 bg-white whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="text-sm font-medium text-gray-900">
                                                @foreach($program_sponsors as $program_sponsor)
                                                    @if($program_sponsor['sponsor_id'] == $sponsor->id)
                                                        @foreach($levels as $level)
                                                            @if($program_sponsor['level_id'] == $level['id'])
                                                                {{ $level->name }} - {{$level->amount}}<br><hr>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </td>
                                    <td class="pl-3 py-4 bg-white whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="text-sm font-medium text-gray-900">
                                                @foreach($sponsor->beneficiaries as $beneficiary)
                                                    {{ $beneficiary->name }}<br><hr>
                                                @endforeach
                                            </div>
                                        </div>
                                    </td>

                                    <td class="pl-3 py-4 bg-white whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $sponsor->reference }}
                                            </div>
                                        </div>
                                    </td>

                                    <td class="pl-3 py-4 bg-white whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="text-sm font-medium text-gray-900">
                                                @if($sponsor->isactive === 1)
                                                    <form method="post" action="{{ route('deactive-sponsor') }}" >
                                                    @csrf
                                                        <input type="hidden" name="id" value="{{ $sponsor->id }}"/>
                                                        <button class="bg-green-100 text-xs font-bold text-green-900 px-3 py-1 rounded trasition duration-300 ease-in-out hover:bg-green-200 hover:outline-none focus:outline-none">
                                                            Active
                                                        </button>
                                                    </form>
                                                @else
                                                    <form method="post" action="{{ route('active-sponsor') }}" >
                                                    @csrf
                                                        <input type="hidden" name="id" value="{{ $sponsor->id }}"/>
                                                        <button class="bg-red-100 text-xs font-bold text-red-900 px-3 py-1 rounded trasition duration-300 ease-in-out hover:bg-red-200 hover:outline-none focus:outline-none">
                                                            Deactive
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="pl-3 py-4 bg-white whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="text-sm font-medium text-gray-900">
                                                <form method="post" action="edit-sponsor" >
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $sponsor->id }}"/>
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
            url : '{{URL::to('searchSponsor')}}',
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
            url : '{{URL::to('searchSponsor')}}',
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