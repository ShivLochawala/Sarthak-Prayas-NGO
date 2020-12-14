<x-app-layout>
    @section('bank','bg-gray-300')
    @section('content')
    <div class="w-100 bg-white shadow-md m-4 p-4">
            <div class="flex justify-between items-center">
                <h1 class="text-center" style="font-size:25px">Transfer Mode Added</h1>
                <button type="button" class="btn-outline-primary add_mode_modal px-4 bg-green-100 py-1 rounded hover:bg-green-200">Add Mode</button>
            </div>
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
                                    <th class="pl-3 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transfer Modes</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($modes as $mode)
                                <tr>
                                    <td class="pl-3 py-4 bg-white whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $mode->name }}
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
        </div>
    @endsection
        @livewire('model2',['name'=>'mode_modal','btn_name'=>'add_mode_modal'])
    
</x-app-layout>