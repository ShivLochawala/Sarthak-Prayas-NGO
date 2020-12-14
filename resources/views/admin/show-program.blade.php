<x-app-layout>
    @section('program','bg-gray-300')

    @section('content')
    <div class="w-full p-4">
        <div class="flex flex-col">
            <div class="overflow-x-auto sm:-ml-6 lg:-ml-8">
                <div class="py-2 align-middle inline-block min-w-full sm:pl-6 lg:pl-8 ">
                    @foreach(['deactive-message','active-message','success-message'] as $msg)
                        @if(Session::has($msg))
                            <div class="text-green-500 mb-2" font-size="20px">{{ session($msg) }}</div>
                        @endif
                    @endforeach
                    <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg"> 
                        <table class="min-w-full divide-y divide-gray-200 text-center">
                            <thead>
                                <tr>
                                    <th class="pl-3 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="pl-3 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                    <th class="pl-3 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Levels</th>
                                    <th class="pl-3 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="pl-3 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Frequency</th>
                                    <th class="pl-3 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="pl-3 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($programs_array as $program)
                                <tr>
                                    <td class="pl-3 py-4 bg-white whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $program->name }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="pl-3 py-4 bg-white whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $program->desc }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="pl-3 py-4 bg-white whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="text-sm font-medium text-gray-900">
                                                @foreach($levels as $lev)
                                                    @if($lev['program_id']==$program->id)
                                                        {{$lev['name']}}
                                                    @endif
                                                    <br>
                                                @endforeach
                                            </div>
                                        </div>
                                    </td>
                                    <td class="pl-3 py-4 bg-white whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="text-sm font-medium text-gray-900">
                                                @foreach($levels as $lev)
                                                    @if($lev['program_id']==$program->id)
                                                        {{$lev['amount']}}
                                                    @endif
                                                    <br>
                                                @endforeach
                                            </div>
                                        </div>
                                    </td>
                                    <td class="pl-3 py-4 bg-white whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $program->frequency }} Months 
                                            </div>
                                        </div>
                                    </td>
                                    <td class="pl-3 py-4 bg-white whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="text-sm font-medium text-gray-900">
                                                @if($program->isactive === 1)
                                                    <form method="post" action="{{ route('deactive-program') }}" >
                                                    @csrf
                                                        <input type="hidden" name="id" value="{{ $program->id }}"/>
                                                        <button class="bg-green-100 text-xs font-bold text-green-900 px-3 py-1 rounded trasition duration-300 ease-in-out hover:bg-green-200 hover:outline-none focus:outline-none">
                                                            Active
                                                        </button>
                                                    </form>
                                                @else
                                                    <form method="post" action="{{ route('active-program') }}" >
                                                    @csrf
                                                        <input type="hidden" name="id" value="{{ $program->id }}"/>
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
                                                <form method="post" action="{{ route('edit-program') }}" >
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $program->id }}"/>
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