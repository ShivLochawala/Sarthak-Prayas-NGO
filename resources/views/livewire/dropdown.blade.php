<div>
    <div>
    {{ $ids }}
        @for($i=1; $i <= count($programs) ; $i++)
        <div class="mt-1">
            <x-jet-label for="name" value="Program {{ $i }}" />
            <select name="program{{ $i }}" wire:model="program" class="form-select mt-1 block w-full">
                <option value=''>Choose a program</option>
                @foreach($programs as $program)
                    <option value={{ $program->id }}>{{ $program->name }}</option>
                @endforeach
            </select>
        </div>
        @if(count($levels) > 0)
            <div class="mt-4">
                <x-jet-label for="name" value="{{ __('Level') }}" />
                <select name="level{{ $i }}[]" wire:model="level" Multiple
                    class="form-select mt-1 block w-full">
                    <option value=''>Choose a level</option>
                    @foreach($levels as $level)
                        <option value={{ $level->id }}>{{ $level->name }}</option>
                    @endforeach
                </select>
            </div>
        @endif
        @endfor
    </div>
</div>
