@extends('layouts.app')

@section('title', 'مطابقة البيانات')

@section('content')
    <!-- Custom CSS styling block -->
    @include('data.differentiation.partials._styles')

    <!-- Page Header & Tab Navigation -->
    <div class="page-header">
        <h1 class="page-title"><i class="fas fa-code-compare me-2"></i>مطابقة البيانات</h1>
        <div class="nav-tabs-custom">
            <a href="{{ route('data.import.index') }}" class="nav-tab">
                <i class="fas fa-table me-1"></i> البيانات الرئيسية
            </a>
            <a href="{{ route('data.differentiation.index') }}" class="nav-tab active">
                <i class="fas fa-code-compare me-1"></i> المطابقة
            </a>
        </div>
    </div>

    <!-- Main Content & Sidebar Grid Layout -->
    <div class="diff-layout">
        @include('data.differentiation.partials._main_content')
        @include('data.differentiation.partials._sidebar')
    </div>

    <!-- Add Column & Matching Progress Modals -->
    @include('data.differentiation.partials._modals')

    <!-- Interactive JS handlers, Simulators, CSV Export & Printing Engine -->
    @include('data.differentiation.partials._scripts')
@endsection


