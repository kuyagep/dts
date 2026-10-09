@extends('layouts.main')

@section('title', 'Document Dashboard')

@section('content')
    <h1 class="h3 mb-3">Document Tracking Overview</h1>

    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Pending Documents</h5>
                    <h1 class="mt-1 mb-3">12</h1>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">In Review</h5>
                    <h1 class="mt-1 mb-3">5</h1>
                </div>
            </div>
        </div>
    </div>
@endsection
