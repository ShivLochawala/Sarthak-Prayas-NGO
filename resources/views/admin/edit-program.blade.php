<x-app-layout>
    @section('program','bg-gray-300')
    @section('content')

        <div class="w-100 bg-white shadow-md m-4 p-4">
            <h1 class="text-center mb-4" style="font-size:25px">Edit Program</h1>
            @if(Session::has('success-message'))
                <div class="text-green-500 mb-2" font-size="20px">{{ session('success-message') }}</div>
            @endif
            <form method="POST" action="{{ route('update-program') }}">
                @csrf
                @method('PATCH')
    
                <input id="program-id" type="hidden" name="id" value="{{ $program->id }}" />

                <div>
                    <x-jet-label for="program-name" value="{{ __('Program Name') }}" />
                    <x-jet-input id="program-name" class=" mt-1 w-full" type="text" name="name" value="{{ $program->name }}" required autofocus autocomplete="program-name" />
                    @error('program-name')
                        <div class="text-red-500">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-4">
                    <x-jet-label for="program-desc" value="{{ __('Program Description') }}" />
                    <textarea id="program-desc" style="resize:none" rows="3" class="form-textarea border rounded-md shadow-sm mt-1 w-full " name="desc" required >{{ $program->desc }}</textarea>
                </div>

                <div class="mt-3 levels" >
                    <div class="flex justify-between">
                        <x-jet-label for="program-levels" class="mb-2" style="font-size:18px;font-weight:bold" value="{{ __('Program Levels') }}" />
                        <button type="button" class="add_btn btn-outline transition duration-300 ease-in-out bg-purple-700 hover:outline-none hover:bg-purple-500 focus:outline-none focus:bg-purple-500 rounded px-3 text-gray-100">Add Level</button>
                    </div>
                    <?php $j= 1; 
                        $count =0?>
                    @foreach($levels as $lev)
                        @if($lev['program_id']== $program->id)
                        
                        <div class="flex mt-2 level_container items-center" data-id="{{ $j }}">
                        <div class="w-1/2 mr-4">
                            <x-jet-label for="program-level-{{ $j }}" value="Level {{  $j }}" />
                            <x-jet-input id="program-level-{{ $j }}" class="block mt-1 w-full" type="text" name="program-level-{{ $j }}" autocomplete="new-program-level-{{ $j }}" value="{{ $lev['name'] }}" />
                        </div>
                        <div class="w-1/2">
                            <x-jet-label for="program-amount-{{ $j }}" value="Amount {{ $j }}" />
                            <x-jet-input id="program-amount-{{ $j }}" class="block mt-1 w-full" type="number" name="program-amount-{{ $j }}" autocomplete="new-program-levels" value="{{ $lev['amount'] }}" />
                        </div>
                        </div>
                        <?php $j++; 
                            $count++;
                        ?>
                        @endif
                    @endforeach
                    <input type="hidden" class="count_input" value="{{ $count }}" name="count"/>
                </div>

                <div class="mt-4">
                    <x-jet-label for="program-frequency" value="{{ __('Program frequency') }}" />
                    <select id="program-frequency" class="form-select block mt-1 w-full"  name="frequency" required autocomplete="new-program-frequency">
                        <option value="">-- Select Month --</option>
                        @for($i=1;$i < 13;$i++)
                            @if($i==$program->frequency)
                                <option class="form-option" value="{{ $i }}" selected>{{ $i }} Month</option>
                            @endif
                            @if($i==1)
                                <option class="form-option" value="{{ $i }}">{{ $i }} Month</option>
                            @else
                                <option class="form-option" value="{{ $i }}">{{ $i }} Months</option>
                            @endif
                        @endfor
                    </select>
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-jet-button class="ml-4">
                        {{ __('Update') }}
                    </x-jet-button>
                </div>
            </form>
        </div>
    @endsection

    @push('script')
    <script>
        const levels = document.querySelector('.levels') 
        const add_btn = document.querySelector('.add_btn')

        add_btn.addEventListener('click',()=>{
            const count_input = document.querySelector('.count_input')
            const count = parseInt(count_input.value) + 1
            
            const level_container = document.createElement('div')
            level_container.classList.add('flex')
            level_container.classList.add('mt-2')
            level_container.innerHTML = "<div class='w-1/2 mr-4 level_container' data-id='"+count+"'>"+
                                "<label class='block font-medium text-sm text-gray-700' for='program-level-"+count+"' >Level "+count+"</label>"+
                                "<input class='form-input rounded-md shadow-sm block mt-1 w-full' id='program-level-"+count+"' class='block mt-1 w-full' type='text' name='program-level-"+count+"' required autocomplete='new-program-level-1' />"+
                            "</div>"+
                            "<div class='w-1/2'>"+
                                "<label class='block font-medium text-sm text-gray-700' for='program-level-"+count+"' >Amount "+count+"</label>"+
                                "<input class='form-input rounded-md shadow-sm block mt-1 w-full' id='program-amount-"+count+"' class='block mt-1 w-full' type='number' name='program-amount-"+count+"' required autocomplete='new-program-levels' />"+
                            "</div>"

            levels.append(level_container)

            count_input.value = count
        })        

        </script>
</x-app-layout>

