@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <a class="info-box mb-3" href="{{ route('products.index') }}">
                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-shopping-cart"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Продукты</span>
                    <span class="info-box-number">{{ $productsCount }}</span>
                </div>
                <!-- /.info-box-content -->
            </a>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
    </div>
@endsection
