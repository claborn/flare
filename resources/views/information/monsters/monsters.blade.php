@extends('layouts.information')

@section('content')
  <div class="container-fluid">
    <div class="row page-titles">
      <div class="col-md-6 align-self-left">
        <h4 class="mt-3">Monsters</h4>
      </div>
    </div>
    <hr />
    <x-tabs.pill-tabs-container>
      @foreach($gameMapNames as $index => $gameMapName)
        @php $name = str_replace(' ', '-', $gameMapName)@endphp
        <x-tabs.tab
          tab="{{$name . '-' . $index}}"
          selected="{{$index === 0 ? 'true' : 'false'}}"
          active="{{$index === 0 ? 'true' : 'false'}}"
          title="{{$gameMapName}}"
        />
      @endforeach
    </x-tabs.pill-tabs-container>
    <x-tabs.tab-content>
      @foreach($gameMapNames as $index => $gameMapName)
        @php $name = str_replace(' ', '-', $gameMapName)@endphp
        <x-tabs.tab-content-section
          tab="{{$name . '-' . $index}}"
          active="{{$index === 0 ? 'true' : 'false'}}"
        >
          <x-cards.card>
            <x-tabs.pill-tabs-container>
              <x-tabs.tab
                tab="{{$name . '-' . $index . '-monsters'}}"
                selected="true"
                active="true"
                title="Monsters"
              />
              <x-tabs.tab
                tab="{{$name . '-' . $index . '-celestials'}}"
                selected="false"
                active="false"
                title="Celestials"
              />
            </x-tabs.pill-tabs-container>
            <x-tabs.tab-content>
              <x-tabs.tab-content-section
                tab="{{$name . '-' . $index . '-monsters'}}"
                active="true"
              >
                @livewire('admin.monsters.data-table', [
                    'onlyMapName' => $gameMapName,
                ])
              </x-tabs.tab-content-section>
              <x-tabs.tab-content-section
                tab="{{$name . '-' . $index . '-celestials'}}"
                active="false"
              >
                @livewire('admin.monsters.data-table', [
                    'onlyMapName' => $gameMapName,
                    'withCelestials' => true,
                ])
              </x-tabs.tab-content-section>
            </x-tabs.tab-content>
          </x-cards.card>
        </x-tabs.tab-content-section>
      @endforeach
    </x-tabs.tab-content>
  </div>
@endsection