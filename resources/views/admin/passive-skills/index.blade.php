@extends('layouts.app')

@section('content')
  <div class="mt-10 mb-10 w-full lg:w-3/5 m-auto">
    <x-core.page-title
      title="Passive Skills"
      route="{{route('home')}}"
      color="success" link="Home"
    >
      <x-core.buttons.link-buttons.primary-button
        href="{{route('passive.skills.create')}}"
<<<<<<< HEAD
        css="ml-5"
=======
        css="tw-ml-2"
>>>>>>> 1.1.10
      >
        Create Passive
      </x-core.buttons.link-buttons.primary-button>
      <x-core.buttons.link-buttons.primary-button
        href="{{route('passive.skills.export')}}"
        css="tw-ml-2"
      >
        <i class="fas fa-file-export"></i> Export
      </x-core.buttons.link-buttons.primary-button>
      <x-core.buttons.link-buttons.primary-button
        href="{{route('passive.skills.import')}}"
        css="tw-ml-2"
      >
        <i class="fas fa-file-upload"></i> Import
      </x-core.buttons.link-buttons.primary-button>
    </x-core.page-title>
    <hr />
    @livewire('admin.passive-skills.data-table')
  </div>
@endsection